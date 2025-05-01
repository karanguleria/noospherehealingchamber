<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nosphere Healing') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center  selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right w-full h-15" style="max-height: 100px;
                    position: fixed;
                    z-index: 9999;
                    background-color: rgba(0,0,0,0.7);
                    width: 100%;">
                    <div class="container">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-100 hover:text-gray-500 focus:outline-0 focus:rounded-xs">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-100 hover:text-gray-500 focus:outline-0 focus:rounded-xs">Log in</a>
                    @endauth
            </div>
                </div>
            @endif
            <img  class="video-wrap mx-auto mt-24" style="position: fixed;
min-width: 100%;
min-height: 100%;
bottom: 0;
right: 0;
object-fit: cover;"  src="/img/background.jpg"/>
     {{-- <video class="video-wrap mx-auto mt-24" style="position: fixed;
min-width: 100%;
min-height: 100%;
bottom: 0;
right: 0;
object-fit: cover;" poster="https://www.projectnoosphere.com/wp-content/uploads/2022/03/Screenshot-Capture-2022-02-22-05-55-24-1.png" preload="auto" loop="" autoplay="" muted="" id="mejs_8964654255143016_html5" src="https://www.projectnoosphere.com/wp-content/uploads/2022/03/HomePage-FinalBackground-1.mp4" style="width: 1122px; height: 632px;" width="1920" height="800">
                  <source type="video/mp4" src="https://www.projectnoosphere.com/wp-content/uploads/2022/03/HomePage-FinalBackground-1.mp4">
                  <object type="application/x-shockwave-flash" data="https://www.projectnoosphere.com/wp-content/themes/bridge/js/flashmediaelement.swf" class="skrollable skrollable-after" width="320" height="240">
                     <param name="movie" value="https://www.projectnoosphere.com/wp-content/themes/bridge/js/flashmediaelement.swf">
                     <param name="flashvars" value="controls=true&amp;file=https://www.projectnoosphere.com/wp-content/uploads/2022/03/HomePage-FinalBackground-1.mp4">
                     <span class="web-developer-display-image-paths"><a href="https://www.projectnoosphere.com/wp-content/uploads/2022/03/Screenshot-Capture-2022-02-22-05-55-24-1.png">src="https://www.projectnoosphere.com/wp-content/uploads/2022/03/Screenshot-Capture-2022-02-22-05-55-24-1.png"</a></span><img itemprop="image" src="https://www.projectnoosphere.com/wp-content/uploads/2022/03/Screenshot-Capture-2022-02-22-05-55-24-1.png" title="No video playback capabilities" alt="Video Thumb" width="1920" height="800">
                  </object>
               </video> --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-28 sm:pt-0 z-50 relative">
            <!-- <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div> -->
            <h4 class="bg-white p-2 rounded-lg text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Nosphere Healing</h4>
            <div class="w-full max-w-xs sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
               
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
