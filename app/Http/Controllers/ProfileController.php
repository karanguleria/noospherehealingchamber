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
    public function question(): View
    {
        $questions = Question::where('state', '1')->get();
        $elements = Element::where('state', '1')->get();
        $elements_arr = [];
         foreach($elements as $key => $element){
            $elements_arr[$element->title]['name'] = $element->title;
            $elements_arr[$element->title]['seasone'] = $element->seasone;
            $elements_arr[$element->title]['description'] = $element->description;
            $elements_arr[$element->title]['image'] = $element->image;

         }
        $question_arr = [];
        foreach($questions as $key => $val){
            $type =  "Physical";
            if($val->type == 1){
                $type = "Physical";
            }else{
                $type ="Mental";
            }
         
            $question_arr[$val->element->title][$val->bodypart->title]['image'] = $val->bodypart->image;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['question'] = $val->title;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['id'] = $val->id;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['option_a'] = $val->option_a;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['option_b'] = $val->option_b;
            $question_arr[$val->element->title][$val->bodypart->title][$type][$key]['option_c'] = $val->option_c;
        }
        return view('question', ['questions' => $question_arr,'elements_arr'=>$elements_arr]);
    }
    public function passwordCheck(): View
    {
        return view('password-protected');
    }
    public function passwordSubmit(Request $request ){
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
                Cache::put('password_authenticated', true, $seconds = 600);
                return redirect('/register');
            }
    }
}
}
