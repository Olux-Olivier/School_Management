<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Eleve;
use Illuminate\Support\Facades\DB;

class ReinscriptionController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        */

        $anneeScolaireActive = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Année scolaire précédente
        |--------------------------------------------------------------------------
        */

        $anneeScolairePrecedente = AnneeScolaire::where(
            'date_fin',
            '<',
            $anneeScolaireActive->date_debut
        )
            ->orderByDesc('date_fin')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Aucune année précédente
        |--------------------------------------------------------------------------
        */

        if (!$anneeScolairePrecedente) {

            $inscriptions = Inscription::whereRaw('1 = 0')
                ->paginate(25);

            $classes = collect();

            return view(
                'reinscriptions.index',
                compact(
                    'anneeScolaireActive',
                    'anneeScolairePrecedente',
                    'inscriptions',
                    'classes'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Inscriptions de l'année précédente
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        |
        | On exclut directement les élèves qui possèdent déjà
        | une inscription dans l'année scolaire active.
        |
        */

        $query = Inscription::with([
            'eleve',
            'classe',
            'anneeScolaire',
        ])
            ->where(
                'annee_scolaire_id',
                $anneeScolairePrecedente->id
            )
            ->whereNotExists(function ($subQuery) use ($anneeScolaireActive) {

                $subQuery->selectRaw('1')
                    ->from('inscriptions as inscriptions_actives')
                    ->whereColumn(
                        'inscriptions_actives.eleve_id',
                        'inscriptions.eleve_id'
                    )
                    ->where(
                        'inscriptions_actives.annee_scolaire_id',
                        $anneeScolaireActive->id
                    );

            });


        /*
        |--------------------------------------------------------------------------
        | Recherche élève
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('eleve', function ($q) use ($search) {

                $q->where('matricule', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhere('postnom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Section → Niveau
        |--------------------------------------------------------------------------
        |
        | Maternelle = 0
        | Primaire   = 1
        | Secondaire = 2
        | Humanités  = 3
        |
        */

        $niveau = null;

        if ($request->filled('section')) {

            $niveau = match ($request->section) {

                'maternelle' => 0,

                'primaire' => 1,

                'secondaire' => 2,

                'humanites' => 3,

                default => null,

            };
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre par section
        |--------------------------------------------------------------------------
        */

        if ($niveau !== null) {

            $query->whereHas('classe', function ($q) use ($niveau) {

                $q->where(
                    'niveau',
                    $niveau
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre par classe
        |--------------------------------------------------------------------------
        */

        if ($request->filled('classe_id')) {

            $query->where(
                'classe_id',
                $request->classe_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $inscriptions = $query
            ->latest('id')
            ->paginate(25)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Classes ayant des inscriptions dans l'année précédente
        |--------------------------------------------------------------------------
        */

        $classes = Classe::whereHas(
            'inscriptions',
            function ($query) use ($anneeScolairePrecedente) {

                $query->where(
                    'annee_scolaire_id',
                    $anneeScolairePrecedente->id
                );

            }
        )
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Retour vers la vue
        |--------------------------------------------------------------------------
        */

        return view(
            'reinscriptions.index',
            compact(
                'anneeScolaireActive',
                'anneeScolairePrecedente',
                'inscriptions',
                'classes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulaire de réinscription
    |--------------------------------------------------------------------------*/

    public function create(Inscription $inscription)
    {
        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        */

        $anneeScolaireActive = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Charger l'inscription précédente
        |--------------------------------------------------------------------------
        */

        $inscription->load([
            'eleve',
            'classe',
            'anneeScolaire',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'inscription appartient bien
        | à l'année scolaire précédente
        |--------------------------------------------------------------------------
        */

        $anneeScolairePrecedente = AnneeScolaire::where(
            'date_fin',
            '<',
            $anneeScolaireActive->date_debut
        )
            ->orderByDesc('date_fin')
            ->first();


        if (!$anneeScolairePrecedente) {

            return redirect()
                ->route('reinscriptions.index')
                ->with(
                    'error',
                    'Aucune année scolaire précédente disponible.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'inscription sélectionnée
        | appartient bien à l'année précédente
        |--------------------------------------------------------------------------
        */

        if (
            $inscription->annee_scolaire_id
            != $anneeScolairePrecedente->id
        ) {

            return redirect()
                ->route('reinscriptions.index')
                ->with(
                    'error',
                    'Cette inscription ne peut pas être utilisée pour une réinscription.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier si l'élève est déjà réinscrit
        |--------------------------------------------------------------------------
        */

        $dejaReinscrit = Inscription::where(
            'annee_scolaire_id',
            $anneeScolaireActive->id
        )
            ->where(
                'eleve_id',
                $inscription->eleve_id
            )
            ->exists();


        if ($dejaReinscrit) {

            return redirect()
                ->route('reinscriptions.index')
                ->with(
                    'error',
                    'Cet élève est déjà réinscrit dans l’année scolaire active.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Classes actives
        |--------------------------------------------------------------------------
        |
        | On charge toutes les classes actives.
        | Le JavaScript du formulaire se chargera ensuite
        | de filtrer selon la section.
        |
        */

        $classes = Classe::where('actif', true)
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Retour vers le formulaire
        |--------------------------------------------------------------------------
        */

        return view(
            'reinscriptions.create',
            compact(
                'inscription',
                'anneeScolaireActive',
                'anneeScolairePrecedente',
                'classes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Enregistrer la réinscription
    |--------------------------------------------------------------------------*/

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'eleve_id' => [
                'required',
                'integer',
                'exists:eleves,id',
            ],

            'classe_id' => [
                'required',
                'integer',
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
        ], [

            'eleve_id.required' =>
                'Veuillez sélectionner un élève.',

            'eleve_id.exists' =>
                'L’élève sélectionné n’existe pas.',

            'classe_id.required' =>
                'Veuillez sélectionner une classe.',

            'classe_id.exists' =>
                'La classe sélectionnée n’existe pas.',

            'date_inscription.required' =>
                'La date de réinscription est obligatoire.',

            'date_inscription.date' =>
                'La date de réinscription est invalide.',

            'montant.required' =>
                'Le montant est obligatoire.',

            'montant.numeric' =>
                'Le montant doit être numérique.',

            'montant.min' =>
                'Le montant ne peut pas être négatif.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | On ne récupère PAS l'année depuis le formulaire.
        | L'inscription est toujours créée dans l'année active.
        |
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->first();


        if (!$anneeScolaire) {

            return back()
                ->withInput()
                ->withErrors([
                    'annee_scolaire' =>
                        'Aucune année scolaire active n’est disponible.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'élève
        |--------------------------------------------------------------------------
        */

        $eleve = Eleve::find($validated['eleve_id']);


        if (!$eleve) {

            return back()
                ->withInput()
                ->withErrors([
                    'eleve_id' =>
                        'L’élève sélectionné n’existe pas.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        |
        | Une classe inactive ne doit pas pouvoir recevoir
        | une nouvelle inscription.
        |
        */

        $classe = Classe::where('id', $validated['classe_id'])
            ->where('actif', true)
            ->first();


        if (!$classe) {

            return back()
                ->withInput()
                ->withErrors([
                    'classe_id' =>
                        'La classe sélectionnée est inexistante ou inactive.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier si l'élève est déjà inscrit cette année
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

            return redirect()
                ->route('reinscriptions.index')
                ->with(
                    'error',
                    'Cet élève est déjà inscrit dans l’année scolaire active.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Création de la nouvelle inscription
        |--------------------------------------------------------------------------
        */

        try {

            $inscription = DB::transaction(function () use (
                $validated,
                $anneeScolaire,
                $classe
            ) {

                return Inscription::create([

                    /*
                    | Élève
                    */

                    'eleve_id' =>
                        $validated['eleve_id'],


                    /*
                    | Année active récupérée côté serveur
                    */

                    'annee_scolaire_id' =>
                        $anneeScolaire->id,


                    /*
                    | Nouvelle classe
                    */

                    'classe_id' =>
                        $classe->id,


                    /*
                    | Date
                    */

                    'date_inscription' =>
                        $validated['date_inscription'],


                    /*
                    | Montant
                    */

                    'montant' =>
                        $validated['montant'],


                    /*
                    | Traçabilité
                    */

                    'created_by' =>
                        auth()->id(),

                ]);

            });


            /*
            |--------------------------------------------------------------------------
            | Succès
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'reinscriptions.show',
                    $inscription
                )
                ->with(
                    'success',
                    'La réinscription de l’élève a été enregistrée avec succès.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Erreur
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Une erreur est survenue lors de l’enregistrement de la réinscription.'
                );
        }
    }

}
