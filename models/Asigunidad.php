<?php

namespace Model;

class Asigunidad extends ActiveRecord{

    protected static $tabla = 'codemar_asig_unidad'; //nombre de la tablaX
    protected static $columnasDB = ['ASI_ID','ASI_OPERACION','ASI_UNIDAD','ASI_SIT'];

    public $asi_id;
    public $asi_operacion;
    public $asi_unidad;
    public $asi_sit;

    public function __construct($args = []){
        $this->asi_id = $args['asi_id'] ?? null;
        $this->asi_operacion = $args['asi_operacion'] ?? '';
        $this->asi_unidad = $args['asi_unidad'] ?? '';
        $this->asi_sit = $args['asi_sit'] ?? '1';
    }

}