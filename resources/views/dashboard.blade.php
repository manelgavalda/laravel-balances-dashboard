<x-app-layout>
    <div class="flex">
        <div class="w-1/3">
            <x-dashboard.chart
                label="EUR Price"
                element="prices_eur"
                :dates="$balances['dates']"
                :data="$balances['prices_eur']"
                :total="number_format(end($balances['prices_eur']), 2, ',', '.') . '€'"
            />
            <x-dashboard.chart
                label="USD Price"
                element="price_usd"
                :dates="$balances['dates']"
                :data="$balances['prices']"
                :total="'$' . number_format(end($balances['prices']), 2, ',', '.')"
            />
        </div>
        <div class="w-1/3">
            <x-dashboard.chart
                color="blue"
                label="Total ETH"
                element="total_eth"
                :dates="$balances['dates']"
                :data="$balances['ethereum']"
                :total="number_format(end($balances['ethereum']), 2, ',', '.')"
            />
        </div>
        <div class="w-1/3">
            <x-dashboard.chart
                label="Total EUR"
                element="total_eur"
                :dates="$balances['dates']"
                :data="$balances['totals_eur']"
                :total="number_format(end($balances['totals_eur']), 2, ',', '.') . '€'"
            />
            <x-dashboard.chart
                label="Total USD"
                element="total_usd"
                :data="$balances['totals']"
                :dates="$balances['dates']"
                :total="'$' . number_format(end($balances['totals']), 2, ',', '.')"
            />
        </div>
    </div>
    <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 mt-4">
        <header class="p-4 border-b border-slate-100 dark:border-slate-700">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100">Balances</h2>
        </header>
        <table class="table-autodark:text-slate-300 mx-auto w-full sortable">
            <thead class="text-xs uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700 dark:bg-opacity-50 rounded-sm cursor-pointer">
                <tr>
                    <th class="p-2 text-left pl-5">
                        Name
                    </th>
                    <th class="p-2 text-right">
                        Balance
                    </th>
                    <th class="p-2 text-right">
                        EUR Price
                    </th>
                    <th class="p-2 text-right">
                        USD Price
                    </th>
                    <th class="p-2 text-right">
                        24h
                    </th>
                    <th class="p-2 text-right">
                        Total EUR
                    </th>
                    <th class="p-2 text-right">
                        Total USD
                    </th>
                    <th class="p-2 text-right">
                        Total ETH
                    </th>
                    <th class="p-2 text-center">
                        Last days
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($tokens->first() as $i => $token)
                    <tr>
                        <td class="p-2 w-1/4">
                            <div class="text-slate-800 dark:text-slate-100 pl-3">{{ $token->pool }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-yellow-300">{{ number_format($token->balance, 3) }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-red-300">{{ number_format($token->price_eur, 2) }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-red-300">${{ number_format($token->price, 2) }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right">
                                @php($change = ($token->price - $prevPrice = $tokens->values()->get(1)[$i]->price) / $prevPrice * 100)

                                <span @class(['text-green-500' => $change >= 0, 'text-red-500' => $change < 0])>
                                    {{ number_format($change, 1) }}%
                                </span>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-emerald-300">{{ number_format($token->price_eur * $token->balance, 2) }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-emerald-300">${{ number_format($token->total, 2) }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-sky-300">{{ number_format($token->total / end($balances['prices']), 3) }}</div>
                        </td>
                        <td class="p-2 w-2/12">
                            <x-dashboard.simple-chart
                                :element="$token->pool"
                                :dates="$tokens->keys()->toArray()"
                                :data="$tokens->flatten()->where('pool', $token->pool)->pluck('price')->toArray()"
                            />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
