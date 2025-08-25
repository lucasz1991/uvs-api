<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Token aus Header (Bearer oder X-Api-Key)
        $token = $request->bearerToken() ?? $request->header('X-Api-Key');

        if (!$token) {
            return response()->json(['message' => 'API key missing'], 401);
        }

        // 2. Token hashen
        $hashed = hash('sha256', $token);

        // 3. In der DB nach aktivem, gültigem Schlüssel suchen
        $apiKey = ApiKey::where('token_hash', $hashed)->first();

        if (
            !$apiKey ||
            !$apiKey->active ||
            $apiKey->isExpired()
        ) {
            return response()->json(['message' => 'Invalid or expired API key'], 401);
        }

        // 4. Letzte Nutzung speichern (optional)
        $apiKey->forceFill([
            'last_used_at' => now(),
        ])->save();

        $routeName = $request->route()?->getName();

        if (!$routeName || !$apiKey->hasAbility($routeName)) {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        $request->merge(['apiKey' => $apiKey]);

        return $next($request);
    }
}
