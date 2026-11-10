<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Nova\Http\Requests\NovaRequest;

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
     * Display the Nova change password form.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Inertia\Response
     */
    public function novaChangePassword(NovaRequest $request): Response
    {
        return inertia('ChangePassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Manually validate to have better control
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // If validation fails, return the ChangePassword component with errors
        if ($validator->fails()) {
           
            return redirect()->route('nova.pages.change-password')
                ->withErrors($validator->errors())
                ->withInput();
            return redirect()->route('nova.pages.change-password')->with('error', $validator->errors());

        }

        // Validation passed, update the password
        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_first_login' => 0,
        ]);

        // Return success
        if ($request->header('X-Inertia')) {
            return Inertia::render('ChangePassword', [
                'status' => 'password-updated',
            ]);
        }

        return redirect()->route('nova.pages.change-password')->with('status', 'password-updated');
    }

    /**
     * Display the Nova profile form.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Inertia\Response
     */
    public function novaProfile(NovaRequest $request): Response
    {
        return inertia('Profile', [
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'first_name' => $request->user()->first_name ?? '',
                'last_name' => $request->user()->last_name ?? '',
                'email' => $request->user()->email,
            ],
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information for Nova users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNovaProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        // Derive the display name from first/last name so it stays in sync.
        // The profile form does not submit a "name" field, so requiring it would
        // break updates for users whose name column is empty (non-admin roles).
        $fullName = trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        if ($fullName !== '') {
            $validated['name'] = $fullName;
        }

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Check if this is a Nova route
        if ($request->routeIs('nova.pages.profile.update') || $request->routeIs('profile.update.nova')) {
            return Redirect::route('nova.pages.profile')->with('status', 'profile-updated');
        }

        return back()->with('status', 'profile-updated');
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
