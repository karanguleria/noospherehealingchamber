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
                        <td align="center">
                            <img src="{{ asset('img/header-blue.jpg') }}" alt="Noosphere Healing Chamber" style="display:block; max-width:100%; height:auto;" />
                        </td>
                    </tr>
                    <!-- Main Content -->
                    <tr>
                         <td class="content" style="background: linear-gradient(0deg,rgba(7, 32, 114, 1) 0%, rgba(7, 32, 114, 1) 100%);
background-color: #ffffff; padding: 20px; color:#ffffff; ">
                        <h1 style="color: #ffffff;">Dear {{$name ?? '' }},</h1>    
                        <h2 style="color: #ffffff; margin-top: 0;">Get Started with Your Practitioner Account</h2>
                            <p style="color: #ffffff; line-height: 1.6; font-size: 16px; margin: 20px 0;">
                            We invite you to join the Noosphere Healing Chamber, a purpose-built space that empowers you to deliver focused, intentional energy support to your clients.
                            </p>
                            <p style="color: #ffffff; line-height: 1.6; font-size: 16px; margin: 20px 0;">
                                Once you create your account, you’ll be able to set up client profiles, start new healing sessions, upload visuals such as blood microscopy images and voice recordings, add session notes, and review past session details, all in one place.
                            </p>

                            <p style="color: #ffffff; line-height: 1.6; font-size: 16px; margin: 20px 0;">
                            Getting started is simple. Just click the button below to create your practitioner account and begin working with the Chamber’s unique features.
                            </p>

                            <p style="margin-top: 30px;">
                                  <a href="https://noospherehealingchamber.exponentialhealthcare.com/register-user/{{$id}}" class="button" style="display:inline-block; background-color:#18b69b; color:#1e293b; padding:12px 25px; text-decoration:none; border-radius:5px; font-size: 16px;">Create Your Account</a>
                            </p>

                            <p  style="color: #ffffff; line-height: 1.6; font-size: 16px; margin: 20px 0;">
                            Thank you for being part of this important work. We look forward to supporting you every step of the way.
                            <br><br>
                            Sincerely,<br>

                            The Noosphere Healing Chamber Team
                            </p>
                        </td>
                    </tr>
<tr>
                        <td align="center">
                            <img src="{{ asset('img/footer-blue.jpg') }}" alt="Footer" style="display:block; max-width:100%; height:auto;" />
                        </td>
                    </tr>
                    
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
