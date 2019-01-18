<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';

$Version = new Version;
//id version en feacture
$verFeature = $Version -> feature($_POST["version"]);


?>

<div class="brand-sub-name"><?= $verFeature[$_POST["version"]]["C_Vehicle_Brand"]?> <?= $verFeature[$_POST["version"]]["C_Vehicle_SubBrand_Name"]?> <?= $verFeature[$_POST["version"]]["C_Vehicle_Versions_Name"]?></div>

<div class="table-responsive text-center pad-line-bk">
    <table class="table border-table">
    <?php
    $caracteristicas = json_decode($verFeature[$_POST["version"]]["c_vehicle_versions_extraSpecifications"], true);

    $Version->getFullCaratersiticas($caracteristicas, "", 0, "");
    //$Version->getFullCaratersiticas($caracteristicas, 0, "");
    ?>
    </table>
</div>
