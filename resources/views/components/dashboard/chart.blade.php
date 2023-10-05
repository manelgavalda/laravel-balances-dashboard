<div class="w-full p-1">
    <div class="flex flex-col col-span-full sm:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-slate-200 dark:border-slate-700">
        <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100">Total</h2>
        </header>
        <div class="px-5 py-3">
            <div class="flex flex-wrap justify-between items-end">
                <div class="flex items-start">
                    <div class="text-3xl font-bold text-slate-800 dark:text-slate-100 mr-2">{{ $total }}</div>
                    <div class="text-sm font-semibold text-white px-1.5 bg-amber-500 rounded-full">2.1%</div>
                </div>
                <div id="dashboard-card-08-legend" class="grow ml-2 mb-1">
                    <ul class="flex flex-wrap justify-end"></ul>
                </div>
            </div>
        </div>
        <div>
            <canvas id="{{ $chart }}" height="120"></canvas>
        </div>
    </div>
</div>
