<div>
    <style>
        .pagination {
            font-size: 0.875rem;
            /* Cambia el tamaño de la fuente */
        }

        .pagination .page-link {
            padding: 0.25rem 0.5rem;
            /* Ajusta el padding de los enlaces */
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            /* Color de fondo para la página activa */
            border-color: #007bff;
            /* Color del borde para la página activa */
        }
    </style>
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
            <div class="form-group mt-2 mb-1">
                <button type="button" class="btn btn-primary btn-block" wire:click='updateData'>Filtrar</button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico combinado -->
        <div class="col-md-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Resguardo</span>


                    <span class="info-box-number">

                        $ {{ number_format($resguardototal, 2, '.', ',') }} MXN
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center">
                    <h4 class="card-title mb-0">Servicios de Entrega y Recolección</h4>
                    <i class="fas fa-chevron-up toggle-icon ml-auto" data-toggle="collapse" data-target="#collapseChart"
                        aria-expanded="true" aria-controls="collapseChart" style="cursor: pointer;"></i>
                </div>
                <div id="collapseChart" class="collapse show">
                    <div class="card-body" style="position: relative; height: 400px;">
                        <canvas id="combinedChart" wire:ignore.self style="height: 100%; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta para monto total de servicios de entrega -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center">
                    <h4 class="card-title">Monto Total Entrega: ${{ number_format($totalMontosEntrega, 2) }}</h4>
                    <i class="fas fa-chevron-up toggle-icon ml-auto" data-toggle="collapse"
                        data-target="#collapseEntrega" aria-expanded="true" aria-controls="collapseEntrega"
                        style="cursor: pointer;"></i>
                </div>
                <div id="collapseEntrega" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if ($entregaServicios->isNotEmpty())
                            <table class="table table-bordered table-rounded table-sm text-xs">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>Ruta</th>
                                        <th>Servicio</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entregaServicios as $servicio)
                                    <tr>
                                        <td class="text-xs">{{ $servicio->ruta->nombre->name}}</td>
                                        <td class="text-xs">{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                        <td class="text-xs">{{ $servicio->monto }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $entregaServicios->links() }}
                            <!-- Aquí se imprime la paginación -->
                            @else
                            <p>No hay servicios de entrega disponibles.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta para monto total de servicios de recolección -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center">
                    <h4 class="card-title">Monto Total Recolección: ${{ number_format($totalMontosRecoleccion, 2) }}
                    </h4>
                    <i class="fas fa-chevron-up toggle-icon ml-auto" data-toggle="collapse"
                        data-target="#collapseRecoleccion" aria-expanded="true" aria-controls="collapseRecoleccion"
                        style="cursor: pointer;"></i>
                </div>
                <div id="collapseRecoleccion" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if ($recoleccionServicios->isNotEmpty())
                            <table class="table table-bordered table-rounded table-sm text-xs">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>Ruta</th>
                                        <th>Servicio</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recoleccionServicios as $servicio)
                                    <tr>
                                        <td class="text-xs">{{ $servicio->ruta->nombre->name}}</td>
                                        <td class="text-xs">{{ $servicio->servicio->ctg_servicio->descripcion }}</td>
                                        <td class="text-xs">{{ $servicio->monto }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $recoleccionServicios->links() }}
                            <!-- Aquí se imprime la paginación -->
                            @else
                            <p>No hay servicios de recolección disponibles.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta para rutas totales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center">
                    <h4 class="card-title">Rutas totales: {{$totalderutas}}</h4>
                    <i class="fas fa-chevron-up toggle-icon ml-auto" data-toggle="collapse"
                        data-target="#collapseRoutes" aria-expanded="true" aria-controls="collapseRoutes"></i>
                </div>
                <div id="collapseRoutes" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-rounded table-sm text-xs">
                                <thead class="bg-dark">
                                    <tr>
                                        <th class="col-2">Nombre</th> <!-- Ancho pequeño -->
                                        <th class="col-2">Cantidad</th>
                                        <th>Total</th> <!-- Ancho grande -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diasrutas as $ruta)
                                    <tr>
                                        <td class="text-xs">{{ $ruta->name }}</td>
                                        <td class="text-xs">{{ $ruta->rutasdia_count }}</td>
                                        <td class="text-xs">
                                            <div class="progress" style="position: relative;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $totalderutas > 0 ? ($ruta->rutasdia_count / $totalderutas) * 100 : 0 }}%;"
                                                    aria-valuenow="{{ $totalderutas > 0 ? ($ruta->rutasdia_count / $totalderutas) * 100 : 0 }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                                <span class="text-center"
                                                    style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                                                    {{ number_format($totalderutas > 0 ? ($ruta->rutasdia_count /
                                                    $totalderutas) * 100 : 0, 2) }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center">
                    <h4 class="card-title">Total Reprogramaciones: {{ $totalreprogramacion }}</h4>
                    <i class="fas fa-chevron-up toggle-icon ml-auto" data-toggle="collapse"
                        data-target="#collapseReprogramacion" aria-expanded="true"
                        aria-controls="collapseReprogramacion" style="cursor: pointer;"></i>
                </div>
                <div id="collapseReprogramacion" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if ($reprogramacion->isNotEmpty())
                            <table class="table table-bordered table-rounded table-sm text-xs">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>Ruta Anterior</th>
                                        <th>Ruta Actual</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reprogramacion as $repro)
                                    <tr>
                                        <td>{{$repro->rutaOld->nombre->name}}</td>
                                        <td>{{$repro->rutaNew?->nombre->name}}</td>
                                        <td>{{$repro->motivo}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $reprogramacion->links() }}
                            <!-- Aquí se imprime la paginación -->
                            @else
                            <p>No hay servicios de reprogramacíon disponibles.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tarjeta para monto total de servicios de recolección -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center">
                    <h4 class="card-title">Actas de diferencia: {{ $totalactas }}</h4>
                    <i class="fas fa-chevron-up toggle-icon ml-auto" data-toggle="collapse"
                        data-target="#collapseInconsistencias" aria-expanded="true"
                        aria-controls="collapseInconsistencias" style="cursor: pointer;"></i>
                </div>
                <div id="collapseInconsistencias" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if ($inconsistencias->isNotEmpty())
                            <table class="table table-bordered table-rounded table-sm text-xs">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Folio</th>
                                        <th>Imp.Indicado</th>
                                        <th>Imp.Comprobado</th>
                                        <th>Diferencia</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inconsistencias as $inco)
                                    <tr>
                                        <td class="text-xs">{{ $inco->cliente->razon_social}}</td>
                                        <td class="text-xs">{{ $inco->fecha_comprobante}}</td>
                                        <td class="text-xs">{{ $inco->folio }}</td>
                                        <td class="text-xs">${{ number_format($inco->importe_indicado, 2, ',', '.') }}
                                        </td>
                                        <td class="text-xs">${{ number_format($inco->importe_comprobado, 2, ',', '.') }}
                                        </td>
                                        <td class="text-xs">${{ number_format($inco->diferencia, 2, ',', '.') }}</td>
                                        <td class="text-xs">{{ $inco->observacion }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $inconsistencias->links() }}
                            <!-- Aquí se imprime la paginación -->
                            @else
                            <p>No hay Actas de diferencia disponibles.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center">
                    <h4 class="card-title">Vehiculos</h4>
                    <i class="fas fa-chevron-up toggle-icon ml-auto" data-toggle="collapse"
                        data-target="#collapseVehiculosReporte" aria-expanded="true"
                        aria-controls="collapseVehiculosReporte" style="cursor: pointer;"></i>
                </div>
                <div id="collapseVehiculosReporte" class="collapse show">
                    <div class="card-body">
                        <!-- Tabla de Vehículos -->
                        <table class="table table-bordered table-striped">

                            <thead>
                                <tr>
                                    <th>Ruta</th>
                                    <th>Servicio</th>
                                    <th>Serie</th>
                                    <th>Placas</th>
                                    <th>KM por litro</th>
                                    <th>Combustible</th>
                                    <th>Distancia Km</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- ruta servicio --}}
                                @forelse($vehiculosservi as $vehiculosservicios)
                                <tr>
                                    <td>{{$vehiculosservicios->rutaServicioVehiculo->ruta->nombre->name}}</td>
                                    <td>{{$vehiculosservicios->rutaServicioVehiculo->servicio->ctg_servicio->descripcion}}
                                    </td>
                                    <td>{{$vehiculosservicios->vehiculoRuta->serie}}</td>
                                    <td>{{$vehiculosservicios->vehiculoRuta->placas}}</td>
                                    <td>{{$vehiculosservicios->vehiculoRuta->litro_km}}</td>
                                    <td>
                                        {{$vehiculosservicios->vehiculoRuta->tipo_combustible==1?'Magna':''}}
                                        {{$vehiculosservicios->vehiculoRuta->tipo_combustible==2?'Premium':''}}
                                        {{$vehiculosservicios->vehiculoRuta->tipo_combustible==3?'Diesel':''}}
                                    </td>
                                    <td>
                                        {{$vehiculosservicios->km}}
                                    </td>
                                    <td>

                                        $ {{ number_format($this->getPrice($vehiculosservicios), 2, '.', ',' )}}MXN
                                    </td>
                                    <td>
                                        $ {{ number_format($this->costo($vehiculosservicios), 2, '.', ',') }}MXN
                                    </td>

                                </tr>
                                @empty
                                <p>No hay elementos disponibles.</p>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $vehiculosservi->links() }}
                        <!-- Gráfica de Dona 1 - Vehículos más usados -->
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <h5>Vehículos más usados</h5>
                                <canvas id="vehiculosDonaChart"></canvas>
                            </div>

                            <!-- Gráfica de Dona 2 - Kilometraje -->
                            <div class="col-md-6">
                                <h5>Kilometraje por Vehículo</h5>
                                <canvas id="kmtrajeDonaChart"></canvas>
                            </div>
                        </div> --}}
                    </div>
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

            $('.collapse').on('show.bs.collapse', function () {
            $(this).parent().find('.toggle-icon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        });

        $('.collapse').on('hide.bs.collapse', function () {
            $(this).parent().find('.toggle-icon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        });
        });
    </script>
    <script>
        // Gráfico de Dona - Vehículos más usados
    var ctxVehiculos = document.getElementById('vehiculosDonaChart').getContext('2d');
    var vehiculosDonaChart = new Chart(ctxVehiculos, {
        type: 'doughnut',
        data: {
            labels: ['Vehículo A', 'Vehículo B', 'Vehículo C', 'Vehículo D'],
            datasets: [{
                data: [120, 150, 180, 100],
                backgroundColor: ['#FF6347', '#36A2EB', '#FFCE56', '#4CAF50'],
                hoverBackgroundColor: ['#FF4500', '#3D8BFF', '#FFD700', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' km';
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Dona - Kilometraje por Vehículo
    var ctxKmtraje = document.getElementById('kmtrajeDonaChart').getContext('2d');
    var kmtrajeDonaChart = new Chart(ctxKmtraje, {
        type: 'doughnut',
        data: {
            labels: ['Vehículo A', 'Vehículo B', 'Vehículo C', 'Vehículo D'],
            datasets: [{
                data: [250, 320, 200, 150],
                backgroundColor: ['#FF6347', '#36A2EB', '#FFCE56', '#4CAF50'],
                hoverBackgroundColor: ['#FF4500', '#3D8BFF', '#FFD700', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' km';
                        }
                    }
                }
            }
        }
    });
    </script>
    @endpush
</div>