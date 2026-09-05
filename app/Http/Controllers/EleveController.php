<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use App\Models\Paiement;
use App\Models\Inscription;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Liste des élèves
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $eleves = Eleve::query()

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('matricule', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%")
                        ->orWhere('postnom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%");

                });

            })

            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')

            ->paginate(25)

            ->withQueryString();


            return view('eleves.index', compact(
                'eleves',
                'search'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Formulaire de création
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $matricule = $this->genererMatricule();

        return view('eleves.form', compact('matricule'));
    }


    /*
    |--------------------------------------------------------------------------
    | Génération du matricule
    |--------------------------------------------------------------------------
    |
    | Format :
    | ELV-202600001
    |
    | ELV-  = préfixe
    | 2026  = année
    | 00001 = numéro séquentiel
    |
    */

    private function genererMatricule()
    {
        $annee = date('Y');

        $dernierEleve = Eleve::where(
            'matricule',
            'like',
            "ELV-{$annee}%"
        )
        ->orderByDesc('matricule')
        ->first();

        if ($dernierEleve) {

            $dernierNumero = (int) substr(
                $dernierEleve->matricule,
                -5
            );

            $nouveauNumero = $dernierNumero + 1;

        } else {

            $nouveauNumero = 1;

        }

        return 'ELV-' . $annee . str_pad(
            $nouveauNumero,
            5,
            '0',
            STR_PAD_LEFT
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Enregistrer l'élève
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'nom' => 'required|string|max:100',

            'postnom' => 'nullable|string|max:100',

            'prenom' => 'nullable|string|max:100',

            'sexe' => 'nullable|in:M,F',

            'date_naissance' => 'nullable|date',

            'lieu_naissance' => 'nullable|string|max:150',

            'adresse' => 'nullable|string|max:255',

            'telephone' => 'nullable|string|max:30',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'actif' => 'required|boolean',

        ], [

            'nom.required' =>
                'Le nom de l’élève est obligatoire.',

            'sexe.in' =>
                'Le sexe sélectionné est invalide.',

            'date_naissance.date' =>
                'La date de naissance est invalide.',

            'photo.image' =>
                'Le fichier sélectionné doit être une image.',

            'photo.mimes' =>
                'La photo doit être au format JPG, JPEG, PNG ou WEBP.',

            'photo.max' =>
                'La photo ne doit pas dépasser 2 Mo.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Génération réelle du matricule
        |--------------------------------------------------------------------------
        |
        | On ne récupère PAS le matricule envoyé par le formulaire.
        | Le serveur le génère lui-même.
        |
        */

        $matricule = $this->genererMatricule();


        /*
        |--------------------------------------------------------------------------
        | Photo
        |--------------------------------------------------------------------------
        */

        $photo = null;

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo')
                ->store('eleves', 'public');

        }


        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        Eleve::create([

            'matricule' => $matricule,

            'nom' => $request->nom,

            'postnom' => $request->postnom,

            'prenom' => $request->prenom,

            'sexe' => $request->sexe,

            'date_naissance' => $request->date_naissance,

            'lieu_naissance' => $request->lieu_naissance,

            'adresse' => $request->adresse,

            'telephone' => $request->telephone,

            'photo' => $photo,

            'actif' => $request->actif,

            'created_by' => auth()->id(),

        ]);


        return redirect()
            ->route('eleves.index')
            ->with(
                'success',
                'Élève enregistré avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Consultation et modification
    |--------------------------------------------------------------------------
    */

    public function show(Eleve $eleve)
    {
        return view('eleves.show', compact('eleve'));
    }

    public function edit(Eleve $eleve)
    {
        return view('eleves.form', compact('eleve'));
    }

    public function update(Request $request, Eleve $eleve)
    {
        $request->validate([

            'nom' => 'required|string|max:100',

            'postnom' => 'nullable|string|max:100',

            'prenom' => 'nullable|string|max:100',

            'sexe' => 'nullable|in:M,F',

            'date_naissance' => 'nullable|date',

            'lieu_naissance' => 'nullable|string|max:150',

            'adresse' => 'nullable|string|max:255',

            'telephone' => 'nullable|string|max:30',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'actif' => 'required|boolean',

        ]);


        $data = [

            'nom' => $request->nom,

            'postnom' => $request->postnom,

            'prenom' => $request->prenom,

            'sexe' => $request->sexe,

            'date_naissance' => $request->date_naissance,

            'lieu_naissance' => $request->lieu_naissance,

            'adresse' => $request->adresse,

            'telephone' => $request->telephone,

            'actif' => $request->actif,

            'updated_by' => auth()->id(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Nouvelle photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            $data['photo'] = $request->file('photo')
                ->store('eleves', 'public');

        }


        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        |
        | Le matricule n'est volontairement PAS modifié.
        |
        */

        $eleve->update($data);


        return redirect()
            ->route('eleves.index')
            ->with(
                'success',
                'Les informations de l’élève ont été modifiées avec succès.'
            );
    }

    public function toggleStatus(Eleve $eleve)
    {
        $eleve->actif = !$eleve->actif;

        $eleve->updated_by = auth()->id();

        $eleve->save();


        return response()->json([

            'success' => true,

            'message' => $eleve->actif
                ? 'Élève activé avec succès.'
                : 'Élève désactivé avec succès.',

            'actif' => $eleve->actif,

            'statut' => $eleve->statut_libelle,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Parcours d'un élève
    |--------------------------------------------------------------------------
    */
    public function parcours(Request $request, Eleve $eleve)
{
    $inscriptions = $eleve->inscriptions()
        ->with([
            'classe',
            'anneeScolaire',
        ])
        ->get()
        ->sortBy(function ($inscription) {
            return $inscription->anneeScolaire->date_debut;
        })
        ->values();

    return view('eleves.parcours', compact(
        'eleve',
        'inscriptions'
    ));
}

}
