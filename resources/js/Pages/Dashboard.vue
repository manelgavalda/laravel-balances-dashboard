<template>
  <div class="flex">
    <div class="w-1/2">
      <balances-chart
        label="ETH"
        color="blue"
        :dates="prices.dates"
        :data="prices.eth"
        :total="currencyFormat(ethPrice)"
        :subtotal="currencyFormat(ethPrice * eurPrice, 'EUR')"
      />
      <balances-chart
        label="BTC"
        :dates="prices.dates"
        :data="prices.btc"
        :total="currencyFormat(btcPrice)"
        :subtotal="currencyFormat(btcPrice * eurPrice, 'EUR')"
      />
      <balances-chart
        label="EUR"
        :dates="prices.dates"
        :data="prices.eur"
        :total="currencyFormat(eurPrice, 'EUR')"
        subtotal="-"
      />
    </div>
    <div class="w-1/2">
      <balances-chart
        label="Total"
        :dates="totals.dates"
        :data="totals.totals"
        :total="currencyFormat(total)"
        :subtotal="currencyFormat(total * eurPrice, 'EUR')"
      />
      <balances-chart
        label="Debt"
        :dates="totals.dates"
        :data="totals.debts"
        :total="currencyFormat(debt)"
        :subtotal="currencyFormat(debt * eurPrice, 'EUR')"
      />
      <balances-chart
        label="Total"
        :dates="totals.dates"
        :data="totals.totals"
        :total="currencyFormat(total - debt)"
        :subtotal="currencyFormat((total - debt) * eurPrice, 'EUR')"
      />
    </div>
  </div>
  <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 mt-4">
    <header class="p-4 border-b border-slate-100 dark:border-slate-700 inline-flex">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100 mr-2">Balances</h2>
      <button class="bg-gray-500 hover:bg-gray-700 font-bold py-2 px-4 rounded inline-flex items-center h-6" :disabled="loading" :class="{ 'bg-gray-700': loading }" @click="refreshTokens">
        <i class="fa fa-refresh" style="font-size:15px" :class="{ 'animate-spin': loading }"></i>
      </button>
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
           Yearly APY
          </th>
          <th class="p-2 text-right">
           Yearly gain
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
        <tr v-for="(token, index) in tokens[0]">
          <td class="p-2 text-slate-800 dark:text-slate-100 pl-3 w-1/3">
            {{ token.name }}
          </td>
          <td class="p-2 text-right text-yellow-300">
            {{ token.balance.toFixed(3) }}
          </td>
          <td class="p-2 text-right text-red-300">
            {{ currencyFormat(token.price * eurPrice, 'EUR') }}
          </td>
          <td class="p-2 text-right text-red-300">
            {{ currencyFormat(token.price) }}
          </td>
          <td class="p-2 text-right text-white">
            <span :class="{
              'text-red-500': getDailyChange(index, token.price) < 0,
              'text-green-500': getDailyChange(index, token.price) > 0
            }">{{ getDailyChange(index, token.price) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            {{ currencyFormat(token.price * eurPrice * token.balance, 'EUR') }}
          </td>
          <td class="p-2 text-right text-emerald-300">
            {{ currencyFormat(token.price * token.balance) }}
          </td>
          <td class="p-2 text-right text-sky-300">
            {{ (token.price * token.balance / ethPrice).toFixed(3) }}
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyApy(index) != 0" :class="{
              'text-red-500': getWeeklyApy(index) < 0,
              'text-green-500': getWeeklyApy(index) > 0
            }">{{ getWeeklyApy(index) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyGain(index) != 0" :class="{
              'text-red-500': getWeeklyGain(index) < 0,
              'text-green-500': getWeeklyGain(index) > 0
            }">{{ currencyFormat(getWeeklyGain(index)) }}</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyApy(index) != 0" :class="{
              'text-red-500': getYearlyApy(index) < 0,
              'text-green-500': getYearlyApy(index) > 0
            }">{{ getYearlyApy(index) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getYearlyGain(index) != 0" :class="{
              'text-red-500': getYearlyGain(index) < 0,
              'text-green-500': getYearlyGain(index) > 0
            }">{{ currencyFormat(getYearlyGain(index)) }}</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getMonthlyApy(index) != 0" :class="{
              'text-red-500': getMonthlyApy(index) < 0,
              'text-green-500': getMonthlyApy(index) > 0
            }">{{ getMonthlyApy(index) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getMonthlyGain(index) != 0" :class="{
              'text-red-500': getMonthlyGain(index) < 0,
              'text-green-500': getMonthlyGain(index) > 0
            }">{{ currencyFormat(getMonthlyGain(index)) }}</span>
          </td>
          <td class="p-2 w-2/12">
            <token-chart
              :labels="Object.keys(tokens)"
              :data="getBalanceHistory(token.name)"
            />
          </td>
          <td class="p-2 w-2/12">
            <token-chart
              :labels="Object.keys(tokens)"
              :data="getPriceHistory(token.name)"
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
  import axios from "axios"
  import TokenChart  from '../components/Dashboard/TokenChart.vue'
  import BalancesChart  from '../components/Dashboard/BalancesChart.vue'

  export default {
    components: {
      TokenChart,
      BalancesChart
    },
    props: {
      prices: Object,
      tokens: Object,
      totals: Object,
      balance: Number,
      transactions: Object,
    },
    data() {
      return {
        loading: false
      }
    },
    created() {
      this.debt = this.totals.debts.at(-1)
      this.total = this.totals.totals.at(-1)
      this.eurPrice = this.prices.eur.at(-1)
      this.ethPrice = this.prices.eth.at(-1)
      this.btcPrice = this.prices.btc.at(-1)
    },
    methods: {
      getDailyChange(index) {
        const prevPrice = this.tokens[1][index].price

        return ((this.tokens[0][index].price - prevPrice) / prevPrice * 100).toFixed(2)
      },
      getWeeklyApy(index) {
        const balance = this.tokens[0][index].balance

        return (((balance - (this.tokens[6][index] || {}).balance) / balance) * 100 || 0).toFixed(2)
      },
      getWeeklyGain(index) {
        const token = this.tokens[0][index]

        return ((token.balance - (this.tokens[6][index] || {}).balance) * token.price).toFixed(2)
      },
      getYearlyApy(index) {
        return (this.getWeeklyApy(index) / 7 * 365).toFixed(2)
      },
      getYearlyGain(index) {
        return (this.getWeeklyGain(index) / 7 * 365).toFixed(2)
      },
      getMonthlyApy(index) {
        const balance = this.tokens[0][index].balance

        return (((balance - (this.tokens.at(-1)[index] || {}).balance) / balance) * 100 || 0).toFixed(2)
      },
      getMonthlyGain(index) {
        const token = this.tokens[0][index]

        return ((token.balance - (this.tokens.at(-1)[index] || {}).balance) * token.price || 0).toFixed(2)
      },
      getBalanceHistory(name) {
        return this.tokens.map(token => (token[name] || {}).balance).reverse()
      },
      getPriceHistory(name) {
        return this.tokens.map(token => token[name] ? (token[name].balance * token[name].price) : 0).reverse()
      },
      currencyFormat(number, currency='USD') {
        return new Intl.NumberFormat(
          currency=='EUR' ? 'es-ES' : 'en-US',
          { style: 'currency', currency }
        ).format(number);
      },
      refreshTokens() {
        this.loading = true

        axios.get('get-tokens').then(({data}) => {
          this.debt = data.debt
          this.btcPrice = data.bitcoinPrice.usd
          this.ethPrice = data.ethereumPrice.usd
          this.eurPrice = data.bitcoinPrice.eur / data.bitcoinPrice.usd

          this.total = 0

          data.balances.forEach(newToken => {
            this.tokens[0][newToken.name] = newToken

            this.total += newToken.price * newToken.balance
          })

          this.loading = false
        })
      }
    }
  }
</script>
