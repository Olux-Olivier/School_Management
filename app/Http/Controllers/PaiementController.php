<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\HistoriquePaiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')
            ->get();

        $anneeScolaireActive = AnneeScolaire::where('actif', true)
            ->first();

        $anneeScolaireId = $request->input(
            'annee_scolaire_id',
            $anneeScolaireActive?->id
        );

        $anneeScolaire = AnneeScolaire::find($anneeScolaireId);

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
            ->where('annee_scolaire_id', $anneeScolaireId);

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->whereHas(
                'eleve',
                function ($q) use ($search) {

                    $q->where('matricule', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%")
                        ->orWhere('postnom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre section
        |--------------------------------------------------------------------------
        */

        $niveau = null;

        if ($request->filled('section')) {

            $niveau = match ($request->input('section')) {

                'maternelle' => 0,
                'primaire' => 1,
                'secondaire' => 2,
                'humanites' => 3,

                default => null,
            };
        }

        if ($niveau !== null) {

            $query->whereHas(
                'classe',
                function ($q) use ($niveau) {
                    $q->where('niveau', $niveau);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre classe
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
        | Classes disponibles
        |--------------------------------------------------------------------------
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
    | SHOW
    |--------------------------------------------------------------------------
    |
    | Consultation de la situation financière d'un élève.
    |
    */

    public function show(Request $request, Eleve $eleve)
    {
        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')
            ->get();

        $anneeScolaireActive = AnneeScolaire::where('actif', true)
            ->first();

        $anneeScolaireId = $request->input(
            'annee_scolaire_id',
            $anneeScolaireActive?->id
        );

        $anneeScolaire = AnneeScolaire::find($anneeScolaireId);

        /*
        |--------------------------------------------------------------------------
        | Inscription
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
        | Paiements
        |--------------------------------------------------------------------------
        |
        | On charge maintenant également l'historique des versements.
        |
        */

        $paiements = Paiement::with([
            'frais',
            'anneeScolaire',
            'createdBy',
            'historiques.createdBy',
            'historiques.updatedBy',
        ])
            ->where('eleve_id', $eleve->id)
            ->where('annee_scolaire_id', $anneeScolaireId)
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
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Request $request, Eleve $eleve)
    {
        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')
            ->get();

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

        $anneeScolaireId = $request->input(
            'annee_scolaire_id',
            $anneeScolaireActive->id
        );

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
        | Inscription
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
        | Section
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
        | Frais
        |--------------------------------------------------------------------------
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
    | STORE
    |--------------------------------------------------------------------------
    |
    | Nouveau fonctionnement :
    |
    | Paiement = situation cumulée
    |
    | HistoriquePaiement = chaque versement individuel
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
        'eleve_id' => 'required|exists:eleves,id',
        'frais_id' => 'required|exists:frais,id',
        'mois' => 'nullable|string|max:50',
        'montant_paye' => 'required|numeric|min:0.01',
        'date_paiement' => 'required|date',
        'mode_paiement' => 'required|string|max:50',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Année scolaire active
    |--------------------------------------------------------------------------
    */

    $anneeScolaire = AnneeScolaire::where('actif', true)->first();

    if (!$anneeScolaire) {
        return back()
            ->withInput()
            ->with('error', 'Aucune année scolaire active.');
    }


    /*
    |--------------------------------------------------------------------------
    | Vérification de l'inscription de l'élève
    |--------------------------------------------------------------------------
    */

    $inscription = Inscription::with('classe')
        ->where('eleve_id', $validated['eleve_id'])
        ->where('annee_scolaire_id', $anneeScolaire->id)
        ->first();

    if (!$inscription) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Cet élève n\'est pas inscrit pour l\'année scolaire active.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Récupération des frais
    |--------------------------------------------------------------------------
    */

    $frais = Frais::find($validated['frais_id']);

    if (!$frais) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Les frais sélectionnés sont introuvables.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Motif
    |--------------------------------------------------------------------------
    |
    | Le formulaire n'envoie pas de champ "motif".
    | Le motif est toujours déterminé à partir de l'intitulé du frais.
    |
    */

    $motif = trim($frais->intitule);


    /*
    |--------------------------------------------------------------------------
    | Vérification de l'année scolaire des frais
    |--------------------------------------------------------------------------
    */

    if (
        (int) $frais->annee_scolaire_id !==
        (int) $anneeScolaire->id
    ) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Ces frais ne correspondent pas à l\'année scolaire active.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Vérification de la classe
    |--------------------------------------------------------------------------
    */

    if (
        (int) $frais->classe_id !==
        (int) $inscription->classe_id
    ) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Ces frais ne correspondent pas à la classe de l\'élève.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Détermination de la section
    |--------------------------------------------------------------------------
    |
    | Les valeurs utilisées correspondent aux valeurs réelles
    | enregistrées dans la table frais :
    |
    | Maternelle
    | Primaire
    | Secondaire
    | Humanités
    |
    */

    $sectionEleve = match ((int) $inscription->classe->niveau) {

        0 => 'Maternelle',
        1 => 'Primaire',
        2 => 'Secondaire',
        3 => 'Humanités',

        default => 'Autre',
    };


    /*
    |--------------------------------------------------------------------------
    | Normalisation de la section
    |--------------------------------------------------------------------------
    |
    | Cette fonction permet d'éviter les problèmes liés à :
    |
    | Humanités
    | humanités
    | HUMANITÉS
    | humanites
    | HUMANITES
    |
    */

    $normaliserSection = function ($section) {

        $section = trim((string) $section);

        $section = mb_strtolower(
            $section,
            'UTF-8'
        );

        $section = strtr(
            $section,
            [
                'à' => 'a',
                'á' => 'a',
                'â' => 'a',
                'ä' => 'a',
                'ã' => 'a',
                'å' => 'a',

                'ç' => 'c',

                'è' => 'e',
                'é' => 'e',
                'ê' => 'e',
                'ë' => 'e',

                'ì' => 'i',
                'í' => 'i',
                'î' => 'i',
                'ï' => 'i',

                'ñ' => 'n',

                'ò' => 'o',
                'ó' => 'o',
                'ô' => 'o',
                'ö' => 'o',
                'õ' => 'o',

                'ù' => 'u',
                'ú' => 'u',
                'û' => 'u',
                'ü' => 'u',

                'ý' => 'y',
                'ÿ' => 'y',
            ]
        );

        return $section;
    };


    /*
    |--------------------------------------------------------------------------
    | Comparaison de la section
    |--------------------------------------------------------------------------
    */

    $sectionEleveNormalisee =
        $normaliserSection($sectionEleve);

    $sectionFraisNormalisee =
        $normaliserSection($frais->section);


    if (
        $sectionEleveNormalisee !==
        $sectionFraisNormalisee
    ) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Ces frais ne correspondent pas à la section de l\'élève.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Gestion du mois
    |--------------------------------------------------------------------------
    */

    $estMinerval =
        mb_strtolower(
            trim($motif),
            'UTF-8'
        ) === 'minerval';


    if ($estMinerval) {

        if (
            empty($validated['mois']) ||
            mb_strtolower(
                trim($validated['mois']),
                'UTF-8'
            ) === 'pas disponible'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Le mois est obligatoire pour le minerval.'
                );
        }

    } else {

        /*
        |--------------------------------------------------------------------------
        | Les autres frais n'ont pas de mois
        |--------------------------------------------------------------------------
        */

        $validated['mois'] = 'Pas disponible';
    }


    /*
    |--------------------------------------------------------------------------
    | Montant des frais
    |--------------------------------------------------------------------------
    */

    $montantDu = (float) $frais->montant;

    $montantVerse = (float) $validated['montant_paye'];


    if ($montantVerse <= 0) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Le montant versé doit être supérieur à zéro.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Transaction
    |--------------------------------------------------------------------------
    */

    try {

        DB::beginTransaction();


        /*
        |--------------------------------------------------------------------------
        | Recherche du paiement existant
        |--------------------------------------------------------------------------
        |
        | Minerval :
        | élève + année + frais + mois
        |
        | Autres frais :
        | élève + année + frais
        |
        */

        $paiementQuery = Paiement::query()
            ->where(
                'eleve_id',
                $validated['eleve_id']
            )
            ->where(
                'annee_scolaire_id',
                $anneeScolaire->id
            )
            ->where(
                'frais_id',
                $validated['frais_id']
            )
            ->lockForUpdate();


        if ($estMinerval) {

            $paiementQuery->where(
                'mois',
                $validated['mois']
            );
        }


        $paiement = $paiementQuery->first();


        /*
        |--------------------------------------------------------------------------
        | Paiement existant
        |--------------------------------------------------------------------------
        */

        if ($paiement) {

            $montantDejaPaye =
                (float) $paiement->montant_paye;


            $restantAvant = max(
                0,
                $montantDu - $montantDejaPaye
            );


            /*
            |--------------------------------------------------------------------------
            | Paiement déjà soldé
            |--------------------------------------------------------------------------
            */

            if ($restantAvant <= 0) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        $estMinerval
                            ? 'Ce minerval est déjà entièrement payé pour ce mois.'
                            : 'Ces frais sont déjà entièrement payés.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Vérification du montant restant
            |--------------------------------------------------------------------------
            */

            if ($montantVerse > $restantAvant) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le montant versé dépasse le montant restant de '
                        . number_format(
                            $restantAvant,
                            2,
                            ',',
                            ' '
                        )
                        . ' FC.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Nouveau montant payé
            |--------------------------------------------------------------------------
            */

            $nouveauMontantPaye =
                $montantDejaPaye + $montantVerse;


            $nouveauRestant =
                max(
                    0,
                    $montantDu - $nouveauMontantPaye
                );


            /*
            |--------------------------------------------------------------------------
            | Mise à jour du paiement
            |--------------------------------------------------------------------------
            */

            $paiement->update([
                'motif' => $motif,
                'mois' => $validated['mois'],
                'montant_du' => $montantDu,
                'montant_paye' => $nouveauMontantPaye,
                'restant' => $nouveauRestant,
                'date_paiement' => $validated['date_paiement'],
                'mode_paiement' => $validated['mode_paiement'],
                'updated_by' => auth()->id(),
            ]);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Premier versement
            |--------------------------------------------------------------------------
            */

            if ($montantVerse > $montantDu) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le montant versé ne peut pas dépasser '
                        . number_format(
                            $montantDu,
                            2,
                            ',',
                            ' '
                        )
                        . ' FC.'
                    );
            }


            $restant =
                max(
                    0,
                    $montantDu - $montantVerse
                );


            /*
            |--------------------------------------------------------------------------
            | Création du paiement
            |--------------------------------------------------------------------------
            */

            $paiement = Paiement::create([
                'eleve_id' => $validated['eleve_id'],
                'annee_scolaire_id' => $anneeScolaire->id,
                'frais_id' => $validated['frais_id'],
                'motif' => $motif,
                'mois' => $validated['mois'],
                'montant_du' => $montantDu,
                'montant_paye' => $montantVerse,
                'restant' => $restant,
                'date_paiement' => $validated['date_paiement'],
                'mode_paiement' => $validated['mode_paiement'],
                'created_by' => auth()->id(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Préfixe selon le mode de paiement
        |--------------------------------------------------------------------------
        */

        $prefixeMode = match (
            mb_strtolower(
                trim(
                    $validated['mode_paiement']
                ),
                'UTF-8'
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
        | Génération d'une référence globalement unique
        |--------------------------------------------------------------------------
        |
        | Exemple :
        |
        | ESP-7K4P9-X2Q8M-A6R3T
        | MM-9F2KD-P7L3Q-Z8W1A
        | VIR-A7K2P-9QX4M-L5T8R
        |
        | La vérification porte sur tout l'historique,
        | quelle que soit l'année scolaire.
        |
        */

        do {

            $reference =
                $prefixeMode
                . '-'
                . strtoupper(Str::random(5))
                . '-'
                . strtoupper(Str::random(5))
                . '-'
                . strtoupper(Str::random(5));

        } while (
            HistoriquePaiement::where(
                'reference',
                $reference
            )->exists()
        );


        /*
        |--------------------------------------------------------------------------
        | Création de l'historique du versement
        |--------------------------------------------------------------------------
        */

        HistoriquePaiement::create([
            'paiement_id' => $paiement->id,
            'montant' => $montantVerse,
            'date_paiement' => $validated['date_paiement'],
            'mode_paiement' => $validated['mode_paiement'],
            'reference' => $reference,
            'created_by' => auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validation de la transaction
        |--------------------------------------------------------------------------
        */

        DB::commit();


        /*
        |--------------------------------------------------------------------------
        | Redirection
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'paiements.show',
                $validated['eleve_id']
            )
            ->with(
                'success',
                'Versement enregistré avec succès. Référence : '
                . $reference
            );


    } catch (\Throwable $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                'Une erreur est survenue lors de l\'enregistrement du paiement : '
                . $e->getMessage()
            );
    }
}






    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | L'édition concerne maintenant un versement de l'historique.
    |
    */

    public function edit(Request $request, Paiement $paiement)
    {
        $paiement->load([
            'eleve',
            'frais',
            'anneeScolaire',
            'historiques',
        ]);

        if (!$paiement->eleve) {

            return redirect()
                ->route('paiements.index')
                ->with(
                    'error',
                    'L’élève associé à ce paiement est introuvable.'
                );
        }

        if (!$paiement->frais) {

            return redirect()
                ->route('paiements.index')
                ->with(
                    'error',
                    'Le frais associé à ce paiement est introuvable.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Date de retour
        |--------------------------------------------------------------------------
        */

        $dateRetour = $request->input(
            'date',
            $paiement->date_paiement?->format('Y-m-d')
        );


        /*
        |--------------------------------------------------------------------------
        | Minerval
        |--------------------------------------------------------------------------
        */

        $estMinerval =
            $paiement->motif === 'Minerval' ||
            $paiement->motif === 'minerval';


        /*
        |--------------------------------------------------------------------------
        | Modes disponibles
        |--------------------------------------------------------------------------
        */

        $modesPaiement = [
            'Espèces',
            'Mobile Money',
            'Virement',
            'Chèque',
        ];


        /*
        |--------------------------------------------------------------------------
        | Historique
        |--------------------------------------------------------------------------
        |
        | La vue pourra choisir le versement à modifier.
        |
        */

        $historiques = $paiement->historiques()
            ->with([
                'createdBy',
                'updatedBy',
            ])
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->get();


        return view(
            'paiements.edit',
            compact(
                'paiement',
                'estMinerval',
                'modesPaiement',
                'dateRetour',
                'historiques'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    |
    | On ne modifie plus directement le cumul du paiement.
    |
    | On modifie un versement précis dans l'historique.
    |
    */

    public function update(Request $request, Paiement $paiement)
    {
        $validated = $request->validate([

            'historique_id' => [
                'required',
                'integer',
                'exists:historique_paiements,id',
            ],

            'mois' => [
                'nullable',
                'string',
                'max:50',
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
                'max:50',
            ],

        ], [

            'historique_id.required' =>
                'Le versement à modifier est obligatoire.',

            'historique_id.exists' =>
                'Le versement sélectionné est introuvable.',

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


        try {

            DB::transaction(function () use (
                $request,
                $validated,
                $paiement
            ) {

                /*
                |--------------------------------------------------------------------------
                | Verrouiller le paiement
                |--------------------------------------------------------------------------
                */

                $paiement = Paiement::lockForUpdate()
                    ->findOrFail($paiement->id);


                /*
                |--------------------------------------------------------------------------
                | Récupérer le versement concerné
                |--------------------------------------------------------------------------
                */

                $historique = HistoriquePaiement::where(
                    'id',
                    $validated['historique_id']
                )
                    ->where(
                        'paiement_id',
                        $paiement->id
                    )
                    ->lockForUpdate()
                    ->first();


                if (!$historique) {

                    throw ValidationException::withMessages([
                        'historique_id' =>
                            'Le versement sélectionné n’appartient pas à ce paiement.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Vérification Minerval
                |--------------------------------------------------------------------------
                */

                $estMinerval =
                    $paiement->motif === 'Minerval' ||
                    $paiement->motif === 'minerval';


                if (
                    $estMinerval &&
                    empty($validated['mois'])
                ) {

                    throw ValidationException::withMessages([
                        'mois' =>
                            'Veuillez sélectionner le mois du minerval.',
                    ]);
                }


                $mois = $estMinerval
                    ? $validated['mois']
                    : 'Pas disponible';


                /*
                |--------------------------------------------------------------------------
                | Recalcul du cumul
                |--------------------------------------------------------------------------
                |
                | Ancien versement :
                | 40
                |
                | Nouveau versement :
                | 50
                |
                | Nouveau cumul :
                | ancien cumul - 40 + 50
                |
                |--------------------------------------------------------------------------
                */

                $ancienMontant =
                    (float) $historique->montant;

                $nouveauMontant =
                    (float) $validated['montant_paye'];


                $nouveauCumul =
                    (float) $paiement->montant_paye
                    - $ancienMontant
                    + $nouveauMontant;


                if ($nouveauCumul > (float) $paiement->montant_du) {

                    throw ValidationException::withMessages([
                        'montant_paye' =>
                            'Le montant cumulé des versements ne peut pas dépasser le montant dû.',
                    ]);
                }


                if ($nouveauCumul < 0) {

                    throw ValidationException::withMessages([
                        'montant_paye' =>
                            'Le montant payé est invalide.',
                    ]);
                }


                $nouveauRestant =
                    (float) $paiement->montant_du
                    - $nouveauCumul;


                /*
                |--------------------------------------------------------------------------
                | Mise à jour du paiement principal
                |--------------------------------------------------------------------------
                */

                $paiement->update([

                    'mois' =>
                        $mois,

                    'montant_paye' =>
                        $nouveauCumul,

                    'restant' =>
                        $nouveauRestant,

                    'date_paiement' =>
                        $validated['date_paiement'],

                    'updated_by' =>
                        auth()->id(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | Mise à jour de l'historique
                |--------------------------------------------------------------------------
                |
                | La référence reste inchangée.
                |
                */

                $historique->update([

                    'montant' =>
                        $nouveauMontant,

                    'date_paiement' =>
                        $validated['date_paiement'],

                    'mode_paiement' =>
                        $validated['mode_paiement'],

                    'updated_by' =>
                        auth()->id(),
                ]);
            });


            return redirect()
                ->route('paiements.show', [
                    'eleve' =>
                        $paiement->eleve_id,

                    'annee_scolaire_id' =>
                        $paiement->annee_scolaire_id,
                ])
                ->with(
                    'success',
                    'Le versement a été modifié avec succès.'
                );


        } catch (ValidationException $e) {

            return back()
                ->withInput()
                ->withErrors(
                    $e->errors()
                );


        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Une erreur est survenue lors de la modification du versement.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    |
    | Suppression du paiement principal.
    |
    | Les historiques associés sont automatiquement supprimés grâce
    | à cascadeOnDelete().
    |
    */

    public function destroy(Paiement $paiement)
    {
        $eleveId =
            $paiement->eleve_id;

        $anneeScolaireId =
            $paiement->annee_scolaire_id;


        try {

            DB::transaction(function () use ($paiement) {

                $paiement->delete();
            });


            return redirect()
                ->route('paiements.show', [
                    'eleve' =>
                        $eleveId,

                    'annee_scolaire_id' =>
                        $anneeScolaireId,
                ])
                ->with(
                    'success',
                    'Le paiement a été annulé avec succès.'
                );


        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    'Impossible d’annuler ce paiement.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    |
    | Les statistiques financières utilisent maintenant
    | historique_paiements.
    |
    | Cela permet de compter chaque versement à sa vraie date.
    |
    */

    public function dashboard()
    {
        $anneeScolaireActive =
            AnneeScolaire::where('actif', true)
                ->first();


        if (!$anneeScolaireActive) {

            return view(
                'paiements.dashboard',
                [
                    'anneeScolaireActive' => null,
                    'totalJour' => 0,
                    'totalSemaine' => 0,
                    'totalMois' => 0,
                    'totalAnnee' => 0,
                    'totauxSections' => collect(),
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        $aujourdHui =
            now()->startOfDay();

        $debutSemaine =
            now()->startOfWeek();

        $debutMois =
            now()->startOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Requête de base historique
        |--------------------------------------------------------------------------
        */

        $historiqueQuery = HistoriquePaiement::whereHas(
            'paiement',
            function ($query) use ($anneeScolaireActive) {

                $query->where(
                    'annee_scolaire_id',
                    $anneeScolaireActive->id
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Aujourd'hui
        |--------------------------------------------------------------------------
        */

        $totalJour = (clone $historiqueQuery)
            ->whereDate(
                'date_paiement',
                $aujourdHui
            )
            ->sum('montant');


        /*
        |--------------------------------------------------------------------------
        | Semaine
        |--------------------------------------------------------------------------
        */

        $totalSemaine = (clone $historiqueQuery)
            ->whereBetween(
                'date_paiement',
                [
                    $debutSemaine,
                    $aujourdHui->copy()->endOfDay(),
                ]
            )
            ->sum('montant');


        /*
        |--------------------------------------------------------------------------
        | Mois
        |--------------------------------------------------------------------------
        */

        $totalMois = (clone $historiqueQuery)
            ->whereBetween(
                'date_paiement',
                [
                    $debutMois,
                    $aujourdHui->copy()->endOfDay(),
                ]
            )
            ->sum('montant');


        /*
        |--------------------------------------------------------------------------
        | Année scolaire
        |--------------------------------------------------------------------------
        */

        $totalAnnee = (clone $historiqueQuery)
            ->sum('montant');


        /*
        |--------------------------------------------------------------------------
        | Totaux par section
        |--------------------------------------------------------------------------
        */

        $historiquesAnnee = HistoriquePaiement::with([
            'paiement.eleve',
            'paiement.frais',
        ])
            ->whereHas(
                'paiement',
                function ($query) use ($anneeScolaireActive) {

                    $query->where(
                        'annee_scolaire_id',
                        $anneeScolaireActive->id
                    );
                }
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Inscriptions
        |--------------------------------------------------------------------------
        */

        $elevesIds = $historiquesAnnee
            ->pluck('paiement.eleve_id')
            ->unique();


        $inscriptions = Inscription::with('classe')
            ->where(
                'annee_scolaire_id',
                $anneeScolaireActive->id
            )
            ->whereIn(
                'eleve_id',
                $elevesIds
            )
            ->get()
            ->keyBy('eleve_id');


        /*
        |--------------------------------------------------------------------------
        | Groupement par section
        |--------------------------------------------------------------------------
        */

        $totauxSections = $historiquesAnnee
            ->groupBy(function ($historique) use ($inscriptions) {

                $eleveId =
                    $historique->paiement->eleve_id;

                return $inscriptions
                    ->get($eleveId)
                    ?->classe
                    ?->section
                    ?? 'Non définie';
            })
            ->map(function ($historiques) {

                return $historiques->sum(
                    'montant'
                );
            });


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
    | DETAILS PAIEMENTS PAR JOUR
    |--------------------------------------------------------------------------
    |
    | Chaque versement historique est maintenant affiché séparément.
    |
    */

    public function detailsJour(Request $request)
{
/*
|--------------------------------------------------------------------------
| Année scolaire active
|--------------------------------------------------------------------------
*/

$anneeScolaireActive = AnneeScolaire::where('actif', true)
    ->first();


/*
|--------------------------------------------------------------------------
| Date à consulter
|--------------------------------------------------------------------------
*/

$date = $request->input(
    'date',
    now()->format('Y-m-d')
);


/*
|--------------------------------------------------------------------------
| Valeurs par défaut
|--------------------------------------------------------------------------
*/

$paiements = collect();

$inscriptions = collect();

$totalJour = 0;

$nombrePaiements = 0;


/*
|--------------------------------------------------------------------------
| Vérification de l'année scolaire active
|--------------------------------------------------------------------------
*/

if ($anneeScolaireActive) {

    /*
    |--------------------------------------------------------------------------
    | Base de la requête
    |--------------------------------------------------------------------------
    */

    $query = HistoriquePaiement::with([
        'paiement.eleve',
        'paiement.frais',
        'createdBy',
        'updatedBy',
    ])
        ->whereHas(
            'paiement',
            function ($query) use ($anneeScolaireActive) {

                $query->where(
                    'annee_scolaire_id',
                    $anneeScolaireActive->id
                );
            }
        )
        ->whereDate(
            'date_paiement',
            $date
        );


    /*
    |--------------------------------------------------------------------------
    | Total réellement encaissé ce jour
    |--------------------------------------------------------------------------
    |
    | On utilise HistoriquePaiement.montant.
    | Il ne faut surtout pas utiliser Paiement.montant_paye
    | car celui-ci est cumulatif.
    |
    */

    $totalJour = (clone $query)->sum('montant');


    /*
    |--------------------------------------------------------------------------
    | Nombre total de versements du jour
    |--------------------------------------------------------------------------
    */

    $nombrePaiements = (clone $query)->count();


    /*
    |--------------------------------------------------------------------------
    | Versements du jour avec pagination
    |--------------------------------------------------------------------------
    */

    $paiements = $query
        ->orderBy(
            'date_paiement',
            'asc'
        )
        ->orderBy(
            'id',
            'asc'
        )
        ->paginate(25)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Élèves concernés par la page courante
    |--------------------------------------------------------------------------
    |
    | On ne récupère que les élèves affichés sur la page courante.
    | Cela évite de charger inutilement toutes les inscriptions.
    |
    */

    $elevesIds = $paiements
        ->pluck('paiement.eleve_id')
        ->filter()
        ->unique()
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Inscriptions des élèves concernés
    |--------------------------------------------------------------------------
    */

    if ($elevesIds->isNotEmpty()) {

        $inscriptions = Inscription::with('classe')
            ->where(
                'annee_scolaire_id',
                $anneeScolaireActive->id
            )
            ->whereIn(
                'eleve_id',
                $elevesIds
            )
            ->get()
            ->keyBy('eleve_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Associer l'inscription à chaque historique
    |--------------------------------------------------------------------------
    */

    $paiements->each(
        function ($historique) use ($inscriptions) {

            $historique->inscription =
                $inscriptions->get(
                    $historique->paiement->eleve_id
                );
        }
    );
}


/*
|--------------------------------------------------------------------------
| Retour de la vue
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

    /*
    |--------------------------------------------------------------------------
    | RECU
    |--------------------------------------------------------------------------
    |
    | Génération d'un reçu pour un versement historique.
    |
    */
    public function recu(HistoriquePaiement $historique)
    {
        /*
        |--------------------------------------------------------------------------
        | Charger le versement et toutes les informations nécessaires
        |--------------------------------------------------------------------------
        */

        $historique->load([
            'paiement.eleve',
            'paiement.frais',
            'paiement.anneeScolaire',
            'paiement.historiques',
            'createdBy',
        ]);

        $paiement = $historique->paiement;

        /*
        |--------------------------------------------------------------------------
        | Vérification
        |--------------------------------------------------------------------------
        */

        if (!$paiement) {
            abort(404, 'Paiement introuvable.');
        }

        /*
        |--------------------------------------------------------------------------
        | Récupérer l'inscription de l'élève pour cette année scolaire
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::with([
            'classe',
        ])
            ->where('eleve_id', $paiement->eleve_id)
            ->where('annee_scolaire_id', $paiement->annee_scolaire_id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Calcul du cumul payé au moment de ce versement
        |--------------------------------------------------------------------------
        |
        | On utilise l'ordre des ID des historiques.
        | Cela permet qu'un ancien reçu conserve le cumul correspondant
        | au moment où ce versement a été enregistré.
        |
        */

        $montantCumule = $paiement->historiques
            ->filter(function ($item) use ($historique) {
                return $item->id <= $historique->id;
            })
            ->sum('montant');

        /*
        |--------------------------------------------------------------------------
        | Solde restant après ce versement
        |--------------------------------------------------------------------------
        */

        $montantDu = (float) $paiement->montant_du;

        $restant = max(
            0,
            $montantDu - (float) $montantCumule
        );

        /*
        |--------------------------------------------------------------------------
        | Génération du PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView('paiements.recu', [
            'historique' => $historique,
            'paiement' => $paiement,
            'eleve' => $paiement->eleve,
            'frais' => $paiement->frais,
            'anneeScolaire' => $paiement->anneeScolaire,
            'inscription' => $inscription,
            'montantDu' => $montantDu,
            'montantCumule' => $montantCumule,
            'restant' => $restant,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Configuration du document
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'recu-' . $historique->reference . '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ANNULER VERSEMENT
    |--------------------------------------------------------------------------
    |
    | Annulation d'un versement historique.
    |
    | Le cumul du paiement principal est recalculé.
    |
    | Le versement est supprimé de l'historique.
    |
    | IMPORTANT :
    |
    | On ne supprime pas le paiement principal, même si le cumul devient nul.
    |
    */
    public function annulerVersement(HistoriquePaiement $historique)
    {
        /*
        |--------------------------------------------------------------------------
        | Paiement associé
        |--------------------------------------------------------------------------
        */

        $paiement = $historique->paiement;

        if (!$paiement) {
            return back()->with(
                'error',
                'Le paiement associé à ce versement est introuvable.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date de retour
        |--------------------------------------------------------------------------
        */

        $dateRetour = $historique->date_paiement
            ? \Carbon\Carbon::parse($historique->date_paiement)->format('Y-m-d')
            : now()->format('Y-m-d');


        /*
        |--------------------------------------------------------------------------
        | Annulation du versement
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($historique) {

            /*
            |--------------------------------------------------------------------------
            | Verrouiller le paiement principal
            |--------------------------------------------------------------------------
            */

            $paiement = Paiement::whereKey($historique->paiement_id)
                ->lockForUpdate()
                ->first();

            if (!$paiement) {
                throw new \RuntimeException(
                    'Le paiement associé est introuvable.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Verrouiller le versement à annuler
            |--------------------------------------------------------------------------
            */

            $versement = HistoriquePaiement::whereKey($historique->id)
                ->where('paiement_id', $paiement->id)
                ->lockForUpdate()
                ->first();

            if (!$versement) {
                throw new \RuntimeException(
                    'Le versement est introuvable.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Supprimer uniquement ce versement
            |--------------------------------------------------------------------------
            */

            $versement->delete();


            /*
            |--------------------------------------------------------------------------
            | Recalculer le montant payé
            |--------------------------------------------------------------------------
            */

            $nouveauMontantPaye = (float) HistoriquePaiement::where(
                'paiement_id',
                $paiement->id
            )->sum('montant');


            /*
            |--------------------------------------------------------------------------
            | S'il ne reste plus aucun versement
            |--------------------------------------------------------------------------
            */

            if ($nouveauMontantPaye <= 0) {

                $paiement->delete();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Nouveau montant restant
            |--------------------------------------------------------------------------
            */

            $nouveauRestant = max(
                0,
                (float) $paiement->montant_du - $nouveauMontantPaye
            );


            /*
            |--------------------------------------------------------------------------
            | Nouvelle date du dernier versement
            |--------------------------------------------------------------------------
            */

            $nouvelleDatePaiement = HistoriquePaiement::where(
                'paiement_id',
                $paiement->id
            )
                ->orderByDesc('date_paiement')
                ->orderByDesc('id')
                ->value('date_paiement');


            /*
            |--------------------------------------------------------------------------
            | Mise à jour du paiement cumulatif
            |--------------------------------------------------------------------------
            */

            $paiement->update([
                'montant_paye' => $nouveauMontantPaye,
                'restant' => $nouveauRestant,
                'date_paiement' => $nouvelleDatePaiement,
                'updated_by' => auth()->id(),
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Retour
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('paiements.details-jour', [
                'date' => $dateRetour,
            ])
            ->with(
                'success',
                'Le versement a été annulé avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    RAPPORTS QUOTIDIENS
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| RAPPORT QUOTIDIEN PDF
|--------------------------------------------------------------------------
|
| Génère le rapport de tous les versements effectués aujourd'hui.
|
*/
public function rapportQuotidienPdf(Request $request)
{
    // Si aucune date n'est fournie, on utilise automatiquement aujourd'hui.
    $date = $request->input('date', now()->format('Y-m-d'));

    // Récupération de l'année scolaire active.
    $anneeScolaireActive = AnneeScolaire::where('actif', true)->first();

    if (!$anneeScolaireActive) {
        return back()->with(
            'error',
            'Aucune année scolaire active n\'est configurée.'
        );
    }

    // Récupération des versements réellement encaissés
    // à la date demandée et pour l'année scolaire active.
    $paiements = HistoriquePaiement::with([
        'paiement.eleve',
        'paiement.frais',
        'paiement.anneeScolaire',
        'createdBy',
        'updatedBy',
    ])
        ->whereDate('date_paiement', $date)
        ->whereHas('paiement', function ($query) use ($anneeScolaireActive) {
            $query->where(
                'annee_scolaire_id',
                $anneeScolaireActive->id
            );
        })
        ->orderBy('date_paiement')
        ->orderBy('id')
        ->get();

    // Total des versements de la journée.
    $totalJour = $paiements->sum('montant');

    // Nombre de versements.
    $nombrePaiements = $paiements->count();

    // Génération du PDF.
    $pdf = Pdf::loadView('paiements.rapport-quotidien', [
        'paiements' => $paiements,
        'anneeScolaireActive' => $anneeScolaireActive,
        'date' => $date,
        'totalJour' => $totalJour,
        'nombrePaiements' => $nombrePaiements,
    ]);

    return $pdf->stream(
        'rapport-quotidien-' . $date . '.pdf'
    );
}


/*
|--------------------------------------------------------------------------
| RAPPORT QUOTIDIEN EXCEL
|--------------------------------------------------------------------------
|
| Génère le fichier Excel des versements du jour.
|
*/
public function rapportQuotidienExcel(Request $request)
{
    $date = $request->input('date', now()->format('Y-m-d'));

    $anneeScolaireActive = AnneeScolaire::where('actif', true)->first();

    if (!$anneeScolaireActive) {
        return back()->with(
            'error',
            'Aucune année scolaire active n\'est configurée.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Récupération des versements du jour
    |--------------------------------------------------------------------------
    */

    $paiements = HistoriquePaiement::with([
        'paiement.eleve',
        'paiement.frais',
        'paiement.anneeScolaire',
        'createdBy',
        'updatedBy',
    ])
        ->whereDate('date_paiement', $date)
        ->whereHas('paiement', function ($query) use ($anneeScolaireActive) {
            $query->where(
                'annee_scolaire_id',
                $anneeScolaireActive->id
            );
        })
        ->orderBy('date_paiement')
        ->orderBy('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Récupération des inscriptions
    |--------------------------------------------------------------------------
    */

    $eleveIds = $paiements
        ->map(fn ($historique) => $historique->paiement?->eleve_id)
        ->filter()
        ->unique()
        ->values();

    $inscriptions = Inscription::with('classe')
        ->where('annee_scolaire_id', $anneeScolaireActive->id)
        ->whereIn('eleve_id', $eleveIds)
        ->get()
        ->keyBy('eleve_id');

    /*
    |--------------------------------------------------------------------------
    | Nombre de versements et total
    |--------------------------------------------------------------------------
    */

    $nombreVersements = $paiements->count();

    $totalJour = $paiements->sum('montant');

    /*
    |--------------------------------------------------------------------------
    | Nom du fichier
    |--------------------------------------------------------------------------
    */

    $filename = 'rapport-quotidien-' . $date . '.csv';

    /*
    |--------------------------------------------------------------------------
    | En-têtes HTTP
    |--------------------------------------------------------------------------
    */

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
    ];

    /*
    |--------------------------------------------------------------------------
    | Génération du CSV
    |--------------------------------------------------------------------------
    */

    return response()->streamDownload(function () use (
        $paiements,
        $inscriptions,
        $anneeScolaireActive,
        $date,
        $nombreVersements,
        $totalJour
    ) {

        $handle = fopen('php://output', 'w');

        /*
        |--------------------------------------------------------------------------
        | BOM UTF-8
        |--------------------------------------------------------------------------
        |
        | Permet notamment à Excel de reconnaître correctement
        | les accents et caractères français.
        |
        */

        fwrite($handle, "\xEF\xBB\xBF");

        /*
        |--------------------------------------------------------------------------
        | TITRE
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, [
            'RAPPORT QUOTIDIEN DES VERSEMENTS'
        ], ';');

        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS DU RAPPORT
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, [
            'Date',
            \Carbon\Carbon::parse($date)->format('d/m/Y')
        ], ';');

        fputcsv($handle, [
            'Année scolaire',
            $anneeScolaireActive->libelle
                ?? $anneeScolaireActive->nom
                ?? '—'
        ], ';');

        fputcsv($handle, [
            'Nombre de versements',
            $nombreVersements
        ], ';');

        fputcsv($handle, [
            'Généré le',
            now()->format('d/m/Y à H:i')
        ], ';');

        /*
        |--------------------------------------------------------------------------
        | LIGNE VIDE
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, [], ';');

        /*
        |--------------------------------------------------------------------------
        | EN-TÊTES DU TABLEAU
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, [
            'N°',
            'Référence',
            'Matricule',
            'Élève',
            'Section',
            'Frais',
            'Mois',
            'Montant',
            'Mode de paiement',
            'Date',
            'Agent',
        ], ';');

        /*
        |--------------------------------------------------------------------------
        | VERSEMENTS
        |--------------------------------------------------------------------------
        */

        foreach ($paiements as $index => $historique) {

            $paiement = $historique->paiement;

            $eleve = $paiement?->eleve;

            /*
            |--------------------------------------------------------------------------
            | Inscription de l'élève
            |--------------------------------------------------------------------------
            */

            $inscription = $eleve
                ? $inscriptions->get($eleve->id)
                : null;

            $classe = $inscription?->classe;

            /*
            |--------------------------------------------------------------------------
            | Section
            |--------------------------------------------------------------------------
            */

            $section = match ((int) ($classe?->niveau ?? -1)) {
                0 => 'Maternelle',
                1 => 'Primaire',
                2 => 'Secondaire',
                3 => 'Humanités',
                default => '—',
            };

            /*
            |--------------------------------------------------------------------------
            | Classe + option
            |--------------------------------------------------------------------------
            */

            if ($classe) {

                $classeLibelle = trim(
                    ($classe->nom ?? '') . ' ' .
                    ($classe->option ?? '')
                );

                $sectionLibelle = $classeLibelle !== ''
                    ? $classeLibelle . ' (' . $section . ')'
                    : $section;

            } else {

                $sectionLibelle = '—';
            }

            /*
            |--------------------------------------------------------------------------
            | Nom complet de l'élève
            |--------------------------------------------------------------------------
            */

            if ($eleve) {

                $nomEleve = trim(
                    ($eleve->nom ?? '') . ' ' .
                    ($eleve->postnom ?? '') . ' ' .
                    ($eleve->prenom ?? '')
                );

            } else {

                $nomEleve = 'Élève supprimé';
            }

            /*
            |--------------------------------------------------------------------------
            | Frais
            |--------------------------------------------------------------------------
            */

            $frais = $paiement?->frais?->intitule ?? '—';

            /*
            |--------------------------------------------------------------------------
            | Mois
            |--------------------------------------------------------------------------
            */

            $mois = $paiement?->mois ?? 'Pas disponible';

            /*
            |--------------------------------------------------------------------------
            | Mode de paiement
            |--------------------------------------------------------------------------
            */

            $modePaiement = $historique->mode_paiement
                ?? $paiement?->mode_paiement
                ?? '—';

            /*
            |--------------------------------------------------------------------------
            | Date du versement
            |--------------------------------------------------------------------------
            */

            $datePaiement = $historique->date_paiement
                ? \Carbon\Carbon::parse(
                    $historique->date_paiement
                )->format('d/m/Y')
                : '—';

            /*
            |--------------------------------------------------------------------------
            | Agent
            |--------------------------------------------------------------------------
            */

            $agent = $historique->createdBy?->nom_complet ?? '—';

            /*
            |--------------------------------------------------------------------------
            | Ligne du versement
            |--------------------------------------------------------------------------
            */

            fputcsv($handle, [
                $index + 1,
                $historique->reference ?? '—',
                $eleve?->matricule ?? '—',
                $nomEleve,
                $sectionLibelle,
                $frais,
                $mois,
                number_format(
                    (float) ($historique->montant ?? 0),
                    2,
                    '.',
                    ''
                ),
                $modePaiement,
                $datePaiement,
                $agent,
            ], ';');
        }

        /*
        |--------------------------------------------------------------------------
        | LIGNE DU TOTAL
        |--------------------------------------------------------------------------
        */

        fputcsv($handle, [
            '',
            '',
            '',
            '',
            '',
            '',
            'TOTAL',
            number_format(
                (float) $totalJour,
                2,
                '.',
                ''
            ),
            '',
            '',
            '',
        ], ';');

        /*
        |--------------------------------------------------------------------------
        | Fermeture du fichier
        |--------------------------------------------------------------------------
        */

        fclose($handle);

    }, $filename, $headers);
}

/*
|--------------------------------------------------------------------------
| MÉTHODE DE VERIFICATION DES PAIEMENTS
|*-------------------------------------------------------------------------
*/

public function verification(Request $request)
{
    $reference = trim($request->input('reference', ''));

    // Aucun numéro fourni : afficher simplement le formulaire
    if ($reference === '') {
        return view('paiements.verification');
    }

    /*
    |--------------------------------------------------------------------------
    | Recherche du versement par sa référence
    |--------------------------------------------------------------------------
    */
    $historique = HistoriquePaiement::with([
        'paiement.eleve',
        'paiement.anneeScolaire',
        'paiement.frais',
        'paiement.createdBy',
        'paiement.updatedBy',
    ])
    ->where('reference', $reference)
    ->first();

    /*
    |--------------------------------------------------------------------------
    | Référence inexistante
    |--------------------------------------------------------------------------
    */
    if (!$historique) {
        return view('paiements.verification', [
            'reference' => $reference,
            'historique' => null,
        ])->with(
            'error',
            'Aucun versement ne correspond à cette référence.'
        );
    }

    $paiement = $historique->paiement;

    /*
    |--------------------------------------------------------------------------
    | Récupérer l'inscription de l'élève pour l'année concernée
    |--------------------------------------------------------------------------
    */
    $inscription = $paiement->eleve
        ->inscriptions()
        ->with('classe')
        ->where('annee_scolaire_id', $paiement->annee_scolaire_id)
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Calcul du montant AVANT ce versement
    |--------------------------------------------------------------------------
    |
    | On additionne uniquement les versements enregistrés avant
    | celui que nous sommes en train de vérifier.
    |
    */

    $montantAvant = $paiement->historiques()
        ->where(function ($query) use ($historique) {

            $query->where('date_paiement', '<', $historique->date_paiement)
                ->orWhere(function ($query) use ($historique) {

                    $query->whereDate(
                        'date_paiement',
                        $historique->date_paiement
                    )
                    ->where('id', '<', $historique->id);

                });

        })
        ->sum('montant');

    /*
    |--------------------------------------------------------------------------
    | Montant de ce versement
    |--------------------------------------------------------------------------
    */
    $montantVersement = (float) $historique->montant;

    /*
    |--------------------------------------------------------------------------
    | Montant APRÈS ce versement
    |--------------------------------------------------------------------------
    */
    $montantApres = (float) $montantAvant + $montantVersement;

    /*
    |--------------------------------------------------------------------------
    | Solde après ce versement
    |--------------------------------------------------------------------------
    */
    $soldeApres = max(
        0,
        (float) $paiement->montant_du - $montantApres
    );

    return view('paiements.verification', [
        'reference' => $reference,
        'historique' => $historique,
        'paiement' => $paiement,
        'inscription' => $inscription,

        // Informations financières du versement
        'montantAvant' => $montantAvant,
        'montantVersement' => $montantVersement,
        'montantApres' => $montantApres,
        'soldeApres' => $soldeApres,
    ]);
}





}

