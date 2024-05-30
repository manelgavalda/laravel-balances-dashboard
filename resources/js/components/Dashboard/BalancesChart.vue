<template>
  <div class="flex flex-col col-span-full sm:col-span-12 bg-white dark:bg-slate-800 shadow-lg border border-slate-200 dark:border-slate-700">
    <header class="px-5 py-2 border-b border-slate-100 dark:border-slate-700 flex items-center">
      <h2 class="font-semibold text-slate-800 dark:text-slate-100">{{ label }}</h2>
    </header>
    <div class="px-5 py-3">
      <div class="flex flex-wrap justify-between items-end ">
        <div class="flex items-start ">
          <div class="text-right mr-2 text-slate-800 dark:text-slate-100">
            <div class="text-3xl font-bold">{{ total }}</div>
            <div class="text-lg font-semibold h-2">{{ subtotal ?? '-' }}</div>
          </div>
          <div class="text-sm font-semibold text-white px-1.5 rounded-full" :class="{
            'bg-red-700': getChange() < 0,
            'bg-green-700': getChange() >= 0
          }">{{ getChange() }}%</div>
        </div>
      </div>
    </div>
    <div>
      <Line
        height="150"
        width="875"
        :data="{
          labels: dates,
          datasets: [{ data }]
        }"
        :options="{
          color: 'white',
          borderColor: chartColor,
          backgroundColor: chartColor,
          elements: { point: { radius: 2 } },
          plugins: { legend: { display: false } },
          scales: {
            y: { ticks: { color: 'white' } },
            x: { ticks: { color: 'white' } }
          }
        }"
      />
    </div>
  </div>
</template>
<script>
  import { Line } from 'vue-chartjs'
  import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'

  ChartJS.register( CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend)

  export default {
    props: {
      data: Array,
      dates: Array,
      color: String,
      label: String,
      total: String,
      subtotal: String
    },
    components: {
      Line
    },
    created() {
      this.chartColor = this.color || (this.getChange() > 0 ? 'green' : 'red')
    },
    methods: {
      getChange() {
        return ((this.data.at(-1) - this.data.at(-2)) / this.data.at(-2) * 100).toFixed(2)
      }
    }
  }
</script>
