@extends('nova-account', ['title' => 'My Profile', 'subtitle' => 'Update your account information'])

@section('content')
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name') <div class="error">{{ $message }}</div> @enderror

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <button type="submit" class="btn">Save Changes</button>
        <a class="back" href="{{ url('/nova') }}">&larr; Back to Dashboard</a>
    </form>
@endsection
