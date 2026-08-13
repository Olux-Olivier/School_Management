<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InscriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Liste des inscriptions
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $inscriptions = Inscription::with([
            'eleve',
            'classe',
            'anneeScolaire'
        ])
        ->when($search, function ($query) use ($search) {

            $query->whereHas('eleve', function ($q) use ($search) {

                $q->where('matricule', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('postnom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");

            });

        })
        ->latest()
        ->paginate(25)
        ->withQueryString();

        return view(
            'inscriptions.index',
            compact(
                'inscriptions',
                'search'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Formulaire d'inscription
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        // Récupérer uniquement l'année scolaire active
        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->first();

        return view('inscriptions.create', compact(
            'anneeScolaire'
        ));

    }


    /*
    |--------------------------------------------------------------------------
    | Enregistrer l'inscription
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation des données du formulaire
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'eleve_id' => [
                'required',
                'exists:eleves,id',
            ],

            'section' => [
                'required',
                'in:maternelle,primaire,secondaire,humanites',
            ],

            'classe_id' => [
                'required',
                'exists:classes,id',
            ],

            'date_inscription' => [
                'required',
                'date',
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Récupérer l'année scolaire active
        |--------------------------------------------------------------------------
        |
        | On ne fait absolument pas confiance à une éventuelle valeur
        | annee_scolaire_id envoyée par le navigateur.
        |
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->first();


        if (!$anneeScolaire) {

            return back()
                ->withInput()
                ->withErrors([
                    'annee_scolaire_id' =>
                        'Aucune année scolaire active n’est disponible.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'élève
        |--------------------------------------------------------------------------
        |
        | L'élève doit exister et être actif.
        |
        */

        $eleve = Eleve::where('id', $request->eleve_id)
            ->where('actif', true)
            ->first();


        if (!$eleve) {

            return back()
                ->withInput()
                ->withErrors([
                    'eleve_id' =>
                        'Cet élève est introuvable ou inactif.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        |
        | Seules les classes actives peuvent recevoir une inscription.
        |
        */

        $classe = Classe::where('id', $request->classe_id)
            ->where('actif', true)
            ->first();


        if (!$classe) {

            return back()
                ->withInput()
                ->withErrors([
                    'classe_id' =>
                        'Cette classe est inexistante ou inactive.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la cohérence entre la classe et la section
        |--------------------------------------------------------------------------
        */

        $sectionClasse = strtolower(
            trim($classe->section)
        );

        $sectionFormulaire = strtolower(
            trim($request->section)
        );


        /*
        | Normalisation de "humanités" / "humanites"
        */

        $sectionClasse = str_replace(
            ['é', 'è', 'ê', 'ë'],
            'e',
            $sectionClasse
        );

        $sectionFormulaire = str_replace(
            ['é', 'è', 'ê', 'ë'],
            'e',
            $sectionFormulaire
        );


        if ($sectionClasse !== $sectionFormulaire) {

            return back()
                ->withInput()
                ->withErrors([
                    'classe_id' =>
                        'La classe sélectionnée ne correspond pas à la section choisie.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier si l'élève est déjà inscrit dans l'année active
        |--------------------------------------------------------------------------
        */

        $dejaInscrit = Inscription::where(
            'eleve_id',
            $eleve->id
        )
        ->where(
            'annee_scolaire_id',
            $anneeScolaire->id
        )
        ->exists();


        if ($dejaInscrit) {

            return back()
                ->withInput()
                ->withErrors([
                    'eleve_id' =>
                        'Cet élève est déjà inscrit pour l’année scolaire active.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Création de l'inscription
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::create([

            'eleve_id' =>
                $eleve->id,

            'annee_scolaire_id' =>
                $anneeScolaire->id,

            'classe_id' =>
                $classe->id,

            'date_inscription' =>
                $request->date_inscription,

            'montant' =>
                $request->montant,

            'actif' =>
                true,

            'created_by' =>
                auth()->id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirection vers la consultation
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'inscriptions.show',
                $inscription
            )
            ->with(
                'success',
                'Inscription enregistrée avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Consultation
    |--------------------------------------------------------------------------
    */

    public function show(Inscription $inscription)
    {
        $inscription->load([
            'eleve',
            'classe',
            'anneeScolaire',
            'createdBy',
            'updatedBy',
        ]);

        return view(
            'inscriptions.show',
            compact('inscription')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Recherche d'élèves pour l'inscription
    |--------------------------------------------------------------------------
    */

    public function searchEleves(Request $request)
    {
        $search = trim($request->search);

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $eleves = Eleve::where('actif', true)
            ->where(function ($query) use ($search) {

                $query->where('matricule', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('postnom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");

            })
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->limit(10)
            ->get([
                'id',
                'matricule',
                'nom',
                'postnom',
                'prenom'
            ]);

        return response()->json($eleves);
    }

    public function classes(Request $request)
    {
        $request->validate([
            'section' => [
                'required',
                'in:maternelle,primaire,secondaire,humanites',
            ],
        ]);


        $classes = Classe::where('actif', true)
            ->where('section', $request->section)
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get([
                'id',
                'nom',
                'niveau',
                'section',
                'option',
            ]);


        return response()->json(
            $classes->map(function ($classe) {

                return [
                    'id' => $classe->id,

                    'nom_complet' =>
                        $classe->nom_complet,
                ];

            })
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Génération du PDF de l'inscription
    |--------------------------------------------------------------------------
    */
    public function pdf(Inscription $inscription)
    {
        $inscription->load([
            'eleve',
            'classe',
            'anneeScolaire',
            'createdBy',
            'updatedBy',
        ]);

        $pdf = Pdf::loadView(
            'inscriptions.pdf',
            compact('inscription')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(
            'fiche-inscription-' . $inscription->eleve->matricule . '.pdf'
        );
    }
}
