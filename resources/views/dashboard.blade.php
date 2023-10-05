<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-12 px-4 sm:px-6 lg:px-8 py-8w-full max-w-9xl mx-auto pt-2">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{ __("You're logged in!") }}
            </div>
        </div>

        <div class="flex py-2">
            <x-dashboard.chart
                :total="end($balances['ethereum'])"
                chart="total_eth"
            />

            <x-dashboard.chart
                :total="end($balances['prices'])"
                chart="price_usd"
            />

            <x-dashboard.chart
                :total="end($balances['ethereum']) * end($balances['prices'])"
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
    }].forEach(chart => new Chart(document.getElementById(chart.element), {
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
    }))
</script>
