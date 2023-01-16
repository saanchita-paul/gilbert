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
<div class="main-container" style="font-family: 'Ubuntu'; font-style: normal; font-weight: 400; font-size: 14px; line-height: 18px; color: #252830; max-width: 800px; margin: 10px auto;">
    <header>
        <img src="{{ asset('/assets/images/email/new_lead.png') }}" alt="Banner Image" width="100%">
    </header>

    <section style="padding: 15px;">
        <div>
            <h2 style="font-weight: 700; font-size: 24px;">Hi {{ $lead_info['agent_name'] }},</h2>
        </div>

        <div>
            <p>HOOD has received an application from you.</p>
        </div>

        <div style="margin-top: 30px; margin-bottom: 30px;">
            <div style="display: flex; margin-bottom: 10px;">
                <div style="flex-basis: 125px;"><span style="font-weight: 700;">Submitted by</span></div>
                <div><span style="font-weight: 400;">{{ $lead_info['agent_name'] }}</span></div>
            </div>
            <div style="display: flex; margin-bottom: 10px;">
                <div style="flex-basis: 125px;"><span style="font-weight: 700;">Tenant Name</span></div>
                <div><span style="font-weight: 400;">{{ $lead_info['full_name'] }}</span></div>
            </div>
            <div style="display: flex; margin-bottom: 10px;">
                <div style="flex-basis: 125px;"><span style="font-weight: 700;">Address</span></div>
                <div><span style="font-weight: 400;">{{ $lead_info['full_address'] }}</span></div>
            </div>
            <div style="display: flex; margin-bottom: 10px;">
                <div style="flex-basis: 125px;"><span style="font-weight: 700;">Move In Date</span></div>
                <div><span style="font-weight: 400;">{{ $lead_info['connection_date'] }}</span></div>
            </div>
            <div style="display: flex;">
                <div style="flex-basis: 125px;"><span style="font-weight: 700;">App ID</span></div>
                <div><span style="font-weight: 400;">{{ $lead_info['id'] }}</span></div>
            </div>
        </div>

        <div>
            <p>One of our HOOD staff will be in touch with your client to arrange their connections shortly. For enquiries, please contact your HOOD Account Manager.</p>
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


