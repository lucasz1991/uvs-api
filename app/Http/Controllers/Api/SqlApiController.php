<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SqlApiController extends BaseUvsController
{
    /**
     * POST /api/sql
     * Body: { "query": "SELECT * FROM person JOIN institut USING(institut_id)" }
     */
    public function run(Request $request)
    {
        $data = $request->validate([
            'query' => 'required|string',
        ]);

        $this->connectToUvsDatabase();

        try {
            $sql = trim($data['query']);

            if (preg_match(
                '/\b(insert|update|delete|merge|replace|upsert|alter|drop|create|truncate|rename|grant|revoke|call|handler|load\s+data|outfile|infile|into\s+dumpfile)\b/i',
                $sql
            )) {
                return response()->json([
                    'error' => 'Schreibende oder gefährliche SQL-Befehle sind nicht erlaubt.',
                ], 403);
            }

            $result = DB::connection('uvs')->select($sql, []);


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
