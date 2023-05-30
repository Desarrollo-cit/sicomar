<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class EstadisticasController {
    public static function index(Router $router){
        $router->render('estadisticas/index', []);
    }

    public static function operacionesMapaApi(){
        getHeadersApi();
        $inicio = str_replace('T',' ', $_GET['inicio']);
        $fin = str_replace('T',' ', $_GET['fin']);
        try {
            $sql = "SELECT round(der_latitud, 2) as lat , round(der_longitud,2) as lng, count (*) as count from codemar_operaciones inner join codemar_derrota on der_ope = ope_id where ope_sit = 1 and der_situacion = 1 ";
            if($inicio != ''){
                $sql.= " and ope_fecha_zarpe >= '$inicio' "; 
            }
            if($fin != ''){
                $sql.= " and ope_fecha_zarpe <= '$fin' "; 
            }

            $sql.= " group by lat, lng ";
            $operaciones = ActiveRecord::fetchArray($sql);
            echo json_encode($operaciones);
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "Ocurrió un error en base de datos",
    
                "codigo" => 4,
            ]);
        }
    }
    public static function operacionesConsumosApi(){
        getHeadersApi();

        $inicio = str_replace('T',' ', $_GET['inicio']);
        $fin = str_replace('T',' ', $_GET['fin']);
        try {
            $sql = "SELECT insumo_color as color, insumo_desc as nombre, sum(con_cantidad) as cantidad FROM codemar_consumos inner join codemar_operaciones on con_operacion = ope_id inner join codemar_insumos_operaciones on insumo_id = con_insumo where con_situacion = 1 and ope_sit = 4 "; 
            
            if($inicio != ''){
                $sql.= " and ope_fecha_zarpe >= '$inicio' "; 
            }
            if($fin != ''){
                $sql.= " and ope_fecha_zarpe <= '$fin' "; 
            }
            $sql.= " group by nombre, color ";
            $consumos = ActiveRecord::fetchArray($sql);
            echo json_encode($consumos);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "Ocurrió un error en base de datos",
    
                "codigo" => 4,
            ]);
        }
    }

    public static function operacionesComandoApi(){
        getHeadersApi();

        $inicio = str_replace('T',' ', $_GET['inicio']);
        $fin = str_replace('T',' ', $_GET['fin']);
        try {
            $tipos = ActiveRecord::fetchArray("SELECT * from codemar_tipos_operaciones where tipo_situacion = 1 AND tipo_id != 10");
            $dependencias = ActiveRecord::fetchArray("SELECT distinct ope_dependencia, dep_desc_ct from codemar_operaciones inner join mdep on ope_dependencia = dep_llave where ope_sit = 4 order by dep_desc_ct");
            $data = [];
            $labels = [];
            $cantidades = [];
            $i = 0;
            foreach ($tipos as $key => $tipo ) {
                
                $tipo_id = $tipo['tipo_id'];
                
                $labels[]= $tipo['tipo_desc'];
                foreach ($dependencias as $dependencia) {
                    $dep_llave =  $dependencia['ope_dependencia'];
                    $nombreDep = trim($dependencia['dep_desc_ct']);
                    $sql = "SELECT count(*) as cantidad from codemar_operaciones where ope_sit = 4 and ope_tipo = $tipo_id and ope_nacional = 'N' and ope_dependencia = $dep_llave";
                    if($inicio != ''){
                        $sql.= " and ope_fecha_zarpe >= '$inicio' "; 
                    }
                    if($fin != ''){
                        $sql.= " and ope_fecha_zarpe <= '$fin' "; 
                    }
                    $operaciones = ActiveRecord::fetchArray($sql);
                    $cantidades[$nombreDep][]= (int) $operaciones[0]['cantidad'];
                }
                $i++;
            }
            $data = [
                'labels' => $labels,
                'cantidades' => $cantidades
            ];
    
        
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "$sql",
    
                "codigo" => 4,
            ]);
        }
    }


}