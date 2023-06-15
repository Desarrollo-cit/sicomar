<?php

namespace Controllers;

use Model\Zarpes;
use Model\Asigpersonal;
use Model\Asigunidad;

use MVC\Router;
use Exception;
use Model\Operacion;

class ZarpesController
{

    public static function index(Router $router)
    {
        $busqueda =  Zarpes::fetchArray('SELECT * FROM codemar_tipos_operaciones');

        $embarcacion =  Zarpes::fetchArray('SELECT * FROM codemar_embarcaciones');

        $router->render('zarpes/index', [

            'busqueda' => $busqueda,
            'embarcacion' => $embarcacion,
        ]);
    }

    public static function buscarApi()
    {

        try {

            $sql = "SELECT ope_id, codemar_tipos_operaciones.tipo_desc as tipo, ope_fecha_zarpe, ope_fecha_atraque, ope_situacion, ope_mision, ope_ejecucion, ope_identificador, dep_desc_lg,
            ope_reutilizar, ope_distancia, ope_nacional, ope_sit
            from codemar_operaciones INNER JOIN codemar_tipos_operaciones on tipo_id = ope_tipo 
            inner join mdep on dep_llave = ope_dependencia
            where ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza 
            inner join codemar_tipos_operaciones on tipo_id=ope_tipo
            where per_catalogo = user) and ope_sit != 0 order by ope_id asc";
            $resultado =  Zarpes::fetchArray($sql);
            $i = 0;

            $data = [];
            foreach ($resultado as $key => $zarpe) {
                $identificador = $zarpe['ope_identificador'];
                $tipo_desc = $zarpe['tipo'];
                $ope_id = $zarpe['ope_id'];
                $ope_sit = $zarpe['ope_sit'];

                $unidades = static::getUnidad($ope_id);
                $personal = static::getPersonal($ope_id);

                $arrayInterno = [[
                    "contador" => $key + 1,
                    "ope_identificador" => $identificador,
                    "unidades" => $unidades['tipo'] . " " . $unidades['nombre'],
                    "personal" => $personal,
                    "tipo" => $tipo_desc,
                    "id" => $ope_id,
                    "situacion" => $ope_sit,

                ]];
                $i++;
                $data = array_merge($data, $arrayInterno);
            }
            if ($resultado) {
                echo json_encode($data);
            }
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),
                "mensaje" => "Ocurrió un error en base de datos",

                "codigo" => 4,
            ]);
        }
    }
    function getPersonal($operacion)
    {
        $sql = "SELECT trim(gra_desc_ct) || ' ' || trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre, per_catalogo as catalogo, asi_id FROM codemar_asig_personal inner join mper on  per_catalogo = asi_catalogo inner join grados on per_grado = gra_codigo where asi_sit = 1 and asi_operacion = $operacion order by per_grado, per_catalogo ";
        $resultado = Zarpes::fetchArray($sql);
        return $resultado;
    }

    function getUnidad($operacion)
    {
        $sql = "SELECT tipo_desc as tipo , emb_nombre as nombre, emb_id as id , asi_id FROM codemar_asig_unidad inner join codemar_embarcaciones on  emb_id = asi_unidad inner join codemar_tipos_embarcaciones on tipo_id = emb_tipo where asi_sit = 1 and asi_operacion = $operacion ";
        $resultado = Zarpes::fetchFirst($sql);
        return $resultado;
    }

    public static function buscarPersonasApi()
    {
        getHeadersApi();
        $val = $_GET['val_id'];

        $personal = static::getPersonal($val);
        echo json_encode($personal);
    }



////EMPIEZA LA PUTA FUNCION DE MODIFICAR/////

    public static function modificar()
    {
        // echo json_encode($_POST);
        // exit;
        getHeadersApi();
        $_POST['ope_fecha_atraque'] = str_replace('T', ' ', $_POST['ope_fecha_atraque']);
        $_POST['ope_fecha_zarpe'] = str_replace('T', ' ', $_POST['ope_fecha_zarpe']);
        $_POST['ope_dependencia'] =  $_POST['ope_dependencia'];


        try {

            $zarp = Zarpes::find($_POST['codigo']);
            // $zarp->ope_id = $_POST['ope_id'];
            $zarp->ope_tipo = $_POST['ope_tipo'];
            $zarp->ope_fecha_zarpe = $_POST['ope_fecha_zarpe'];
            $zarp->ope_fecha_atraque = $_POST['ope_fecha_atraque'];
            $zarp->setSituacion($_POST['ope_situacion']);
            $zarp->setMision($_POST['ope_mision']);
            $zarp->setEjecucion($_POST['ope_ejecucion']);
            $zarp->ope_identificador = $_POST['ope_identificador'];
            $zarp->ope_dependencia = $_POST['ope_dependencia'];
            $zarp->ope_reutilizar = $_POST['ope_reutilizar'];
            

            $resultado =  $zarp->actualizar();

            if ($resultado['resultado'] == 1) {

                $id = $_POST['codigo'];
                $id_asi = Asigunidad::fetchArray("SELECT asi_id from codemar_asig_unidad where asi_operacion = $id ");

                $ids = $id_asi[0]['asi_id'];
                $asig = Asigunidad::find($ids);
                $asig->asi_unidad = $_POST['asi_unidad'];

                $resultado =  $asig->actualizar();
            }

            if ($resultado['resultado'] == 1) {

            $datos = Asigpersonal::fetchArray("SELECT * FROM codemar_asig_personal where asi_operacion = $id ");

            if ($datos) {

                foreach ($datos as $key => $value) {
                    $cambio = new Asigpersonal([
                        'asi_id' => $value['asi_id'],
                        'asi_operacion' => $value['asi_operacion'],
                        'asi_catalogo' => $value['asi_catalogo'],
                        'asi_sit' =>  "0"
                    ]);
                    $cambiar = $cambio->guardar();
            }
        }

        $cantidades_array = explode(",", $_POST['catalogos']);
        $cuenta= count($cantidades_array);
 
        for ($i = 0; $i < $cuenta; $i++) {




            $consumos = new Asigpersonal([

           
                'asi_operacion' => $_POST['codigo'],
                'asi_catalogo' => $_POST['catalogo'.$i],
       


            ]);


            $guardado = $consumos->guardar();
        }


    }
       
            if (!array_search(0, $guardado)) {
                echo json_encode([
                    "mensaje" => "El registro se modificó.",
                    "codigo" => 1,
                ]);
            } else {
                echo json_encode([
                    "mensaje" => "Ocurrió  un error.",
                    "codigo" => 0,
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),
                "mensaje" => "Ocurrió  un error en base de datos.",

                "codigo" => 4,
            ]);
        }
    }

       //////TERMINA MODIFICAR////////  


    public static function verRegistroApi()
    {
        getHeadersApi();
        $val = $_GET['val_id'];

        $personal = static::getPersonal($val);
        echo json_encode($val);
        exit;
    }





    

    public static function imprimirRegistroApi()
    {
        getHeadersApi();
        $val = $_GET['val_id'];

        $personal = static::getPersonal($val);
        echo json_encode($personal);
    }


    public static function eliminar()
    {

        getHeadersApi();
        $val = $_GET['val_id'];


        try {
            $datos = Operacion::fetchArray("SELECT * FROM codemar_operaciones WHERE ope_id = $val");

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

                    if ($cambiar['resultado'] == 1) {

                        $datos = Asigunidad::fetchArray("SELECT * FROM codemar_asig_unidad where asi_operacion = $val ");
            
                        if ($datos) {
            
                            foreach ($datos as $key => $value) {
                                $cambio = new Asigunidad([
                                    'asi_id' => $value['asi_id'],
                                    'asi_operacion' => $value['asi_operacion'],
                                    'asi_unidad' => $value['asi_unidad'],
                                    'asi_sit' =>  "0"
                                ]);
                                $cambiar = $cambio->guardar();
                        }
                    }      

                }

                if ($cambiar['resultado'] == 1) {

                    $datos = Asigpersonal::fetchArray("SELECT * FROM codemar_asig_personal where asi_operacion = $val ");
        
                    if ($datos) {
        
                        foreach ($datos as $key => $value) {
                            $cambio = new Asigpersonal([
                                'asi_id' => $value['asi_id'],
                                'asi_operacion' => $value['asi_operacion'],
                                'asi_catalogo' => $value['asi_catalogo'],
                                'asi_sit' =>  "0"
                            ]);
                            $cambiar = $cambio->guardar();
                    }
                }
            }
        }
    }

            if ($cambiar) {
                echo json_encode([
                    "mensaje" => "Eliminado Correctamente",
                    "codigo" => 1,
                ]);
            } else {
                echo json_encode([
                    "mensaje" => "Error al Eliminar",
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



    public static function colocar()
    {
        getHeadersApi();

        try {
            $id = $_GET['id'];

            $operacion = Zarpes::fetchArray("SELECT * from codemar_operaciones INNER JOIN codemar_tipos_operaciones on tipo_id = ope_tipo  where ope_id = $id");

            $unidad = Asigunidad::fetchArray(("SELECT tipo_desc as tipo , emb_nombre as nombre, emb_id as id , asi_id FROM codemar_asig_unidad inner join codemar_embarcaciones on  emb_id = asi_unidad inner join codemar_tipos_embarcaciones on tipo_id = emb_tipo where asi_sit = 1 and asi_operacion = $id"));

            $personal = Asigpersonal::fetchArray("SELECT trim(gra_desc_ct) || ' ' || trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre, per_catalogo as catalogo, asi_id FROM codemar_asig_personal inner join mper on  per_catalogo = asi_catalogo inner join grados on per_grado = gra_codigo where asi_sit = 1 and asi_operacion = $id order by per_grado, per_catalogo ");

            $operacion[0]['ope_situacion'] = str_replace("\\\"", "'", htmlspecialchars_decode($operacion[0]['ope_situacion']));
            $operacion[0]['ope_ejecucion'] = str_replace("\\\"", "'", htmlspecialchars_decode($operacion[0]['ope_ejecucion']));
            $operacion[0]['ope_mision'] = str_replace("\\\"", "'", htmlspecialchars_decode($operacion[0]['ope_mision']));

            echo json_encode([

                'operacion' => $operacion,
                'unidad' => $unidad,
                'personal' => $personal

            ]);
            exit;


        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),
                "mensaje" => "Ocurrió  un error en base de datos.",

                "codigo" => 4,
            ]);
        }
    }
}
