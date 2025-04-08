<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fbff;
        }
        .certificate {
            width: 100%;
            height: 100%;
            padding: 50px 70px;
            box-sizing: border-box;
            text-align: center;
            background-color: #fff;
            position: relative;
        }
        .header {
            font-size: 30px;
            font-weight: bold;
            color: #004085;
        }
        .location {
            font-size: 16px;
            color: #0056b3;
            margin-top: 5px;
        }
        .logo {
            margin: 35px auto 25px;
        }
        .certifies {
            font-size: 22px;
            margin-top: 25px;
            color: #333;
        }
        .recipient {
            font-size: 42px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: capitalize;
            color: #002855;
        }
        .description {
            font-size: 20px;
            line-height: 1.8;
            color: #333;
        }
        .course {
            font-size: 30px;
            font-weight: bold;
            margin-top: 20px;
            text-transform: capitalize;
            color: #004085;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            padding: 0 10px;
        }
        .stamp {
            text-align: left;
            color: #333;
            line-height: 1.6;
        }
        .signature {
            text-align: center;
            color: #333;
        }
        .signature img {
            height: 50px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">Xpertbot Academy</div>
        <div class="location">Beirut · Lebanon</div>

        <div class="logo">
            <img src="{{ public_path('images/logo.png') }}" height="90" alt="Logo">
        </div>

        <div class="certifies">This certifies that</div>
        <div class="recipient">{{ ucwords($user->name) }}</div>

        <div class="description">
            has successfully met the requirements for<br>
            and was awarded a certificate in
        </div>

        <div class="course">{{ ucwords($course->name) }}</div>

        <div class="footer">
    <div class="stamp">
        <div><strong>Issue Date:</strong> {{ now()->format('F d, Y') }}</div>
        <div><strong>Certificate ID:{{ $certificateNumber }}</strong> </div>
    </div>
    <div class="signature">
        <img src="{{ public_path('images/signature.png') }}" alt="Signature">
        <div><strong>Program Director</strong></div>
    </div>
</div>

    </div>
</body>
</html>
