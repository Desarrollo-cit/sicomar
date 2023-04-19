<?php

$ope_id = $_GET['id'];

$decoded_id = base64_decode($ope_id);
echo $decoded_id;
?>



<h1>DERROTA</h1>

<script src="<?= asset('/build/js/Reporte/derrota.js') ?>"></script>