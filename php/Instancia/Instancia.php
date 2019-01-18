<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-17 15:51:45
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-12-13 13:29:52
 */

require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"]. "/php/Garage/Garage.php";
require_once $_SERVER["DOCUMENT_ROOT"]. "/php/catalogoAutos/version.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once $_SERVER["DOCUMENT_ROOT"]. "/php/Utilities/coder.php";
class Instancia extends Garage
{
	public $intanceByCar=null;
	function __construct($idAccountCar=null)
	{
		if($idAccountCar)
		{
			$this->getInstanceByCar($idAccountCar);
		}
	}
	function createInstance($autoCuentaid, $garageid)
	{
		$database=new Database;
        $db=$database->connect();
        $query = "INSERT INTO i_avi_account_instance(i_avi_account_car_id, i_avi_account_id ) VALUES ($autoCuentaid, $garageid)";
		if($result=$db->query($query))
		{
			$result=$db->insert_id;
		}
		$db->close();
		return $result;
	}
	function getInstanceByCar($idCar)
	{
		$database=new Database;
        $db=$database->connect();
        $query="SELECT i_avi_account_instance_id instanciaId, i_avi_account_id garages, i_avi_account_car_id auto FROM i_avi_account_instance WHERE i_avi_account_car_id=$idCar";
        $result=$db->query($query);
        if($result->num_rows>0){
			while ($row=$result->fetch_assoc()) {
				$this->intanceByCar[$row["instanciaId"]]=array("garage"=>$row["garages"], "auto"=>$row["auto"]);
			}
		}
		else
		{
			$this->intanceByCar=0;
		}
	}
	function deleteInstanciaById($id)
	{
		$database=new Database;
        $db=$database->connect();
        $query="DELETE FROM i_avi_account_instance WHERE i_avi_account_car_id=$id";
        $resp=false;
        if($result=$db->query($query)){
			$resp=true;
		}
		return $resp;
	}
    function getCarsFhorSearch($search=null,$searcher=null,$time=0)
    {
        $users=array();
        $coder=new Coder;
        $search=strtolower($search);
        if(!$search||$search==="")
        {
            throw new Exception("Empty search", 1);
            
        }
        $inicio=10*$time;
        $notAllowedUsers=$this->notAccesibleUsers($searcher);
        $notin="0";
        foreach ($notAllowedUsers as $u => $user) {
            $notin.=",$user";
        }
        $database=new Database;
        $db=$database->connect();
        $query="SELECT
                    IAAC.i_avi_account_car_alias nombre,
                    OAA.o_avi_account_user_id a_to,
                    IAAC.i_avi_account_car_id c_to,
                    IAAC.i_avi_account_car_privacy privacidad,
                    IF(IAAC.i_avi_account_car_privacy IN (1,2) AND (OAA.o_avi_account_type_id=1 OR AUFU.a_user_follower_user_id=$searcher OR OAA.o_avi_account_user_id=$searcher) , OAA.o_avi_account_name, NULL) ownerName,
                    (SELECT a_avi_car_img_car img FROM a_avi_car_img WHERE a_avi_car_img_account_car_id=IAAC.i_avi_account_car_id LIMIT 1) img,
                    IF(OAA.o_avi_account_user_id=$searcher, 1, 0) owner,
                    IF(AUFU.a_user_follower_user_id IS NULL, 0, 1) followOwner,
                    IF(AUFA.a_user_follower_acc_user_id IS NULL, 0, 1) followGarage,
                    IF(AUFC.a_user_follower_acc_user_id IS NULL, 0, 1) followCar,
                    IFNULL(AASC.a_avi_sell_detaill_price,0) precio,
                    AASC.a_avi_sell_car_currency currency, OACA.o_avi_car_ad_sold vendido,
                    IAAC.i_avi_account_car_verified verifiedCar,
                    OAA.o_avi_account_verified verifiedGarage,
                    OAU.o_avi_user_verified verifiedUser
                FROM o_avi_account OAA
                LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
                LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_account_id=OAA.o_avi_account_id
                LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_following_user_id=OAA.o_avi_account_user_id AND AUFU.a_user_follower_user_id=$searcher
                LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
                LEFT JOIN a_user_follow_account AUFA ON AUFA.a_user_following_account_id=OAA.o_avi_account_id AND AUFA.a_user_follower_acc_user_id=$searcher
                LEFT JOIN a_user_follow_car AUFC ON AUFC.a_user_following_i_car_id=IAAC.i_avi_account_car_id AND AUFC.a_user_follower_acc_user_id=$searcher
                LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id
                LEFT JOIN o_avi_user OAU on OAU.o_avi_user_id=OAA.o_avi_account_user_id
                LEFT JOIN o_avi_car_ad OACA ON IAAC.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
                WHERE 
                (OAU.o_avi_user_status=1 OR OAU.o_avi_user_id=$searcher) AND 
                IAAC.i_avi_account_car_status=1 AND
                (
                	IAAC.i_avi_account_car_privacy IN (1,2)
                	OR (IAAC.i_avi_account_car_privacy=3 AND AUFC.a_user_follower_acc_user_id IS NOT NULL)
                    OR $searcher=OAA.o_avi_account_user_id
                )
                AND (
                    LOWER(IAAC.i_avi_account_car_alias) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_brand) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_subbrand) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_model) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_version) LIKE '%$search%' 
                    OR (((IAAC.i_avi_account_car_privacy IN (1,2) AND OAA.o_avi_account_type_id=1) OR AUFU.a_user_follower_user_id=$searcher OR OAA.o_avi_account_user_id=$searcher) AND LOWER(OAA.o_avi_account_name) LIKE '%$search%' )
                ) AND i_avi_account_car_status=1 AND  o_avi_account_status=1
                AND OAU.o_avi_user_id NOT IN ($notin)
                ORDER BY owner DESC, followGarage DESC, followOwner DESC, verifiedCar DESC, verifiedGarage DESC, verifiedUser DESC, nombre
                LIMIT $inicio, 10";
        //echo $query;
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                    $row["precio"]=number_format($row["precio"],0);
                    $coder -> encode($row["a_to"]);
                    $row["a_to"] = $coder-> encoded;
                    $coder -> encode($row["c_to"]);
                    $row["c_to"] = $coder-> encoded;
                    $users[]=$row;
                }
            }
        }
        $db->close();
        return $users;
    }
    function UserAccessToInstance($user,$instance){
        $database=new Database;
        $db=$database->connect();
        $hasAccess=false;
        $query="SELECT i_avi_account_car_privacy privacidad, o_avi_account_user_id user, o_avi_account_id garage FROM i_avi_account_car IAAC LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id WHERE i_avi_account_car_id=$instance";
        //echo $query;
        $dataGarage=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $dataGarage=$row;
                }
            }
        }
        if(!empty($dataGarage)){
            if($dataGarage["user"]==$user){
                $hasAccess=true;
            }
            elseif($dataGarage["privacidad"]==2){
                $hasAccess=true;
            }
            elseif($dataGarage["privacidad"]==1){
                $garage=$dataGarage["garage"];
                $query="SELECT a_user_follower_acc_user_id FROM a_user_follow_account WHERE a_user_follower_acc_user_id=$user AND a_user_following_account_id=$garage";
                    if($data=$db->query($query)){
                        if($data->num_rows>0){
                            $hasAccess=true;
                        }
                    }
            }
        }
        $db->close();
        return $hasAccess;
    }
    function getImgsCar($car){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT AACI.a_avi_car_img_car img FROM a_avi_car_img AACI WHERE AACI.a_avi_car_img_account_car_id=$car ORDER BY a_avi_car_img_type DESC ";
        $imagenes=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $imagenes[]=$row["img"];
                }
            }
        }
        $db->close();
        return $imagenes;
    }
    function getInfoCar($brand=null,$subbrand=null,$model=null,$version=null){
        $versionObject=new version;
        if($version){
            $auto=$versionObject->getVersionById($version);
        }
        elseif($model){
            $auto=$versionObject->getModelById($model);
        }
        elseif($subbrand){
            $auto=$versionObject->getSubBrandById($subbrand);
        }
        elseif($brand){
            $auto=$versionObject->getBrandById($brand);
        }
        else{
            $auto=array();
        }
        return $auto;
    }
    function getAdvertisementById($idAd){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT OACA.o_avi_car_ad_id idAd, 
            OACA.o_avi_car_ad_car_id auto, 
            OACA.o_avi_car_ad_text texto, 
            OACA.o_avi_car_ad_status estatus, 
            AASC.a_avi_sell_detaill_price precio, 
            AASC.a_avi_sell_car_currency currency,
            OAC.o_avi_car_version_id version, 
            OAC.o_avi_car_brand_id marca, 
            OAC.o_avi_car_subbrand_id submarca, 
            OAC.o_avi_car_model_id modelo, 
            OAC.o_avi_car_name_brand nombreMarca, 
            OAC.o_avi_car_name_subbrand nombreSubmarca, 
            OAC.o_avi_car_name_model nombreModelo,
            OAC.o_avi_car_name_model nombreVersion,
            IAAC.i_avi_account_car_alias alias, 
            AACAC.a_avi_car_ad_contact_phone phone, 
            AACAC.a_avi_car_ad_contact_email mail, 
            OAAC.o_avi_account_user_id ownerid
            FROM o_avi_car_ad OACA
            LEFT JOIN a_avi_car_ad_contact AACAC ON AACAC.a_avi_car_o_ad_id=OACA.o_avi_car_ad_id
            LEFT JOIN a_avi_car_ad_location AACAL ON AACAL.a_avi_car_o_ad_id=OACA.o_avi_car_ad_id
            LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
            LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id
            LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
            LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=IAAC.i_avi_account_car_account_id
            WHERE OACA.o_avi_car_ad_id=$idAd AND AASC.a_avi_sell_detaill_price IS NOT NULL AND i_avi_account_car_status=1 AND o_avi_account_status=1";
        $anuncio=array();
        $coder=new Coder;
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $anuncio=$row;
                    $coder->encode($row["idAd"]);
                    $anuncio["link"]=$coder->encoded;
                }
            }
        }
        if(!empty($anuncio)){
            $marca=null;
            if($anuncio["marca"]!=""){
                $marca=$anuncio["marca"];
            }
            $submarca=null;
            if($anuncio["submarca"]!=""){
                $submarca=$anuncio["submarca"];
            }
            $modelo=null;
            if($anuncio["modelo"]!=""){
                $modelo=$anuncio["modelo"];
            }
            $version=null;
            if($anuncio["version"]!=""){
                $version=$anuncio["version"];
            }
            $anuncio["imagenes"]=$this->getImgsCar($anuncio["auto"]);
            $anuncio["auto"]=$this->getInfoCar($marca,$submarca,$modelo,$version); 
        }
        $db->close();
        return $anuncio;
    }
    function getCarFollowers($idCar){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT f_avi_user_liker_id liker FROM f_avi_user_like WHERE f_avi_user_car_id=$idCar";
        $likers=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $likers[]=$row["liker"];
                }
            }
        }
        $db->close();
        //print_r($likers);
        return $likers;
    }
     function getAdFollowers($idAd){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT f_avi_user_liker_id liker FROM f_avi_user_like WHERE f_avi_user_car_ad_id=$idAd";
        $likers=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $likers[]=$row["liker"];
                }
            }
        }
        $db->close();
        return $likers;
    }
    function getInfoinstance($idInstance){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT IAAC.i_avi_account_car_alias alias, OAC.o_avi_car_name_brand marca, OAC.o_avi_car_name_subbrand submarca, OAC.o_avi_car_name_model model, OAC.o_avi_car_name_version version, OAUD.o_avi_userdetail_name nombre, OAUD.o_avi_userdetail_last_name apellido, OAU.o_avi_user_email mail, CAZ.c_avi_zipcode_id cp, AAUA.a_avi_useraddress_street calle, CAS.c_avi_state_name estado, CAC.c_avi_country_name pais, OAU.o_avi_user_id idUser, CAZ.c_avi_zipcode_city municipio, OAUD.o_avi_userdetail_birth_date nac, OAUD.o_avi_userdetail_cellphone telefono, OAUD.o_avi_userdetail_cellphone_code codephone, IAAC.i_avi_account_car_privacy privacidad, OAA.o_avi_account_id garageId
            FROM i_avi_account_car IAAC 
            LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id
            LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAA.o_avi_account_user_id
            LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
            LEFT JOIN a_avi_useraddress AAUA ON AAUA.a_avi_useraddress_id_user=OAU.o_avi_user_id
            LEFT JOIN c_avi_zipcode CAZ ON CAZ.c_avi_zipcode_id=AAUA.a_avi_useraddress_zip_code
            LEFT JOIN c_avi_state CAS ON CAS.c_avi_state_id=CAZ.c_avi_zipcode_id_state
            LEFT JOIN c_avi_country CAC ON CAC.c_avi_country_id=CAZ.c_avi_zipcode_id_country
            WHERE IAAC.i_avi_account_car_id=$idInstance";
            //echo $query;
        $instance=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $instance=$row;
                }
            }
        }
        $db->close();
        return $instance;
    }
    function changeGarage($car,$garage,$idInstancia){
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE i_avi_account_car SET i_avi_account_car_account_id=$garage WHERE i_avi_account_car_id=$car";
        $return=false;
        if($db->query($query)){
            $return=true;
            $sql = "UPDATE i_avi_account_car SET i_avi_account_car_verified = 0 WHERE i_avi_account_car_car_id = $idInstancia";
            $db->query($sql);
        }
        return $return;
    }
    function getAllSharablesCarsByUser($idUser){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($idUser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $query="SELECT IAAC.i_avi_account_car_alias alias, IAAC.i_avi_account_car_id id, OAC.o_avi_car_name_brand marca, OAC.o_avi_car_name_subbrand sumbarca, OAC.o_avi_car_name_model modelo, OAC.o_avi_car_name_version version, OAA.o_avi_account_name, IF(OAA.o_avi_account_user_id=$idUser, 1, 0) owner, (SELECT a_avi_car_img_car img FROM a_avi_car_img WHERE a_avi_car_img_account_car_id=IAAC.i_avi_account_car_id LIMIT 1) img
            FROM a_avi_user_account AAUA
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=AAUA.a_avi_account_id
            LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_account_id=OAA.o_avi_account_id
            LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id
            WHERE AAUA.a_avi_user_id=$idUser AND AAUA.status=1 AND AAUA.a_avi_user_account_level<=3 AND IAAC.i_avi_account_car_id IS NOT NULL AND OAA.o_avi_account_status=1 AND OAA.o_avi_account_user_id NOT IN ($notin)";
        $cars=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $cars[]=$row;
                }
            }
        }
        $db->close();
        return $cars;
    }
    function CarsRecomended($user){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $sugerencias=array();
        $query="SELECT IAAC.i_avi_account_car_id id, IAAC.i_avi_account_car_alias alias, OAC.o_avi_car_name_brand marca, OAC.o_avi_car_name_subbrand submarca, OAC.o_avi_car_name_model modelo, OAC.o_avi_car_name_version version, (SELECT a_avi_car_img_car img FROM a_avi_car_img WHERE a_avi_car_img_account_car_id=IAAC.i_avi_account_car_id LIMIT 1) img, OAA.o_avi_account_name garageName, OAA.o_avi_account_id garageId, OAU.o_avi_user_id userId, OAUD.o_avi_userdetail_name userName, OAUD.o_avi_userdetail_last_name userLastName, AAUP.a_avi_user_perfil_privacy userPrivacy, OAA.o_avi_account_type_id garagePrivacy, AASC.a_avi_sell_detaill_price precio, AASC.a_avi_sell_car_currency moneda, IF(OACA.o_avi_car_ad_id IS NULL, 0, 1) enVenta
            FROM i_avi_account_car IAAC 
            LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id 
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id
            LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAA.o_avi_account_user_id
            LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
            LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
            LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
            LEFT JOIN o_avi_car_ad OACA ON OACA.o_avi_car_ad_car_id=IAAC.i_avi_account_car_id AND OACA.o_avi_car_ad_status=1
            WHERE OAA.o_avi_account_user_id <> $user AND IAAC.i_avi_account_car_id NOT IN (SELECT a_user_following_i_car_id FROM a_user_follow_car WHERE a_user_follower_acc_user_id=$user) AND ( IAAC.i_avi_account_car_privacy = 1 OR OACA.o_avi_car_ad_status=1 ) AND OAU.o_avi_user_id NOT IN ($notin)
            ORDER BY RAND()
            LIMIT 10";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $sugerencias[]=$row;
                }
            }
        }
        $db->close();
        return $sugerencias;
    }
    function getPublicAdvertisementByPublication($start=0){
        $database=new Database;
        $db=$database->connect();
        $inicio=$start;
        $fin=$start+10;
        $query="SELECT 
            OAP.o_avi_publication_id idPublicacion,
            OAP.o_avi_publication_price_ad precio,
            OAP.o_avi_publication_currency_ad currency,  
            OAP.o_avi_publication_author_user usuarioAutor, 
            OAP.o_avi_publication_author_garage cuentaAutor,
            OAP.o_avi_publication_content_auto autoDestino,
            OAP.o_avi_publication_time fecha, 
            OAP.o_avi_publication_modification_time modificacion,
            OAP.o_avi_publication_url url,
            OAP.o_avi_publication_imgs imagenes,
            CAPT.c_avi_publication_type_description tipo, 
            OAAC.o_avi_account_name authoGarage,
            AAA.a_avi_accountdetail_avatar_img authorGarageImg,
            OACA.o_avi_car_ad_id idAd, 
            OACA.o_avi_car_ad_car_id auto, 
            OACA.o_avi_car_ad_text texto, 
            OACA.o_avi_car_ad_status estatus,
            OAC.o_avi_car_version_id version, 
            OAC.o_avi_car_brand_id marca, 
            OAC.o_avi_car_subbrand_id submarca, 
            OAC.o_avi_car_model_id modelo, 
            OAC.o_avi_car_name_brand nombreMarca, 
            OAC.o_avi_car_name_subbrand nombreSubmarca, 
            OAC.o_avi_car_name_model nombreModelo,
            OAC.o_avi_car_name_model nombreVersion,
            IAAC.i_avi_account_car_alias alias, 
            (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
            (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds
            FROM o_avi_publication OAP
            LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type
            LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user
            LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
            LEFT JOIN o_avi_user OAU ON OAP.o_avi_publication_author_user=OAU.o_avi_user_id
            LEFT JOIN a_avi_user_perfil AAUP ON OAU.o_avi_user_id=AAUP.a_avi_user_id
            LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
            LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
            LEFT JOIN o_avi_car_ad OACA ON OACA.o_avi_car_ad_car_id =OAP.o_avi_publication_content_auto
            LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OAP.o_avi_publication_content_auto
            LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id
            WHERE OAP.o_avi_publication_price_ad IS NOT NULL 
            AND OAP.o_avi_publication_type = 5 
            AND OAP.o_avi_publication_status = 1
            AND OAAC.o_avi_account_status=1 
            AND OACA.o_avi_car_ad_sold=0 
            ORDER BY OAP.o_avi_publication_id DESC
            LIMIT $inicio, 10";
        $anuncio=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $anuncio[]=$row;
                }
            }
        }
        $db->close();
        return $anuncio;
    }

    function getTotalUsersId()
    {
        $database=new Database;
        $db=$database->connect();
        $query="SELECT  o_avi_user_id userid FROM o_avi_user WHERE o_avi_user_status= 1";
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

	function __destruct(){
		$this->intanceByCar=null;
	}
}