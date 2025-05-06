<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>You're Invited!</title>
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
                        <img src="{{ asset('img/header.jpg') }}" alt="Noosphere Healing Chamber" style="display:block; max-width:100%; height:auto;" />
                    </td>
                </tr>

                <!-- Main Content -->
                <tr>
                    <td class="content" style="background: linear-gradient(0deg,rgba(7, 32, 114, 1) 0%, rgba(7, 32, 114, 1) 100%);
background-color: #ffffff; padding: 20px; color:#ffffff; ">
                    <h1>Hello, {{$mailData['name'] ?? ''}}</h1>    
                   
                    <p>You have been invited! Click the link below to join:</p>
                    <p><a href="{{ $mailData['invitation_url'] }}" target="__blank">{{ $mailData['invitation_url'] }}</a></p>
                    <p>This link is unique and should not be shared.</p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center">
                        <img src="{{ asset('img/footer.jpg') }}" alt="Footer" style="display:block; max-width:100%; height:auto;" />
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
 

</html>
