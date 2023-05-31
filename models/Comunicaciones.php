<?php
namespace Model;

class Comunicaciones extends ActiveRecord {
    protected static $idTabla = 'com_id';
    protected static $tabla = 'codemar_comunicaciones';
    protected static $columnasDB = ['com_operacion', 'com_medio', 'com_receptor', 'com_calidad', 'com_observacion', 'com_situacion'];

    public $com_id;
    public $com_operacion;
    public $com_medio;
    public $com_receptor;
    public $com_calidad;
    public $com_observacion;
    public $com_situacion;

    public function __construct($args = []) {
        $this->com_id = $args['com_id'] ?? null;
        $this->com_operacion = $args['com_operacion'] ?? '';
        $this->com_medio = $args['com_medio'] ?? '';
        $this->com_receptor = $args['com_receptor'] ?? '';
        $this->com_calidad = $args['com_calidad'] ?? '';
        $this->com_observacion = $args['com_observacion'] ?? '';
        $this->com_situacion = $args['com_situacion'] ?? 1;
    }
}
?>