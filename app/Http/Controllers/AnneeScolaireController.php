<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AnneeScolaireController extends Controller
{
    /**
     * Liste des années scolaires
     */
    public function index()
    {
        $annees = AnneeScolaire::orderBy('date_debut', 'desc')->get();

        return view('annees_scolaires.index', compact('annees'));
    }

    /**
     * Formulaire d'ajout
     */
    public function create()
    {
        return view('annees_scolaires.form');
    }

    /**
     * Enregistrer une année scolaire
     */
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => ['required', 'string', 'max:9', 'unique:annee_scolaires,libelle'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
        ]);

        AnneeScolaire::create([
            'libelle' => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'actif' => false,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('annees.index')
            ->with('success', 'Année scolaire ajoutée avec succès.');
    }

    /**
     * Afficher une année scolaire
     */
    public function show(AnneeScolaire $annee)
    {
        return view('annees_scolaires.show', compact('annee'));
    }

    /**
     * Formulaire de modification
     */
    public function edit(AnneeScolaire $annee)
    {
        return view('annees_scolaires.form', compact('annee'));
    }

    /**
     * Modifier une année scolaire
     */
    public function update(Request $request, AnneeScolaire $annee)
    {
        $request->validate([
            'libelle' => [
                'required',
                'string',
                'max:9',
                'unique:annee_scolaires,libelle,' . $annee->id,
            ],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
        ]);

        $annee->update([
            'libelle' => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('annees.index')
            ->with('success', 'Année scolaire modifiée avec succès.');
    }

    /**
     * Activer une année scolaire
     */
    public function toggleStatus(AnneeScolaire $annee)
    {
        // Si on veut activer cette année
        if ($annee->actif) {

            return response()->json([
                'success' => false,
                'message' => 'L’année scolaire active ne peut pas être désactivée directement.'
            ], 400);
        }

        AnneeScolaire::where('id', '!=', $annee->id)
            ->update([
                'actif' => false,
                'updated_by' => auth()->id(),
            ]);

        $annee->actif = true;
        $annee->updated_by = auth()->id();
        $annee->save();

        return response()->json([
            'success' => true,
            'message' => 'Année scolaire activée avec succès.',
        ]);
    }
}
