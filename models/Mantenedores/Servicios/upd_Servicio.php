<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_SERVICIOS, "servicio_Estado", "servicio_Id", $_POST['pvi_ServicioId']);
  }else if($fn_Funcion == 'EditarServicio'){
    $json_Servicio = $_POST['json_Servicio'];

    $sql_EditarServicio = "UPDATE ".MAN_SERVICIOS."
                           SET    servicio_Nombre  = '".$json_Servicio['vls_ServicioNombre']."'
                           WHERE  servicio_Id = ".$_POST['pvi_ServicioId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarServicio);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
