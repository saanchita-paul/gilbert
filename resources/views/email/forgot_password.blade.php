<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
    <noscript>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    </noscript>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');

        table, td, div, h1, p {font-family: 'Ubuntu', Arial, sans-serif}
        .main-container {
            width:770px;
            border-collapse:collapse;
            text-align:left;
        }
        @media only screen and (max-width:576px) {
            .main-container {
                width: 100%;
            }
        }
    </style>
</head>
<body style="margin:0;padding:0;">
<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
        <td align="center" style="padding:0;">
            <table role="presentation" class="main-container" >
                <tr>
                    <td>
                        <img src="{{ asset('/assets/images/email/pw_reset.png') }}" alt="Banner Image" width="100%" style="height:auto;display:block;border-top-left-radius:10px; border-top-right-radius:10px;" />
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 30px 30px 30px;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td style="padding:0 0 20px 0;color:#252830;">
                                    <h1 style="font-size:24px;margin:0 0 20px 0;font-family:'Ubuntu', Arial, sans-serif;font-weight:700;line-height:31px">Hi {{ $name }}! It happens...</h1>
                                    <p style="margin:0 0 12px 0;font-size:14px;line-height:18px;font-family:'Ubuntu', Arial, sans-serif;font-weight:400;">But don’t worry, we got you covered! <br>
                                        We received a request from you to reset your password.</p>
                                    <p style="margin:0 0 12px 0;font-size:14px;line-height:18px;font-family:'Ubuntu', Arial, sans-serif;font-weight:400;">If you requested to reset your password, please click the button below:</p>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:0 0 20px 0">
                                    <!--[if mso]>
                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ url('/reset/password/' .$token) }}" style="height:36px;v-text-anchor:middle;width:150px;" arcsize="5%" strokecolor="#542E89" fillcolor="#542E89">
                                                <w:anchorlock/>
                                                <center style="color:#ffffff;font-family:'Ubuntu', Arial, sans-serif;font-size:14px;">Reset my password</center>
                                            </v:roundrect>
                                        <![endif]-->
                                    <a href="{{ url('/reset/password/' .$token) }}" style="background-color:#542E89;border:1px solid #542E89;border-radius:10px;color:#ffffff;display:inline-block;font-family:'Ubuntu', Arial, sans-serif;font-size:14px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;font-weight:700;">Reset my password</a>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:0 0 20px 0;color:#252830;">
                                    <p style="margin:0 0 12px 0;font-size:14px;line-height:18px;font-family:'Ubuntu', Arial, sans-serif;font-weight:400;">Please ignore this email if you did not request this action.</p>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:0;color:#252830;">
                                    <p style="margin:0 0 12px 0;font-size:14px;line-height:18px;font-family:'Ubuntu', Arial, sans-serif;font-weight:400;">Kind Regards, <br> HOOD Team</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 0 30px 30px;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td style="padding:0;width:100%;">
                                    <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                                        <tr>
                                            <td style="padding:0 0 0 0;">
                                                <a href="https://hoodagents.com.au/">
                                                    <img src="{{ asset('/assets/images/email/rea.png') }}" alt="REA" width="auto" style="display:block;border:0;" />
                                                </a>
                                            </td>
                                            <td style="padding:0 0 0 10px;">
                                                <a href="https://www.facebook.com/hood.ai.official">
                                                    <img src="{{ asset('/assets/images/email/fb.png') }}" alt="Facebook" width="auto" style="display:block;border:0;" />
                                                </a>
                                            </td>
                                            <td style="padding:0 0 0 10px;">
                                                <a href="https://www.instagram.com/hood.ai_official/">
                                                    <img src="{{ asset('/assets/images/email/instagram.png') }}" alt="Instagram" width="auto" style="display:block;border:0;" />
                                                </a>
                                            </td>
                                            <td style="padding:0 0 0 10px;">
                                                <a href="https://www.linkedin.com/company/hood-ai/mycompany/">
                                                    <img src="{{ asset('/assets/images/email/linkedin.png') }}" alt="Linkedin" width="auto" style="display:block;border:0;" />
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
