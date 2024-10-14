<div>
    <div class="row">
        <!-- Filtros de Fecha -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="startDate">Fecha Inicio:</label>
                <input type="date" wire:model="startDate" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="endDate">Fecha Fin:</label>
                <input type="date" wire:model="endDate" class="form-control">
            </div>
        </div>
        <div class="col-md-4 mt-4">
            <div class="form-group mt-1">
                <button type="button" class="btn btn-primary btn-block" wire:click='updateData'>Filtrar</button>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Gráfico combinado -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Servicios de Entrega y Recolección</h3>
                </div>
                <div class="card-body" style="position: relative; height: 400px;">
                    <canvas id="combinedChart" wire:ignore.self style="height: 100%; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {
            let combinedChart;

            function initCombinedChart(chartType, ctx, labels, entregaData, recoleccionData) {
                // Verifica si el chartType existe y destrúyelo
                if (chartType) {
                    chartType.destroy();
                }

                // Crea el gráfico combinado
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Servicios de Entrega',
                                data: entregaData,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Color moderno y claro
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                barThickness: 20,
                                categoryPercentage: 0.5,
                                maxBarThickness: 30
                            },
                            {
                                label: 'Servicios de Recolección',
                                data: recoleccionData,
                                backgroundColor: 'rgba(255, 99, 132, 0.6)', // Color moderno y claro
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 2,
                                barThickness: 20,
                                categoryPercentage: 0.5,
                                maxBarThickness: 30
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animations: {
                            tension: {
                                duration: 1000,
                                easing: 'easeOutBounce',
                                from: 1,
                                to: 0,
                                loop: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Cantidad',
                                    color: '#666',
                                    font: {
                                        weight: 'bold',
                                        size: 16
                                    }
                                },
                                grid: {
                                    color: 'rgba(200, 200, 200, 0.2)' // Color de la cuadrícula
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Servicios',
                                    color: '#666',
                                    font: {
                                        weight: 'bold',
                                        size: 16
                                    }
                                },
                                stacked: true,
                                grid: {
                                    color: 'rgba(200, 200, 200, 0.2)' // Color de la cuadrícula
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: '#333', // Color de las etiquetas de la leyenda
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)', // Fondo del tooltip
                                titleColor: '#fff', // Color del título
                                bodyColor: '#fff', // Color del cuerpo
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += context.parsed.y;
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function getCombinedData(entregaData, recoleccionData) {
                const combinedLabels = new Set([...Object.keys(entregaData), ...Object.keys(recoleccionData)]);
                const entregaValues = [];
                const recoleccionValues = [];

                const labelsArray = [...combinedLabels];

                labelsArray.forEach(label => {
                    entregaValues.push(entregaData[label] !== undefined ? entregaData[label] : 0);
                    recoleccionValues.push(recoleccionData[label] !== undefined ? recoleccionData[label] : 0);
                });
                
                return {
                    labels: labelsArray,
                    entregaValues,
                    recoleccionValues,
                };
            }

            // Inicializar gráficos con datos recibidos
            const combinedCtx = document.getElementById('combinedChart').getContext('2d');

            const { labels, entregaValues, recoleccionValues } = getCombinedData(@json($serviciosEntrega), @json($serviciosRecoleccion));
            combinedChart = initCombinedChart(combinedChart, combinedCtx, labels, entregaValues, recoleccionValues);

            Livewire.on('updatedChartData', (data) => {
                console.log('Datos actualizados de Livewire:', data);
                
                const { labels, entregaValues, recoleccionValues } = getCombinedData(data.serviciosEntrega, data.serviciosRecoleccion);

                combinedChart = initCombinedChart(combinedChart, combinedCtx, labels, entregaValues, recoleccionValues);
            });
        });
    </script>
    @endpush
</div>
