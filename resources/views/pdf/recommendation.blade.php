{{-- resources/views/pdf/recommendation.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommendation Letter</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #e6f0ff; /* Soft light blue background */
        }

        .card {
            max-width: 700px;
            margin: 30px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #dcdfe5;
            color: #333;
        }

        .card-header {
            background-color: #3f51b5;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .card-header h1 {
            font-size: 24px;
            margin: 0;
        }

        .card-content {
            padding: 20px;
            font-size: 16px;
            color: #333;
        }

        .card-content p {
            margin-bottom: 20px;
        }

        .card-content strong {
            color: #3f51b5;
        }

        .card-footer {
            text-align: right;
            margin-top: 30px;
        }

        .signature {
            font-size: 18px;
            font-weight: bold;
        }

        .footer-text {
            font-size: 14px;
            color: #777;
        }

        .highlight {
            color: #3f51b5;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1>Recommendation Letter</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <div class="card-content">
            <p>To Whom It May Concern,</p>

            <p>I am pleased to recommend <strong>{{ $user->name }}</strong>, who has successfully completed our <strong>{{ $course->name }}</strong> program.</p>

            <p>Certificate Number: <strong>{{ $certificateNumber }}</strong></p>

            <p>During the program, <strong>{{ $user->name }}</strong> demonstrated:</p>
            <ul>
                <li>Exceptional problem-solving skills.</li>
                <li>Strong leadership and teamwork abilities.</li>
                <li>Dedication to learning and professional growth.</li>
            </ul>

            <p>We highly recommend <strong>{{ $user->name }}</strong> for any opportunities, as they will bring great value to any organization.</p>
        </div>

        <div class="card-footer">
            <div class="signature">
                Sincerely,<br>
                The Academic Committee
            </div>
            <div class="footer-text">
                {{ config('app.name') }}
            </div>
        </div>
    </div>
</body>
</html>
