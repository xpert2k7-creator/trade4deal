@props([
    'chartId',
    'labels' => [],
    'values' => [],
    'verifiedValues' => null,
    'title' => 'Buy leads added (last 30 days)',
    'datasetLabel' => 'Leads submitted',
    'verifiedDatasetLabel' => 'Verified',
])

<div class="panel">
    <div class="panel-header">
        <h2>{{ $title }}</h2>
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
        const verifiedValues = @json($verifiedValues);
        const ctx = document.getElementById(@json($chartId));
        if (!ctx) return;

        const datasets = [{
            label: @json($datasetLabel),
            data: values,
            backgroundColor: 'rgba(11, 58, 110, 0.75)',
            borderRadius: 6,
        }];

        if (Array.isArray(verifiedValues)) {
            datasets.push({
                label: @json($verifiedDatasetLabel),
                data: verifiedValues,
                backgroundColor: 'rgba(25, 135, 84, 0.75)',
                borderRadius: 6,
            });
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets,
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
