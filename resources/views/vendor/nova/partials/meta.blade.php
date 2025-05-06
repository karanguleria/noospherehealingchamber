<script src="{{asset('theme.js')}}"></script>
<script src="{{ asset('js/nova-login-mod.js') }}"></script>
<link href="{{ asset('css/hide-resourse.css') }}">
@if (Route::has('login'))
    <style>
        body.login .hidden.lg\:w-60.shrink-0.md\:flex.items-center a svg {
            background-color:transparent;
        }
    </style>
@else
    <style>
        body.login .hidden.lg\:w-60.shrink-0.md\:flex.items-center a svg {
            background-color: #ffffff;
        }
    </style>
@endif
<style>
    body.nova-login-page {
        background-image: url('/img/noosphere-healing-chamber-dashboard.jpg');
        /*background-image: url('/img/Noosphere-Healing-Chamber-Dashboard-Image_1.jpg');
        background-image: url('/img/Noosphere-Healing-Chamber-Dashboard-Image_2.jpg');*/ 
        background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    position: relative;
    }

 

    body.nova-login-page div[class*="py-6"][class*="px-1"] div[class*="py-8"] svg g{
    fill:#ffffff;
}
body.nova-login-page div[class*="py-6"][class*="px-1"] div[class*="py-8"] span > svg{
    height: 7rem;
    width: 100%;
    background-color: rgba(var(--colors-gray-800));
    /* filter: drop-shadow(3px 5px 2px rgb(0 0 0 / 0.4)); */
    padding-left:20px;
padding-right:20px;
border-radius:.5rem;
}

body.nova-login-page div[class*="py-6"][class*="px-1"] div[class*="py-8"]{
 
}

body.login .hidden.lg\:w-60.shrink-0.md\:flex.items-center a svg {
    /* fill: #ffffff; */
    border-radius: 8px;
    padding: 5px 10px;
    height: 50px;
}
body.login .hidden.lg\:w-60.shrink-0.md\:flex.items-center a+a{
    font-size:9px
}



</style>
</script>
