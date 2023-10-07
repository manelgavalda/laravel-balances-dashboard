<div>
    <canvas id="{{ $element }}" height="35"></canvas>
</div>
<script>
    var color = "{{ $data[0] < end($data) ? 'green' : 'red' }}"

    new Chart(@json($element), {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{ data: @json($data) }]
        },
        options: {
            borderColor: color,
            backgroundColor: color,
            plugins: { legend: { display: false }},
            scales: {
                y: { ticks: { display: false } },
                x: { ticks: { display: false } }
            }
        }
    })
</script>
