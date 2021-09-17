<!DOCTYPE html>
<html>
<head>
    <title>HOOD</title>
</head>
​
<style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
    .wrapper {
        max-width: 90%;
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
    @include('email.agency.layout.header')
    ​

    <section class="main-content" style="display: flex">
        <div style="width: 35%;color:black; padding:0px 20px; font-size: 16px">
            <p >Hi {{$profile->first_name}}!</p>
            <br/>
            <h2 style="margin-top:10px;font-weight: bold; font-size: 18px">Welcome to HOOD!</h2>
            <br/>
            <p>You are now part of a community that helps moving households
                connect with service providers across Australia!</p>
            <p>You received this email as confirmation of your HOOD account.</p>
            <br/>
            <p>Click the button below to get started!</p>
            <br/>
            <div class="btn-area">
                <a class="started-btn" style=" background-color: #532D86;
        color: #fff;
        text-decoration: none;
        padding: 10px 14px;
        border-radius: 4px;
        margin-bottom: 20px;" target="_blank" href="#">Let’s get started</a>
            </div>
            <br/>
            <br/>
            @include('email.agency.layout.footer')
        </div>
        <div style="width: 60%">
            <img src="https://db2b-103-143-255-12.ngrok.io/hood-crm-dashboard/public/assets/images/invite_email.png" width="100%;">
        </div>

    </section>
</div>
​
</body>
</html>
