<template>
  <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 mt-4">
    <header class="p-4 border-b border-slate-100 dark:border-slate-700 inline-flex">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100 mr-2">Balances</h2>
      <!-- <button class="bg-gray-500 hover:bg-gray-700 font-bold py-2 px-4 rounded inline-flex items-center h-6" wire:loading.attr="disabled" wire:loading.class="bg-gray-700" x-on:click="$wire.call('reloadTokens')"> -->
      <!--     <i class="fa fa-refresh" style="font-size:15px" wire:loading.class="animate-spin"></i> -->
      <!-- </button> -->
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
            7 D APY
          </th>
          <th class="p-2 text-right">
            7 D gain
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
        <tr v-for="(token, index) in Object.values(tokens)[0]">
          <td class="p-2 text-slate-800 dark:text-slate-100 pl-3 w-1/3">
            {{ token.pool }}
          </td>
          <td class="p-2 text-right text-yellow-300">
            {{ parseFloat(token.balance).toFixed(2) }}
          </td>
          <td class="p-2 text-right text-red-300">
            {{ parseFloat(token.price_eur).toFixed(2) }}
          </td>
          <td class="p-2 text-right text-red-300">
            ${{ parseFloat(token.price).toFixed(2) }}
          </td>
          <td class="p-2 text-right text-white">
            <span :class="{
              'text-red-500': getDailyChange(index, token.price) < 0,
              'text-green-500': getDailyChange(index, token.price) > 0
            }">{{ parseFloat(getDailyChange(index, token.price)).toFixed(2) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            {{ parseFloat(token.price_eur * token.balance).toFixed(2) }}
          </td>
          <td class="p-2 text-right text-emerald-300">
            ${{ parseFloat(token.price * token.balance).toFixed(2) }}
          </td>
          <td class="p-2 text-right text-sky-300">
            {{ parseFloat(token.price * token.balance / balances.prices.slice(-1)).toFixed(3) }}
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyApy(index)" :class="{
              'text-red-500': getWeeklyApy(index) < 0,
              'text-green-500': getWeeklyApy(index) > 0
            }">{{ parseFloat(getWeeklyApy(index)).toFixed(2) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyGain(index)" :class="{
              'text-red-500': getWeeklyGain(index) < 0,
              'text-green-500': getWeeklyGain(index) > 0
            }">${{ parseFloat(getWeeklyGain(index)).toFixed(2) }}</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getMonthlyApy(index)" :class="{
              'text-red-500': getMonthlyApy(index) < 0,
              'text-green-500': getMonthlyApy(index) > 0
            }">{{ parseFloat(getMonthlyApy(index)).toFixed(2) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getMonthlyGain(index)" :class="{
              'text-red-500': getMonthlyGain(index) < 0,
              'text-green-500': getMonthlyGain(index) > 0
            }">${{ parseFloat(getMonthlyGain(index)).toFixed(2) }}</span>
          </td>
          <td class="p-2 w-2/12">
            <Line
              height="30"
              :data="{
                labels: Object.keys(tokens),
                datasets: [{
                  borderColor: 'green',
                  backgroundColor: 'green',
                  data: getBalanceHistory(token.pool),
                }]
              }"
              :options="{
                elements: {
                  point:{
                    radius: 0
                  }
                },
                plugins: { legend: { display: false }},
                scales: {
                  y: { ticks: { display: false } },
                  x: { ticks: { display: false } }
                }
              }"
            />
          </td>
          <td class="p-2 w-2/12">
            <Line
              height="30"
              :data="{
                labels: Object.keys(tokens),
                datasets: [{
                  borderColor: 'green',
                  backgroundColor: 'green',
                  data: getPriceHistory(token.pool)
                }]
              }"
              :options="{
                elements: {
                  point:{
                    radius: 0
                  }
                },
                plugins: { legend: { display: false }},
                scales: {
                  y: { ticks: { display: false } },
                  x: { ticks: { display: false } }
                }
              }"
            />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 mt-4">
      <header class="p-4 border-b border-slate-100 dark:border-slate-700">
          <h2 class="font-semibold text-slate-800 dark:text-slate-100">Wise balance: <p class="font-normal">{{ balance }} EUR</p></h2>
      </header>
      <table class="table-autodark:text-slate-300 mx-auto w-full sortable">
          <thead class="text-xs uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700 dark:bg-opacity-50 rounded-sm cursor-pointer">
              <tr>
                  <th class="p-2 text-left pl-5">
                      Title
                  </th>
                  <th class="p-2 text-left pl-5">
                      Type
                  </th>
                  <th class="p-2 text-left pl-5">
                      Status
                  </th>
                  <th class="p-2 text-left pl-5">
                      Primary Amount
                  </th>
                  <th class="p-2 text-left pl-5">
                      Secondary Amount
                  </th>
                  <th class="p-2 text-left pl-5">
                      Created On
                  </th>
              </tr>
          </thead>
          <tbody class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
            <tr v-for="transaction in transactions">
              <td class="p-2 w-1/4 text-slate-800 dark:text-slate-100 pl-3"
                v-html="transaction.title"
              ></td>
              <td class="p-2 text-slate-800 dark:text-slate-100">
                {{ transaction.type }}
              </td>
              <td class="p-2 text-slate-800 dark:text-slate-100">
                {{ transaction.status }}
              </td>
              <td class="p-2 text-slate-800 dark:text-slate-100"
                v-html="transaction.primaryAmount"
              ></td>
              <td class="p-2 text-slate-800 dark:text-slate-100">
                {{ transaction.secondaryAmount }}
              </td>
              <td class="p-2 text-slate-800 dark:text-slate-100">
                {{ transaction.createdOn }}
              </td>
            </tr>
          </tbody>
      </table>
  </div>
</template>

<script>
  import { Line } from 'vue-chartjs'
  import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'

  ChartJS.register( CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend)

  export default {
    components: {
      Line
    },
    props: {
      balance: Number,
      tokens: Object,
      balances: Object,
      transactions: Array,
    },
    data() {
      return {
        weeklyLast: []
      }
    },
    created() {
      const tokens = Object.values(this.tokens)

      this.first = tokens[0]
      this.monthlyLast = tokens.splice(-1)[0]
      this.weeklyLast = tokens.splice(0, 7).splice(-1)[0]
    },
    methods: {
      getDailyChange(index) {
        const prevPrice = Object.values(this.tokens)[1][index].price

        return (this.first[index].price - prevPrice) / prevPrice * 100
      },
      getWeeklyApy(index) {
        return ((this.first[index].balance - this.weeklyLast[index].balance) / this.first[index].balance) * 100
      },
      getWeeklyGain(index) {
        return (this.first[index].balance - this.weeklyLast[index].balance) * this.first[index].price
      },
      getMonthlyApy(index) {
        return ((this.first[index].balance - this.monthlyLast[index].balance) / this.first[index].balance) * 100
      },
      getMonthlyGain(index) {
        return (this.first[index].balance - this.monthlyLast[index].balance) * this.first[index].price
      },
      getBalanceHistory(pool) {
        return Object.values(this.tokens).flat().filter(token => token.pool == pool).reverse().map(token => token.balance)
      },
      getPriceHistory(pool) {
        return Object.values(this.tokens).flat().filter(token => token.pool == pool).reverse().map(token => token.balance * token.price)
      }
    }
  }
</script>
