<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarServicio'){
    $json_Servicio = $_POST['json_Servicio'];

    $sql_InsertarServicio = "INSERT INTO ".MAN_SERVICIOS." (servicio_Nombre) VALUES('".$json_Servicio['vls_ServicioNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarServicio);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
