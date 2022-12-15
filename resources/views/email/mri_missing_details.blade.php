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
    .email-footer {
        background-color: #532D86;
        padding: 5px 15px;
    }
    .email-footer img {
        max-width: 150px;
    }
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    .title{
        text-align: center;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
<body>
    <div class="wrapper" style=" max-width: 560px;
    margin: 0px auto;
    font-family: 'Ubuntu', sans-serif;
    font-size: 14px;">
        <header>
            <img src="https://devcrmagency.hood.ai/assets/images/email/headerFrame.png" width="100%">
        </header>

        <section class="main-content" style="padding: 10px 5px;">
            <br>
            <p>Hello,</p>
            <br>
            <p>The following MRI applications have missing or failed to fetch details</p>
            <br>
            @foreach ($missingData as $appId => $missingColumns)
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Missing Details</th>
                    </tr>
                    @foreach ($missingColumns as $column)
                    <tr>
                        @if ($loop->first)
                            <td rowspan="{{count($missingColumns)}}">{{ $appId }}</td>
                        @endif
                        <td>{{ $column }}</td>
                    </tr>
                    @endforeach
                </table>
            @endforeach
            <br>
            <br>
            <p>HOOD Support Team</p>
            <br>
            <div class="email-footer" style="background-color: #532D86;
        padding: 5px 15px;">
                <img src="https://devcrmagency.hood.ai/assets/images/email/HOODlogo.png" style="max-width: 150px;">
            </div>
        </section>
    </div>

</body>
</html>
