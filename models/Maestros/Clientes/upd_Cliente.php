<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAE_CLIENTES, "cliente_Estado", "cliente_Id", $_POST['pvi_ClienteId']);
  }else if($fn_Funcion == 'EditarCliente'){
    $json_Cliente = $_POST['json_Cliente'];

    $sql_EditarCliente = "UPDATE  ".MAE_CLIENTES."
                          SET     cliente_Tipo              = '".$json_Cliente['vls_ClienteTipo']."',
                                  nacionalidad_Id           = ".$json_Cliente['vli_NacionalidadId'].",
                                  cliente_Rut               = '".$json_Cliente['vls_ClienteRut']."',
                                  cliente_Nombres           = '".$json_Cliente['vls_ClienteNombres']."',
                                  cliente_ApellidoPaterno   = '".$json_Cliente['vls_ClienteApellidoPaterno']."',
                                  cliente_ApellidoMaterno   = '".$json_Cliente['vls_ClienteApellidoMaterno']."',
                                  pais_Id                   = ".($json_Cliente['vli_PaisId']==''?"NULL":$json_Cliente['vli_PaisId']).",
                                  region_Id                 = ".($json_Cliente['vli_RegionId']==''?"NULL":$json_Cliente['vli_RegionId']).",
                                  ciudad_Id                 = ".($json_Cliente['vli_CiudadId']==''?"NULL":$json_Cliente['vli_CiudadId']).",
                                  comuna_Id                 = ".($json_Cliente['vli_ComunaId']==''?"NULL":$json_Cliente['vli_ComunaId']).",
                                  cliente_Direccion         = '".$json_Cliente['vls_ClienteDireccion']."',
                                  cliente_CorreoElectronico = '".$json_Cliente['vls_ClienteCorreoElectronico']."',
                                  cliente_TelefonoFijo      = '".$json_Cliente['vls_ClienteTelefonoFijo']."',
                                  cliente_Celular           = '".$json_Cliente['vls_ClienteCelular']."'
                          WHERE   cliente_Id = ".$_POST['pvi_ClienteId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarCliente);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }else if($fn_Funcion == 'EditarValoracion'){
    $json_Valoracion = $_POST['json_Valoracion'];

    $sql_EditarValoracion = "UPDATE  ".MAE_CLIENTEVALORACIONES."
                             SET     valoracion_Puntaje         = ".$json_Valoracion['vli_ValoracionPuntaje'].",
                                     valoracion_Observacion     = '".$json_Valoracion['vls_ValoracionObservacion']."',
                                     valoracion_UsuarioModifica = ".$vli_UsuarioId.",
                                     valoracion_FechaModifica   = NOW()
                             WHERE   valoracion_Id = ".$_POST['pvi_ValoracionId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarValoracion);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
