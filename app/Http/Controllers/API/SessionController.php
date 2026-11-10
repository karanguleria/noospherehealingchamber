<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\NovaNotifier;
use Illuminate\Http\Request;
use App\Models\UserSession as Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    //
    public function updateSession(Request $request)
    {
        // Validate input
        $request->validate([
            'sessionId' => 'required|integer',
            'userId' => 'required|integer',
            'type' => 'required|string|in:voice,image', // Ensure type is either 'audio' or 'image'
            'file' => 'required|file',
        ]);

        // Find the record based on sessionId and userId
        $record = Session::where('id', $request->sessionId)
            ->where('user_id', $request->userId)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');
            $url = url(Storage::url($path));

            if ($request->type == 'voice') {
                // Save in the audio field
                $record->audio = $path;
            } elseif ($request->type == 'image') {
                // If image_1 is empty, save the image there
                if (empty($record->image_1)) {
                    $record->image_1 = $path;
                }
                // If image_1 is already filled, save it in image_2
                else {
                    $record->image_2 = $path;
                }
            }
        }

        // Save updated record
        $record->save();

        return response()->json([
            'message' => 'Record updated successfully',
            'url' => $url
        ]);
    }

    public function startSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sessionId' => 'required|integer',
            'userId' => 'required|integer',
            'audioEnabled' => 'required|bool',
            'healingType' => 'required|string',
            'gender' => 'required|string',
            // 'image1' => 'string',
            // 'image2' => 'string',
            'voiceRecordingEnabled' => 'required|integer',
            // 'voiceUrl' => 'string',
            'startDateTime' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $record = Session::where('id', $validated['sessionId'])
            ->where('user_id', $validated['userId'])
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        // Notify only when the exercise actually starts (first healing setup).
        $alreadyStarted = filled($record->healing_type);

        // Update record
        $record->user_id = $validated['userId'];
        $record->audio_enabled = $validated['audioEnabled'];
        $record->healing_type = $validated['healingType'];
        $record->gender = $validated['gender'];
        $record->voice_recording_enabled = $validated['voiceRecordingEnabled'];
        $record->session_start = $validated['startDateTime'];
        $record->save();

        if (!$alreadyStarted) {
            NovaNotifier::sessionStarted($record->fresh(['user']));
        }

        return response()->json([
            'message' => 'User session saved successfully.',
            'session' => $record,
        ]);
    }
}
