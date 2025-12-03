<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AssetsApiController extends BaseUvsController
{

    /**
     * GET /api/assets/institutions
     *
     * Lädt die komplette Institut-Tabelle aus der UVS-Datenbank.
     *
     * Response (Beispiel):
     * {
     *   "ok": true,
     *   "data": [
     *      {
     *          "institut_id": 1,
     *          "name": "CBW Hamburg",
     *          "strasse": "...",
     *          "plz": "...",
     *          "ort": "...",
     *          ...
     *      },
     *      ...
     *   ]
     * }
     */
    public function getInstitutions(): JsonResponse
    {
        $this->connectToUvsDatabase();

        // Falls Tabelle anders heißt, bitte anpassen (manchmal: instituts / institute)
        $rows = DB::connection('uvs')
            ->table('institut')
            ->select('*')
            ->orderBy('institut_id')
            ->get();

        return response()->json([
            'ok'   => true,
            'data' => $rows,
        ]);
    }

    /**
     * GET /api/assets/pruef-kennz-options
     *
     * Liefert die Selectbox-Struktur für Person-/Prüf-Kennzeichen
     * aus keydefs (schluessel_name='PRKZ', deleted=0) – sortiert nach text1.
     *
     * Response:
     * {
     *   "ok": true,
     *   "data": {
     *     "pruef_kennz": {
     *       "<schluessel_wert>": "<text1>",
     *       ...
     *     }
     *   }
     * }
     */
    public function getPruefKennzOptions(): JsonResponse
    {
        $this->connectToUvsDatabase();

        $rows = DB::connection('uvs')
            ->table('keydefs')
            ->select(['schluessel_wert', 'text1'])
            ->where('schluessel_name', 'PRKZ')
            ->where('deleted', 0)
            ->orderBy('text1')
            ->get();

        $selectbox = ['pruef_kennz' => []];

        foreach ($rows as $row) {
            $selectbox['pruef_kennz'][$row->schluessel_wert] = $row->text1;
        }

        return response()->json([
            'ok'   => true,
            'data' => $selectbox,
        ]);
    }
}
