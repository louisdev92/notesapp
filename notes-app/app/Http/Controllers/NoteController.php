<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Auth::user()->notes()
            ->orderByDesc('is_pinned')     // Notes épinglées en haut
            ->orderBy('position')          // Ordre manuel (drag & drop)
            ->orderByDesc('created_at')    // Par défaut, les plus récentes d'abord
            ->get();

        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
        ]);

        Auth::user()->notes()->create($request->only('title', 'content'));

        return redirect()->route('notes.index')->with('success', 'Note créée');
    }

    public function edit(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
        ]);

        $note->update($request->only('title', 'content'));

        return redirect()->route('notes.index')->with('success', 'Note mise à jour');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note supprimée');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            Note::where('id', $item['id'])->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }

    public function togglePin(Note $note)
    {
        $note->is_pinned = !$note->is_pinned;
        $note->save();

        return back();
    }
    public function updatePosition(Request $request, Note $note)
    {
        // Vérifie que l'utilisateur est propriétaire de la note
        if ($note->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Validation simple
        $data = $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        // Met à jour position et épingle (on considère épingle = vrai dès que position mise à jour)
        $note->x_position = $data['x'];
        $note->y_position = $data['y'];
        $note->is_pinned = true; // ou toggle si tu veux gérer différemment
        $note->save();

        return response()->json(['success' => true]);
    }
}
