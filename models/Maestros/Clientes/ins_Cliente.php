<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'InsertarCliente'){
    $json_Cliente = $_POST['json_Cliente'];

    $sql_InsertarCliente = "INSERT INTO ".MAE_CLIENTES." (
                              cliente_Tipo,
                              nacionalidad_Id,
                              cliente_Rut,
                              cliente_Nombres,
                              cliente_ApellidoPaterno,
                              cliente_ApellidoMaterno,
                              pais_Id,
                              region_Id,
                              ciudad_Id,
                              comuna_Id,
                              cliente_Direccion,
                              cliente_CorreoElectronico,
                              cliente_TelefonoFijo,
                              cliente_Celular
                            ) VALUES(
                              '".$json_Cliente['vls_ClienteTipo']."',
                              ".$json_Cliente['vli_NacionalidadId'].",
                              '".$json_Cliente['vls_ClienteRut']."',
                              '".$json_Cliente['vls_ClienteNombres']."',
                              '".$json_Cliente['vls_ClienteApellidoPaterno']."',
                              '".$json_Cliente['vls_ClienteApellidoMaterno']."',
                              ".($json_Cliente['vli_PaisId']==''?"NULL":$json_Cliente['vli_PaisId']).",
                              ".($json_Cliente['vli_RegionId']==''?"NULL":$json_Cliente['vli_RegionId']).",
                              ".($json_Cliente['vli_CiudadId']==''?"NULL":$json_Cliente['vli_CiudadId']).",
                              ".($json_Cliente['vli_ComunaId']==''?"NULL":$json_Cliente['vli_ComunaId']).",
                              '".$json_Cliente['vls_ClienteDireccion']."',
                              '".$json_Cliente['vls_ClienteCorreoElectronico']."',
                              '".$json_Cliente['vls_ClienteTelefonoFijo']."',
                              '".$json_Cliente['vls_ClienteCelular']."'
                            )";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarCliente);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }else if($fn_Funcion == 'InsertarValoracion'){
    $json_Valoracion = $_POST['json_Valoracion'];

    $sql_InsertarValoracion = "INSERT INTO ".MAE_CLIENTEVALORACIONES." (
                                cliente_Id,
                                reserva_Id,
                                valoracion_Puntaje,
                                valoracion_Observacion,
                                valoracion_UsuarioCrea,
                                valoracion_FechaCrea
                              ) VALUES(
                                ".$json_Valoracion['vli_ClienteId'].",
                                ".$json_Valoracion['vli_ReservaId'].",
                                ".$json_Valoracion['vli_ValoracionPuntaje'].",
                                '".$json_Valoracion['vls_ValoracionObservacion']."',
                                ".$vli_UsuarioId.",
                                NOW()
                              )";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarValoracion);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
