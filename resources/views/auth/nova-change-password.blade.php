<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-lg font-medium text-white">{{ __('Change Password') }}</h2>
        <p class="text-sm text-gray-400 mt-2">{{ __('Ensure your account is using a secure password to stay protected.') }}</p>
    </div>

    @if (session('status') === 'password-updated')
        <div class="mb-4 p-3 bg-green-800 border border-green-600 rounded-md text-sm font-medium text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ __('Your password has been successfully updated!') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update.nova') }}">
        @csrf
        @method('POST')

        <!-- Current Password -->
        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-text-input id="current_password" class="block mt-1 w-full" type="password" name="current_password" required autofocus autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ url('/nova') }}" class="text-sm text-gray-400 hover:text-gray-100">
                {{ __('← Back to Dashboard') }}
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-blue-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Change Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
