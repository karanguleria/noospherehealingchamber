<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Answer;
use App\Models\Element;
use App\Models\Invitation;
use App\Models\Question;
use App\Models\Result;
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
     * Display the Nova change password form.
     *
     * @return \Illuminate\View\View
     */
    public function novaChangePassword(): View
    {
        return view('auth.nova-change-password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        // For debugging
        \Illuminate\Support\Facades\Log::info('updatePassword method called');
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'is_first_login' => 0,
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Display the Nova profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function novaProfile(Request $request): View
    {
        return view('auth.nova-profile', [
            'user' => $request->user(),
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
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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
     * Display the questionnaire interface with all active questions.
     * 
     * Retrieves active questions and elements, organizes them by element and body part,
     * and prepares the data structure for the question view.
     * 
     * @return \Illuminate\View\View
     */
    public function question(): View
    {
        $questions = Question::where('state', '1')->get();
        $elements = Element::where('state', '1')->get();
        $elements_arr = [];
        
        foreach($elements as $key => $element) {
            $elements_arr[$element->title]['name'] = $element->title;
            $elements_arr[$element->title]['seasone'] = $element->seasone;
            $elements_arr[$element->title]['description'] = $element->description;
            $elements_arr[$element->title]['image'] = $element->image;
        }
        
        $question_arr = [];
        foreach($questions as $key => $val) {
            $type = ($val->type == 1) ? "Physical" : "Mental";
         
            $question_arr[$val->element->title][$val->bodypart->title]['image'] = $val->bodypart->image;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['question'] = $val->title;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['id'] = $val->id;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['option_a'] = $val->option_a;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['option_b'] = $val->option_b;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['option_c'] = $val->option_c;
        }
        
        return view('question', ['questions' => $question_arr, 'elements_arr' => $elements_arr]);
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
