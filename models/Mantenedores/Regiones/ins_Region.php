<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarRegion'){
    $json_Region = $_POST['json_Region'];

    $sql_InsertarRegion = "INSERT INTO ".MAN_REGIONES." (region_Nombre) VALUES('".$json_Region['vls_RegionNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarRegion);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
