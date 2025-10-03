<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;


abstract class BaseUvsController extends Controller
{
    /**
     * Verbindung zur UVS-Datenbank herstellen
     */
    protected function connectToUvsDatabase(): void
    {
        config(['database.connections.uvs' => [
            'driver'    => 'mysql',
            'host'      => Setting::getValue('database', 'hostname'),
            'database'  => Setting::getValue('database', 'database'),
            'username'  => Setting::getValue('database', 'username'),
            'password'  => Setting::getValue('database', 'password'),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]]);
    }
}
