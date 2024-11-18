{{-- resources/views/livewire/dashboard/chart-component.blade.php --}}
<div class="mt-8">
    <div class="w-full bg-white rounded-md shadow-md p-6">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-xl font-semibold text-gray-800">Sales Analytics</h4>
                <p class="text-sm text-gray-600">Total: ${{ number_format($total, 2) }}</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <select wire:model.live="timeFilter" 
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="weekly">This Week</option>
                    <option value="monthly">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="yearly">This Year</option>
                    <option value="last_year">Last Year</option>
                </select>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 rounded-md p-4">
                <p class="text-sm text-blue-600 mb-1">Average</p>
                <p class="text-lg font-semibold">₱{{ number_format($average, 2) }}</p>
            </div>
            <div class="bg-green-50 rounded-md p-4">
                <p class="text-sm text-green-600 mb-1">Highest</p>
                <p class="text-lg font-semibold">₱{{ number_format($highest, 2) }}</p>
            </div>
            <div class="bg-red-50 rounded-md p-4">
                <p class="text-sm text-red-600 mb-1">Lowest</p>
                <p class="text-lg font-semibold">₱{{ number_format($lowest, 2) }}</p>
            </div>
        </div>

        <div class="relative mt-4" style="width: 100%; height: 400px">
            <canvas id="paymentChart"></canvas>
        </div>
    </div>
</div>

<script>
    let paymentChart = null; // Declare the chart instance globally

    window.onload = function() {
        const ctx = document.getElementById('paymentChart').getContext('2d');

        if (paymentChart !== null) {
            paymentChart.destroy();
        }

        paymentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Sales',
                    data: @json($chartData),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // This allows it to scale properly
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.raw.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Listen for Livewire events and update the chart
        Livewire.on('updateChart', ($data) => {
            console.log('Update Chart:', $data);

            // Update the chart with new data
            paymentChart.data.labels = $data[0].labels;
            paymentChart.data.datasets[0].data = $data[0].data;
            
            // Smoother updates
            paymentChart.update(); 

            // Force the chart to resize
            paymentChart.resize();
        });

    }
</script>


    