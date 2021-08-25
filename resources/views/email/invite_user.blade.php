<!DOCTYPE html>
<html lang="en">
<body>
<p>Hi {{$user->first_name}} {{$user->last_name}}</p>
<p>You are invited to join at Hood CRM</p>
<a href="{{url('confirm-invitation?token' .$token)}}">ACCEPT INVITATION</a>
</body>
</html>

