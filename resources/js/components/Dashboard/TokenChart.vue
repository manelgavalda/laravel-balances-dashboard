<template>
  <Line
    height="30"
    :data="{
      labels,
      datasets: [{
        data,
        borderColor: 'green',
        backgroundColor: 'green'
      }]
    }"
    :options="{
      elements: { point: { radius: 0 } },
      plugins: { legend: { display: false } },
      scales: {
        y: { ticks: { display: false } },
        x: { ticks: { display: false } }
      }
    }"
  />
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
      data: Array,
      labels: Array
    },
  }
</script>
