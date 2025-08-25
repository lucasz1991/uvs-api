<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'token_hash',
        'active',
        'expires_at',
        'last_used_at',
        'meta',
        'settings',
    ];

    protected $casts = [
        'active'      => 'boolean',
        'expires_at'  => 'datetime',
        'last_used_at'=> 'datetime',
        'meta'        => 'array',
        'settings'    => 'array',
    ];

    /**
     * Erstellt einen neuen API-Key.
     * Gibt sowohl das Model als auch den Klartext-Token zurück.
     */
    public static function mint(array $attributes = []): array
    {
        $plain = Str::random(64); // Klartext
        $hash  = hash('sha256', $plain);

        $model = static::create(array_merge($attributes, [
            'token_hash' => $hash,
        ]));

        return [
            'model' => $model,
            'plain' => $plain, // nur einmal anzeigen!
        ];
    }

    /**
     * Prüft, ob der Key abgelaufen ist.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }

    /**
     * Prüft, ob der Key eine bestimmte Berechtigung hat
     * (falls Abilities im JSON "settings.abilities" gespeichert werden).
     */
    public function hasAbility(string $ability): bool
    {
        $abilities = $this->settings['abilities'] ?? [];
        return in_array($ability, $abilities, true);
    }
}
