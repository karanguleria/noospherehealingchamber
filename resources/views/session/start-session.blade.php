<!DOCTYPE html>
<html lang="zh_CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ env('APP_URL') }}">
    <title>Noosphere Healing Chamber</title>
    <link rel="stylesheet" href="{{ asset('chamber.css') }}">
    
    <style>
        #widget-container {
            width: 100%;
            height: 100%;
        }

        .end_session {
            position: absolute;
            z-index: 99999;
            margin: 0 auto;
            bottom: 2%;
            align-items: center;
            text-align: center;
            right: 1%;
        }


        .end_session a {
    background: #ff0000a6;
    padding: 10px 30px;
    border-radius: 10px;
    color: #fff;
    position: relative;
    bottom: -6px;
    right: 33px;
    font-size: 14px;
}
        #nsh-canvas {
            height: 100vh !important;
        }

        .end-session-buttons {
    display: flex;
    justify-content: flex-end; /* aligns buttons to the right */
    gap: 10px;
    margin-top: 15px;
    /* position: relative;
    bottom: -6px;
    right: 33px; */
}

.btn-session {
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    color: #fff;
    transition: background-color 0.3s;
}

.btn-session.end {
    background-color: #e74c3c;
}

.btn-session.resume {
    background-color: #2ecc71;
}

.btn-session:hover {
    opacity: 0.85;
}

.end-session-buttons{
        position: fixed;
    bottom: 2px;
    right: 15px;
}

    </style>
    </head>

<body>
    <div id="widget-container"></div>
    <script src="{{ asset('chamber_v1.js')}}"></script>
    <script>               
// More actions

                        var sessionId = {{$session_id}};
                        var userId = {{$user_id}};
                        var photo1 = "{{$photo1 ?? ''}}";
                        var photo2 = "{{$photo2 ?? '' }}";
                        var gender = "{{$gender ?? '' }}";
                        var recording_url = "{{$recording_url ?? null }}";
                        // var uploadAudio = false;
                        // if(recording_url){
                            uploadAudio = true;
                        // }
                        var healingType = "{{$type ?? ''}}";
                        // Default to 'physical' if healingType is empty
                        if (!healingType || healingType.trim() === '') {
                            healingType = 'physical';
                        }
                        var freshSession = "{{$freshSession}}";
                        console.log('Session data:', {
                            healingType: healingType,
                            freshSession: freshSession,
                            typeFromServer: "{{$type}}"
                        });
                        var showControls = true;
                        var showSidebar = false;
                        var baseUrl = "{{ env('APP_URL') }}/"; // Replace with your actual base URL// Replace with your actual base URL
                        var apiUrl = "{{ env('APP_URL') }}/"; // Replace with your actual API URL
                        var containerId = 'widget-container'; // The ID of the container where the widget will be rendered
                        if(freshSession){
                            var options = {
                                containerId: containerId,
                                session: {
                                    intro: false,
                                    gender: gender,
                                    flowStep: "finalShow",
                                    sessionId: sessionId,
                                    userId: userId,
                                    uploadAudio: uploadAudio,
                                    showSidebar: true,
                                    showControls: true,
                                    showSessionLoader: false,
                                    sessionStarted: true,
                                    voiceUrl:recording_url,
                                    photo1: photo1,
                                    photo2: photo2,
                                    showHealingFloor: true,
                                    healingType: healingType,
                                    showHumanModel: true,
                                },
                                baseUrl: baseUrl,
                                apiUrl: apiUrl,
                            };
                        }else{
                            var options = {
                                containerId: containerId,
                                session: {
                                    sessionId: sessionId,
                                    userId: userId,
                                    // photo1: "https://noospherehealingchamber.exponentialhealthcare.com/storage/uploads/HLIgBXUx4VAycmrRrmCCTZ1bsXR4Kzb4rRebWlc2.jpg",
                                    // photo2:  "https://noospherehealingchamber.exponentialhealthcare.com/storage/uploads/8GKctIzKIleO8BMksgGlUQUZ3AMyURLJ5RzS4MB7.jpg",
                                },

                                baseUrl: baseUrl,

                                apiUrl: apiUrl,

                            };
                        }
                        console.log('Final widget options:', options);
                        // Log specifically the healing type being used
                        console.log('Final healing type:', options.session && options.session.healingType);
                        window.renderWidget(options)

    </script>
        <!-- <div class="end_session"><a href="{{route('end.session',['user_id'=>$user_id,'session_id'=>$session_id,'is_complete'=>true])}}">End Session</a></div>

    <div class="end_session"><a href="{{route('end.session',['user_id'=>$user_id,'session_id'=>$session_id, 'is_complete'=>false])}}">Resume Session</a></div> -->

    <div class="end-session-buttons">
    <a href="{{ route('end.session', ['user_id' => $user_id, 'session_id' => $session_id, 'is_complete' => false]) }}" class="btn-session resume">
        Save Session
    </a>
    <a href="{{ route('end.session', ['user_id' => $user_id, 'session_id' => $session_id, 'is_complete' => true]) }}" class="btn-session end">
        End Session
    </a>
    
</div>

</body>

</html>
