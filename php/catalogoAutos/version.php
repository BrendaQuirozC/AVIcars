<?php
/**
 * Created by PhpStorm.
 * User: Juan Gonzalez
 * Date: 15/01/2018
 * Time: 11:38 AM
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/CatalogoDB.php';

class Version
{
    public function feature($versionid)
    {
        $dbcatalogo = new CatalogoDB;
        $dbcat = $dbcatalogo->connect();
        $query = "
          SELECT c_vehicle_model.C_Vehicle_Model_System_ID, C_Vehicle_Versions_Class_ID, c_vehicle_subbrand.C_Vehicle_SubBrand_System_ID , c_vehicle_model.C_Vehicle_Model, c_vehicle_subbrand.C_Vehicle_SubBrand_Name, c_vehicle_brand.C_Vehicle_Brand, C_Vehicle_Versions_System_ID, C_Vehicle_Versions_Name,c_class.C_Class_Description_SP, c_dim.C_Dim_Description_SP, c_vehicle_versions_Char_Num_P,c_vehicle_versions_extraSpecifications, C_Vehicle_Versions_Char_Transmision, c_vehicle_versions_Char_Num_P, c_vehicle_versions_Motor_Combustible, c_vehicle_versions_Motor_Conf, C_Vehicle_Brand_System_ID
          FROM c_vehicle_versions
            LEFT JOIN c_class on c_vehicle_versions.C_Vehicle_Versions_Class_ID = c_class.C_Class_System_ID
            LEFT JOIN c_dim on c_vehicle_versions.C_Vehicle_Versions_Dim_ID = c_dim.C_Dim_System_ID
            LEFT JOIN c_vehicle_model on c_vehicle_versions.C_Vehicle_Versions_Model_ID = c_vehicle_model.C_Vehicle_Model_System_ID
            LEFT JOIN c_vehicle_subbrand on c_vehicle_model.C_Vehicle_Model_SubBrands_ID = c_vehicle_subbrand.C_Vehicle_SubBrand_System_ID
            LEFT JOIN c_vehicle_brand on c_vehicle_subbrand.C_Vehicle_SubBrand_Brand_ID = c_vehicle_brand.C_Vehicle_Brand_System_ID
          WHERE c_vehicle_versions.C_Vehicle_Versions_System_ID='$versionid'";
        $queryDB = $dbcat->query($query);
        $caracteristicas = array();
        if ($queryDB->num_rows > 0) {
            while ($row = $queryDB->fetch_assoc()) {
                $caracteristicas[$row["C_Vehicle_Versions_System_ID"]] = $row;
            }
        }
        $dbcat->close();
        return $caracteristicas;
    }


    function getFullCaratersiticas($caracteristicas, $data, $n, $padre)
    {
        $n++;
        foreach ($caracteristicas as $caract => $car) {
            if(is_array($car) && !empty($car) && $n==1)
            {
                ?>
                <tr class='warning' data-padre="<?= $caract?>">
                    <th colspan='10'>&ensp;<?=$caract?></th>
                </tr>
                <?php
                $padre = $caract;
                $this->getFullCaratersiticas($car, "", $n, $padre);
            }
            elseif(is_array($car) )
            {

                $this -> getFullCaratersiticas($car, $data." ".$caract, $n, $padre);
            }
            else
            {
                ?>
                <tr class="<?= $padre?>">
                    <td><?=$data?>&ensp;<?= $caract?>&ensp;</td>
                    <?php
                    if($car == "on" || $car == "Si")
                    {

                    ?>
                    <td><span class="glyphicon glyphicon-ok"></span></td>
                        <?php
                    }
                    else
                    {
                        ?>
                        <td><?= $car?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }

        }

    }
    function precio($idVersion)
    {
        $dbcatalogo = new CatalogoDB;
        $dbcat = $dbcatalogo->connect();
        $query = "SELECT c_version_price FROM c_version_price WHERE c_version_id ='$idVersion' AND c_version_price_status='1'";
        $result= $dbcat->query($query);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        /* liberar la serie de resultados */
        $result->free();
        /* cerrar la conexión */
        $dbcat->close();
        return $row["c_version_price"];
    }

    function getModelsBetweenYearsBySubrand($idSubBrand,$start,$end){
        $database=new CatalogoDB;
        $db=$database->connect();
        $mod = array();
        $sql3="SELECT C_Vehicle_SubBrand_System_ID idSubBrand, C_Vehicle_SubBrand_Name nombreSubmarca, C_Vehicle_Versions_System_ID idVersion, C_Vehicle_Versions_Class_ID idClass, c_vehicle_model.C_Vehicle_Model modelName, c_vehicle_model.C_Vehicle_Model_System_ID modelId, c_class.c_class_icons iconClass
               FROM c_vehicle_subbrand
               LEFT JOIN c_vehicle_model on c_vehicle_model.C_Vehicle_Model_SubBrands_ID = c_vehicle_subbrand.C_Vehicle_SubBrand_System_ID
               LEFT JOIN c_vehicle_versions on c_vehicle_model.C_Vehicle_Model_System_ID = c_vehicle_versions.C_Vehicle_Versions_Model_ID
               LEFT JOIN c_class on c_vehicle_versions.C_Vehicle_Versions_Class_ID = c_class.C_Class_System_ID
               WHERE C_Vehicle_SubBrand_Brand_ID='$idSubBrand' AND C_Vehicle_Versions_Class_ID is not null AND c_vehicle_model BETWEEN $start AND $end
               group by C_Vehicle_Versions_Class_ID, C_Vehicle_Model 
               order by C_Vehicle_SubBrand_System_ID, C_Vehicle_Model, C_Vehicle_Versions_Class_ID";
        $queryDB = $db -> query($sql3);
        $db->set_charset("utf8");
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                if(!isset($mod[$row["idSubBrand"]]))
                {
                    $mod[$row["idSubBrand"]]=array("name"=>$row["nombreSubmarca"],"modelos"=>array());
                }
                if(!isset($mod[$row["idSubBrand"]]["modelos"][$row["modelName"]]))
                {
                    $mod[$row["idSubBrand"]]["modelos"][$row["modelName"]]=array("idModel"=>$row["modelId"],"clases"=>array());
                }
                $mod[$row["idSubBrand"]]["modelos"][$row["modelName"]]["clases"][$row["idClass"]]=$row["iconClass"];
                
            }
           
        }
        $db->close();
        return $mod;
    }
    
    function versionByClass($modelID, $classID)
    {
        $database=new CatalogoDB;
        $db=$database->connect();
        $query = "
          SELECT C_Vehicle_Versions_Name, C_Vehicle_Version_SubName, C_Vehicle_Versions_System_ID, c_vehicle_versions_extraSpecifications
          FROM c_vehicle_versions
          WHERE  C_Vehicle_Versions_Model_ID='$modelID' and C_Vehicle_Versions_Class_ID='$classID'";
        $queryDB = $db->query($query);
        $caracteristicas = array();
        if ($queryDB->num_rows > 0) {
            while ($row = $queryDB->fetch_assoc()) {
                $caracteristicas[$row["C_Vehicle_Versions_System_ID"]] = $row;
            }
        }
        $db->close();
        return $caracteristicas;
    }

    function buscarCaracteristicas($padre,$db, $lvl){
        $lvl++;
        $getarraycaracteristicas=array();
        $query="SELECT C_Char_System_ID,C_Char_Part_Vehicle,C_Char_Part_Father,C_Char_Type, C_Char_InputTypeID, C_Char_Unitnum_ID, C_Char_falgActive FROM c_char WHERE C_Char_Part_Father='$padre' AND C_Char_falgActive=1 ORDER BY C_Char_Order";
        $caracteristicas = array();
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0){
          while($row=$queryDB->fetch_assoc()){
            $hijos=$this->buscarCaracteristicas($row["C_Char_System_ID"],$db, $lvl);

            if(empty($hijos))
            {
              $hijos="No Data";
            }
            //$hijos=array();
            $caracteristicas[$row["C_Char_System_ID"]]= array("nivel"=>$lvl,"nombre"=> $row["C_Char_Part_Vehicle"], "tipo"=> $row["C_Char_Type"], "hijos" => $hijos, "padre"=> $row["C_Char_Part_Father"], "inputType" => $row["C_Char_InputTypeID"], "unidades" => $row["C_Char_Unitnum_ID"]);
            }
        }

        return $caracteristicas;
    }
    function getCaracteristicas($nivel)
    {
      $database=new CatalogoDB;
       $db=$database->connect();
       $db-> set_charset("utf8");
       $chars=$this->buscarCaracteristicas($nivel,$db, 0);
       $db->close();
       return $chars;
    }
    function submarcaById($id){
        $database=new CatalogoDB;
        $db=$database->connect();
        $mod = array();
        $sql3="SELECT C_Vehicle_SubBrand_Name, C_Vehicle_Brand, C_Vehicle_Brand_System_ID FROM c_vehicle_subbrand
                LEFT JOIN c_vehicle_brand on  c_vehicle_brand.C_Vehicle_Brand_System_ID=c_vehicle_subbrand.C_Vehicle_SubBrand_Brand_ID
               WHERE C_Vehicle_SubBrand_System_ID='$id' ";
        $queryDB = $db -> query($sql3);
        $db->set_charset("utf8");
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $mod[]=array( "Submarca" => $row["C_Vehicle_SubBrand_Name"], "Marca" => $row["C_Vehicle_Brand"], "Marcaid" => $row["C_Vehicle_Brand_System_ID"]);
            }
            return $mod;
        }
        $db->close();
    }
    function inputTypeUnit($id)
    {
            $database=new CatalogoDB;
            $db=$database->connect();
            $query="SELECT c_unit_symbol, c_unit_measurement_name_sp FROM c_unit INNER JOIN c_char on c_unit.c_unit_system_id = c_char.C_Char_Unitnum_ID where C_Char_System_ID='$id';";
            $db->set_charset("utf8");
            $queryDB=$db->query($query);
            $simbolo=array();
            if($queryDB->num_rows>0)
            {
                while($row=$queryDB->fetch_assoc())
                {
                    $simbolo=array("simbolo" => $row["c_unit_symbol"], "nombre" => $row["c_unit_measurement_name_sp"]);
                }
            }
            $db->close();
            return $simbolo;
        }
    function recursivetable($jsonData, $contadorHijos){
        $contadorModals=0;
        $siguienteModal=1;
        foreach ($jsonData as $key => $value) 
        {
            if($value["hijos"]=="No Data" && $value["inputType"])
            {
                
                if($value["inputType"]=="1"){
                    $unidad = $this->inputTypeUnit($key);
                    if(!empty($unidad))
                    {
                        echo "<label>Insertar ".$value["nombre"]." en ".$unidad["nombre"]." (".$unidad["simbolo"].")</label>";
                    }
                    else
                    {
                        echo "<label>Insertar ".$value["nombre"]."</label>";
                    }
                    echo "
                    <input type='number' class='form-control' name='".$value["nombre"]."' id='$key'/>
                    <button type='button' class='btn btn-success'>Si</button> 
                    <button type='button' class='btn btn-danger'>No</button>
                    ";
                    }
                    elseif($value["inputType"]=="4"){
                    echo "<label>Se insertar&aacute; ".$value["nombre"]." y podr&iacute;a remplazar elementos</label>";
                    echo "
                        <input type='radio' value='Si' name='optradio-$contadorModals'>
                        <button type='button' class='btn btn-success'>Si</button> 
                        <button type='button' class='btn btn-danger'>No</button>
                        ";
                    
                    }
                    elseif($value["inputType"]=="2") {
                    echo "<label>Insertar ".$contadorModals.$value["nombre"]."</label>
                        <input type='text' class='form-control' name='".$value["nombre"]."' id='$key'/>
                        <button type='button' class='btn btn-success'>Si</button> 
                        <button type='button' class='btn btn-danger'>No</button>
                        ";
                    }
                    elseif($value["inputType"]=="3") {
                    echo "<label>¿Desea insertar ".$value["nombre"]."?</label>
                        <input type='checkbox' class='form-control' name='".$value["nombre"]."' id='$key'/>
                        <button type='button' class='btn btn-success'>Si</button> 
                        <button type='button' class='btn btn-danger'>No</button>
                        ";
                    }
                    else{
                    echo "<label>Se insert&oacute ".$value["nombre"]."</label>";
                    }
            $contadorModals++;
            $siguienteModal++;
            }
            if(is_array($value["hijos"]))
            {
              //$this->recursivetable($value["hijos"], $contadorHijos++, $key);
            }
        }
    }
    function getBrandById($idBrand){
        $database=new CatalogoDB;
        $db=$database->connect();
        $auto=array();
        $query="SELECT C_Vehicle_Brand marca,  CVBI.c_vehicle_brand_images_logo_url img
                FROM c_vehicle_brand CVB
                LEFT JOIN c_vehicle_brand_images CVBI ON CVBI.c_vehicle_brand_id=CVB.C_Vehicle_Brand_System_ID
                WHERE C_Vehicle_Brand_System_ID=$idBrand";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $auto=$row;
                }
            }
        }
        $db->close();
        return $auto;
    }

    function getSubBrandById($idSubbrand){
        $database=new CatalogoDB;
        $db=$database->connect();
        $auto=array();
        $query="SELECT C_Vehicle_Brand marca, CVBI.c_vehicle_brand_images_logo_url img, C_Vehicle_SubBrand_Name submarca 
                FROM c_vehicle_subbrand CVS 
                LEFT JOIN c_vehicle_brand CVB ON CVB.C_Vehicle_Brand_System_ID=CVS.C_Vehicle_SubBrand_Brand_ID 
                LEFT JOIN c_vehicle_brand_images CVBI ON CVBI.c_vehicle_brand_id=CVB.C_Vehicle_Brand_System_ID
                WHERE C_Vehicle_SubBrand_System_ID=$idSubbrand";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $auto=$row;
                }
            }
        }
        $db->close();
        return $auto;
    }

    function getModelById($idModel){
        $database=new CatalogoDB;
        $db=$database->connect();
        $auto=array();
        $query="SELECT C_Vehicle_Brand marca, CVBI.c_vehicle_brand_images_logo_url img, C_Vehicle_SubBrand_Name submarca, C_Vehicle_Model modelo 
                FROM c_vehicle_model CVM 
                LEFT JOIN c_vehicle_subbrand CVS ON CVM.C_Vehicle_Model_SubBrands_ID=CVS.C_Vehicle_SubBrand_System_ID 
                LEFT JOIN c_vehicle_brand CVB ON CVB.C_Vehicle_Brand_System_ID=CVS.C_Vehicle_SubBrand_Brand_ID 
                LEFT JOIN c_vehicle_brand_images CVBI ON CVBI.c_vehicle_brand_id=CVB.C_Vehicle_Brand_System_ID
                WHERE C_Vehicle_Model_System_ID=$idModel";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $auto=$row;
                }
            }
        }
        $db->close();
        return $auto;
    }

    function getVersionById($idVersion){
        $database=new CatalogoDB;
        $db=$database->connect();
        $auto=array();
        $query="SELECT C_Vehicle_Brand marca, CVBI.c_vehicle_brand_images_logo_url img, C_Vehicle_SubBrand_Name submarca, C_Vehicle_Model modelo, C_Vehicle_Versions_Name version 
                FROM c_vehicle_versions CVV 
                LEFT JOIN c_vehicle_model CVM ON CVM.C_Vehicle_Model_System_ID=CVV.C_Vehicle_Versions_Model_ID 
                LEFT JOIN c_vehicle_subbrand CVS ON CVM.C_Vehicle_Model_SubBrands_ID=CVS.C_Vehicle_SubBrand_System_ID 
                LEFT JOIN c_vehicle_brand CVB ON CVB.C_Vehicle_Brand_System_ID=CVS.C_Vehicle_SubBrand_Brand_ID 
                LEFT JOIN c_vehicle_brand_images CVBI ON CVBI.c_vehicle_brand_id=CVB.C_Vehicle_Brand_System_ID
                WHERE C_Vehicle_versions_System_ID=$idVersion";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $auto=$row;
                }
            }
        }
        $db->close();
        return $auto;
    }
}
