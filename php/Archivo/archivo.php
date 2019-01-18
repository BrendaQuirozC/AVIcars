<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-29 10:09:18
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-01 14:23:15
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
class Archivo{
	private $id=null;
	public $object=null;
	public $url=null;
	public $type=null;
	public $warning=null;
	public $typeObject=null;
	function __construct($id=null,$object=null){
		try{
			$this->init($id,$object);
		}
		catch(Exception $e){
			$this->warning=$e->getMessage();
		}
	}
	public function init($id=null,$object=null){
		if($id&&$object){
			$infoFile=$this->get($id,$object);
			if(!empty($infoFile)){
				$this->id=$id;
				$this->object=$infoFile["object"];
				$this->url=$infoFile["url"];
				$this->type=$infoFile["type"];
				$this->typeObject=$object;
				$this->typeName=$infoFile["typeName"];
			}
		}
		elseif($id)
		{
			throw new Exception("Debes especificar un objeto para el documento", 1);
		}
		else{
			throw new Exception("No se especifico el documento", 1);
			
		}
	}
	public function add($url,$type,$object,$extras=array(),$nombre=null){
        try{
        	$infoType=$this->getType($type);
        }
        catch(Exception $e){
        	throw new Exception($e->getMessage(), 1);
        	
        }
        if(empty($infoType))
        {
        	throw new Exception("Tipo de Objeto no valido", 1);
        }
        $objectType=$infoType["objeto"];
        switch ($objectType) {
        	case 1:
        		$table="a_avi_car_file";
        		$objName="car";
        		break;
        	case 2:
        		$table="a_avi_account_file";
        		$objName="account";
        		break;
        	case 3:
        		$table="a_avi_user_file";;
        		$objName="user";
        		break;
        	default:
        		throw new Exception("Objeto no válido", 1);
        		break;
        }
		$database=new Database;
        $db=$database->connect();
        $insert="{$table}_{$objName},{$table}_type,{$table}_url";
        $values="$object,$type,'$url'";
        if(!empty($extras)){
        	$insert.=",{$table}_extras";
        	$values.=",'".base64_encode(json_encode($extras))."'";
        }
        if($nombre){
        	$insert.=",{$table}_name";
        	$values.=",'$nombre'";
        }
        $query="INSERT INTO $table ($insert) VALUES ($values)";
        $data=$db->query($query);
        $response=false;
        if($data)
        {
        	$response=true;
        	$this->init($db->insert_id,$objectType);
        }
        $db->close();
        return $response;

	}
	public function delete($id=null,$object=null){
		$delete=null;
		$from=null;
		if($id&&$object)
		{
			$delete=$id;
			$from=$object;
		}
		elseif ($this->id) {
			$delete=$this->id;
			$from=$this->typeObject;
		}
		if(!$delete){
			throw new Exception("Especifica un documento", 1);
		}
        $infoFile=$this->get($delete,$from);
		$resp=false;
        if(!empty($infoFile)){
		    switch ($from) {
	        	case 1:
	        		$table="a_avi_car_file";
	        		$objName="car";
	        		break;
	        	case 2:
	        		$table="a_avi_account_file";
	        		$objName="account";
	        		break;
	        	case 3:
	        		$table="a_avi_user_file";;
	        		$objName="user";
	        		break;
	        	default:
	        		throw new Exception("Objeto no válido", 1);
	        		break;
	        }
        	unlink($_SERVER["DOCUMENT_ROOT"].$infoFile["url"]);
			$database=new Database;
		    $db=$database->connect();
		    $query="DELETE FROM $table WHERE {$table}_id=$delete";
		    if($db->query($query)){
		    	$resp=true;
		    }
        	$db->close();
        }
        else
        {
        	throw new Exception("EL documento no existe", 1);
        }
        return $resp;
	}
	public function get($id=null,$object=null){
		if($id&&$object){
			$select=$id;
			$from=$object;
		}
		elseif($this->id){
			$select=$this->id;
			$from=$this->typeObject;
		}
		else{
			throw new Exception("Especifica un documento", 1);
			
		}
		switch ($from) {
        	case 1:
        		$table="a_avi_car_file";
        		$objName="car";
        		break;
        	case 2:
        		$table="a_avi_account_file";
        		$objName="account";
        		break;
        	case 3:
        		$table="a_avi_user_file";;
        		$objName="user";
        		break;
        	default:
        		throw new Exception("Objeto no válido", 1);
        		break;
        }
		$database=new Database;
	    $db=$database->connect();
	    $query="SELECT {$table}_id id, {$table}_{$objName} object, {$table}_type type, {$table}_url url, {$table}_name filename, c_avi_file_type_name typeName
	    		FROm $table AATF
	    		LEFT JOIN c_avi_file_type CAFT ON CAFT.c_avi_file_type_id=AATF.{$table}_type
	    		WHERE {$table}_id=$select AND {$table}_status=1";
	    $file=array();
	    if($data=$db->query($query)){
	    	while ($row=$data->fetch_assoc()) {
	    		$file=$row;
	    	}
	    }
	    $db->close();
	    return $file;
	}
	public function getType($type=null)
	{
		if(!$type){
			throw new Exception("Especifica un tipo", 1);
		}
		$database=new Database;
        $db=$database->connect();
        $tipo=array();
        $query="SELECT c_avi_file_type_name nombre, c_avi_file_type_object objeto 
        		FROM c_avi_file_type
        		WHERE c_avi_file_type_id=$type AND c_avi_file_type_status=1";
        $data=$db->query($query);
        if($data->num_rows>0)
        {
        	while ($row=$data->fetch_assoc()) {
        		$tipo=$row;
        	}
        }
    	$db->close();
    	return $tipo;
    
	}
	function getFilesByObject($idObject,$typeObject){
		switch ($typeObject) {
        	case 1:
        		$table="a_avi_car_file";
        		$objName="car";
        		break;
        	case 2:
        		$table="a_avi_account_file";
        		$objName="account";
        		break;
        	case 3:
        		$table="a_avi_user_file";;
        		$objName="user";
        		break;
        	default:
        		throw new Exception("Objeto no válido", 1);
        		break;
        }
		$database=new Database;
	    $db=$database->connect();
	    $query="SELECT {$table}_id id, {$table}_{$objName} object, {$table}_type type, {$table}_url url, {$table}_name filename, {$table}_extras extras, c_avi_file_type_name typeName
	    		FROm $table AATF
	    		LEFT JOIN c_avi_file_type CAFT ON CAFT.c_avi_file_type_id=AATF.{$table}_type
	    		WHERE {$table}_{$objName}=$idObject AND {$table}_status=1 
	    		ORDER BY filename";
	    $files=array();
	    if($data=$db->query($query)){
	    	while ($row=$data->fetch_assoc()) {
	    		$files[]=$row;
	    	}
	    }
	    $db->close();
	    return $files;
	}
	function getFileTypesByTypeObject($objectType){

		$database=new Database;
	    $db=$database->connect();
	    $query="SELECT c_avi_file_type_id id, c_avi_file_type_name nombre, c_avi_file_type_object objeto, c_avi_file_type_extra extra 
        		FROM c_avi_file_type
        		WHERE c_avi_file_type_object=$objectType AND c_avi_file_type_status=1";
        $tipos=array();
        if($data=$db->query($query)){
        	while($row=$data->fetch_assoc()){
        		$tipos[]=$row;
        	}
        }
        $db->close();
        return $tipos;
	}
	function validateAccesFile($user,$file,$type,$object){
		try{
			$infoType=$this->getType($type);
		}
		catch(Exception $e){
			return false;
		}
		$typeObject=$infoType["objeto"];
		$response=false;
		if($this->objectBelongsToUser($user,$object,$typeObject)||$this->objectBelongsToColaborator($user,$object,$typeObject))
		{
			switch ($typeObject) {
	        	case 1:
	        		$table="a_avi_car_file";
	        		$objName="car";
	        		
	        		break;
	        	case 2:
	        		$table="a_avi_account_file";
	        		$objName="account";
	        		break;
	        	case 3:
	        		$table="a_avi_user_file";;
	        		$objName="user";
	        		break;
	        	default:
	        		throw new Exception("Objeto no válido", 1);
	        		break;
	        }

			$database=new Database;
		    $db=$database->connect();
        	$query="SELECT {$table}_url url FROM {$table} WHERE {$table}_id=$file AND {$table}_status=1";

        	if($data=$db->query($query))
			{
				if($data->num_rows>0)
				{
					while ($row=$data->fetch_assoc()) {
						
						$response=$row["url"];
					}
				}
			}
			//echo $query;
			$db->close();
			return $response;
        }
	}
	function objectBelongsToColaborator($user,$object,$typeObject){
		$Garage = new Garage;
		$response=false;
		if($typeObject==1) {
			$garageContain= $Garage-> instanciaById($object);
			if($Garage->getAUserAccount($user,$garageContain[0]['o_avi_account_id'],2)){
				$response=true;
			}
			
		}
		return $response;
	}
	function objectBelongsToUser($user,$object,$typeObject){
		$response=false;
		switch ($typeObject) {
			case 1:
				$database=new Database;
			    $db=$database->connect();
				$query="SELECT i_avi_account_car_id 
						FROM i_avi_account_car IAAC
						LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id
						WHERE OAA.o_avi_account_user_id=$user AND IAAC.i_avi_account_car_id=$object";
				if($data=$db->query($query))
				{
					if($data->num_rows>0)
					{
						$response=true;
					}
				}
				$db->close();
				break;
			case 2:
				$database=new Database;
			    $db=$database->connect();
				$query="SELECT o_avi_account_id 
						FROM o_avi_account OAA
						WHERE OAA.o_avi_account_user_id=$user AND OAA.o_avi_account_id=$object";
				if($data=$db->query($query))
				{
					if($data->num_rows>0)
					{
						$response=true;
					}
				}
				$db->close();
				break;
			case 3:
				if($user===$object){
					$response=true;
				}
				break;
			default:
				throw new Exception("Objeto no válido", 1);
				break;
		}
		return $response;
	}
	function __destruct(){
		$this->id=null;
		$this->object=null;
		$this->url=null;
		$this->type=null;
		$this->warning=null;
	}
}