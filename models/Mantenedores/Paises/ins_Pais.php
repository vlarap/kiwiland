<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarPais'){
    $json_Pais = $_POST['json_Pais'];

    $sql_InsertarPais = "INSERT INTO ".MAN_PAISES." (pais_Nombre) VALUES('".$json_Pais['vls_PaisNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarPais);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
