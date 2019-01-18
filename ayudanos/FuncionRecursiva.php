<?php
  require_once ($_SERVER['DOCUMENT_ROOT']).'/database/CatalogoDB.php';

  class Recursividad{

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
    function desactivar($id)
    {
        $database = new Database;
        $db=$database->connect();
        $query = "UPDATE c_char SET C_Char_falgActive='0' WHERE C_Char_System_ID = '$id'";
        $db->query($query);
    }
    function getArrayextraSpecific($versionID){
      $database=new CatalogoDB;
      $db=$database->connect();
      $query="SELECT c_vehicle_versions_extraSpecifications FROM c_vehicle_versions WHERE C_Vehicle_Versions_System_ID='$versionID'";
      $db->query($query);
      $queryDB=$db->query($query);
      $getfullCaracteristicas=array();
      if($queryDB->num_rows>0)
      {
        while($row=$queryDB->fetch_assoc())
        {
          $getfullCaracteristicas=$row["c_vehicle_versions_extraSpecifications"];
        }
      }
      //echo "<br/>-- ".$getfullCaracteristicas." --<br/>";
      return $getfullCaracteristicas;
    }

    function ExtendsCharUpdate($config, $versionID, $username){
      $database=new CatalogoDB;
      $db=$database->connect();
      $query="UPDATE c_vehicle_versions SET c_vehicle_versions_extraSpecifications = '$config', C_Vehicle_Versions_Modified_By='$username' WHERE C_Vehicle_Versions_System_ID='$versionID'";
      //$db->set_charset("utf8");
      if(!($db->query($query)))
      {
        return false;
      }
      echo $config;
    }

    function throwLastValue($extSpecifict, $lastVal)
    {
        $nuevo=array();
        foreach ($extSpecifict as $specif => $exSpc)
        {
            if (is_array($extSpecifict[$specif]))
            {
                $nuevo[$specif] = $this->throwLastValue($extSpecifict[$specif], $lastVal);

            } else
            {
                $nuevo[$specif] = $lastVal;
            }
        }
        return $nuevo;
    }
    function replaceRecursive($truejson, $getfullCaracteristicasNjson, $nuevoArreglo,$padre){
      $new=array();
      $truejsonKey=key($truejson);
      if(!isset($getfullCaracteristicasNjson[$truejsonKey]))
      {
          if(is_array($truejson[$truejsonKey]))
          {
                $new[$padre][$truejsonKey]=$truejson[$truejsonKey];
          }
          else{
                $new[$padre]=$truejson;
          }
      }
      foreach ($getfullCaracteristicasNjson as $caracteristicas => $arrCaract) {
          if(isset($truejson[$caracteristicas]))
          {
              if(!is_array($truejson[$caracteristicas]))
              {
                  $new[$padre][$caracteristicas]=$truejson[$caracteristicas];

              }
              else
              {
                  $nuevo=$this->replaceRecursive($truejson[$caracteristicas], $getfullCaracteristicasNjson[$caracteristicas], $nuevoArreglo,$caracteristicas);
                  $new[$padre][$caracteristicas]=$nuevo[$caracteristicas];
              }
          }
          else {
              if(is_array($truejson[$truejsonKey])) {
                  $new[$padre][$caracteristicas] = $arrCaract;
              }


          }
      }
      return $new;
    }
    
    function unidades($unidadSimbolo)
    {
        $database=new CatalogoDB;
        $db=$database->connect();
        $query = "SELECT c_unit_symbol, c_unit_measurement_name_sp FROM c_unit WHERE c_unit_system_id = '$unidadSimbolo'";
        $result = $db -> query($query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $db->close();
        $unidades = array("nombre" => $row["c_unit_measurement_name_sp"], "simbolo" => $row["c_unit_symbol"]);
        return $unidades;
    }
    function replaceNumandText($truejson, $getfullCaracteristicasNjson, $nuevoArreglo,$padre){
        $new=array();
        $truejsonKey=key($truejson);
        if(!isset($getfullCaracteristicasNjson[$truejsonKey]))
        {
            $new[$padre][$truejsonKey]=$truejson[$truejsonKey];
        }
        foreach ($getfullCaracteristicasNjson as $caracteristicas => $arrCaract) {
            if(isset($truejson[$caracteristicas]))
            {
                if(!is_array($truejson[$caracteristicas]))
                {
                    $new[$padre][$caracteristicas]=$truejson[$caracteristicas];
                }
                else
                {
                    $nuevo=$this->replaceNumandText($truejson[$caracteristicas], $getfullCaracteristicasNjson[$caracteristicas], $nuevoArreglo,$caracteristicas);
                    $new[$padre][$caracteristicas]=$nuevo[$caracteristicas];
                }
            }
            else {
                $new[$padre][$caracteristicas]= $arrCaract;
            }
        }
        return $new;
    }

    public function featureVersion($versionid)
    {
        $dbcatalogo = new CatalogoDB;
        $dbcat = $dbcatalogo->connect();
        $query = "
          SELECT c_vehicle_model.C_Vehicle_Model_System_ID, c_vehicle_subbrand.C_Vehicle_SubBrand_System_ID , c_vehicle_model.C_Vehicle_Model, c_vehicle_subbrand.C_Vehicle_SubBrand_Name, c_vehicle_brand.C_Vehicle_Brand, C_Vehicle_Versions_System_ID, C_Vehicle_Versions_Name,c_vehicle_versions_extraSpecifications
          FROM c_vehicle_versions
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
        return $caracteristicas;
    }
  }
?>