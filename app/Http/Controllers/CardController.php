<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Mostra o dashboard com os cards, 9 por página.
     */
    public function index(): View
    {
        $cards = Card::with('user')
            ->withCount('likes')
            ->withExists([
                'likes as liked_by_current_user' => fn ($query) => $query->where('user_id' , Auth::id()),
            ])
            ->latest()
            ->paginate(9);

        
        return view('dashboard', [
            'cards' => $cards,
        ]);
    }

    /**
     * Cadastra um novo card com imagem. Somente fotógrafos.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Card::class);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $path = $request->file('image')->store('cards', 'public');

        $request->user()->cards()->create([
            'title' => $validated['title'] ?? null,
            'image' => $path,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'card-created');
    }

    /**
     * Remove um card (somente o dono, que precisa ser fotógrafo).
     */
    public function destroy(Card $card): RedirectResponse
    {
        $this->authorize('delete', $card);

        Storage::disk('public')->delete($card->image);
        $card->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'card-deleted');
    }

    public function like(Card $card) {
        $user = Auth::user();
        
        $like = $card->likes()
        ->where('user_id', $user->id)
        ->first();
        
        if($like)
            $like->delete();
        else 
            $card->likes()->create([
                'user_id' => $user->id,
            ]);
        return back();
    }
}
