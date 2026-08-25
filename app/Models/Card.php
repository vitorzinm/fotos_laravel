<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'image',
    ];
    /**
     * URL pública da imagem do card (storage/app/public/cards/...).
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
