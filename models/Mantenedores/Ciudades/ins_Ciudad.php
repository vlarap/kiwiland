<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarCiudad'){
    $json_Ciudad = $_POST['json_Ciudad'];

    $sql_InsertarCiudad = "INSERT INTO ".MAN_CIUDADES." (ciudad_Nombre, region_Id) VALUES('".$json_Ciudad['vls_CiudadNombre']."',".$json_Ciudad['vli_RegionId'].")";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarCiudad);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
