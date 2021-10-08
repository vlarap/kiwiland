<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../includes/plugins/PHPMailer/class.phpmailer.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion     = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'InsertarReserva'){
    $json_Reserva = $_POST['json_Reserva'];
    //VERIFICAR DISPONIBILIDAD
    $sql_VerificarDisponibilidad = "SELECT  COUNT(*) AS Contador
                                    FROM    ".RES_RESERVAS."
                                    WHERE   propiedad_Id = ".$json_Reserva['vli_PropiedadId']." AND
                                            ((reserva_FechaDesde <= '".$json_Reserva['vld_ReservaFechaDesde']."' AND reserva_FechaHasta >= '".$json_Reserva['vld_ReservaFechaHasta']."')
                                            OR reserva_FechaHasta BETWEEN '".$json_Reserva['vld_ReservaFechaDesde']."' AND '".$json_Reserva['vld_ReservaFechaHasta']."'
                                            OR reserva_FechaDesde BETWEEN '".$json_Reserva['vld_ReservaFechaDesde']."' AND '".$json_Reserva['vld_ReservaFechaHasta']."')";

    $sql_Query = $sql_DB->query($sql_VerificarDisponibilidad);
    $sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ);
    $vli_Contador = $sql_Fila->Contador;

    if($vli_Contador == 0){ //DISPONIBLE
      if($json_Reserva['vli_OrigenId'] == 'Web'){
        $sql_BuscarOrigen = "SELECT origen_Id FROM ".MAN_ORIGENES." WHERE origen_Nombre = '".$json_Reserva['vli_OrigenId']."'";
        $sql_Query        = $sql_DB->query($sql_BuscarOrigen);
        $sql_Fila         = $sql_Query->fetch(PDO::FETCH_OBJ);
        $vli_ReservaId    = $sql_Fila->origen_Id;
        $vli_UsuarioId    = 0;
      }else{
        $vli_ReservaId = $json_Reserva['vli_OrigenId'];
      }


      try{
        $sql_DB->beginTransaction();
        if($json_Reserva['vli_ClienteId'] == ''){
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
                                    '".$json_Reserva['vls_ClienteTipo']."',
                                    (SELECT pais_Id FROM ".MAN_PAISES." WHERE pais_Sigla = '".$json_Reserva['vls_Nacionalidad']."'),
                                    '".$json_Reserva['vls_ClienteRut']."',
                                    '".$json_Reserva['vls_ClienteNombres']."',
                                    '".$json_Reserva['vls_ClienteApellidoPaterno']."',
                                    '".$json_Reserva['vls_ClienteApellidoMaterno']."',
                                    ".($json_Reserva['vli_PaisId']==''?"NULL":$json_Reserva['vli_PaisId']).",
                                    ".($json_Reserva['vli_RegionId']==''?"NULL":$json_Reserva['vli_RegionId']).",
                                    ".($json_Reserva['vli_CiudadId']==''?"NULL":$json_Reserva['vli_CiudadId']).",
                                    ".($json_Reserva['vli_ComunaId']==''?"NULL":$json_Reserva['vli_ComunaId']).",
                                    '".$json_Reserva['vls_ClienteDireccion']."',
                                    '".$json_Reserva['vls_ClienteCorreo']."',
                                    '".$json_Reserva['vls_ClienteTelefonoFijo']."',
                                    '".$json_Reserva['vls_ClienteCelular']."'
                                  )";
          $sql_DB->exec($sql_InsertarCliente);
          $vli_ClienteId = $sql_DB->lastInsertId();
        }

        $sql_InsertarReserva = "INSERT INTO ".RES_RESERVAS." (
                                  reserva_FechaCrea,
                                  reserva_UsuarioCrea,
                                  cliente_Tipo,
                                  cliente_Id,
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
                                  cliente_Celular,
                                  propiedad_Id,
                                  reserva_FechaDesde,
                                  reserva_FechaHasta,
                                  reserva_HoraLlegada,
                                  reserva_HoraSalida,
                                  reserva_Adultos,
                                  reserva_Ninos,
                                  origen_Id
                                ) VALUES(
                                  NOW(),
                                  ".$vli_UsuarioId.",
                                  '".$json_Reserva['vls_ClienteTipo']."',
                                  ".($json_Reserva['vli_ClienteId']==''?$vli_ClienteId:$json_Reserva['vli_ClienteId']).",
                                  (SELECT pais_Id FROM ".MAN_PAISES." WHERE pais_Sigla = '".$json_Reserva['vls_Nacionalidad']."'),
                                  '".$json_Reserva['vls_ClienteRut']."',
                                  '".$json_Reserva['vls_ClienteNombres']."',
                                  '".$json_Reserva['vls_ClienteApellidoPaterno']."',
                                  '".$json_Reserva['vls_ClienteApellidoMaterno']."',
                                  ".($json_Reserva['vli_PaisId']==''?"NULL":$json_Reserva['vli_PaisId']).",
                                  ".($json_Reserva['vli_RegionId']==''?"NULL":$json_Reserva['vli_RegionId']).",
                                  ".($json_Reserva['vli_CiudadId']==''?"NULL":$json_Reserva['vli_CiudadId']).",
                                  ".($json_Reserva['vli_ComunaId']==''?"NULL":$json_Reserva['vli_ComunaId']).",
                                  '".$json_Reserva['vls_ClienteDireccion']."',
                                  '".$json_Reserva['vls_ClienteCorreo']."',
                                  '".$json_Reserva['vls_ClienteTelefonoFijo']."',
                                  '".$json_Reserva['vls_ClienteCelular']."',
                                  ".$json_Reserva['vli_PropiedadId'].",
                                  '".$json_Reserva['vld_ReservaFechaDesde']."',
                                  '".$json_Reserva['vld_ReservaFechaHasta']."',
                                  '".$json_Reserva['vld_ReservaHoraDesde']."',
                                  '".$json_Reserva['vld_ReservaHoraHasta']."',
                                  ".$json_Reserva['vli_ReservaAdultos'].",
                                  ".$json_Reserva['vli_ReservaNinos'].",
                                  ".$vli_ReservaId."
                                )";

        $sql_DB->exec($sql_InsertarReserva);
        $vli_ReservaId = $sql_DB->lastInsertId();

        $sql_DB->commit();
        $vli_Retorno = 1; //RETORNO ACEPTACION DE REGISTRO
      }catch (PDOException $e){
        echo $e->getMessage();
        $sql_DB->rollBack();
      }

      if($vli_Retorno == 1){
        //DATOS PROPIEDAD
        $sql_BuscarDatos = "SELECT  propiedad_Nombre
                            FROM    ".MAE_PROPIEDADES."
                            WHERE   propiedad_Id = ".$json_Reserva['vli_PropiedadId'];
        $sql_Query = $sql_DB->query($sql_BuscarDatos);
        $sql_Fila  = $sql_Query->fetch(PDO::FETCH_OBJ);
        $vls_PropiedadNombre = $sql_Fila->propiedad_Nombre;

        //CANTIDAD DE NOCHES
        $vld_Desde = new DateTime($json_Reserva['vld_ReservaFechaDesde']);
        $vld_Hasta = new DateTime($json_Reserva['vld_ReservaFechaHasta']);
        $vli_Dias  = $vld_Hasta->diff($vld_Desde)->format("%a");

        //CARGAMOS LA TARIFA
        $sql_BuscarDatos = "SELECT tarifa_Valor,
                                   CASE
                                      WHEN '".$json_Reserva['vld_ReservaHoraHasta']."' = (SELECT cfg_LateCheckOut FROM ".ADM_CONFIGURACIONES." WHERE cfg_Id = 1) THEN (SELECT cfg_LCOPrecio FROM ".ADM_CONFIGURACIONES." WHERE cfg_Id = 1)
                                      ELSE 0
                                   END AS lateCheckOut
                            FROM   ".MAN_TARIFAS."
                            WHERE  tarifa_CantPersonas = (".((int)$json_Reserva['vli_ReservaAdultos'] + (int)$json_Reserva['vli_ReservaNinos']).") AND tarifa_Tipo = '".$json_Reserva['vls_ClienteTipo']."'";
        $sql_Query  = $sql_DB->query($sql_BuscarDatos);
        $sql_Fila   = $sql_Query->fetch(PDO::FETCH_OBJ);
        $vli_Tarifa = (int)$sql_Fila->tarifa_Valor + (int)$sql_Fila->lateCheckOut;

        // ENVIO DE CORREO DE RESERVA
        $vls_Contenido    = '';
        $mail             = new PHPMailer();
        $mail->From       = 'no-responder@kiwiland.cl';
        $mail->FromName   = "Cabañas Kiwiland";
        $mail->Subject    = 'Reserva - Nro. ' . $vli_ReservaId;

        $vls_Contenido    .= '<html>';
        $vls_Contenido    .=  '<body style="font-size: 15px; font-family: Tahoma, Arial;">';
        $vls_Contenido    .=   '<span style="display: block;">Hola <b>'.$json_Reserva['vls_ClienteNombres']." ".$json_Reserva['vls_ClienteApellidoPaterno']." ".$json_Reserva['vls_ClienteApellidoMaterno'].'</b>,</span>';
        $vls_Contenido    .=   '<span style="display: block;">Gracias por confiar en nosotros. Tu reserva está en espera hasta que confirmemos que se ha recibido el pago. Mientras tanto, aquí tienes un recordatorio de los detalles de tu reserva:</span>';
        $vls_Contenido    .=   '<br/>';
        $vls_Contenido    .=   '<span style="display: block;"><b>Banco ITAU</b></span>';
        $vls_Contenido    .=   '<span style="display: block;"><b>Cuenta corriente</b>: 0208542590</span>';
        $vls_Contenido    .=   '<span style="display: block;"><b>Nombre</b>: Carmen Luz Gallardo Alvear</span>';
        $vls_Contenido    .=   '<span style="display: block;"><b>RUT</b>: 9.692.386-4</span>';
        $vls_Contenido    .=   '<span style="display: block;"><b>Correo</b>: contacto@kiwiland.cl</span>';
        $vls_Contenido    .=   '<br/>';
        $vls_Contenido    .=   '<span style="display: block; font-size:17px; color:#96588a; font-weight:bold;">[Reserva #'.$vli_ReservaId.'] ('.fn_TransformarFechaSQL($json_Reserva['vld_ReservaFechaDesde']).')</span>';
        $vls_Contenido    .=   '<br/>';
        $vls_Contenido    .=   '<table style="width: 400px; font-size: 14px; border: 1px solid #BCBCBC; border-collapse: collapse;">';
        $vls_Contenido    .=    '<thead style="font-weight: bold;">';
        $vls_Contenido    .=     '<td style="padding: 5px; font-size: 14px; border: 1px solid #BCBCBC; border-collapse: collapse;">Cabaña / Nro. de Pasajeros</td>';
        $vls_Contenido    .=     '<td style="padding: 5px; font-size: 14px; border: 1px solid #BCBCBC; border-collapse: collapse;">Nro. de noches</td>';
        $vls_Contenido    .=     '<td style="padding: 5px; font-size: 14px; border: 1px solid #BCBCBC; border-collapse: collapse;">Precio</td>';
        $vls_Contenido    .=    '</thead>';
        $vls_Contenido    .=    '<tbody>';
        $vls_Contenido    .=     '<tr>';
        $vls_Contenido    .=      '<td style="padding: 5px; font-size: 14px; border: 1px solid #BCBCBC; border-collapse: collapse;"> '.$vls_PropiedadNombre.' / '.((int)$json_Reserva['vli_ReservaAdultos'] + (int)$json_Reserva['vli_ReservaNinos']).'</td>';
        $vls_Contenido    .=      '<td style="padding: 5px; font-size: 14px; border: 1px solid #BCBCBC; border-collapse: collapse;">'.$vli_Dias.'</td>';
        $vls_Contenido    .=      '<td style="padding: 5px; font-size: 14px; border: 1px solid #BCBCBC; border-collapse: collapse;">'.money_format2("%.0n", $vli_Tarifa).'</td>';
        $vls_Contenido    .=     '</tr>';
        $vls_Contenido    .=    '</tbody>';
        $vls_Contenido    .=   '</table>';
        $vls_Contenido    .=   '<br/>';
        $vls_Contenido    .=   '<span style="display: block; font-size:17px; color:#96588a; font-weight:bold;">Dirección de facturación</span>';
        $vls_Contenido    .=   '<br/>';
        $vls_Contenido    .=   '<div style="width: 400px; border: 1px solid #BCBCBC; padding: 15px; box-sizing: border-box;">';
        $vls_Contenido    .=    '<span style="display: block;">Carmen Gallardo</span>';
        $vls_Contenido    .=    '<span style="display: block;">Agua santa 3218</span>';
        $vls_Contenido    .=    '<span style="display: block;">Tarapacá</span>';
        $vls_Contenido    .=    '<span style="display: block;">Iquique</span>';
        $vls_Contenido    .=    '<span style="display: block;">+56 9 93592555</span>';
        $vls_Contenido    .=    '<span style="display: block;">jbradnock@hotmail.com</span>';
        $vls_Contenido    .=   '</div>';
        $vls_Contenido    .=   '<br/></br>';
        $vls_Contenido    .=   '<span style="display: block;">Lo único que queremos es que disfrutes y te desconectes un rato!</span>';
        $vls_Contenido    .=   '<span style="display: block;">Te esperamos</span>';
        $vls_Contenido    .=   '<br/><br/>';
        $vls_Contenido    .=   '<span style="display: block;">Saludos,</span>';
        $vls_Contenido    .=   '<span style="display: block;">Equipo de Kiwiland</span>';
        $vls_Contenido    .=  '</body>';
        $vls_Contenido    .= '</html>';

        $mail->Body       = $vls_Contenido;
        $mail->IsHTML(true);
        $mail->AddAddress($json_Reserva['vls_ClienteCorreo']);

        $mail->CharSet = 'UTF-8';
        $exito = $mail->Send();

        echo $vli_Retorno;
      }else{
        echo $vli_Retorno;
      }
    }else{
      echo -1; //ERROR EN DISPONIBILIDAD
    }
  }
?>
