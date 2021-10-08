<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  origen_Id,
                                origen_Nombre,
                                origen_Estado
                        FROM    ".MAN_ORIGENES;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->origen_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoOrigen(".$sql_Fila->origen_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoOrigen(".$sql_Fila->origen_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "origen_Nombre"   =>  $sql_Fila->origen_Nombre,
        "origen_Estado"   =>  fn_TransformarEstado($sql_Fila->origen_Estado),
        "origen_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarOrigen('.$sql_Fila->origen_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarOrigen'){
    $sql_EditarOrigen = "SELECT origen_Nombre
                         FROM   ".MAN_ORIGENES."
                         WHERE  origen_Id = ".$_POST['pvi_OrigenId'];

    $sql_Query = $sql_DB->query($sql_EditarOrigen);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "origen_Nombre"  =>  $sql_Fila->origen_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarOrigenes'){
    $sql_CargarOrigenes = "SELECT    origen_Id,
                                     origen_Nombre
                           FROM      ".MAN_ORIGENES."
                           WHERE     origen_Estado = 'A'
                           ORDER BY  origen_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarOrigenes);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->origen_Id,
        "nombre"  =>  $sql_Fila->origen_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
