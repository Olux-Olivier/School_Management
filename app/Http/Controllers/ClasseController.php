<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\AnneeScolaire;
use App\Models\Inscription;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Liste des classes
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $classes = Classe::query()
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('nom', 'like', "%{$search}%")
                        ->orWhere('section', 'like', "%{$search}%")
                        ->orWhere('option', 'like', "%{$search}%")
                        ->orWhere('variante', 'like', "%{$search}%");

                });

            })
            ->orderBy('niveau')
            ->orderBy('nom')
            ->orderBy('option')
            ->orderBy('variante')
            ->get();

        return view('classe.index', compact('classes'));
    }


    /*
    |--------------------------------------------------------------------------
    | Formulaire d'ajout
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('classe.create');
    }


    /*
    |--------------------------------------------------------------------------
    | Enregistrer une classe
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'nom' =>
                'required|string|max:100',

            'niveau' =>
                'required|integer|between:0,3',

            'section' =>
                'required|string|max:100',

            'option' =>
                'nullable|string|max:100',

            'variante' =>
                ['nullable', 'string', 'size:1', Rule::in([
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H',
                ])],

            'actif' =>
                'required|boolean',

        ], [

            'nom.required' =>
                'Le nom de la classe est obligatoire.',

            'niveau.required' =>
                'Le niveau est obligatoire.',

            'niveau.between' =>
                'Le niveau sélectionné est invalide.',

            'section.required' =>
                'La section est obligatoire.',

            'variante.in' =>
                'La variante sélectionnée est invalide. Choisissez une lettre de A à H.',

            'variante.size' =>
                'La variante doit contenir une seule lettre.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Option uniquement pour les Humanités
        |--------------------------------------------------------------------------
        */

        $option = null;

        if ((int) $request->niveau === 3) {

            $request->validate([

                'option' =>
                    'required|string|max:100',

            ], [

                'option.required' =>
                    'L’option est obligatoire pour les Humanités.',

            ]);

            $option = trim($request->option);
        }


        /*
        |--------------------------------------------------------------------------
        | Variante
        |--------------------------------------------------------------------------
        */

        $variante = $request->filled('variante')
            ? strtoupper(trim($request->variante))
            : null;


        /*
        |--------------------------------------------------------------------------
        | Vérifier les doublons
        |--------------------------------------------------------------------------
        |
        | Une même combinaison ne peut pas être enregistrée deux fois :
        |
        | 1ère + Humanités + Commerciale + A
        |
        | Mais ceci reste possible :
        |
        | 1ère + Humanités + Commerciale + A
        | 1ère + Humanités + Commerciale + B
        |
        */

        $doublon = Classe::query()
            ->where('nom', $request->nom)
            ->where('niveau', $request->niveau)
            ->where('section', $request->section)
            ->where(function ($query) use ($option) {

                if ($option === null) {
                    $query->whereNull('option');
                } else {
                    $query->where('option', $option);
                }

            })
            ->where(function ($query) use ($variante) {

                if ($variante === null) {
                    $query->whereNull('variante');
                } else {
                    $query->where('variante', $variante);
                }

            })
            ->exists();


        if ($doublon) {

            return back()
                ->withInput()
                ->withErrors([
                    'variante' =>
                        'Cette classe existe déjà avec cette variante.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        Classe::create([

            'nom' =>
                $request->nom,

            'niveau' =>
                $request->niveau,

            'section' =>
                $request->section,

            'option' =>
                $option,

            'variante' =>
                $variante,

            'actif' =>
                $request->actif,

            'created_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route('classes.index')
            ->with(
                'success',
                'Classe enregistrée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Consultation
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, Classe $classe)
    {
        $anneesScolaires =
            AnneeScolaire::orderByDesc('id')->get();

        $anneeScolaire =
            $request->filled('annee_scolaire_id')
                ? AnneeScolaire::findOrFail(
                    $request->annee_scolaire_id
                )
                : AnneeScolaire::where(
                    'actif',
                    true
                )->firstOrFail();

        $inscriptions = $classe->inscriptions()
            ->where(
                'annee_scolaire_id',
                $anneeScolaire->id
            )
            ->with('eleve')
            ->latest('id')
            ->paginate(25);

        return view(
            'classe.show',
            compact(
                'classe',
                'anneesScolaires',
                'anneeScolaire',
                'inscriptions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Formulaire de modification
    |--------------------------------------------------------------------------
    */

    public function edit(Classe $classe)
    {
        return view(
            'classe.form',
            compact('classe')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Modifier une classe
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Classe $classe
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'nom' =>
                'required|string|max:100',

            'niveau' =>
                'required|integer|between:0,3',

            'section' =>
                'required|string|max:100',

            'option' =>
                'nullable|string|max:100',

            'variante' =>
                ['nullable', 'string', 'size:1', Rule::in([
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H',
                ])],

            'actif' =>
                'required|boolean',

        ], [

            'nom.required' =>
                'Le nom de la classe est obligatoire.',

            'niveau.required' =>
                'Le niveau est obligatoire.',

            'niveau.between' =>
                'Le niveau sélectionné est invalide.',

            'section.required' =>
                'La section est obligatoire.',

            'variante.in' =>
                'La variante sélectionnée est invalide. Choisissez une lettre de A à H.',

            'variante.size' =>
                'La variante doit contenir une seule lettre.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Gestion de l'option
        |--------------------------------------------------------------------------
        */

        $option = null;

        if ((int) $request->niveau === 3) {

            $request->validate([

                'option' =>
                    'required|string|max:100',

            ], [

                'option.required' =>
                    'L’option est obligatoire pour les Humanités.',

            ]);

            $option = trim($request->option);
        }


        /*
        |--------------------------------------------------------------------------
        | Gestion de la variante
        |--------------------------------------------------------------------------
        */

        $variante = $request->filled('variante')
            ? strtoupper(trim($request->variante))
            : null;


        /*
        |--------------------------------------------------------------------------
        | Vérifier les doublons
        |--------------------------------------------------------------------------
        */

        $doublon = Classe::query()
            ->where('id', '!=', $classe->id)
            ->where('nom', $request->nom)
            ->where('niveau', $request->niveau)
            ->where('section', $request->section)
            ->where(function ($query) use ($option) {

                if ($option === null) {
                    $query->whereNull('option');
                } else {
                    $query->where('option', $option);
                }

            })
            ->where(function ($query) use ($variante) {

                if ($variante === null) {
                    $query->whereNull('variante');
                } else {
                    $query->where('variante', $variante);
                }

            })
            ->exists();


        if ($doublon) {

            return back()
                ->withInput()
                ->withErrors([
                    'variante' =>
                        'Cette classe existe déjà avec cette variante.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */

        $classe->update([

            'nom' =>
                $request->nom,

            'niveau' =>
                $request->niveau,

            'section' =>
                $request->section,

            'option' =>
                $option,

            'variante' =>
                $variante,

            'actif' =>
                $request->actif,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route('classes.index')
            ->with(
                'success',
                'Classe modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Activer / désactiver
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Classe $classe)
    {
        $classe->actif =
            !$classe->actif;

        $classe->updated_by =
            auth()->id();

        $classe->save();


        return response()->json([

            'success' =>
                true,

            'message' =>
                $classe->actif
                    ? 'Classe activée avec succès.'
                    : 'Classe désactivée avec succès.',

            'actif' =>
                $classe->actif,

            'statut' =>
                $classe->statut_libelle,

        ]);
    }
}
