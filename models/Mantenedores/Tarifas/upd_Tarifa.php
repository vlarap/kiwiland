<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_TARIFAS, "tarifa_Estado", "tarifa_Id", $_POST['pvi_TarifaId']);
  }else if($fn_Funcion == 'EditarTarifa'){
    $json_Tarifa= $_POST['json_Tarifa'];

    $sql_EditarTarifa = "UPDATE ".MAN_TARIFAS."
                         SET    tarifa_Tipo         = '".$json_Tarifa['vls_TarifaTipo']."',
                                tarifa_CantPersonas = ".$json_Tarifa['vli_TarifaCantPersonas'].",
                                tarifa_Valor        = ".$json_Tarifa['vli_TarifaValor']."
                         WHERE  tarifa_Id = ".$_POST['pvi_TarifaId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarTarifa);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
