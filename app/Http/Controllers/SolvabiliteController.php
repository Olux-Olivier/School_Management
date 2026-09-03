<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SolvabiliteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CONSULTATION
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $anneesScolaires = AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->get();

        $anneeScolaireId = $request->annee_scolaire_id
            ?? optional(
                AnneeScolaire::where('actif', true)->first()
            )->id;

        $sections = Classe::query()
            ->where('actif', true)
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        $classes = collect();
        $options = collect();
        $frais = collect();

        $section = $request->section;
        $classeId = $request->classe_id;
        $option = $request->option;
        $fraisId = $request->frais_id;
        $mois = $request->mois;

        /*
        |--------------------------------------------------------------------------
        | CLASSES
        |--------------------------------------------------------------------------
        */

        if ($section) {
            $classes = Classe::query()
                ->where('actif', true)
                ->where('section', $section)
                ->orderBy('niveau')
                ->orderBy('nom')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | OPTIONS HUMANITÉS
        |--------------------------------------------------------------------------
        */

        if ($section && $this->estHumanites($section)) {
            $options = Classe::query()
                ->where('actif', true)
                ->where('section', $section)
                ->whereNotNull('option')
                ->where('option', '!=', '')
                ->select('option')
                ->distinct()
                ->orderBy('option')
                ->pluck('option');
        }

        /*
        |--------------------------------------------------------------------------
        | FRAIS DE LA CLASSE
        |--------------------------------------------------------------------------
        */

        if ($anneeScolaireId && $classeId) {
            $frais = Frais::query()
                ->where('annee_scolaire_id', $anneeScolaireId)
                ->where('classe_id', $classeId)
                ->orderBy('intitule')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | RÉSULTATS
        |--------------------------------------------------------------------------
        */

        $enOrdre = collect();
        $partiellementPaye = collect();
        $nonEnOrdre = collect();

        $rechercheEffectuee =
            $request->boolean('rechercher') &&
            $anneeScolaireId &&
            $classeId &&
            $fraisId;

        $fraisSelectionne = null;

        if ($rechercheEffectuee) {

            $fraisSelectionne = Frais::query()
                ->where('id', $fraisId)
                ->where('annee_scolaire_id', $anneeScolaireId)
                ->where('classe_id', $classeId)
                ->first();

            if (!$fraisSelectionne) {
                return redirect()
                    ->route('solvabilites.index')
                    ->with('error', 'Le frais sélectionné est invalide.');
            }

            $estMinerval = $this->estMinerval(
                $fraisSelectionne->intitule
            );

            if ($estMinerval && !$mois) {
                return redirect()
                    ->route('solvabilites.index', $request->query())
                    ->with('error', 'Veuillez sélectionner un mois.');
            }

            /*
            |--------------------------------------------------------------------------
            | INSCRIPTIONS
            |--------------------------------------------------------------------------
            */

            $query = Inscription::query()
                ->with([
                    'eleve',
                    'classe',
                    'anneeScolaire',
                ])
                ->where('annee_scolaire_id', $anneeScolaireId)
                ->where('classe_id', $classeId);

            /*
            |--------------------------------------------------------------------------
            | OPTION HUMANITÉS
            |--------------------------------------------------------------------------
            */

            if (
                $this->estHumanites($section)
                && $option
            ) {
                $query->whereHas('classe', function ($q) use ($option) {
                    $q->where('option', $option);
                });
            }

            $inscriptions = $query
                ->orderBy('id')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | CALCUL
            |--------------------------------------------------------------------------
            */

            foreach ($inscriptions as $inscription) {

                if (!$inscription->eleve) {
                    continue;
                }

                $paiementsQuery = Paiement::query()
                    ->where('eleve_id', $inscription->eleve_id)
                    ->where('annee_scolaire_id', $anneeScolaireId)
                    ->where('frais_id', $fraisSelectionne->id);

                /*
                | Pour le Minerval, le mois est obligatoire.
                */

                if ($estMinerval) {
                    $paiementsQuery->where('mois', $mois);
                }

                $montantPaye = (float) $paiementsQuery
                    ->sum('montant_paye');

                $montantDu = (float) $fraisSelectionne->montant;

                $restant = max(
                    0,
                    $montantDu - $montantPaye
                );

                if ($montantPaye <= 0) {

                    $statut = 'Non en ordre';

                } elseif ($montantPaye < $montantDu) {

                    $statut = 'Partiellement payé';

                } else {

                    $statut = 'En ordre';
                }

                $ligne = (object) [
                    'inscription' => $inscription,
                    'eleve' => $inscription->eleve,
                    'classe' => $inscription->classe,
                    'montant_du' => $montantDu,
                    'montant_paye' => $montantPaye,
                    'restant' => $restant,
                    'statut' => $statut,
                ];

                if ($statut === 'En ordre') {

                    $enOrdre->push($ligne);

                } elseif ($statut === 'Partiellement payé') {

                    $partiellementPaye->push($ligne);

                } else {

                    $nonEnOrdre->push($ligne);
                }
            }
        }

        return view('solvabilites.index', compact(
            'anneesScolaires',
            'anneeScolaireId',
            'sections',
            'classes',
            'options',
            'frais',
            'section',
            'classeId',
            'option',
            'fraisId',
            'mois',
            'fraisSelectionne',
            'enOrdre',
            'partiellementPaye',
            'nonEnOrdre',
            'rechercheEffectuee'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX : CLASSES
    |--------------------------------------------------------------------------
    */

    public function classes(Request $request)
    {
        $request->validate([
            'section' => ['required', 'string'],
        ]);

        $classes = Classe::query()
            ->where('actif', true)
            ->where('section', $request->section)
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get([
                'id',
                'nom',
                'section',
                'option',
                'niveau',
            ]);

        return response()->json($classes);
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX : OPTIONS
    |--------------------------------------------------------------------------
    */

    public function options(Request $request)
    {
        $request->validate([
            'section' => ['required', 'string'],
        ]);

        if (!$this->estHumanites($request->section)) {
            return response()->json([]);
        }

        $options = Classe::query()
            ->where('actif', true)
            ->where('section', $request->section)
            ->whereNotNull('option')
            ->where('option', '!=', '')
            ->select('option')
            ->distinct()
            ->orderBy('option')
            ->pluck('option');

        return response()->json($options);
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX : FRAIS
    |--------------------------------------------------------------------------
    */

    public function frais(Request $request)
    {
        $request->validate([
            'annee_scolaire_id' => ['required', 'integer'],
            'classe_id' => ['required', 'integer'],
        ]);

        $frais = Frais::query()
            ->where('annee_scolaire_id', $request->annee_scolaire_id)
            ->where('classe_id', $request->classe_id)
            ->orderBy('intitule')
            ->get([
                'id',
                'intitule',
                'montant',
                'classe_id',
                'annee_scolaire_id',
            ]);

        return response()->json($frais);
    }


    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    // public function pdf(Request $request)
    // {
    //     /*
    //     | On réutilise exactement la logique de la consultation.
    //     */

    //     $data = $this->calculerSolvabilite($request);

    //     return view(
    //         'solvabilites.pdf',
    //         $data
    //     );
    // }


    /*
    |--------------------------------------------------------------------------
    | CALCUL POUR LE PDF
    |--------------------------------------------------------------------------
    */

    private function calculerSolvabilite(Request $request)
    {
        $anneeScolaire = AnneeScolaire::findOrFail(
            $request->annee_scolaire_id
        );

        $classe = Classe::findOrFail(
            $request->classe_id
        );

        $frais = Frais::query()
            ->where('id', $request->frais_id)
            ->where('annee_scolaire_id', $request->annee_scolaire_id)
            ->where('classe_id', $request->classe_id)
            ->firstOrFail();

        $estMinerval = $this->estMinerval(
            $frais->intitule
        );

        $inscriptionsQuery = Inscription::query()
            ->with([
                'eleve',
                'classe',
            ])
            ->where(
                'annee_scolaire_id',
                $request->annee_scolaire_id
            )
            ->where(
                'classe_id',
                $request->classe_id
            );

        if (
            $this->estHumanites($classe->section)
            && $request->option
        ) {
            $inscriptionsQuery->whereHas(
                'classe',
                function ($query) use ($request) {
                    $query->where(
                        'option',
                        $request->option
                    );
                }
            );
        }

        $inscriptions = $inscriptionsQuery
            ->orderBy('id')
            ->get();

        $enOrdre = collect();
        $partiellementPaye = collect();
        $nonEnOrdre = collect();

        foreach ($inscriptions as $inscription) {

            if (!$inscription->eleve) {
                continue;
            }

            $queryPaiement = Paiement::query()
                ->where(
                    'eleve_id',
                    $inscription->eleve_id
                )
                ->where(
                    'annee_scolaire_id',
                    $request->annee_scolaire_id
                )
                ->where(
                    'frais_id',
                    $frais->id
                );

            if ($estMinerval) {
                $queryPaiement->where(
                    'mois',
                    $request->mois
                );
            }

            $montantPaye = (float) $queryPaiement
                ->sum('montant_paye');

            $montantDu = (float) $frais->montant;

            $restant = max(
                0,
                $montantDu - $montantPaye
            );

            if ($montantPaye <= 0) {
                $statut = 'Non en ordre';
            } elseif ($montantPaye < $montantDu) {
                $statut = 'Partiellement payé';
            } else {
                $statut = 'En ordre';
            }

            $ligne = (object) [
                'eleve' => $inscription->eleve,
                'classe' => $inscription->classe,
                'montant_du' => $montantDu,
                'montant_paye' => $montantPaye,
                'restant' => $restant,
                'statut' => $statut,
            ];

            match ($statut) {
                'En ordre' => $enOrdre->push($ligne),
                'Partiellement payé' => $partiellementPaye->push($ligne),
                default => $nonEnOrdre->push($ligne),
            };
        }

        return compact(
            'anneeScolaire',
            'classe',
            'frais',
            'enOrdre',
            'partiellementPaye',
            'nonEnOrdre',
            'request'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UTILITAIRES
    |--------------------------------------------------------------------------
    */

    private function estMinerval(?string $intitule): bool
    {
        return strtolower(
            trim((string) $intitule)
        ) === 'minerval';
    }


    private function estHumanites(?string $section): bool
    {
        $section = strtolower(
            trim((string) $section)
        );

        $section = str_replace(
            ['é', 'è', 'ê', 'ë'],
            'e',
            $section
        );

        return $section === 'humanites';
    }

    public function pdf(Request $request)
    {
        $data = $this->calculerSolvabilite($request);

        $pdf = Pdf::loadView(
            'solvabilites.pdf',
            $data
        );

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream(
            'solvabilite-' . $data['frais']->intitule . '.pdf'
        );
    }
}
