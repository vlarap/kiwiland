<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  medioPago_Id,
                                medioPago_Nombre,
                                medioPago_Estado
                        FROM    ".MAN_MEDIOSPAGO;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->medioPago_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoMedioPago(".$sql_Fila->medioPago_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoMedioPago(".$sql_Fila->medioPago_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "medioPago_Nombre"   =>  $sql_Fila->medioPago_Nombre,
        "medioPago_Estado"   =>  fn_TransformarEstado($sql_Fila->medioPago_Estado),
        "medioPago_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarMedioPago('.$sql_Fila->medioPago_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                   ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarMedioPago'){
    $sql_EditarMedioPago = "SELECT medioPago_Nombre
                            FROM   ".MAN_MEDIOSPAGO."
                            WHERE  medioPago_Id = ".$_POST['pvi_MedioPagoId'];

    $sql_Query = $sql_DB->query($sql_EditarMedioPago);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "medioPago_Nombre" =>  $sql_Fila->medioPago_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarMediosPago'){
    $sql_CargarMedios = "SELECT    medioPago_Id,
                                   medioPago_Nombre
                         FROM      ".MAN_MEDIOSPAGO."
                         WHERE     medioPago_Estado = 'A'
                         ORDER BY  medioPago_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarMedios);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->medioPago_Id,
        "nombre"  =>  $sql_Fila->medioPago_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
