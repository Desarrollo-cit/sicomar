<?php
namespace Controllers;

use MVC\Router;
use Model\Internacionales;
use Model\Operaciones;
use Model\Asignar_personal;
use Model\Derrota;
use Exception;


class InternacionalesController {


    public static function index(Router $router){

        $paises = Internacionales::fetchArray("SELECT * from paises order by pai_desc_lg asc");



        $router->render('internacionales/index',[

            'paises'=>$paises,

            
        ]);
    }


    public static function getCatalogo(){

        $catalogo = $_GET['catalogo'];
        // echo json_encode($catalogo);
        // exit;

        $informacion= Internacionales::fetchArray("SELECT trim(gra_desc_ct) as grado, trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre  FROM mper inner join grados on per_grado = gra_codigo inner join morg on per_plaza = org_plaza where per_catalogo = $catalogo and org_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = user)");

        echo json_encode($informacion);
        
    }

    public static function guardar(){
       
        $_POST['atraque'] = str_replace('T', ' ', $_POST['atraque']);
        $_POST['zarpe'] = str_replace('T', ' ', $_POST['zarpe']);

         
       try {
        $dependencias = Internacionales::fetchArray("SELECT org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER");

        // foreach ($dependencias as $key => $value) {

        //     $dependencia =$value['org_dependencia'];
        // }

   
        $guardar = new Operaciones([

            'ope_fecha_zarpe' => $_POST['zarpe'],
            'ope_fecha_atraque' => $_POST['atraque'],
            'ope_dependencia' => $dependencias[0]['org_dependencia'],
            'ope_distancia' => $_POST['distancia'],

        ]);


        $resultado = $guardar->crear();

        if($resultado['resultado'] == 1){

            $id= $guardar->getIdUltimaOperacion();

            $asignar_personal = new Asignar_personal([

                'asi_operacion' => $id,
                'asi_catalogo' => $_POST['catalogo']


            ]);

            $resultado2 = $asignar_personal->crear();

    


            if($resultado2['resultado'] == 1){

                
                foreach ($_POST['puntos'] as $key => $punto) {
                    $arrayPunto = explode(',',$punto);
                    $latitud = $arrayPunto[0];
                    $longitud = $arrayPunto[1];



          

                    $derrota = new Derrota (
                        [
                            'der_ope'=>$id,
                            'der_latitud' => $latitud,
                            'der_longitud' => $longitud
                        ]
                    );
                    //                     echo json_encode($derrota);
                    // exit;

                    $resultado3 = $derrota->guardar();


                    }

                    if ($resultado3['resultado'] == 1){

                        $uniqid = uniqid();
                        $ruta = 'storage/'. $uniqid. ".pdf";
                        $temporal = $_FILES['documento']['tmp_name'];

                        $subido = move_uploaded_file($temporal, $ruta);

                        if($subido){

                            $internacional = new Internacionales([
    
                                'int_ope' => $id,
                                'int_pais' => $_POST['pais'],
                                'int_documento' => $ruta
    
    
    
    
                            ]);
    
                            $resultado4 = $internacional->guardar();
                        }else{
                            echo json_encode([
                                "mensaje" => "Ocurrió  un error subiendo el archivo.",
                                "codigo" => 0,
                            ]);
                            exit;
                        }



  
                }
            //     // echo json_encode($latlng);
            //     // exit;



            }


        }


        
        if($resultado4['resultado'] == 1){
            echo json_encode([
                "mensaje" => "Se guardo la operacion.",
                "codigo" => 1,
            ]);
            
        }else{
            echo json_encode([
                "mensaje" => "Ocurrió  un error.",
                "codigo" => 0,
            ]);

        }
    } catch (Exception $e) {
        echo json_encode([
            "detalle" => $e->getMessage(),       
            "mensaje" => "Ocurrió un error en base de datos",

            "codigo" => 4,
        ]);
    }


  

     


        



        
    }

}