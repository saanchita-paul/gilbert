<!DOCTYPE html>
<html>
<head>
<title>HOOD</title>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
    .wrapper {
        max-width: 560px;
        margin: 0px auto;
        font-family: 'Ubuntu', sans-serif;
        font-size: 14px;
    }
    .main-content {
        padding: 10px 5px;
    }
    .btn-area .started-btn {
        background-color: #532D86;
        color: #fff;
        text-decoration: none;
        padding: 10px 14px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .email-footer {
        background-color: #532D86;
        padding: 5px 15px;
    }
    .email-footer img {
        max-width: 150px;
    }
</style>
<body>
    <div class="wrapper" style=" max-width: 560px;
    margin: 0px auto;
    font-family: 'Ubuntu', sans-serif;
    font-size: 14px;">
        <header>
            {{-- <img src="{{ asset('assets/images/email/headerFrame.png' )}}" width="100%"> --}}
            <img src="https://devcrmagency.hood.ai/assets/images/email/headerFrame.png" width="100%">
        </header>

        <section class="main-content" style="padding: 10px 5px;">
            <br>
            <p>Hi {{$name}},</p>
            <br>
            <p>Welcome to HOOD!</p>
            <br>
            <p>Please click the button link below to create your password and  activate your account.</p>
            <p>Once you’re activated, you’re all set to send through the details of your movers.</p>
            <br>
            <p> We look forward helping your movers connect their services with ease.</p>
            <p>Thank You.</p>
            <br>
            <div class="btn-area">
                <a  class="started-btn" 
                    style="
                           background-color: #532D86;
                           color: #fff;
                           text-decoration: none;
                           padding: 10px 14px;
                           border-radius: 4px;
                           margin-bottom: 20px;"

                    target="_blank" 
                    href="{{url('confirm-invitation?token=' .$token)}}">
                   Let’s get started
                </a>
            </div>
            <br>
            <p>HOOD Support Team</p>
            <br>
            <div class="email-footer" style="background-color: #532D86;
        padding: 5px 15px;">
                {{-- <img src="{{ asset('assets/images/email/HOODlogo.png')}}" style="max-width: 150px;"> --}}
                <img src="https://devcrmagency.hood.ai/assets/images/email/HOODlogo.png" style="max-width: 150px;">
            </div>
        </section>
    </div>

</body>
</html>