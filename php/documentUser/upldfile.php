<?php


include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';

$image = array();
$dir_subida = ($_SERVER['DOCUMENT_ROOT'])."/tmp/";
//$username = $_SESSION["user"];

if(isset($_FILES['carPhotos']))
{
    $image = $_FILES['carPhotos'];
    $fichero_subido = $dir_subida . basename($image['name']);
    //$dir_subida = ($_SERVER['DOCUMENT_ROOT'])." /users/".$username."/fotoCar/ ";
    subirimagen ($image, $fichero_subido, 1);
}
elseif(isset($_FILES['factura']))
{
    $image = $_FILES['factura'];
    $fichero_subido = $dir_subida . basename($image['name']);
    //$dir_subida = ($_SERVER['DOCUMENT_ROOT'])." /users/".$nombreUsuario."/FotoFactura/ ";
    subirimagen ($image, $fichero_subido, 2);
}
else
{
    $image = $_FILES['vin'];
    $fichero_subido = $dir_subida . basename($image['name']);
    //$dir_subida = ($_SERVER['DOCUMENT_ROOT'])." /users/".$nombreUsuario."/FotoVin/ ";
    subirimagen ($image, $fichero_subido, 3);
}

function subirimagen ($image, $fichero_subido, $tipo)
{
    session_start();
    $Venta = new Venta;
    if (move_uploaded_file($image['tmp_name'], $fichero_subido))
    {
        $imagenExistente = $Venta ->checkImgCarTmp($_SESSION["venta"]["cuentaid"], $fichero_subido);
        if($imagenExistente==0)
        {
            $uploadFileUser = $Venta -> imagenAutoTmp($_SESSION["venta"]["cuentaid"], $fichero_subido, $tipo);

            echo $fichero_subido;
        }
        else
        {
            echo "exist";
        }

    }
    else
    {
        echo 0;
    }
}
