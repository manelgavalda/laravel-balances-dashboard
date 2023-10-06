<x-app-layout>
    <div class="flex">
        <div class="w-1/3">
            <x-dashboard.chart
                total="{{ number_format(end($balances['prices_eur']), 2, ',', '.') }}€"
                element="prices_eur"
                :data="$balances['prices_eur']"
                :dates="$balances['dates']"
                color='green'
                label='ETH Price EUR'
            />
            <x-dashboard.chart
                total="${{ number_format(end($balances['prices']), 2, ',', '.') }}"
                element="price_usd"
                :data="$balances['prices']"
                :dates="$balances['dates']"
                color='red'
                label='ETH Price USD'
            />
        </div>
        <div class="w-1/3">
            <x-dashboard.chart
                total="{{ number_format(end($balances['ethereum']), 2, ',', '.') }}"
                element="total_eth"
                :data="$balances['ethereum']"
                :dates="$balances['dates']"
                color='blue'
                label='ETH Balance'
            />
        </div>
        <div class="w-1/3">
            <x-dashboard.chart
                total="{{ number_format(end($balances['totals_eur']), 2, ',', '.') }}€"
                element="total_eur"
                :data="$balances['totals_eur']"
                :dates="$balances['dates']"
                color='green'
                label='Total EUR'
            />
            <x-dashboard.chart
                total="${{ number_format(end($balances['totals']), 2, ',', '.') }}"
                element="total_usd"
                :data="$balances['totals']"
                :dates="$balances['dates']"
                color='green'
                label='Total USD'
            />
        </div>
    </div>

    <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
        <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100">Balances</h2>
        </header>
        <div class="p-3">

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full dark:text-slate-300">
                    <!-- Table header -->
                    <thead class="text-xs uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700 dark:bg-opacity-50 rounded-sm">
                        <tr>
                            <th class="p-2">
                                <div class="font-semibold text-left">Name</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">Balance</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">Price</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">USD value</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">ETH value</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
                        <!-- Row -->
                        @foreach($tokens as $token)
                            <tr>
                                <td class="p-2">
                                    <div class="flex items-center">
                                        <svg width="24px" height="24px" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                            <title>Ethereum icon</title>
                                            <path d="M11.944 17.97L4.58 13.62 11.943 24l7.37-10.38-7.372 4.35h.003zM12.056 0L4.69 12.223l7.365 4.354 7.365-4.35L12.056 0z"/>
                                        </svg>
                                        <div class="text-slate-800 dark:text-slate-100 pl-1">{{ $token->pool }}</div>
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-sky-500">{{ number_format($token->balance, 3) }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-emerald-500">${{ number_format($token->price, 2) }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-emerald-500">${{ number_format($token->price * $token->balance, 2) }}</div>
                                </td>
                                <td class="p-2">
                                    {{-- <div class="text-right text-sky-500">{{ number_format(($token->price * $token->balance) / $ethereumPrice, 3) }}</div> --}}
                                </td>
                            </tr>
                            @foreach($token->rewards as $reward)
                                <tr class="bg-gray-100">
                                    <td class="p-2">
                                        <div class="flex items-center">
                                            <svg width="24px" height="24px" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg">
                                                <title>Ethereum icon</title>
                                                <path d="M11.944 17.97L4.58 13.62 11.943 24l7.37-10.38-7.372 4.35h.003zM12.056 0L4.69 12.223l7.365 4.354 7.365-4.35L12.056 0z"/>
                                            </svg>
                                            <div class="text-slate-800 dark:text-slate-100 pl-1"> - {{ $reward->pool }}</div>
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right text-sky-500">{{ number_format($reward->balance, 3) }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right text-emerald-500">${{ number_format($reward->price, 2) }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right text-emerald-500">${{ number_format($reward->price * $reward->balance, 2) }}</div>
                                    </td>
                                    <td class="p-2">
                                        {{-- <div class="text-right text-sky-500">{{ number_format(($reward->price * $reward->balance) / $ethereumPrice, 3) }}</div> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
