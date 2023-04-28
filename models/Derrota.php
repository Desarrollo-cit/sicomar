<?php

namespace Model;

class Derrota extends ActiveRecord{
    protected static $idTabla = 'der_id';
    protected static $tabla = 'codemar_derrota'; //nombre de la tablaX
    protected static $columnasDB = ['DER_ID', 'DER_OPE', 'DER_LATITUD', 'DER_LONGITUD', 'DER_FECHA','DER_SITUACION'];

    public $der_id;
    public $der_ope;
    public $der_latitud;
    public $der_longitud;
    public $der_fecha;
    public $der_situacion;



    public function __construct($args = []){
        $this->der_id = $args['der_id'] ?? null;
        $this->der_ope = $args['der_ope'] ?? '';
        $this->der_latitud = $args['der_latitud'] ?? '';
        $this->der_longitud = $args['der_longitud'] ?? '';
        $this->der_fecha = $args['der_fecha'] ?? '';
        $this->der_situacion = $args['der_situacion'] ?? 1;
    }

}