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

    public function startSession($user_id, $session_id=null){

         /*echo $user_id;
         die;*/ 
        $userSession = '';
        $user = User::find($user_id);
            if($session_id){
                $freshSession = true;
                $userSession = UserSession::find($session_id);
            }else{
                // Create a new session
                $session = UserSession::create([
                    'user_id' => $user_id,
                    'practitioner_id' => $user->id, // Assuming authenticated practitioner
                    'session_start' => now(),
                    'state' => 1, // Active session
                ]);
                 $freshSession = false;
            }
            // dd($userSession);
            $photo1 = (!empty($userSession) && !empty($userSession->image_1)) ? asset('storage/'.$userSession->image_1) : asset('img/noimage.png');
            $photo2 =  (!empty($userSession) && !empty($userSession->image_2)) ? asset('storage/'.$userSession->image_2) : asset('img/noimage.png');
            $gender = !empty($userSession) ? $userSession->gender : '';
            $recording_url = (!empty($userSession) && !empty($userSession->recording_url)) ? $userSession->recording_url : asset('img/nomusic.png');
            $type = !empty($userSession) ? $userSession->type : '';
            $is_complete = !empty($userSession) ? $userSession->is_complete : '';

        //    $data =  [
        //     'user_id' => $user_id,
        //     'session_id' => $session->id ?? $session_id,
        //     'photo1' => $photo1,
        //     'photo2' => $photo2,
        //     'gender' => $gender,
        //     'recording_url' => $recording_url,
        //     'type' => $type,
        //     'is_complete' => $is_complete,
        //     'freshSession' => $freshSession 
        //     ];
        //     dd($data);
        return view('session.start-session', [
            'user_id' => $user_id,
            'session_id' => $session->id ?? $session_id,
            'photo1' => $photo1,
            'photo2' => $photo2,
            'gender' => $gender,
            'recording_url' => $recording_url,
            'type' => $type,
            'is_complete' => $is_complete,
            'freshSession' => $freshSession 
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
    public function endSession($user_id, $session_id, $is_complete = false){
        /*echo $user_id;
        echo $session_id;
        die('Dddd');*/
        // dd($user_id, $session_id, $is_complete );
        $user_session = UserSession::where('id', $session_id)->first();
        $user_session->session_end = Carbon::now();
        $user_session->is_complete = $is_complete;
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
