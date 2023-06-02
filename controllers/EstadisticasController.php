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
    public static function operacionesTopApi(){
        getHeadersApi();
        $inicio = str_replace('T',' ', $_GET['inicio']);
        $fin = str_replace('T',' ', $_GET['fin']);
        try {
            $sql = "SELECT first 5 distinct asi_catalogo, trim(gra_desc_ct) || ' ' || trim (per_ape1) || ' ' || trim (per_ape2[1]) || '.' as nombre , count(*) as cantidad FROM codemar_asig_personal inner join mper on asi_catalogo = per_catalogo  inner join grados on per_grado = gra_codigo inner join codemar_operaciones on ope_id = asi_operacion where asi_sit = 1 ";
            if($inicio != ''){
                $sql.= " and ope_fecha_zarpe >= '$inicio' "; 
            }
            if($fin != ''){
                $sql.= " and ope_fecha_zarpe <= '$fin' "; 
            }

            $sql.= " group by asi_catalogo, nombre order by cantidad desc ";
            $top = ActiveRecord::fetchArray($sql);
            $data = [];
            $labels = [];
            $cantidades = [];
        
            foreach ($top as $key => $puesto ) {
                
    
                $labels[]= $puesto['nombre'];
                $cantidades[]= $puesto['cantidad'];
            
            }
            $data = [
                'labels' => $labels,
                'cantidades' => $cantidades
            ];
    
        
            echo json_encode($data);
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

    public static function operacionesMensualesApi(){
        getHeadersApi();
        $inicio = $_GET['inicio'];
        $year = $inicio != '' ? date('Y',  strtotime($inicio)) : 'year(current)';
        try {
            $sql = "SELECT
            distinct dep_desc_ct,
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 1 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as enero,
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 2 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as febrero, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 3 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as marzo, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 4 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as abril, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 5 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as mayo, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 6 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as junio, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 7 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as julio, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 8 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as agosto, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 9 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as septiembre, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 10 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as octubre, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 11 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as noviembre, 
            (
                select count (*) from codemar_operaciones b where month(b.ope_fecha_zarpe) = 12 and a.ope_dependencia = b.ope_dependencia and b.ope_sit = 4 and b.ope_nacional = 'N' and year(b.ope_fecha_zarpe) = $year
            ) as diciembre
            
            
            FROM CODEMAR_OPERACIONES a inner join mdep on a.ope_dependencia = dep_llave order by dep_desc_ct";
            $operaciones = ActiveRecord::fetchArray($sql);
            $data = [];
            foreach ($operaciones as $key => $operacion) {
                $data['labels'][] = trim($operacion['dep_desc_ct']);
                $data['cantidades'][] = [
                    $operacion['enero'],
                    $operacion['febrero'],
                    $operacion['marzo'],
                    $operacion['abril'],
                    $operacion['mayo'],
                    $operacion['junio'],
                    $operacion['julio'],
                    $operacion['agosto'],
                    $operacion['septiembre'],
                    $operacion['octubre'],
                    $operacion['noviembre'],
                    $operacion['diciembre'],
                ];
    
            }
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "Ocurrió un error en base de datos",
    
                "codigo" => 4,
            ]);
        }
    }


}