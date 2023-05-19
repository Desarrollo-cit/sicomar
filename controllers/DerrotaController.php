<?php

namespace Controllers;
use Model\Derrota;

use MVC\Router;
use Exception;
use Model\Ingreso;

class DerrotaController{

    public static function index(Router $router){
        $ope_id = $_GET['id'];
        $ope_identificador = $_GET['identificador'];
        $ope_fecha_zarpe = $_GET['fecha_zarpe'];

        $decoded_id = base64_decode($ope_id);
        $decoded_identificador = base64_decode($ope_identificador);
        $decoded_fecha_zarpe = base64_decode($ope_fecha_zarpe);

        $router->render('Reporte/derrota',[
            'decoded_id'            =>   $decoded_id,
            'decoded_identificador' =>   $decoded_identificador,
            'decoded_fecha_zarpe'   =>   $decoded_fecha_zarpe
        ]);
    }


public static function BuscarDerrotas(){
    getHeadersApi();
$valor = $_GET['id'];
try {
    getHeadersApi();
    $datos = Derrota::fetchArray("SELECT * FROM codemar_derrota  WHERE der_ope = $valor AND der_situacion = 1");
 
    $data = [];
    
if($datos != null){

    foreach ($datos as $vuelta) {
        $puntos [] = [
            $vuelta['der_latitud'],
      $vuelta['der_longitud'],
    $vuelta['der_fecha'],
    
];

$data[]  =[
    "puntosDerrota" => $puntos,
    "id" => $vuelta['der_id'],
    'codigo' => 1
    
    
];

}
}else{

    $data  =[
       
        'codigo' => 2
        
        
    ];
}
echo json_encode($data);    


} catch (Exception $e) {
    echo json_encode(["error"=>$e->getMessage()]);
}


}

public static function GuardarAPI(){

    getHeadersApi();

// echo json_encode($_POST);
// exit;


try {


    $der_coodigo = $_POST['id'];
    //$der_coodigos = $_POST['puntos'];
    $der_ope =$_POST['puntos'];


    $datos = Derrota::fetchArray("SELECT * FROM codemar_derrota  WHERE der_ope = $der_coodigo AND der_situacion = 1");
if ($datos){
    foreach ($datos as $key => $value) {

        $cambio = new Derrota([

            'der_id' => $value['der_id'],
            'der_ope' => $value['der_ope'],
            'der_latitud' => $value['der_latitud'],
            'der_longitud' => $value['der_longitud'],
            'der_fecha' => $value['der_fecha'],
            'der_situacion' =>  "0"
        
        ]);
        $cambiar = $cambio->guardar();
    }


}


foreach ($der_ope as $val) {
    $datos = explode(',', $val);
    $latitud = $datos[0];
    $longitud = $datos[1];
    $fecha = $datos[2];

    $Ingreso = new Derrota([

        'der_ope' =>        $der_coodigo,
        'der_latitud' =>    $latitud,
        'der_longitud' =>   $longitud,
        'der_fecha' =>      $fecha,
        'der_situacion' =>  "1"

    ]);
    $guardado = $Ingreso->guardar(); 
    

}

if ($guardado) {

    echo json_encode([

        "codigo" => 7,
    ]);

} else {
    echo json_encode([

        "codigo" => 2,
    ]);
}
  
//     $der_coodigo = $_POST['id'];
//     //$der_coodigos = $_POST['puntos'];
//     $der_ope =$_POST['puntos'];
//     $der_id =$_POST['der_id'];
// $cuantosId = count($der_id);

// $val_id = 0;
// for ($i=0; $i < $cuantosId; $i++) { 
//     # code...
// foreach ($der_ope as $val) {
//     $datos = explode(',', $val);
//     $latitud = $datos[0];
//     $longitud = $datos[1];
//     $fecha = $datos[2];
// // echo json_encode($der_id[$i]);
// // exit;
    
//     $Ingreso = new Derrota([
//         'der_id' => $der_id[$i],
//         'der_ope' =>        $der_coodigo,
//         'der_latitud' =>    $latitud,
//         'der_longitud' =>   $longitud,
//         'der_fecha' =>      $fecha,
//         'der_situacion' =>  "1"

//     ]);
//     $guardado = $Ingreso->guardar(); 
    

// }
// }
// if ($guardado) {

//     echo json_encode([

//         "codigo" => 7,
//     ]);

// } else {
//     echo json_encode([

//         "codigo" => 2,
//     ]);
// }



} catch (Exception $e) {
    echo json_encode([
        "detalle" => $e->getMessage(),
        "mensaje" => "Ocurrió un error en la base de datos.",
        "codigo" => 4,
    ]);
}



}


}


