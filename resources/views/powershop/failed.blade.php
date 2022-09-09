<!DOCTYPE html>
<html>
<head>
<title>HOOD | Payment Failed</title>
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
    .mt-20 {
        margin-top: 20px;
    }

</style>
<body class="wrapper">
    <section class="main-container" style="width: 280px;">
        <div style="color: #FFFFFF; text-align: center;">
            <h2 class="font-24">Unfortunately, your payment has been declined due to {{ $reason }}.</h2>
            <h2 class="font-24 mt-20">To complete your order, we will be sending you another payment link.</h2>
        </div>
    </section>
</body>
</html>
