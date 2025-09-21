<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\User;
use Carbon\Carbon;
use Mail;
use App\Mail\SendInvitation;

/**
 * SessionController handles the management of user sessions in the Noosphere Healing Chamber.
 * 
 * This controller provides functionality for creating, managing, and ending user sessions,
 * as well as sending invitations and handling session recordings.
 */
class SessionController extends Controller
{
    /**
     * Start or resume a user session in the healing chamber.
     *
     * If session_id is provided, resumes an existing session.
     * Otherwise, creates a new session for the user.
     *
     * @param int $user_id The ID of the user for whom to start/resume a session
     * @param int|null $session_id Optional ID of an existing session to resume
     * @return \Illuminate\View\View Returns the session view with user data
     */
    public function startSession($user_id, $session_id=null){
        $userSession = '';
        $user = User::find($user_id);
            if($session_id){
                $freshSession = true;
                $userSession = UserSession::find($session_id);
            }else{
                $session = UserSession::create([
                    'user_id' => $user_id,
                    'practitioner_id' => $user->id,
                    'session_start' => now(),
                    'state' => 1, // Active session
                ]);
                 $freshSession = false;
            }
            
            $photo1 = (!empty($userSession) && !empty($userSession->image_1)) ? asset('storage/'.$userSession->image_1) : null;
            $photo2 =  (!empty($userSession) && !empty($userSession->image_2)) ? asset('storage/'.$userSession->image_2) : null;
            $gender = !empty($userSession) ? $userSession->gender : '';
            $recording_url = (!empty($userSession) && !empty($userSession->recording_url)) ? $userSession->recording_url : null;
            $type = !empty($userSession) ? $userSession->type : '';
            \Illuminate\Support\Facades\Log::info('Session type from DB', ['session_id' => $session_id, 'type' => $type, 'healing_type' => !empty($userSession) ? $userSession->healing_type : '']);
            
            // If type is empty but healing_type exists, use healing_type instead
            if (empty($type) && !empty($userSession) && !empty($userSession->healing_type)) {
                $type = $userSession->healing_type;
                \Illuminate\Support\Facades\Log::info('Using healing_type instead of type', ['type' => $type]);
            }
            $is_complete = !empty($userSession) ? $userSession->is_complete : '';

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

    /**
     * Show the invitation form for a specific user.
     *
     * @param int $user_id The ID of the user to invite
     * @return \Illuminate\View\View Returns the invitation form view
     */
    public function sendInvitation($user_id){
        $user = User::find($user_id);
        return view('session.send-invitation', [
            'user' => $user,
        ]);
    }

    /**
     * Send an invitation email to join a healing session.
     *
     * @param Request $request The request containing user_id, email, and invitation_url
     * @return \Illuminate\Http\JsonResponse Response with success message
     */
    public function sendInvitationEmail(Request $request){
        $inputs = $request->all();
        $user = \App\Models\User::find($inputs['user_id']);
        
        $mailData = [
            'email' => $inputs['email'],
            'invitation_url' => $inputs['invitation_url'],
            'name' => $user->name,
        ];
        
        Mail::to($inputs['email'])->send(new SendInvitation($mailData));
        return response()->json([
            'message' => 'Invitation sent successfully!'
        ]);
    }


    /**
     * Retrieve created session data.
     *
     * @param int $user_id The ID of the user who owns the session
     * @param int $session_id The ID of the session to retrieve
     * @return \Illuminate\Http\JsonResponse JSON response with session data
     */
    public function createdSession($user_id, $session_id){
        $session = UserSession::find($session_id);
        return response()->json(['message' => 'Session Data', 'session' => $session], 201);
    }
    /**
     * End a user session and mark it as complete if specified.
     *
     * @param int $user_id The ID of the user who owns the session
     * @param int $session_id The ID of the session to end
     * @param bool $is_complete Whether to mark the session as complete
     * @return \Illuminate\Http\RedirectResponse Redirects to the session edit page in Nova
     */
    public function endSession($user_id, $session_id, $is_complete = false){
        $user_session = UserSession::where('id', $session_id)->first();
        $user_session->session_end = Carbon::now();
        $user_session->is_complete = $is_complete;
        $user_session->save();

        $url = env('APP_URL') ."/nova/resources/user-sessions/" . $session_id. "/edit?viaRelationship=userSession&viaResource=users&viaResourceId=".$user_id;
        return redirect($url);
    }
    /**
     * Update the recording URL for a session.
     *
     * @param Request $request The request containing session_id and recording_url
     * @return \Illuminate\Http\JsonResponse Response with success message
     */
    public function updateRecording(Request $request){
        $post_data = $request->all();
        $user_session = UserSession::where('id', $post_data['session_id'])->first();
        $user_session->recording_url = $post_data['recording_url'];
        $user_session->save();
        
        return response()->json([
            'message' => 'Recording url saved successfully!'
        ]);
    }
}
