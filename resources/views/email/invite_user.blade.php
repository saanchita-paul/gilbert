<!DOCTYPE html>
<html lang="en">
<body>
<p>Hi {{$name}}</p>
<p>You are invited to join at Hood CRM</p>
<a href="{{url('confirm-invitation?token=' .$token)}}">ACCEPT INVITATION</a>
</body>
</html>




<!DOCTYPE html>
<html>
<head>
    <title>HOOD</title>
</head>
​
<style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
    .wrapper {
        max-width: 540px;
        margin: 0px auto;
        font-family: 'Ubuntu', sans-serif;
        font-size: 14px;
    }
    .wrapper header {
        background-color: #532D86;
        padding: 10px 5px;
    }
    .main-content {
        padding: 10px 5px;
    }
    .btn-area {
        margin: 20px 0px 35px 0px;
    }
    .btn-area .started-btn {
        background-color: #532D86;
        color: #fff;
        text-decoration: none;
        padding: 10px 14px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>
<body>
<div class="wrapper">
    <header>
        <img src="http://testhood.brc.technology/wp-content/uploads/2021/09/email-logo.png" width="210px;">
    </header>
    ​
    <section class="main-content">
        <p>Hi [Firstname]!</p>
        <p>Welcome to HOOD!</p>
        <p>You are now part of a community that helps moving households
            connect with service providers across Australia!</p>
        <p>You received this email as confirmation of your HOOD account.</p>
        <br>
        <p>Click the button below to get started!</p>
        <br>
        <div class="btn-area">
            <a class="started-btn" target="_blank" href="#">Let’s get started</a>
        </div>
        <p><small>Thank you,</small></p>
        <p>HOOD Support Team</p>
    </section>
</div>
​
</body>
</html>
