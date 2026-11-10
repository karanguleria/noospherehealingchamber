<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Account' }} - Noosphere Healing Chamber</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Nunito, system-ui, -apple-system, sans-serif;
            background: #161d2c;
            color: #e2e8f0;
            min-height: 100vh;
            padding: 40px 20px;
        }
        .card {
            max-width: 560px;
            margin: 60px auto;
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 12px;
            padding: 32px;
        }
        h1 { font-size: 22px; font-weight: 700; margin-bottom: 6px; }
        .subtitle { color: #9ca3af; font-size: 14px; margin-bottom: 28px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #d1d5db; margin-bottom: 6px; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #4b5563;
            background: #111827;
            color: #f9fafb;
            font-size: 14px;
            margin-bottom: 18px;
        }
        input:focus { outline: none; border-color: #18b69b; }
        .error { color: #f87171; font-size: 12px; margin: -12px 0 14px; }
        .status {
            background: #064e3b;
            color: #6ee7b7;
            border: 1px solid #065f46;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background: #18b69b;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover { background: #14a08a; }
        .back {
            display: inline-block;
            margin-left: 14px;
            color: #9ca3af;
            font-size: 13px;
            text-decoration: none;
        }
        .back:hover { color: #e5e7eb; }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ $title }}</h1>
        <p class="subtitle">{{ $subtitle ?? '' }}</p>

        @if (session('status') === 'password-updated' || session('status') === 'profile-updated')
            <div class="status">Saved successfully.</div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </div>
</body>
</html>
