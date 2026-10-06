@props(['chartId', 'labels' => [], 'values' => []])

<div class="panel">
    <div class="panel-header">
        <h2>Buy leads added (last 30 days)</h2>
    </div>
    <div class="panel-body">
        <canvas id="{{ $chartId }}" height="100"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const labels = @json($labels);
        const values = @json($values);
        const ctx = document.getElementById(@json($chartId));
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Leads submitted',
                    data: values,
                    backgroundColor: 'rgba(11, 58, 110, 0.75)',
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });
    })();
</script>
@endpush
