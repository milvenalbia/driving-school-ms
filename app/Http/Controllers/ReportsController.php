<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Schedules;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\StudentReport;
use Barryvdh\DomPDF\Facade\PDF;

class ReportsController extends Controller
{
    
    public function showStudentReports(): View {
        return view('pages.reports.student-reports');
    }

    public function showScheduleReports(): View {
        return view('pages.reports.schedule-reports');
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
    $pdf = PDF::loadView('pdf.certificate', compact('student'))->setPaper([0, 0, 1200, 800]);

    // Stream or download the generated PDF
    return $pdf->stream($student_id . '_certificate.pdf');
}

    public function student_pdf(Request $request){

        $student_ids = $request->input('ids');

        $selectedStudents = StudentReport::whereIn('id', $student_ids)
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

    public function invoice_pdf(Request $request){

        $paymentId = $request->input('paymentId');
        $newBalance = $request->input('newBalance');
        $amount = $request->input('amount');
        $status = $request->input('status');

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
                'vat' =>  0,
            ];
        }

        $pdf = PDF::loadView('pdf.payment-receipt', [
            'payment' => $paymentData,
            'balance' => $newBalance,
            'amount' => $amount,
            'status' => $status,
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
