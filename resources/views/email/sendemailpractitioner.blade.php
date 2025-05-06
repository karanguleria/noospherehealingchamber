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

                    <!-- Header -->
                    <tr>
                        <td align="center">
                            <img src="{{ asset('img/header-blue.jpg') }}" alt="Noosphere Healing Chamber" style="display:block; max-width:100%; height:auto;" />
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td class="content" style="background: linear-gradient(0deg,rgba(7, 32, 114, 1) 0%, rgba(7, 32, 114, 1) 100%);
background-color: #ffffff; padding: 20px; color:#ffffff; ">
                        <h1>Dear {{ucfirst($user->first_name) ?? 'Practitioner'}},</h1>    
                        <h2 style="color: #ffffff; margin-top: 0;">Welcome to the Noosphere Healing Chamber – Your Account Details</h2>
                            <p style="color: #ffffff; line-height: 1.6; font-size: 16px; margin: 20px 0;">
                                Congratulations, your practitioner account has been successfully created!
                            </p>
                            <p style="color: #ffffff; line-height: 1.6; font-size: 16px; margin: 20px 0;">
                                You can now log in and begin using the Noosphere Healing Chamber to support your clients through intentional, focused energy work.
                            </p>
                            <p style="color: #ffffff; line-height: 1.6; font-size: 16px; margin: 20px 0;">
                                Here are your login details:</p>
                            <ul style="list-style:none">
                                <li style="margin-bottom:8px;">Login URL: <a href="https://noospherehealingchamber.exponentialhealthcare.com/nova/login" style="color:#18b69b;">https://noospherehealingchamber.exponentialhealthcare.com/nova/login</a></li>
                                <li style="margin-bottom:8px;">Email: <a href="mailto:{{ $user->email ?? '' }}" style="color:#18b69b;">{{ $user->email ?? '' }}</a></li>
                                <li style="margin-bottom:8px;">Password: <span style="color:#18b69b;">{{ $password ?? '' }}</span></li>
                            </ul>                            
                            <p style="margin-top: 30px;">
                                <a href="{{ url('/') }}" class="button" style="display:inline-block; background-color:#18b69b; color:#1e293b; padding:12px 25px; text-decoration:none; border-radius:5px; font-size: 16px;">Visit Our Website</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
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
