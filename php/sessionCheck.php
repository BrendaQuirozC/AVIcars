<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["user"])){
	echo 0;
}
else
	echo 1;

?>
