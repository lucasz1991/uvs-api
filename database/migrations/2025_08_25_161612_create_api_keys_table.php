<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();

            // optional: Zuordnung zu einem User
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Anzeigename / Zweck
            $table->string('name');

            // Der gespeicherte Token-Hash (SHA-256)
            $table->string('token_hash')->unique();

            // Aktiv/Inaktiv
            $table->boolean('active')->default(true);

            // Ablaufdatum
            $table->timestamp('expires_at')->nullable();

            // Letzte Nutzung
            $table->timestamp('last_used_at')->nullable();

            // Freies JSON-Feld (z. B. IP-Whitelist, Notizen, Limits)
            $table->json('meta')->nullable();

            // JSON für Berechtigungen / Settings
            // Beispiel: { "abilities": ["orders.read","users.write"], "rate_limit": 1000 }
            $table->json('settings')->nullable();

            $table->timestamps();

            $table->index(['active', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
