<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Students;
use App\Models\Schedules;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\StudentReport;
use App\Models\CourseEnrolled;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    
    public function showStudentReports(): View {
        return view('pages.reports.student-reports');
    }

    public function showScheduleReports(): View {
        return view('pages.reports.schedule-reports');
    }

    public function showPaymentReports(): View {
        return view('pages.reports.payment-reports');
    }

    public function showStudentCertificate(): View {
        return view('pages.reports.student-certificate');
    }

    public function showStudentList(): View {
        return view('pages.reports.student-list');
    }

    public function showDailySales(): View {
        return view('pages.reports.daily-sales');
    }

    public function showTheoreticalStudents(): View {
        return view('pages.reports.theoretical-students');
    }

    public function showPracticalStudents(): View {
        return view('pages.reports.practical-students');
    }

    public function studentCertificate($user_id, $id)
{
    // Fetch the student report
    $student = StudentRecord::where('id', $id)->first();

    if(!$student){
        return; // Handle the case when no student is found
    }

    // Get student ID and name
    $student_id = $student->student->user_id;

    // Format the updated date for the certificate
    $date = Carbon::parse($student->updated_at)->format('M d, Y');

    // Prepare the data to pass to the PDF view
    // $data = [
    //     'name' => $student->student->firstname . ' ' . $student->student->lastname,
    //     'date' => $date,  // You can pass other relevant data if needed
    // ];

    // Generate the PDF using the view
    $pdf = PDF::loadView('pdf.student-certificate', compact('student', 'date'))->setPaper([0, 0, 1200, 800]);

    // Stream or download the generated PDF
    return $pdf->stream($student_id . '_certificate.pdf');
}

    public function generateCertificate($user_id, $id)
{
    // Fetch the student report
    $student = StudentReport::where('id', $id)->first();

    if(!$student){
        return; // Handle the case when no student is found
    }

    // Get student ID and name
    $student_id = $student->student->user_id;

    // Format the updated date for the certificate
    $date = Carbon::parse($student->updated_at)->format('M d, Y');

    // Prepare the data to pass to the PDF view
    // $data = [
    //     'name' => $student->student->firstname . ' ' . $student->student->lastname,
    //     'date' => $date,  // You can pass other relevant data if needed
    // ];

    // Generate the PDF using the view
    $pdf = PDF::loadView('pdf.certificate', compact('student', 'date'))->setPaper([0, 0, 1200, 800]);

    // Stream or download the generated PDF
    return $pdf->stream($student_id . '_certificate.pdf');
}

    public function daily_sales_pdf(Request $request){

        $dates = $request->input('dates');

        $minDate = min($dates);  // Get the first date (earliest)
        $maxDate = max($dates);    // Get the last date (latest)

        $startDate = Carbon::parse(min($dates))->startOfDay();
        $endDate = Carbon::parse(max($dates))->endOfDay();

        // $sales = CourseEnrolled::query()
        //     ->whereBetween('course_enrolleds.created_at', [$startDate, $endDate])
        //     ->whereHas('payments', function ($q) {
        //         $q->where('status', 'paid')
        //             ->orWhere('status', 'partial');
        //     })
        //     ->select(
        //         DB::raw('DATE(payments.created_at) AS date'),
        //         DB::raw('COUNT(DISTINCT course_enrolleds.schedule_id) AS total_schedule'),
        //         DB::raw('COUNT(course_enrolleds.student_id) AS total_student'),
        //         DB::raw('COUNT(CASE WHEN schedules.type = "theoretical" THEN course_enrolleds.student_id END) AS theoretical_student'),
        //         DB::raw('COUNT(CASE WHEN schedules.type = "practical" THEN course_enrolleds.student_id END) AS practical_student'),
        //         DB::raw('COALESCE(SUM(payments.paid_amount), 0) as total_sales')
        //     )
        //     ->leftJoin('schedules', 'course_enrolleds.schedule_id', '=', 'schedules.id')
        //     ->leftJoin('payments', 'course_enrolleds.id', '=', 'payments.course_enrolled_id')
        //     ->with('payments')
        //     ->groupBy(DB::raw('DATE(payments.created_at)'))
        //     ->get();

        $sales = CourseEnrolled::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('payments', function ($q) {
                $q->where('status', 'paid')
                    ->orWhere('status', 'partial');
            })
            ->with('payments')
            ->get();

        $pdf = PDF::loadView('pdf.daily-sales', compact('sales', 'minDate', 'maxDate'));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Optional: Set other PDF properties
        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'dejavu sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return $pdf->stream('daily_sales_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function student_pdf(Request $request){

        $student_ids = $request->input('ids');

        $selectedStudents = StudentRecord::whereIn('id', $student_ids)
        ->with(['student', 'schedule'])
        ->get();

        $pdf = PDF::loadView('pdf.students', [
            'students' => $selectedStudents,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Optional: Set other PDF properties
        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'dejavu sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return $pdf->stream('course_students_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function schedule_pdf(Request $request){

        $schedule_ids = $request->input('ids');

        $selectedSchedules = Schedules::whereIn('id', $schedule_ids)
        ->with('instructorBy')
        ->get();

        $pdf = PDF::loadView('pdf.schedules', [
            'schedules' => $selectedSchedules,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Optional: Set other PDF properties
        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'dejavu sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return $pdf->stream('course_schedules_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function student_list_pdf(Request $request){

        $student_ids = $request->input('ids');

        $selectedStudents = Students::with('student_records') // Eager-load student_records
        ->select(
            'students.id', 
            'students.user_id', 
            'students.firstname', 
            'students.lastname', 
            'students.email', 
            'students.phone_number', 
            'students.gender', 
            'students.civil_status', 
            'students.image_path', 
            'students.theoretical_test', 
            'students.practical_test'
        )
        ->whereIn('id', $student_ids)
        ->get();

        $pdf = PDF::loadView('pdf.student-list', [
            'students' => $selectedStudents,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Optional: Set other PDF properties
        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'dejavu sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return $pdf->stream('student_list_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function payment_pdf(Request $request){

        $payment_ids = $request->input('ids');

        $selectedPayments = Payment::whereIn('id', $payment_ids)
        ->where(function ($q) {
            $q->where('status', 'paid')
            ->orWhere('status', 'partial');
        })
        ->with(['student', 'schedule'])
        ->get();

        $pdf = PDF::loadView('pdf.payment', [
            'payments' => $selectedPayments,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Optional: Set other PDF properties
        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'dejavu sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return $pdf->stream('course_payment_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function invoice_pdf(Request $request){

        $paymentId = $request->input('paymentId');
        $newBalance = $request->input('newBalance');
        $amount = $request->input('amount');
        $status = $request->input('status');
        $partial =$request->input('partial');

        $payment = Payment::where('id', $paymentId)->first();

        if($payment){
            $paymentData = [
                'invoice_code' => $payment->invoice_code,
                'student_name' => $payment->student->firstname .' '. $payment->student->lastname,
                'course_name' => $payment->schedule->name,
                'course_type' => $payment->schedule->type,
                'course_amount' => $payment->schedule->amount,
                'course_code' => $payment->schedule->schedule_code,
                'paid_amount' => $payment->paid_amount,
                'balance' => $payment->balance,
                'vat' =>  0,
            ];
        }

        $pdf = PDF::loadView('pdf.payment-receipt', [
            'payment' => $paymentData,
            'balance' => $newBalance,
            'amount' => $amount,
            'status' => $status,
            'partial' => $partial,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Optional: Set other PDF properties
        $pdf->setOption([
            'dpi' => 150,
            'defaultFont' => 'dejavu sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        return $pdf->stream('prime-driving-school-receipt-' . now()->format('Y-m-d_His') . '.pdf');
    }

    
}
