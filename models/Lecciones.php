<?php
namespace Model;

class Lecciones extends ActiveRecord {
    protected static $idTabla = 'rec_id';
    protected static $tabla = 'codemar_recomendaciones';
    protected static $columnasDB = ['rec_operacion', 'rec_recomendacion', 'rec_situacion'];

    public $rec_id;
    public $rec_operacion;
    public $rec_recomendacion;
    public $rec_situacion;

    public function __construct($args = []) {
        $this->rec_id = $args['rec_id'] ?? null;
        $this->rec_operacion = $args['rec_operacion'] ?? '';
        $this->rec_recomendacion = $args['rec_recomendacion'] ?? '';
        $this->rec_situacion = $args['rec_situacion'] ?? 1;
    }
}
?>
