<?php

namespace Controllers;
use Model\Derrota;

use MVC\Router;
use Exception;
use Model\Ingreso;

class DerrotaController{

    public static function index(Router $router){
        $router->render('Reporte/derrota',[]);
    }


public static function BuscarDerrotas(){
    getHeadersApi();
$valor = $_GET['id'];
try {
    getHeadersApi();
    $datos = Derrota::fetchArray("SELECT * FROM codemar_derrota  WHERE der_ope = 38 AND der_situacion = 1");
 
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




try {
  
    $der_coodigo = $_POST['id'];
    //$der_coodigos = $_POST['puntos'];
    $der_ope =$_POST['puntos'];
    $der_id =$_POST['der_id'];
$cuantosId = count($der_id);

$val_id = 0;
for ($i=0; $i < $cuantosId; $i++) { 
    # code...
foreach ($der_ope as $val) {
    $datos = explode(',', $val);
    $latitud = $datos[0];
    $longitud = $datos[1];
    $fecha = $datos[2];
// echo json_encode($der_id[$i]);
// exit;
    
    $Ingreso = new Derrota([
        'der_id' => $der_id[$i],
        'der_ope' =>        $der_coodigo,
        'der_latitud' =>    $latitud,
        'der_longitud' =>   $longitud,
        'der_fecha' =>      $fecha,
        'der_situacion' =>  "1"

    ]);
    $guardado = $Ingreso->guardar(); 
    

}
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



} catch (Exception $e) {
    echo json_encode([
        "detalle" => $e->getMessage(),
        "mensaje" => "Ocurrió un error en la base de datos.",
        "codigo" => 4,
    ]);
}



}


}

