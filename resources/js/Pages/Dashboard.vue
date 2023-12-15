<template>
  <div class="flex">
    <div class="w-1/3">
      <balances-chart
        label="EUR Price"
        :dates="balances.dates"
        :data="balances.prices_eur"
        :total="currencyFormat(totals.pricesEur) + '€'"
      />
      <balances-chart
        label="USD Price"
        :dates="balances.dates"
        :data="balances.prices"
        :total="'$' + currencyFormat(totals.pricesUsd)"
      />
    </div>
    <div class="w-1/3">
      <balances-chart
        color="blue"
        label="Total ETH"
        :dates="balances.dates"
        :data="balances.ethereum"
        :total="totals.eth.toFixed(3)"
      />
    </div>
    <div class="w-1/3">
      <balances-chart
        label="Total EUR"
        :dates="balances.dates"
        :data="balances.totals_eur"
        :total="currencyFormat(totals.eur.toFixed(2)) + '€'"
      />
      <balances-chart
        label="Total USD"
        :dates="balances.dates"
        :data="balances.totals"
        :total="'$' + currencyFormat(totals.usd.toFixed(2))"
      />
    </div>
  </div>
  <div class="col-span-full xl:col-span-12 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 mt-4">
    <header class="p-4 border-b border-slate-100 dark:border-slate-700 inline-flex">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100 mr-2">Balances</h2>
      <button class="bg-gray-500 hover:bg-gray-700 font-bold py-2 px-4 rounded inline-flex items-center h-6" :disabled="loading" :class="{ 'bg-gray-700': loading }" @click="refreshTokens" >
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
            {{ token.pool }}
          </td>
          <td class="p-2 text-right text-yellow-300">
            {{ currencyFormat(token.balance.toFixed(2)) }}
          </td>
          <td class="p-2 text-right text-red-300">
            {{ currencyFormat(token.price_eur.toFixed(2)) }}
          </td>
          <td class="p-2 text-right text-red-300">
            ${{ currencyFormat(token.price.toFixed(2)) }}
          </td>
          <td class="p-2 text-right text-white">
            <span :class="{
              'text-red-500': getDailyChange(index, token.price) < 0,
              'text-green-500': getDailyChange(index, token.price) > 0
            }">{{ currencyFormat(getDailyChange(index, token.price)) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            {{ currencyFormat((token.price_eur * token.balance).toFixed(2)) }}
          </td>
          <td class="p-2 text-right text-emerald-300">
            ${{ currencyFormat((token.price * token.balance).toFixed(2)) }}
          </td>
          <td class="p-2 text-right text-sky-300">
            {{ currencyFormat(token.price * token.balance / balances.prices.slice(-1)) }}
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyApy(index) != 0" :class="{
              'text-red-500': getWeeklyApy(index) < 0,
              'text-green-500': getWeeklyApy(index) > 0
            }">{{ currencyFormat(getWeeklyApy(index)) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyGain(index) != 0" :class="{
              'text-red-500': getWeeklyGain(index) < 0,
              'text-green-500': getWeeklyGain(index) > 0
            }">${{ currencyFormat(getWeeklyGain(index)) }}</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getMonthlyApy(index) != 0" :class="{
              'text-red-500': getMonthlyApy(index) < 0,
              'text-green-500': getMonthlyApy(index) > 0
            }">{{ currencyFormat(getMonthlyApy(index)) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getMonthlyGain(index) != 0" :class="{
              'text-red-500': getMonthlyGain(index) < 0,
              'text-green-500': getMonthlyGain(index) > 0
            }">${{ currencyFormat(getMonthlyGain(index)) }}</span>
          </td>
          <td class="p-2 w-2/12">
            <token-chart
              :labels="Object.keys(tokens)"
              :data="getBalanceHistory(token.pool)"
            />
          </td>
          <td class="p-2 w-2/12">
            <token-chart
              :labels="Object.keys(tokens)"
              :data="getPriceHistory(token.pool)"
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
  import TokenChart  from '../components/Dashboard/TokenChart.vue'
  import BalancesChart  from '../components/Dashboard/BalancesChart.vue'

  export default {
    components: {
      TokenChart,
      BalancesChart
    },
    props: {
      tokens: Object,
      balance: Number,
      balances: Object,
      transactions: Array,
    },
    data() {
      return {
        loading: false,
      }
    },
    created() {
      this.refreshTokens()

      this.totals = {
        usd: this.balances.totals.at(-1),
        eth: this.balances.ethereum.at(-1),
        eur: this.balances.totals_eur.at(-1),
        pricesUsd: this.balances.prices.at(-1),
        pricesEur: this.balances.prices_eur.at(-1)
      }

      this.weeklyLast = this.tokens.splice(0, 7).at(-1)
    },
    methods: {
      getDailyChange(index) {
        const prevPrice = this.tokens[1][index].price

        return ((this.tokens[0][index].price - prevPrice) / prevPrice * 100).toFixed(2)
      },
      getWeeklyApy(index) {
        const balance = this.tokens[0][index].balance

        return (((balance - this.weeklyLast[index].balance) / balance) * 100 || 0).toFixed(2)
      },
      getWeeklyGain(index) {
        const token = this.tokens[0][index]

        return ((token.balance - this.weeklyLast[index].balance) * token.price).toFixed(2)
      },
      getMonthlyApy(index) {
        const balance = this.tokens[0][index].balance

        return (((balance - this.tokens.at(-1)[index].balance) / balance) * 100 || 0).toFixed(2)
      },
      getMonthlyGain(index) {
        const token = this.tokens[0][index]

        return ((token.balance - this.tokens.at(-1)[index].balance) * token.price).toFixed(2)
      },
      getTokensHistory(pool) {
        return this.tokens.flat().filter(token => token.pool == pool).reverse()
      },
      getBalanceHistory(pool) {
        return this.getTokensHistory(pool).map(token => token.balance)
      },
      getPriceHistory(pool) {
        return this.getTokensHistory(pool).map(token => token.balance * token.price)
      },
      refreshTokens() {
        this.loading = true

        axios.get('get-tokens').then(({data}) => {
          data.balances.forEach(newToken => {
            const token = this.tokens[0].find(token => token.pool == newToken.pool)

            token.price = newToken.price
            token.balance = newToken.balance
            token.price_eur = newToken.price_eur
          })

          this.totals = {
            usd: 0,
            eth: 0,
            eur: 0,
            pricesEur: data.ethereumPrice.eur,
            pricesUsd: data.ethereumPrice.usd
          }

          data.balances.forEach(token => {
            this.totals.usd += token.price * token.balance
            this.totals.eur += token.price_eur * token.balance
            this.totals.eth += token.price * token.balance / this.totals.pricesUsd
          })

          this.loading = false
        })
      },
      currencyFormat(number) {
        return new Intl.NumberFormat().format(number)
      }
    }
  }
</script>
