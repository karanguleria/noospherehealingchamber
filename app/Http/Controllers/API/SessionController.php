<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
            'audioEnabled' => 'required|integer',
            'healingType' => 'required|string',
            'gender' => 'required|string',
            'image1' => 'required|file',
            'image2' => 'required|file',
            'voiceRecordingEnabled' => 'required|integer',
            'voiceUrl' => 'required|file',
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

        // File uploads
        $image1Path = $request->file('image1')->store('uploads/images', 'public');
        $image2Path = $request->file('image2')->store('uploads/images', 'public');
        $voiceUrlPath = $request->file('voiceUrl')->store('uploads/voices', 'public');

        // Update record
        $record->user_id = $validated['userId'];
        $record->audio_enabled = $validated['audioEnabled'];
        $record->healing_type = $validated['healingType'];
        $record->gender = $validated['gender'];
        $record->image_1 = $image1Path;
        $record->image_2 = $image2Path;
        $record->voice_recording_enabled = $validated['voiceRecordingEnabled'];
        $record->audio = $voiceUrlPath;
        $record->session_start = $validated['startDateTime'];
        $record->save();

        // Append full URLs
        $record->image_1 = url(Storage::url($record->image_1));
        $record->image_2 = url(Storage::url($record->image_2));
        $record->audio = url(Storage::url($record->audio));

        return response()->json([
            'message' => 'User session saved successfully.',
            'session' => $record,
        ]);
    }
}
