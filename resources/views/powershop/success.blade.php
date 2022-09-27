<!DOCTYPE html>
<html>
<head>
<title>HOOD | Payment Success</title>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
    .wrapper {
        background: #542E89;
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 32px;
    }
    .main-container {
        position: absolute;
        top: 50%;
        left: 50%;
        -moz-transform: translateX(-50%) translateY(-50%);
        -webkit-transform: translateX(-50%) translateY(-50%);
        transform: translateX(-50%) translateY(-50%);
    }
    .font-24 {
        font-size: 24px
    }
    .text-normal {
        width: 255px;
        font-size: 18px;
        line-height: 23px;
        font-weight: 400;
        font-style: normal;
    }
    .mb-70 {
        margin-bottom: 70px;
    }
    .mt-20 {
        margin-top: 20px;
    }
</style>
<body class="wrapper">
    <section class="main-container">
        <div style="color: #FFFFFF; text-align: center">
            <div class="mb-70">
                <img src="{{ asset('assets/images/icons/thumb.svg') }}">
            </div>
            <div class="font-24" style="width: 270px">
                Thank you for connecting with HOOD!
            </div>
            <div class="text-normal mt-20">
                <p>We have securely received your details. You can now close this window.</p>
            </div>
        </div>
    </section>
</body>
</html>
