<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Noosphere Healing Chamber</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Make it mobile friendly */
        @media only screen and (max-width: 620px) {
            .wrapper {
                width: 100% !important;
                padding: 0 10px !important;
            }

            .content {
                padding: 15px !important;
            }

            h2 {
                font-size: 20px !important;
            }

            p {
                font-size: 14px !important;
            }

            a.button {
                display: block !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center;">

    <!-- Outer Wrapper Table -->
    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">

                <!-- Main Email Container -->
                <table class="wrapper" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width: 100%; margin: 0 auto;">

                  <tr>
                        <td style="background-color: #000035;" align="center">
                            <img src="{{ asset('img/mail-header.jpg') }}" alt="Noosphere Healing Chamber" style="display:block; max-width:100%; height:auto;" />
                        </td>
                    </tr>
                    <!-- Main Content -->
                    <tr>
                         <td class="content" style="padding: 20px; color:#000000; background-color:white; ">
                        <p style="color: #000000;font-size: 12px;">Hi  {{ucfirst($user->first_name) ?? 'Practitioner'}},</p>    
                        <!-- <h2 style="color: #000000; margin-top: 0;font-size: 14px;">Get Started with Your Practitioner Account</h2> -->
                            <p style="color: #000000; line-height: 1.6; font-size: 12px; margin: 15px 0;">
                            You’ve been added to the Noosphere Healing Chamber as a {{ $user->type_id == 2 ? 'Practitioner' : 'Client' }}
                            </p>
                            <p style="color: #000000; line-height: 1.6; font-size: 12px; margin: 15px 0;">
                                Here are your login details:
                            </p>
                                
                            <ul style="list-style:none">
                                <li style="margin-bottom:8px;">Email: <a href="mailto:{{ $user->email ?? '' }}" style="color:#18b69b;">{{ $user->email ?? '' }}</a></li>
                                <li style="margin-bottom:8px;">Temporary Password: <span style="color:#18b69b;">{{ $password ?? '' }}</span></li>

                                <li style="margin-bottom:8px;">
                                    Please update your password here: <span style="color:#18b69b;">{{ $resetPasswordLink ?? ''}}<span>
                                </li>

<<<<<<< Updated upstream
                            </ul>                            
                           
                            <p> 
                                If you have any questions or need assistance, feel free to contact us at <a href="mailto:support@projectnoosphere.com">support@projectnoosphere.com</a>
=======
                            </ul>  
                            
                                <p style="margin-top: 30px;">
                                  <a href="https://noospherehealingchamber.exponentialhealthcare.com/" c
                                  lass="button" 
                                  style="display:inline-flex; background-color:#18b69b; color:#ffffff; padding:12px 25px; text-decoration:none; border-radius:5px; font-size: 14px;">Log In Now</a>
                            </p>
                             If you have any questions or need assistance, feel free to contact us at <a style="color:#18b69b;" href="mailto:alumni@quantumuniversity.com">alumni@quantumuniversity.com</a>
                            <br><br>
                            Sincerely,<br>

                            The Noosphere Healing Chamber Team
>>>>>>> Stashed changes
                            </p>
                        </td>
                    </tr>
                            <tr>
                        <td align="center">
                            <img src="{{ asset('img/mail-footer.jpg') }}" alt="Footer" style="display:block; max-width:100%; height:auto;" />
                        </td>
                    </tr>
                    
                </table>

            </td>
        </tr>
    </table>

</body>



</html>
