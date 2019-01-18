<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-10-04 17:17:30
 * @Last Modified by:   Erik Viveros
 * @Last Modified time: 2018-10-04 17:19:04
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode("MTUxNTE1MTVRSg==");
$idPost=$coder->toEncode;
echo $idPost;