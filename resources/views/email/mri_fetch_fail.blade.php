<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
    <section style="padding: 10px 5px;">
        <p>Hello</p>
        <br />
        <p>Fetch MRI Data Failed for {{ $serviceClassName }}</p>
        <br />
        @foreach ($exceptionData as $ed)
            @if (!$loop->first)
                <br/>
            @endif
            <table>
                <tr>
                    <th>Message</th>
                    <td>{{ $ed['message'] }}</td>
                </tr>
                <tr>
                    <th>Data</th>
                    <td>{{ $ed['data'] ?? '-' }}</td>
                </tr>
            </table>
        @endforeach
        <br />
        <br />
        <p>HOOD Support Team</p>
        <br />
        <br />
        <div style="background-color: #532D86; padding: 5px 15px;">
            <img src="https://devcrmagency.hood.ai/assets/images/email/HOODlogo.png" style="max-width: 150px;" alt="LOGO">
        </div>
    </section>
</body>
</html>
