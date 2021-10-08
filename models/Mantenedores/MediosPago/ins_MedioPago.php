<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarMedioPago'){
    $json_MedioPago = $_POST['json_MedioPago'];

    $sql_InsertarMedioPago = "INSERT INTO ".MAN_MEDIOSPAGO." (medioPago_Nombre) VALUES('".$json_MedioPago['vls_MedioPagoNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarMedioPago);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
