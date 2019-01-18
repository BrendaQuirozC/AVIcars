<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/database/CatalogoDB.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/catalogoAutos/version.php";
class Auto extends Version{
	function getMarcas($modelo=null,$ano=null)
	{
		$database=new CatalogoDB;
		$db=$database->connect();
		$query="SELECT c_vehicle_brand_system_id id, c_vehicle_brand name FROM c_vehicle_brand ORDER BY 2";
		$marcas=array();
		$queryDB=$db->query($query);
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$marcas[$row["id"]]=$row["name"];
			}
		}
		$db->close();
		return $marcas;
	}
	function getSubMarcas($marca=null,$ano=null)
	{
		$database=new CatalogoDB;
		$db=$database->connect();
		if(!$marca&&!$ano)
		{

			$query="SELECT c_vehicle_subbrand_system_id id, c_vehicle_subbrand_brand_id marca, c_vehicle_subbrand_name name 
					FROM c_vehicle_subbrand 
					ORDER BY 3 ";
		}
		elseif(!$marca&&$ano)
		{
			$query="SELECT c_vehicle_subbrand_system_id id, c_vehicle_subbrand_brand_id marca, c_vehicle_subbrand_name name 
					FROM c_vehicle_subbrand CVS 
					RIGHT JOIN c_vehicle_model CVM ON CVM.C_Vehicle_Model_SubBrands_ID=CVS.C_Vehicle_SubBrand_System_ID 
					WHERE CVM.C_Vehicle_Model=$ano 
					GROUP BY CVS.C_Vehicle_SubBrand_System_ID 
					ORDER BY 3 ";
		}
		elseif(!$ano)
		{
			$query="SELECT c_vehicle_subbrand_system_id id, c_vehicle_subbrand_brand_id marca, c_vehicle_subbrand_name name 
					FROM c_vehicle_subbrand 
					WHERE c_vehicle_subbrand_brand_id=$marca 
					ORDER BY 3 ";
		}
		else
		{
			$query="SELECT c_vehicle_subbrand_system_id id, c_vehicle_subbrand_brand_id marca, c_vehicle_subbrand_name name 
					FROM c_vehicle_subbrand CVS
					RIGHT JOIN c_vehicle_model CVM ON CVM.C_Vehicle_Model_SubBrands_ID=CVS.C_Vehicle_SubBrand_System_ID 
					WHERE c_vehicle_subbrand_brand_id=$marca AND CVM.C_Vehicle_Model=$ano 
					GROUP BY CVS.C_Vehicle_SubBrand_System_ID 
					ORDER BY 3 ";
		}
		$submarcas=array();
		$queryDB=$db->query($query);
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$submarcas[]=array("id"=>$row["id"], "marca" => $row["marca"], "submarca" => $row["name"]);
			}
		}
		$db->close();
		return $submarcas;
	}
    function getMarcaById($id){
        $database=new CatalogoDB;
        $db=$database->connect();
        $query="SELECT c_vehicle_brand name FROM c_vehicle_brand WHERE c_vehicle_brand_system_id=$id";
        $marca="";
        $queryDB=$db->query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $marca=$row["name"];
            }
        }
        $db->close();
        return $marca;
    }
    function getSubmarcaById($id){
        $database=new CatalogoDB;
        $db=$database->connect();
        $query="SELECT c_vehicle_subbrand_name name FROM c_vehicle_subbrand WHERE c_vehicle_subbrand_system_id=$id";
        $submarca="";
        $queryDB=$db->query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $submarca=$row["name"];
            }
        }
        $db->close();
        return $submarca;
    }
    function getModelById($id){
        $database=new CatalogoDB;
        $db=$database->connect();
        $query="SELECT C_Vehicle_Model name FROM c_vehicle_model WHERE C_Vehicle_Model_System_ID=$id";
        $modelo="";
        $queryDB=$db->query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $modelo=$row["name"];
            }
        }
        $db->close();
        return $modelo;
    }
    function getVersionById($id){
        $database=new CatalogoDB;
        $db=$database->connect();
        $query="SELECT C_Vehicle_Versions_Name name FROM c_vehicle_versions WHERE C_Vehicle_Versions_System_Id=$id";
        $version="";
        $queryDB=$db->query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $version=$row["name"];
            }
        }
        $db->close();
        return $version;
    }
	function getModels($marca=null,$submarca=null)
	{
		$database=new CatalogoDB;
		$db=$database->connect();
		if($submarca)
		{
			$query="SELECT CVM.C_Vehicle_Model_System_ID id, CVM.C_Vehicle_Model modelo, CVS.C_Vehicle_SubBrand_System_ID submarca, CVB.C_Vehicle_Brand_System_ID marca FROM c_vehicle_model CVM
					LEFT JOIN c_vehicle_subbrand CVS ON CVS.C_Vehicle_SubBrand_System_ID=CVM.C_Vehicle_Model_SubBrands_ID
					LEFT JOIN c_vehicle_brand CVB ON CVB.C_Vehicle_Brand_System_ID=CVS.C_Vehicle_SubBrand_Brand_ID
					WHERE CVS.C_Vehicle_SubBrand_System_ID=$submarca
					GROUP BY CVM.c_vehicle_model";
		}
		elseif($marca)
		{
			$query="SELECT CVM.C_Vehicle_Model_System_ID id, CVM.C_Vehicle_Model modelo, CVS.C_Vehicle_SubBrand_System_ID submarca, CVB.C_Vehicle_Brand_System_ID marca FROM c_vehicle_model CVM
					LEFT JOIN c_vehicle_subbrand CVS ON CVS.C_Vehicle_SubBrand_System_ID=CVM.C_Vehicle_Model_SubBrands_ID
					LEFT JOIN c_vehicle_brand CVB ON CVB.C_Vehicle_Brand_System_ID=CVS.C_Vehicle_SubBrand_Brand_ID
					WHERE CVB.c_vehicle_brand_system_id=$marca
					GROUP BY CVM.c_vehicle_model";
		}
		else
		{
			$query="SELECT CVM.C_Vehicle_Model_System_ID id, CVM.C_Vehicle_Model modelo, CVS.C_Vehicle_SubBrand_System_ID submarca, CVB.C_Vehicle_Brand_System_ID marca FROM c_vehicle_model CVM
					LEFT JOIN c_vehicle_subbrand CVS ON CVS.C_Vehicle_SubBrand_System_ID=CVM.C_Vehicle_Model_SubBrands_ID
					LEFT JOIN c_vehicle_brand CVB ON CVB.C_Vehicle_Brand_System_ID=CVS.C_Vehicle_SubBrand_Brand_ID
					GROUP BY CVM.c_vehicle_model";
		}
		$modelos=array();
		$queryDB=$db->query($query);
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$modelos[]=array("id"=>$row["id"], "modelo"=>$row["modelo"], "submarca"=>$row["submarca"], "marca"=>$row["marca"]);
			}
		}
		$db->close();
		return $modelos;
	}

	function knowVersion($modelo=null)
	{
		$database=new CatalogoDB;
		$db=$database->connect();

		if(!$modelo)
		{
            $modelo=0;
        }
		$query="SELECT C_Vehicle_Versions_System_Id id, C_Vehicle_Versions_Name version, C_Vehicle_Version_SubName subnombre, C_Vehicle_Versions_Model_ID modelo
				FROM c_vehicle_versions
				WHERE C_Vehicle_Versions_Model_ID=$modelo
				ORDER BY 2";
		
		$queryDB=$db->query($query);
		$versiones=array();
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$versiones[]=array("id"=>$row["id"],"version"=>$row["version"],"modelo"=>$row["modelo"],"subnombre"=>$row["subnombre"]);
			}
		}
		$db->close();
		return $versiones;
	}	

	function getVersiones($modelo=null)
	{
		$database=new CatalogoDB;
		$db=$database->connect();
        if($modelo)
        {
            $query="SELECT c_vehicle_versions_system_id id, c_vehicle_versions_name version
                FROM c_vehicle_versions 
                WHERE C_Vehicle_Versions_Model_ID=$modelo
                ORDER BY 1";
        }
        $versiones=array();
        if($query){
            $queryDB=$db->query($query);
            if($queryDB->num_rows>0)
            {
                while($row=$queryDB->fetch_assoc())
                {
                    $versiones[$row["id"]]=$row["version"];
                }
            }
            $db->close();
            return $versiones;
        }
		
	}	

	function imagenes(){
        $database=new CatalogoDB;
        $db=$database->connect();
        $img = array();
        $sql3="SELECT c_vehicle_brand_id, c_vehicle_brand_images_url, c_vehicle_brand_images_logo_url, c_vehicle_brand.C_Vehicle_Brand
                FROM c_vehicle_brand_images
                LEFT JOIN c_vehicle_brand on  c_vehicle_brand.C_Vehicle_Brand_System_ID=c_vehicle_brand_images.c_vehicle_brand_id
                WHERE c_vehicle_brand_images_url is not NULL";
        $queryDB = $db -> query($sql3);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $img[$row["c_vehicle_brand_id"]]=array("pic" => $row["c_vehicle_brand_images_url"], "cap"=>$row["C_Vehicle_Brand"],"logo"=>$row["c_vehicle_brand_images_logo_url"]);
            }  
        }
        $db->close();
     	return $img;
    }
    function getClass()
    {
    	$database=new CatalogoDB;
        $db=$database->connect();
        $query="SELECT C_Class_System_ID id, C_Class_Description_SP description, c_class_icons iconos, C_Class_Icons_Green green, C_Class_Icons_Outline outline FROM c_class WHERE C_Class_CrossOver_FLAG <> 1 ORDER BY C_Class_Order ASC";
     	$queryDB = $db -> query($query);
     	$classCar=array();
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
               $classCar[$row["id"]]=array("description"=>$row["description"], "iconos"=>$row["iconos"],"green"=>$row["green"],"outline"=>$row["outline"] );
            }
        }
        $db->close();
        return $classCar;
    }
    function getTypeCar($id)
    {
        $database=new CatalogoDB;
        $db=$database->connect();
        $Inst = array();
        $query ="SELECT C_Class_Description_SP description FROM c_class WHERE C_Class_System_ID = '$id'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $Inst=$row["description"];
            }
        }
        $db->close();
        return $Inst;
    }
    function getEngineType()
    {
    	$database=new CatalogoDB;
        $db=$database->connect();
        $query="SELECT c_engine_type_id id, c_engine_type_name name FROM c_engine_type WHERE c_engine_type_status=1";
     	$queryDB = $db -> query($query);
     	$engine=array();
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
               $engine[$row["id"]]=$row["name"];
            }
        }
        $db->close();
        return $engine;
    }
    function getTypeFuel()
    {
    	$database=new Database;
        $db=$database->connect();
    	$query="SELECT  c_avi_car_type_fuel_id id, c_avi_car_type_fuel_name_sp nombre FROM c_avi_car_type_fuel";
    	$queryDB = $db -> query($query);
     	$fuel=array();
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
               $fuel[$row["id"]]=$row["nombre"];
            }
        }
        $db->close();
        return $fuel;
    }
    function getTypeTrans()
    {
    	$database=new Database;
        $db=$database->connect();
    	$query="
    		SELECT c_avi_car_type_trans_id id,
    	 	c_avi_car_type_trans_name_sp nombre, 
    	 	c_avi_car_type_trans_img img 
    	 	FROM c_avi_car_type_trans";
    	$queryDB = $db -> query($query);
     	$trnsmition=array();
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
               $trnsmition[$row["id"]]=array("nombre"=>$row["nombre"], "img"=>$row["img"]);
            }
        }
        $db->close();
        return $trnsmition;
    }
    function updateObjCar($idObject, $color=NULL, $versionId=NULL, $vin=NULL, $engineType=NULL, $clasecar=NULL, $marca=NULL, $submarca=NULL, $modelo=NULL, $fuel=NULL, $trans=NULL, $doors=NULL, $windows=NULL, $interior=NULL, $km=NULL, $duenos=null, $hp=null, $newBrand=NULL, $newSubBrand=NULL, $newModel=NULL, $newVersion=NULL)
    {
    	$database=new Database;
        $db=$database->connect();
        $query="";
        $query.="UPDATE o_avi_car SET ";
        $count=0;
        if($color)
        {
        	$count++; 
        	$query.="o_avi_car_color=$color ";
        }
        if($newBrand)
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_brand='$newBrand'";
            $count++;
        }
        else{
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_brand=NULL";
            $count++;
        }
        if($newSubBrand)
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_subbrand='$newSubBrand'";
            $count++;
        }
        else
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_subbrand=NULL";
            $count++;
        }
        if($newModel)
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_model='$newModel'";
            $count++;
        }
        else
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_model=NULL";
            $count++;
        }
        if($versionId>0)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_version_id='$versionId'";
            $count++;
        }
        else
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_version_id=NULL";
            $count++;
        }
        if($vin)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_vin='$vin'";
            $count++;
        }
        if($engineType)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_engine_type_id ='$engineType'";
            $count++;
        }
        if($clasecar)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_class_id ='$clasecar'";
            $count++;
        }
        if($marca>0)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_brand_id  ='$marca'";
            $count++;
        }
        else
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_brand_id  =NULL";
            $count++;
        }
        if($submarca>0)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_subbrand_id ='$submarca'";
            $count++;
        }
        else
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_subbrand_id =NULL";
            $count++;
        }
        if($modelo>0)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_model_id ='$modelo'";
            $count++;
        }
        else
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_model_id =NULL";
            $count++;
        }
        if($fuel)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_fuel ='$fuel'";
            $count++;
        }
        if($trans)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_transmission ='$trans'";
            $count++;
        }
        if($doors)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_doors ='$doors'";
            $count++;
        }
        if($windows)
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_windows ='$windows'";
            $count++;
        }
        if($interior)
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_interior ='$interior'";
            $count++;
        }
        if($km)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_km ='$km'";
            $count++;
        }
        if($duenos)
        {
        	if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_number_owner ='$duenos'";
            $count++;
        }
        if($hp)
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_horsepower='$hp'";
            $count++;
        }
        if($newVersion)
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_version ='$newVersion'";
            $count++;
        }
        else
        {
            if($count>0)
            {
                $query.=", ";
            }
            $query.="o_avi_car_name_version=NULL";
            $count++;
        }
        $query.=" WHERE o_avi_car_id=$idObject";       
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
            $sql = "UPDATE i_avi_account_car SET i_avi_account_car_verified = 0 WHERE i_avi_account_car_car_id = $idObject";
            $db->query($sql);
        }
     	$db->close();
        return $resp;
    }

    function updateNewClass($idObject, $newBrand=NULL, $newSubBrand=NULL, $newModel=NULL){
        $database=new CatalogoDB;
        $db=$database->connect();
        $query="INSERT INTO ";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function getDoorsByClass($clase)
	{
		$database=new CatalogoDB;
        $db=$database->connect();
		$query="SELECT C_Class_Description_SP, C_Class_min_doors_numbers, C_Class_max_doors_numbers from c_class where C_Class_System_ID ='$clase'";
		$db->set_charset("utf8");
		$queryDB=$db->query($query);
		$puertas=array();
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc()) {
				$puertas=array("MinimoP"=> $row["C_Class_min_doors_numbers"], "MaximoP"=> $row["C_Class_max_doors_numbers"], "ClassName" => $row["C_Class_Description_SP"]);
			}
		}
		$db->close();
		return $puertas;
	}

	function updateInsGarageAuto($idInstancia, $detailStole=null, $detailRebuild=null, $detailLegalized=null, $fempresa=null, $flote=null, $fpersonafisica=null, $faseguradora=null, $placa=null, $holograma=null, $extras=null)
	{
		$database=new Database;
        $db=$database->connect();
		$query="UPDATE i_avi_account_car SET ";
		$count=0;
		if($detailStole || $detailStole=="0")
		{
			$count++;
			$query.="i_avi_account_car_detail_stole=$detailStole ";
		}
		if($detailRebuild || $detailRebuild=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_car_detail_rebuilt=$detailRebuild ";
            $count++;
		}
		if($detailLegalized || $detailLegalized=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_car_detail_legalized=$detailLegalized ";
            $count++;
		}
		if($fempresa || $fempresa=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_factura_empresa=$fempresa ";
            $count++;
		}
		if($flote || $flote=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_factura_lote=$flote ";
            $count++;
		}
		if($fpersonafisica || $fpersonafisica=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_factura_personafisica=$fpersonafisica ";
            $count++;
		}
		if($faseguradora || $faseguradora=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_factura_aseguradora=$faseguradora ";
            $count++;
		}
		if($placa || $placa=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_car_plate='$placa' ";
            $count++;
		}
		if($holograma || $holograma=="0")
		{
			if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_car_hologram ='$holograma' ";
            $count++;
		}
        if($extras)
        {
            $extras=json_encode($extras,JSON_UNESCAPED_UNICODE);
            if($count>0)
            {
                $query.=", ";
            }
            $query.="i_avi_account_car_extra_detail='$extras' ";
            $count++;
        }
		$query.="WHERE i_avi_account_car_car_id=$idInstancia";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
            $sql = "UPDATE i_avi_account_car SET i_avi_account_car_verified = 0 WHERE i_avi_account_car_car_id = $idInstancia";
            $db->query($sql);
        }
     	$db->close();
        return $resp;
	}
    function adCar($id)
    {
        $database=new Database;
        $db=$database->connect();
        $adCar = array();
        $query ="SELECT 
                o_avi_car_ad_id idAnuncio,
                o_avi_car_ad_car_id idauto,
                o_avi_car_ad_text texto,
                o_avi_car_ad_payment_method metodoPago,
                o_avi_car_ad_negociable negociable,
                o_avi_car_ad_since fecha,
                a_avi_car_ad_contact_phone phone,
                a_avi_car_ad_contact_phone_2 phone2,
                a_avi_car_ad_contact_phone_3 phone3,
                a_avi_car_ad_contact_phone_wa phonewa,
                a_avi_car_ad_contact_phone_2_wa phone2wa,
                a_avi_car_ad_contact_phone_3_wa phone3wa,
                a_avi_car_ad_contact_id idContact,
                a_avi_car_ad_contact_email email,
                a_avi_car_ad_contact_email_2 email2,
                a_avi_car_ad_location_address_street street,
                a_avi_car_ad_location_suburb suburb,
                a_avi_car_ad_location_zipcode zipcode,
                a_avi_car_ad_location_reference reference,
                a_avi_car_ad_location_id idLocation,
                a_avi_car_ad_contact_phone_code locationphone,
                a_avi_car_ad_contact_phone_2_code locationphone2,
                a_avi_car_ad_contact_phone_3_code locationphone3,
                (SELECT c_avi_country_phonecode FROM c_avi_country WHERE c_avi_country_id=a_avi_car_ad_contact_phone_code) phonecode,
                (SELECT c_avi_country_phonecode FROM c_avi_country WHERE c_avi_country_id=a_avi_car_ad_contact_phone_2_code) phonecode2,
                (SELECT c_avi_country_phonecode FROM c_avi_country WHERE c_avi_country_id=a_avi_car_ad_contact_phone_3_code) phonecode3
                FROM o_avi_car_ad
        LEFT JOIN a_avi_car_ad_contact AACAC  ON AACAC.a_avi_car_o_ad_id=o_avi_car_ad.o_avi_car_ad_id 
        LEFT JOIN a_avi_car_ad_location AACAL ON AACAL.a_avi_car_o_ad_id=o_avi_car_ad.o_avi_car_ad_id
        WHERE o_avi_car_ad_car_id='$id' and o_avi_car_ad_status=1";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $adCar=$row;
            }
        }
        $db->close();
        return $adCar;
        unset($adCar);
    }
    function createAdCar($id, $text=null, $payMethod, $negociable=null, $colaborador=null)
    {
        $database=new Database;
        $db=$database->connect();
        $into="o_avi_car_ad_car_id";
        $values="$id";
        if($text)
        {
            $into.=",o_avi_car_ad_text";
            $values.=", '$text'";
        }
        if($payMethod)
        {
            $payMethod=json_encode($payMethod);
            $into.=",o_avi_car_ad_payment_method";
            $values.=",'".$payMethod."'";
        }
        if($negociable)
        {
            $into.=",o_avi_car_ad_negociable";
            $values.=",".$negociable;
        }
        if($colaborador){
            $into.=", o_avi_car_ad_colaborator";
            $values.=", $colaborador";
        }
        $into.=",o_avi_car_ad_since";
        $values.=", NOW()";
        $query="INSERT INTO o_avi_car_ad($into) VALUES ($values)";
        $resp=false;
        if($result=$db->query($query)){
            $resp=$db->insert_id;
        }
        $db->close();
        return $resp;

    }
    function updateAdCar($id, $text, $payMethod, $negociable=null)
    {
        $database=new Database;
        $db=$database->connect();
        $payMethod=json_encode($payMethod);
        $set="o_avi_car_ad_payment_method='$payMethod'";
        if($text)
        {
            $set.=", o_avi_car_ad_text='$text'";
        }
        if($negociable==0 || $negociable==1)
        {
            $set.=", o_avi_car_ad_negociable=$negociable";
        }
        $query="UPDATE o_avi_car_ad SET $set WHERE o_avi_car_ad_car_id ='$id'";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function contactoAd($idAd,$phone, $email)
    {
        $database=new Database;
        $db=$database->connect();
        $into="a_avi_car_o_ad_id";
        $values="$idAd";
        $count=0;
        if(!empty($phone))
        {
            if($phone[0]["number"]!=""){
                $into.=",a_avi_car_ad_contact_phone, a_avi_car_ad_contact_phone_code, a_avi_car_ad_contact_phone_wa";
                $values.=", '".$phone[0]['number']."', '".$phone[0]['code']."', ".$phone[0]['wa'];
                $count++;
            }
            if($phone[1]["number"]!=""){
                $into.=",a_avi_car_ad_contact_phone_2, a_avi_car_ad_contact_phone_2_code, a_avi_car_ad_contact_phone_2_wa";
                $values.=", '".$phone[1]['number']."', '".$phone[1]['code']."', ".$phone[1]['wa'];
                $count++;
            }
            if($phone[2]["number"]!=""){
                $into.=",a_avi_car_ad_contact_phone_3, a_avi_car_ad_contact_phone_3_code, a_avi_car_ad_contact_phone_3_wa";
                $values.=", '".$phone[2]['number']."', '".$phone[2]['code']."', ".$phone[2]['wa'];
                $count++;
            }
            
            
        }
        if(!empty($email))
        {
            if($email[0]!=""){
               $into.=",a_avi_car_ad_contact_email";
                $values.=", '".$email[0]."'"; 
                $count++;
            }
            if(isset($email[1])){
                if($email[1]!=""){
                   $into.=",a_avi_car_ad_contact_email_2";
                    $values.=", '".$email[1]."'"; 
                    $count++;
                }

            }
            
        }
        $query="INSERT INTO a_avi_car_ad_contact($into) VALUES ($values)";
        //echo $query;
        $resp=false;
        if($count && $result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function updateContactoAdCar($idAd,$phone, $email)
    {
        $database=new Database;
        $db=$database->connect();
        $set="";
        $count=0;
        if(!empty($phone))
        {
            if($phone[0]["number"]!=""){
                if($count)
                {
                    $set.=", ";
                }
                $set.="a_avi_car_ad_contact_phone = '".$phone[0]['number']."', a_avi_car_ad_contact_phone_code = '".$phone[0]['code']."', a_avi_car_ad_contact_phone_wa = ".$phone[0]['wa'];
                $count++;
            }
            if($phone[1]["number"]!=""){
                if($count)
                {
                    $set.=", ";
                }
                $set.="a_avi_car_ad_contact_phone_2 = '".$phone[1]['number']."', a_avi_car_ad_contact_phone_2_code = '".$phone[1]['code']."', a_avi_car_ad_contact_phone_2_wa = ".$phone[1]['wa'];
                $count++;
            }
            if($phone[2]["number"]!=""){
                if($count)
                {
                    $set.=", ";
                }
                $set.="a_avi_car_ad_contact_phone_3 = '".$phone[2]['number']."', a_avi_car_ad_contact_phone_3_code = '".$phone[2]['code']."', a_avi_car_ad_contact_phone_3_wa = ".$phone[2]['wa'];
                $count++;
            }
            
        }
        if(!empty($email))
        {
            if($email[0]!=""){
                if($count)
                {
                    $set.=", ";
                }
                $set.="a_avi_car_ad_contact_email = '".$email[0]."'";
                $count++;
            }
            if(isset($email[1])){
                if($email[1]!=""){
                    if($count)
                    {
                        $set.=", ";
                    }
                    $set.="a_avi_car_ad_contact_email_2 = '".$email[1]."'";
                    $count++;
                }
            }
        }
        $query="UPDATE a_avi_car_ad_contact SET $set WHERE a_avi_car_ad_contact_id ='$idAd'";
        //echo $query;
        $resp=false;
        if($count && $result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function updateLocationAdCar($idAd,$street=null, $suburb=null, $zipcode=null, $reference=null)
    {
        $database=new Database;
        $db=$database->connect();
        $set="";
        $count=0;
        if($street)
        {
            $set.="a_avi_car_ad_location_address_street='$street'";
            $count++;
        }
        if($suburb)
        {
            if($count)
            {
                $set.=", ";
            }
            $set.="a_avi_car_ad_location_suburb='$suburb'";
            $count++;
        }
        if($zipcode)
        {
            if($count)
            {
                $set.=", ";
            }
            $set.="a_avi_car_ad_location_zipcode='$zipcode'";
            $count++;
        }
        if($reference)
        {
            if($count)
            {
                $set.=", ";
            }
            $set.="a_avi_car_ad_location_reference='$reference'";
            $count++;
        }
        $query="UPDATE a_avi_car_ad_location SET $set WHERE a_avi_car_ad_location_id ='$idAd'";
        $resp=false;
        if($count && $result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function locationAd($idAd,$street=null, $suburb=null, $zipcode=null, $reference=null)
    {
        $database=new Database;
        $db=$database->connect();
        $into="a_avi_car_o_ad_id";
        $values="$idAd";
        if($street)
        {
            $into.=",a_avi_car_ad_location_address_street";
            $values.=", '$street'";
        }
        if($suburb)
        {
            $into.=",a_avi_car_ad_location_suburb";
            $values.=", '$suburb'";
        }
        if($zipcode)
        {
            $into.=",a_avi_car_ad_location_zipcode";
            $values.=", '$zipcode'";
        }
        if($reference)
        {
            $into.=",a_avi_car_ad_location_reference";
            $values.=", '$reference'";
        }
        $query="INSERT INTO a_avi_car_ad_location($into) VALUES ($values)";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function deleteFollowersCar($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_user_follow_car WHERE a_user_following_i_car_id='$idCar';";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function deleteFiles($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_avi_car_file WHERE a_avi_car_file_car='$idCar';";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function deletePlacas($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_avi_car_plate WHERE a_avi_car_plate_car_id='$idCar';";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function getCarifAd($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="SELECT count(o_avi_car_ad_car_id) auto, o_avi_car_ad_id anuncio,o_avi_car_ad_status status FROM o_avi_car_ad WHERE o_avi_car_ad_car_id= $idCar";
        $autoId="";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $autoId=$row;
                }
            }
        }
        $db->close();
        return $autoId;
    }
    function getAdExtras($idAd)
    {
        $database=new Database;
        $db=$database->connect();
        $query="SELECT o_avi_car_ad_car_id carId,
        i_avi_account_car_account_id garage,
        o_avi_account_user_id userAd
        FROM o_avi_car_ad 
        LEFT JOIN i_avi_account_car ON o_avi_car_ad.o_avi_car_ad_car_id = i_avi_account_car.i_avi_account_car_id
        LEFT JOIN o_avi_account ON i_avi_account_car.i_avi_account_car_account_id = o_avi_account.o_avi_account_id
        WHERE o_avi_car_ad_id= $idAd";
        $autoId="";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $autoId=$row;
                }
            }
        }
        $db->close();
        return $autoId;
    }
}

?>