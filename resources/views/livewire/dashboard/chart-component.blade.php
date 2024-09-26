<div x-data="chartComponent()" class="w-full bg-white rounded-sm shadow-md p-4 mt-5">
    <div class="flex items-center justify-between">
        <h4 class="font-bold">Sales Line Chart</h4>
        <x-elements.select class="py-0 pt-2 pb-2 text-sm">
            <option value="">Select to Filter</option>
            <option value="weekly">This Week</option>
            <option value="monthly">This Month</option>
            <option value="yearly">This Year</option>
        </x-elements.select>
    </div>
    <canvas id="myChart" class="w-full"></canvas>
</div>

@section('scripts')
<script>
  function chartComponent() {
      return {
          chart: null,
          
          init() {
              // Initialize the chart when Alpine.js component is initialized
              const ctx = document.getElementById("myChart").getContext("2d");

              this.chart = new Chart(ctx, {
                  type: "line", // Chart type
                  data: {
                      labels: ["January", "February", "March", "April", "May", "June"], // X-axis labels
                      datasets: [
                          {
                              label: "Sales", // Chart label
                              data: [5000, 7900, 9500, 5700, 4300, 5670], // Data points
                              borderWidth: 1, // Border thickness
                          },
                      ],
                  },
                  options: {
                      scales: {
                          y: {
                              beginAtZero: true, // Y-axis starts from 0
                          },
                      },
                  },
              });
          }
      };
  }
</script>
@endsection