<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAE_FUNCIONARIOS, "funcionario_Estado", "funcionario_Id", $_POST['pvi_FuncionarioId']);
  }else if($fn_Funcion == 'EditarFuncionario'){
    $json_Funcionario = $_POST['json_Funcionario'];

    $sql_EditarFuncionario = "UPDATE  ".MAE_FUNCIONARIOS."
                              SET     funcionario_Rut             = '".$json_Funcionario['vls_FuncionarioRut']."',
                                      cargoFuncionario_Id         = ".$json_Funcionario['vli_CargoFuncionarioId'].",
                                      funcionario_Nombres         = '".$json_Funcionario['vls_FuncionarioNombres']."',
                                      funcionario_ApellidoPaterno = '".$json_Funcionario['vls_FuncionarioApellidoP']."',
                                      funcionario_ApellidoMaterno = '".$json_Funcionario['vls_FuncionarioApellidoM']."',
                                      funcionario_Direccion       = '".$json_Funcionario['vls_FuncionarioDireccion']."',
                                      funcionario_Telefono        = '".$json_Funcionario['vls_FuncionarioTelefono']."'
                              WHERE   funcionario_Id = ".$_POST['pvi_FuncionarioId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarFuncionario);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
