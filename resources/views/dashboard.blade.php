<x-app-layout>
    <div class="flex">
        <div class="w-1/3">
            <x-dashboard.chart
                total="{{ number_format(end($balances['prices_eur']), 2, ',', '.') }}€"
                element="prices_eur"
                :data="$balances['prices_eur']"
                :dates="$balances['dates']"
                color='red'
                label='EUR Price'
                />
            <x-dashboard.chart
                total="${{ number_format(end($balances['prices']), 2, ',', '.') }}"
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
                element="total_eth"
                :data="$balances['ethereum']"
                :dates="$balances['dates']"
                color='blue'
                label='Total ETH'
                height="193"
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
    <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 mt-2">
        <header class="px-5 py-2 border-b border-slate-100 dark:border-slate-700">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100">Balances</h2>
        </header>
        <!-- Table -->
        <table class="table-autodark:text-slate-300 mx-auto w-full">
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
                        <div class="font-semibold text-right">24h</div>
                    </th>
                    <th class="p-2">
                        <div class="font-semibold text-right">Total EUR</div>
                    </th>
                    <th class="p-2">
                        <div class="font-semibold text-right">Total USD</div>
                    </th>
                    <th class="p-2">
                        <div class="font-semibold text-right">Total ETH</div>
                    </th>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
                <!-- Row -->
                @foreach($tokens as $token)
                    <tr>
                        <td class="p-2 w-1/3">
                            <div class="flex items-center">
                                <div class="text-slate-800 dark:text-slate-100 pl-3">{{ $token->pool }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-yellow-300">{{ number_format($token->balance, 3) }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-red-300">{{ number_format($token->price_eur, 2) }}€</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-red-300">
                                ${{ number_format($token->price, 2) }}
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="text-right">
                                @php($change = 1)
                                <span @class([
                                    'text-xs',
                                    'text-green-500' => $change >= 0,
                                    'text-red-500' => $change < 0
                                ])>{{ number_format($change, 1) }}%</span>
                            </div>
                        </td>
                        <td class="p-2 w-1/12">
                            <div class="text-right text-emerald-300">{{ number_format($token->price_eur * $token->balance, 2) }}€</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-emerald-300">${{ number_format($token->price * $token->balance, 2) }}</div>
                        </td>
                        <td class="p-2">
                            <div class="text-right text-sky-300">{{ number_format(($token->price * $token->balance) / end($balances['prices']), 3) }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
