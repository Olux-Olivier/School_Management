<?php

namespace App\Http\Controllers;

use App\Models\Frais;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class HistoriqueFraisController extends Controller
{
    /**
     * Historique des frais
     */
    public function index(Request $request)
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
        | Toutes les années scolaires
        |--------------------------------------------------------------------------
        */

        $anneesScolaires = AnneeScolaire::orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Requête des frais
        |--------------------------------------------------------------------------
        */

        $query = Frais::with([
            'classe',
            'anneeScolaire',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Filtre par année scolaire
        |--------------------------------------------------------------------------
        */

        if ($request->filled('annee_scolaire_id')) {

            $query->where(
                'annee_scolaire_id',
                $request->annee_scolaire_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'intitule',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'classe',
                    function ($classeQuery) use ($search) {

                        $classeQuery
                            ->where(
                                'nom',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'section',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'option',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre par section
        |--------------------------------------------------------------------------
        */

        if ($request->filled('section')) {

            $query->whereHas(
                'classe',
                function ($q) use ($request) {

                    $q->where(
                        'section',
                        $request->section
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $frais = $query
            ->latest('annee_scolaire_id')
            ->latest('id')
            ->paginate(25)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistiques générales
        |--------------------------------------------------------------------------
        */

        $nombreAnnees = $anneesScolaires->count();


        $nombreFrais = Frais::count();


        $nombreFraisAnneeActive = $anneeScolaireActive
            ? Frais::where(
                'annee_scolaire_id',
                $anneeScolaireActive->id
            )->count()
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Retour
        |--------------------------------------------------------------------------
        */

        return view(
            'frais.historique_frais',
            compact(
                'frais',
                'anneesScolaires',
                'anneeScolaireActive',
                'nombreAnnees',
                'nombreFrais',
                'nombreFraisAnneeActive'
            )
        );
    }

    /**
     * Évolution des frais
     */
    public function evolution(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Toutes les années scolaires
        |--------------------------------------------------------------------------
        */

        $anneesScolaires = AnneeScolaire::orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Frais
        |--------------------------------------------------------------------------
        */

        $query = Frais::with([
            'classe',
            'anneeScolaire',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Frais sélectionné
        |--------------------------------------------------------------------------
        */

        if ($request->filled('intitule')) {

            $query->where(
                'intitule',
                $request->intitule
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Classe
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
        | Récupération
        |--------------------------------------------------------------------------
        */

        $frais = $query
            ->orderBy('annee_scolaire_id')
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Liste des intitulés disponibles
        |--------------------------------------------------------------------------
        */

        $intitules = Frais::select('intitule')
            ->distinct()
            ->orderBy('intitule')
            ->pluck('intitule');


        /*
        |--------------------------------------------------------------------------
        | Classes disponibles
        |--------------------------------------------------------------------------
        */

        $classes = \App\Models\Classe::orderBy('niveau')
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Retour
        |--------------------------------------------------------------------------
        */

        return view(
            'frais.evolution_frais',
            compact(
                'anneesScolaires',
                'frais',
                'intitules',
                'classes'
            )
        );
    }
}
