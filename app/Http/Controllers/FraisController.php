<?php

namespace App\Http\Controllers;

use App\Models\Frais;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class FraisController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Afficher uniquement les frais de l'année scolaire active.
    |
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Requête des frais
        |--------------------------------------------------------------------------
        */

        $query = Frais::with([
            'classe',
            'anneeScolaire',
            'createdBy',
            'updatedBy',
        ])
            ->where(
                'annee_scolaire_id',
                $anneeScolaire->id
            );


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

            $section = $request->section;

            $query->whereHas(
                'classe',
                function ($q) use ($section) {

                    $q->where(
                        'section',
                        $section
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
            ->latest('id')
            ->paginate(25)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Retour vue
        |--------------------------------------------------------------------------
        */

        return view(
            'frais.index',
            compact(
                'frais',
                'anneeScolaire'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Formulaire d'ajout d'un nouveau frais.
    |
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Classes actives
        |--------------------------------------------------------------------------
        */

        $classes = Classe::where('actif', true)
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();


        return view(
            'frais.create',
            compact(
                'anneeScolaire',
                'classes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Création d'un frais.
    |
    | IMPORTANT :
    | L'année scolaire n'est jamais récupérée depuis
    | le formulaire.
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

            'intitule' => [
                'required',
                'string',
                'max:255',
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0',
            ],

            'classe_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

        ], [

            'intitule.required' =>
                'L’intitulé du frais est obligatoire.',

            'intitule.string' =>
                'L’intitulé du frais est invalide.',

            'intitule.max' =>
                'L’intitulé du frais ne peut pas dépasser 255 caractères.',

            'montant.required' =>
                'Le montant est obligatoire.',

            'montant.numeric' =>
                'Le montant doit être numérique.',

            'montant.min' =>
                'Le montant ne peut pas être négatif.',

            'classe_id.required' =>
                'Veuillez sélectionner une classe.',

            'classe_id.exists' =>
                'La classe sélectionnée n’existe pas.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
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
        | Vérifier la classe
        |--------------------------------------------------------------------------
        |
        | Une classe inactive ne peut pas recevoir
        | un nouveau frais.
        |
        */

        $classe = Classe::where(
            'id',
            $validated['classe_id']
        )
            ->where(
                'actif',
                true
            )
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
        | Vérifier si le même frais existe déjà
        |--------------------------------------------------------------------------
        |
        | Exemple :
        |
        | Minerval + 1ère Commerciale + 2026-2027
        |
        | ne doit pas être enregistré deux fois.
        |
        */

        $fraisExiste = Frais::where(
            'annee_scolaire_id',
            $anneeScolaire->id
        )
            ->where(
                'classe_id',
                $classe->id
            )
            ->whereRaw(
                'LOWER(intitule) = ?',
                [strtolower(trim($validated['intitule']))]
            )
            ->exists();


        if ($fraisExiste) {

            return back()
                ->withInput()
                ->withErrors([
                    'intitule' =>
                        'Ce frais existe déjà pour cette classe dans l’année scolaire active.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        Frais::create([

            'intitule' =>
                trim($validated['intitule']),

            'montant' =>
                $validated['montant'],

            'classe_id' =>
                $classe->id,

            /*
            | Année active récupérée côté serveur.
            */

            'annee_scolaire_id' =>
                $anneeScolaire->id,

            'created_by' =>
                auth()->id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Retour
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('frais.index')
            ->with(
                'success',
                'Le frais a été ajouté avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | Modifier un frais de l'année active.
    |
    | L'année scolaire n'est PAS modifiable.
    |
    */

    public function edit(Frais $frais)
    {
        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Vérifier que le frais appartient à l'année active
        |--------------------------------------------------------------------------
        |
        | Un frais d'une ancienne année est historique.
        | Il ne doit pas être modifié depuis cette interface.
        |
        */

        if (
            $frais->annee_scolaire_id !== $anneeScolaire->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Classes actives
        |--------------------------------------------------------------------------
        */

        $classes = Classe::where('actif', true)
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Charger la classe
        |--------------------------------------------------------------------------
        */

        $frais->load('classe');


        /*
        |--------------------------------------------------------------------------
        | Retour vue
        |--------------------------------------------------------------------------
        */

        return view(
            'frais.edit',
            compact(
                'frais',
                'anneeScolaire',
                'classes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Modifier :
    |
    | - intitulé
    | - montant
    | - classe
    |
    | MAIS PAS :
    |
    | - annee_scolaire_id
    |
    */

    public function update(
        Request $request,
        Frais $frais
    ) {

        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Vérifier que le frais appartient
        | à l'année active
        |--------------------------------------------------------------------------
        */

        if (
            $frais->annee_scolaire_id !== $anneeScolaire->id
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'intitule' => [
                'required',
                'string',
                'max:255',
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0',
            ],

            'classe_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

        ], [

            'intitule.required' =>
                'L’intitulé du frais est obligatoire.',

            'intitule.string' =>
                'L’intitulé du frais est invalide.',

            'intitule.max' =>
                'L’intitulé du frais ne peut pas dépasser 255 caractères.',

            'montant.required' =>
                'Le montant est obligatoire.',

            'montant.numeric' =>
                'Le montant doit être numérique.',

            'montant.min' =>
                'Le montant ne peut pas être négatif.',

            'classe_id.required' =>
                'Veuillez sélectionner une classe.',

            'classe_id.exists' =>
                'La classe sélectionnée n’existe pas.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        */

        $classe = Classe::where(
            'id',
            $validated['classe_id']
        )
            ->where(
                'actif',
                true
            )
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
        | Vérifier le doublon
        |--------------------------------------------------------------------------
        |
        | On exclut le frais actuellement modifié.
        |
        */

        $fraisExiste = Frais::where(
            'annee_scolaire_id',
            $anneeScolaire->id
        )
            ->where(
                'classe_id',
                $classe->id
            )
            ->whereRaw(
                'LOWER(intitule) = ?',
                [strtolower(trim($validated['intitule']))]
            )
            ->where(
                'id',
                '!=',
                $frais->id
            )
            ->exists();


        if ($fraisExiste) {

            return back()
                ->withInput()
                ->withErrors([
                    'intitule' =>
                        'Ce frais existe déjà pour cette classe dans l’année scolaire active.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        |
        | Nous ne mettons volontairement PAS :
        |
        | 'annee_scolaire_id'
        |
        | dans update().
        |
        | Elle reste donc définitivement liée à l'année
        | dans laquelle le frais a été créé.
        |
        */

        $frais->update([

            'intitule' =>
                trim($validated['intitule']),

            'montant' =>
                $validated['montant'],

            'classe_id' =>
                $classe->id,

            'updated_by' =>
                auth()->id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Retour
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('frais.index')
            ->with(
                'success',
                'Le frais a été modifié avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | COMPARAISON DES FRAIS
    |--------------------------------------------------------------------------
    |
    | Comparer les frais d'une année scolaire à une autre.
    |
    */

    public function comparaison(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Toutes les années scolaires
    |--------------------------------------------------------------------------
    */

    $anneesScolaires = AnneeScolaire::orderByDesc('id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */

    $classes = Classe::orderBy('niveau')
        ->orderBy('nom')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Années sélectionnées
    |--------------------------------------------------------------------------
    */

    $annee1 = null;
    $annee2 = null;

    $resultats = collect();


    /*
    |--------------------------------------------------------------------------
    | Si l'utilisateur lance une comparaison
    |--------------------------------------------------------------------------
    */

    if (
        $request->filled('annee_1') &&
        $request->filled('annee_2')
    ) {

        /*
        |----------------------------------------------------------------------
        | Vérifier que les deux années existent
        |----------------------------------------------------------------------
        */

        $annee1 = AnneeScolaire::findOrFail(
            $request->annee_1
        );

        $annee2 = AnneeScolaire::findOrFail(
            $request->annee_2
        );


        /*
        |----------------------------------------------------------------------
        | Les deux années doivent être différentes
        |----------------------------------------------------------------------
        */

        if ($annee1->id === $annee2->id) {

            return back()
                ->withInput()
                ->withErrors([
                    'annee_2' =>
                        'Veuillez sélectionner deux années scolaires différentes.',
                ]);
        }


        /*
        |----------------------------------------------------------------------
        | Requête année 1
        |----------------------------------------------------------------------
        */

        $query1 = Frais::with('classe')
            ->where(
                'annee_scolaire_id',
                $annee1->id
            );


        /*
        |----------------------------------------------------------------------
        | Requête année 2
        |----------------------------------------------------------------------
        */

        $query2 = Frais::with('classe')
            ->where(
                'annee_scolaire_id',
                $annee2->id
            );


        /*
        |----------------------------------------------------------------------
        | Filtre section
        |----------------------------------------------------------------------
        */

        if ($request->filled('section')) {

            $query1->whereHas(
                'classe',
                function ($query) use ($request) {

                    $query->where(
                        'section',
                        $request->section
                    );
                }
            );

            $query2->whereHas(
                'classe',
                function ($query) use ($request) {

                    $query->where(
                        'section',
                        $request->section
                    );
                }
            );
        }


        /*
        |----------------------------------------------------------------------
        | Filtre classe
        |----------------------------------------------------------------------
        */

        if ($request->filled('classe_id')) {

            $query1->where(
                'classe_id',
                $request->classe_id
            );

            $query2->where(
                'classe_id',
                $request->classe_id
            );
        }


        /*
        |----------------------------------------------------------------------
        | Filtre intitulé
        |----------------------------------------------------------------------
        */

        if ($request->filled('intitule')) {

            $intitule = trim(
                $request->intitule
            );

            $query1->where(
                'intitule',
                'like',
                "%{$intitule}%"
            );

            $query2->where(
                'intitule',
                'like',
                "%{$intitule}%"
            );
        }


        /*
        |----------------------------------------------------------------------
        | Récupération
        |----------------------------------------------------------------------
        */

        $frais1 = $query1->get();

        $frais2 = $query2->get();


        /*
        |--------------------------------------------------------------------------
        | Construction de la comparaison
        |--------------------------------------------------------------------------
        |
        | On crée une clé unique :
        |
        | intitulé + classe
        |
        */

        $fraisCollection = $frais1
            ->concat($frais2)
            ->map(function ($frais) {

                return [
                    'key' =>
                        strtolower(
                            trim($frais->intitule)
                        )
                        . '|'
                        . $frais->classe_id,

                    'intitule' =>
                        $frais->intitule,

                    'classe' =>
                        $frais->classe,

                ];
            })
            ->unique('key')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Construire les résultats
        |--------------------------------------------------------------------------
        */

        foreach ($fraisCollection as $item) {

            $fraisAnnee1 = $frais1
                ->first(function ($frais) use ($item) {

                    return strtolower(
                        trim($frais->intitule)
                    )
                    . '|'
                    . $frais->classe_id
                    === $item['key'];
                });


            $fraisAnnee2 = $frais2
                ->first(function ($frais) use ($item) {

                    return strtolower(
                        trim($frais->intitule)
                    )
                    . '|'
                    . $frais->classe_id
                    === $item['key'];
                });


            $montant1 = $fraisAnnee1?->montant;

            $montant2 = $fraisAnnee2?->montant;


            /*
            |------------------------------------------------------------------
            | Différence
            |------------------------------------------------------------------
            */

            $difference = null;

            $pourcentage = null;

            $statut = 'absent';


            if (
                $montant1 !== null &&
                $montant2 !== null
            ) {

                $difference =
                    $montant2 - $montant1;


                if ($montant1 > 0) {

                    $pourcentage =
                        (
                            $difference
                            / $montant1
                        ) * 100;
                }


                if ($difference > 0) {

                    $statut = 'augmentation';

                } elseif ($difference < 0) {

                    $statut = 'diminution';

                } else {

                    $statut = 'stable';
                }


            } elseif ($montant1 === null) {

                $statut = 'nouveau';

            } elseif ($montant2 === null) {

                $statut = 'supprime';
            }


            $resultats->push([

                'intitule' =>
                    $item['intitule'],

                'classe' =>
                    $item['classe'],

                'montant_1' =>
                    $montant1,

                'montant_2' =>
                    $montant2,

                'difference' =>
                    $difference,

                'pourcentage' =>
                    $pourcentage,

                'statut' =>
                    $statut,

            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Retour
    |--------------------------------------------------------------------------
    */

    return view(
        'frais.comparaison_frais',
        compact(
            'anneesScolaires',
            'classes',
            'annee1',
            'annee2',
            'resultats'
        )
    );
}

/*dashboard*/

public function dashboard()
{
    /*
    |--------------------------------------------------------------------------
    | Année scolaire active
    |--------------------------------------------------------------------------
    */

    $anneeScolaire = AnneeScolaire::where('actif', true)
        ->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | Frais de l'année active
    |--------------------------------------------------------------------------
    */

    $frais = Frais::with('classe')
        ->where(
            'annee_scolaire_id',
            $anneeScolaire->id
        )
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Statistiques
    |--------------------------------------------------------------------------
    */

    $nombreFrais = $frais->count();

    $nombreClasses = $frais
        ->pluck('classe_id')
        ->unique()
        ->count();

    $montantTotal = $frais->sum('montant');

    $montantMoyen = $nombreFrais > 0
        ? $frais->avg('montant')
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Derniers frais ajoutés
    |--------------------------------------------------------------------------
    */

    $derniersFrais = Frais::with('classe')
        ->where(
            'annee_scolaire_id',
            $anneeScolaire->id
        )
        ->latest('id')
        ->take(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Retour
    |--------------------------------------------------------------------------
    */

    return view(
        'frais.dashboard',
        compact(
            'anneeScolaire',
            'nombreFrais',
            'nombreClasses',
            'montantTotal',
            'montantMoyen',
            'derniersFrais'
        )
    );
}
}
