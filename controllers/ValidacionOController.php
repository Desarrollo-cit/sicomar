<?php

namespace Controllers;

use Model\Derrota;
use Model\Operaciones;

use MVC\Router;
use Exception;
use Model\Ingreso;

class ValidacionOController
{

    public static function index(Router $router)
    {


        $router->render('validacionO/index', []);
    }

    //hace el data table
    public static function BuscarDatosAPI()
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * from codemar_operaciones INNER JOIN codemar_tipos_operaciones on tipo_id = ope_tipo  
                where ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER) and ope_sit = 3 order by ope_id asc");

            if ($datos) {
                echo json_encode($datos);
            } else {
                echo json_encode([
                    "codigo" => 1,
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public static function DistanciaTotalAPI()
    {
        getHeadersApi();
        $id = $_GET['id'];
        // echo json_encode($id);
        // exit;

        try {

            $datos = Derrota::fetchArray("SELECT der_latitud, der_longitud, der_fecha from codemar_derrota where der_ope = $id and der_situacion != 0");



            if ($datos != null) {

                $data = [];
                foreach ($datos as $punto) {
                    $data[] = [
                        "latitud" => $punto['der_latitud'],
                        "longitud" => $punto['der_longitud'],
                    ];
                }

                echo json_encode($data);
            } else {
                echo json_encode([]);
            }
        } catch (Exception $e) {
            return  [];
        }
    }




    // consulta puntos


    public static function BuscarPuntos($operacion)
    {
        getHeadersApi();

        try {

            $datos = Derrota::fetchArray("SELECT * FROM codemar_derrota  WHERE der_ope = $operacion AND der_situacion = 1");



            if ($datos != null) {
                $puntos = [];
                foreach ($datos as $vuelta) {
                    $puntos[] = [
                        'latitud' =>   $vuelta['der_latitud'],
                        'longitud' =>  $vuelta['der_longitud'],
                        'fecha' =>  $vuelta['der_fecha'],
                    ];
                }
            } else {
                return  [];
            }
            return ($puntos);
        } catch (Exception $e) {
            return  [];
        }
    }

    //buscar personal 

    public static function BuscarPersonal($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray(" SELECT trim(gra_desc_ct) || ' ' || trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre, per_catalogo as catalogo, asi_id FROM codemar_asig_personal inner join mper on  per_catalogo = asi_catalogo inner join grados on per_grado = gra_codigo where asi_sit = 1 and asi_operacion = $operacion order by per_grado, per_catalogo 
    ");

            if ($datos != null) {
                $personal = [];
                foreach ($datos as $vuelta) {
                    $personal[] = [
                        'nombre' =>   $vuelta['nombre'],
                        'catalogo' =>  $vuelta['catalogo'],

                    ];
                }
            } else {
                return  [];
            }
            return ($personal);
        } catch (Exception $e) {
            return  [];
        }
    }


    // buscar unidades
    public static function BuscarUnidad($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray(" SELECT tipo_desc as tipo , emb_nombre as nombre, emb_id as id , asi_id FROM codemar_asig_unidad inner join codemar_embarcaciones on  emb_id = asi_unidad inner join codemar_tipos_embarcaciones on tipo_id = emb_tipo where asi_sit = 1 and asi_operacion = $operacion ");



            if ($datos != null) {
                $unidad = [];
                foreach ($datos as $vuelta) {
                    $unidad[] = [
                        'tipo' =>   $vuelta['tipo'],
                        'nombre' =>  $vuelta['nombre'],

                    ];
                }
            } else {
                return  [];
            }
            return ($unidad);
        } catch (Exception $e) {
            return  [];
        }
    }

    //buscar trabajo motores
    public static function BuscarTrabajoMotores($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_trabajo_motores INNER JOIN codemar_motores on mot_id = tra_motor where tra_operacion = $operacion and tra_situacion = 1 ");



            if ($datos != null) {
                $motores = [];
                foreach ($datos as $vuelta) {
                    $motores[] = [
                        'serie' => $vuelta['mot_serie'],
                        'horas' => $vuelta['tra_horas'],
                        'rpm' => $vuelta['tra_rpm'],
                        'fallas' => $vuelta['tra_fallas'],
                        'observaciones' => $vuelta['tra_observacion'],
                    ];
                }
            } else {
                return  [];
            }
            return ($motores);
        } catch (Exception $e) {
            return  [];
        }
    }


    // buscar comunicaciones totales 
    public static function BuscarComunicaciones($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_comunicaciones inner join codemar_medios_comunicacion on com_medio = medio_id inner join codemar_receptor_comunicacion on com_receptor = rec_id where com_operacion = $operacion and com_situacion = 1");



            if ($datos != null) {
                $comunicaciones = [];
                foreach ($datos as $vuelta) {
                    $comunicaciones[] = [
                        'medio' => $vuelta['medio_desc'],
                        'receptor' => $vuelta['rec_desc'],
                        'calidad' => $vuelta['com_calidad'],
                        'observaciones' => $vuelta['com_observacion'],
                    ];
                }
            } else {
                return  [];
            }
            return ($comunicaciones);
        } catch (Exception $e) {
            return  [];
        }
    }


    //buscar novedades

    public static function BuscarNovedades($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_novedades where nov_operacion = $operacion and nov_situacion = 1 order by nov_fechahora asc");



            if ($datos != null) {
                $novedad = [];
                foreach ($datos as $vuelta) {
                    $novedad[] = [
                        'fecha' => strtoupper(strftime('%d%b%G', strtotime($vuelta['nov_fechahora']))),
                        'hora' => date('Hi', strtotime($vuelta['nov_fechahora'])),
                        'novedad' => $vuelta['nov_novedad'],
                    ];
                }
            } else {
                return  [];
            }
            return ($novedad);
        } catch (Exception $e) {
            return  [];
        }
    }


    //buscar consumos 

    public static function BuscarConsumos($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT con_insumo, sum(con_cantidad) as con_cantidad, insumo_desc as insumo, uni_desc as unidad FROM codemar_consumos inner join codemar_insumos_operaciones on con_insumo = insumo_id inner join codemar_unidades_medida on insumo_unidad = uni_id where con_operacion = $operacion and con_situacion = 1 group by con_insumo, insumo, unidad");



            if ($datos != null) {
                $consumos = [];
                foreach ($datos as $vuelta) {
                    $consumos[] = [
                        'insumo' => $vuelta['insumo'],
                        'cantidad' => $vuelta['con_cantidad'],
                        'unidad' => $vuelta['unidad'],

                    ];
                }
            } else {
                return  [];
            }
            return ($consumos);
        } catch (Exception $e) {
            return  [];
        }
    }

    // recomendaciones 

    public static function BuscarRecomendaciones($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_recomendaciones where rec_operacion = $operacion and rec_situacion = 1");



            if ($datos != null) {
                $recomendaciones = [];
                foreach ($datos as $vuelta) {
                    $recomendaciones[] = [
                        'recomendacion' => $vuelta['rec_recomendacion'],
                    ];
                }
            } else {
                return  [];
            }
            return ($recomendaciones);
        } catch (Exception $e) {
            return  [];
        }
    }

    //inteligencia

    public static function BuscarInteligencia($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_informacion where info_operacion = $operacion and info_situacion = 1");



            if ($datos != null) {
                $inteligencia = [];
                foreach ($datos as $vuelta) {
                    $inteligencia[] = [
                        'informacion' => $vuelta['info_descripcion'],
                    ];
                }
            } else {
                return  [];
            }
            return ($inteligencia);
        } catch (Exception $e) {
            return  [];
        }
    }




    //retornar informacion
    public static function BuscarInformacionAPI()
    {
        getHeadersApi();
        try {
            $operacion = $_GET['id'];
            $data = [];


            $datos = Derrota::fetchArray("SELECT * from codemar_operaciones INNER JOIN codemar_tipos_operaciones on tipo_id = ope_tipo  where ope_id = $operacion");
            $data['identificador'] = $datos[0]['ope_identificador'];
            $data['zarpe'] = $datos[0]['ope_fecha_zarpe'];
            $data['atraque'] = $datos[0]['ope_fecha_atraque'];
            $data['tipo'] = $datos[0]['tipo_desc'];


            $puntos = static::BuscarPuntos($operacion);
            $personal = static::BuscarPersonal($operacion);
            $unidades = static::BuscarUnidad($operacion);
            $motores = static::BuscarTrabajoMotores($operacion);
            $consumos = static::BuscarConsumos($operacion);
            $comunicaciones = static::BuscarComunicaciones($operacion);
            $novedades = static::BuscarNovedades($operacion);
            $recomendaciones = static::BuscarRecomendaciones($operacion);
            $inteligencia = static::BuscarInteligencia($operacion);


            $data['unidades'] = $unidades;
            $data['puntos'] = $puntos;
            $data['personal'] = $personal;
            $data['motores'] = $motores;
            $data['consumos'] = $consumos;
            $data['comunicaciones'] = $comunicaciones;
            $data['novedades'] = $novedades;
            $data['recomendaciones'] = $recomendaciones;
            $data['inteligencia'] = $inteligencia;

            echo json_encode($data);
        } catch (Exception $e) {

            echo json_encode(["error" => $e->getMessage()]);
        }
    }


    public static function CambioSituacionAPI()
    {

        getHeadersApi();
        $id = $_GET["id"];
        $distancia = $_GET["distancia"];
     


        try {
            $datos = Operaciones::fetchArray("SELECT * FROM codemar_operaciones WHERE ope_id = $id");

            if ($datos) {


                foreach ($datos as $key => $value) {
                    $cambio = new Operaciones([
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
                        'ope_distancia' => $distancia,
                        'ope_nacional' => $value['ope_nacional'],
                        'ope_sit' =>  "4"
                    ]);
                    $cambiar = $cambio->guardar();
                }
            }

            if ($cambiar) {
                echo json_encode([
                    "mensaje" => "Operacion aprobada",
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



    public static function RechazoSituacionAPI()
    {

        getHeadersApi();
        $id = $_GET["id"];


        try {
            $datos = Operaciones::fetchArray("SELECT * FROM codemar_operaciones WHERE ope_id = $id");

            if ($datos) {


                foreach ($datos as $key => $value) {
                    $cambio = new Operaciones([
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
                        'ope_sit' =>  "2"
                    ]);
                    $cambiar = $cambio->guardar();
                }
            }

            if ($cambiar) {
                echo json_encode([
                    "mensaje" => "Operacion rechazada",
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
