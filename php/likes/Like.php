<?php 

/**
 * 
 */
require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/notification/Notification.php";
class Like 
{
    public $userid=null;
    public $type=null;
    public $idLiking=null;
    private $isLike=null;
    function __construct($userid=null, $type=null, $idLiking=null)
    {
        if($userid && $type && $idLiking)
        {
            $this->isLike=$this->alreadyLike($userid, $type, $idLiking);
        }
    }
    function doYouLike($userid, $whatToLike, $start=0)
    {
        $database=new Database;
        $db=$database->connect();
        $field="";
        switch ($whatToLike) {
            case 1:
                $field="f_avi_user_profile_id perfil";
                $campo="f_avi_user_profile_id";
                break;
            case 2:
                $field="f_avi_user_account_id garage";
                $campo="f_avi_user_account_id";
                break;
            case 3:
                $field="f_avi_user_car_id auto";
                $campo="f_avi_user_car_id";
                break;
            case 4:
                $field="f_avi_user_publication_id publicacion";
                $campo="f_avi_user_publication_id";
                break;
            case 5:
                $field="f_avi_user_car_ad_id ad";
                $campo="f_avi_user_car_ad_id";
                break;
            default:
                $field="f_avi_user_profile_id perfil, f_avi_user_account_id garage, f_avi_user_car_id auto, f_avi_user_publication_id publicacion, f_avi_user_car_ad_id ad";
                $campo="f_avi_user_profile_id, f_avi_user_account_id, f_avi_user_car_id, f_avi_user_publication_id, f_avi_user_car_ad_id";
                break;
        }
        $query="SELECT $field FROM f_avi_user_like WHERE f_avi_user_liker_id=$userid AND $campo IS NOT NULL
                ORDER BY f_avi_user_like_id DESC LIMIT $start, 9";
        $queryDB=$db->query($query);
        $resp=array();
        if($queryDB->num_rows>0) 
        {
            while($row=$queryDB->fetch_assoc())
            {
                $resp[]=$row;
            }
        }
        $db->close();
        return $resp;
    }
    function whoLikesYou($idlike, $whatToLike)
    {
        $database=new Database;
        $db=$database->connect();
        $field="";
        switch ($whatToLike) {
            case 1:
                $field="f_avi_user_profile_id";
                break;
            case 2:
                $field="f_avi_user_account_id";
                break;

            case 3:
                $field="f_avi_user_car_id";
                break;
            case 4:
                $field="f_avi_user_publication_id";
                break;
            case 5:
                $field="f_avi_user_car_ad_id";
                break;
        }
        $query="SELECT f_avi_user_liker_id likerid FROM f_avi_user_like WHERE $field=$idlike ";
        $queryDB=$db->query($query);
        $resp=array();
        if($queryDB->num_rows>0) 
        {
            while($row=$queryDB->fetch_assoc())
            {
                $resp[]=$row;
            }
        }
        $db->close();
        return $resp;
    }
    function getObjectOwner($type=1,$object){
        $database=new Database;
        $db=$database->connect();
        $owner=0;
        if($type==1){
            return $object;
        }
        if($type==2){
            $query="SELECT o_avi_account_user_id owner FROM o_avi_account WHERE o_avi_account_id=$object";
        }
        if($type==3){
            $query="SELECT o_avi_account_user_id owner FROM i_avi_account_car IAAC LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id WHERE i_avi_account_car_id=$object";
        }
        if($type==4){
            $query="SELECT o_avi_publication_author_user owner FROM o_avi_publication WHERE o_avi_publication_id=$object";
        }
        if($type==5){
            $query="SELECT o_avi_account_user_id owner FROM o_avi_account WHERE o_avi_account_id = (SELECT i_avi_account_car_account_id FROM i_avi_account_car WHERE i_avi_account_car_id=(SELECT o_avi_car_ad_car_id FROM o_avi_car_ad WHERE o_avi_car_ad_id=$object))";
        }
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $owner=$row["owner"];
                }
            }
        }
        $db->close();
        return $owner;
    }
    function likeit($iduser,$whatToLike, $id)
    {

        require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
        $coder = new Coder();
        $database=new Database;
        $db=$database->connect();
        $field="";
        $dest=$this->getObjectOwner($whatToLike,$id);
        $coder->encode($id);
        $idCoded=$coder->encoded;
        $coder->encode($dest);
        $destCoded=$coder->encoded;
        switch ($whatToLike) {
            case 1:
                $field="f_avi_user_profile_id";
                $likedTo=17;
                $urlLiked="/perfil/?cuenta=".$destCoded;
                $garageid = null;
                $autoid = null;
                break;
            case 2:
                $field="f_avi_user_account_id";
                $likedTo=18;
                $urlLiked="/perfil/garage/timeline/?cuenta=".$destCoded."&garage=".$idCoded;
                $garageid = $id;
                $autoid = null;
                break;
            case 3:
                $field="f_avi_user_car_id";
                $likedTo=19;
                $urlLiked="/perfil/autos/detalles/?cuenta=".$destCoded."&auto=".$idCoded;
                $garageid = null;
                $autoid = $id;
                break;
            case 4:
                $field="f_avi_user_publication_id";
                $likedTo=4;
                $urlLiked="/post/?p=".$idCoded;
                $garageid = null;
                $autoid = null;
                break;
            case 5:
                $field="f_avi_user_car_ad_id";
                $likedTo=22;
                $urlLiked="/anuncio/?a=".$idCoded;
                $garageid = null;
                $autoid = null;
                break;
        }
        $query="INSERT INTO f_avi_user_like(f_avi_user_liker_id, $field) VALUES ($iduser,$id)";
        $resp=false;
        if($db->query($query))
        {
            
            if($dest!=$iduser&&$dest>0&&$whatToLike>3){
                $notification = new Notificacion;

                $notification->addNotification($likedTo,"",$iduser,$dest, $garageid, $autoid,$urlLiked,1);
            }
            $resp=true;
        }
        return $resp;
        $db->close();
    }

    function alreadyLike($whoLikes, $whatToLike, $idlike)
    {
        $database=new Database;
        $db=$database->connect();
        $field="";
        switch ($whatToLike) {
            case 1:
                $field="f_avi_user_profile_id";
                break;
            case 2:
                $field="f_avi_user_account_id";
                break;

            case 3:
                $field="f_avi_user_car_id";
                break;
            case 4:
                $field="f_avi_user_publication_id";
                break;
            case 5:
                $field="f_avi_user_car_ad_id";
                break;
        }
        $query="SELECT f_avi_user_like_id likeid FROM f_avi_user_like WHERE $field=$idlike AND f_avi_user_liker_id=$whoLikes";
        $queryDB=$db->query($query);
        $resp=false;
        if($queryDB->num_rows>0) 
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function unlike($whoLikes, $whatToLike, $idlike)
    {
        $database=new Database;
        $db=$database->connect();
        switch ($whatToLike) {
            case 1:
                $field="f_avi_user_profile_id";
                break;
            case 2:
                $field="f_avi_user_account_id";
                break;

            case 3:
                $field="f_avi_user_car_id";
                break;
            case 4:
                $field="f_avi_user_publication_id";
                break;
            case 5:
                $field="f_avi_user_car_ad_id";
                break;
        }
        $query="DELETE FROM f_avi_user_like WHERE $field=$idlike AND f_avi_user_liker_id=$whoLikes";
        $resp=false;
        if($db->query($query)) 
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function countLikes($whatToLike, $idlike)
    {
        $database=new Database;
        $db=$database->connect();
        $field="";
        switch ($whatToLike) {
            case 1:
                $field="f_avi_user_profile_id";
                break;
            case 2:
                $field="f_avi_user_account_id";
                break;

            case 3:
                $field="f_avi_user_car_id";
                break;
            case 4:
                $field="f_avi_user_publication_id";
                break;
            case 5:
                $field="f_avi_user_car_ad_id";
                break;
        }
        $query="SELECT COUNT(f_avi_user_like_id) likes FROM f_avi_user_like WHERE $field=$idlike ";
        $queryDB=$db->query($query);
        $resp="";
        if($queryDB->num_rows>0) 
        {
            while($row=$queryDB->fetch_assoc())
            {
                $resp=$row["likes"];
            }
        }
        $db->close();
        return $resp;
    }

    function carYouLike($carId)
    {
        $database=new Database;
        $db=$database->connect();
        $query="SELECT f_avi_user_car_id siguiendo2,f_avi_user_car_id siguiendo, i_avi_account_car_alias aliasAuto, o_avi_account_id idGarage, o_avi_account_name garageNombre, o_avi_account_user_id idUsuario, a_avi_accountdetail_avatar_img garageAvatar, a_avi_car_img_car imgAuto, o_avi_car_ad_sold vendido, i_avi_account_car_privacy privacidad
        FROM f_avi_user_like 
        LEFT JOIN i_avi_account_car ON i_avi_account_car.i_avi_account_car_id = f_avi_user_like.f_avi_user_car_id
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_account_id 
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id
        LEFT JOIN a_avi_accountdetail ON a_avi_accountdetail.a_avi_account_id = o_avi_account.o_avi_account_id
        LEFT JOIN a_avi_car_img ON a_avi_car_img.a_avi_car_img_account_car_id = i_avi_account_car.i_avi_account_car_id 
        LEFT JOIN o_avi_car_ad ON i_avi_account_car.i_avi_account_car_id=o_avi_car_ad.o_avi_car_ad_car_id
        WHERE f_avi_user_car_id ='$carId'
        ORDER BY f_avi_user_like_id DESC";
        $AutoGustado=array();
        if($result=$db->query($query))
        {
            if($result->num_rows>0){
                while ($row=$result->fetch_assoc()) {
                    $AutoGustado=$row;
                }
            }
        }
        $db->close();
        return $AutoGustado;
    }

    function adYouLike($carId)
    {
        $database=new Database;
        $db=$database->connect();
        if(!$carId){
            $carId=$this->carId;
        }
        if(!$carId)
        {
            throw new Exception("El anuncio no existe");
        }
        $AnuncioGustado=array();
        $query="SELECT o_avi_car_ad_id siguiendo, 
        i_avi_account_car_alias aliasAuto, 
        i_avi_account_car_account_id idGarage, 
        o_avi_account_user_id idUsuario, 
        a_avi_car_img_car imgAuto,
        o_avi_account_name garageNombre,
        a_avi_sell_detaill_price precio,
        a_avi_sell_car_currency currency
        FROM f_avi_user_like 
        LEFT JOIN o_avi_car_ad ON o_avi_car_ad.o_avi_car_ad_id = f_avi_user_like.f_avi_user_car_ad_id
        LEFT JOIN i_avi_account_car ON i_avi_account_car.i_avi_account_car_id = o_avi_car_ad.o_avi_car_ad_car_id
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_account_id 
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id
        LEFT JOIN a_avi_car_img ON a_avi_car_img.a_avi_car_img_account_car_id = i_avi_account_car.i_avi_account_car_id 
        LEFT JOIN a_avi_sell_car ON a_avi_sell_car.a_avi_sell_car_account_car_id =  o_avi_car_ad.o_avi_car_ad_car_id
        WHERE f_avi_user_car_ad_id ='$carId' AND a_avi_sell_car_status = 1 AND o_avi_car_ad_status=1 AND o_avi_car_ad_sold=0
        ORDER BY f_avi_user_like_id DESC";
        
        if($result=$db->query($query))
        {
            if($result->num_rows>0){
                while ($row=$result->fetch_assoc()) {
                    $AnuncioGustado=$row;
                }
            }
        }
        $db->close();
        return $AnuncioGustado;
    }

    function __destruct(){
        $this->userid=null;
        $this->type=null;
        $this->idLiking=null;
    }
}