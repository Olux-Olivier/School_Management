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
        // Année scolaire active
        $anneeScolaireActive = AnneeScolaire::where('actif', true)
            ->firstOrFail();

        // Année scolaire précédente
        $anneeScolairePrecedente = AnneeScolaire::where(
            'date_fin',
            '<',
            $anneeScolaireActive->date_debut
        )
            ->orderByDesc('date_fin')
            ->first();

        // Déterminer si une recherche a été lancée
        $rechercheEffectuee = $request->boolean('rechercher');

        // Vérifier qu'au moins un critère est renseigné
        $criteresRenseignes =
            $request->filled('search')
            || $request->filled('section')
            || $request->filled('classe_id');

        // Initialiser les résultats sans charger la liste complète
        $inscriptions = Inscription::whereRaw('1 = 0')
            ->paginate(25)
            ->withQueryString();

        // Identifiants des élèves déjà inscrits dans l'année active
        $elevesDejaInscrits = [];

        // Classes disponibles dans les filtres
        $classes = collect();

        if ($anneeScolairePrecedente) {

            /*
            * Classes ayant des inscriptions pendant l'année précédente.
            * On conserve les classes historiques pour la consultation.
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
            * Effectuer la recherche uniquement si :
            * - le formulaire a été soumis ;
            * - au moins un critère est renseigné.
            *
            * On conserve les élèves déjà réinscrits afin de pouvoir
            * afficher le bouton « Déjà inscrit ».
            */
            if ($rechercheEffectuee && $criteresRenseignes) {

                $query = Inscription::with([
                    'eleve',
                    'classe',
                    'anneeScolaire',
                ])
                    ->where(
                        'annee_scolaire_id',
                        $anneeScolairePrecedente->id
                    );

                // Recherche par matricule, nom, postnom ou prénom
                if ($request->filled('search')) {

                    $search = trim($request->search);

                    $query->whereHas('eleve', function ($q) use ($search) {
                        $q->where('matricule', 'like', "%{$search}%")
                            ->orWhere('nom', 'like', "%{$search}%")
                            ->orWhere('postnom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%");
                    });
                }

                // Section vers niveau
                $niveau = match ($request->section) {
                    'maternelle' => 0,
                    'primaire' => 1,
                    'secondaire' => 2,
                    'humanites' => 3,
                    default => null,
                };

                if ($request->filled('section') && $niveau !== null) {
                    $query->whereHas('classe', function ($q) use ($niveau) {
                        $q->where('niveau', $niveau);
                    });
                }

                // Filtre par classe
                if ($request->filled('classe_id')) {
                    $query->where('classe_id', $request->classe_id);
                }

                // Résultats paginés
                $inscriptions = $query
                    ->latest('id')
                    ->paginate(25)
                    ->withQueryString();

                /*
                * Vérifier en une seule requête quels élèves affichés
                * possèdent déjà une inscription dans l'année active.
                */
                if ($inscriptions->isNotEmpty()) {

                    $idsEleves = $inscriptions->getCollection()
                        ->pluck('eleve_id')
                        ->unique()
                        ->values();

                    $elevesDejaInscrits = Inscription::where(
                        'annee_scolaire_id',
                        $anneeScolaireActive->id
                    )
                        ->whereIn('eleve_id', $idsEleves)
                        ->pluck('eleve_id')
                        ->map(fn ($id) => (int) $id)
                        ->all();
                }
            }
        }

        return view(
            'reinscriptions.index',
            compact(
                'anneeScolaireActive',
                'anneeScolairePrecedente',
                'inscriptions',
                'classes',
                'rechercheEffectuee',
                'criteresRenseignes',
                'elevesDejaInscrits'
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
