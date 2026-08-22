<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\Paiement;
use Illuminate\Http\Request;

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
}
