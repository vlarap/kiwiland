<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], ADM_USUARIOS, "usuario_Estado", "usuario_Id", $_POST['pvi_UsuarioId']);
  }else if($fn_Funcion == 'EditarUsuario'){
    $json_Usuario = $_POST['json_Usuario'];

    $sql_EditarUsuario = "UPDATE  ".ADM_USUARIOS."
                          SET     funcionario_Id      = ".$json_Usuario['vli_FuncionarioId'].",
                                  usuario_Nivel       = '".$json_Usuario['vls_UsuarioNivel']."',
                                  usuario_Nombre      = '".$json_Usuario['vls_UsuarioNombre']."',
                                  usuario_Contrasena  = ".($json_Usuario['vls_UsuarioContrasena']==''?"usuario_Contrasena":"'".md5($json_Usuario['vls_UsuarioContrasena'])."'")."
                          WHERE   usuario_Id = ".$_POST['pvi_UsuarioId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarUsuario);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
