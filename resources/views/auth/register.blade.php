<x-guest-layout>
   <form method="POST" action="{{ route('register') }}">
      @csrf
      <!-- Name -->
      <div>
         <x-input-label for="name" :value="__('Name')" />
         <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
            autofocus autocomplete="name" />
         <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div>
      <!-- Email Address -->
      <div class="mt-4">
         <x-input-label for="email" :value="__('Email')" />
         <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
            required autocomplete="username" />
         <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>
      <!-- Password -->
      <div class="mt-4">
         <x-input-label for="password" :value="__('Password')" />
         <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
            autocomplete="new-password" />
         <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>
      <!-- Confirm Password -->
      <div class="mt-4">
         <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
         <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
            name="password_confirmation" required autocomplete="new-password" />
         <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
      </div>
      <?php if(@$practitioner){ ?>
      <input type="hidden" name="practitioner_id" value="{{$practitioner}}">
      <input type="hidden" name="user_type" value="1" required  />
      <?php }else{ ?>
      <div class="mt-4">
      <input type="hidden" name="user_type" value="2" required  />
         <!-- <x-input-label for="user_type" :value="__('User Type')" /> -->
         
         <div class="flex">
            <!-- <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">                                    
               <input
                  class="radio-register relative float-left mt-0.5 mr-1 -ml-[1.5rem] h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-1 after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                  type="radio"
                  name="user_type"
                  id="user" value="1" required  />
               <label
                  class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                  for="user"
                  >User</label>
            </div> -->
            <!-- <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">               
               <input
                  class="radio-register relative float-left mt-0.5 mr-1 -ml-[1.5rem] h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-1 after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] "
                  type="radio"
                  name="user_type"
                  id="practitioner" value="2" required />
               <label
                  class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                  for="practitioner"
                  >Practitioner</label>
            </div> -->
         </div>
         <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
      </div>
      <div class="mt-4 show-practitioner hidden">
        <x-input-label for="type_id" :value="__('Your Practitioner Name')" />
        <div class="flex justify-center">
        <div class="mb-3 w-full">
            <select data-te-select-init class="form-select w-full" name="practitioner_id">
                <?php foreach($practitioners as $key =>$practitioner){ ?>
                    <option value="<?php echo $practitioner->id; ?>"><?php echo ucfirst($practitioner->name); ?></option>
                    <?php } ?>                
            </select>
        </div>
    </div>
 
        <x-input-error :messages="$errors->get('type_id')" class="mt-2" />
    </div>
    <?php } ?>
      <div class="flex items-center justify-end mt-4">
         <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md"
            href="{{ route('login') }}">
         {{ __('Already registered?') }}
         </a>
         <x-primary-button class="ml-4">
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
