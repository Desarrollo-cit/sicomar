<?php

namespace Controllers;

use Model\Derrota;
use Model\Novedades;


use MVC\Router;
use Exception;
use Model\Comunicaciones;

class NovedadesController
{

    public static function index(Router $router)
    {
        $ope_id = $_GET['id'];
        $ope_identificador = $_GET['identificador'];
        $ope_fecha_zarpe = $_GET['fecha_zarpe'];

        $decoded_id = base64_decode($ope_id);
        $decoded_identificador = base64_decode($ope_identificador);
        $decoded_fecha_zarpe = base64_decode($ope_fecha_zarpe);

        $router->render('Reporte/novedades', [
            'decoded_id'            =>   $decoded_id,
            'decoded_identificador' =>   $decoded_identificador,
            'decoded_fecha_zarpe'   =>   $decoded_fecha_zarpe
        ]);
    }


    public static function BuscarNovedades()
    {
        getHeadersApi();
            $valor = $_GET['id'];
            // echo json_encode($valor);
            // exit;
        try {

        $datos = Derrota::fetchArray("SELECT * FROM codemar_novedades where nov_operacion = $valor and nov_situacion = 1 order by nov_fechahora asc");

            if($datos ){

                echo json_encode($datos);

            }

        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }


    
    public static function BuscarMedios()
    {
        getHeadersApi();
     
        try {

        $datos = Derrota::fetchArray("SELECT * FROM codemar_medios_comunicacion where medio_situacion = 1  ");

            if($datos ){

                echo json_encode($datos);

            }

        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }


    
    public static function BuscarReceptores()
    {
        getHeadersApi();
     
        try {

        $datos = Derrota::fetchArray("SELECT * FROM codemar_receptor_comunicacion where rec_situacion = 1 ");

            if($datos ){

                echo json_encode($datos);

            }

        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }




    public static function GuardarNovedadesAPI()
    {

        getHeadersApi();

        // echo json_encode($_POST);
        // exit;

        try {
            $fechas = $_POST['fechas'];
            $novedades = $_POST['novedades'];
            $id_ope = $_POST['id_ope'];
    
            // descomponer arrays
            $novedades_array = explode(",", $novedades);
            $fechas_array = explode(",", str_replace('T', ' ', $fechas));

       
            $num_elementos = count($novedades_array);

            $datos = Novedades::fetchArray("SELECT * FROM codemar_novedades where nov_operacion = 38 and nov_situacion = 1 and nov_operacion =  $id_ope order by nov_fechahora asc 
           ");
            if ($datos) {
                foreach ($datos as $key => $value) {
                    $cambio = new Novedades([
                        'nov_id' => $value['nov_id'],
                        'nov_operacion' => $value['nov_operacion'],
                        'nov_fechahora' => $value['nov_fechahora'],
                        'nov_novedad' => $value['nov_novedad'],
                        'nov_situacion' => "0"
                    ]);
                    $cambiar = $cambio->guardar();
                }
            }

for ($i = 0; $i < $num_elementos; $i++) {
            $valor_novedad = $novedades_array[$i];
            $valor_fechas = $fechas_array[$i];
      

            $consumos = new Novedades([
                'nov_operacion' => $id_ope,
                'nov_novedad' => $valor_novedad,
                'nov_fechahora' => $valor_fechas,
                'com_situacion' => "1"
            ]);

            $guardado = $consumos->guardar();
        }

        if ($guardado) {
            echo json_encode([
                "mensaje" => "Novedades guardadadas",
                "codigo" => 1,
            ]);
        } else {
            echo json_encode([
                "mensaje" => "Error, Verifique sus datos",
                "codigo" => 0,
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
