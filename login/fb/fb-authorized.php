<?php
/**
 * User: Brenda Quiroz
 * Date: 23/03/2018
 * Time: 03:54 PM
 */



include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';

if(isset($_POST["username"])){
    $usrname = $_POST["username"];
    $email=NULL;
}else{
    $email=$_POST["mail"];
    $usrname=NULL;
}
$pwd= $_POST["password"];
$session =$usr -> login($usrname, $email, $pwd);
if($session==FALSE)
{
    echo 0;
}
else{
    echo $_SESSION["iduser"];


}