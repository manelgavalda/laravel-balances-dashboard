<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="pt-10 flex flex-col col-span-full sm:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
                <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center">
                    <h2 class="font-semibold text-slate-800 dark:text-slate-100">Total</h2>
                </header>
                <div class="px-5 py-3">
                    <div class="flex flex-wrap justify-between items-end">
                        <div class="flex items-start">
                            <div class="text-3xl font-bold text-slate-800 dark:text-slate-100 mr-2">$1500.00</div>
                            <div class="text-sm font-semibold text-white px-1.5 bg-amber-500 rounded-full">{{ round($balances[0], 2) }}</div>
                        </div>
                        <div id="dashboard-card-08-legend" class="grow ml-2 mb-1">
                            <ul class="flex flex-wrap justify-end"></ul>
                        </div>
                    </div>
                </div>
                <div class="grow">
                    <canvas id="balances" width="600" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const ctx = document.getElementById('balances');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{
                label: '# ETH balance',
                data: @json($balances),
            }]
        },
        options: {
            color: 'white',
            scales: {
                y: {
                    ticks: { color: 'white' }
                },
                x: {
                    ticks: { color: 'white' }
                }
            }
        }
    });
</script>
