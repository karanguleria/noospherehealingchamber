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
                        var showControls = true;
                        var showSidebar = false;
                        var baseUrl = "{{ env('APP_URL') }}/"; // Replace with your actual base URL// Replace with your actual base URL
                        var apiUrl = "{{ env('APP_URL') }}/"; // Replace with your actual API URL
                        var containerId = 'widget-container'; // The ID of the container where the widget will be rendered
                        var options = {
                                        containerId: containerId,
                                        session: {
                                            sessionId: sessionId,
                                            userId: userId,
                                        },
                                        baseUrl: baseUrl,
                                        apiUrl: apiUrl,
                                    };
                        window.renderWidget(options)
    </script>
    <div class="end_session"><a href="{{route('end.session',['user_id'=>$user_id,'session_id'=>$session_id])}}">End Session</a></div>
</body>

</html>
