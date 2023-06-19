<?php

namespace Model;

class Asigunidad extends ActiveRecord{

    protected static $tabla = 'codemar_asig_unidad'; //nombre de la tablaX
    protected static $columnasDB = ['ASI_OPERACION','ASI_UNIDAD','ASI_SIT'];
    protected static $idTabla = 'asi_id';

    public $asi_id;
    public $asi_operacion;
    public $asi_unidad;
    public $asi_sit;

    public function __construct($args = []){
        $this->asi_id = $args['asi_id'] ?? '0';
        $this->asi_operacion = $args['asi_operacion'] ?? '';
        $this->asi_unidad = $args['asi_unidad'] ?? '';
        $this->asi_sit = $args['asi_sit'] ?? '1';
    }

    public function getIdasi($id){
        $sql = "SELECT asi_id from codemar_asig_unidad where asi_operacion = $id";
        $resultado = $this->fetchArray($sql);
        return $resultado[0]['asi_id']; 
    }



}