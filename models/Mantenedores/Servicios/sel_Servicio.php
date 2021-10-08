<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  servicio_Id,
                                servicio_Nombre,
                                servicio_Estado
                        FROM    ".MAN_SERVICIOS;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->servicio_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoServicio(".$sql_Fila->servicio_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoServicio(".$sql_Fila->servicio_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "servicio_Nombre"   => $sql_Fila->servicio_Nombre,
        "servicio_Estado"   => fn_TransformarEstado($sql_Fila->servicio_Estado),
        "servicio_Acciones" => ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarServicio('.$sql_Fila->servicio_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                 ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarServicio'){
    $sql_EditarServicio = "SELECT servicio_Nombre
                           FROM   ".MAN_SERVICIOS."
                           WHERE  servicio_Id = ".$_POST['pvi_ServicioId'];

    $sql_Query = $sql_DB->query($sql_EditarServicio);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "servicio_Nombre"  =>  $sql_Fila->servicio_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarServicios'){
    $sql_CargarServicios = "SELECT    servicio_Id,
                                      servicio_Nombre
                            FROM      ".MAN_SERVICIOS."
                            WHERE     servicio_Estado = 'A'
                            ORDER BY  servicio_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarServicios);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->servicio_Id,
        "nombre"  =>  $sql_Fila->servicio_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
