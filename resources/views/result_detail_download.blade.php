<html>
    <body style="background-color:#0a1944">
        <?php
        $total_excess = 0;
        $total_balance = 0;
        $total_insufficiency = 0;
        $count = 0;
        // echo "<pre>";
        //     print_r($elements);
        //     echo "</pre>";
        //     die("");
        foreach ($elements as $key_element => $element) {
            if($key_element != "total"){
            // print_r($element["message"]);
            $element_image = App\Models\Element::where('title', $key_element)->value('result_img');
            ?>

            <div class="wrapper result-web-page result-download">
                <div  style="max-width:960px; margin:0 auto">
                    <div class="col-md-12 ">
                        <div class="logo" style="text-align:center">
                            <img style="max-width:230px; display:inline;" src="https://quantumevaluation.exponentialhealthcare.com/img/Quantum-evaluation-logo.png" alt="logo">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="some-text" style="text-align:center; margin-bottom:30px; margin-top:30px;">
                                <h1 style="font-family: Poppins, sans-serif; font-size:20px padding-top: 50px; padding-bottom: 15px;">Thank you for completing the Nosphere Healing</h1>
                                <p style="font-size:19px; line-height:normal; margin-bottom:0.8em;font-family: Poppins, sans-serif;">
                                    Your responses are invaluable in crafting a personalized wellness plan tailored uniquely to you. Here are your wellness insights:
                                </p>
                            </div>
                        </div>
                    </div>        
                    <table width="100%" border-collapse="collapse" style="color:#fff;font-family: Poppins, sans-serif;">
                        <tr>
                            <td width="100"> 
                                <h3 style="margin:0 10px 5px 0px;text-align:center;color:#a69340; text-transform:uppercase;"> <?php echo $key_element; ?> </h3>
                                <img style="margin:0 0px 5px 10px;" width="100" height="100" src="https://quantumevaluation.exponentialhealthcare.com/storage/<?php echo $element_image ?>" alt="<?php echo $key_element; ?>"> 
                            </td>
                            <?php foreach ($element as $key_bodytype => $bodytype) {
                              if($key_bodytype !="message"){ ?>
                             
                                <td>
                                    <?php
                                    foreach ($bodytype as $key_type => $type) {
                                        if ($key_type == "Total") {
                                            $total_excess = $total_excess + $type['excess'];
                                            $total_balance = $total_balance + $type['balance'];
                                            $total_insufficiency = $total_insufficiency + $type['insufficiency'];
                                            $count++;
                                        }
                                        ?>
                                        <h4 style="margin:0 0 8px;text-transform:uppercase;padding:30px 5px 10px 5px;"> <?php echo $key_bodytype ?> <br> (<?php echo $key_type ?>)</h4>
                                        <p style="margin:5px;"> EXCESS <span style="float:right;"> <?php echo $type['excess'] ?>% </span></p>
                                        <p style="margin:5px;text-transform:uppercase;"> BALANCE <span style="float:right;"> <?php echo $type['balance'] ?>% </span></p>
                                        <p style="margin:5px;text-transform:uppercase;"> INSUFFICIENCY <span style="float:right;"><?php echo $type['insufficiency'] ?>% </span></p>
        <?php } ?>

                                </td>
    <?php }} ?>
                        </tr>
                    </table>
                </div>
                <table class="result-web-page-footer"  aligne="center" width="100%" border-collapse="collapse" style="background-image: url(https://quantumevaluation.exponentialhealthcare.com/img/total-bg.png);
                       background-size: 100%;
                       background-color: #0a1944;
                       color: #fff;

                       background-repeat: no-repeat;
                       background-position: center bottom;
                       text-align:center;">
                    <tr><td height="50"></td></td>
                    <tr>
                        <td><img style="display:inline; max-width:250px" src="https://quantumevaluation.exponentialhealthcare.com/img/Logo-footer-new.png" alt=""></td>
                    </tr>
                </table>          


            </div>
        <?php
        
        if(@$element['message']){ ?>
             <div class="wrapper result-web-page result-download">
                <div  style="max-width:960px; margin:0 auto">
                    <div class="col-md-12 ">
                        <div class="logo" style="text-align:center">
                            <img style="max-width:230px; display:inline;" src="https://quantumevaluation.exponentialhealthcare.com/img/Quantum-evaluation-logo.png" alt="logo">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="some-text" style="text-align:center; margin-bottom:5px; margin-top:30px;">
                                <h1 style="font-family: Poppins, sans-serif; font-size:20px padding-top: 50px; padding-bottom: 15px;">Thank you for completing the Nosphere Healing</h1>
                                <p style="font-size:19px; line-height:normal; margin-bottom:0.8em;font-family: Poppins, sans-serif;">
                                    Your responses are invaluable in crafting a personalized wellness plan tailored uniquely to you. Here are your wellness insights:
                                </p>
                            </div>
                        </div>
                    </div>        
                    <table width="100%" border-collapse="collapse" style="color:#fff;font-family: Poppins, sans-serif;">
                        <tr>
                            <td style="text-align:center;" width="100"> 
                                <h3 style="margin:0 0px 5px 0px;color:#a69340; text-transform:uppercase;"> <?php echo $key_element; ?></h3>
                                <img width="100" height="100" src="https://quantumevaluation.exponentialhealthcare.com/storage/<?php echo $element_image ?>" alt="<?php echo $key_element; ?>"> 
                            </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4 style="padding:0 0 8px;text-transform:uppercase;padding:30px 5px 10px 5px;"> Interpretation:  </h4>
                                    <p style="margin:5px;"><span style="float:right;"><?php echo $element['message']["a"]; ?></span></p><br>
                                    <h4 style="padding:25px 0 8px;text-transform:uppercase;padding:30px 5px 10px 5px;"> Recommendation:  </h4>
                                    <p style="margin:5px;"> <span style="float:right;"><?php echo $element['message']["b"]; ?></span></p><br>
                                    <h4 style="padding:25px 0 8px;text-transform:uppercase;padding:30px 5px 10px 5px;"> Emotional Component:  </h4>
                                    <p style="margin:5px 5px 0;padding-bottom:25px;" >  <span style="float:right;"><?php echo $element['message']["c"]; ?></span></p><br>
                                </td>
    
                        </tr>
                    </table>
                </div>
                <table class="result-web-page-footer"  aligne="center" width="100%" border-collapse="collapse" 
                style="background-image: url(https://quantumevaluation.exponentialhealthcare.com/img/total-bg.png);
                       background-size: 100%;
                       background-color: #0a1944;
                       color: #fff;
                       margin-top:20px;
                       background-repeat: no-repeat;
                       background-position: center bottom;
                       text-align:center;">
                    <tr><td height="50"></td></td>
                    <tr>
                        <td><img style="display:inline; max-width:250px" src="https://quantumevaluation.exponentialhealthcare.com/img/Logo-footer-new.png" alt=""></td>
                    </tr>
                </table>          


            </div>
        <?php }

        }
    }

// echo $count;
        ?>
        <div class="wrapper result-web-page result-download">
            <div  style="max-width:960px; margin:0 auto">
                <div class="col-md-12 ">
                    <div class="logo" style="text-align:center">
                        <img style="max-width:230px; display:inline;" src="https://quantumevaluation.exponentialhealthcare.com/img/Quantum-evaluation-logo.png" alt="logo">
                    </div>
                </div>
                <div class="row" style="padding-top:80px">
                    <div class="col-md-12">
                        <div class="some-text" style="text-align:center; margin-bottom:30px; margin-top:30px;">
                            <p>Nosphere Healing Result for <?php echo auth()->user()->name ; ?></p>
                            <h1 style="font-family: Poppins, sans-serif; font-size:20px padding-top: 50px; padding-bottom: 15px;">Thank you for completing the Nosphere Healing</h1>
                            <p style="font-size:19px; line-height:normal; margin-bottom:0.8em;font-family: Poppins, sans-serif;">
                                Your responses are invaluable in crafting a personalized wellness plan tailored uniquely to you. Here are your wellness insights:
                            </p>
                        </div>
                    </div>
                </div>    
                <div class="result-web-page-total" style="background-color:#0a1944">
                    <div style="max-width:960px; margin:0 auto; width:100%;">

                        <table aligne="center" width="100%" border-collapse="collapse" style="padding: 0 20px">
                            <tr>
                                <td height="60"></td>
                            </tr>
                            <tr>
                                <td"><h2 style="font-weight:900; font-family: Poppins, sans-serif; font-size:30px; color:#fff">Totals</h2></td>
                                <td  style="text-align:center"><h3 style=" font-size:19px; color:rgb(226, 194, 67);font-family: Poppins, sans-serif;">Excess</h3>
                                    <h4 style="font-weight:500; font-size:19px; color:#fff;font-family: Poppins, sans-serif;" ><?php echo round(($total_excess / $count),2); ?> %</h4></td>
                                <td style="text-align:center"><h3 style=" font-size:19px; color:rgb(226, 194, 67);font-family: Poppins, sans-serif;">Balance</h3>
                                    <h4 style="font-weight:5; font-size:19px; color:#fff;font-family: Poppins, sans-serif;"><?php echo round(($total_balance / $count),2); ?> %</h4></td>
                                <td style="text-align:center"><h3 style=" font-size:19px; color:rgb(226, 194, 67);font-family: Poppins, sans-serif;">Insufficient</h3>
                                    <h4 style="font-weight:500; font-size:19px; color:#fff;font-family: Poppins, sans-serif;"><?php echo round(($total_insufficiency / $count),2); ?> %</h4></td>

                            </tr>
                        </table>
                        <table class="result-web-page-footer-last"  aligne="center" width="100%" border-collapse="collapse" style="background-image: url(https://quantumevaluation.exponentialhealthcare.com/img/total-bg.png);
                               background-size: 100%;
                               background-color: #0a1944;
                               color: #fff;
                            
                               background-repeat: no-repeat;
                               background-position: center bottom;
                               text-align:center;">
                            <tr><td height="50"></td></td>
                            <tr>
                                <td><img style="display:inline; max-width:250px" src="https://quantumevaluation.exponentialhealthcare.com/img/Logo-footer-new.png" alt=""></td>
                            </tr>
                        </table>    
                    </div>
                </div>
    </div>
    </div>

<?php
// for total
if(@$element['message']){ ?>
    <div class="wrapper result-web-page result-download">
       <div  style="max-width:960px; margin:0 auto">
           <div class="col-md-12 ">
               <div class="logo" style="text-align:center">
                   <img style="max-width:230px; display:inline;" src="https://quantumevaluation.exponentialhealthcare.com/img/Quantum-evaluation-logo.png" alt="logo">
               </div>
           </div>
           <div class="row">
               <div class="col-md-12">
                   <div class="some-text" style="text-align:center; margin-bottom:30px; margin-top:30px;">
                       <h1 style="font-family: Poppins, sans-serif; font-size:20px padding-top: 50px; padding-bottom: 15px;">Thank you for completing the Nosphere Healing</h1>
                       <p style="font-size:19px; line-height:normal; margin-bottom:0.8em;font-family: Poppins, sans-serif;">
                           Your responses are invaluable in crafting a personalized wellness plan tailored uniquely to you. Here are your wellness insights:
                       </p>
                   </div>
               </div>
           </div>        
           <table width="100%" border-collapse="collapse" style="color:#fff;font-family: Poppins, sans-serif;">
               <tr>
                   <td style="text-align:center;" width="100"> 
                       <h3 style="margin:0 0px 5px 0px;color:#a69340; text-transform:uppercase;"> <?php echo $key_element; ?> </h3>
                       <img  width="100" height="100" src="https://quantumevaluation.exponentialhealthcare.com/storage/<?php echo $element_image ?>" alt="<?php echo $key_element; ?>"> 
                   </td>
</tr>
                     <tr>  <td>
                            <h4 style="padding:0 0 8px;text-transform:uppercase;padding:30px 5px 10px 5px;"> Interpretation:  </h4>
                               <p style="margin:5px;">  <span style="float:right;"><?php echo $element['message']["a"]; ?></span></p><br>
                               <h4 style="padding:0 0 8px;text-transform:uppercase;padding:30px 5px 10px 5px;"> Recommendation:  </h4>
                               <p style="margin:5px;">  <span style="float:right;"><?php echo $element['message']["b"]; ?></span></p><br>
                               <h4 style="padding:0 0 8px;text-transform:uppercase;padding:30px 5px 10px 5px;"> Emotional Component:  </h4>
                               <p style="margin:5px;"> <span style="float:right;"><?php echo $element['message']["c"]; ?></span></p><br>
                       </td>
               </tr>
           </table>
       </div>
       <table class="result-web-page-footer"  aligne="center" width="100%" border-collapse="collapse" style="background-image: url(https://quantumevaluation.exponentialhealthcare.com/img/total-bg.png);
              background-size: 100%;
              background-color: #0a1944;
              color: #fff;
 margin-top:30px;
              background-repeat: no-repeat;
              background-position: center bottom;
              text-align:center;">
           <tr><td height="50"></td></td>
           <tr>
               <td><img style="display:inline; max-width:250px" src="https://quantumevaluation.exponentialhealthcare.com/img/Logo-footer-new.png" alt=""></td>
           </tr>
       </table>          


   </div>
<?php }

?>





                <script>
                    function print_content() {
                        window.print();
                    }
                </script>
                <style>
                    @import url('https://fonts.gstatic.com/s/poppins/v20/pxiEyp8kv8JHgFVrJJnecnFHGPezSQ.woff2');
                    * {
          padding: 0;
          margin: 0;
          box-sizing: border-box;
          font-family: 'Poppins', sans-serif
        }
                    @media print {

                        .result-download .logo{
                            text-align:center
                        }
                        .result-download .logo img{
                            max-width:280px;
                            display:inline;
                        }
                        .result-download .some-text{
                            text-align:center;
                            margin-bottom:30px;
                        }
                    }
                    .result-web-page {

                        background-image: url(https://quantumevaluation.exponentialhealthcare.com/img/result-Web-page-bg.png);
                        background-size: 100% auto;
                        background-color: #0a1944;
                        color: #fff;
                        padding: 100px 0 20px 0;
                        background-repeat: no-repeat;
                    }
                    .result-web-page-footer {
                        background-image: url(https://quantumevaluation.exponentialhealthcare.com/img/total-bg.png);
                        background-size: 100% auto;
                        background-color: #0a1944;
                        color: #fff;
                        padding: 55px 0 20px 0;
                        background-repeat: no-repeat;
                    }
                    .result-web-page-footer-last {
                        background-image: url(https://quantumevaluation.exponentialhealthcare.com/img/total-bg.png);
                        background-size: 100% auto;
                        background-color: #0a1944;
                        color: #fff;
                        padding: 300px 0 20px 0;
                        background-repeat: no-repeat;
                    }
                    table tr td{
                        padding:5px;
                    }
                    .result-web-page .sec-page {
                        padding-top: 150px;
                    }                  
                </style>
                </body>
                </html>
