<?php

namespace App\Livewire\ReportsDatatable;

use Carbon\Carbon;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseEnrolled;
use Illuminate\Support\Facades\DB;

class DailySales extends Component
{
    use WithPagination;

    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $showNotification = false;
    public $showModal = false;

    public $sale_date = []; // Use sale date instead of sale ID
    public $selectAll = false;

    public $startDate;
    public $endDate;

    protected $listeners = ['sortField', 'success_message'];

    protected $queryString = [
        'sortBy' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
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
            $this->sale_date = $this->getFilteredSales()
                ->pluck('date') // Using date here
                ->map(fn($date) => (string) $date)
                ->toArray();
        } else {
            $this->sale_date = [];
        }
    }

    public function updatedSaleDate($value)
    {
        $this->selectAll = count($this->sale_date) === $this->getFilteredSales()->count();
    }

    // Helper method to get filtered sales query
    private function getFilteredSales()
    {
        return CourseEnrolled::query()
            ->whereHas('payments', function ($q) {
                $q->where('status', 'paid')
                    ->orWhere('status', 'partial');
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::parse($this->startDate)->startOfDay();
                $endDate = Carbon::parse($this->endDate)->endOfDay();
                $query->whereBetween('course_enrolleds.created_at', [$startDate, $endDate]);
            })
            ->select(
                DB::raw('DATE(payments.created_at) AS date'),
                DB::raw('COUNT(DISTINCT course_enrolleds.schedule_id) AS total_schedule'),
                DB::raw('COUNT(course_enrolleds.student_id) AS total_student'),
                DB::raw('COUNT(CASE WHEN schedules.type = "theoretical" THEN course_enrolleds.student_id END) AS theoretical_student'),
                DB::raw('COUNT(CASE WHEN schedules.type = "practical" THEN course_enrolleds.student_id END) AS practical_student'),
                DB::raw('COALESCE(SUM(payments.paid_amount), 0) as total_sales')
            )
            ->leftJoin('schedules', 'course_enrolleds.schedule_id', '=', 'schedules.id')
            ->leftJoin('payments', 'course_enrolleds.id', '=', 'payments.course_enrolled_id')
            ->with('payments')
            ->groupBy(DB::raw('DATE(payments.created_at)'))
            ->orderBy('course_enrolleds.id', $this->sortDirection);
            
    }

    public function generatePDF()
    {
        if (empty($this->sale_date)) {
            session()->flash('error', 'Please select at least one sale to generate PDF');
            $this->showNotification = true;
            return;
        }

        $sale_dates = is_array($this->sale_date) ? $this->sale_date : [$this->sale_date];
        $this->dispatch('openSalesInNewTab', route('generate-daily-sales-reports', ['dates' => $sale_dates]));
    }

    public function render()
    {
        $sales = $this->getFilteredSales()->paginate($this->perPage);
    
        return view('livewire.reports-datatable.daily-sales', [
            'sales' => $sales,
        ]);
    }
}
