<!doctype html>
<html lang="en">

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');

        @media only screen and (max-width:576px) {
            .main-container {
                width: 100%;
            }
        }
    </style>
</head>

<body style="margin: 0;">
<div class="main-container" style="font-family: 'Ubuntu'; font-style: normal; font-weight: 400; font-size: 14px; line-height: 18px; color: #252830; max-width: 800px; margin: 0 auto;">
    <header>
        <img src="{{ asset('/assets/images/email/pw_reset.png') }}" alt="Banner Image" width="100%">
    </header>

    <section style="padding: 15px;">
        <div>
            <h2 style="font-weight: 700; font-size: 24px;">Hi {{ $name }}! It happens...</h2>
            <p>But don’t worry, we got you covered!</p>
            <p>We received a request from you to reset your password.</p>
            <p>If you requested to reset your password, please click the button below:</p>
        </div>

        <div style="margin-top: 40px; margin-bottom: 40px;">
            <p>
                <a href="{{ url('/reset/password/' .$token) }}" target="_blank" style="padding: 10px 20px; box-shadow: 0 0 10px 10px #673ab71a; background: #542E89; color: white; border-radius: 10px; text-decoration: none; font-weight: 700;">Reset my password</a>
            </p>
        </div>

        <div>
            <p>Please ignore this email if you did not request this action.</p>
        </div>

        <div style="margin-top: 30px;">
            <p>Kind Regards, <br> HOOD Team</p>
        </div>
    </section>

    <footer>
        <div style=" display: flex; padding: 15px;">
            <div>
                <a href="https://hoodagents.com.au/" target="_blank" style="padding: 8px 8px 8px 0;">
                    <img src="{{ asset('/assets/images/email/rea.png') }}" width="auto" alt="REA" />
                </a>
            </div>
            <div>
                <a href="https://www.facebook.com/hood.ai.official" target="_blank" style="padding: 8px;">
                    <img src="{{ asset('/assets/images/email/fb.png') }}" width="auto" alt="Facebook" />
                </a>
            </div>
            <div>
                <a href="https://www.instagram.com/hood.ai_official/" target="_blank" style="padding: 8px;">
                    <img src="{{ asset('/assets/images/email/instagram.png') }}" width="auto" alt="Instagram" />
                </a>
            </div>
            <div>
                <a href="https://www.linkedin.com/company/hood-ai/mycompany/" target="_blank" style="padding: 8px;">
                    <img src="{{ asset('/assets/images/email/linkedin.png') }}" width="auto" alt="Linkedin" />
                </a>
            </div>
        </div>
    </footer>
</div>
</body>
</html>

