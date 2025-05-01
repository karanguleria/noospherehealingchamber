<!DOCTYPE html>
<html>

<head>
    <title>You're Invited!</title>
</head>

<body>
    <p>Hello, {{$mailData['name'] ?? ''}}</p>
    <p>You have been invited! Click the link below to join:</p>
    <p><a href="{{ $mailData['invitation_url'] }}" target="__blank">{{ $mailData['invitation_url'] }}</a></p>
    <p>This link is unique and should not be shared.</p>
</body>

</html>
