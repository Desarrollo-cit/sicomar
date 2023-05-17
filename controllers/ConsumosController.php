<?php

namespace Controllers;

use Model\Derrota;
use Model\Trabajo_motores;

use MVC\Router;
use Exception;


class ConsumosController
{

    public static function index(Router $router)
    {
        $router->render('Reporte/consumos', []);
    }


    public static function BuscarInsumos()
    {
        getHeadersApi();
        
     
        $valor = $_GET['id'];
        try {

       

            $datos = Derrota::fetchArray("SELECT * FROM codemar_insumos_operaciones INNER JOIN codemar_unidades_medida on insumo_unidad = uni_id where insumo_situacion = 1 
            ");
        

         

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public static function BuscarConsumos()
    {
        getHeadersApi();
        $valor = $_GET['id'];
     

        try {

            $datos = Derrota::fetchArray("SELECT con_insumo, sum(con_cantidad) as con_cantidad, insumo_desc as insumo, uni_desc as unidad 
            FROM codemar_consumos inner join codemar_insumos_operaciones on con_insumo = insumo_id 
            inner join codemar_unidades_medida on insumo_unidad = uni_id where con_operacion = $valor and con_situacion = 1 group by con_insumo, insumo, unidad
            ");
        

         

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }




    public static function Buscartrabajo()
    {
        getHeadersApi();

        try {
            $operacion = $_GET['id'];
            $motor = $_GET['motor'];

            $datos = Derrota::fetchArray(" SELECT * FROM codemar_trabajo_motores where tra_operacion = $operacion and tra_motor = $motor and tra_situacion = 1");
           
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public static function GuardarTrabajoAPI()
    {

        getHeadersApi();

        try {
            $fallas = $_POST['fallas'];
            $horas = $_POST['horas'];
            $id_ope = $_POST['id_ope'];
            $ids = $_POST['ids'];
            $observaciones = $_POST['observaciones'];
            $rpm = $_POST['rpm'];
            // descomponer arrays    
            $fallas_array = explode(",", $fallas);
            $horas_array = explode(",", $horas);
            $ids_array = explode(",", $ids);
            $observaciones_array = explode(",", $observaciones);
            $rpm_array = explode(",", $rpm);
                
            $num_elementos = count($ids_array);
       
            $datos = Trabajo_motores::fetchArray("SELECT * FROM codemar_trabajo_motores WHERE tra_operacion = $id_ope");        
            if ($datos) {
               
                foreach ($datos as $key => $value) {
                    $cambio = new Trabajo_motores([
                        'tra_id' => $value['tra_id'],
                        'tra_operacion' => $value['tra_operacion'],
                        'tra_motor' => $value['tra_motor'],
                        'tra_horas' => $value['tra_horas'],
                        'tra_rpm' => $value['tra_rpm'],
                        'tra_fallas' => $value['tra_fallas'],
                        'tra_observacion' => $value['tra_observacion'],
                        'tra_situacion' =>  "0"
                    ]);
                    $cambiar = $cambio->guardar();
                }
            }



            for ($i = 0; $i < $num_elementos; $i++) {

                $valor_fallas = $fallas_array[$i];
                $valor_horas = $horas_array[$i];
                $valor_ids = $ids_array[$i];
                $valor_observaciones = $observaciones_array[$i];
                $valor_rpm = $rpm_array[$i];
            

                $trabajo_motor = new Trabajo_motores([
                    'tra_fallas' => $valor_fallas,
                    'tra_horas' => $valor_horas,
                    'tra_motor' => $valor_ids,
                    'tra_observacion' => $valor_observaciones,
                    'tra_rpm' => $valor_rpm,
                    'tra_operacion' => $id_ope,
                    'tra_situacion' =>  "1"
                ]);
            

                $guardado = $trabajo_motor->guardar();
            
        
              
            }
            if ($guardado) {
                echo json_encode([   
                    "mensaje" => "Trabajo de los motores guardado",
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
