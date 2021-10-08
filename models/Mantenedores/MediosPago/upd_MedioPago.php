<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_MEDIOSPAGO, "medioPago_Estado", "medioPago_Id", $_POST['pvi_MedioPagoId']);
  }else if($fn_Funcion == 'EditarMedioPago'){
    $json_MedioPago = $_POST['json_MedioPago'];

    $sql_EditarMedioPago = "UPDATE ".MAN_MEDIOSPAGO."
                            SET    medioPago_Nombre = '".$json_MedioPago['vls_MedioPagoNombre']."'
                            WHERE  medioPago_Id = ".$_POST['pvi_MedioPagoId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarMedioPago);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
