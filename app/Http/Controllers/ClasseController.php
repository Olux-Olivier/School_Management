<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE PAR SECTION
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        return redirect()->route(
            'classes.maternelle',
            $request->only('search')
        );
    }

    public function maternelle(Request $request)
    {
        return $this->listeParSection(
            $request,
            'Maternelle',
            'classe.maternelle'
        );
    }

    public function primaire(Request $request)
    {
        return $this->listeParSection(
            $request,
            'Primaire',
            'classe.primaire'
        );
    }

    public function secondaire(Request $request)
    {
        return $this->listeParSection(
            $request,
            'Secondaire',
            'classe.secondaire'
        );
    }

    public function humanites(Request $request)
    {
        return $this->listeParSection(
            $request,
            'Humanités',
            'classe.humanites'
        );
    }

    private function listeParSection(
        Request $request,
        string $section,
        string $vue
    ) {
        $search = trim($request->search ?? '');

        $classes = Classe::query()
            ->where('section', $section)

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

        return view($vue, compact(
            'classes',
            'section',
            'search'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | AJOUT
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $section = $request->get('section');

        $sections = [
            'Maternelle',
            'Primaire',
            'Secondaire',
            'Humanités',
        ];

        // Si la section envoyée n'est pas valide,
        // on laisse l'utilisateur choisir.
        if (!in_array($section, $sections)) {
            $section = null;
        }

        return view('classe.create', compact(
            'section',
            'sections'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'niveau' => 'required|integer|between:0,3',
            'section' => 'required|string|max:100',
            'option' => 'nullable|string|max:100',
            'variante' => [
                'nullable',
                'string',
                'size:1',
                Rule::in([
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H'
                ])
            ],
            'actif' => 'required|boolean',
        ], [
            'nom.required' => 'Le nom de la classe est obligatoire.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.between' => 'Le niveau sélectionné est invalide.',
            'section.required' => 'La section est obligatoire.',
            'variante.in' => 'La variante sélectionnée est invalide. Choisissez une lettre de A à H.',
            'variante.size' => 'La variante doit contenir une seule lettre.',
        ]);

        $option = null;

        /*
        |--------------------------------------------------------------------------
        | HUMANITÉS
        |--------------------------------------------------------------------------
        */

        if ((int) $request->niveau === 3) {

            $request->validate([
                'option' => 'required|string|max:100',
            ], [
                'option.required' =>
                    'L’option est obligatoire pour les Humanités.',
            ]);

            $option = trim($request->option);
        }

        /*
        |--------------------------------------------------------------------------
        | VARIANTE
        |--------------------------------------------------------------------------
        */

        $variante = $request->filled('variante')
            ? strtoupper(trim($request->variante))
            : null;


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION DOUBLON
        |--------------------------------------------------------------------------
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
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        Classe::create([
            'nom' => trim($request->nom),
            'niveau' => $request->niveau,
            'section' => $request->section,
            'option' => $option,
            'variante' => $variante,
            'actif' => $request->actif,
            'created_by' => auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | RETOUR VERS LA BONNE SECTION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route($this->routeSection($request->section))
            ->with(
                'success',
                'Classe enregistrée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ROUTE DE LA SECTION
    |--------------------------------------------------------------------------
    */

    private function routeSection(string $section): string
    {
        return match ($section) {
            'Maternelle' => 'classes.maternelle',
            'Primaire' => 'classes.primaire',
            'Secondaire' => 'classes.secondaire',
            'Humanités' => 'classes.humanites',
            default => 'classes.maternelle',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, Classe $classe)
    {
        $anneesScolaires = AnneeScolaire::orderByDesc('id')->get();

        $anneeScolaire = $request->filled('annee_scolaire_id')
            ? AnneeScolaire::findOrFail(
                $request->annee_scolaire_id
            )
            : AnneeScolaire::where('actif', true)->firstOrFail();

        $inscriptions = $classe->inscriptions()
            ->where(
                'annee_scolaire_id',
                $anneeScolaire->id
            )
            ->with('eleve')
            ->latest('id')
            ->paginate(25);

        return view('classe.show', compact(
            'classe',
            'anneesScolaires',
            'anneeScolaire',
            'inscriptions'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Classe $classe)
    {
        return view('classe.form', compact('classe'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'niveau' => 'required|integer|between:0,3',
            'section' => 'required|string|max:100',
            'option' => 'nullable|string|max:100',
            'variante' => [
                'nullable',
                'string',
                'size:1',
                Rule::in([
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H'
                ])
            ],
            'actif' => 'required|boolean',
        ], [
            'nom.required' => 'Le nom de la classe est obligatoire.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.between' => 'Le niveau sélectionné est invalide.',
            'section.required' => 'La section est obligatoire.',
            'variante.in' => 'La variante sélectionnée est invalide.',
            'variante.size' => 'La variante doit contenir une seule lettre.',
        ]);


        $option = null;

        if ((int) $request->niveau === 3) {

            $request->validate([
                'option' => 'required|string|max:100',
            ], [
                'option.required' =>
                    'L’option est obligatoire pour les Humanités.',
            ]);

            $option = trim($request->option);
        }


        $variante = $request->filled('variante')
            ? strtoupper(trim($request->variante))
            : null;


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


        $classe->update([
            'nom' => trim($request->nom),
            'niveau' => $request->niveau,
            'section' => $request->section,
            'option' => $option,
            'variante' => $variante,
            'actif' => $request->actif,
            'updated_by' => auth()->id(),
        ]);


        return redirect()
            ->route($this->routeSection($request->section))
            ->with(
                'success',
                'Classe modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVATION / DÉSACTIVATION
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Classe $classe)
    {
        $classe->actif = !$classe->actif;
        $classe->updated_by = auth()->id();
        $classe->save();

        return response()->json([
            'success' => true,
            'message' => $classe->actif
                ? 'Classe activée avec succès.'
                : 'Classe désactivée avec succès.',
            'actif' => $classe->actif,
            'statut' => $classe->statut_libelle,
        ]);
    }
}
