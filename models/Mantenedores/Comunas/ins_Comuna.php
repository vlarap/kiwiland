<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarComuna'){
    $json_Comuna = $_POST['json_Comuna'];

    $sql_InsertarComuna = "INSERT INTO ".MAN_COMUNAS." (comuna_Nombre, ciudad_Id) VALUES('".$json_Comuna['vls_ComunaNombre']."',".$json_Comuna['vli_CiudadId'].")";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarComuna);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
