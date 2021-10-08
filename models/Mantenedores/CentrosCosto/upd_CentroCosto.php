<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_CENTROSCOSTO, "cCosto_Estado", "cCosto_Id", $_POST['pvi_CCostoId']);
  }else if($fn_Funcion == 'EditarCentroCosto'){
    $json_CentroCosto = $_POST['json_CentroCosto'];

    $sql_EditarCentroCosto = "UPDATE ".MAN_CENTROSCOSTO."
                              SET    cCosto_Nombre = '".$json_CentroCosto['vls_CCostoNombre']."'
                              WHERE  cCosto_Id = ".$_POST['pvi_CCostoId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarCentroCosto);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
