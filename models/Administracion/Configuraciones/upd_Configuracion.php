<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'EditarConfiguracion'){
    $json_Configuracion = $_POST['json_Configuracion'];

    $sql_EditarConfiguracion = "UPDATE  ".ADM_CONFIGURACIONES."
                                SET     cfg_CheckIn      = '".$json_Configuracion['vld_CFGCheckIn']."',
                                        cfg_CheckOut     = '".$json_Configuracion['vld_CFGCheckOut']."',
                                        cfg_LateCheckOut = '".$json_Configuracion['vld_CFGLateCheckOut']."',
                                        cfg_LCOPrecio    = ".$json_Configuracion['vli_CFGLCOPrecio']."
                                WHERE   cfg_Id = 1";

    try{
      $sql_Query = $sql_DB->query($sql_EditarConfiguracion);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }else if($fn_Funcion == 'EditarPermiso'){
    $json_Permiso = $_POST['json_Permiso'];

    $sql_Comprobar = "SELECT  COUNT(*) AS Comprobar
                      FROM    ".ADM_PERMISOS."
                      WHERE   modulo_Id = ".$json_Permiso['vli_ModuloId']." AND usuario_Id = ".$json_Permiso['vli_UsuarioId'];

    $sql_Query = $sql_DB->query($sql_Comprobar);
    $sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ);

    if($sql_Fila->Comprobar == 0){ //SE CREA
      $vls_PermisoCrear       = 'I';
      $vls_PermisoLeer        = 'I';
      $vls_PermisoActualizar  = 'I';
      $vls_PermisoEliminar    = 'I';
      $vls_PermisoPagar       = 'I';

      if($json_Permiso['vls_Columna'] == 'permiso_Crear'){
        $vls_PermisoCrear = 'A';
      }else if($json_Permiso['vls_Columna'] == 'permiso_Leer'){
        $vls_PermisoLeer = 'A';
      }else if($json_Permiso['vls_Columna'] == 'permiso_Actualizar'){
        $vls_PermisoActualizar = 'A';
      }else if($json_Permiso['vls_Columna'] == 'permiso_Eliminar'){
        $vls_PermisoEliminar = 'A';
      }else if($json_Permiso['vls_Columna'] == 'permiso_Pagar'){
        $vls_PermisoEliminar = 'A';
      }

      $sql_Permiso = "INSERT INTO ".ADM_PERMISOS." (usuario_Id, modulo_Id, permiso_Crear, permiso_Leer, permiso_Actualizar, permiso_Eliminar, permiso_Pagar)
                      VALUES(".$json_Permiso['vli_UsuarioId'].", ".$json_Permiso['vli_ModuloId'].",'".$vls_PermisoCrear."','".$vls_PermisoLeer."','".$vls_PermisoActualizar."','".$vls_PermisoEliminar."','".$vls_PermisoPagar."')";
    }else{ //SE EDITA
      $sql_Permiso = "UPDATE  ".ADM_PERMISOS."
                      SET     ".$json_Permiso['vls_Columna']." = '".$json_Permiso['vls_Check']."'
                      WHERE   modulo_Id = ".$json_Permiso['vli_ModuloId']." AND usuario_Id = ".$json_Permiso['vli_UsuarioId'];
    }

    try{
      $sql_Query = $sql_DB->query($sql_Permiso);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
