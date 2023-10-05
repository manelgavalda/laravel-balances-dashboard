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
            <div class="w-full p-1">
                <div class="flex flex-col col-span-full sm:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-slate-200 dark:border-slate-700">
                    <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center">
                        <h2 class="font-semibold text-slate-800 dark:text-slate-100">ETH Balance</h2>
                    </header>
                    <div class="px-5 py-3">
                        <div class="flex flex-wrap justify-between items-end">
                            <div class="flex items-start">
                                <div class="text-3xl font-bold text-slate-800 dark:text-slate-100 mr-2">{{ round(end($balances['ethereum']), 2) }}</div>
                                <div class="text-sm font-semibold text-white px-1.5 bg-amber-500 rounded-full">2.1%</div>
                            </div>
                            <div id="dashboard-card-08-legend" class="grow ml-2 mb-1">
                                <ul class="flex flex-wrap justify-end"></ul>
                            </div>
                        </div>
                    </div>
                    <div>
                        <canvas id="total_eth" height="120"></canvas>
                    </div>
                </div>
            </div>

            <div class="w-full p-1">
                <div class="flex flex-col col-span-full sm:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-slate-200 dark:border-slate-700">
                    <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center">
                        <h2 class="font-semibold text-slate-800 dark:text-slate-100">ETH Price</h2>
                    </header>
                    <div class="px-5 py-3">
                        <div class="flex flex-wrap justify-between items-end">
                            <div class="flex items-start">
                                <div class="text-3xl font-bold text-slate-800 dark:text-slate-100 mr-2">${{ number_format(end($balances['prices']), 2) }}</div>
                                <div class="text-sm font-semibold text-white px-1.5 bg-amber-500 rounded-full">2.1%</div>
                            </div>
                            <div id="dashboard-card-08-legend" class="grow ml-2 mb-1">
                                <ul class="flex flex-wrap justify-end"></ul>
                            </div>
                        </div>
                    </div>
                    <div>
                        <canvas id="price_usd" height=120"></canvas>
                    </div>
                </div>
            </div>

            <div class="w-full p-1">
                <div class="flex flex-col col-span-full sm:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-slate-200 dark:border-slate-700">
                    <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center">
                        <h2 class="font-semibold text-slate-800 dark:text-slate-100">Total USD</h2>
                    </header>
                    <div class="px-5 py-3">
                        <div class="flex flex-wrap justify-between items-end">
                            <div class="flex items-start">
                                <div class="text-3xl font-bold text-slate-800 dark:text-slate-100 mr-2">${{ number_format(end($balances['ethereum']) * end($balances['prices']), 2) }}</div>
                                <div class="text-sm font-semibold text-white px-1.5 bg-amber-500 rounded-full">2.1%</div>
                            </div>
                            <div id="dashboard-card-08-legend" class="grow ml-2 mb-1">
                                <ul class="flex flex-wrap justify-end"></ul>
                            </div>
                        </div>
                    </div>
                    <div>
                        <canvas id="total_usd" height=120"></canvas>
                    </div>
                </div>
            </div>
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
