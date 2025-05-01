<x-app-layout>
    <!-- Sidebar-->
    @include('components.sidebar')
    <!-- Page content wrapper-->
    <div class="wrapper" id="yourDivID">
        <form method="post" action="{{ route('profile.thankyou') }}">
            <?php 
            $key = 0; 
            foreach($questions as $elements_key => $elements){ ?>
            <div class="wrap" id="q<?php echo $key+1; ?>">
                <div class="text-center p-4 pb-0 pt-2">
                    <div class="h5 text-center">
                        <div class="block sm:flex justify-between">
                            <h4>Element: <?php echo $elements_arr[$elements_key]['name']; ?></h4>
                            <h4>Season: <?php echo $elements_arr[$elements_key]['seasone']; ?></h4>
                        </div>
                        <img class="element-img h-32 mx-auto border-2 border-slate-800 rounded-lg my-4" src="/storage/<?php echo $elements_arr[$elements_key]['image']; ?>">
                        <div class="block sm:flex sm:justify-between ">
                            <p><?php echo $elements_arr[$elements_key]['description']; ?></p>
                            <span class="font-bold text-sm">Step <?php echo $key+1; ?> of 5</span>
                        </div>
                    </div>
                </div>
                @csrf
                <?php $counter_part = 1 ; ?>
                <?php $counter = 1 ;
               foreach($elements as $body_parts_key => $body_parts){                
               
                  ?>
                <?php foreach($body_parts as $type_key => $types){ 
               if($type_key !=="image" ) { ?>
                <?php  if($counter_part !==1 ) { ?>
                <hr>
                <?php  } 
            if($type_key == "Mental"){
                     $type_heading = "Vital / Mental";
            }else{
                     $type_heading = "Physical";
            }
            ?>
                <div class="px-4 py-2 cls-<?php echo strtolower($body_parts_key."-".$type_key); ?>">
                    <img class="text-center mb-4 mt-2 shadow-2xl bg-white rounded-md mx-auto h-28" src="/storage/<?php  echo $body_parts['image']; ?>">
                    <h4 class="text-center font-bold headding underline"><?php echo $body_parts_key.": ".$type_heading; ?></h4>
                    <hr>
                    <?php  foreach($types as $questions_key => $questions){  ?>
                    <div class="question">
                        <div class="headding4">
                            <?php echo $counter; ?>. <?php  echo $questions['question']; ?>
                        </div>
                        <div class="pt-0">
                            <label class="options"><?php  echo $questions['option_a']; ?><input
                                    class="radio-btn relative float-left mt-0.5 mr-1 -ml-[1.5rem] h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-1 after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:checked:focus:border-primary"
                                    required="required" type="radio" name="radio[<?php echo strtolower($elements_arr[$elements_key]['name']); ?>][<?php echo strtolower($body_parts_key); ?>][<?php echo strtolower($type_key); ?>][<?php echo $questions['id']; ?>]" value="1">
                                <span class="checkmark"></span>
                            </label>
                            <label class="options"><?php  echo $questions['option_b']; ?> <input
                                    class="radio-btn relative float-left mt-0.5 mr-1 -ml-[1.5rem] h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-1 after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:checked:focus:border-primary"
                                    required="required" type="radio" name="radio[<?php echo strtolower($elements_arr[$elements_key]['name']); ?>][<?php echo strtolower($body_parts_key); ?>][<?php echo strtolower($type_key); ?>][<?php echo $questions['id']; ?>]" value="2">
                                <span class="checkmark"></span>
                            </label>
                            <label class="options"><?php  echo $questions['option_c']; ?> <input
                                    class="radio-btn relative float-left mt-0.5 mr-1 -ml-[1.5rem] h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-1 after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:checked:focus:border-primary"
                                    required="required" type="radio" name="radio[<?php echo strtolower($elements_arr[$elements_key]['name']); ?>][<?php echo strtolower($body_parts_key); ?>][<?php echo strtolower($type_key); ?>][<?php echo $questions['id']; ?>]" value="3">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <?php  $counter_part++; $counter++; } ?>
                </div>
                <?php } ?>
                <?php }} ?>
                <div class="d-flex justify-content-end pt-2 pb-2 pr-5 mb-10 button-section sticky bottom-0 right-0 bg-slate-800">
                    <?php if(@$key){ ?>
                    <input type="button" class="btn btn-primary mr-2 next-previous" id="back<?php echo $key; ?>" value="<- Previous">
                    {{-- <span class="fas fa-arrow-left pr-1"></span>Previous  --}}
                    <?php } ?>

                    <?php if(@$key ==4){ ?>
                    <button class="btn btn-primary mr-2" id="submit">Submit </button>
                    <?php }else{ ?>
                    <input type="button" class="btn btn-primary mr-2 next-previous" id="next<?php echo $key+1; ?>" value="Next ->">
                    {{-- <span class="fas fa-arrow-right pr-1"></span> --}}
                    <?php  } ?>
                </div>
            </div>
            <?php   
            $key++; 
            }   ?>
        </form>
        <div class="spinner-container" style="display:none">
            <div class="spinner-border text-primary" role="status">
            </div><br>
            <span class="spinner"><strong>Please wait, this will take less than a minute.</strong></span>
        </div>

    </div>
    <style>
    .spinner-container {
        position: fixed;
        top: 0;
        /*! transform: translateY(50%); */
        left: 0;
        right: 0;
        margin: 0 auto;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.5);
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        flex-direction: column;
        gap: 15px;
    }

    </style>
    <script>
    $(document).ready(function() {
        $('#submit').click(function() {
            //   $(this).prop('disabled', true);
            $('.spinner-container').show();
        });
    });

    $(document).ready(function() {
        $('.radio-btn').click(function() {
            $('.radio-btn').prop('required', false);
        });
        $('.next-previous').click(function(e) {
            console.log("asd");
            e.preventDefault();
            // $('body').scrollTop(0);
            // document.getElementById('yourDivID').scrollIntoView();
            // $('body, html, .wrapper').scrollTop(0);
            $('body, html, .wrapper').animate({
                scrollTop: 0
            }, '300');
        });
    });
    </script>
</x-app-layout>
