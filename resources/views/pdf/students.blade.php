<!-- resources/views/pdf/students.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('build/assets/images/prime.jpg') }}" type="image/x-icon">
    <title>Course Students Report</title>
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
        .button-container {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .day-btn {
            display: inline-block;
            padding: 2px 3px;
            font-size: 11px;
            border: 1px solid #d4d4d4;
            background-color: #ffffff;
            text-align: center;
        }

        /* Present state */
        .present {
            background-color: #4477ff;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <div class="text-right">REPORT ID: {{ strtoupper(uniqid('SCH')) }}</div>
            <div class="text-right" style="color: #6B7280; font-size: 12px;">Generated on: {{ now()->format('F d, Y - h:i A') }}</div>
        </div>
        <h1 class="report-title">Students Report</h1>
        <div class="report-subtitle">Total Records: {{ $students->count() }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-grid" style="width: 44%">
            <div class="summary-label">Total Students</div>
            <div class="summary-value">{{ $students->count() }}</div>
        </div>
        <div class="summary-grid" style="width: 44%">
            <div class="summary-label">Total Schedules</div>
            <div class="summary-value">{{ $students->unique('schedule.id')->count() }}</div>
        </div>
        <div class="summary-grid">
            <div class="summary-label">Passed Students</div>
            <div class="summary-value">{{ $students->where('remarks', true)->count() }}</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <h2 class="section-title">Student Details</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Student ID</th>
                    <th style="width: 15%;">Name</th>
                    <th style="width: 15%;">Course Name</th>
                    <th style="width: 10%;">Type</th>
                    <th style="width: 10%;">Attendance</th>
                    <th style="width: 10%;">Grade</th>
                    <th style="width: 15%;">Instructor</th>
                    <th style="width: 10%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->student->user_id }}</td>
                        <td class="font-bold text-primary">{{ $student->student->firstname }} {{ $student->student->lastname }}</td>
                        <td class="capitalize">{{ $student->schedule->name }}</td>
                        <td class="capitalize">{{ $student->type }}</td>
                        <td class="capitalize">
                            @if($student->type === 'theoretical')
                            <div class="button-container">
                                <div class="day-btn day-btn-first <?php echo ($student->course->day1_status === 'present' ? 'present' : ''); ?>">
                                    D-1
                                </div>
                                <div class="day-btn day-btn-middle <?php echo ($student->course->day2_status === 'present' ? 'present' : ''); ?>">
                                    D-2
                                </div>
                                <div class="day-btn <?php echo ($student->course->day3_status === 'present' ? 'present' : ''); ?>">
                                    D-3
                                </div>
                            </div>
                            @else
                            <div class="button-container">
                                @if($student->course->sessions === 1)
                                    <div class="day-btn day-btn-first <?php echo ($student->course->day1_status === 'present' ? 'present' : ''); ?>">
                                        D-1
                                    </div>
                                @elseif($student->course->sessions === 2)
                                    <div class="day-btn day-btn-first <?php echo ($student->course->day1_status === 'present' ? 'present' : ''); ?>">
                                        D-1
                                    </div>
                                    <div class="day-btn day-btn-middle <?php echo ($student->course->day2_status === 'present' ? 'present' : ''); ?>">
                                        D-2
                                    </div>
                                @elseif($student->course->sessions === 3)
                                    <div class="day-btn day-btn-first <?php echo ($student->course->day1_status === 'present' ? 'present' : ''); ?>">
                                        D-1
                                    </div>
                                    <div class="day-btn day-btn-middle <?php echo ($student->course->day2_status === 'present' ? 'present' : ''); ?>">
                                        D-2
                                    </div>
                                    <div class="day-btn <?php echo ($student->course->day3_status === 'present' ? 'present' : ''); ?>">
                                        D-3
                                    </div>
                                @else
                                    <div class="day-btn day-btn-first <?php echo ($student->course->day1_status === 'present' ? 'present' : ''); ?>">
                                        D-1
                                    </div>
                                    <div class="day-btn day-btn-middle <?php echo ($student->course->day2_status === 'present' ? 'present' : ''); ?>">
                                        D-2
                                    </div>
                                    <div class="day-btn <?php echo ($student->course->day3_status === 'present' ? 'present' : ''); ?>">
                                        D-3
                                    </div>
                                    <div class="day-btn <?php echo ($student->course->day4_status === 'present' ? 'present' : ''); ?>">
                                        D-4
                                    </div>
                                @endif
                            </div>   
                            @endif
                        </td>
                        <td>{{ $student->grade }}</td>
                        <td>{{ $student->schedule->instructorBy->firstname }} {{ $student->schedule->instructorBy->lastname }}</td>
                        <td class="capitalize">{{ $student->remarks ? ($student->remarks ? 'passed' : 'failed') : 'in progress' }}</td>
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