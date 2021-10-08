<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'EliminarServicio'){
    $sql_EliminarServicio = "DELETE FROM ".MAE_SERVICIOSPROPIEDAD." WHERE propiedad_Id = ".$_POST['pvi_PropiedadId']." AND servicio_Id = ".$_POST['pvi_ServicioId'];

    try{
      $sql_Query = $sql_DB->query($sql_EliminarServicio);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
