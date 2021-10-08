<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion     = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'EditarReserva'){
    $json_Reserva = $_POST['json_Reserva'];

    $sql_EditarReserva = "UPDATE  ".RES_RESERVAS."
                          SET     cliente_Tipo              = '".$json_Reserva['vls_ClienteTipo']."',
                                  cliente_Id                = ".($json_Reserva['vli_ClienteId']==''?"NULL":$json_Reserva['vli_ClienteId']).",
                                  nacionalidad_Id           = (SELECT pais_Id FROM ".MAN_PAISES." WHERE pais_Sigla = '".$json_Reserva['vls_Nacionalidad']."'),
                                  cliente_Rut               = '".$json_Reserva['vls_ClienteRut']."',
                                  cliente_Nombres           = '".$json_Reserva['vls_ClienteNombres']."',
                                  cliente_ApellidoPaterno   = '".$json_Reserva['vls_ClienteApellidoPaterno']."',
                                  cliente_ApellidoMaterno   = '".$json_Reserva['vls_ClienteApellidoMaterno']."',
                                  pais_Id                   = ".($json_Reserva['vli_PaisId']==''?"NULL":$json_Reserva['vli_PaisId']).",
                                  region_Id                 = ".($json_Reserva['vli_RegionId']==''?"NULL":$json_Reserva['vli_RegionId']).",
                                  ciudad_Id                 = ".($json_Reserva['vli_CiudadId']==''?"NULL":$json_Reserva['vli_CiudadId']).",
                                  comuna_Id                 = ".($json_Reserva['vli_ComunaId']==''?"NULL":$json_Reserva['vli_ComunaId']).",
                                  cliente_Direccion         = '".$json_Reserva['vls_ClienteDireccion']."',
                                  cliente_CorreoElectronico = '".$json_Reserva['vls_ClienteCorreo']."',
                                  cliente_TelefonoFijo      = '".$json_Reserva['vls_ClienteTelefonoFijo']."',
                                  cliente_Celular           = '".$json_Reserva['vls_ClienteCelular']."',
                                  reserva_HoraLlegada       = '".$json_Reserva['vld_ReservaHoraDesde']."',
                                  reserva_HoraSalida        = '".$json_Reserva['vld_ReservaHoraHasta']."',
                                  reserva_Adultos           = ".$json_Reserva['vli_ReservaAdultos'].",
                                  reserva_Ninos             = ".$json_Reserva['vli_ReservaNinos'].",
                                  origen_Id                 = ".$json_Reserva['vli_OrigenId']."
                          WHERE   reserva_Id = ".$_POST['pvi_ReservaId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarReserva);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }else if($fn_Funcion == 'ActualizarPago'){
    $json_Pago = $_POST['json_Pago'];

    $sql_ActualizarPago = "UPDATE ".RES_RESERVAS."
                           SET    medioPago_Id            = ".$json_Pago['vli_MedioPagoId'].",
                                  reserva_FechaPago       = NOW(),
                                  reserva_TotalPago       = ".$json_Pago['vli_TotalPago'].",
                                  reserva_EstadoComercial = 'PA'
                           WHERE  reserva_Id = ".$_POST['pvi_ReservaId'];

    try{
      $sql_Query = $sql_DB->query($sql_ActualizarPago);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }else if($fn_Funcion == 'CheckOut'){
    $json_Pago = $_POST['json_Pago'];

    $sql_ActualizarPago = "UPDATE ".RES_RESERVAS."
                           SET    reserva_EstadoComercial = 'PA'
                           WHERE  reserva_Id = ".$_POST['pvi_ReservaId'];

    try{
      $sql_Query = $sql_DB->query($sql_ActualizarPago);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
