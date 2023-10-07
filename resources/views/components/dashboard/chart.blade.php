<div class="flex flex-col col-span-full sm:col-span-12 bg-white dark:bg-slate-800 shadow-lg border border-slate-200 dark:border-slate-700">
    <header class="px-5 py-2 border-b border-slate-100 dark:border-slate-700 flex items-center">
        <h2 class="font-semibold text-slate-800 dark:text-slate-100">{{ $label }}</h2>
    </header>
    <div class="px-5 py-3">
        <div class="flex flex-wrap justify-between items-end">
            <div class="flex items-start">
                <div class="text-3xl font-bold text-slate-800 dark:text-slate-100 mr-2">{{ $total }}</div>
                @php($change = (end($data) - $prevTotal = prev($data)) / $prevTotal * 100)

                <div @class([
                    'text-sm font-semibold text-white px-1.5 rounded-full',
                    'bg-green-700' => $change >= 0,
                    'bg-red-700' => $change < 0
                ])>{{ number_format($change, 2) }}%</div>
            </div>
            <div id="dashboard-card-08-legend" class="grow ml-2 mb-1">
                <ul class="flex flex-wrap justify-end"></ul>
            </div>
        </div>
    </div>
    <div>
        <canvas id="{{ $element }}" height="120%"></canvas>
    </div>
</div>
<script>
    var color = "{{ $color ?? ($data[0] < end($data) ? 'green' : 'red') }}"

    new Chart({{ $element }}, {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{
                label: '{{ $label }}',
                data: @json($data)
            }]
        },
        options: {
            color: 'white',
            borderColor: color,
            backgroundColor: color,
            scales: {
                y: { ticks: { color: 'white' } },
                x: { ticks: { color: 'white' } }
            }
        }
    })
</script>
