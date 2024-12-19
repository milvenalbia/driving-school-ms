<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Professional Driving Certificate</title>
    <style>
        /* @page {
            size: 29.7cm 21cm landscape;
            margin: 0;
        } */

        body {
            font-family: times;
            margin: 0;
            padding: 1.5cm;
            background: #ffffff;
        }

        .certificate-container {
            width: 100%;
            height: 18cm;
            border: 2pt solid #1a3d6b;
            padding: 0.8cm;
            background: #ffffff;
        }

        .certificate-border {
            width: 100%;
            height: 100%;
            border: 1pt solid #1a3d6b;
            padding: 0.5cm;
            position: relative;
            background: linear-gradient(#ffffff, #ffffff);
        }

        .header-section {
            text-align: center;
            border-bottom: 2pt solid #c4a44d;
            padding-bottom: 15pt;
            margin-bottom: 20pt;
        }

        .logo-text {
            font-size: 24pt;
            color: #1a3d6b;
            font-weight: bold;
            margin-bottom: 10pt;
            text-transform: uppercase;
        }

        .certificate-title {
            font-size: 36pt;
            color: #1a3d6b;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2pt;
            margin: 15pt 0;
        }

        .ornament {
            color: #c4a44d;
            font-size: 24pt;
            margin: 10pt 0;
            letter-spacing: 5pt;
        }

        .main-content {
            text-align: center;
            margin: 10pt 0;
        }

        .presentation-text {
            font-size: 18pt;
            color: #333333;
            margin: 15pt 0;
            line-height: 1.6;
        }

        .recipient-name {
            font-size: 36pt;
            color: #1a3d6b;
            font-weight: bold;
            font-style: italic;
            margin: 20pt 0;
            padding: 5pt 30pt;
            display: inline-block;
            border-bottom: 2pt solid #c4a44d;
        }

        .certificate-text {
            text-align: center;
            font-size: 18pt;
            color: #333333;
            line-height: 1.6;
            margin: 20pt 0;
        }

        .footer-section {
            position: absolute;
            bottom: 0.5cm;
            width: calc(100% - 1cm);
        }

        .signature-grid {
            display: table;
            width: 100%;
            margin: 20pt 0;
        }

        .signature-block {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 20pt;
        }

        .signature-line {
            width: 80%;
            margin: 0 auto;
            border-bottom: 1pt solid #333333;
        }

        .signature-name {
            font-size: 16pt;
            color: #1a3d6b;
            margin-top: 5pt;
        }

        .signature-title {
            font-size: 16pt;
            color: #666666;
            margin-top: 3pt;
        }

        .certificate-number {
            font-size: 10pt;
            color: #666666;
            text-align: left;
            position: absolute;
            bottom: 10pt;
            left: 0.5cm;
        }

        .issue-date {
            font-size: 10pt;
            color: #666666;
            text-align: right;
            position: absolute;
            bottom: 10pt;
            right: 0.5cm;
        }

        .certificate-seal {
            position: absolute;
            right: 2cm;
            bottom: 3cm;
            font-family: times;
            font-size: 14pt;
            color: #c4a44d;
            text-align: center;
            transform: rotate(-15deg);
        }

        .seal-text {
            border: 2pt solid #c4a44d;
            padding: 20pt;
            border-radius: 50%;
            width: 100pt;
            height: 100pt;
        }

        .span {
            margin-top: 45px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="header-section">
                <div class="logo-text">{{$student->type}}</div>
                <div class="certificate-title">Certificate of Completion</div>
            </div>

            <div class="main-content">
                <div class="presentation-text">This is to certify that</div>
                <div class="recipient-name">{{ $student->student->firstname }} {{ $student->student->lastname }}</div>
                <div class="certificate-text">
                    @if ($student->type === 'theoretical')
                        has successfully completed the theoretical driving lesson,<br>
                        demonstrating a solid understanding of road safety rules and traffic regulations.<br>
                        This certificate attests to their proficiency in theoretical knowledge <br> as outlined in the Prime Driving School Program.
                    @else
                        has successfully completed the practical driving lesson,<br>
                        exhibiting skilled vehicle operation and safe driving techniques.<br>
                        This certificate validates their competence in practical driving <br> as per the standards of the Prime Driving School Program.
                    @endif
                </div>
            </div>

            <div class="footer-section">
                <div class="signature-grid">
                    
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        {{-- <div class="signature-name">{{ $data['examiner_name'] }}</div> --}}
                        <div class="signature-title">Chief Examiner</div>
                    </div>
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        {{-- <div class="signature-name">{{ $data['director_name'] }}</div> --}}
                        <div class="signature-title">Academy Director</div>
                    </div>
                </div>
            </div>

            <div class="certificate-seal">
                <div class="seal-text"><p class="span">CERTIFIED <br> <i>{{ now()->format('M, d, Y') }}</i></p></div>
            </div>
        </div>
    </div>
</body>
</html>