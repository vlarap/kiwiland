<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarCentroCosto'){
    $json_CentroCosto = $_POST['json_CentroCosto'];

    $sql_InsertarCentroCosto = "INSERT INTO ".MAN_CENTROSCOSTO." (cCosto_Nombre) VALUES('".$json_CentroCosto['vls_CCostoNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarCentroCosto);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
