<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class ProfileController extends Controller
{
    //
    public function userProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the user exists in the users table
        if (!User::where('id', $request->user_id)->exists()) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $profile = Profile::updateOrCreate(
            ['user_id' => $request->user_id],
            $request->only([
                'first_name', 'last_name', 'email', 'date_of_birth',
                'place_of_birth', 'religion', 'occupation', 'country', 'city'
            ])
        );

        return response()->json(['message' => 'Profile saved successfully', 'profile' => $profile], 200);
    }
}
