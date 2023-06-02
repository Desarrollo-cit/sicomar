<?php
namespace Model;

class Consumos extends ActiveRecord {
    protected static $idTabla = 'con_id';
    protected static $tabla = 'codemar_consumos';
    protected static $columnasDB = ['con_operacion', 'con_insumo', 'con_cantidad', 'con_situacion'];

    public $con_id;
    public $con_operacion;
    public $con_insumo;
    public $con_cantidad;
    public $con_situacion;

    public function __construct($args = []) {
        $this->con_id = $args['con_id'] ?? null;
        $this->con_operacion = $args['con_operacion'] ?? '';
        $this->con_insumo = $args['con_insumo'] ?? '';
        $this->con_cantidad = $args['con_cantidad'] ?? '';
        $this->con_situacion = $args['con_situacion'] ?? 1;
    }
}

?>