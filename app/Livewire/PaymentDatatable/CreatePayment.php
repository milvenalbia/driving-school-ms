<?php

namespace App\Livewire\PaymentDatatable;

use App\Models\Payment;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class CreatePayment extends Component
{

    public $payments = [];
    public $amount;
    public $payment_id = 0;

    protected $listeners = ['showPayment'];

    public function render()
    {
        return view('livewire.payment-datatable.create-payment');
    }

    public function showPayment($payment_id){

        if(!$payment_id){
            return;
        }

        $this->payment_id = $payment_id;

        $payment = Payment::where('id', $payment_id)->first();

        if($payment){
            $this->payments = [
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

        // 'vat' =>  $payment->schedule->amount * .12,

        $this->dispatch('open-modal', name: 'create-payment');
        
    }

    public function submit_payment(){

        $totalAmount = ($this->payments['vat'] + $this->payments['course_amount']) - $this->payments['paid_amount'];
        $minAmount = $totalAmount / 2; 

        $validated = $this->validate([
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($totalAmount, $minAmount) {
                    if ($value < $minAmount) {
                        $fail("The amount must be at least half of the total amount (₱{$minAmount}).");
                    }
                    if ($value > $totalAmount) {
                        $fail("The amount cannot exceed the total amount (₱{$totalAmount}).");
                    }
                },
            ],
        ]);

        $payment = Payment::where('id', $this->payment_id)->first();

        $newBalance = $payment->balance - $validated['amount'];

        $payment->update([
            'paid_amount' => $payment->paid_amount + $validated['amount'],
            'balance' => $newBalance,
            'status' => $newBalance > 0 ? 'partial' : 'paid',
        ]);

        $this->dispatch('success_message', 'Payment has been submitted successfully');

        $paymentData = $this->payments;
        $amount = $validated['amount'];
        $status = $newBalance > 0 ? 'partial' : 'paid';

        $this->reset();

        // $pdf = PDF::loadView('pdf.payment-receipt', [
        //     'payment' => $paymentData,
        //     'balance' => $newBalance,
        //     'amount' => $amount,
        //     'status' => $status,
        // ]);
        
        // return $pdf->stream('prime-driving-school-receipt.pdf');
        
        return response()->stream(function () use ($amount, $paymentData, $status, $newBalance) {
            $pdf = PDF::loadView('pdf.payment-receipt', [
                'payment' => array_map(function ($value) {
                    return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }, $paymentData),
                'amount' => mb_convert_encoding($amount, 'UTF-8', 'UTF-8'),
                'balance' => mb_convert_encoding($newBalance, 'UTF-8', 'UTF-8'),
                'status' => mb_convert_encoding($status, 'UTF-8', 'UTF-8'),
            ]);
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="prime-driving-school-receipt.pdf"'
        ]);
    }

    public function formClose(){
        $this->reset();
        $this->resetValidation();

        if($this->payments){
            $this->payments = [];
        }

        if($this->payment_id){
            $this->payment_id = 0;
        }
     }
}
