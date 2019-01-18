<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-28 12:58:12
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-20 12:34:39
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/auto.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';
require_once $_SERVER["DOCUMENT_ROOT"]. "/php/Utilities/coder.php";
class Anuncio extends Auto
{
	
	function commentAd($idAd, $text, $usuario, $account=null)
	{
		$database=new Database;
        $db=$database->connect();
        $into="f_avi_ad_comment_ad, f_avi_ad_comment_text, f_avi_ad_comment_user";
        $values="$idAd, '$text', $usuario";
        if($account)
        {
        	$into.=", f_avi_ad_comment_account";
        	$values.=", $account";
        }
        $query="INSERT INTO f_avi_ad_comment ($into) VALUES ($values)";
        $ret=false;
		if($db->query($query)){
            $ret=$db->insert_id;
        }
        $db->close();
        return $ret;
	}
	function updateCommentAd($commentId, $text, $usuario, $account=null)
	{
		$database=new Database;
        $db=$database->connect();
        $query="UPDATE f_avi_ad_comment SET f_avi_ad_comment_text='$text' WHERE f_avi_ad_comment_id=$commentId";
        $ret=false;
		if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
	}
	function getCommentAd($idAd)
	{
		$database=new Database;
        $db=$database->connect();
        $usr=0;
        if(!empty($_SESSION))
            $usr=$_SESSION["iduser"];
		$query="
			SELECT f_avi_ad_comment_id commentId,
			IF(f_avi_ad_comment_account IS NULL ,1,2) type, 
			f_avi_ad_comment_text comentario,
			f_avi_ad_comment_user authorUser,
			f_avi_ad_comment_account cuenta,
			f_avi_ad_comment_time fecha,
			f_avi_ad_comment_account authorGarage,
			IF(f_avi_ad_comment_account IS NULL , (
				IF(AAUP.a_avi_user_perfil_privacy=1, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name),OAU.o_avi_user_username)
			), OAA.o_avi_account_name) author,
			IF(f_avi_ad_comment_account IS NULL ,AAUP.a_avi_user_perfil_avatar, AAA.a_avi_accountdetail_avatar_img) imgAuthor,
			(SELECT COUNT(f_avi_ad_comment_id) FROM f_avi_ad_comment WHERE f_avi_ad_comment_ad='$idAd') conteo,
			(SELECT COUNT(f_avi_ad_comment_id) FROM f_avi_ad_comment WHERE f_avi_ad_comment_ad='$idAd' AND f_avi_ad_comment_user=$usr) icomment
			FROM f_avi_ad_comment
			LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=f_avi_ad_comment.f_avi_ad_comment_user
			LEFT JOIN o_avi_userdetail OAUD ON OAU.o_avi_user_id=OAUD.o_avi_userdetail_id_user
			LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
			LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=f_avi_ad_comment.f_avi_ad_comment_account
			LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=f_avi_ad_comment.f_avi_ad_comment_account
			WHERE f_avi_ad_comment_ad='$idAd' AND f_avi_ad_comment_status=1
			ORDER BY fecha";
		$comentarios=array();
		$queryDB=$db->query($query);
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$comentarios[]=$row;
			}
		}
		$db->close();
		return $comentarios;
	}
	function getOwnerAd($idAd)
	{
		$database=new Database;
        $db=$database->connect();
        $query="SELECT o_avi_account_user_id owner, o_avi_account_id garage 
        FROM o_avi_account 
        WHERE o_avi_account_id = (SELECT i_avi_account_car_account_id FROM i_avi_account_car WHERE i_avi_account_car_id=(SELECT o_avi_car_ad_car_id FROM o_avi_car_ad WHERE o_avi_car_ad_id=$idAd)) ";
        $infoPost=array();
        if($data=$db->query($query)){
        	if($data->num_rows>0){
        		while ($row=$data->fetch_assoc()) {
        			$infoPost=$row;
        		}
        	}
        }
        $db->close();
        return $infoPost;
	}
	function getCarbyAd($idAd)
	{
		$database=new Database;
        $db=$database->connect();
        $query="SELECT o_avi_car_ad_car_id auto FROM o_avi_car_ad WHERE o_avi_car_ad_id=$idAd";
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
	function getShares($idAd){
		$database=new Database;
        $db=$database->connect();
        $query="SELECT count(f_avi_share_id) cuantos FROM f_avi_share WHERE f_avi_share_ad=$idAd";
        $cuantos=0;
        if($data=$db->query($query)){
        	if($data->num_rows>0){
        		while ($row=$data->fetch_assoc()) {
        			$cuantos=$row["cuantos"];
        		}
        	}
        }
        $db->close();
        return $cuantos;
	}
	function deleteAdbyCar($idCar)
	{
		$database=new Database;
        $db=$database->connect();
        $query="DELETE FROM o_avi_car_ad WHERE o_avi_car_ad_car_id=$idCar";
       	$ret=false;
		if($db->query($query))
		{
            $ret=true;
        }
        $db->close();
        return $ret;
	}
    function hideAd($idAd)
    {
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE o_avi_car_ad SET o_avi_car_ad_status='0' WHERE o_avi_car_ad_id=$idAd AND  o_avi_car_ad_status=1";
        $ret=false;
        if($db->query($query))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function hideAdSellCar($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE a_avi_sell_car SET a_avi_sell_car_status='0' WHERE a_avi_sell_car_account_car_id = '$idCar'  AND  a_avi_sell_car_status=1";
        $ret=false;
        if($db->query($query))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function hideAdPublication($idUser, $idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE o_avi_publication SET o_avi_publication_status='0' WHERE o_avi_publication_author_user = '$idUser'  AND  o_avi_publication_status=1 AND o_avi_publication_type=5 AND o_avi_publication_content_auto='$idCar'";
        $ret=false;
        if($db->query($query))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function deleteAdPublicationbyId($idUser, $idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM o_avi_publication WHERE o_avi_publication_type=5 AND o_avi_publication_author_user=$idUser AND o_avi_publication_content_auto=$idCar";
        $ret=false;
        if($db->query($query))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function deleteAdbyId($id)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM o_avi_car_ad WHERE o_avi_car_ad_id=$id";
        $ret=false;
        if($db->query($query))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
	function deleteAdContact($idAd)
	{
		$database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_avi_car_ad_contact WHERE a_avi_car_o_ad_id=$idAd";
       	$ret=false;
		if($db->query($query))
		{
            $ret=true;
        }
        $db->close();
        return $ret;
	}
	function deleteAdLocation($idAd)
	{
		$database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_avi_car_ad_location WHERE a_avi_car_o_ad_id=$idAd";
       	$ret=false;
		if($db->query($query))
		{
            $ret=true;
        }
        $db->close();
        return $ret;
	}
    function deleteAdCommets($idAd)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM f_avi_ad_comment WHERE f_avi_ad_comment_ad='$idAd';";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function deleteAdLikes($idAd)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM f_avi_user_like WHERE f_avi_user_car_ad_id='$idAd';";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function deleteAdFollow($idAd)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_avi_user_follow_ad WHERE a_avi_user_following_ad_id='$idAd';";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function getAnunciosByUser($idUser){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT DISTINCT OACA.o_avi_car_ad_id anuncio, 
            IAAC.i_avi_account_car_id autoId, 
            IAAC.i_avi_account_car_alias auto, 
            OAA.o_avi_account_name garage, 
            IFNULL(AASC.a_avi_sell_detaill_price, 0) precio, 
            OACA.o_avi_car_ad_since publicacion, 
            OACA.o_avi_car_ad_sold vendido
            FROM o_avi_car_ad OACA
            LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id
            LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
            WHERE OAA.o_avi_account_user_id=$idUser AND o_avi_car_ad_status=1 AND IAAC.i_avi_account_car_status=1 AND OAA.o_avi_account_status=1
            ORDER BY auto";
        $anuncios=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $anuncios[]=$row;
                }
            }
        }
        $db->close();
        return $anuncios;

    }
    function getAnunciosByGarage($idGarage){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT OACA.o_avi_car_ad_id anuncio, IAAC.i_avi_account_car_id autoId, IAAC.i_avi_account_car_alias auto, IFNULL(AASC.a_avi_sell_detaill_price, 0) precio, OACA.o_avi_car_ad_since publicacion, OACA.o_avi_car_ad_sold vendido
            FROM o_avi_car_ad OACA
            LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
            LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
            WHERE IAAC.i_avi_account_car_account_id=$idGarage AND o_avi_car_ad_status=1 AND IAAC.i_avi_account_car_status=1
            ORDER BY auto";
        $anuncios=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $anuncios[]=$row;
                }
            }
        }
        $db->close();
        return $anuncios;

    }
    function getAnunciosByCar($idCar){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT OACA.o_avi_car_ad_id anuncio, IAAC.i_avi_account_car_id autoId, IAAC.i_avi_account_car_alias auto, IFNULL(AASC.a_avi_sell_detaill_price, 0) precio, OACA.o_avi_car_ad_since publicacion, OACA.o_avi_car_ad_sold vendido
            FROM o_avi_car_ad OACA
            LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
            LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
            WHERE IAAC.i_avi_account_car_id=$idCar AND o_avi_car_ad_status=1 AND IAAC.i_avi_account_car_status=1
            ORDER BY auto";
        $anuncios=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $anuncios[]=$row;
                }
            }
        }
        $db->close();
        return $anuncios;

    }
    function anuncioVendido($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE o_avi_car_ad SET o_avi_car_ad_sold='1' WHERE o_avi_car_ad_car_id =$idCar";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function getAdsForSearch($search=null,$searcher=null,$time=0){
        $users=array();
        $search=strtolower($search);
        if(!$search||$search==="")
        {
            throw new Exception("Empty search", 1);
            
        }
        $inicio=10*$time;
        $database=new Database;
        $db=$database->connect();
        $Instancia=new Instancia;
        $notAllowedUsers=$Instancia->notAccesibleUsers($searcher);
        $notin="0";
        foreach ($notAllowedUsers as $u => $user) {
            $notin.=",$user";
        }
        $query="SELECT
                    OACA.o_avi_car_ad_id idAd, 
                    OACA.o_avi_car_ad_car_id auto, 
                    OACA.o_avi_car_ad_text texto, 
                    OACA.o_avi_car_ad_status estatus, 
                    OACA.o_avi_car_ad_since publicacion, 
                    AASC.a_avi_sell_detaill_price precio, 
                    AASC.a_avi_sell_car_currency currency,
                    OAA.o_avi_account_user_id owner, 
                    OAC.o_avi_car_name_brand nombreMarca, 
                    OAC.o_avi_car_name_subbrand nombreSubmarca, 
                    OAC.o_avi_car_name_model nombreModelo,
                    OAC.o_avi_car_name_version nombreVersion,
                    (SELECT a_avi_car_img_car img FROM a_avi_car_img WHERE a_avi_car_img_account_car_id=IAAC.i_avi_account_car_id LIMIT 1) img,
                    IF(OAA.o_avi_account_user_id=$searcher, 1, 0) owner,
                    IF(AUFU.a_user_follower_user_id IS NULL, 0, 1) followOwner,
                    IF(AUFA.a_user_follower_acc_user_id IS NULL, 0, 1) followGarage,
                    IF(OAA.o_avi_account_type_id IN (1,2), OAA.o_avi_account_name, '') nameGarage,
                    IAAC.i_avi_account_car_verified verifiedCar,
                    OAA.o_avi_account_verified verifiedGarage,
                    OAU.o_avi_user_verified verifiedUser,
                    AACAL.a_avi_car_ad_location_zipcode cp,
                    CAZ.c_avi_zipcode_city municipio,
                    CAS.c_avi_state_name state
                FROM o_avi_car_ad OACA
                LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
                LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id
                LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
                LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_following_user_id=OAA.o_avi_account_user_id AND AUFU.a_user_follower_user_id=$searcher
                LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
                LEFT JOIN a_user_follow_account AUFA ON AUFA.a_user_following_account_id=OAA.o_avi_account_id AND AUFA.a_user_follower_acc_user_id=$searcher
                LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id
                LEFT JOIN o_avi_user OAU on OAU.o_avi_user_id=OAA.o_avi_account_user_id
                LEFT JOIN a_avi_car_ad_location AACAL ON AACAL.a_avi_car_o_ad_id=OACA.o_avi_car_ad_id
                LEFT JOIN c_avi_zipcode CAZ ON CAZ.c_avi_zipcode_id=AACAL.a_avi_car_ad_location_zipcode
                LEFT JOIN c_avi_state CAS ON CAS.c_avi_state_id=CAZ.c_avi_zipcode_id_state
                WHERE 
                OACA.o_avi_car_ad_sold=0 AND 
                (OAU.o_avi_user_status=1 OR OAU.o_avi_user_id=$searcher) AND 
                (
                    LOWER(IAAC.i_avi_account_car_alias) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_brand) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_subbrand) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_model) LIKE '%$search%' 
                    OR LOWER(OAC.o_avi_car_name_version) LIKE '%$search%' 
                    OR (((IAAC.i_avi_account_car_privacy IN (1,2) AND OAA.o_avi_account_type_id=1) OR AUFU.a_user_follower_user_id=$searcher OR OAA.o_avi_account_user_id=$searcher) AND LOWER(OAA.o_avi_account_name) LIKE '%$search%' )
                ) AND i_avi_account_car_status=1 AND o_avi_account_status=1 AND OACA.o_avi_car_ad_status=1 AND OACA.o_avi_car_ad_sold=0
                AND OAU.o_avi_user_id NOT IN ($notin)
                ORDER BY owner DESC, followGarage DESC, followOwner DESC, verifiedCar DESC, verifiedGarage DESC, verifiedUser DESC, publicacion DESC
                LIMIT $inicio, 10";
        //echo $query;
        $coder=new Coder;
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                    $coder->encode($row["idAd"]);
                    $row["precio"]=number_format($row["precio"],0);
                    $row["link"]=$coder->encoded;

                    $users[]=$row;
                
                }
            }
        }
        $db->close();
        return $users;
    }
    function getCommentByID($idComment){
        $database=new Database;
        $db=$database->connect();
        $usr=0;
        if(!empty($_SESSION))
            $usr=$_SESSION["iduser"];
        $query="
            SELECT f_avi_ad_comment_id idComment,
            f_avi_ad_comment_ad adv,
            IF(f_avi_ad_comment_account IS NULL ,1,2) type, 
            f_avi_ad_comment_text comentario,
            f_avi_ad_comment_user authorUser,
            f_avi_ad_comment_account cuenta,
            f_avi_ad_comment_time fecha,
            f_avi_ad_comment_account authorGarage,
            IF(f_avi_ad_comment_account IS NULL , (
                IF(AAUP.a_avi_user_perfil_privacy=1, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name),OAU.o_avi_user_username)
            ), OAA.o_avi_account_name) author,
            IF(f_avi_ad_comment_account IS NULL ,AAUP.a_avi_user_perfil_avatar, AAA.a_avi_accountdetail_avatar_img) imgAuthor    
            FROM f_avi_ad_comment
            LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=f_avi_ad_comment.f_avi_ad_comment_user
            LEFT JOIN o_avi_userdetail OAUD ON OAU.o_avi_user_id=OAUD.o_avi_userdetail_id_user
            LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=f_avi_ad_comment.f_avi_ad_comment_account
            LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=f_avi_ad_comment.f_avi_ad_comment_account
            WHERE f_avi_ad_comment_id='$idComment'";
        $comentarios=array();
        $queryDB=$db->query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $comentarios=$row;
            }
        }
        $db->close();
        return $comentarios;
    }
    function isUserOwnerComment($user,$comment){
        $comentario=$this->getCommentByID($comment);
        if($comentario["authorUser"]==$user){
            return true;
        }
        return false;
    }
    function editComment($comment,$text){
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE f_avi_ad_comment SET  f_avi_ad_comment_text='$text' WHERE  f_avi_ad_comment_id=$comment";
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;

    }
    function deleteComment($comment){
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE f_avi_ad_comment SET  f_avi_ad_comment_status=0 WHERE  f_avi_ad_comment_id=$comment";
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function advancedSearch($time=0,$marca=0,$modelo=0,$ano=0,$clase=0,$min=0,$max=0,$state=0,$town=0){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT IAAC.i_avi_account_car_alias alias, OAC.o_avi_car_brand_id marca_id, OAC.o_avi_car_subbrand_id submarca_id, OAC.o_avi_car_model_id modelo_id, OAC.o_avi_car_version_id version_id, OAC.o_avi_car_name_brand marca, OAC.o_avi_car_name_subbrand submarca, OAC.o_avi_car_name_model modelo, OAC.o_avi_car_name_version version, AASC.a_avi_sell_detaill_price precio, AASC.a_avi_sell_car_currency moneda, OACAL.a_avi_car_ad_location_suburb pueblo, OACAL.a_avi_car_ad_location_zipcode cp, CAZ.c_avi_zipcode_city ciudad, CAZ.c_avi_zipcode_id_state estado, CAZ.c_avi_zipcode_id_country pais, CAS.c_avi_state_name estado_nombre, OAC.o_avi_car_class_id clase, OACA.o_avi_car_ad_id ad_id, (SELECT a_avi_car_img_car img FROM a_avi_car_img WHERE a_avi_car_img_account_car_id=IAAC.i_avi_account_car_id LIMIT 1) img, IF(OAA.o_avi_account_type_id IN (1,2), OAA.o_avi_account_name, '') nameGarage
            FROM o_avi_car_ad OACA
            LEFT JOIN a_avi_car_ad_location OACAL ON OACAL.a_avi_car_o_ad_id=OACA.o_avi_car_ad_id
            LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id
            LEFT JOIN o_avi_car OAC ON OAC.o_avi_car_id=IAAC.i_avi_account_car_car_id
            LEFT JOIN a_avi_sell_car AASC ON AASC.a_avi_sell_car_account_car_id=IAAC.i_avi_account_car_id AND AASC.a_avi_sell_car_status=1
            LEFT JOIN c_avi_zipcode CAZ ON CAZ.c_avi_zipcode_id=OACAL.a_avi_car_ad_location_zipcode
            LEFT JOIN c_avi_state CAS ON CAS.c_avi_state_id=CAZ.c_avi_zipcode_id_state
            WHERE OACA.o_avi_car_ad_sold=0";
        if($ano){
            $query.=" AND OAC.o_avi_car_model_id = $ano";
        }
        elseif($modelo){
            $query.=" AND OAC.o_avi_car_subbrand_id = $modelo";
        }
        elseif($marca){
            $query.=" AND OAC.o_avi_car_brand_id = $marca";
        }
        if($clase){
            $query.=" AND OAC.o_avi_car_class_id = $clase";
        }
        if($min){
            $query.=" AND AASC.a_avi_sell_detaill_price>=$min";
        }
        if($max){
            $query.=" AND AASC.a_avi_sell_detaill_price<=$max";
        }
        if($state){
            $query.=" AND CAS.c_avi_state_id='$state'";
        }
        if($town){
            $query.=" AND CAZ.c_avi_zipcode_city='$town'";
        }
        $query.=" LIMIT $time,10";
        $autos=array();
        //echo $query;
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while($row = $data->fetch_assoc()){
                    $autos[]=$row;
                }
            }
        }
        $db->close();
        return $autos;
    }
}