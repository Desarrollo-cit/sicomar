<?php

namespace Controllers;

use Model\Derrota;
use Model\Consumos;


use MVC\Router;
use Exception;
use Model\Comunicaciones;

class ComunicacionesController
{

    public static function index(Router $router)
    {
        $ope_id = $_GET['id'];
        $ope_identificador = $_GET['identificador'];
        $ope_fecha_zarpe = $_GET['fecha_zarpe'];

        $decoded_id = base64_decode($ope_id);
        $decoded_identificador = base64_decode($ope_identificador);
        $decoded_fecha_zarpe = base64_decode($ope_fecha_zarpe);

        $router->render('Reporte/comunicaciones', [
            'decoded_id'            =>   $decoded_id,
            'decoded_identificador' =>   $decoded_identificador,
            'decoded_fecha_zarpe'   =>   $decoded_fecha_zarpe
        ]);
    }


    public static function BuscarComun()
    {
        getHeadersApi();
        $valor = $_GET['id'];
        // echo json_encode($valor);
        // exit;
        try {

            $datos = Derrota::fetchArray("SELECT * FROM codemar_comunicaciones where com_operacion = $valor and com_situacion = 1 ");

            if ($datos) {

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

            if ($datos) {

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

            if ($datos) {

                echo json_encode($datos);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }




    public static function GuardarComunicacionesAPI()
    {

        getHeadersApi();

        // echo json_encode($_POST);
        // exit;

        try {
            $medios = $_POST['medios'];
            $calidades = $_POST['calidades'];
            $receptores = $_POST['receptores'];
            $observaciones = $_POST['observaciones'];
            $id_ope = $_POST['id_ope'];

            // descomponer arrays
            $medios_array = explode(",", $medios);
            $calidades_array = explode(",", $calidades);
            $receptores_array = explode(",", $receptores);
            $observaciones_array = explode(",", $observaciones);

            $num_elementos = count($medios_array);

            $datos = Comunicaciones::fetchArray("SELECT * from codemar_comunicaciones where com_situacion = 1 and com_operacion = $id_ope");
            if ($datos) {
                foreach ($datos as $key => $value) {
                    $cambio = new Comunicaciones([
                        'com_id' => $value['com_id'],
                        'com_operacion' => $value['com_operacion'],
                        'com_medio' => $value['com_medio'],
                        'com_receptor' => $value['com_receptor'],
                        'com_calidad' => $value['com_calidad'],
                        'com_obserevacion' => $value['com_observacion'],
                        'com_situacion' => "0"
                    ]);
                    $cambiar = $cambio->guardar();
                }
            }

            for ($i = 0; $i < $num_elementos; $i++) {
                $valor_medio = $medios_array[$i];
                $valor_calidad = $calidades_array[$i];
                $valor_receptor = $receptores_array[$i];
                $valor_observacion = $observaciones_array[$i];

                $consumos = new Comunicaciones([
                    'com_operacion' => $id_ope,
                    'com_insumo' => $valor_medio,
                    'com_cantidad' => $valor_calidad,
                    'com_medio' => $valor_medio,
                    'com_calidad' => $valor_calidad,
                    'com_receptor' => $valor_receptor,
                    'com_observacion' => $valor_observacion,
                    'com_situacion' => "1"
                ]);

                $guardado = $consumos->guardar();
            }

            if ($guardado) {
                echo json_encode([
                    "mensaje" => "Comunicaciones guardadas",
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
