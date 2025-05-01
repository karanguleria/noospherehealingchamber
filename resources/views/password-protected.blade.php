<x-guest-layout>

<form method="POST" action="/password-protected">
    @csrf
    <input type="password" name="password" placeholder="Enter password">
    <x-primary-button class="ml-3">
                {{ __('Register') }}
            </x-primary-button>
    @if(session('error'))
        <p class="error-password">{{ session('error') }}</p>
    @enderror
</form>

<style>
        .error-password{
                            display: block;
                            text-align: center;
                            margin-top: 10px;
                            color: red;
                    }
</style>
   
</x-guest-layout>


