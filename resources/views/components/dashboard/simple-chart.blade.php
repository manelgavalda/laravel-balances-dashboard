<div>
    <canvas id="{{ $element }}"  width="100%" height="13%"></canvas>
</div>
<script>
    var color = "{{ $data[0] <= end($data) ? 'green' : 'red' }}"

    new Chart(@json($element), {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{ data: @json($data) }]
        },
        options: {
            elements: {
                point:{
                    radius: 0
                }
            },
            borderColor: color,
            plugins: { legend: { display: false }},
            scales: {
                y: { ticks: { display: false } },
                x: { ticks: { display: false } }
            }
        }
    })
</script>
