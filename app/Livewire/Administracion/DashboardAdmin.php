<?php

namespace App\Livewire\Administracion;

use App\Livewire\Catalogos\Vehiculos;
use App\Models\Cliente;
use App\Models\CtgRutaDias;
use App\Models\CtgRutas;
use App\Models\CtgVehiculos;
use App\Models\CtgVehiculosRutaServicios;
use App\Models\FuelPrice;
use App\Models\Inconsistencias;
use App\Models\Reprogramacion;
use App\Models\Ruta;
use App\Models\RutaServicio;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DashboardAdmin extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $startDate;
    public $endDate;
    public $serviciosEntrega = [];
    public $serviciosRecoleccion = [];
    public $totalMontosEntrega;
    public $totalMontosRecoleccion;
    public $entregaServicios2;
    public $recoleccionServicios2;
    public $totalactas;
    public $totalreprogramacion;
    public $vehiculosservicios;

    //incializa el componenete con la fecha de inicio de mes y fin del mes actual
    public function mount()
    {
        // Inicializa las fechas al inicio y fin del mes actual
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    //renderiza el componente
    public function render()
    {
        $this->updateData();
        $this->datosMontos();
        $resguardototal = Cliente::where('status_cliente', 1)->sum('resguardo');
        $recoleccionServicios = $this->datosrecoleccion();
        $entregaServicios = $this->datosentrega();
        $inconsistencias = $this->datosinconsistencias();
        $reprogramacion = $this->datosreprogramacion();

        $vehiculos = $this->getVehiculos();
        $rutas = $this->getRutas();
        $diasrutas = CtgRutaDias::withCount('rutasdia')->get();
        $totalderutas = Ruta::count();
        $ctg_ruta_dia= CtgRutaDias::all();
        $ctg_ruta_name= CtgRutas::all();
        return view('livewire.administracion.dashboard-admin', compact('ctg_ruta_name','ctg_ruta_dia','diasrutas', 'totalderutas', 'recoleccionServicios', 'entregaServicios', 'resguardototal', 'inconsistencias', 'reprogramacion', 'rutas', 'vehiculos'));
    }

    //actulliza la informacion con el filtro del las fechas seleccionadas en la grafica
    public function updateData()
    {
        // Verificar si ambos filtros están presentes
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

            // Consultas para entrega y recolección con el rango de fechas
            $this->serviciosEntrega = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
                ->where('tipo_servicio', '1')
                ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get()
                ->pluck('cantidad', 'fecha')
                ->toArray();

            $this->serviciosRecoleccion = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
                ->where('tipo_servicio', '2')
                ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get()
                ->pluck('cantidad', 'fecha')
                ->toArray();

            // Emitir evento para actualizar gráficos
            $this->dispatch('updatedChartData', serviciosEntrega: $this->serviciosEntrega, serviciosRecoleccion: $this->serviciosRecoleccion);
        }
    }
    //obtiene informacion de montos de fechas seleccionadas
    public function datosMontos()
    {
        // Verificar si ambos filtros están presentes
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

            // Sumar montos para servicios de entrega
            $this->totalMontosEntrega = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
                ->where('tipo_servicio', '1')
                ->sum('monto');

            // Sumar montos para servicios de recolección
            $this->totalMontosRecoleccion = RutaServicio::whereBetween('created_at', [$startDate, $endDate])
                ->where('tipo_servicio', '2')
                ->sum('monto');
        }
    }

    //obtiene informacion de recoleccion por fechas
    public function datosrecoleccion()
    {
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

            return RutaServicio::whereBetween('created_at', [$startDate, $endDate])
                ->where('tipo_servicio', '2')
                ->paginate(5, pageName: 'invoices-pagedos');
        }

        return RutaServicio::where('tipo_servicio', '2')->paginate(5, pageName: 'invoices-pagedos'); // Devuelve paginación por defecto
    }

    //obtiene informacion de entregas por fecha seleccionada
    public function datosentrega()
    {
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

            return RutaServicio::whereBetween('created_at', [$startDate, $endDate])
                ->where('tipo_servicio', '1')
                ->paginate(5, pageName: 'invoices-page');
        }

        return RutaServicio::where('tipo_servicio', '1')->paginate(5, pageName: 'invoices-page'); // Devuelve paginación por defecto
    }

    //obtiene informacion de inconsistencias
    public function datosinconsistencias()
    {
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $this->totalactas = Inconsistencias::whereBetween('fecha_comprobante', [$startDate, $endDate])->count();
            return Inconsistencias::whereBetween('fecha_comprobante', [$startDate, $endDate])
                ->paginate(5, pageName: 'invoices-page3');
        }

        return Inconsistencias::paginate(5, pageName: 'invoices-page3'); // Devuelve paginación por defecto
    }

    //obtiene informacion de los servicios que fueron reporgramados
    public function datosreprogramacion()
    {

        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $this->totalreprogramacion = Reprogramacion::whereBetween('created_at', [$startDate, $endDate])->count();
            return Reprogramacion::whereBetween('created_at', [$startDate, $endDate])
                ->paginate(5, pageName: 'invoices-page4');
        }

        return Reprogramacion::paginate(5, pageName: 'invoices-page4'); // Devuelve paginación por defecto
    }

    public function datsovehiculosServicios()
    {
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            return CtgVehiculosRutaServicios::whereBetween('created_at', [$startDate, $endDate])
                ->paginate(5, pageName: 'invoices-page5');
        }

        return Reprogramacion::paginate(5, pageName: 'invoices-page5'); // Devuelve paginación por defecto 
    }

    public function getPrice(CtgVehiculosRutaServicios $vehiculo)
    {

        $fecha_vehiculo = Carbon::parse($vehiculo->rutaServicioVehiculo->fecha_servicio);

        $price = 0;
        $type_vehiculo = $vehiculo->vehiculoRuta->tipo_combustible;
        if ($type_vehiculo == 1) {
            $price = FuelPrice::where('fecha', $fecha_vehiculo)->where('type', 'Magna')->value('price');
        } elseif ($type_vehiculo == 2) {
            $price = FuelPrice::where('fecha', $fecha_vehiculo)->where('type', 'Premium')->value('price');
        } else {
            $price = FuelPrice::where('fecha', $fecha_vehiculo)->where('type', 'Diesel')->value('price');
        }


        return $price;
    }

    public function costo(CtgVehiculosRutaServicios $vehiculo)
    {

        // $fecha_vehiculo = Carbon::createFromFormat('Y-m-d', $vehiculo->rutaServicioVehiculo->fecha_servicio);
        $fecha_vehiculo = Carbon::parse($vehiculo->rutaServicioVehiculo->fecha_servicio);

        $price = 0;
        $type_vehiculo = $vehiculo->vehiculoRuta->tipo_combustible;
        if ($type_vehiculo == 1) {
            $price = FuelPrice::where('fecha', $fecha_vehiculo)->where('type', 'Magna')->value('price');
        } elseif ($type_vehiculo == 2) {
            $price = FuelPrice::where('fecha', $fecha_vehiculo)->where('type', 'Premium')->value('price');
        } else {
            $price = FuelPrice::where('fecha', $fecha_vehiculo)->where('type', 'Diesel')->value('price');
        }

        $distancia = $vehiculo->km;
        $litrosKm  = $vehiculo->vehiculoRuta->litro_km;

        if ($distancia == 0 || $litrosKm == 0) {
            return 'Sin información para calcular el valor';
        }
        return ($distancia / $litrosKm) * $price;
    }

    public $fechaInicio;
    public $fechaFin;
    public $placas;
    
    public $serie;
    public $tipo_combustible;
    public function getVehiculos()
    {
        $vehiculos = CtgVehiculos::query()
            ->with(['servicios_rutas' => function ($query) {
                if ($this->fechaInicio && $this->fechaFin) {
                    $query->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin]);
                }
            }])
            ->where('placas','like'. '%'.$this->placas.'%')
            ->where('serie','like'. '%',$this->serie.'%')
            ->where('tipo_combustible','like'. '%'.$this->tipo_combustible.'%')
            ->orderBy('id')
            ->paginate(5);

        return $vehiculos;
    }

    public $fechaInicioR;
    public $fechaFinR;
    public $ruta_name;
    public $ruta_dia;
    public function getRutas()
    {
        $rutas = Ruta::query()
            ->when($this->ruta_name, function ($query) {
                $query->whereHas('nombre', function ($q) {
                    $q->where('id',$this->ruta_name);
                });
            })
            ->when($this->ruta_dia, function ($query) {
                $query->whereHas('dia', function ($q) {
                    $q->where('id', $this->ruta_dia);
                });
            })
            ->orderBy('id')
            ->paginate(5);

        return $rutas;
    }
    public function cleanFiltrerVehiculos()
    {
        $this->fechaInicio = null;
        $this->fechaFin = null;
        $this->placas = null;
        $this->serie = null;
        $this->tipo_combustible = null;
    }
    public function cleanFiltrerRutas()
    {
        $this->fechaInicioR = null;
        $this->fechaFinR = null;
        $this->ruta_dia = null;
        $this->ruta_name = null;
    }
}
