<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseUvsController extends Controller
{

    protected function connectToUvsDatabase(?bool $forceDev = null): void
    {

        $request = request();
        $isDev = $forceDev ?? (bool) ($request?->attributes->get('isdevdb', false));

        $hostKey     = $isDev ? 'hostname_dev' : 'hostname';
        $dbKey       = $isDev ? 'database_dev' : 'database';
        $userKey     = $isDev ? 'username_dev' : 'username';
        $passKey     = $isDev ? 'password_dev' : 'password';

        $host = Setting::getValue('database', $hostKey);
        $db   = Setting::getValue('database', $dbKey);
        $user = Setting::getValue('database', $userKey);
        $pass = Setting::getValue('database', $passKey);

        config(['database.connections.uvs' => [
            'driver'    => 'mysql',
            'host'      => $host,
            'database'  => $db,
            'username'  => $user,
            'password'  => $pass,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ]]);

        DB::purge('uvs');
        DB::reconnect('uvs');

        Log::debug('[UVS] connected', ['env' => $isDev ? 'dev' : 'live', 'db' => $db, 'host' => $host]);
    }
}
