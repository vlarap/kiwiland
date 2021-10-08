<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_CARGOSFUNCIONARIO, "cargoFuncionario_Estado", "cargoFuncionario_Id", $_POST['pvi_CargoFuncionarioId']);
  }else if($fn_Funcion == 'EditarCargoFuncionario'){
    $json_CargoFuncionario = $_POST['json_CargoFuncionario'];

    $sql_EditarCargoFuncionario = "UPDATE ".MAN_CARGOSFUNCIONARIO."
                                   SET    cargoFuncionario_Nombre  = '".$json_CargoFuncionario['vls_CargoFuncionarioNombre']."'
                                   WHERE  cargoFuncionario_Id = ".$_POST['pvi_CargoFuncionarioId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarCargoFuncionario);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
