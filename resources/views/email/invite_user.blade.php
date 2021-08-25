<!DOCTYPE html>
<html lang="en">
<body>
<p>Hi {{$name}}</p>
<p>You are invited to join at Hood CRM</p>
<a href="{{url('confirm-invitation?token' .$token)}}">ACCEPT INVITATION</a>
</body>
</html>

