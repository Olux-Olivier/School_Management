<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Eleve;

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
        */

        $query = Inscription::with([
            'eleve',
            'classe',
            'anneeScolaire',
        ])
            ->where(
                'annee_scolaire_id',
                $anneeScolairePrecedente->id
            );


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
        | On utilise le niveau de la classe et non directement
        | la valeur "section" de la base.
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
        | Filtre par section / niveau
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

        $classes = Classe::whereHas('inscriptions', function ($query) use ($anneeScolairePrecedente) {

            $query->where(
                'annee_scolaire_id',
                $anneeScolairePrecedente->id
            );

        })
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Élèves déjà réinscrits
        |--------------------------------------------------------------------------
        */

        $elevesIds = $inscriptions
            ->pluck('eleve_id')
            ->filter()
            ->values();


        $dejaReinscrits = Inscription::where(
            'annee_scolaire_id',
            $anneeScolaireActive->id
        )
            ->whereIn('eleve_id', $elevesIds)
            ->pluck('eleve_id')
            ->flip();


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
                'classes',
                'dejaReinscrits'
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

}
