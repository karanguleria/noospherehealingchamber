<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\User;
use Carbon\Carbon;
use Mail;
use App\Mail\SendInvitation;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function startSession($user_id){

         /*echo $user_id;
         die;*/ 
        $user = User::find($user_id);

            // Create a new session
            $session = UserSession::create([
                'user_id' => $user_id,
                'practitioner_id' => $user->id, // Assuming authenticated practitioner
                'session_start' => now(),
                'state' => 1, // Active session
            ]);
         
        return view('session.start-session', [
            'user_id' => $user_id,
            'session_id' => $session->id
        ]);
    }

    public function sendInvitation($user_id){
        //echo $user_id;
        $user = User::find($user_id);
        return view('session.send-invitation', [
            'user' => $user,
        ]);
    }

    public function sendInvitationEmail(Request $request){
        /*echo "<pre>";  print_r($request->all());
        die;*/ 
        $inputs = $request->all();
        $user = \App\Models\User::find($inputs['user_id']);
        

        $mailData = [
            'email' => $inputs['email'],
            'invitation_url' => $inputs['invitation_url'],
            'name' => $user->name,
        ];
        
        Mail::to($inputs['email'])->send(new SendInvitation($mailData));
        return response()->json([
            'message' => 'Invitation sent successfully!',
            //'url' => $url
        ]);
    }


    public function createdSession($user_id, $session_id){
        $session = UserSession::find($session_id);
        return response()->json(['message' => 'Session Data', 'session' => $session], 201);
    }
    public function endSession($user_id, $session_id){
        /*echo $user_id;
        echo $session_id;
        die('Dddd');*/

        $user_session = UserSession::where('id', $session_id)->first();
        $user_session->session_end = Carbon::now();
        $user_session->save();

        /*return view('session.end-session', [
            'user_id' => $user_id,
            'session_id' => $session_id
        ]);*/


        $url = env('APP_URL') ."/nova/resources/user-sessions/" . $session_id. "/edit?viaRelationship=userSession&viaResource=users&viaResourceId=".$user_id;
        return redirect($url);
    }
    public function updateRecording(Request $request){
        $post_data = $request->all();
        $user_session = UserSession::where('id', $post_data['session_id'])->first();
        $user_session->recording_url = $post_data['recording_url'];
        $user_session->save();
        //print_r();
        return response()->json([
            'message' => 'Recording url saved successfully!',
            //'url' => $url
        ]);
    }



}
