<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Affiche la page de profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour les infos utilisateur (nom, email...).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Supprime le compte utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Déconnexion avant suppression
        Auth::logout();

        // Supprime l'avatar si existant
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Supprime l'utilisateur
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Met à jour l'image de profil (avatar).
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        // Vérifie bien que le fichier est reçu
        if (!$request->hasFile('avatar')) {
            return back()->withErrors(['avatar' => 'Aucun fichier reçu']);
        }

        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // max 2 Mo
        ]);

        $user = $request->user();

        // Supprime l'ancien avatar s’il existe
        if ($user->avatar_path && \Storage::disk('public')->exists($user->avatar_path)) {
            \Storage::disk('public')->delete($user->avatar_path);
        }

        // Stocke le nouveau avatar dans "storage/app/public/avatars"
        $path = $request->file('avatar')->store('avatars', 'public');

        // Met à jour le chemin dans la base
        $user->avatar_path = $path;
        $user->save();

        return back()->with('status', 'avatar-updated');
    }
}
