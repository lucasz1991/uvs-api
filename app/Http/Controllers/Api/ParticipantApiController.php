<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParticipantApiController extends Controller
{
    /**
     * Neuen Teilnehmer speichern oder vorhandenen aktualisieren
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id'        => 'sometimes',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'nullable|string|max:100',
            'meta'      => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $participant = Participant::updateOrCreate(
            ['id' => $data['id'] ?? null],
            $validator->validated()
        );

        return response()->json([
            'message'     => 'Participant saved successfully',
            'participant' => $participant,
        ]);
    }
}
