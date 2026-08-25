<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_img'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * A imagem de perfil crua (ou null se o usuário não tiver enviado uma).
     * A imagem padrão é resolvida na view, não aqui — ela é um asset fixo
     * da aplicação, não um arquivo de storage/upload.
     */
    public function getProfileImgAttribute($value): ?string
    {
        return $value ?: null;
    }

    public function isFotografo(): bool
    {
        return $this->role === 'fotografo';
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
