<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-lg font-medium text-white">{{ __('Profile Information') }}</h2>
        <p class="text-sm text-gray-400 mt-2">{{ __('Update your account\'s profile information.') }}</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update.nova') }}">
        @csrf
        @method('POST')

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $user->first_name)" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $user->last_name)" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-4">
                <a href="{{ url('/nova') }}" class="text-sm text-gray-400 hover:text-gray-100">
                    {{ __('← Back to Dashboard') }}
                </a>
                <a href="{{ route('password.change') }}" class="text-sm text-gray-400 hover:text-gray-100">
                    {{ __('Change Password') }}
                </a>
            </div>
            <x-primary-button>
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
