<?php

namespace App\Livewire\ReportsDatatable;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentReports extends Component
{
    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 10;

    public $showNotification = false;
    public $showModal = false;

    public $payment_id = [];
    public $selectAll = false;
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

    public function sortField($field, $direction)
    {
        $this->sortBy = $field;
        $this->sortDirection = $direction;
    }

    // Handle the select all functionality
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Get all schedule IDs from the current query
            $this->payment_id = $this->getFilteredPayments()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->payment_id = [];
        }
    }

    // Update selectAll when individual items are selected/deselected
    public function updatedPaymentId($value)
    {
        $this->selectAll = count($this->payment_id) === $this->getFilteredPayments()->count();
    }

    // Helper method to get filtered schedules query
    private function getFilteredPayments()
    {
        return Payment::query()
            ->where('status', 'paid')
            ->when($this->search, function ($query) {
                $query->where('invoice_code', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    // Generate PDF for selected schedules
    public function generatePDF()
    {
        if (empty($this->payment_id)) {

            session()->flash('error', 'Please select at least one schedule to generate PDF');

            $this->showNotification = true;
            return;
        }

        $payment_ids = is_array( $this->payment_id) ?  $this->payment_id : [ $this->payment_id];

        $this->dispatch('openPaymentInNewTab', route('generate-payment-reports', ['ids' => $payment_ids]));

        // Automatic download
        // $selectedSchedules = Schedules::whereIn('id', $this->schedule_id)
        // ->with('instructorBy')
        // ->get();

        // $pdf = PDF::loadView('pdf.schedules', [
        //     'schedules' => $selectedSchedules,
        // ]);

        // // Set paper size and orientation
        // $pdf->setPaper('A4', 'portrait');

        // // Optional: Set other PDF properties
        // $pdf->setOption([
        //     'dpi' => 150,
        //     'defaultFont' => 'dejavu sans',
        //     'isHtml5ParserEnabled' => true,
        //     'isRemoteEnabled' => true
        // ]);

        // return response()->streamDownload(function() use ($pdf) {
        //     echo $pdf->output();
        // }, 'course_schedules_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function render()
    {
        $payments = $this->getFilteredPayments()->paginate($this->perPage);
    
        return view('livewire.reports-datatable.payment-reports', [
            'payments' => $payments,
        ]);
    }
}
