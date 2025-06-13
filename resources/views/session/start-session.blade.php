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
    </style>
    </head>

<body>
    <div id="widget-container"></div>
    <script src="{{ asset('chamber.js')}}"></script>
     <script>               
                        var sessionId = {{$session_id}};
                        var userId = {{$user_id}};
                        var photo1 = "{{$photo1 ?? ''}}";
                        var photo2 = "{{$photo2 ?? '' }}";
                        var gender = "{{$gender ?? '' }}";
                        var recording_url =" {{$recording_url ?? '' }}";
                        var type = "{{$type ?? ''}}";
                        var freshSession = "{{$freshSession}}";
                        var showControls = true;
                        var showSidebar = false;
                        var baseUrl = "{{ env('APP_URL') }}/"; // Replace with your actual base URL// Replace with your actual base URL
                        var apiUrl = "{{ env('APP_URL') }}/"; // Replace with your actual API URL
                        var containerId = 'widget-container'; // The ID of the container where the widget will be rendered
                        if(!freshSession){
                            var options = {
                                containerId: containerId,
                                session: {
                                    gender: gender,
                                    flowStep: type,
                                    sessionId: sessionId,
                                    userId: userId,
                                    uploadAudio: false,
                                    showSidebar: true,
                                    showControls: true,
                                    showSessionLoader: false,
                                    sessionStarted: false,
                                    voiceUrl:recording_url,
                                    photo1: photo1,
                                    photo2: photo2,
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
                        
                        window.renderWidget(options)
    </script>
        <div class="end_session"><a href="{{route('end.session',['user_id'=>$user_id,'session_id'=>$session_id,'is_complete'=>true])}}">End Session</a></div>

    <div class="end_session"><a href="{{route('end.session',['user_id'=>$user_id,'session_id'=>$session_id, 'is_complete'=>false])}}">Resume Session</a></div>
</body>

</html>
