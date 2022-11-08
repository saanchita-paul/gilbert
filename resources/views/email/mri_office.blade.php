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
        <p>New MRI office list</p>
        <br />
            <table>
                <tr>
                    <th>Sl. No</th>
                    <th>Office Name</th>
                </tr>
                @foreach($offices as $office)
                    <tr>
                        <td> {{ $loop->index + 1 }}</td>
                        <td> {{ $office['company_name'] }}</td>
                    </tr>
                @endforeach
            </table>
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
