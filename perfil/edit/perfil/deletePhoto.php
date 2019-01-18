<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-07-13 09:03:43
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:52:48
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(file_exists($_SERVER["DOCUMENT_ROOT"].$_POST["url"])){
	unlink($_SERVER["DOCUMENT_ROOT"].$_POST["url"]);
}