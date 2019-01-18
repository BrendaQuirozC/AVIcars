<?php 
require_once 'FuncionRecursiva.php';
$recursive = new Recursividad;
echo json_encode($recursive -> unidades($_POST["unidad"]), JSON_UNESCAPED_UNICODE);
?>