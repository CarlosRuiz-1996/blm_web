<?php

use App\Http\Controllers\administracion\administracioncontroller;
use App\Http\Controllers\altaValidaCumplimiento;
use App\Http\Controllers\Anexo1;
use App\Http\Controllers\BancosController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\boveda;
use App\Http\Controllers\BovedaController;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\ClientesActivosController;
use App\Http\Controllers\cumplimientoController;
use App\Http\Controllers\Factibilidad;
use App\Http\Controllers\juridicoController;
use App\Http\Controllers\MemorandumController;
use App\Http\Controllers\OperacionesController;
use App\Http\Controllers\Operadores;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\RhController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\tableroController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ValidacionMemorandumController;
use App\Http\Controllers\ventasController;
use App\Livewire\BancosGestion;
use App\Livewire\Boveda\CambioEfectivo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//rutas para livewire
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // 'role.redirect', 

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('role.redirect', 'role:Super|Admin')->name('dashboard');

    Route::middleware(['role:Super|Admin'])->group(function () {

        Route::get('/usuarios', [UsuariosController::class, 'index'])->name('user.index');
        Route::get('/usuarios/nuevo', [UsuariosController::class, 'nuevousuario'])->name('user.create');
        Route::post('/usuarios/save', [UsuariosController::class, 'store'])->name('user.store');
        Route::get('/usuarios/actualizar/{user}', [UsuariosController::class, 'actualizarusuario'])->name('user.edit');
        Route::put('/usuarios/updated/{user}', [UsuariosController::class, 'updated'])->name('user.updated');
        Route::get('/usuarios/delete/{user}', [UsuariosController::class, 'delete'])->name('user.delete');
        Route::get('/usuarios/reactivar/{user}', [UsuariosController::class, 'reactivar'])->name('user.reactivar');
        Route::get('/usuarios/cambio-contrasenia/{user}', [UsuariosController::class, 'password_view'])->name('user.password');
        Route::put('/usuarios/password/{user}', [UsuariosController::class, 'password'])->name('user.save-password');
    });
    //cliente activo
    // Route::middleware(['permission:menu-clientes'])->group(function () {
        Route::get('/clientes-activos', [ClientesActivosController::class, 'index'])->name('cliente.index');
        Route::get('/cliente/nuevo', [ClientesActivosController::class, 'nuevousuario'])->name('cliente.create');
        Route::get('/cliente/detalles/{cliente}/{op}', [ClientesActivosController::class, 'detalles'])->name('cliente.detalles');
        Route::get('/cliente/editar/{cliente}', [ClientesActivosController::class, 'edit'])->name('cliente.edit');
        Route::get('/clientesactivos/CotizacionesNuevas', [ClientesActivosController::class, 'CotizacionesNuevas'])->name('clientesactivos.CotizacionesNuevas');
        Route::get('/clientesactivos/cotizardenuevo/{id}', [ClientesActivosController::class, 'cotizardenuevo'])->name('clientesactivos.cotizardenuevo');
    // });

    // Route::middleware(['permission:menu-clientes'])->group(function () {

        Route::get('/ventas', [ventasController::class, 'indexventas'])->name('ventas.indexventas');
        Route::get('/ventas/altaSolicitudCumplimiento/{id}', [ventasController::class, 'altaSolicitudCumplimiento'])->name('clientesactivos.altaSolicitudCumplimiento');
        Route::get('/ventas/expediente-digital/{id}/{sts}', [ventasController::class, 'expediente_digital'])->name('cliente.expediente');
        Route::get('/ventas/detalle-cotizacion/{cotizacion}', [ClientesActivosController::class, 'detalle_cotizacion'])->name('cotizacion.detalle');
        Route::get('/ventas/cotizacion-pdf/{cotizacion}', [ClientesActivosController::class, 'cotizacion_pdf'])->name('cotizacion.pdf');
    
    // });
   
    Route::get('/ventas/detalle-cotizacion/{cotizacion}', [ClientesActivosController::class, 'detalle_cotizacion'])->name('cotizacion.detalle');
    Route::get('/ventas/cotizacion-pdf/{cotizacion}', [ClientesActivosController::class, 'cotizacion_pdf'])->name('cotizacion.pdf');

    // Route::get('/cumplimiento/altaValidaCumplimiento/{id}', [altaValidaCumplimiento::class, 'index'])->name('altaValidaCumplimiento.index');
    Route::get('/cumplimiento/validacion-cumplimiento/{id}', [cumplimientoController::class, 'validacion'])->name('cumplimiento.validacion');

    Route::get('/cumplimiento', [cumplimientoController::class, 'index'])->name('cumplimiento.index');
    Route::get('/cumplimiento/pdf/{id}', [cumplimientoController::class, 'pdfcumplimiento'])->name('cumplimiento.pdfdictamencumplimiento');
    Route::get('/cumplimiento/pdfnegado/{id}', [cumplimientoController::class, 'pdfcumplimientonegado'])->name('cumplimiento.pdfdictamencumplimientonegado');


    Route::get('/juridico', [juridicoController::class, 'index'])->name('juridico.index');
    Route::get('/juridico/altaValidaJuridico/{id}', [juridicoController::class, 'validajuridico'])->name('juridico.validajuridico');


    //admin
    Route::resource('/admin/permisos', PermisosController::class)->names('permisos');
    Route::resource('/admin/roles', RoleController::class)->names('roles');
    Route::put('/admin/rol/actualizar/{role}', [RoleController::class, 'updated_rol'])->name('rol.actualizar');
    Route::put('/admin/permiso/actualizar/{permiso}', [PermisosController::class, 'updated_permiso'])->name('permiso.actualizar');

    Route::get('/admin/bitacora', [BitacoraController::class, 'index'])->name('bitacora');
    Route::get('/admin/catalogos', [CatalogosController::class, 'index'])->name('catalogo');
    Route::get('/admin/catalogos/listar/{op}', [CatalogosController::class, 'listar'])->name('catalogo.listar');



    // anexo 1
    Route::get('ventas/anexo1/{cotizacion}', [Anexo1::class, 'index'])->name('anexo.index');
    Route::get('ventas/anexo1-pdf/{anexo}', [Anexo1::class, 'anexo_pdf'])->name('anexo.pdf');

    // boveda
    Route::get('boveda/', [boveda::class, 'index'])->name('boveda.index');
    Route::get('/boveda/inicio', [BovedaController::class, 'index'])->name('boveda.inicio');
    Route::get('/boveda/reporte', [BovedaController::class, 'bovedaresguardo'])->name('boveda.bovedaresguardo');
    Route::get('/boveda/acta_diferencia-pdf/{diferencia}', [BovedaController::class, 'acta_diferencia'])->name('acta_diferencia.pdf');
    Route::get('/boveda/procesa-ruta/{ruta}', [BovedaController::class, 'procesa_ruta'])->name('boveda.procesa-ruta');
    Route::get('/boveda/cambio-efectivo', [BovedaController::class, 'cambio_efectivo'])->name('boveda.cambio');

    //factibilidad
    Route::get('seguridad/', [Factibilidad::class, 'index'])->name('seguridad.index');
    Route::get('seguridad/reporte/{anexo}', [Factibilidad::class, 'reporte'])->name('seguridad.reporte');
    Route::get('seguridad/reportePDF', [Factibilidad::class, 'showPDF'])->name('seguridad.pdf');
    //memorandum
    Route::get('ventas/memorandum/{factibilidad}', [MemorandumController::class, 'create'])->name('memorandum');
    Route::get('ventas/memorandum/validacion/{memorandum}', [MemorandumController::class, 'validacion'])->name('memorandum.validacion');



    //rh
    Route::get('rh/', [RhController::class, 'index'])->name('rh.index');
    Route::get('rh/altaempleado', [RhController::class, 'altaempleado'])->name('rh.altaempleado');
    Route::get('rh/empleadosactivos', [RhController::class, 'EmpleadosActivos'])->name('rh.EmpleadosActivos');
    Route::get('rh/empleadosinactivos', [RhController::class, 'EmpleadosInactivos'])->name('rh.EmpleadosInactivos');
    Route::get('rh/vacaciones', [RhController::class, 'indexVacaciones'])->name('rh.indexVacaciones');
    Route::get('rh/{id}/perfil', [RhController::class, 'EmpleadosPerfil'])->name('rh.perfil');
    Route::get('rh/solicitudVacaciones', [RhController::class, 'solicitudVacaciones'])->name('rh.solicitudVacaciones');

    Route::get('validacion/memorandum/{memorandum}/{area}/{admin?}', [ValidacionMemorandumController::class, 'validar'])->name('memorandum.validar');
    Route::get('validacion/listar/{area}/{name?}/{admin?}', [ValidacionMemorandumController::class, 'listar'])->name('memorandum.validar.listar');


    //operaciones.-rutas
    Route::get('/operaciones', [OperacionesController::class, 'index'])->name('operaciones');
    Route::get('/ruta/gestion/{op}/{ruta?}', [OperacionesController::class, 'ruta_gestion'])->name('ruta.gestion');
    //hoja de rutas
    Route::get('/ruta/ruta-pdf/{ruta}', [OperacionesController::class, 'hoja_ruta'])->name('ruta.pdf');


    //operadores
    Route::get('/operadores', [Operadores::class, 'index'])->name('index');


    //bancos
    Route::get('/bancos', [BancosController::class, 'index'])->name('bancos.index');
    //tablero
    // Route::get('/tablero', [tableroController::class, 'index'])->name('tablero.index');

    //tablero
    Route::get('/tablero', [tableroController::class, 'index'])->name('tablero.index');
    //tablero
    Route::get('/administraciontablero', [administracioncontroller::class, 'index'])->name('administracion.index');
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/livewire/livewire.js', $handle);
});
