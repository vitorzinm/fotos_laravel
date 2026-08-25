<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\User;

class CardPolicy
{
    /**
     * Apenas usuários com a role "fotografo" podem cadastrar novos cards.
     */
    public function create(User $user): bool
    {
        return $user->isFotografo();
    }

    /**
     * Apenas o fotógrafo dono do card pode apagá-lo.
     */
    public function delete(User $user, Card $card): bool
    {
        return $user->isFotografo() && $user->id === $card->user_id;
    }
}
