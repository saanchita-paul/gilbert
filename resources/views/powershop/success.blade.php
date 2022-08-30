<!DOCTYPE html>
<html>
<head>
<title>HOOD</title>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
    .wrapper {
        max-width: 850px;
        margin: 0px auto;
        font-family: 'Ubuntu', sans-serif;
        font-size: 14px;
    }
    .main-content {
        padding: 10px 5px;
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
    <div class="wrapper" style=" max-width: 850px;
    margin: 0px auto;
    font-family: 'Ubuntu', sans-serif;
    font-size: 14px;">
        <header>
            {{-- <img src="{{ asset('assets/images/email/headerFrame.png' )}}" width="100%"> --}}
            <img src="https://devcrmagency.hood.ai/assets/images/email/headerFrame.png" width="100%">
        </header>

        <section class="main-content" style="padding: 10px 5px;">
            <br>
            <p>Hey, {{$name}}</p>
            <br>
            <p><strong>Credit Card verification is completed! 🤠</strong></p>
            <br>
            <p>Thank you for choosing Powershop with Hood!.</p>

            <br>
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
