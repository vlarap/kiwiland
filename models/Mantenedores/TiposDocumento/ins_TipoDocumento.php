<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarTipoDocumento'){
    $json_TipoDocumento = $_POST['json_TipoDocumento'];

    $sql_InsertarTipoDocumento = "INSERT INTO ".MAN_TIPOSDOCUMENTO." (tipoDoc_Nombre) VALUES('".$json_TipoDocumento['vls_TipoDocumentoNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarTipoDocumento);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
