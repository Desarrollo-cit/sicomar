<?php

namespace Model;

class Asigpersonal extends ActiveRecord{

    protected static $tabla = 'codemar_asig_personal'; //nombre de la tablaX
    protected static $columnasDB = ['ASI_OPERACION','ASI_CATALOGO','ASI_SIT'];
    protected static $idTabla = 'asi_id';


    public $asi_id;
    public $asi_operacion;
    public $asi_catalogo;
    public $asi_sit;

    public function __construct($args = []){
        $this->asi_id = $args['asi_id'] ?? null;
        $this->asi_operacion = $args['asi_operacion'] ?? '';
        $this->asi_catalogo = $args['asi_catalogo'] ?? '';
        $this->asi_sit = $args['asi_sit'] ?? '1';
    }

    public function getIdpersonas($id){
        $sql = "SELECT asi_id from codemar_asig_personal where asi_operacion = $id";
        $resultado = $this->fetchArray($sql);
        $arrayResultados = array();
        // foreach ($resultado as $fila) {
        //     $arrayResultados[] = $fila['der_id'];
        // }
        
        return $resultado;
    }

}