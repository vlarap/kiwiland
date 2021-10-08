<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarCargoFuncionario'){
    $json_CargoFuncionario = $_POST['json_CargoFuncionario'];

    $sql_InsertarCargoFuncionario = "INSERT INTO ".MAN_CARGOSFUNCIONARIO." (cargoFuncionario_Nombre) VALUES('".$json_CargoFuncionario['vls_CargoFuncionarioNombre']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarCargoFuncionario);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
