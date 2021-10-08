<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarUsuario'){
    $json_Usuario = $_POST['json_Usuario'];

    $sql_InsertarUsuario = "INSERT INTO ".ADM_USUARIOS." (
                              funcionario_Id,
                              usuario_Nivel,
                              usuario_Nombre,
                              usuario_Contrasena
                            ) VALUES(
                              ".$json_Usuario['vli_FuncionarioId'].",
                              '".$json_Usuario['vls_UsuarioNivel']."',
                              '".$json_Usuario['vls_UsuarioNombre']."',
                              '".md5($json_Usuario['vls_UsuarioContrasena'])."'
                            )";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarUsuario);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
