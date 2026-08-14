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
        | Classes de l'année précédente
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
        | Vérification des élèves déjà réinscrits
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
        | Retour
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
}
