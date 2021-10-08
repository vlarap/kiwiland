<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_TIPOSDOCUMENTO, "tipoDoc_Estado", "tipoDoc_Id", $_POST['pvi_TipoDocumentoId']);
  }else if($fn_Funcion == 'EditarTipoDocumento'){
    $json_TipoDocumento = $_POST['json_TipoDocumento'];

    $sql_EditarTipoDocumento = "UPDATE ".MAN_TIPOSDOCUMENTO."
                                SET    tipoDoc_Nombre = '".$json_TipoDocumento['vls_TipoDocumentoNombre']."'
                                WHERE  tipoDoc_Id = ".$_POST['pvi_TipoDocumentoId'];
    try{
      $sql_Query = $sql_DB->query($sql_EditarTipoDocumento);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
