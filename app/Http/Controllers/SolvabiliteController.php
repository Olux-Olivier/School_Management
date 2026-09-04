<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SolvabiliteController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */

        $anneesScolaires = AnneeScolaire::orderByDesc('date_debut')->get();


        /*
        |--------------------------------------------------------------------------
        | Sections disponibles
        |--------------------------------------------------------------------------
        */

        $sections = Classe::query()
            ->where('actif', true)
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');


        /*
        |--------------------------------------------------------------------------
        | Valeurs initiales
        |--------------------------------------------------------------------------
        */

        $classes = collect();

        $options = collect();

        $frais = collect();


        /*
        |--------------------------------------------------------------------------
        | Section sélectionnée
        |--------------------------------------------------------------------------
        */

        if ($request->filled('section')) {

            /*
            |--------------------------------------------------------------------------
            | Humanités
            |--------------------------------------------------------------------------
            */

            if ($request->section === 'Humanités') {

                /*
                | Les options viennent directement de la table classes.
                */

                $options = Classe::query()
                    ->where('actif', true)
                    ->where('section', 'Humanités')
                    ->whereNotNull('option')
                    ->where('option', '!=', '')
                    ->select('option')
                    ->distinct()
                    ->orderBy('option')
                    ->pluck('option');


                /*
                |--------------------------------------------------------------------------
                | Si une option est choisie
                |--------------------------------------------------------------------------
                */

                if ($request->filled('option')) {

                    $classes = Classe::query()
                        ->where('actif', true)
                        ->where('section', 'Humanités')
                        ->where('option', $request->option)
                        ->orderBy('niveau')
                        ->orderBy('nom')
                        ->get();

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Autres sections
            |--------------------------------------------------------------------------
            */

            else {

                $classes = Classe::query()
                    ->where('actif', true)
                    ->where('section', $request->section)
                    ->orderBy('niveau')
                    ->orderBy('nom')
                    ->get();

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Frais liés à la classe sélectionnée
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('annee_scolaire_id') &&
            $request->filled('classe_id')
        ) {

            $frais = Frais::query()
                ->where('annee_scolaire_id', $request->annee_scolaire_id)
                ->where('classe_id', $request->classe_id)
                ->orderBy('intitule')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Résultats
        |--------------------------------------------------------------------------
        */

        $enOrdre = collect();

        $partiellementPaye = collect();

        $nonEnOrdre = collect();


        if (
            $request->filled('annee_scolaire_id') &&
            $request->filled('classe_id') &&
            $request->filled('frais_id') &&
            $request->has('rechercher')
        ) {

            $resultats = $this->calculerSolvabilite($request);

            $enOrdre = $resultats['enOrdre'];

            $partiellementPaye = $resultats['partiellementPaye'];

            $nonEnOrdre = $resultats['nonEnOrdre'];
        }


        return view('solvabilites.index', compact(
            'anneesScolaires',
            'sections',
            'classes',
            'options',
            'frais',
            'enOrdre',
            'partiellementPaye',
            'nonEnOrdre'
        ));
    }


    /**
     * ============================================================
     * CLASSES
     * ============================================================
     *
     * Retourne toutes les classes actives de la section.
     *
     * Pour Humanités, l'option est obligatoire pour filtrer.
     */
    public function classes(Request $request)
    {
        $request->validate([
            'section' => 'required|string',
            'option' => 'nullable|string',
        ]);


        $query = Classe::query()
            ->where('actif', true)
            ->where('section', $request->section);


        /*
        |--------------------------------------------------------------------------
        | Humanités
        |--------------------------------------------------------------------------
        */

        if ($request->section === 'Humanités') {

            /*
            | Sans option, on ne retourne aucune classe.
            */

            if (!$request->filled('option')) {

                return response()->json([]);
            }


            $query->where('option', $request->option);
        }


        /*
        |--------------------------------------------------------------------------
        | Résultat
        |--------------------------------------------------------------------------
        */

        $classes = $query
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get([
                'id',
                'nom',
                'niveau',
                'section',
                'option',
            ]);


        return response()->json($classes);
    }


    /**
     * ============================================================
     * OPTIONS
     * ============================================================
     */
    public function options(Request $request)
    {
        $request->validate([
            'section' => 'required|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Les options ne concernent que Humanités
        |--------------------------------------------------------------------------
        */

        if ($request->section !== 'Humanités') {

            return response()->json([]);
        }


        $options = Classe::query()
            ->where('actif', true)
            ->where('section', 'Humanités')
            ->whereNotNull('option')
            ->where('option', '!=', '')
            ->select('option')
            ->distinct()
            ->orderBy('option')
            ->pluck('option');


        return response()->json($options);
    }


    /**
     * ============================================================
     * FRAIS
     * ============================================================
     */
    public function frais(Request $request)
    {
        $request->validate([
            'annee_scolaire_id' => 'required|integer',
            'classe_id' => 'required|integer',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier que la classe existe réellement
        |--------------------------------------------------------------------------
        */

        $classe = Classe::query()
            ->where('id', $request->classe_id)
            ->where('actif', true)
            ->first();


        if (!$classe) {

            return response()->json([]);
        }


        /*
        |--------------------------------------------------------------------------
        | Frais de cette classe pour cette année scolaire
        |--------------------------------------------------------------------------
        */

        $frais = Frais::query()
            ->where('annee_scolaire_id', $request->annee_scolaire_id)
            ->where('classe_id', $classe->id)
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


    /**
     * ============================================================
     * PDF
     * ============================================================
     */
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


    /**
     * ============================================================
     * CALCUL SOLVABILITÉ
     * ============================================================
     */
    private function calculerSolvabilite(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Année scolaire
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::findOrFail(
            $request->annee_scolaire_id
        );


        /*
        |--------------------------------------------------------------------------
        | Classe
        |--------------------------------------------------------------------------
        */

        $classe = Classe::query()
            ->where('id', $request->classe_id)
            ->where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Frais
        |--------------------------------------------------------------------------
        */

        $frais = Frais::query()
            ->where('id', $request->frais_id)
            ->where('annee_scolaire_id', $anneeScolaire->id)
            ->where('classe_id', $classe->id)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Élèves inscrits dans cette classe pour cette année
        |--------------------------------------------------------------------------
        */

        $inscriptions = Inscription::query()
            ->where('annee_scolaire_id', $anneeScolaire->id)
            ->where('classe_id', $classe->id)
            ->with([
                'eleve',
                'classe',
            ])
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Collections
        |--------------------------------------------------------------------------
        */

        $enOrdre = collect();

        $partiellementPaye = collect();

        $nonEnOrdre = collect();


        /*
        |--------------------------------------------------------------------------
        | Calcul
        |--------------------------------------------------------------------------
        */

        foreach ($inscriptions as $inscription) {

            $eleve = $inscription->eleve;


            if (!$eleve) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Montant dû
            |--------------------------------------------------------------------------
            */

            $montantDu = (float) $frais->montant;


            /*
            |--------------------------------------------------------------------------
            | Paiements
            |--------------------------------------------------------------------------
            */

            $queryPaiement = Paiement::query()
                ->where('eleve_id', $eleve->id)
                ->where('annee_scolaire_id', $anneeScolaire->id)
                ->where('frais_id', $frais->id);


            /*
            |--------------------------------------------------------------------------
            | Minerval = paiement par mois
            |--------------------------------------------------------------------------
            */

            if (
                strtolower(trim($frais->intitule)) === 'minerval' &&
                $request->filled('mois')
            ) {

                $queryPaiement->where(
                    'mois',
                    $request->mois
                );
            }


            $montantPaye = (float) (
                $queryPaiement
                    ->sum('montant_paye')
            );


            /*
            |--------------------------------------------------------------------------
            | Restant
            |--------------------------------------------------------------------------
            */

            $restant = max(
                0,
                $montantDu - $montantPaye
            );


            /*
            |--------------------------------------------------------------------------
            | Statut
            |--------------------------------------------------------------------------
            */

            if ($montantPaye == 0) {

                $statut = 'Non en ordre';

            } elseif ($montantPaye < $montantDu) {

                $statut = 'Partiellement payé';

            } else {

                $statut = 'En ordre';
            }


            /*
            |--------------------------------------------------------------------------
            | Ligne résultat
            |--------------------------------------------------------------------------
            */

            $ligne = (object) [

                'eleve' => $eleve,

                'classe' => $classe,

                'montant_du' => $montantDu,

                'montant_paye' => $montantPaye,

                'restant' => $restant,

                'statut' => $statut,
            ];


            /*
            |--------------------------------------------------------------------------
            | Classification
            |--------------------------------------------------------------------------
            */

            if ($statut === 'En ordre') {

                $enOrdre->push($ligne);

            } elseif ($statut === 'Partiellement payé') {

                $partiellementPaye->push($ligne);

            } else {

                $nonEnOrdre->push($ligne);
            }
        }


        return [

            'anneeScolaire' => $anneeScolaire,

            'classe' => $classe,

            'frais' => $frais,

            'request' => $request,

            'enOrdre' => $enOrdre,

            'partiellementPaye' => $partiellementPaye,

            'nonEnOrdre' => $nonEnOrdre,
        ];
    }
}
