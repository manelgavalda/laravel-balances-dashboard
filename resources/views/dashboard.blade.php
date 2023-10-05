<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
        <div class="flex py-2">
            <x-dashboard.chart
                total="{{ number_format(end($balances['ethereum']), 2, ',', '.') }}"
                chart="total_eth"
            />

            <x-dashboard.chart
                total="{{ '$' . number_format(end($balances['prices']), 2, ',', '.') }}"
                chart="price_usd"
            />

            <x-dashboard.chart
                total="{{ '$' . number_format(end($balances['ethereum']) * end($balances['prices']), 2, ',', '.') }}"
                chart="total_usd"
            />
        </div>
    </div>
</x-app-layout>

<script>
    [{
        element: 'total_eth',
        data: @json($balances['ethereum']),
        color: 'blue',
        label: 'ETH Balance'
    }, {
        element: 'price_usd',
        data: @json($balances['prices']),
        color: 'red',
        label: 'ETH Price'
    }, {
        element: 'total_usd',
        data: @json($balances['totals']),
        color: 'green',
        label: 'Total USD'
    }].forEach(createChart)


    function createChart(chart) {
        new Chart(document.getElementById(chart.element), {
            type: 'line',
            data: {
                labels: @json($balances['dates']),
                datasets: [{
                    label: chart.label,
                    data: chart.data
                }]
            },
            options: {
                backgroundColor: chart.color,
                borderColor: chart.color,
                color: 'white',
                scales: {
                    y: { ticks: { color: 'white' } },
                    x: { ticks: { color: 'white' } }
                }
            }
        })
    }
</script>
