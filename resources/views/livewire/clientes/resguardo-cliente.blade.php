<div>
    <div class="container-fluid mt-2">
        <div class="text-center">
            <h4>Resguardo Total: 
                <span class="info-box-number">$ {{ number_format($clienteResguardo, 2, '.', ',') }} MXN</span>
            </h4>
        </div>

        <!-- Gráficos -->
        <div class="row mt-4">
            <div class="col-md-6">
                <!-- Coloca aquí tu primer gráfico -->
                <canvas id="myChart" style="width:100%;"></canvas>
            </div>
            <div class="col-md-6">
                <!-- Coloca aquí tu segundo gráfico -->
                <canvas id="myChart2" style="width:100%;"></canvas>
            </div>
        </div>
    </div>

    @section('js')
    <script>
        // Obtener el contexto del lienzo (canvas)
        var ctx = document.getElementById('myChart').getContext('2d');
        var ctx2 = document.getElementById('myChart2').getContext('2d');
        // Datos del gráfico
        var data = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'],
            datasets: [{
                label: 'Recolecciones',
                data: [12, 19, 3, 5, 2],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };
        var data2 = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'],
            datasets: [{
                label: 'Entregas',
                data: [12, 19, 3, 5, 2],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Opciones del gráfico
        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true // Mantén la responsividad activada
        };

        // Crear el gráfico
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
        var myChart2 = new Chart(ctx2, {
            type: 'bar',
            data: data2,
            options: options
        });
    </script>

@stop
</div>
