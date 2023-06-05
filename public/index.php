<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\EstadisticasController;
use Controllers\ReporteController;
use Controllers\DerrotaController;
use Controllers\MotoresController;
use Controllers\ConsumosController;
use Controllers\ComunicacionesController;
use Controllers\InteligenciaController;
use Controllers\NovedadesController;
use Controllers\LeccionesController;
use Controllers\InternacionalesController;
use Controllers\ValidacionRController;
use Controllers\ValidacionOController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);
$router->get('/internacionales', [InternacionalesController::class,'index']);
$router->get('/API/internacionales/catalogo', [InternacionalesController::class,'getCatalogo']);
$router->post('/API/internacionales/guardar', [InternacionalesController::class,'guardar']);
$router->post('/API/internacionales/modificar', [InternacionalesController::class,'modificar']);
$router->post('/API/internacionales/eliminar', [InternacionalesController::class,'eliminar']);
$router->get('/API/internacionales/buscar', [InternacionalesController::class,'buscarAPI']);
$router->get('/API/internacionales/colocarInfo', [InternacionalesController::class,'colocarInfo']);

/**ESTADISTICAS */
$router->get('/estadisticas', [EstadisticasController::class,'index']);
$router->get('/API/estadisticas/mapa', [EstadisticasController::class,'operacionesMapaApi']);
$router->get('/API/estadisticas/consumos', [EstadisticasController::class,'operacionesConsumosApi']);
$router->get('/API/estadisticas/comando', [EstadisticasController::class,'operacionesComandoApi']);
$router->get('/API/estadisticas/mensuales', [EstadisticasController::class,'operacionesMensualesApi']);
$router->get('/API/estadisticas/top', [EstadisticasController::class,'operacionesTopApi']);



//reporte
$router->get('/reporte', [ReporteController::class,'index']);
$router->get('/API/reporte/BusDatos', [ReporteController::class, 'BuscarDatosAPI'] );
$router->get('/API/reporte/CambioSit', [ReporteController::class, 'CambioSituacionAPI'] );



$router->get('/reporte/derrota', [DerrotaController::class,'index']);
$router->post('/API/reporte/derrota/GuardarDatos', [DerrotaController::class,'GuardarAPI']);
$router->get('/API/reporte/derrota/BusDerrota', [DerrotaController::class, 'BuscarDerrotas'] );

// motores
$router->get('/reporte/motores', [MotoresController::class,'index']);
$router->get('/API/reporte/motores/BusMotor', [MotoresController::class, 'BuscarMotores'] );
$router->get('/API/reporte/motores/BusTrabajo', [MotoresController::class, 'BuscarTrabajo'] );
$router->post('/API/reporte/motores/GuardarTrabajo', [MotoresController::class, 'GuardarTrabajoAPI'] );

//consumos
$router->get('/reporte/consumos', [ConsumosController::class,'index']);
$router->get('/API/reporte/consumos/BusConsumos', [ConsumosController::class, 'BuscarConsumos'] );
$router->get('/API/reporte/consumos/BusInsumos', [ConsumosController::class, 'BuscarInsumos'] );
$router->post('/API/reporte/consumos/GuardarCons', [ConsumosController::class, 'GuardarConsumoAPI'] );

//Comunicaciones
$router->get('/reporte/comunicaciones', [ComunicacionesController::class,'index']);
$router->get('/API/reporte/comunicaciones/BusComuni', [ComunicacionesController::class, 'BuscarComun'] );
$router->get('/API/reporte/comunicaciones/BusMedios', [ComunicacionesController::class, 'BuscarMedios'] );
$router->get('/API/reporte/comunicaciones/BusReceptores', [ComunicacionesController::class, 'BuscarReceptores'] );
$router->post('/API/reporte/comunicaciones/GuardarCom', [ComunicacionesController::class, 'GuardarComunicacionesAPI'] );

//Novedades
$router->get('/reporte/novedades', [NovedadesController::class,'index']);
$router->get('/API/reporte/novedades/BusNovedades', [NovedadesController::class, 'BuscarNovedades'] );
$router->post('/API/reporte/novedades/GuardarNov', [NovedadesController::class, 'GuardarNovedadesAPI'] );

//lecciones
$router->get('/reporte/lecciones', [LeccionesController::class,'index']);
$router->get('/API/reporte/lecciones/BusLecciones', [LeccionesController::class, 'BuscarLecciones'] );
$router->post('/API/reporte/lecciones/GuardarLec', [LeccionesController::class, 'GuardarLeccionesAPI'] );


//Inteligencia
$router->get('/reporte/inteligencia', [InteligenciaController::class,'index']);
$router->get('/API/reporte/inteligencia/BusInteligencia', [InteligenciaController::class, 'Buscarinteligencia'] );
$router->post('/API/reporte/inteligencia/GuardarInf', [InteligenciaController::class, 'GuardarInteligenciaAPI'] );


// validar reporte de patrulla
$router->get('/validacionR', [ValidacionRController::class,'index']);
$router->get('/API/validacionR/BusDatos', [ValidacionRController::class, 'BuscarDatosAPI'] );
$router->get('/API/validacionR/BusInformacion', [ValidacionRController::class, 'BuscarInformacionAPI'] );
$router->get('/API/validacionR/CambioSit', [ValidacionRController::class, 'CambioSituacionAPI'] );
$router->get('/API/validacionR/CambioRec', [ValidacionRController::class, 'RechazoSituacionAPI'] );



$router->get('/validacionO', [ValidacionOController::class,'index']);
$router->get('/API/validacionO/BusDatos', [ValidacionOController::class, 'BuscarDatosAPI'] );
$router->get('/API/validacionO/BusInformacion', [ValidacionOController::class, 'BuscarInformacionAPI'] );
$router->get('/API/validacionO/CambioSit', [ValidacionOController::class, 'CambioSituacionAPI'] );
$router->get('/API/validacionO/CambioRec', [ValidacionOController::class, 'RechazoSituacionAPI'] );


$router->comprobarRutas();
