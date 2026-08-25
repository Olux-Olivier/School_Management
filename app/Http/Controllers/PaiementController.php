<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaiementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Recherche des élèves ayant une inscription dans l'année sélectionnée.
    |
    | Année → Section → Classe → Élève
    |
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Années scolaires disponibles
        |--------------------------------------------------------------------------
        |
        | On ne modifie jamais l'année active.
        | Toutes les années scolaires déjà enregistrées peuvent être consultées.
        |
        */

        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Année sélectionnée
        |--------------------------------------------------------------------------
        |
        | Par défaut : année scolaire active.
        |
        */

        $anneeScolaireActive = AnneeScolaire::where('actif', true)
            ->first();


        $anneeScolaireId = $request->input(
            'annee_scolaire_id',
            $anneeScolaireActive?->id
        );


        $anneeScolaire = AnneeScolaire::find(
            $anneeScolaireId
        );


        /*
        |--------------------------------------------------------------------------
        | Recherche des élèves
        |--------------------------------------------------------------------------
        */

        $query = Inscription::with([
            'eleve',
            'classe',
            'anneeScolaire',
        ])
            ->where(
                'annee_scolaire_id',
                $anneeScolaireId
            );


        /*
        |--------------------------------------------------------------------------
        | Recherche par élève
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->input('search')
            );

            $query->whereHas(
                'eleve',
                function ($q) use ($search) {

                    $q->where(
                        'matricule',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'nom',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'postnom',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'prenom',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Section
        |--------------------------------------------------------------------------
        */

        $niveau = null;

        if ($request->filled('section')) {

            $niveau = match (
                $request->input('section')
            ) {

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

            $query->whereHas(
                'classe',
                function ($q) use ($niveau) {

                    $q->where(
                        'niveau',
                        $niveau
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre par classe
        |--------------------------------------------------------------------------
        */

        if ($request->filled('classe_id')) {

            $query->where(
                'classe_id',
                $request->input('classe_id')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Résultats
        |--------------------------------------------------------------------------
        */

        $inscriptions = $query
            ->latest('id')
            ->paginate(25)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Classes disponibles pour l'année sélectionnée
        |--------------------------------------------------------------------------
        |
        | Seulement les classes qui possèdent au moins une inscription
        | dans l'année sélectionnée.
        |
        */

        $classes = Classe::whereHas(
            'inscriptions',
            function ($q) use ($anneeScolaireId) {

                $q->where(
                    'annee_scolaire_id',
                    $anneeScolaireId
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
            'paiements.index',
            compact(
                'anneesScolaires',
                'anneeScolaireActive',
                'anneeScolaire',
                'anneeScolaireId',
                'inscriptions',
                'classes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | show
    |--------------------------------------------------------------------------
    |
    | consulter les détails d'un paiement pour un élève donné.
    |
    */
    public function show(Request $request, Eleve $eleve)
    {
        /*
        |--------------------------------------------------------------------------
        | Années scolaires disponibles
        |--------------------------------------------------------------------------
        */

        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Année scolaire sélectionnée
        |--------------------------------------------------------------------------
        |
        | Par défaut, on consulte l'année active.
        |
        */

        $anneeScolaireActive = AnneeScolaire::where('actif', true)
            ->first();


        $anneeScolaireId = $request->input(
            'annee_scolaire_id',
            $anneeScolaireActive?->id
        );


        $anneeScolaire = AnneeScolaire::find(
            $anneeScolaireId
        );


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'inscription de l'élève
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::with([
            'classe',
            'anneeScolaire',
        ])
            ->where('eleve_id', $eleve->id)
            ->where('annee_scolaire_id', $anneeScolaireId)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Paiements de l'élève pour cette année
        |--------------------------------------------------------------------------
        */

        $paiements = Paiement::with([
            'frais',
            'anneeScolaire',
            'createdBy',
        ])
            ->where('eleve_id', $eleve->id)
            ->where('annee_scolaire_id', $anneeScolaireId)
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Totaux
        |--------------------------------------------------------------------------
        */

        $totalMontantDu = Paiement::where(
            'eleve_id',
            $eleve->id
        )
            ->where(
                'annee_scolaire_id',
                $anneeScolaireId
            )
            ->sum('montant_du');


        $totalPaye = Paiement::where(
            'eleve_id',
            $eleve->id
        )
            ->where(
                'annee_scolaire_id',
                $anneeScolaireId
            )
            ->sum('montant_paye');


        $totalRestant = Paiement::where(
            'eleve_id',
            $eleve->id
        )
            ->where(
                'annee_scolaire_id',
                $anneeScolaireId
            )
            ->sum('restant');


        /*
        |--------------------------------------------------------------------------
        | Retour vers la vue
        |--------------------------------------------------------------------------
        */

        return view(
            'paiements.show',
            compact(
                'eleve',
                'anneesScolaires',
                'anneeScolaireActive',
                'anneeScolaire',
                'anneeScolaireId',
                'inscription',
                'paiements',
                'totalMontantDu',
                'totalPaye',
                'totalRestant'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | create
    |--------------------------------------------------------------------------
    |
    | Formulaire pour enregistrer un nouveau paiement pour un élève donné.
    |
    */
    public function create(Request $request, Eleve $eleve)
{
    /*
    |--------------------------------------------------------------------------
    | Années scolaires disponibles
    |--------------------------------------------------------------------------
    */

    $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Année scolaire active
    |--------------------------------------------------------------------------
    */

    $anneeScolaireActive = AnneeScolaire::where('actif', true)
        ->first();


    if (!$anneeScolaireActive) {

        return redirect()
            ->route('paiements.show', $eleve)
            ->with(
                'error',
                'Aucune année scolaire active n’est disponible.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Année scolaire sélectionnée
    |--------------------------------------------------------------------------
    */

    $anneeScolaireId = $request->input(
        'annee_scolaire_id',
        $anneeScolaireActive->id
    );


    /*
    |--------------------------------------------------------------------------
    | Vérifier l'année scolaire
    |--------------------------------------------------------------------------
    */

    $anneeScolaire = AnneeScolaire::find(
        $anneeScolaireId
    );


    if (!$anneeScolaire) {

        return redirect()
            ->route('paiements.show', [
                'eleve' => $eleve->id,
            ])
            ->with(
                'error',
                'L’année scolaire sélectionnée est introuvable.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Inscription de l'élève dans l'année sélectionnée
    |--------------------------------------------------------------------------
    */

    $inscription = Inscription::with([
        'classe',
        'anneeScolaire',
    ])
        ->where(
            'eleve_id',
            $eleve->id
        )
        ->where(
            'annee_scolaire_id',
            $anneeScolaireId
        )
        ->first();


    if (!$inscription) {

        return redirect()
            ->route('paiements.show', [
                'eleve' => $eleve->id,
                'annee_scolaire_id' => $anneeScolaireId,
            ])
            ->with(
                'error',
                'Cet élève n’est pas inscrit dans l’année scolaire sélectionnée.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Déterminer la section
    |--------------------------------------------------------------------------
    */

    $section = match ((int) $inscription->classe->niveau) {

        0 => 'maternelle',

        1 => 'primaire',

        2 => 'secondaire',

        3 => 'humanites',

        default => null,

    };


    if (!$section) {

        return redirect()
            ->route('paiements.show', [
                'eleve' => $eleve->id,
                'annee_scolaire_id' => $anneeScolaireId,
            ])
            ->with(
                'error',
                'La section de la classe de cet élève est invalide.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Frais disponibles pour la section
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    | On ne met pas ->where('actif', true)
    | car ta table frais ne possède pas cette colonne.
    |
    */

    $frais = Frais::where(
        'annee_scolaire_id',
        $anneeScolaireId
    )
        ->where(
            'section',
            $section
        )
        ->orderBy('intitule')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Retour vers la vue
    |--------------------------------------------------------------------------
    */

    return view(
        'paiements.create',
        compact(
            'eleve',
            'anneesScolaires',
            'anneeScolaireActive',
            'anneeScolaire',
            'anneeScolaireId',
            'inscription',
            'section',
            'frais'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | store
    |--------------------------------------------------------------------------
    |
    | Enregistrement d'un nouveau paiement pour un élève donné.
    |
    */
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

        'annee_scolaire_id' => [
            'required',
            'integer',
            'exists:annee_scolaires,id',
        ],

        'frais_id' => [
            'required',
            'integer',
            'exists:frais,id',
        ],

        'mois' => [
            'nullable',
            'string',
            'in:Septembre,Octobre,Novembre,Décembre,Janvier,Février,Mars,Avril,Mai,Juin',
        ],

        'montant_paye' => [
            'required',
            'numeric',
            'min:1',
        ],

        'date_paiement' => [
            'required',
            'date',
        ],

        'mode_paiement' => [
            'required',
            'string',
            'in:especes,mobile_money,virement,cheque',
        ],

    ], [

        'eleve_id.required' =>
            'Veuillez sélectionner un élève.',

        'eleve_id.exists' =>
            'L’élève sélectionné n’existe pas.',

        'annee_scolaire_id.required' =>
            'Veuillez sélectionner une année scolaire.',

        'annee_scolaire_id.exists' =>
            'L’année scolaire sélectionnée n’existe pas.',

        'frais_id.required' =>
            'Veuillez sélectionner un motif.',

        'frais_id.exists' =>
            'Le motif sélectionné n’existe pas.',

        'mois.in' =>
            'Le mois sélectionné est invalide.',

        'montant_paye.required' =>
            'Veuillez saisir le montant payé.',

        'montant_paye.numeric' =>
            'Le montant payé doit être numérique.',

        'montant_paye.min' =>
            'Le montant payé doit être supérieur à zéro.',

        'date_paiement.required' =>
            'La date du paiement est obligatoire.',

        'date_paiement.date' =>
            'La date du paiement est invalide.',

        'mode_paiement.required' =>
            'Veuillez sélectionner le mode de paiement.',

        'mode_paiement.in' =>
            'Le mode de paiement sélectionné est invalide.',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Élève
    |--------------------------------------------------------------------------
    */

    $eleve = Eleve::find(
        $validated['eleve_id']
    );


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
    | Année scolaire
    |--------------------------------------------------------------------------
    */

    $anneeScolaire = AnneeScolaire::find(
        $validated['annee_scolaire_id']
    );


    if (!$anneeScolaire) {

        return back()
            ->withInput()
            ->withErrors([
                'annee_scolaire_id' =>
                    'L’année scolaire sélectionnée n’existe pas.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Inscription
    |--------------------------------------------------------------------------
    */

    $inscription = Inscription::with('classe')
        ->where(
            'eleve_id',
            $eleve->id
        )
        ->where(
            'annee_scolaire_id',
            $anneeScolaire->id
        )
        ->first();


    if (!$inscription) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Cet élève n’est pas inscrit dans l’année scolaire sélectionnée.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Déterminer la section
    |--------------------------------------------------------------------------
    */

    $section = match ((int) $inscription->classe->niveau) {

        0 => 'maternelle',

        1 => 'primaire',

        2 => 'secondaire',

        3 => 'humanites',

        default => null,

    };


    if (!$section) {

        return back()
            ->withInput()
            ->with(
                'error',
                'La section de l’élève est invalide.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Vérifier le frais
    |--------------------------------------------------------------------------
    |
    | Le frais doit correspondre :
    |
    | - à l'année scolaire sélectionnée ;
    | - à la section de l'élève.
    |
    */

    $frais = Frais::where(
        'id',
        $validated['frais_id']
    )
        ->where(
            'annee_scolaire_id',
            $anneeScolaire->id
        )
        ->where(
            'section',
            $section
        )
        ->first();


    if (!$frais) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Le motif sélectionné n’est pas disponible pour la section de cet élève.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Motif
    |--------------------------------------------------------------------------
    |
    | Le motif est récupéré depuis le frais.
    | L'utilisateur ne peut donc pas inventer un motif.
    |
    */

    $motif = $frais->intitule;


    /*
    |--------------------------------------------------------------------------
    | Vérifier si le motif est un minerval
    |--------------------------------------------------------------------------
    |
    | On respecte exactement :
    |
    | Minerval
    | minerval
    |
    */

    $estMinerval =
        $motif === 'Minerval' ||
        $motif === 'minerval';


    /*
    |--------------------------------------------------------------------------
    | Gestion du mois
    |--------------------------------------------------------------------------
    */

    if ($estMinerval) {

        /*
        |----------------------------------------------------------------------
        | Le mois est obligatoire
        |----------------------------------------------------------------------
        */

        if (empty($validated['mois'])) {

            return back()
                ->withInput()
                ->withErrors([
                    'mois' =>
                        'Veuillez sélectionner le mois du minerval.',
                ]);
        }

        $mois = $validated['mois'];

    } else {

        /*
        |----------------------------------------------------------------------
        | Tous les autres frais
        |----------------------------------------------------------------------
        */

        $mois = 'Pas disponible';

    }


    /*
    |--------------------------------------------------------------------------
    | Montants
    |--------------------------------------------------------------------------
    */

    $montantDu = (float) $frais->montant;

    $montantPaye = (float) $validated['montant_paye'];


    /*
    |--------------------------------------------------------------------------
    | Vérifier le montant
    |--------------------------------------------------------------------------
    */

    if ($montantPaye > $montantDu) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Le montant payé ne peut pas être supérieur au montant du frais.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Enregistrement
    |--------------------------------------------------------------------------
    */

    try {

        $paiement = DB::transaction(function () use (
            $validated,
            $eleve,
            $anneeScolaire,
            $frais,
            $section,
            $motif,
            $mois,
            $montantDu,
            $montantPaye
        ) {

            /*
            |--------------------------------------------------------------------------
            | Rechercher un paiement incomplet
            |--------------------------------------------------------------------------
            |
            | On cherche le même :
            |
            | Élève
            | Année
            | Frais
            | Motif
            | Mois
            |
            | avec un restant supérieur à zéro.
            |
            */

            $paiementExistant = Paiement::where(
                'eleve_id',
                $eleve->id
            )
                ->where(
                    'annee_scolaire_id',
                    $anneeScolaire->id
                )
                ->where(
                    'frais_id',
                    $frais->id
                )
                ->where(
                    'motif',
                    $motif
                )
                ->where(
                    'mois',
                    $mois
                )
                ->where(
                    'restant',
                    '>',
                    0
                )
                ->lockForUpdate()
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Compléter le paiement existant
            |--------------------------------------------------------------------------
            */

            if ($paiementExistant) {

                $restantActuel =
                    (float) $paiementExistant->restant;


                /*
                |--------------------------------------------------------------------------
                | Le versement ne doit pas dépasser le restant
                |--------------------------------------------------------------------------
                */

                if ($montantPaye > $restantActuel) {

                    throw ValidationException::withMessages([

                        'montant_paye' =>
                            'Le montant payé dépasse le montant restant de ce paiement.',

                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Nouveau montant payé
                |--------------------------------------------------------------------------
                */

                $nouveauMontantPaye =
                    (float) $paiementExistant->montant_paye
                    + $montantPaye;


                /*
                |--------------------------------------------------------------------------
                | Nouveau restant
                |--------------------------------------------------------------------------
                */

                $nouveauRestant =
                    $restantActuel
                    - $montantPaye;


                /*
                |--------------------------------------------------------------------------
                | Mise à jour
                |--------------------------------------------------------------------------
                */

                $paiementExistant->update([

                    'montant_paye' =>
                        $nouveauMontantPaye,

                    'restant' =>
                        $nouveauRestant,

                    'date_paiement' =>
                        $validated['date_paiement'],

                    'mode_paiement' =>
                        $validated['mode_paiement'],

                    'updated_by' =>
                        auth()->id(),

                ]);


                return $paiementExistant;
            }


            /*
            |--------------------------------------------------------------------------
            | Nouveau paiement
            |--------------------------------------------------------------------------
            */

            $restant =
                $montantDu
                - $montantPaye;


            /*
            |--------------------------------------------------------------------------
            | Suffixe de section
            |--------------------------------------------------------------------------
            */

            $suffixeSection = match ($section) {

                'humanites' => 'HUM',

                'secondaire' => 'SEC',

                'primaire' => 'PRIM',

                'maternelle' => 'MAT',

                default => 'AUT',

            };


            /*
            |--------------------------------------------------------------------------
            | Dernière référence de cette section
            | dans cette année scolaire
            |--------------------------------------------------------------------------
            */

            $dernierPaiement = Paiement::where(
                'annee_scolaire_id',
                $anneeScolaire->id
            )
                ->where(
                    'reference',
                    'like',
                    '%-' . $suffixeSection
                )
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Numéro séquentiel
            |--------------------------------------------------------------------------
            */

            if ($dernierPaiement) {

                $partieNumerique = explode(
                    '-',
                    $dernierPaiement->reference
                )[0];


                $numero =
                    ((int) $partieNumerique) + 1;

            } else {

                /*
                |----------------------------------------------------------------------
                | Nouvelle année scolaire :
                | le compteur recommence à 00001.
                |----------------------------------------------------------------------
                */

                $numero = 1;

            }


            /*
            |--------------------------------------------------------------------------
            | Référence automatique
            |--------------------------------------------------------------------------
            */

            $reference =
                str_pad(
                    $numero,
                    5,
                    '0',
                    STR_PAD_LEFT
                )
                . '-'
                . $suffixeSection;


            /*
            |--------------------------------------------------------------------------
            | Création du paiement
            |--------------------------------------------------------------------------
            */

            return Paiement::create([

                'eleve_id' =>
                    $eleve->id,

                'annee_scolaire_id' =>
                    $anneeScolaire->id,

                'frais_id' =>
                    $frais->id,

                'motif' =>
                    $motif,

                'mois' =>
                    $mois,

                'montant_du' =>
                    $montantDu,

                'montant_paye' =>
                    $montantPaye,

                'restant' =>
                    $restant,

                'date_paiement' =>
                    $validated['date_paiement'],

                'mode_paiement' =>
                    $validated['mode_paiement'],

                'reference' =>
                    $reference,

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
            ->route('paiements.show', [
                'eleve' =>
                    $eleve->id,

                'annee_scolaire_id' =>
                    $anneeScolaire->id,
            ])
            ->with(
                'success',
                'Le paiement a été enregistré avec succès.'
            );


    } catch (ValidationException $e) {

        throw $e;


    } catch (\Throwable $e) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Une erreur est survenue lors de l’enregistrement du paiement.'
        );
    }
}
}
