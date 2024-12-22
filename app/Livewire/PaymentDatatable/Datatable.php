<?php

namespace App\Livewire\PaymentDatatable;

use App\Models\Payment;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\PDF;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;

    public $activeDay = 'Day 1';
    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;

    public $showNotification = false;
    public $showModal = false;
    protected $listeners = ['sortField', 'success_message'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function success_message($message, $amount, $paymentId, $status, $newBalance, $partial)
    {
        // Flash the success message to the session
        session()->flash('success', $message);

        // Dispatch to close the modal
        $this->dispatch('close-modal');

        // Set notification visibility
        $this->showNotification = true;

        // Dispatch to open the invoice in a new tab with the relevant data
        $this->dispatch('openInvoiceInNewTab', route('generate-invoice', [
            'paymentId' => $paymentId,
            'balance' => $newBalance,
            'amount' => $amount,
            'status' => $status,
            'partial' => $partial,
        ]));
    }

    public function sortField($field, $direction)
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
    }

    public function render()
    {
        $payments = Payment::query()
            ->when($this->search, function ($query) {
                $query->where('invoice_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('student', function ($query) {
                        $query->where('firstname', 'like', '%' . $this->search . '%')
                            ->orWhere('lastname', 'like', '%' . $this->search . '%')
                            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $this->search . '%']);
                    });
            })
            ->with(['student'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    
        return view('livewire.payment-datatable.datatable', [
            'payments' => $payments,
        ]);
    }

    public function showPayment($payment_id){
        if(!$payment_id){
            return;
        }

        $this->dispatch('showPayment', $payment_id);

    }
}
