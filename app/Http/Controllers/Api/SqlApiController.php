<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class SqlApiController extends Controller
{
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

    /**
     * POST /api/sql
     * Body: { "query": "SELECT * FROM person LIMIT 5" }
     */
    public function run(Request $request)
    {
        $data = $request->validate([
            'query' => 'required|string|max:2000',
        ]);

        $this->connectToUvsDatabase();

        try {
            $sql = trim($data['query']);

            // Nur SELECTs erlauben (sicherer)
            if (!preg_match('/^select/i', $sql)) {
                return response()->json([
                    'error' => 'Nur SELECT-Abfragen sind erlaubt.'
                ], 403);
            }

            $result = DB::connection('uvs')->select(DB::raw($sql));

            return response()->json([
                'query'  => $sql,
                'result' => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
