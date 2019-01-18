<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 11/01/2018
 * Time: 09:12 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/database/conexion.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/login/address.php';

$address = new Address;
$database=new Database;
$db=$database->connect();
$zipcode = $_POST["code"];
$add = $address-> add($zipcode);
$verifzip=$address->verifyZip($zipcode);

if($verifzip!=NULL)
{
    $addjson = json_encode($add);
    print_r($addjson);
}
else{
    echo 0;
}
