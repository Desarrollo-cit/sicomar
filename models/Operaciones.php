<?php

namespace Model;

class Operaciones extends ActiveRecord{
    protected static $tabla = 'codemar_operaciones'; //nombre de la tablaX
    protected static $columnasDB = ['OPE_TIPO', 'OPE_FECHA_ZARPE', 'OPE_FECHA_ATRAQUE','OPE_SITUACION','OPE_MISION','OPE_EJECUCION','OPE_IDENTIFICADOR','OPE_DEPENDENCIA','OPE_REUTILIZAR','OPE_DISTANCIA','OPE_NACIONAL','OPE_SIT'];
    protected static $idTabla = 'OPE_ID';

    public $ope_id;
    public $ope_tipo;
    public $ope_fecha_zarpe;
    public $ope_fecha_atraque;
    public $ope_situacion;
    public $ope_mision;
    public $ope_ejecucion;
    public $ope_identificador;
    public $ope_dependencia;
    public $ope_reutilizar;
    public $ope_distancia;
    public $ope_nacional;
    public $ope_sit;
     

    public function __construct($args = []){
        $this->ope_id = $args['ope_id'] ?? null;
        $this->ope_tipo = $args['ope_tipo'] ?? '10';
        $this->ope_fecha_zarpe    = $args['ope_fecha_zarpe'] ?? '';
        $this->ope_fecha_atraque    = $args['ope_fecha_atraque'] ?? '';
        $this->ope_situacion    = $args['ope_situacion'] ?? '';
        $this->ope_mision    = $args['ope_mision'] ?? '';
        $this->ope_ejecucion    = $args['ope_ejecucion'] ?? '';
        $this->ope_identificador    = $args['ope_identificador'] ?? 'INTERNACIONAL';
        $this->ope_dependencia    = $args['ope_dependencia'] ?? '';
        $this->ope_reutilizar    = $args['ope_reutilizar'] ?? '0';
        $this->ope_distancia    = $args['ope_distancia'] ?? '0';
        $this->ope_nacional    = $args['ope_nacional'] ?? 'I';
        $this->ope_sit = $args['ope_sit'] ?? '1';
    }

   public function getIdUltimaOperacion(){
        $sql = "SELECT first 1 ope_id as id from codemar_operaciones where ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER) and ope_sit != 0 order by ope_id desc";
        $resultado = $this->fetchArray($sql);
        return $resultado[0]['id']; 
    }
}