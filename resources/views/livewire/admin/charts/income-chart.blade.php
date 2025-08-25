<div>
    <div id="{{ $chartId }}" style="height:{{ $height }}px; overflow-y:hidden; " class="scroll-container"></div>

    @script
        <script>
            (function () {
                const chartId = '{{ $chartId }}';
                const options = {
                    chart: {
                        id: chartId,
                        type: 'area',
                        height: {{ $height }},
                        stacked: true, // Balken gestapelt darstellen
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
                    
                    dataLabels: {
                        enabled: false
                        },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    series: [
                        {
                            name: 'Mieten (€)',
                            data: @json($rentalData) // Einkünfte aus Mieten
                        },
                        {
                            name: 'Verkäufe (16%) (€)',
                            data: @json($salesData) // Einkünfte aus Verkäufen (16 %)
                        }
                    ],
                    xaxis: {
                        categories: @json($months), // Monatsnamen für X-Achse
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '10px',
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Einkünfte (€)'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return '€' + value.toFixed(2); // Formatierung mit €
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
