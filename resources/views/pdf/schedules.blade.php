<!-- resources/views/pdf/schedules.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Course Schedules Report</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        body {
            padding: 2.5cm 1.5cm;
            color: #2D3748;
            line-height: 1.6;
        }

        /* Header Section */
        .header {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3B82F6;
        }

        .logo-section {
            position: absolute;
            top: 0;
            right: 0;
            text-align: right;
        }

        .report-title {
            color: #1E40AF;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .report-subtitle {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 5px;
        }

        /* Summary Section */
        .summary-section {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #F3F4F6;
            border-radius: 5px;
        }

        .summary-grid {
            display: inline-block;
            vertical-align: top;
        }

        .summary-label {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #1E40AF;
        }

        /* Table Styles */
        .table-section {
            margin-bottom: 2cm;
        }

        .section-title {
            color: #1E40AF;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        thead tr {
            background-color: #1E40AF;
            color: white;
        }

        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: normal;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #E5E7EB;
        }

        tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        /* Footer Section */
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 1.5cm;
            right: 1.5cm;
            font-size: 10px;
            color: #6B7280;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
        }

        .footer-grid {
           display: flex;
           justify-content: space-between;
           align-items: center;
        }

        /* Helper Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-primary { color: #1E40AF; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .capitalize { text-transform: capitalize; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <div class="text-right">REPORT ID: {{ strtoupper(uniqid('SCH')) }}</div>
            <div class="text-right" style="color: #6B7280; font-size: 12px;">Generated on: {{ now()->format('F d, Y - h:i A') }}</div>
        </div>
        <h1 class="report-title">Course Schedules Report</h1>
        <div class="report-subtitle">Academic Period: {{ now()->format('Y') }}</div>
        <div class="report-subtitle">Total Records: {{ $schedules->count() }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-grid" style="width: 44%">
            <div class="summary-label">Total Courses</div>
            <div class="summary-value">{{ $schedules->count() }}</div>
        </div>
        <div class="summary-grid" style="width: 44%">
            <div class="summary-label">Total Enrolled Students</div>
            <div class="summary-value">{{ $schedules->sum('enrolled_student') }}</div>
        </div>
        <div class="summary-grid">
            <div class="summary-label">Active Instructors</div>
            <div class="summary-value">{{ $schedules->unique('instructorBy.id')->count() }}</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <h2 class="section-title">Schedule Details</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 15%;">Schedule Code</th>
                    <th style="width: 25%;">Course Name</th>
                    <th style="width: 13%;">Type</th>
                    <th style="width: 10%;">Enrolled</th>
                    <th style="width: 25%;">Instructor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->created_at->format('M d, Y') }}</td>
                        <td class="uppercase">{{ $schedule->schedule_code }}</td>
                        <td class="capitalize font-bold text-primary">{{ $schedule->name }}</td>
                        <td class="capitalize">{{ $schedule->type }}</td>
                        <td class="text-center">{{ $schedule->enrolled_student }}</td>
                        <td>{{ $schedule->instructorBy->firstname }} {{ $schedule->instructorBy->lastname }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-grid">
            Generated by: System Administrator
        </div>
        <div class="footer-grid text-right">
            {{ config('app.name', 'Laravel') }} © {{ date('Y') }}
        </div>
    </div>
</body>
</html>