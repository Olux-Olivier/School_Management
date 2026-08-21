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
        | Requête
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

                ->orWhere(
                    'section',
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

            $query->where(
                'section',
                $request->section
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
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Année active
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
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        |
        | section n'est PAS utilisée pour déterminer la section réelle.
        | Elle sert uniquement à l'interface.
        |
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

            'section' => [
                'required',
                'string',
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

            'section.required' =>
                'Veuillez sélectionner une section.',

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
        | Récupérer la classe
        |--------------------------------------------------------------------------
        |
        | On récupère la section directement depuis la classe.
        |
        */

        $classe = Classe::where('id', $validated['classe_id'])
            ->where('actif', true)
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
        | SECTION RÉELLE
        |--------------------------------------------------------------------------
        |
        | La section enregistrée dans frais provient de la classe.
        |
        */

        $section = $classe->section;


        /*
        |--------------------------------------------------------------------------
        | Vérification du doublon
        |--------------------------------------------------------------------------
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
                [
                    strtolower(
                        trim($validated['intitule'])
                    )
                ]
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

            /*
            | Section récupérée depuis la classe
            */

            'section' =>
                $section,

            'classe_id' =>
                $classe->id,

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
    */

    public function edit(Frais $frais)
    {
        /*
        |--------------------------------------------------------------------------
        | Année active
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année
        |--------------------------------------------------------------------------
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
        | Charger les relations
        |--------------------------------------------------------------------------
        */

        $frais->load('classe');


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
    */

    public function update(
        Request $request,
        Frais $frais
    ) {

        /*
        |--------------------------------------------------------------------------
        | Année active
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année
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

            'section' => [
                'required',
                'string',
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

            'section.required' =>
                'Veuillez sélectionner une section.',

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
        | Section réelle
        |--------------------------------------------------------------------------
        */

        $section = $classe->section;


        /*
        |--------------------------------------------------------------------------
        | Vérifier le doublon
        |--------------------------------------------------------------------------
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
                [
                    strtolower(
                        trim($validated['intitule'])
                    )
                ]
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
        */

        $frais->update([

            'intitule' =>
                trim($validated['intitule']),

            'montant' =>
                $validated['montant'],

            /*
            | Toujours synchroniser la section avec la classe.
            */

            'section' =>
                $section,

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
    | COMPARAISON
    |--------------------------------------------------------------------------
    */

    public function comparaison(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Années scolaires
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
        | Initialisation
        |--------------------------------------------------------------------------
        */

        $annee1 = null;
        $annee2 = null;

        $resultats = collect();


        /*
        |--------------------------------------------------------------------------
        | Comparaison demandée
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('annee_1') &&
            $request->filled('annee_2')
        ) {

            $annee1 = AnneeScolaire::findOrFail(
                $request->annee_1
            );

            $annee2 = AnneeScolaire::findOrFail(
                $request->annee_2
            );


            /*
            | Les années doivent être différentes
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
            |--------------------------------------------------------------------------
            | Requête année 1
            |--------------------------------------------------------------------------
            */

            $query1 = Frais::with('classe')
                ->where(
                    'annee_scolaire_id',
                    $annee1->id
                );


            /*
            |--------------------------------------------------------------------------
            | Requête année 2
            |--------------------------------------------------------------------------
            */

            $query2 = Frais::with('classe')
                ->where(
                    'annee_scolaire_id',
                    $annee2->id
                );


            /*
            |--------------------------------------------------------------------------
            | Filtre section
            |--------------------------------------------------------------------------
            */

            if ($request->filled('section')) {

                $query1->where(
                    'section',
                    $request->section
                );

                $query2->where(
                    'section',
                    $request->section
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Filtre classe
            |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | Filtre intitulé
            |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | Récupération
            |--------------------------------------------------------------------------
            */

            $frais1 = $query1->get();

            $frais2 = $query2->get();


            /*
            |--------------------------------------------------------------------------
            | Construction des clés
            |--------------------------------------------------------------------------
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
            | Résultats
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


                $montant1 =
                    $fraisAnnee1?->montant;

                $montant2 =
                    $fraisAnnee2?->montant;


                $difference = null;

                $pourcentage = null;

                $statut = 'absent';


                /*
                |--------------------------------------------------------------------------
                | Calcul
                |--------------------------------------------------------------------------
                */

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
        | Vue
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


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | Année active
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where('actif', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Frais
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

        $nombreFrais =
            $frais->count();


        $nombreClasses =
            $frais
                ->pluck('classe_id')
                ->unique()
                ->count();


        $montantTotal =
            $frais->sum('montant');


        $montantMoyen =
            $nombreFrais > 0
                ? $frais->avg('montant')
                : 0;


        /*
        |--------------------------------------------------------------------------
        | Derniers frais
        |--------------------------------------------------------------------------
        */

        $derniersFrais =
            Frais::with('classe')
                ->where(
                    'annee_scolaire_id',
                    $anneeScolaire->id
                )
                ->latest('id')
                ->take(5)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Vue
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
