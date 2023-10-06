<x-app-layout>
    <div class="flex">
        <div class="w-1/3">
            <x-dashboard.chart
                total="{{ number_format(end($balances['prices_eur']), 2, ',', '.') }}€"
                prevTotal="{{ number_format((end($balances['prices_eur']) - $prev = prev($balances['prices_eur'])) / $prev * 100, 2) }}"
                element="prices_eur"
                :data="$balances['prices_eur']"
                :dates="$balances['dates']"
                color='red'
                label='EUR Price'
            />
            <x-dashboard.chart
                total="${{ number_format($ethereumPrice, 2, ',', '.') }}"
                prevTotal="{{ number_format((end($balances['prices']) - $prev = prev($balances['prices'])) / $prev * 100, 2) }}"
                element="price_usd"
                :data="$balances['prices']"
                :dates="$balances['dates']"
                color='red'
                label='USD Price'
            />
        </div>
        <div class="w-1/3">
            <x-dashboard.chart
                total="{{ number_format(end($balances['ethereum']), 2, ',', '.') }}"
                prevTotal="{{ number_format((end($balances['ethereum']) - $prev = prev($balances['ethereum'])) / $prev * 100, 2) }}"
                element="total_eth"
                :data="$balances['ethereum']"
                :dates="$balances['dates']"
                color='blue'
                label='Total ETH'
                height="196"
            />
        </div>
        <div class="w-1/3">
            <x-dashboard.chart
                total="{{ number_format(end($balances['totals_eur']), 2, ',', '.') }}€"
                prevTotal="{{ number_format((end($balances['totals_eur']) - $prev = prev($balances['totals_eur'])) / $prev * 100, 2) }}"
                element="total_eur"
                :data="$balances['totals_eur']"
                :dates="$balances['dates']"
                color='green'
                label='Total EUR'
            />
            <x-dashboard.chart
                total="${{ number_format(end($balances['totals']), 2, ',', '.') }}"
                prevTotal="{{ number_format((end($balances['totals']) - $prev = prev($balances['totals'])) / $prev * 100, 2) }}"
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
                                <div class="font-semibold text-right">EUR Price</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">USD Price</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">Total ETH</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">Total EUR</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-right">Total USD</div>
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
                                    <div class="text-right text-yellow-300">{{ number_format($token->balance, 3) }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-red-300">{{ number_format($token->price_eur, 2) }}€</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-red-300">${{ number_format($token->price, 2) }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-sky-300">{{ number_format(($token->price * $token->balance) / $ethereumPrice, 3) }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-emerald-300">{{ number_format($token->price_eur * $token->balance, 2) }}€</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-right text-emerald-300">${{ number_format($token->price * $token->balance, 2) }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
