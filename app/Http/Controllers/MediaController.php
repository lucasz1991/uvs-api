<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class MediaController extends Controller
{
    protected array $apiSettings = [];

    public function __construct()
    {
        $this->apiSettings['base_api_url'] = Setting::where('key', 'base_api_url')->value('value');
        $this->apiSettings['base_api_key'] = Setting::where('key', 'base_api_key')->value('value');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|max:40960',
        ]);

        $file = $request->file('file');

        $response = Http::attach(
            'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
        )->withHeaders([
            'X-API-KEY' => $this->apiSettings['base_api_key'],
        ])->withoutVerifying()->post($this->apiSettings['base_api_url'] . '/api/admin/upload');

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['success' => false, 'message' => 'Upload fehlgeschlagen.'], $response->status());
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiSettings['base_api_key'],
        ])->withoutVerifying()->delete($this->apiSettings['base_api_url'] . '/api/admin/delete', [
            'path' => $request->path,
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['success' => false, 'message' => 'Löschen fehlgeschlagen.'], $response->status());
    }
}
