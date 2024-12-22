<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="text/html; charset=utf-8">
    <title>Official Receipt</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('/fonts/DejaVuSans.ttf');
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
            line-height: 1.6;
        }
        .logo {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 4px;
        }
        .logo img {
            max-width: 200px;
        }
        .receipt-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .receipt-date {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .invoice-section {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-number {
            width: 100%;
            border: 1px dashed #010101;
            display: inline-block;
            padding: 10px 0;
            letter-spacing: 12px;
            font-family: monospace;
            margin-top: 10px;
        }
        .invoice-label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 8px 0;
        }
        .details-table tr:first-child td {
            border-bottom: 1px solid #010101;
        }
        .details-table tr:last-child td {
            border-bottom: 1px solid #010101;
        }
        .details-table td:first-child {
            width: 40%;
            color: #010101;
        }
        .details-table td:last-child {
            text-align: right;
        }
        .amount-table {
            width: 100%;
            margin-top: 20px;
        }
        .amount-table td {
            padding: 8px 0;
        }
        .amount-table td:first-child {
            width: 40%;
            color: #010101;
        }
        .amount-table td:last-child {
            text-align: right;
        }
        .amount-table tr:last-child td {
            border-bottom: 1px solid #010101;
        }
        .line{
            width: 100%;
            height: 2px;
            background-color: #010101;
        }
        .message{
            text-align: center;
            color: #010101;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="logo">Prime Driving School</div>
    <div class="receipt-title">OFFICIAL RECEIPT</div>
    <div class="receipt-date">{{ now()->format('l, F d, Y - h:i A') }}</div>
    <div class="line"></div>
    <div class="invoice-section">
        <div class="invoice-label">INVOICE #:</div>
        <div class="invoice-number">{{ $payment['invoice_code'] }}</div>
    </div>

    <table class="details-table">
        <tr>
            <td>Name</td>
            <td>{{ $payment['student_name'] ?? '' }}</td>
        </tr>
        <tr>
            <td>Schedule Code</td>
            <td>{{ $payment['course_code'] ?? '' }}</td>
        </tr>
        <tr>
            <td>Course Name</td>
            <td>{{ $payment['course_name'] ?? 'Driving Course' }}</td>
        </tr>
        <tr>
            <td>Course Type</td>
            <td style="text-transform: capitalize;">{{ $payment['course_type'] ?? 'Theoretical' }}</td>
        </tr>
    </table>

    <table class="amount-table">
        {{-- <tr>
            <td>Amount</td>
            <td>₱{{ number_format($payment['paid_amount'] ?? 0, 2) }}</td>
        </tr> --}}
        <tr>
            <td>VAT</td>
            <td>₱{{ number_format($payment['vat'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Total</td>
            <td>₱{{ number_format(($payment['course_amount'] ?? 0) + ($payment['vat'] ?? 0), 2) }}</td>
        </tr>
        @if($partial)
        <tr>
            <td>Partial</td>
            <td>₱{{ number_format($partial ?? 0, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td>Cash</td>
            <td>₱{{ number_format($amount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Balance</td>
            <td>₱{{ number_format($payment['balance'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td style="text-transform: capitalize;">{{ $status }}</td>
        </tr>
    </table>
    <div class="message">
        <p>
            Thank you for choosing Prime Driving School!
        </p>
        <p>
            We appreciate your trust in us to guide you on your driving journey. If you have any questions or need further assistance, feel free to contact us anytime.
        </p>
        <p>Safe driving and best of luck in your lessons</p>
    </div>
</body>
</html>