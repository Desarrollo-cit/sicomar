<?php

namespace Controllers;

use Model\Reporte;
use MVC\Router;
use Exception;
use Model\Operacion;

class ReporteController{

    public static function index(Router $router){
        $router->render('Reporte/index',[]);
    }


    
    public static function BuscarDatosAPI(){


        try {
            getHeadersApi();
            $datos = Reporte::fetchArray("
            SELECT ope_id, ope_identificador, ope_sit, ope_fecha_zarpe
            from codemar_asig_personal inner join codemar_operaciones on asi_operacion = ope_id
             where asi_catalogo = user and asi_sit = 1 and ope_sit = 1 and ope_nacional = 'N'");
            echo json_encode($datos);     
            
            
        } catch (Exception $e) {
            echo json_encode(["error"=>$e->getMessage()]);
        }
       
    }

public static function CambioSituacionAPI(){

    getHeadersApi();
        $id= $_GET["id"];
     
try{
        $datos = Operacion::fetchArray("SELECT * FROM codemar_operaciones WHERE ope_id = $id");
    
        if ($datos) {
           

            foreach ($datos as $key => $value) {
                $cambio = new Operacion([
                    'ope_id' => $value['ope_id'],
                    'ope_tipo' => $value['ope_tipo'],
                    'ope_fecha_zarpe' => $value['ope_fecha_zarpe'],
                    'ope_fecha_atraque' => $value['ope_fecha_atraque'],
                    'ope_situacion' => $value['ope_situacion'],
                    'ope_mision' => $value['ope_mision'],
                    'ope_ejecucion' => $value['ope_ejecucion'],
                    'ope_identificador' => $value['ope_identificador'],
                    'ope_dependencia' => $value['ope_dependencia'],
                    'ope_reutilizar' => $value['ope_reutilizar'],
                    'ope_distancia' => $value['ope_distancia'],
                    'ope_nacional' => $value['ope_nacional'],
                    'ope_sit' =>  "0"
                ]);
                $cambiar = $cambio->guardar();
            }

            
        }

        if ($cambiar) {
            echo json_encode([
                "mensaje" => "Operacion completa",
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

