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
                    <th class="p-2 text-right">
                        {{ $days }} D APY
                    </th>
                    <th class="p-2 text-right">
                        {{ $days }} D gain
                    </th>
                    <th class="p-2 text-right">
                        30 D APY
                    </th>
                    <th class="p-2 text-right">
                        30 D gain
                    </th>
                    <th class="p-2 text-center">
                        Last 30 D balance
                    </th>
                    <th class="p-2 text-center">
                        Last 30 D price
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($tokens->first() as $token)
                    <tr>
                        <td class="p-2 w-2/12 text-slate-800 dark:text-slate-100 pl-3">
                            {{ $token->pool }}
                        </td>
                        <td class="p-2 text-right text-yellow-300">
                            {{ number_format($token->balance, 3) }}
                        </td>
                        <td class="p-2 text-right text-red-300">
                            {{ number_format($token->price_eur, 2) }}
                        </td>
                        <td class="p-2 text-right text-red-300">
                            ${{ number_format($token->price, 2) }}
                        </td>
                        <td class="p-2 text-right">
                            @php
                                $prevPrice = $tokens->values()->get(1)->firstWhere('pool', $token->pool)->price;

                                $change = ($token->price - $prevPrice) / $prevPrice * 100;
                            @endphp

                            <span @class(['text-green-500' => $change >= 0, 'text-red-500' => $change < 0])>
                                {{ number_format($change, 2) }}%
                            </span>
                        </td>
                        <td class="p-2 text-right text-emerald-300">
                            {{ number_format($token->price_eur * $token->balance, 2) }}
                        </td>
                        <td class="p-2 text-right text-emerald-300">
                            ${{ number_format($token->price * $token->balance, 2) }}
                        </td>
                        <td class="p-2 text-right text-sky-300">
                            {{ number_format($token->price * $token->balance / end($balances['prices']), 3) }}
                        </td>

                        @php
                            $weeklyLast = $tokens->take(7)->last()->firstWhere('pool', $token->pool);
                            $weeklyFirst = $tokens->take(7)->first()->firstWhere('pool', $token->pool);
                        @endphp

                        <td class="p-2 text-right text-emerald-300">
                            @if($weeklyFirst->balance != $weeklyLast->balance)
                                {{ number_format(($weeklyFirst->balance - $weeklyLast->balance) / $weeklyLast->balance * 100, 2) }}%
                            @endif
                        </td>
                        <td class="p-2 text-right text-emerald-300">
                            @if($weeklyFirst->balance != $weeklyLast->balance)
                                ${{ number_format(($weeklyFirst->balance - $weeklyLast->balance) * $weeklyLast->price, 2) }}
                            @endif
                        </td>

                        @php
                            $last = $tokens->last()->firstWhere('pool', $token->pool);
                            $first = $tokens->first()->firstWhere('pool', $token->pool);
                        @endphp

                        <td class="p-2 text-right text-emerald-300">
                            @if($first && $last && $first->balance != $last->balance)
                                {{ number_format(($first->balance - $last->balance) / $last->balance * 100, 2) }}%
                            @endif
                        </td>
                        <td class="p-2 text-right text-emerald-300">
                            @if($first && $last && $first->balance != $last->balance)
                                ${{ number_format(($first->balance - $last->balance) * $last->price, 2) }}
                            @endif
                        </td>

                        @php($tokensHistory = $tokens->flatten()->where('pool', $token->pool)->reverse()->values())

                        <td class="p-2 w-2/12">
                            <x-dashboard.simple-chart
                                :element="$token->pool"
                                :dates="$tokens->keys()->toArray()"
                                :data="$tokensHistory->map(fn($token) => $token->balance)->toArray()"
                            />
                        </td>
                        <td class="p-2 w-2/12">
                            <x-dashboard.simple-chart
                                :element="$token->pool . 'balance'"
                                :dates="$tokens->keys()->toArray()"
                                :data="$tokensHistory->map(fn($token) => $token->price * $token->balance)->toArray()"
                            />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
