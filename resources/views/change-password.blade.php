@extends('nova-account', ['title' => 'Change Password', 'subtitle' => 'Update your account password'])

@section('content')
    <form method="POST" action="{{ url('/change-password') }}">
        @csrf

        <label for="current_password">Current Password</label>
        <input id="current_password" type="password" name="current_password" autocomplete="current-password" required>
        @error('current_password') <div class="error">{{ $message }}</div> @enderror

        <label for="password">New Password</label>
        <input id="password" type="password" name="password" autocomplete="new-password" required>
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <label for="password_confirmation">Confirm New Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
        @error('password_confirmation') <div class="error">{{ $message }}</div> @enderror

        <button type="submit" class="btn">Update Password</button>
        <a class="back" href="{{ url('/nova') }}">&larr; Back to Dashboard</a>
    </form>
@endsection
