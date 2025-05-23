<x-guest-layout>
   <form method="POST" action="{{ route('register') }}">
      @csrf
      <!-- Name -->
      <div>
         <x-input-label for="name" :value="__('Name')" />
         <x-text-input id="name" class="form-control form-input form-control-bordered w-full" type="text" name="name" :value="old('name')" required
            autofocus autocomplete="name" />
         <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div>
      <!-- Email Address -->
      <div class="mt-4">
         <x-input-label for="email" :value="__('Email')" />
         <x-text-input id="email" class="form-control form-input form-control-bordered w-full" type="email" name="email" :value="old('email')"
            required autocomplete="username" />
         <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>
      <!-- Password -->
      <div class="mt-4">
         <x-input-label for="password" :value="__('Password')" />
         <x-text-input id="password" class="form-control form-input form-control-bordered w-full" type="password" name="password" required
            autocomplete="new-password" />
         <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>
      <!-- Confirm Password -->
      <div class="mt-4">
         <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
         <x-text-input id="password_confirmation" class="form-control form-input form-control-bordered w-full" type="password"
            name="password_confirmation" required autocomplete="new-password" />
         <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
      </div>
      <!-- <input type="hidden" name="practitioner_id" value="{{$practitioner}}"> -->
      <input type="hidden" name="user_type" value="2" required  />
      
      <div class="flex items-center justify-end mt-4">
         <a class="text-sm text-gray-500 font-bold no-underline rounded-md"
            href="{{ route('login') }}">
         {{ __('Already registered?') }}
         </a>
         <x-primary-button class="ml-4 btn-submit">
            {{ __('Register') }}
         </x-primary-button>
      </div>
   </form>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {
                  $(".radio-register").click(function() {
                          let user = document.getElementById('user');
                          if (user.checked) {
                              $(".show-practitioner").removeClass("hidden");
                          }else{
                              $(".show-practitioner").addClass("hidden");
                          }
                      });
                  })
   </script>
</x-guest-layout>
