<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $practitioners = User::where('type_id', 2)->get();
        return view('auth.register')->with('practitioners', $practitioners);
    }
    /**
     * Display the registration view.
     */
    public function createUser(Request $request,string $id): View
    { 
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); 
        $practitioner_id =  Crypt::decrypt($id);
        Cache::put('password_authenticated', true, $seconds = 600);
        return view('auth.register')->with('practitioner', $practitioner_id);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'user_type' => ['required', 'number'],
        ]);
        // if($request->user_type ==2){
            $practitioner_id = '';
        // }else{
        //     $practitioner_id = $request->practitioner_id;
        // }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type_id' => $request->user_type,
            'practitioner_id' => $practitioner_id,
        ]);

        event(new Registered($user));

        return redirect('/nova/resources/users');

        //Auth::login($user);
        /*if ($request->user_type == 1) {
            return redirect(RouteServiceProvider::HOME);
        } else {
            return redirect('/nova');
        }*/ 
        
    }
}
