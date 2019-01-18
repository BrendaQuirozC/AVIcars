<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 16/01/2018
 * Time: 12:17 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

session_destroy();
session_unset();

header('Location: ../');
