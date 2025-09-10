<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        $request->headers->set('Accept', 'application/json');
        // 1) Token aus Header (Bearer oder X-Api-Key)
        $token = $request->bearerToken() ?? $request->header('X-Api-Key');

        if (!$token) {
            return response()->json(['message' => 'API key missing'], 401);
        }

        // 2) Token hashen und Key suchen
        $hashed = hash('sha256', $token);
        $apiKey = ApiKey::where('token_hash', $hashed)->first();

        // 3) Validität prüfen
        if (!$apiKey || !$apiKey->active || $apiKey->isExpired()) {
            return response()->json(['message' => 'Invalid or expired API key'], 401);
        }

        // 4) User aus ApiKey als authentifizierten User setzen
        if ($apiKey->user) {
            $request->setUserResolver(fn () => $apiKey->user);
            
        }

        if (!$apiKey->user->status) {
            return response()->json(['message' => 'Permission denied - User is not activ'], 401);
        }

        // 5) Letzte Nutzung speichern
        $apiKey->forceFill(['last_used_at' => now()])->save();

        // 6) Ability prüfen
        $routeName = $request->route()?->getName();
        if (!$routeName || !$apiKey->hasAbility($routeName) && !$apiKey->hasAbility('all')) {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        // 7) ApiKey am Request speichern (nicht im Input!)
        $request->attributes->set('apiKey', $apiKey);

        // ============ SICHERES ACTIVITY-LOGGING (ohne Job) ============

        $user = $request->user();
        $description = $user ? 'User' : 'Gast';

        $eventSlug = $request->method() . '-' . Str::slug($request->path() ?: 'root');

        // Header kopieren & API-Key maskieren
        $headers = $request->headers->all();
        if (isset($headers['authorization'])) {
            $headers['authorization'] = ['***masked***'];
        }
        if (isset($headers['x-api-key'])) {
            $headers['x-api-key'] = ['***masked***'];
        }

        // Input ohne API-Key Felder
        $input = $request->except(['api_key', 'apikey']);

        // URL-Query ggf. maskieren (?api_key=..., ?token=..., ...)
        $fullUrl = $this->maskSecretsInUrl($request->fullUrl(), [
            'api_key', 'apikey'
        ]);

        activity('api')
            ->causedBy($user)
            ->withProperties([
                'ip'         => $request->ip(),
                'method'     => $request->method(),
                'path'       => $request->path(),
                'full_url'   => $fullUrl,
                'headers'    => $headers,
                'input'      => $input,
                'user_agent' => $request->header('User-Agent'),
                'route'      => $routeName,
            ])
            ->tap(function ($activity) use ($user, $eventSlug) {
                if ($user) {
                    $activity->subject_type = get_class($user);
                    $activity->subject_id   = $user->id;
                }
                $activity->event = $eventSlug;
            })
            ->log("{$description} - used URL - {$fullUrl} - {$request->method()}");

        // ===============================================================

        return $next($request);
    }

    /**
     * Maskiert definierte Query-Parameter in einer URL.
     */
    protected function maskSecretsInUrl(string $url, array $keysToMask): string
    {
        if (!str_contains($url, '?')) {
            return $url;
        }

        $parts = parse_url($url);
        $query = $parts['query'] ?? '';

        parse_str($query, $params);

        foreach ($params as $k => $v) {
            foreach ($keysToMask as $maskKey) {
                if (strtolower($k) === strtolower($maskKey)) {
                    $params[$k] = '***masked***';
                }
            }
        }

        $rebuiltQuery = http_build_query($params);

        $scheme   = $parts['scheme'] ?? null;
        $host     = $parts['host'] ?? null;
        $port     = isset($parts['port']) ? ':'.$parts['port'] : '';
        $user     = $parts['user'] ?? null;
        $pass     = isset($parts['pass']) ? ':'.$parts['pass'] : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = $parts['path'] ?? '';
        $fragment = isset($parts['fragment']) ? '#'.$parts['fragment'] : '';

        $authHost = ($user ? "$user$pass" : '') . ($host ? $host : '');
        $base     = ($scheme ? "$scheme://" : '') . $authHost . $port . $path;

        return $base . ($rebuiltQuery ? '?'.$rebuiltQuery : '') . $fragment;
    }
}
