<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_ORIGENES, "origen_Estado", "origen_Id", $_POST['pvi_OrigenId']);
  }else if($fn_Funcion == 'EditarOrigen'){
    $json_Origen = $_POST['json_Origen'];

    $sql_EditarOrigen = "UPDATE ".MAN_ORIGENES."
                         SET    origen_Nombre  = '".$json_Origen['vls_OrigenNombre']."'
                         WHERE  origen_Id = ".$_POST['pvi_OrigenId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarOrigen);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
