<?php

/**
 * @Author: erikfer94
 * @Date:   2019-01-04 11:46:13
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2019-01-04 12:26:24
 */
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$usuario=new Usuario;
$user=array();
if($usuario->verifyEmail($_POST["m"])){
	echo 1;
}
else{
	echo 0;
}