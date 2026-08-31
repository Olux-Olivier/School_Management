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
    | Validation des données reçues du formulaire
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
            'Veuillez sélectionner un frais.',

        'frais_id.exists' =>
            'Le frais sélectionné n’existe pas.',

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
    ]);


    /*
    |--------------------------------------------------------------------------
    | Récupération de l'élève
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
    | Récupération de l'année scolaire
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
    | Vérification de l'inscription de l'élève
    |--------------------------------------------------------------------------
    |
    | L'élève doit avoir une inscription dans l'année scolaire
    | pour laquelle le paiement est effectué.
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
    | Vérification de la classe
    |--------------------------------------------------------------------------
    */

    if (!$inscription->classe) {

        return back()
            ->withInput()
            ->with(
                'error',
                'La classe associée à l’inscription de cet élève est introuvable.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Détermination de la section
    |--------------------------------------------------------------------------
    |
    | niveau :
    |
    | 0 = Maternelle
    | 1 = Primaire
    | 2 = Secondaire
    | 3 = Humanités
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
    | Récupération du frais
    |--------------------------------------------------------------------------
    |
    | Le frais doit appartenir à la même année scolaire
    | et à la même section que l'élève.
    |--------------------------------------------------------------------------
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
                'Le frais sélectionné n’est pas disponible pour la section de cet élève.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Motif
    |--------------------------------------------------------------------------
    |
    | Le motif est récupéré directement depuis le frais.
    |--------------------------------------------------------------------------
    */

    $motif = $frais->intitule;


    /*
    |--------------------------------------------------------------------------
    | Vérification du Minerval
    |--------------------------------------------------------------------------
    |
    | Les deux écritures suivantes sont considérées comme Minerval :
    |
    | Minerval
    | minerval
    |--------------------------------------------------------------------------
    */

    $estMinerval =
        $motif === 'Minerval' ||
        $motif === 'minerval';


    /*
    |--------------------------------------------------------------------------
    | Gestion du mois
    |--------------------------------------------------------------------------
    |
    | Si le frais est un Minerval :
    |     le mois est obligatoire.
    |
    | Sinon :
    |     le mois devient automatiquement "Pas disponible".
    |--------------------------------------------------------------------------
    */

    if ($estMinerval) {

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
    | Vérification du montant
    |--------------------------------------------------------------------------
    |
    | Pour un nouveau paiement, on ne peut pas payer plus que
    | le montant du frais.
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
    | Transaction
    |--------------------------------------------------------------------------
    |
    | Toutes les opérations suivantes sont effectuées dans une transaction
    | afin d'éviter les incohérences dans la base de données.
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
            $montantPaye,
            $estMinerval
        ) {

            /*
            |--------------------------------------------------------------------------
            | Recherche d'un paiement existant
            |--------------------------------------------------------------------------
            |
            | Pour un Minerval :
            |     élève + année + frais + mois
            |
            | Pour les autres frais :
            |     élève + année + frais
            |--------------------------------------------------------------------------
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
                ->when(
                    $estMinerval,
                    function ($query) use ($mois) {

                        $query->where(
                            'mois',
                            $mois
                        );
                    }
                )
                ->lockForUpdate()
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Un paiement existe déjà
            |--------------------------------------------------------------------------
            */

            if ($paiementExistant) {

                /*
                |--------------------------------------------------------------------------
                | Vérifier si le paiement est déjà complètement soldé
                |--------------------------------------------------------------------------
                */

                if (
                    (float) $paiementExistant->restant <= 0
                ) {

                    if ($estMinerval) {

                        throw ValidationException::withMessages([

                            'frais_id' =>
                                'Le minerval du mois de '
                                . $mois
                                . ' a déjà été entièrement payé.',

                        ]);
                    }


                    throw ValidationException::withMessages([

                        'frais_id' =>
                            'Ce frais a déjà été entièrement payé '
                            . 'pour cet élève pendant cette année scolaire.',

                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Le paiement existe mais il reste une dette
                |--------------------------------------------------------------------------
                |
                | On autorise donc le complément.
                |--------------------------------------------------------------------------
                */

                $restantActuel =
                    (float) $paiementExistant->restant;


                /*
                |--------------------------------------------------------------------------
                | Le complément ne peut pas dépasser le restant
                |--------------------------------------------------------------------------
                */

                if ($montantPaye > $restantActuel) {

                    throw ValidationException::withMessages([

                        'montant_paye' =>
                            'Le montant payé dépasse le montant restant de '
                            . number_format(
                                $restantActuel,
                                2,
                                ',',
                                ' '
                            )
                            . ' FC.',

                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Nouveau montant payé cumulé
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
                | Mise à jour du paiement
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


                /*
                |--------------------------------------------------------------------------
                | Retourner le paiement existant
                |--------------------------------------------------------------------------
                */

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
            | Suffixe de la section
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
            | Préfixe du mode de paiement
            |--------------------------------------------------------------------------
            |
            | Pour les espèces :
            |
            | ESP
            |
            | Les autres modes possèdent également un préfixe.
            |--------------------------------------------------------------------------
            */

            $prefixeMode = match (
                strtolower(
                    trim(
                        $validated['mode_paiement']
                    )
                )
            ) {

                'especes',
                'espèces',
                'espece',
                'espèce'
                    => 'ESP',

                'mobile_money',
                'mobile money'
                    => 'MM',

                'virement'
                    => 'VIR',

                'cheque',
                'chèque'
                    => 'CHQ',

                default
                    => 'PAY',
            };


            /*
            |--------------------------------------------------------------------------
            | Recherche du dernier numéro de référence
            |--------------------------------------------------------------------------
            |
            | IMPORTANT :
            |
            | Le compteur est GLOBAL.
            |
            | Il ne dépend PAS de la section.
            |
            | Exemple :
            |
            | ESP-00001-PRIM
            | ESP-00002-HUM
            | ESP-00003-SEC
            | ESP-00004-MAT
            |
            | La recherche se fait uniquement dans l'année scolaire
            | du paiement.
            |--------------------------------------------------------------------------
            */

            $dernierNumero = Paiement::where(
                'annee_scolaire_id',
                $anneeScolaire->id
            )
                ->lockForUpdate()
                ->get()
                ->map(function ($paiement) {

                    /*
                    |--------------------------------------------------------------------------
                    | Extraire le numéro de la référence
                    |--------------------------------------------------------------------------
                    |
                    | Exemple :
                    |
                    | ESP-00027-HUM
                    |
                    | devient :
                    |
                    | 27
                    |--------------------------------------------------------------------------
                    */

                    $parties = explode(
                        '-',
                        $paiement->reference
                    );


                    if (
                        isset($parties[1]) &&
                        is_numeric($parties[1])
                    ) {

                        return (int) $parties[1];
                    }


                    return 0;

                })
                ->max();


            /*
            |--------------------------------------------------------------------------
            | Calcul du prochain numéro
            |--------------------------------------------------------------------------
            */

            $numero =
                ($dernierNumero ?? 0)
                + 1;


            /*
            |--------------------------------------------------------------------------
            | Construction de la référence
            |--------------------------------------------------------------------------
            |
            | Exemple :
            |
            | ESP-00027-HUM
            |--------------------------------------------------------------------------
            */

            $reference =
                $prefixeMode
                . '-'
                . str_pad(
                    $numero,
                    5,
                    '0',
                    STR_PAD_LEFT
                )
                . '-'
                . $suffixeSection;


            /*
            |--------------------------------------------------------------------------
            | Sécurité supplémentaire contre les doublons
            |--------------------------------------------------------------------------
            |
            | On vérifie directement dans la base avant la création.
            |--------------------------------------------------------------------------
            */

            while (
                Paiement::where(
                    'reference',
                    $reference
                )->exists()
            ) {

                $numero++;


                $reference =
                    $prefixeMode
                    . '-'
                    . str_pad(
                        $numero,
                        5,
                        '0',
                        STR_PAD_LEFT
                    )
                    . '-'
                    . $suffixeSection;
            }


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
        | Redirection après succès
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

        /*
        |--------------------------------------------------------------------------
        | Les erreurs de validation doivent être renvoyées au formulaire
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput()
            ->withErrors(
                $e->errors()
            );


    } catch (\Throwable $e) {

        /*
        |--------------------------------------------------------------------------
        | Erreur inattendue
        |--------------------------------------------------------------------------
        */

        return back()
        ->withInput()
        ->with(
            'error',
            'Une erreur est survenue lors de l’enregistrement du paiement.'
        );
    }
}

/**
 * Dashboard des paiements
 */
public function dashboard()
{
    /*
    |--------------------------------------------------------------------------
    | 1. Récupérer l'année scolaire active
    |--------------------------------------------------------------------------
    | On utilise l'année scolaire marquée comme active dans la table
    | annee_scolaires.
    */
    $anneeScolaireActive = AnneeScolaire::where('actif', true)->first();

    /*
    |--------------------------------------------------------------------------
    | 2. Vérifier qu'une année scolaire active existe
    |--------------------------------------------------------------------------
    | Le dashboard financier doit fonctionner avec une année scolaire.
    */
    if (!$anneeScolaireActive) {

        return view('paiements.dashboard', [
            'anneeScolaireActive' => null,
            'totalJour' => 0,
            'totalSemaine' => 0,
            'totalMois' => 0,
            'totalAnnee' => 0,
            'totauxSections' => collect(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Définir les dates utilisées pour les statistiques
    |--------------------------------------------------------------------------
    | - Aujourd'hui
    | - Début de la semaine
    | - Début du mois
    | - Début de l'année scolaire
    */
    $aujourdHui = now()->startOfDay();

    $debutSemaine = now()->startOfWeek();

    $debutMois = now()->startOfMonth();

    /*
    |--------------------------------------------------------------------------
    | 4. Total des paiements du jour
    |--------------------------------------------------------------------------
    | On additionne uniquement les montants réellement payés.
    */
    $totalJour = Paiement::where(
        'annee_scolaire_id',
        $anneeScolaireActive->id
    )
        ->whereDate('date_paiement', $aujourdHui)
        ->sum('montant_paye');

    /*
    |--------------------------------------------------------------------------
    | 5. Total des paiements de la semaine
    |--------------------------------------------------------------------------
    */
    $totalSemaine = Paiement::where(
        'annee_scolaire_id',
        $anneeScolaireActive->id
    )
        ->whereBetween('date_paiement', [
            $debutSemaine,
            $aujourdHui->copy()->endOfDay(),
        ])
        ->sum('montant_paye');

    /*
    |--------------------------------------------------------------------------
    | 6. Total des paiements du mois
    |--------------------------------------------------------------------------
    */
    $totalMois = Paiement::where(
        'annee_scolaire_id',
        $anneeScolaireActive->id
    )
        ->whereBetween('date_paiement', [
            $debutMois,
            $aujourdHui->copy()->endOfDay(),
        ])
        ->sum('montant_paye');

    /*
    |--------------------------------------------------------------------------
    | 7. Total des paiements de l'année scolaire
    |--------------------------------------------------------------------------
    | Ici on utilise directement annee_scolaire_id.
    | On ne dépend donc pas uniquement de la date du paiement.
    */
    $totalAnnee = Paiement::where(
        'annee_scolaire_id',
        $anneeScolaireActive->id
    )
        ->sum('montant_paye');

    /*
    |--------------------------------------------------------------------------
    | 8. Récupérer les paiements de l'année scolaire
    |--------------------------------------------------------------------------
    | On récupère les relations nécessaires pour déterminer la section
    | correspondant à la classe de l'élève pour cette année scolaire.
    */
    $paiementsAnnee = Paiement::with([
        'eleve',
        'frais',
    ])
        ->where(
            'annee_scolaire_id',
            $anneeScolaireActive->id
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 9. Calcul des totaux par section
    |--------------------------------------------------------------------------
    | Pour chaque paiement, on recherche l'inscription de l'élève
    | correspondant à la même année scolaire que le paiement.
    */
    $totauxSections = $paiementsAnnee
        ->groupBy(function ($paiement) use ($anneeScolaireActive) {

            /*
            |--------------------------------------------------------------------------
            | Recherche de l'inscription correspondante
            |--------------------------------------------------------------------------
            | L'élève peut avoir plusieurs inscriptions au fil des années.
            | On prend donc celle correspondant à l'année du paiement.
            */
            $inscription = \App\Models\Inscription::with('classe')
                ->where('eleve_id', $paiement->eleve_id)
                ->where(
                    'annee_scolaire_id',
                    $anneeScolaireActive->id
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Déterminer la section
            |--------------------------------------------------------------------------
            */
            return $inscription?->classe?->section ?? 'Non définie';
        })
        ->map(function ($paiements) {

            /*
            |--------------------------------------------------------------------------
            | Additionner les montants réellement payés
            |--------------------------------------------------------------------------
            */
            return $paiements->sum('montant_paye');
        });

    /*
    |--------------------------------------------------------------------------
    | 10. Envoyer les données à la vue
    |--------------------------------------------------------------------------
    */
    return view(
        'paiements.dashboard',
        compact(
            'anneeScolaireActive',
            'totalJour',
            'totalSemaine',
            'totalMois',
            'totalAnnee',
            'totauxSections'
        )
    );
}

/*
    |--------------------------------------------------------------------------
    | Autres méthodes du contrôleur PaiementController
    |--------------------------------------------------------------------------
    |
    | Vous pouvez ajouter d'autres méthodes ici pour gérer les paiements,
    | comme l'édition, la suppression, etc.
    |
    */

################### DETAILS PAIEMENT PAR JOUR, SEMAINE, MOIS, ANNEE SCOLAIRE ####################
/**
 * Afficher les détails des paiements d'une journée
 */
public function detailsJour(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | 1. Récupérer l'année scolaire active
    |--------------------------------------------------------------------------
    */
    $anneeScolaireActive = AnneeScolaire::where('actif', true)->first();


    /*
    |--------------------------------------------------------------------------
    | 2. Déterminer la date à consulter
    |--------------------------------------------------------------------------
    | Si aucune date n'est fournie, la date du jour est utilisée.
    |--------------------------------------------------------------------------
    */
    $date = $request->input(
        'date',
        now()->format('Y-m-d')
    );


    /*
    |--------------------------------------------------------------------------
    | 3. Initialiser les variables
    |--------------------------------------------------------------------------
    | Cela permet d'éviter les erreurs lorsqu'aucune année scolaire active
    | n'existe.
    |--------------------------------------------------------------------------
    */
    $paiements = collect();

    $inscriptions = collect();

    $totalJour = 0;

    $nombrePaiements = 0;


    /*
    |--------------------------------------------------------------------------
    | 4. Vérifier qu'une année scolaire active existe
    |--------------------------------------------------------------------------
    */
    if ($anneeScolaireActive) {

        /*
        |--------------------------------------------------------------------------
        | 5. Récupérer les paiements du jour
        |--------------------------------------------------------------------------
        | On récupère uniquement les paiements appartenant à l'année
        | scolaire active et à la date sélectionnée.
        |
        | Les relations nécessaires à l'affichage sont chargées ici :
        | - élève
        | - frais
        | - utilisateur ayant enregistré le paiement
        |--------------------------------------------------------------------------
        */
        $paiements = Paiement::with([
            'eleve',
            'frais',
            'createdBy',
        ])
            ->where(
                'annee_scolaire_id',
                $anneeScolaireActive->id
            )
            ->whereDate(
                'date_paiement',
                $date
            )
            ->orderBy(
                'date_paiement',
                'asc'
            )
            ->orderBy(
                'id',
                'asc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 6. Récupérer les inscriptions des élèves concernés
        |--------------------------------------------------------------------------
        | IMPORTANT :
        |
        | On utilise l'année scolaire du paiement.
        |
        | Ainsi, si un élève était en 5ème l'année passée et en 6ème
        | cette année, un ancien paiement affichera bien sa classe
        | correspondant à l'année du paiement.
        |--------------------------------------------------------------------------
        */
        $inscriptions = Inscription::with('classe')
            ->where(
                'annee_scolaire_id',
                $anneeScolaireActive->id
            )
            ->whereIn(
                'eleve_id',
                $paiements->pluck('eleve_id')->unique()
            )
            ->get()
            ->keyBy('eleve_id');


        /*
        |--------------------------------------------------------------------------
        | 7. Associer l'inscription à chaque paiement
        |--------------------------------------------------------------------------
        | On ajoute dynamiquement l'inscription correspondante au paiement.
        |--------------------------------------------------------------------------
        */
        $paiements->each(function ($paiement) use ($inscriptions) {

            $paiement->inscription = $inscriptions->get(
                $paiement->eleve_id
            );

        });


        /*
        |--------------------------------------------------------------------------
        | 8. Calculer le total encaissé pendant la journée
        |--------------------------------------------------------------------------
        | On additionne uniquement le montant réellement payé.
        |--------------------------------------------------------------------------
        */
        $totalJour = $paiements->sum(
            'montant_paye'
        );


        /*
        |--------------------------------------------------------------------------
        | 9. Compter le nombre de paiements
        |--------------------------------------------------------------------------
        */
        $nombrePaiements = $paiements->count();
    }


    /*
    |--------------------------------------------------------------------------
    | 10. Envoyer les données à la vue
    |--------------------------------------------------------------------------
    */
    return view(
        'paiements.details-jour',
        compact(
            'anneeScolaireActive',
            'date',
            'paiements',
            'inscriptions',
            'totalJour',
            'nombrePaiements'
        )
    );
}
}
