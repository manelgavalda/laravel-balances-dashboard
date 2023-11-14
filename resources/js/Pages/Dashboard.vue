<template>
    <div class="flex">
        <div class="w-1/3">
          <balances-chart
            label="EUR Price"
            :dates="balances.dates"
            :data="balances.prices_eur"
            :total="totalPricesEur + '€'"
          />
          <balances-chart
            label="USD Price"
            :dates="balances.dates"
            :data="balances.prices"
            :total="'$' + totalPricesUsd"
          />
        </div>
        <div class="w-1/3">
          <balances-chart
            color="blue"
            label="Total ETH"
            :dates="balances.dates"
            :data="balances.ethereum"
            :total="totalEth"
          />
        </div>
        <div class="w-1/3">
          <balances-chart
            label="Total EUR"
            :dates="balances.dates"
            :data="balances.totals_eur"
            :total="totalEur + '€'"
          />
          <balances-chart
            label="Total USD"
            :dates="balances.dates"
            :data="balances.totals"
            :total="totalUsd + '€'"
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
            <span v-if="getWeeklyApy(index) != 0" :class="{
              'text-red-500': getWeeklyApy(index) < 0,
              'text-green-500': getWeeklyApy(index) > 0
              }">{{ getWeeklyApy(index) }}%</span>
          </td>
          <td class="p-2 text-right text-emerald-300">
            <span v-if="getWeeklyGain(index) != 0" :class="{
              'text-red-500': getWeeklyGain(index) < 0,
              'text-green-500': getWeeklyGain(index) > 0
            }">${{ getWeeklyGain(index) }}</span>
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
            }">${{ getMonthlyGain(index) }}</span>
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
              :options="chartOptions"
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
              :options="chartOptions"
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
  import BalancesChart  from '../components/Chart.vue'
  import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'

  ChartJS.register( CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend)

  export default {
    components: {
      Line, BalancesChart
    },
    props: {
      balance: Number,
      tokens: Object,
      balances: Object,
      transactions: Array,
    },
    data() {
      return {
        loading: false,
        chartOptions: {
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
        },
        pricesEur: []
      }
    },
    created() {
      this.refreshTokens()

      this.monthlyLast = this.tokens.splice(-1)[0]
      this.weeklyLast = this.tokens.splice(0, 7).splice(-1)[0]

      this.totalUsd = this.balances.totals.at(-1).toFixed(2)
      this.totalEth = this.balances.ethereum.at(-1).toFixed(2)
      this.totalEur = this.balances.totals_eur.at(-1).toFixed(2)
      this.totalPricesUsd = this.balances.prices.at(-1).toFixed(2)
      this.totalPricesEur = this.balances.prices_eur.at(-1).toFixed(2)
    },
    methods: {
      getDailyChange(index) {
        const prevPrice = Object.values(this.tokens)[1][index].price

        return (this.tokens[0][index].price - prevPrice) / prevPrice * 100
      },
      getWeeklyApy(index) {
        return (((this.tokens[0][index].balance - this.weeklyLast[index].balance) / this.tokens[0][index].balance) * 100 || 0).toFixed(2)
      },
      getWeeklyGain(index) {
        return ((this.tokens[0][index].balance - this.weeklyLast[index].balance) * this.tokens[0][index].price).toFixed(2)
      },
      getMonthlyApy(index) {
        return (((this.tokens[0][index].balance - this.monthlyLast[index].balance) / this.tokens[0][index].balance) * 100 || 0).toFixed(2)
      },
      getMonthlyGain(index) {
        return ((this.tokens[0][index].balance - this.monthlyLast[index].balance) * this.tokens[0][index].price).toFixed(2)
      },
      getBalanceHistory(pool) {
        return this.tokens.flat().filter(token => token.pool == pool).reverse().map(token => token.balance)
      },
      getPriceHistory(pool) {
        return this.tokens.flat().filter(token => token.pool == pool).reverse().map(token => token.balance * token.price)
      },
      refreshTokens() {
        this.loading = true

        axios.get('api/get-tokens').then(({data}) => {
          data.balances.forEach(newToken => {
            const token = this.tokens[0].find(token => token.pool == newToken.pool)

            token.price = newToken.price
            token.balance = newToken.balance
            token.price_eur = newToken.price_eur
          })

          this.totalPricesEur = data.ethereumPrice.eur.toFixed(2)
          this.totalPricesUsd = data.ethereumPrice.usd.toFixed(2)

          this.totalUsd = data.balances.reduce((accumulator, token) => {
            return accumulator + token.price * token.balance
          }, 0).toFixed(2)

          this.totalEur = data.balances.reduce((accumulator, token) => {
            return accumulator + token.price_eur * token.balance
          }, 0).toFixed(2)

          this.totalEth = data.balances.reduce((accumulator, token) => {
            return accumulator + (token.price * token.balance / this.totalPricesUsd)
          }, 0).toFixed(2)

          this.loading = false
        })
      }
    }
  }
</script>
