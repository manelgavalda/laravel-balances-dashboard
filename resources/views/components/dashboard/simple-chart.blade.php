<div>
    <canvas id="{{ $element }}" height="35"></canvas>
</div>
<script>
    new Chart(@json($element), {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{ data: @json($data) }]
        },
        options: {
            backgroundColor: '{{ $color }}',
            borderColor: '{{ $color }}',
            scales: {
                y: { ticks: { display: false } },
                x: { ticks: { display: false } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    })
</script>
