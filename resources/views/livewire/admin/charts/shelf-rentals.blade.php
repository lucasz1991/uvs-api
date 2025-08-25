<div>
    <div id="{{ $chartId }}" style="height:{{ $height }}px; overflow-y:hidden;" class="scroll-container"></div>

    @script
        <script>
            (function () {
                const chartId = '{{ $chartId }}';
                const options = {
                    chart: {
                        id: chartId,
                        type: 'bar',
                        height: {{ $height }},
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            dynamicAnimation: {
                                speed: 1000
                            }
                        },
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    series: [{
                        name: 'Mieten',
                        data: @json($data) // Anzahl der Mieten der letzten 12 Monate
                    }],
                    xaxis: {
                        categories: @json($months), // Monatsnamen für X-Achse
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '10px',
                            }
                        }
                    }
                };

                // Initialisiere das Chart
                const chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
                chart.render();
            })();
        </script>
    @endscript
</div>
