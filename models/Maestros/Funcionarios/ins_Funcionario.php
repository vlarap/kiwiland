<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarFuncionario'){
    $json_Funcionario = $_POST['json_Funcionario'];

    $sql_InsertarFuncionario = "INSERT INTO ".MAE_FUNCIONARIOS." (
                                  funcionario_Rut,
                                  cargoFuncionario_Id,
                                  funcionario_Nombres,
                                  funcionario_ApellidoPaterno,
                                  funcionario_ApellidoMaterno,
                                  funcionario_Direccion,
                                  funcionario_Telefono
                                ) VALUES(
                                  '".$json_Funcionario['vls_FuncionarioRut']."',
                                  ".$json_Funcionario['vli_CargoFuncionarioId'].",
                                  '".$json_Funcionario['vls_FuncionarioNombres']."',
                                  '".$json_Funcionario['vls_FuncionarioApellidoP']."',
                                  '".$json_Funcionario['vls_FuncionarioApellidoM']."',
                                  '".$json_Funcionario['vls_FuncionarioDireccion']."',
                                  '".$json_Funcionario['vls_FuncionarioTelefono']."'
                                )";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarFuncionario);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
