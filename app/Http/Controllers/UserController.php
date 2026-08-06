<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {

            $search = $request->search;

            $users = User::when($search, function ($query) use ($search) {

                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('postnom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");

            })
            ->orderBy('nom')
            ->get();

            return response()->json($users);

        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'postnom' => 'nullable|string|max:100',
            'prenom' => 'nullable|string|max:100',
            'sexe' => 'required|in:M,F',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|confirmed|min:6',
            'type' => 'required|in:Admin,Agent',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'actif' => 'required|boolean',
        ]);

        $photo = null;

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo')->store('users', 'public');

        }

        User::create([

            'nom' => strtoupper($request->nom),

            'postnom' => strtoupper($request->postnom),

            'prenom' => ucfirst(strtolower($request->prenom)),

            'sexe' => $request->sexe,

            'telephone' => $request->telephone,

            'email' => $request->email,

            'username' => $request->username,

            'password' => Hash::make($request->password),

            'type' => $request->type,

            'photo' => $photo,

            'actif' => $request->actif,

            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),

        ]);

        return redirect()
                ->route('users.index')
                ->with('success', 'Utilisateur enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['createdBy', 'updatedBy']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user $user)
    {
        return view('users.edit', compact('user'));
    }

    ########"## Modifier un utilisateur ##########
    public function update(Request $request, User $user)
    {
        $request->validate([

            'nom'=>'required|max:100',

            'postnom'=>'nullable|max:100',

            'prenom'=>'nullable|max:100',

            'sexe'=>'required',

            'telephone'=>'nullable|max:20',

            'email'=>'nullable|email|unique:users,email,'.$user->id,

            'username'=>'required|unique:users,username,'.$user->id,

            'type'=>'required',

            'actif'=>'required',

            'photo'=>'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $photo = $user->photo;

        if($request->hasFile('photo')){

            if($photo && Storage::disk('public')->exists($photo)){

                Storage::disk('public')->delete($photo);

            }

            $photo = $request->file('photo')->store('users','public');

        }

        $user->update([

            'nom'=>strtoupper($request->nom),

            'postnom'=>strtoupper($request->postnom),

            'prenom'=>ucfirst(strtolower($request->prenom)),

            'sexe'=>$request->sexe,

            'telephone'=>$request->telephone,

            'email'=>$request->email,

            'username'=>$request->username,

            'type'=>$request->type,

            'photo'=>$photo,

            'actif'=>$request->actif,

            'updated_by'=>auth()->id(),

        ]);

        return redirect()
                ->route('users.index')
                ->with('success','Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    ########## CHanger le statut actif/inactif d'un utilisateur ##########
    public function toggleStatus(User $user)
    {
        // Empêcher de désactiver son propre compte
        if ($user->id == auth()->id()) {

            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas désactiver votre propre compte.'
            ], 403);

        }

        $user->actif = !$user->actif;
        $user->updated_by = auth()->id();
        $user->save();

        return response()->json([

            'success' => true,

            'message' => $user->actif
                ? 'Utilisateur activé avec succès.'
                : 'Utilisateur désactivé avec succès.',

            'actif' => $user->actif,

            'statut' => $user->statut_libelle

        ]);
    }
}
