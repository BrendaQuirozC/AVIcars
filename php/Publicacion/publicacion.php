<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-09 10:02:01
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-17 16:27:45
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Follow/Seguidor.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/Garage.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/metatags.php";

class Publicacion{

	private $id=null;
	public $author=null;
	public $authorType=null;
	public $container=null;
	public $containerType=null;
	public $imagenes=null;
	public $texto=null;
	function __construct($idPublicacion=null){
		if($idPublicacion)
		{
			$this->id=$idPublicacion;
			$publicacion=array();
			try{
				$publicacion=$this->getPublicationByID($idPublicacion);
			}
			catch(Exception $e){
				throw new Exception("Error: ".$e->getMessage());
				
			}
			$this->author=$publicacion["usuarioAutor"];
			$this->authorType=$publicacion["cuentaAutor"];
			$this->container=$publicacion["usuarioDestino"];
			$this->containerType=$publicacion["cuentaDestino"];
			$this->imagenes=$publicacion["imagenes"];
			$this->texto=$publicacion["texto"];
		}
	}
	public function create($id=null){
		$publicacion=array();
		try{
			$publicacion=$this->getPublicacionById($id);
		}
		catch(Exception $e){
			throw new Exception("Error: ".$e->getMessage());
			
		}
		$this->id=$id;
		$this->author=$publicacion["usuarioAutor"];
		$this->authorType=$publicacion["cuentaAutor"];
		$this->container=$publicacion["usuarioDestino"];
		$this->containerType=$publicacion["cuentaDestino"];
		return true;
	}
	public function getPublicacionById($id=null)
	{
        $database=new Database;
        $db=$database->connect();
		if(!$id){
			$id=$this->id;
		}
		if(!$id)
		{
			throw new Exception("La Publicacion no existe");
		}
		$publicacion=array();
		$watchuser=$_SESSION["iduser"];
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($watchuser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				OAP.o_avi_publication_url url,
				CAPT.c_avi_publication_type_description tipo, 
				OAUD.o_avi_userdetail_name authorName, 
				OAUD.o_avi_userdetail_last_name authorLastName,
                OAU.o_avi_user_verified userverified,
                AAUP.a_avi_user_perfil_avatar imgAuthor,
                OAU.o_avi_user_username usernameAuthor,
                OAA.o_avi_account_name authoGarage, 
                OAA.o_avi_account_verified garageverified, 
				AAA.a_avi_accountdetail_avatar_img authorGarageImg,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$watchuser) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$watchuser AND f_avi_publication_comment_status=1) icomment
				FROM o_avi_publication OAP 
				LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAP.o_avi_publication_content_user
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAU.o_avi_user_id AND AUFUA.a_user_follower_user_id=$watchuser AND AUFUA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$watchuser AND AUFAA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$watchuser AND AUFUC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$watchuser AND AUFAC.a_user_follow_acepted=1
				WHERE (
						((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAA.o_avi_account_type_id=2)
                		OR ((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUP.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL))
                		)
	                	AND (
	                		(AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR ((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL) ) 
	                	))
	                	OR OAP.o_avi_publication_author_user=$watchuser
	                	OR OAP.o_avi_publication_content_user=$watchuser
	                )
	                AND o_avi_publication_id=$id AND OAP.o_avi_publication_status=1
				AND OAU.o_avi_user_id not in ($notin)
				ORDER BY fecha DESC";
		//echo $query;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$publicacion=$row;
				}
			}
		}
		if(empty($publicacion))
		{
			throw new Exception("La Publicacion no existe");
			
		}
		return $publicacion;
	}
	public function getAllPublicationsByUser($user,$start=0){
		$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
		$watchuser=$_SESSION["iduser"];
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($watchuser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				OAP.o_avi_publication_url url,
				CAPT.c_avi_publication_type_description tipo, 
				OAUD.o_avi_userdetail_name authorName, 
				OAUD.o_avi_userdetail_last_name authorLastName,
				OAU.o_avi_user_verified userverified,
                AAUP.a_avi_user_perfil_avatar imgAuthor,
                OAU.o_avi_user_username usernameAuthor,
                OAA.o_avi_account_name authoGarage, 
                OAA.o_avi_account_verified garageverified,
				AAA.a_avi_accountdetail_avatar_img authorGarageImg,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$watchuser) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$watchuser AND f_avi_publication_comment_status=1) icomment
				FROM o_avi_publication OAP 
				LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAP.o_avi_publication_content_user
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAU.o_avi_user_id AND AUFUA.a_user_follower_user_id=$watchuser AND AUFUA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$watchuser AND AUFAA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$watchuser AND AUFUC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$watchuser AND AUFAC.a_user_follow_acepted=1
				WHERE 
					(
						((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAA.o_avi_account_type_id=2)
                		OR ((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUP.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL))
                		)
	                	AND (
	                		(AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR ((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL) ) 
	                	))
	                	OR OAP.o_avi_publication_author_user=$watchuser
	                	OR OAP.o_avi_publication_content_user=$watchuser
	                )
                	AND o_avi_publication_content_user=$user AND OAP.o_avi_publication_status=1
				AND OAU.o_avi_user_id not in ($notin)
				ORDER BY fecha DESC
				LIMIT $inicio, 10";
		//echo $query;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$publicaciones[]=$row;
				}
			}
		}
		return $publicaciones;
	}
	public function getPublicationByID($post){
		if(!$post){
			$post=$this->id;
		}
		if(!$post)
		{
			throw new Exception("La Publicacion no existe");
			
		}
		$database=new Database;
        $db=$database->connect();
		$publication=array();
		$watchuser=$_SESSION["iduser"];
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($watchuser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }

		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				OAP.o_avi_publication_url url,
				CAPT.c_avi_publication_type_description tipo, 
				OAUD.o_avi_userdetail_name authorName, 
				OAUD.o_avi_userdetail_last_name authorLastName,
                OAU.o_avi_user_verified userverified,
                AAUP.a_avi_user_perfil_avatar imgAuthor,
                OAU.o_avi_user_username usernameAuthor,
                OAA.o_avi_account_name authoGarage, 
                OAA.o_avi_account_verified garageverified, 
				AAA.a_avi_accountdetail_avatar_img authorGarageImg,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$watchuser) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$watchuser AND f_avi_publication_comment_status=1) icomment
				FROM o_avi_publication OAP 
				LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAP.o_avi_publication_content_user
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAU.o_avi_user_id AND AUFUA.a_user_follower_user_id=$watchuser AND AUFUA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$watchuser AND AUFAA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$watchuser AND AUFUC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$watchuser AND AUFAC.a_user_follow_acepted=1
				WHERE (
						((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAA.o_avi_account_type_id=2)
                		OR ((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUP.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL))
                		)
	                	AND (
	                		(AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR ((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL) ) 
	                	))
	                	OR OAP.o_avi_publication_author_user=$watchuser
	                	OR OAP.o_avi_publication_content_user=$watchuser
	                )
	                AND o_avi_publication_id=$post AND OAP.o_avi_publication_status=1
				AND OAU.o_avi_user_id not in ($notin)
				ORDER BY fecha DESC";
		//echo $query;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$publication=$row;
				}
			}
		}
		return $publication;
	}
	public function getAllPublicationsByGarage($cuenta,$start=0){
		$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
		$watchuser=$_SESSION["iduser"];
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($watchuser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_url url,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				CAPT.c_avi_publication_type_description tipo, 
				OAUD.o_avi_userdetail_name authorName, 
				OAUD.o_avi_userdetail_last_name authorLastName,
                AAUP.a_avi_user_perfil_avatar imgAuthor,
                OAU.o_avi_user_username usernameAuthor,
                OAU.o_avi_user_verified userverified,
                OAA.o_avi_account_name authoGarage, 
                OAA.o_avi_account_verified garageverified,
				AAA.a_avi_accountdetail_avatar_img authorGarageImg,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$watchuser) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$watchuser AND f_avi_publication_comment_status=1) icomment
				FROM o_avi_publication OAP 
				LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user 
				LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN o_avi_user OAU ON OAP.o_avi_publication_author_user=OAU.o_avi_user_id
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAP.o_avi_publication_content_user
				LEFT JOIN a_avi_user_perfil AAUP ON OAU.o_avi_user_id=AAUP.a_avi_user_id
				LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAU.o_avi_user_id AND AUFUA.a_user_follower_user_id=$watchuser AND AUFUA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$watchuser AND AUFAA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$watchuser AND AUFUC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$watchuser AND AUFAC.a_user_follow_acepted=1
				WHERE
					(
						((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAA.o_avi_account_type_id=2)
                		OR (((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUP.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL)))
                		)
	                	AND (
	                		(AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR (((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL))) 
	                	))
	                	OR OAP.o_avi_publication_author_user=$watchuser
	                	OR OAP.o_avi_publication_content_user=$watchuser
	                ) AND o_avi_publication_content_garage=$cuenta AND OAP.o_avi_publication_status=1
				AND OAU.o_avi_user_id not in ($notin) ORDER BY fecha DESC
				LIMIT $inicio, 10";
		//echo $query;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$publicaciones[]=$row;
				}
			}
		}
		return $publicaciones;
	}
	public function getAllPublicationsByAuto($auto,$start=0){
		$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
		$watchuser=$_SESSION["iduser"];
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($watchuser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,	
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_url url,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				CAPT.c_avi_publication_type_description tipo, 
				OAUD.o_avi_userdetail_name authorName, 
				OAUD.o_avi_userdetail_last_name authorLastName,
                AAUP.a_avi_user_perfil_avatar imgAuthor,
                OAU.o_avi_user_username usernameAuthor,
                OAU.o_avi_user_verified userverified,
                OAA.o_avi_account_name authoGarage, 
                OAA.o_avi_account_verified garageverified,
				AAA.a_avi_accountdetail_avatar_img authorGarageImg,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$watchuser) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$watchuser AND f_avi_publication_comment_status=1) icomment
				FROM o_avi_publication OAP 
				LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user
				LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN o_avi_user OAU ON OAP.o_avi_publication_author_user=OAU.o_avi_user_id
				LEFT JOIN a_avi_user_perfil AAUP ON OAU.o_avi_user_id=AAUP.a_avi_user_id
				LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAP.o_avi_publication_content_user
				LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAU.o_avi_user_id AND AUFUA.a_user_follower_user_id=$watchuser AND AUFUA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$watchuser AND AUFAA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$watchuser AND AUFUC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$watchuser AND AUFAC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_car AUFCC ON AUFCC.a_user_following_i_car_id=OAP.o_avi_publication_content_auto AND AUFCC.a_user_follower_acc_user_id=$watchuser AND AUFCC.a_user_follow_acepted=1
				LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=OAP.o_avi_publication_content_auto
				WHERE 
					(
						((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAA.o_avi_account_type_id=2)
                		OR (((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUP.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL)))
                		)
	                	AND (
	                		(AUFCC.a_user_follower_acc_user_id IS NOT NULL OR IAAC.i_avi_account_car_privacy=2)
	                		OR (AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR (((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL))) 
	                	))
	                	OR OAP.o_avi_publication_author_user=$watchuser
	                	OR OAP.o_avi_publication_content_user=$watchuser
	                ) AND o_avi_publication_content_auto=$auto AND OAP.o_avi_publication_status=1 
				AND OAU.o_avi_user_id not in ($notin)
				ORDER BY fecha DESC
				LIMIT $inicio, 10";
		//echo $query;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$publicaciones[]=$row;
				}
			}
		}
		return $publicaciones;
	}
	public function addPublicacion($content,$type,$author,$imgs=null,$price=null, $url=null,$privacy=2,$garage=null,$subgarage=null, $auto=null, $autorCont=null,$sharing=null, $currency=null, $colaborator=null){
		$database=new Database;
        $db=$database->connect();
		$insert="o_avi_publication_content, o_avi_publication_type, o_avi_publication_author_user, o_avi_publication_content_user, o_avi_publication_privacy, o_avi_publication_url,o_avi_publication_time";
		if(!$autorCont)
		{
			$autorCont=$author;
		}
		$values="'$content',$type,$author,$autorCont,$privacy,'$url',NOW()";
		if($imgs)
		{
			$imgs=base64_encode(json_encode($imgs));
			$insert.=", o_avi_publication_imgs";
			$values.=", '$imgs'";
		}
		if($garage){
			$insert.=", o_avi_publication_author_garage";
			$values.=", $garage";
		}
		if($subgarage)
		{
			$insert.=", o_avi_publication_content_garage";
			$values.=", $subgarage";
		}
		if($price)
		{
			$insert.=", o_avi_publication_price_ad";
			$values.=", $price";
		}
		if($currency)
		{
			$insert.=", o_avi_publication_currency_ad";
			$values.=", '$currency'";
		}
		if($auto)
		{
			$insert.=", o_avi_publication_content_auto";
			$values.=", $auto";
		}
		if($sharing)
		{
			$insert.=", o_avi_publication_sharing";
			$values.=", $sharing";
		}
		if($colaborator){
			$insert.=", o_avi_publication_colaborator";
			$values.=", $colaborator";
		}
		if($content!=""){
			$url = $this->getlink($content);
			if($url){
				$enlace = isset($url[0]["link"]) ? $url[0]["link"] : null ;
				$jsonMetas = isset($url[0]["metaInfo"]) ? $url[0]["metaInfo"] : null;
				if($enlace){
					$insert .=", o_avi_publication_link";
					$values.=", '$enlace'";
				}
				if($jsonMetas!=null){
					$insert .=", o_avi_publication_meta ";
					$values.=", '$jsonMetas'";
				}
			}
		}
		$query="INSERT INTO o_avi_publication($insert) VALUES ($values) ";
		if($result=$db->query($query))
		{
			$result=$db->insert_id;
		}
		$db->close();
		$respuesta=$result;
		try{
			$this->create($result);
		}
		catch(Exception $e){
			$respuesta=false;
		}
		return $respuesta;
		
	}
	function publicacionTempImages($ruta,$realnameRuta, $idUser)
	{

        $database=new Database;
        $db=$database->connect();
        //type de imagen en publicacion es de tipo 2 
        $sql = "INSERT INTO tmp_avi_car_img (tmp_avi_car_img_car, tmp_avi_user_id, tmp_avi_img_realname ,tmp_avi_car_img_type) VALUES('$ruta', '$idUser','$realnameRuta', 2)";
       	$respuesta=false;
       	if($db->query($sql))
        {
        	$respuesta=true;
        }
        return $respuesta;
        $db->close();
	}
	function searchTempImages($realname, $idUser)
	{

        $database=new Database;
        $db=$database->connect();
        //type de imagen en publicacion es de tipo 2 
        $query = "SELECT tmp_avi_car_img_car FROM tmp_avi_car_img WHERE  tmp_avi_user_id =$idUser AND tmp_avi_img_realname ='$realname' AND tmp_avi_car_img_type=2";
        $img ="";
        if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$img = $row["tmp_avi_car_img_car"];
				}
			}
		}
        return $img;
        $db->close();
	}
	function publicacionDelOneImgTmp($ruta)
    {
    	//type de imagen en publicacion es de tipo 2 
        $database=new Database;
        $db=$database->connect();
        $sql = "DELETE FROM tmp_avi_car_img WHERE tmp_avi_img_realname = '$ruta' AND tmp_avi_car_img_type='2'";
        $respuesta=false;
        if($db->query($sql))
        {
        	$respuesta=true;
        }
        return $respuesta;
        $db->close();
    }
    function publicacionDelete($id)
    {
    	//type de imagen en publicacion es de tipo 2 
        $database=new Database;
        $db=$database->connect();
        $sql = "UPDATE o_avi_publication SET o_avi_publication_status=0 WHERE o_avi_publication_id = '$id'";
        $respuesta=false;
        if($db->query($sql))
        {
        	$respuesta=true;
        }
        return $respuesta;
        $db->close();
    }
    function publicacionUpdate($content,$id)
    {
    	//type de imagen en publicacion es de tipo 2 
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT o_avi_publication_content FROM o_avi_publication WHERE o_avi_publication_id = '$id'";
        $queryDB=$db->query($select);
        $count=0;
        $sql="";
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $sql = "UPDATE o_avi_publication SET ";

                if($content!="")
                {
                    $count++; 
                    $sql.="o_avi_publication_content='$content'";

                	$url = $this->getlink($content);
					if($url){
						$enlace = isset($url[0]["link"]) ? $url[0]["link"] : null ;
						$jsonMetas = isset($url[0]["metaInfo"]) ? $url[0]["metaInfo"] : null;
						if($enlace){
							if($count>0)
		                    {
		                        $sql.=", ";
		                    }
		                    $sql.="o_avi_publication_link='$enlace'";
	                    	$count++;
						}
						else
						{
							if($count>0)
		                    {
		                        $sql.=", ";
		                    }
		                    $sql.="o_avi_publication_link= NULL";
	                    	$count++;
						}
						if($jsonMetas!=null){
							if($count>0)
		                    {
		                        $sql.=", ";
		                    }
		                    $sql.="o_avi_publication_meta='$jsonMetas'";
	                    	$count++;
						}
						else
						{
							if($count>0)
		                    {
		                        $sql.=", ";
		                    }
		                    $sql.="o_avi_publication_meta= NULL";
	                    	$count++;
						}
					}
	            }
                $sql.= " WHERE o_avi_publication_id='$id'";
            }
        }
        $ret=false;
        if($count>=1)
        {
            if($db->query($sql))
            {
                $ret=true;
            }
        }
        else
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function getPublicacionesTimelineLogin($user=0,$start=0){
    	$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_url url,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				CAPT.c_avi_publication_type_description tipo, 
				OAUDA.o_avi_userdetail_name authorName, 
				OAUDA.o_avi_userdetail_last_name authorLastName,
                AAUPA.a_avi_user_perfil_avatar imgAuthor,
                OAUA.o_avi_user_username usernameAuthor,
                OAAA.o_avi_account_name authoGarage, 
				AAADA.a_avi_accountdetail_avatar_img authorGarageImg,
				OAUDC.o_avi_userdetail_name contentName, 
				OAUDC.o_avi_userdetail_last_name contentLastName,
                OAUC.o_avi_user_username usernameContent,
                OAUA.o_avi_user_verified userverified,
                OAAC.o_avi_account_name contentGarage, 
                OAAA.o_avi_account_verified garageverified,
                IAACC.i_avi_account_car_alias contentCar,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$user) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$user AND f_avi_publication_comment_status=1) icomment
                FROM o_avi_publication OAP
                LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
                LEFT JOIN o_avi_user OAUA ON OAUA.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_userdetail OAUDA ON OAUDA.o_avi_userdetail_id_user=OAUA.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPA ON AAUPA.a_avi_user_id=OAUA.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAUA.o_avi_user_id AND AUFUA.a_user_follower_user_id=$user AND AUFUA.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAA ON OAAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN a_avi_accountdetail AAADA ON AAADA.a_avi_account_id=OAAA.o_avi_account_id
                LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$user AND AUFAA.a_user_follow_acepted=1
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_userdetail OAUDC ON OAUDC.o_avi_userdetail_id_user=OAUC.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAUC.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$user AND AUFUC.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$user AND AUFAC.a_user_follow_acepted=1
                LEFT JOIN f_avi_share FAS ON FAS.f_avi_share_id=OAP.o_avi_publication_sharing
                LEFT JOIN i_avi_account_car IAACC ON IAACC.i_avi_account_car_id=OAP.o_avi_publication_content_auto
                WHERE FAS.f_avi_share_ad IS NOT NULL OR (OAAA.o_avi_account_id=113 AND OAAC.o_avi_account_id=113)
                ORDER BY fecha DESC
				LIMIT $inicio, 10";
		//echo $query;
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                    $publicaciones[]=$row;
                }
            }
        }
        $db->close();
        return $publicaciones;
    }
    function getPublicacionesTimeline($user,$start=0){
    	$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_url url,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				CAPT.c_avi_publication_type_description tipo, 
				OAUDA.o_avi_userdetail_name authorName, 
				OAUDA.o_avi_userdetail_last_name authorLastName,
                AAUPA.a_avi_user_perfil_avatar imgAuthor,
                OAUA.o_avi_user_username usernameAuthor,
                OAAA.o_avi_account_name authoGarage, 
				AAADA.a_avi_accountdetail_avatar_img authorGarageImg,
				OAUDC.o_avi_userdetail_name contentName, 
				OAUDC.o_avi_userdetail_last_name contentLastName,
                OAUC.o_avi_user_username usernameContent,
                OAUA.o_avi_user_verified userverified,
                OAAC.o_avi_account_name contentGarage, 
                OAAA.o_avi_account_verified garageverified,
                IAACC.i_avi_account_car_alias contentCar,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$user) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$user AND f_avi_publication_comment_status=1) icomment
                FROM o_avi_publication OAP
                LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
                LEFT JOIN o_avi_user OAUA ON OAUA.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_userdetail OAUDA ON OAUDA.o_avi_userdetail_id_user=OAUA.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPA ON AAUPA.a_avi_user_id=OAUA.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAUA.o_avi_user_id AND AUFUA.a_user_follower_user_id=$user AND AUFUA.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAA ON OAAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN a_avi_accountdetail AAADA ON AAADA.a_avi_account_id=OAAA.o_avi_account_id
                LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$user AND AUFAA.a_user_follow_acepted=1
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_userdetail OAUDC ON OAUDC.o_avi_userdetail_id_user=OAUC.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAUC.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$user AND AUFUC.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$user AND AUFAC.a_user_follow_acepted=1
                LEFT JOIN i_avi_account_car IAACC ON IAACC.i_avi_account_car_id=OAP.o_avi_publication_content_auto
                WHERE 
                	(
						((
						AUFAA.a_user_follower_acc_user_id IS NOT NULL
                		OR (AUFUA.a_user_follower_user_id IS NOT NULL AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL))
                		)
	                	AND (
	                		AUFAC.a_user_follower_acc_user_id IS NOT NULL
		                	OR (AUFUC.a_user_follower_user_id IS NOT NULL AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL))
	                	))
	                	OR OAP.o_avi_publication_author_user=$user
	                	OR OAP.o_avi_publication_content_user=$user
	                )
                	AND (IAACC.i_avi_account_car_status=1 OR IAACC.i_avi_account_car_status IS NULL)
                	AND (OAUA.o_avi_user_status in (1,3) OR OAUA.o_avi_user_status IS NuLL)
                	AND (OAAA.o_avi_account_status=1 OR OAAA.o_avi_account_status IS NuLL)
                	AND (OAAC.o_avi_account_status=1 OR OAAC.o_avi_account_status IS NuLL)
                	AND (OAUC.o_avi_user_status in (1,3) OR OAUC.o_avi_user_status IS NuLL)
                	AND OAP.o_avi_publication_status=1
                	AND OAUA.o_avi_user_id not in ($notin)
                	AND OAUC.o_avi_user_id not in ($notin)
                ORDER BY fecha DESC
				LIMIT $inicio, 10";
		//echo $query;
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                    $publicaciones[]=$row;
                }
            }
        }
        $db->close();
        return $publicaciones;
    }
    function getPublicacionesForSearch($search=null,$searcher=null,$time=0)
    {
    	$users=array();
        if(!$search||$search==="")
        {
            throw new Exception("Empty search", 1);
            
        }
        $inicio=10*$time;
        $coder=new Coder;
        $database=new Database;
        $db=$database->connect();
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($searcher);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
        $query="SELECT OAP.o_avi_publication_id p_to,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
                OAP.o_avi_publication_content texto,
                OAP.o_avi_publication_time fecha,
				OAP.o_avi_publication_modification_time modificacion,
                OAP.o_avi_publication_url url,
                OAP.o_avi_publication_imgs imagenes,
                CAPT.c_avi_publication_type_description tipo, 
                IF(OAP.o_avi_publication_author_garage IS NULL, IF(AAUPA.a_avi_user_perfil_privacy=1, CONCAT(OAUDA.o_avi_userdetail_name,' ',OAUDA.o_avi_userdetail_last_name), OAUA.o_avi_user_username), OAAA.o_avi_account_name) autor,
                IF(OAP.o_avi_publication_content_auto IS NULL, IF(OAP.o_avi_publication_content_garage IS NULL, IF(AAUPC.a_avi_user_perfil_privacy=1, CONCAT(OAUDC.o_avi_userdetail_name,' ',OAUDC.o_avi_userdetail_last_name), OAUC.o_avi_user_username), OAAC.o_avi_account_name), IAACC.i_avi_account_car_alias) container,
                IF(OAP.o_avi_publication_author_garage IS NULL, IF(AAUPA.a_avi_user_perfil_privacy=1, AAUPA.a_avi_user_perfil_avatar, NULL), AAADA.a_avi_accountdetail_avatar_img) img,
                IF(OAUA.o_avi_user_id=$searcher, 1, 0) owner,
                IF(AUFUA.a_user_follower_user_id IS NULL, 0, 1) followOwner,
                IF(AUFAA.a_user_follower_acc_user_id IS NULL, 0, 1) followGarage,
                IF(OAUC.o_avi_user_id=$searcher, 1, 0) reciever,
                IF(AUFUC.a_user_follower_user_id IS NULL, 0, 1) followReciever,
                IF(AUFAC.a_user_follower_acc_user_id IS NULL, 0, 1) followGarageReciever,
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$searcher) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$searcher AND f_avi_publication_comment_status=1) icomment
                FROM o_avi_publication OAP
                LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
                LEFT JOIN o_avi_user OAUA ON OAUA.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_userdetail OAUDA ON OAUDA.o_avi_userdetail_id_user=OAUA.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPA ON AAUPA.a_avi_user_id=OAUA.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAUA.o_avi_user_id AND AUFUA.a_user_follower_user_id=$searcher AND AUFUA.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAA ON OAAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN a_avi_accountdetail AAADA ON AAADA.a_avi_account_id=OAAA.o_avi_account_id
                LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$searcher AND AUFAA.a_user_follow_acepted=1
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_userdetail OAUDC ON OAUDC.o_avi_userdetail_id_user=OAUC.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAUC.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$searcher AND AUFUC.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$searcher AND AUFAC.a_user_follow_acepted=1
                LEFT JOIN i_avi_account_car IAACC ON IAACC.i_avi_account_car_id=OAP.o_avi_publication_content_auto
                WHERE 
                (OAUA.o_avi_user_status=1 OR OAUA.o_avi_user_id=$searcher) AND 
                (OAUC.o_avi_user_status=1 OR OAUC.o_avi_user_id=$searcher) AND 
                (
                	(
                		OAP.o_avi_publication_privacy IN (1,2) AND 
	                	((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAAA.o_avi_account_type_id=2)
                		OR (((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUPA.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL)))
                		)
	                	AND (
	                		(AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR (((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL))) 
	                	))
	                )
                	OR $searcher=OAP.o_avi_publication_author_user
                	OR $searcher=OAP.o_avi_publication_content_user
                ) AND (
                	(LOWER(OAUA.o_avi_user_username) LIKE '%$search%' AND OAP.o_avi_publication_author_garage IS NULL)
                    OR (LOWER(OAUDA.o_avi_userdetail_name) LIKE '%$search%' AND OAP.o_avi_publication_author_garage IS NULL)
                    OR (LOWER(OAUDA.o_avi_userdetail_last_name) LIKE '%$search%' AND OAP.o_avi_publication_author_garage IS NULL)
                    OR (LOWER(OAAA.o_avi_account_name) LIKE '%$search%' AND OAP.o_avi_publication_author_garage IS NOT NULL)
                    OR (LOWER(OAUC.o_avi_user_username) LIKE '%$search%' AND OAP.o_avi_publication_content_garage IS NULL)
                    OR (LOWER(OAUDC.o_avi_userdetail_name) LIKE '%$search%' AND OAP.o_avi_publication_content_garage IS NULL)
                    OR (LOWER(OAUDC.o_avi_userdetail_last_name) LIKE '%$search%' AND OAP.o_avi_publication_content_garage IS NULL)
                    OR (LOWER(OAAC.o_avi_account_name) LIKE '%$search%' AND OAP.o_avi_publication_content_garage IS NOT NULL)
                    OR (LOWER(IAACC.i_avi_account_car_alias) LIKE '%$search%' AND OAP.o_avi_publication_content_auto IS NOT NULL)
                    OR (LOWER(OAP.o_avi_publication_content) LIKE '% $search %') 
                    OR (LOWER(OAP.o_avi_publication_content) LIKE '$search%')
                )
                AND (IAACC.i_avi_account_car_status=1 OR IAACC.i_avi_account_car_status IS NULL)
            	AND (OAUA.o_avi_user_status in (1,3) OR OAUA.o_avi_user_status IS NuLL)
            	AND (OAAA.o_avi_account_status=1 OR OAAA.o_avi_account_status IS NuLL)
            	AND (OAAC.o_avi_account_status=1 OR OAAC.o_avi_account_status IS NuLL)
            	AND (OAUC.o_avi_user_status in (1,3) OR OAUC.o_avi_user_status IS NuLL)
            	AND OAP.o_avi_publication_status=1
            	AND OAUA.o_avi_user_id not IN ($notin)
            	AND OAUC.o_avi_user_id not IN ($notin)
                ORDER BY o_avi_publication_id DESC, reciever DESC, followOwner DESC, followReciever DESC, followGarage DESC, followGarageReciever DESC, autor
				LIMIT $inicio, 10";
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                	$row["fecha"]=date("M d, Y - H:i\h\\r\\s",strtotime($row["fecha"]));
                	$coder -> encode($row["p_to"]);
                    $row["p_to"] = $coder-> encoded;
                    $users[]=$row;
                }
            }
        }
        $db->close();
        return $users;
    }
    function getPublicacionesForHashtag($user=0,$start=0,$hashname){
    	$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_url url,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				CAPT.c_avi_publication_type_description tipo, 
				OAUDA.o_avi_userdetail_name authorName, 
				OAUDA.o_avi_userdetail_last_name authorLastName,
                AAUPA.a_avi_user_perfil_avatar imgAuthor,
                OAUA.o_avi_user_username usernameAuthor,
                OAAA.o_avi_account_name authoGarage, 
				AAADA.a_avi_accountdetail_avatar_img authorGarageImg,
				OAUDC.o_avi_userdetail_name contentName, 
				OAUDC.o_avi_userdetail_last_name contentLastName,
                OAUC.o_avi_user_username usernameContent,
                OAUA.o_avi_user_verified userverified,
                OAAC.o_avi_account_name contentGarage, 
                OAAA.o_avi_account_verified garageverified,
                IAACC.i_avi_account_car_alias contentCar,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$user) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$user AND f_avi_publication_comment_status=1) icomment
                FROM o_avi_publication OAP
                LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
                LEFT JOIN o_avi_user OAUA ON OAUA.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_userdetail OAUDA ON OAUDA.o_avi_userdetail_id_user=OAUA.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPA ON AAUPA.a_avi_user_id=OAUA.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAUA.o_avi_user_id AND AUFUA.a_user_follower_user_id=$user AND AUFUA.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAA ON OAAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN a_avi_accountdetail AAADA ON AAADA.a_avi_account_id=OAAA.o_avi_account_id
                LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$user AND AUFAA.a_user_follow_acepted=1
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_userdetail OAUDC ON OAUDC.o_avi_userdetail_id_user=OAUC.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAUC.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$user AND AUFUC.a_user_follow_acepted=1
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$user AND AUFAC.a_user_follow_acepted=1
                LEFT JOIN f_avi_share FAS ON FAS.f_avi_share_id=OAP.o_avi_publication_sharing
                LEFT JOIN i_avi_account_car IAACC ON IAACC.i_avi_account_car_id=OAP.o_avi_publication_content_auto
                WHERE OAP.o_avi_publication_content LIKE '%$hashname%'
                ORDER BY fecha DESC
				LIMIT $inicio, 10";
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                    $publicaciones[]=$row;
                }
            }
        }
        $db->close();
        return $publicaciones;
    }
    function UserAccessToPublication($user,$publication){
        $database=new Database;
        $db=$database->connect();
     	$ret=false;
        $query="SELECT OAP.o_avi_publication_author_user autor, OAP.o_avi_publication_content_user container, OAP.o_avi_publication_author_garage autorGarage, OAP.o_avi_publication_privacy pp, IF(AAUP.a_avi_user_perfil_privacy IS NULL, 1, AAUP.a_avi_user_perfil_privacy) up, IF(OAA.o_avi_account_type_id IS NULL, 0, OAA.o_avi_account_type_id) gp 
        		FROM o_avi_publication OAP
        		LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAP.o_avi_publication_author_user
        		LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
        		WHERE OAP.o_avi_publication_id=$publication AND OAP.o_avi_publication_status=1";
       	$dataPublication=array();
       	if($data=$db->query($query)){
       		if($data->num_rows>0)
       		{
       			while ($row=$data->fetch_assoc()) {
       				$dataPublication=$row;
       			}
       		}
       	}
       	if(!empty($dataPublication)){
       		if($dataPublication["autor"]==$user||$dataPublication["container"]==$user){
       			$ret=true;
       		}
       		else{
	       		$followerHuman=new Seguidor(1);
	       		$followerGarage=new Seguidor(2);
	       		switch($dataPublication["pp"]){
	       			case 1:
	       				if($dataPublication["gp"])
	       				{
							if($dataPublication["gp"]==2){
			       				$ret=true;
			       			}
			       			elseif($user){
			       				if($followerGarage->followingTo($user,$dataPublication["autorGarage"])){
			       					$ret=true;
			       				}
			       			}
	       				}
	       				else
	       				{
	       					if($dataPublication["up"]==2){
			       				$ret=true;
			       			}
			       			elseif($user){
			       				if($followerHuman->followingTo($user,$dataPublication["autor"])){
			       					$ret=true;
			       				}
			       			}
	       				}
	       				
	       				break;
	       			case 2:
	       				if($dataPublication["gp"])
	       				{
							if($dataPublication["gp"]==2||$dataPublication["gp"]==1){
			       				$ret=true;
			       			}
			       			elseif($user){
			       				if($followerGarage->followingTo($user,$dataPublication["autorGarage"])){
			       					$ret=true;
			       				}
			       			}
	       				}
	       				else
	       				{
	       					if($dataPublication["up"]==2||$dataPublication["up"]==1){
			       				$ret=true;
			       			}
			       			elseif($user){
			       				if($followerHuman->followingTo($user,$dataPublication["autor"])){
			       					$ret=true;
			       				}
			       			}
	       				}
	       				break;
	       			case 3:
	       			 	if($user){

		       				if($dataPublication["gp"])
		       				{
		       					if($followerGarage->followingTo($user,$dataPublication["autorGarage"])){
				       				$ret=true;
				       			}
		       				}
		       				else
		       				{
		       					if($followerHuman->followingTo($user,$dataPublication["autor"])){
				       				$ret=true;
				       			}
		       				}
		       			}
	       				break;
	       			
	       		}
	       	}
       	}
       	$db->close();
       	return $ret;
    }
    function getCommentsByPublication($publicacion,$user,$last=0){
        $database=new Database;
        $db=$database->connect();
    	$query="SELECT 
    			FAPC.f_avi_publication_comment_id idComment,
    			FAPC.f_avi_publication_comment_text comentario, 
    			IF(FAPC.f_avi_publication_comment_account IS NULL ,1,2) type, 
    			FAPC.f_avi_publication_comment_user authorUser, 
    			FAPC.f_avi_publication_comment_account authorGarage, 
    			IF(FAPC.f_avi_publication_comment_account IS NULL , (
    				IF(AAUP.a_avi_user_perfil_privacy=1, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name),OAU.o_avi_user_username)
    				), OAA.o_avi_account_name) author, 
    			IF(FAPC.f_avi_publication_comment_account IS NULL ,AAUP.a_avi_user_perfil_avatar, AAA.a_avi_accountdetail_avatar_img) imgAuthor,
    			FAPC.f_avi_publication_comment_time hora,
    			FAPC.f_avi_publication_comment_modification_time modificada 
    			FROM f_avi_publication_comment FAPC
				LEFT JOIN o_avi_publication OAP ON OAP.o_avi_publication_id=FAPC.f_avi_publication_comment_publication
				LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=FAPC.f_avi_publication_comment_user
				LEFT JOIN o_avi_userdetail OAUD ON OAU.o_avi_user_id=OAUD.o_avi_userdetail_id_user
				LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
				LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=FAPC.f_avi_publication_comment_account
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_follower_user_id=1 AND FAPC.f_avi_publication_comment_user=AUFU.a_user_following_user_id
				LEFT JOIN a_user_follow_account AUFA ON AUFA.a_user_follower_acc_user_id=1 AND AUFA.a_user_following_account_id=FAPC.f_avi_publication_comment_account
				WHERE OAP.o_avi_publication_id=$publicacion AND (FAPC.f_avi_publication_comment_user=$user OR OAA.o_avi_account_user_id=$user OR (AAUP.a_avi_user_perfil_privacy <> 3 AND FAPC.f_avi_publication_comment_account IS NULL) OR (FAPC.f_avi_publication_comment_account IS NOT NULL AND OAA.o_avi_account_type_id<>3) OR (FAPC.f_avi_publication_comment_account IS NOT NULL AND AUFA.a_user_follower_acc_user_id=$user) OR (FAPC.f_avi_publication_comment_account IS NULL AND AUFU.a_user_follower_user_id=$user))
					AND FAPC.f_avi_publication_comment_status=1
				ORDER BY hora DESC
				LIMIT $last, 10";
		$resp=array();
		if($data=$db->query($query))
		{
			if($data->num_rows>0){
				while ($row=$data->fetch_assoc()) {
					$resp[]=$row;
				}
			}
		}
		$db->close();
		return $resp;
    }
    function comment($publicacion,$content,$user,$cuenta=null){
    	$database=new Database;
        $db=$database->connect();
        $insert="f_avi_publication_comment_publication, f_avi_publication_comment_text, f_avi_publication_comment_user";
        $values="$publicacion, '$content', $user";
        if($cuenta){
        	$insert.=", f_avi_publication_comment_account";
        	$values.=", $cuenta";
        }
        $query="INSERT INTO f_avi_publication_comment ($insert) VALUES ($values)";
        $ret=false;
        if($db->query($query)){
        	$ret=$db->insert_id;
        }
        $db->close();
        return $ret;
    }
    function getComment($idComment){
    	$database=new Database;
        $db=$database->connect();
    	$query="SELECT 
    			FAPC.f_avi_publication_comment_id idComment,
    			FAPC.f_avi_publication_comment_text comentario, 
    			FAPC.f_avi_publication_comment_publication post,
    			IF(FAPC.f_avi_publication_comment_account IS NULL ,1,2) type, 
    			FAPC.f_avi_publication_comment_user authorUser, 
    			FAPC.f_avi_publication_comment_account authorGarage, 
    			IF(FAPC.f_avi_publication_comment_account IS NULL , (
    				IF(AAUP.a_avi_user_perfil_privacy=1, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name),OAU.o_avi_user_username)
    				), OAA.o_avi_account_name) author, 
    			IF(FAPC.f_avi_publication_comment_account IS NULL ,AAUP.a_avi_user_perfil_avatar, AAA.a_avi_accountdetail_avatar_img) imgAuthor,
    			FAPC.f_avi_publication_comment_time hora,
    			FAPC.f_avi_publication_comment_modification_time modificada
    			FROM f_avi_publication_comment FAPC
				LEFT JOIN o_avi_publication OAP ON OAP.o_avi_publication_id=FAPC.f_avi_publication_comment_publication
				LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=FAPC.f_avi_publication_comment_user
				LEFT JOIN o_avi_userdetail OAUD ON OAU.o_avi_user_id=OAUD.o_avi_userdetail_id_user
				LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
				LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=FAPC.f_avi_publication_comment_account
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_follower_user_id=1 AND FAPC.f_avi_publication_comment_user=AUFU.a_user_following_user_id
				LEFT JOIN a_user_follow_account AUFA ON AUFA.a_user_follower_acc_user_id=1 AND AUFA.a_user_following_account_id=FAPC.f_avi_publication_comment_account
				WHERE f_avi_publication_comment_id=$idComment";
		$resp=array();
		if($data=$db->query($query))
		{
			if($data->num_rows>0){
				while ($row=$data->fetch_assoc()) {
					$resp=$row;
				}
			}
		}
		$db->close();
		return $resp;
    }
    function getTotalCommentsByPost($post){
    	$database=new Database;
        $db=$database->connect();
        $query="SELECT count(f_avi_publication_comment_id) total FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=$post AND f_avi_publication_comment_status=1";
        $total=0;
        if($data=$db->query($query)){
        	if($data->num_rows>0){
        		while ($row=$data->fetch_assoc()) {
        			$total=$row["total"];
        		}
        	}
        }
        $db->close();
        return $total;
    }
    function getAvialableCommentors($user,$post){
    	$database=new Database;
        $db=$database->connect();
        $query="SELECT o_avi_publication_author_user authorUser, IFNULL(o_avi_publication_author_garage,0) authorGarage, o_avi_publication_content_user contentUser, IFNULL(o_avi_publication_content_garage,0)contentGarage FROM o_avi_publication OAP WHERE OAP.o_avi_publication_id=$post";
        $ret=false;
        $infoPost=array();
        if($data=$db->query($query)){
        	if($data->num_rows>0){
        		while ($row=$data->fetch_assoc()) {
        			$infoPost=$row;
        		}
        	}
        }
        if(!empty($infoPost))
        {
        	if($user==$infoPost["authorUser"]){
        		if($infoPost["authorGarage"]){
	        		$garage=new Garage;
	        		$ret[]=$garage->getInfoGarage($infoPost["authorGarage"]);
	        		$cuenta=$infoPost["authorGarage"];
	        		while($padre=$garage->getFather($cuenta,$db))
			        {
			            $cuenta=$padre["id"];
			            $ret[]=$padre;
			        }
			    }
        	}
        	if($user==$infoPost["contentUser"]&&!$ret){
        		if ($infoPost["contentGarage"]) {
	        		$garage=new Garage;
	        		$ret[]=$garage->getInfoGarage($infoPost["contentGarage"]);
	        		$cuenta=$infoPost["contentGarage"];
	        		while($padre=$garage->getFather($cuenta,$db))
			        {
			            $cuenta=$padre["id"];
			            $ret[]=$padre;
			        }
        		}
        	}
        }
        $db->close();
        return $ret;
    }
    function getPostFromComment($idComment){
    	$post=0;
        $comment=$this->getComment($idComment);
        if(!empty($comment)){
        	$post=$comment["post"];
        }
        return $post;
    }
    function getUserOfWhereIComment($idPub,$myId)
    {
    	$database=new Database;
        $db=$database->connect();
        $query="SELECT DISTINCT f_avi_publication_comment_publication idPublication, o_avi_publication_author_user authorPub, o_avi_publication_content_user perfilPub
        		FROM f_avi_publication_comment APC
        		LEFT JOIN  o_avi_publication OAP ON OAP.o_avi_publication_id = APC.f_avi_publication_comment_publication
        		WHERE f_avi_publication_comment_publication='$idPub' AND f_avi_publication_comment_user='$myId' and f_avi_publication_comment_status=1
        		ORDER BY f_avi_publication_comment_time DESC; ";
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
    function isUserOwnerComment($user,$comment){
    	$comment=$this->getComment($comment);
    	if($comment["authorUser"]==$user){
    		return true;
    	}
    	return false;
    }
    function editComment($comment,$text){
    	$database=new Database;
        $db=$database->connect();
        $query="UPDATE f_avi_publication_comment SET f_avi_publication_comment_text='$text', f_avi_publication_comment_modification_time=NOW() WHERE f_avi_publication_comment_id=$comment";
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
        $query="UPDATE f_avi_publication_comment SET f_avi_publication_comment_status=0 WHERE f_avi_publication_comment_id=$comment";
        $ret=false;
        if($db->query($query)){
        	$ret=true;
        }
        $db->close();
        return $ret;
    }
	function getAllPublicationsByUserAuthor($user,$start=0){
		$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
		$watchuser=$_SESSION["iduser"];
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($watchuser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				OAP.o_avi_publication_url url,
				CAPT.c_avi_publication_type_description tipo, 
				OAUD.o_avi_userdetail_name authorName, 
				OAUD.o_avi_userdetail_last_name authorLastName,
				OAU.o_avi_user_verified userverified,
                AAUP.a_avi_user_perfil_avatar imgAuthor,
                OAU.o_avi_user_username usernameAuthor,
                OAA.o_avi_account_name authoGarage, 
                OAA.o_avi_account_verified garageverified,
				AAA.a_avi_accountdetail_avatar_img authorGarageImg,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$watchuser) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$watchuser AND f_avi_publication_comment_status=1) icomment
				FROM o_avi_publication OAP 
				LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type 
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAP.o_avi_publication_author_user
                LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAP.o_avi_publication_content_user
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAU.o_avi_user_id AND AUFUA.a_user_follower_user_id=$watchuser AND AUFUA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$watchuser AND AUFAA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$watchuser AND AUFUC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$watchuser AND AUFAC.a_user_follow_acepted=1
				WHERE 
					(
						((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAA.o_avi_account_type_id=2)
                		OR ((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUP.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL))
                		)
	                	AND (
	                		(AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR ((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL) ) 
	                	))
	                	OR OAP.o_avi_publication_author_user=$watchuser
	                	OR OAP.o_avi_publication_content_user=$watchuser
	                )
                	AND o_avi_publication_author_user=$user AND o_avi_publication_author_garage is null AND OAP.o_avi_publication_status=1
				AND OAU.o_avi_user_id not in ($notin)
				ORDER BY fecha DESC
				LIMIT $inicio, 10";
		//echo $query;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$publicaciones[]=$row;
				}
			}
		}
		return $publicaciones;
	}
	public function getAllPublicationsByGarageAuthor($cuenta,$start=0){
		$database=new Database;
        $db=$database->connect();
		$publicaciones=array();
		$inicio=$start;
		$fin=$start+10;
		$watchuser=$_SESSION["iduser"];
        $Garage=new Garage;
        $notAllowedUsers=$Garage->notAccesibleUsers($watchuser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$query="SELECT 
				OAP.o_avi_publication_id idPublicacion,
				OAP.o_avi_publication_price_ad precio,
				OAP.o_avi_publication_currency_ad moneda,
				OAP.o_avi_publication_content texto, 
				OAP.o_avi_publication_type tipoId, 
				OAP.o_avi_publication_author_user usuarioAutor, 
				OAP.o_avi_publication_author_garage cuentaAutor, 
				OAP.o_avi_publication_content_user usuarioDestino, 
				OAP.o_avi_publication_content_garage cuentaDestino, 
				OAP.o_avi_publication_content_auto autoDestino,
				OAP.o_avi_publication_privacy privacidad, 
				OAP.o_avi_publication_status estadoPublicacion, 
				OAP.o_avi_publication_time fecha, 
				OAP.o_avi_publication_modification_time modificacion,
				OAP.o_avi_publication_url url,
				OAP.o_avi_publication_imgs imagenes,
				OAP.o_avi_publication_link linkStatus,
				OAP.o_avi_publication_meta metas,
				CAPT.c_avi_publication_type_description tipo, 
				OAUD.o_avi_userdetail_name authorName, 
				OAUD.o_avi_userdetail_last_name authorLastName,
                AAUP.a_avi_user_perfil_avatar imgAuthor,
                OAU.o_avi_user_username usernameAuthor,
                OAU.o_avi_user_verified userverified,
                OAA.o_avi_account_name authoGarage, 
                OAA.o_avi_account_verified garageverified,
				AAA.a_avi_accountdetail_avatar_img authorGarageImg,
				IFNULL(OAP.o_avi_publication_sharing,0) shared,	
                (SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_status=1) comentarios,
                (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_publication_id=OAP.o_avi_publication_id) likes,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id) shareds,
                (SELECT count(f_avi_share_id) FROM f_avi_share WHERE f_avi_share_post_shared=OAP.o_avi_publication_id AND f_avi_share_sharer=$watchuser) ishare,
				(SELECT count(f_avi_publication_comment_id) FROM f_avi_publication_comment WHERE f_avi_publication_comment_publication=OAP.o_avi_publication_id AND f_avi_publication_comment_user=$watchuser AND f_avi_publication_comment_status=1) icomment
				FROM o_avi_publication OAP 
				LEFT JOIN c_avi_publication_type CAPT ON CAPT.c_avi_publication_type_id=OAP.o_avi_publication_type
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAP.o_avi_publication_author_user 
				LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAP.o_avi_publication_author_garage
				LEFT JOIN o_avi_user OAU ON OAP.o_avi_publication_author_user=OAU.o_avi_user_id
                LEFT JOIN o_avi_user OAUC ON OAUC.o_avi_user_id=OAP.o_avi_publication_content_user
                LEFT JOIN o_avi_account OAAC ON OAAC.o_avi_account_id=OAP.o_avi_publication_content_garage
                LEFT JOIN a_avi_user_perfil AAUPC ON AAUPC.a_avi_user_id=OAP.o_avi_publication_content_user
				LEFT JOIN a_avi_user_perfil AAUP ON OAU.o_avi_user_id=AAUP.a_avi_user_id
				LEFT JOIN a_user_follow_user AUFUA ON AUFUA.a_user_following_user_id=OAU.o_avi_user_id AND AUFUA.a_user_follower_user_id=$watchuser AND AUFUA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAA ON AUFAA.a_user_following_account_id=OAA.o_avi_account_id AND AUFAA.a_user_follower_acc_user_id=$watchuser AND AUFAA.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_user AUFUC ON AUFUC.a_user_following_user_id=OAUC.o_avi_user_id AND AUFUC.a_user_follower_user_id=$watchuser AND AUFUC.a_user_follow_acepted=1
				LEFT JOIN a_user_follow_account AUFAC ON AUFAC.a_user_following_account_id=OAAC.o_avi_account_id AND AUFAC.a_user_follower_acc_user_id=$watchuser AND AUFAC.a_user_follow_acepted=1
				WHERE
					(
						((
						(AUFAA.a_user_follower_acc_user_id IS NOT NULL OR OAA.o_avi_account_type_id=2)
                		OR (((AUFUA.a_user_follower_user_id IS NOT NULL OR AAUP.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_author_garage IS NULL OR AUFAA.a_user_follower_acc_user_id IS NOT NULL)))
                		)
	                	AND (
	                		(AUFAC.a_user_follower_acc_user_id IS NOT NULL OR OAAC.o_avi_account_type_id=2)
		                	OR (((AUFUC.a_user_follower_user_id IS NOT NULL OR AAUPC.a_avi_user_perfil_privacy=2) AND (OAP.o_avi_publication_content_garage IS NULL OR AUFAC.a_user_follower_acc_user_id IS NOT NULL))) 
	                	))
	                	OR OAP.o_avi_publication_author_user=$watchuser
	                	OR OAP.o_avi_publication_content_user=$watchuser
	                ) AND o_avi_publication_author_garage=$cuenta AND OAP.o_avi_publication_status=1
				AND OAU.o_avi_user_id not in ($notin) ORDER BY fecha DESC
				LIMIT $inicio, 10";
		//echo $query;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$publicaciones[]=$row;
				}
			}
		}
		return $publicaciones;
	}
	function getLink($text){
		$reg_exUrl = "/(https|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$reg_Url = "/(http:\/\/www\.|www\.|http:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/\S*)?/";
		if(preg_match($reg_exUrl, $text, $url)) //solo con http
		{
			$embed = 'embed/';
			$YouTubeRegex = "/(http|https|ftp|ftps)\:\/\/((w){3}.)?youtu(be)?(\.com)?\/watch.[^\s]+/";
			$YoutuRegex = "/(http|https|ftp|ftps)\:\/\/((w){3}.)?youtu(.be)?(\.com)?\/.[^\s]+/";
			if(preg_match($YouTubeRegex, $text, $urlyt))//solo https://youtube.com
			{
			    $constructall = '';
			    $restURL = (strlen($urlyt[0]) - 32 );
				for($i=1; $i<= $restURL; $i++ )
				{
					$construct = $urlyt[0][-$i];
					$constructall .=$construct;
				}
				$allInverse = strrev($constructall);
				$allInverseArr=explode("&",$allInverse);
				$allInverse=$allInverseArr[0];
				$urlLink= 'https://www.youtube.com/'.$embed.$allInverse;
			    $infoLink[]=array("link" => $urlLink);
				return $infoLink;
			}
			elseif(preg_match($YoutuRegex, $text, $urlyt))// solo https://youtu.be/
			{
			    $constructall = '';
			    $restURL = (strlen($urlyt[0]) - 17 );
				for($i=1; $i<= $restURL; $i++ )
				{
					$construct = $urlyt[0][-$i];
					$constructall .=$construct;
				}
				$allInverse = strrev($constructall);
				$urlLink= 'https://www.youtube.com/'.$embed.$allInverse;
			    $infoLink[]=array("link" => $urlLink);
				return $infoLink;
			}
	    	else
			{
				//echo preg_replace($reg_exUrl, "<a href='{$url[0]}' target='_blank'>{$url[0]}</a>", $text);
				$html = file_get_contents_curl($url[0]);
				$metas = getMeta($html);
				if ($metas != "nometas" ) {
					$infoLink[]=array("metaInfo" => $metas, "link" => $url[0]);
					return $infoLink;
				}
				else
				{
					$infoLink[]=array("link" => $url[0]);
					return $infoLink;
				}
			}
		} 
		elseif (preg_match($reg_Url, $text, $url)) //sin http
		{
				$html = file_get_contents_curl($url[0]);
				$metas = getMeta($html);
				if ($metas != "nometas" ) {
					$infoLink[]=array("metaInfo" => $metas, "link" => $url[0]);
					return $infoLink;
				}
				else
				{
					$infoLink[]=array("link" => $url[0]);
					return $infoLink;
				}
		}
		else //sin URLs
		{
			$text;
		}
	}
	function getMentions($string) {
	    require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
	    $coder = new Coder();
	    $mentionRegex = '/(?<!\w)@[@.\ñ\Ña-zA-Z0-9_-]+/';
	    $hashtagRegex = '/#[\ñ\Ña-zA-Z0-9]+/';
	    preg_match_all($mentionRegex,$string,$mentionMatches);
	    preg_match_all($hashtagRegex,$string,$hashtagMatches);
	    $mentionKeywords = array();
	    $mention_replace = array();
	    $hashtagKeywords = array();
	    $hashtag_replace = array();
	    foreach ($mentionMatches[0] as $match){
	        $usrname = "";
	        $lenmatch = strlen($match);
	        for ($i=1; $i < $lenmatch ; $i++) //Tomar el username sin el @ 
	        { 
	            $usrname .= $match[$i];
	        }
	        $iduser = $this->idMention($usrname);
	        if($iduser){
	            $mentionKeywords []= ($match);
	            $idcoded = $coder->encode($iduser);
	            $mention_replace[] =  (' <a target="_blank" href="/perfil/?cuenta='.$idcoded.'"> '.$match.'</a> ');
	        }
	    }
	    foreach ($hashtagMatches[0] as $hmatch) {
	        $hashname = "%23";
	        $idPublication = $this->idHashtag($hmatch);
	        $lenhmatch = count($idPublication);
	        for ($i=1; $i < strlen($hmatch) ; $i++) //Tomar el username sin el @ 
	        { 
	            $hashname .= $hmatch[$i];
	        }
	        if($lenhmatch > 0){
	            $hashtagKeywords []= ($hmatch);
	            $hashtag_replace[] =  (' <a href="/hashtag/?src='.$hashname.'"> '.$hmatch.'</a> ');
	        }
	    }

	    $replaceMention = str_replace($mentionKeywords, $mention_replace, $string);
	    $replaceHashtag = str_replace($hashtagKeywords, $hashtag_replace, $replaceMention);
	    
	    return $replaceHashtag;
	}
	function idMention($username){
        $database=new Database;
        $db=$database->connect();
        $sql="SELECT o_avi_user_id id
              FROM o_avi_user
              WHERE o_avi_user_username='$username'";
        $resp= "";
        $queryDB = $db -> query($sql);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $resp=$row["id"];
            }
        }
        $db->close();
        return $resp;
    }
    function idHashtag($hashname){
        $database=new Database;
        $db=$database->connect();
        $resp= array();
        $sql="SELECT o_avi_publication_id idPublicacion, o_avi_publication_content content
        	FROM o_avi_publication
        	WHERE o_avi_publication_content LIKE '%$hashname%' ";
        if($queryDB = $db -> query($sql)){
	        if($queryDB->num_rows>0)
	        {
	            while($row=$queryDB->fetch_assoc())
	            {
	                $resp[]=$row;
	            }
	        }
	    }
		return $resp;
        $db->close();
    }
    function getPotentialMentions($text,$user){
        $database=new Database;
        $db=$database->connect();
        //$actuales=$this->getColaborators($garage);
        //$idsActuales=array_keys($actuales);
        $notIn="0";
        //foreach ($idsActuales as $a => $actual) {
        //    $notIn.=",$actual";
        //}

		require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
		$usuario = new Usuario;
        $notAllowedUsers=$usuario->notAccesibleUsers($user);
        foreach ($notAllowedUsers as $u => $us) {
            $notIn.=",$us";
        }
        $query="SELECT OAU.o_avi_user_id iduser, 
            OAU.o_avi_user_username username, 
            OAUD.o_avi_userdetail_name name, 
            OAUD.o_avi_userdetail_last_name lastname, 
            IF(AUFU.a_user_follow_id IS NULL, 0,1) sigo, 
            IF(AUFUR.a_user_follow_id IS NULL, 0, 1) mesigue, 
            OAU.o_avi_user_verified verificado, 
            AAUP.a_avi_user_perfil_avatar img
            FROM o_avi_user OAU
            LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
            LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
            LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_follower_user_id=$user AND AUFU.a_user_following_user_id=OAU.o_avi_user_id AND AUFU.a_user_follow_acepted=1
            LEFT JOIN a_user_follow_user AUFUR ON AUFUR.a_user_follower_user_id=OAU.o_avi_user_id AND AUFUR.a_user_following_user_id=$user AND AUFUR.a_user_follow_acepted=1
            WHERE (OAU.o_avi_user_username LIKE '$text%' OR CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name) LIKE '$text%' OR OAUD.o_avi_userdetail_last_name LIKE '$text%') 
            AND OAU.o_avi_user_id <> $user 
            AND (AAUP.a_avi_user_perfil_privacy <> 3 OR AUFU.a_user_follow_id IS NOT NULL) 
            AND OAU.o_avi_user_id NOT IN ($notIn) AND OAU.o_avi_user_status = 1
            ORDER BY sigo DESC, mesigue DESC, verificado DESC, username, name, lastname";
        $potenciales=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while($row=$data->fetch_assoc()){
                    $potenciales[]=$row;
                }
            }
        }
        $db->close();
        return $potenciales;
    }
	function __destruct(){
		$this->id=null;
		$this->author=null;
		$this->authorType=null;
		$this->container=null;
		$this->containerType=null;
		$this->imagenes=null;
		$this->texto=null;
	}

}