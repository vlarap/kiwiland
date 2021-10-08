<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion     = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'IngresarPagoReserva'){
    $json_Ingreso = $_POST['json_Ingreso'];

    $sql_InsertarIngreso = "INSERT INTO ".CON_INGRESOS." (
                              reserva_Id,
                              ingreso_Fecha,
                              tipoDoc_Id,
                              medioPago_Id,
                              categoria_Id,
                              ingreso_Monto,
                              ingreso_Comentario,
                              ingreso_UsuarioCrea,
                              ingreso_FechaCrea,
                              ingreso_UsuarioModifica,
                              ingreso_FechaModifica
                            ) VALUES(
                              ".$_POST['pvi_ReservaId'].",
                              '".$json_Ingreso['vld_IngresoFecha']."',
                              (SELECT tipoDoc_Id FROM ".MAN_TIPOSDOCUMENTO." WHERE tipoDoc_Sigla = 'RES'),
                              ".$json_Ingreso['vli_MedioPagoId'].",
                              (SELECT categoria_Id FROM ".MAN_CATEGORIAS." WHERE categoria_Sigla = '".$json_Ingreso['vls_CategoriaId']."'),
                              ".$json_Ingreso['vli_IngresoMonto'].",
                              '".$json_Ingreso['vls_IngresoComentario']."',
                              ".$vli_UsuarioId.",
                              NOW(),
                              ".$vli_UsuarioId.",
                              NOW()
                            )";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarIngreso);
      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
