<?php

namespace App\Livewire\Dashboard;

use App\Models\Payment;
use Livewire\Component;

class ChartComponent extends Component
{
    public $timeFilter = 'yearly';
    public $labels = [];
    public $chartData = [];
    
    public function updatedTimeFilter()
    {
        $this->labels = $this->getChartLabels();
        $this->chartData = $this->getChartData();
    
        // Dispatch the event only if data has changed
        $this->dispatch('updateChart', [
            'labels' => $this->labels,
            'data' => $this->chartData
        ]);
    }

    private function getChartData()
    {
        return $this->getFilteredQuery()
            ->selectRaw($this->getSelectStatement())
            ->groupBy($this->getGroupByColumn())
            ->orderBy($this->getOrderByColumn())
            ->pluck('total')
            ->toArray();
    }

    private function getChartLabels()
    {
        $dates = $this->getFilteredQuery()
            ->selectRaw($this->getLabelSelectStatement())
            ->groupBy($this->getGroupByColumn())
            ->orderBy($this->getOrderByColumn())
            ->pluck($this->getPluckColumn());

        return $dates->map(function($date) {
            return $this->formatLabel($date);
        })->toArray();
    }

    private function getFilteredQuery()
    {
        $query = Payment::query();

        return $query->when($this->timeFilter === 'weekly', function($q) {
            return $q->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        })->when($this->timeFilter === 'monthly', function($q) {
            return $q->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        })->when($this->timeFilter === 'last_month', function($q) {
            return $q->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ]);
        })->when($this->timeFilter === 'yearly', function($q) {
            return $q->whereBetween('created_at', [
                now()->startOfYear(),
                now()->endOfYear()
            ]);
        })->when($this->timeFilter === 'last_year', function($q) {
            return $q->whereBetween('created_at', [
                now()->subYear()->startOfYear(),
                now()->subYear()->endOfYear()
            ]);
        });
    }

    private function getSelectStatement()
    {
        if ($this->timeFilter === 'yearly') {
            return 'MONTH(created_at) as month, SUM(paid_amount) as total';
        }
        return 'DATE(created_at) as date, SUM(paid_amount) as total';
    }

    private function getLabelSelectStatement()
    {
        if ($this->timeFilter === 'yearly') {
            return 'MONTH(created_at) as month';
        }
        return 'DATE(created_at) as date';
    }

    private function getGroupByColumn()
    {
        return $this->timeFilter === 'yearly' ? 'month' : 'date';
    }

    private function getOrderByColumn()
    {
        return $this->timeFilter === 'yearly' ? 'month' : 'date';
    }

    private function getPluckColumn()
    {
        return $this->timeFilter === 'yearly' ? 'month' : 'date';
    }

    private function formatLabel($value)
    {
        switch ($this->timeFilter) {
            case 'weekly':
                return date('D', strtotime($value));
            case 'monthly':
                return date('M d', strtotime($value));
            case 'yearly':
                return date('F', mktime(0, 0, 0, $value, 1));
            default:
                return $value;
        }
    }

    public function render()
    {
        $this->chartData = $this->getChartData();
        $this->labels = $this->getChartLabels();

        return view('livewire.dashboard.chart-component', [
            'chartData' => $this->chartData,
            'labels' => $this->labels,
            'total' => array_sum($this->chartData),
            'average' => count($this->chartData) > 0 ? array_sum($this->chartData) / count($this->chartData) : 0,
            'highest' => count($this->chartData) > 0 ? max($this->chartData) : 0,
            'lowest' => count($this->chartData) > 0 ? min($this->chartData) : 0,
        ]);
    }
}