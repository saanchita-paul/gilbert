<!DOCTYPE html>
<html>
<head>
    <title>Email Template</title>
</head>
<body>
<p>Hey {{$lead_info['agent_name']}}, thank you!</p>
<p>HOOD has received your latest referral.</p>
<p> <b>Referring Agent:</b>  {{$lead_info['agent_name'].' '.$lead_info['agent_name']}}<br>
    <b>Name:</b> {{$lead_info['full_name']}}<br>
    <b>Address:</b>  {{$lead_info['full_address']}}<br>
    <b>Nominated Move-in Date:</b> {{$lead_info['connection_date']}}<br>
    <b>App ID:</b> {{$lead_info['id']}}<br>
</p>
<p> One of our HOOD staff will be in touch with your client to arrange utilities connections shortly.
    For enquiries, please contact your HOOD Account Manager.</p>
<p>Kind Regards,</p>
<p> <b>HOOD Utility Services</b></p>
<img src="{{asset('assets/images/email_logo.png')}}" alt="Hood" title="Hood" width="220" height="80"><br>
<p>     Level 2, 277 Camberwell Road, Camberwell 3124 <br>
    P:  1300242824 W: hood.ai
</p>
</body>
</html>
