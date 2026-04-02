<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Emetteur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmetteurController extends Controller
{
    public function index()
    {
        $emetteurs = Emetteur::with(['user', 'budget'])->get();
        return view('admin.emetteur.index', compact('emetteurs'));
    }

    public function create()
    {
        $budgets = Budget::all();
        return view('admin.emetteur.create', compact('budgets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'budget_id'  => 'required|exists:budgets,id',
            'dotation'   => 'required|numeric|min:0',
            'profession' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $existingUser = User::where('email', $validated['email'])->first();

            if ($existingUser) {
                if ($existingUser->role !== 'emetteur') {
                    abort(422, 'Cet email est déjà utilisé par un compte non-émetteur.');
                }
                $user = $existingUser;
            } else {
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'password' => Hash::make(Str::random(12)),
                    'role'     => 'emetteur',
                ]);
            }

            Emetteur::create([
                'user_id'    => $user->id,
                'budget_id'  => $validated['budget_id'],
                'dotation'   => $validated['dotation'],
                'profession' => $validated['profession'],
            ]);
        });

        return redirect()->route('admin.emetteurs.index')->with('success', 'Emetteur créé.');
    }

    public function show(Emetteur $emetteur)
    {
        return view('admin.emetteur.show', compact('emetteur'));
    }

    public function edit(Emetteur $emetteur)
    {
        $budgets = Budget::all();
        return view('admin.emetteur.edit', compact('emetteur', 'budgets'));
    }

    public function update(Request $request, Emetteur $emetteur)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($emetteur->user_id)],
            'budget_id' => 'required|exists:budgets,id',
            'dotation' => 'required|numeric|min:0',
            'profession' => 'nullable|string',
        ]);

        $emetteur->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $emetteur->update([
            'budget_id' => $validated['budget_id'],
            'dotation' => $validated['dotation'],
            'profession' => $validated['profession'],
        ]);

        return redirect()->route('admin.emetteurs.index')->with('success', 'Emetteur mis à jour.');
    }

    public function resetPassword(Emetteur $emetteur)
    {
        $newPassword = Str::random(10);
        $emetteur->user->update([
            'password'             => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        return redirect()->route('admin.emetteurs.index')
            ->with('new_password', $newPassword)
            ->with('new_password_user', $emetteur->user->name);
    }

    public function destroy(Emetteur $emetteur)
    {
        $emetteur->user->delete(); // Cascades to emetteur
        return redirect()->route('admin.emetteurs.index')->with('success', 'Emetteur supprimé.');
    }
}
