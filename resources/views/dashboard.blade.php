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
        </div>
    </div>
    <div class="max-w-4xl">
        <canvas id="chart" width="3" height="1"></canvas>
    </div>
    <script>
        const ctx = document.getElementById('chart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: '# ETH balance',
                    data: @json($balances),
                }]
            },
        });
    </script>
</x-app-layout>
