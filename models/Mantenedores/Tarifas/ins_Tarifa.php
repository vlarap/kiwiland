<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarTarifa'){
    $json_Tarifa = $_POST['json_Tarifa'];

    $sql_InsertarTarifa = "INSERT INTO ".MAN_TARIFAS." (tarifa_Tipo, tarifa_CantPersonas, tarifa_Valor) VALUES('".$json_Tarifa['vls_TarifaTipo']."',".$json_Tarifa['vli_TarifaCantPersonas'].",".$json_Tarifa['vli_TarifaValor'].")";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarTarifa);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
