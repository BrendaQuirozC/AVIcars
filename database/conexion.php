<?php
class Database{
	private $host="localhost";
	private $user="root";
    private $pwd="";
    private $db="apoyo_vial";
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
?>