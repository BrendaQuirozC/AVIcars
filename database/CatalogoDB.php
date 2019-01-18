<?php
/**
 * Created by PhpStorm.
 * User: Juan Gonzalez
 * Date: 15/01/2018
 * Time: 09:09 AM
 */

class CatalogoDB
{
    private $host="localhost";
    //private $user="cars_public";
    //private $pwd="c4R\$_9Ubl1c";
    //private $db="autos";
    private $user="root";
    private $pwd="";
    private $db="autos";
    private $conn=null;
    public function connect() {
        $conn=new mysqli($this->host,$this->user,$this->pwd,$this->db);
        mysqli_set_charset( $conn, 'utf8');
        if($conn->connect_error)
        {
            die("Conexi&oacute;n fallida: ".$conn->connect_error);
            //return false;
        }
        return $conn;
    }
}