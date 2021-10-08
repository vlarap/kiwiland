<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarOrigen'){
    $json_Origen = $_POST['json_Origen'];

    $sql_InsertarOrigen = "INSERT INTO ".MAN_ORIGENES." (origen_Nombre) VALUES('".$json_Origen['vls_OrigenNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarOrigen);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
