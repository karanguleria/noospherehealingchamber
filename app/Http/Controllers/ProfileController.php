<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * ProfileController handles user profile management and questionnaire access
 * 
 * This controller provides functionality for managing user profiles,
 * accessing the questionnaire system, and handling password-protected areas.
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    /**
     * Display the profile page inside Nova.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function novaProfile(Request $request): View
    {
        return view('nova-profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the change password page inside Nova.
     *
     * @return \Illuminate\View\View
     */
    public function novaChangePassword(): View
    {
        return view('change-password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'is_first_login' => 0,
        ]);

        return Redirect::route('nova.pages.change-password')->with('status', 'password-updated');
    }

    /**
     * Display the password protection page.
     * 
     * @return \Illuminate\View\View
     */
    public function passwordCheck(): View
    {
        return view('password-protected');
    }
    /**
     * Validate the submitted password and grant access if correct.
     * 
     * Checks the entered password against the expected value and redirects
     * accordingly. On successful validation, stores authentication in cache
     * for 10 minutes (600 seconds).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordSubmit(Request $request): RedirectResponse
    {
        $correctPassword = 'QuantumEval-EH23'; 
        $enteredPassword = $request->password;
        
        if (Cache::get('password_authenticated')) {
            return redirect('/register');
        }
        
        if ($enteredPassword !== $correctPassword) {
            return redirect('/password-protected')->with('error', 'Incorrect Password');
        }
        
        if ($request->method() === 'POST') {
            if ($enteredPassword === $correctPassword) {
                Cache::put('password_authenticated', true, 600);
                return redirect('/register');
            }
        }
        
        return redirect('/password-protected');
    }
}
