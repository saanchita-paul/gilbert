<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex">
    <title>Hood Dashboard</title>
    <link rel="icon" href="{{ url('assets/images/favicon.png') }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{mix('css/app.css')}}">

</head>
<body>
<div id="app"></div><script>
    window.Userback = window.Userback || {};
    Userback.access_token = '31538|47548|prFQbybNR6KbB12DXYGaFYQMb';
    (function(d) {
        var s = d.createElement('script');s.async = true;
        s.src = 'https://static.userback.io/widget/v1.js';
        (d.head || d.body).appendChild(s);
    })(document);
</script>

<script src="{{url('assets/js/mapdata.js')}}"></script>
<script src="{{url('assets/js/australiamap.js')}}"></script>
<script src="{{mix('js/app.js')}}"></script>
</body>
</html>

