<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex">
        <x-dashboard.chart
            total="{{ number_format(end($balances['ethereum']), 2, ',', '.') }}"
            element="total_eth"
            :data="$balances['ethereum']"
            :dates="$balances['dates']"
            color='blue'
            label='ETH Balance'
        />

        <x-dashboard.chart
            total="${{ number_format(end($balances['prices']), 2, ',', '.') }}"
            element="price_usd"
            :data="$balances['prices']"
            :dates="$balances['dates']"
            color='red'
            label='ETH Price'
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
</x-app-layout>
