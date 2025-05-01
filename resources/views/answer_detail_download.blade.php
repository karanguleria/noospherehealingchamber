<html>
   <div class="wrapper">
      <form method="post" action="{{ route('profile.thankyou') }}">
         <?php 
            $key = 0; 
            foreach($questions as $elements_key => $elements){ ?>
         <div class="wrap" id="q<?php echo $key+1; ?>">
            <div class="text-center p-4 pb-0 pt-2">
               <div class="h5 text-center">
                  <div class="flex justify-between">
                     <h4>Element: <?php echo $elements_arr[$elements_key]['name']; ?></h4>
                     <h4>Season: <?php echo $elements_arr[$elements_key]['seasone']; ?></h4>
                  </div>
                  {{-- <img class="element-img" style="height:100px;margin:auto" src="/storage/<?php //echo $elements_arr[$elements_key]['image']; ?>"> --}}
                  <div class="flex justify-between">
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
               {{-- <img class="text-center" style="height:100px;margin:auto" src="/storage/<?php //  echo $body_parts['image']; ?>"> --}}
               <h4 class="text-center font-bold headding underline"><?php echo $body_parts_key.": ".$type_heading; ?></h4>
               <hr>
               <?php  foreach($types as $questions_key => $questions){  ?>
               <div class="question">
                  <div class="headding4">
                     <?php echo $counter; ?>. <?php  echo $questions['question']; ?>
                  </div>
                  <div class="pt-0">
                     <label class="options">A. <?php  echo $questions['option_a']; ?>
                     </label>
                     <br>
                     <label class="options">B. <?php  echo $questions['option_b']; ?> 
                     </label>
                     <br>
                     <label class="options">C. <?php  echo $questions['option_c']; ?> </label>
                       <br>
                       <?php if($questions['answer']) { ?>
                     <b>Answer: <?php echo @($questions['answer'] == 1) ? "A" : (($questions['answer'] == 2) ? "B" : "C" ) ; ?></b>
                     <?php  } ?>
                     <br>
                  </div>
               </div>
               <?php  $counter_part++; $counter++; } ?>
            </div>
            <?php } ?>
            <?php }} ?>
           
         </div>
         <?php   
            $key++; 
            }   ?>
      </form>
   </div>
</html>
