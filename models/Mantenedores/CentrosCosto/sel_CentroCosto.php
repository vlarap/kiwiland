<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  cCosto_Id,
                                cCosto_Nombre,
                                cCosto_Estado
                        FROM    ".MAN_CENTROSCOSTO;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->cCosto_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoCentroCosto(".$sql_Fila->cCosto_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoCentroCosto(".$sql_Fila->cCosto_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "cCosto_Nombre"   => $sql_Fila->cCosto_Nombre,
        "cCosto_Estado"   => fn_TransformarEstado($sql_Fila->cCosto_Estado),
        "cCosto_Acciones" => ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarCentroCosto('.$sql_Fila->cCosto_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							               ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarCentroCosto'){
    $sql_EditarCentroCosto = "SELECT  cCosto_Nombre
                              FROM    ".MAN_CENTROSCOSTO."
                              WHERE   cCosto_Id = ".$_POST['pvi_CCostoId'];

    $sql_Query = $sql_DB->query($sql_EditarCentroCosto);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "cCosto_Nombre" => $sql_Fila->cCosto_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarCentrosCosto'){
    $sql_CargarCCosto = "SELECT    cCosto_Id,
                                   cCosto_Nombre
                         FROM      ".MAN_CENTROSCOSTO."
                         WHERE     cCosto_Estado = 'A'
                         ORDER BY  cCosto_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarCCosto);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->cCosto_Id,
        "nombre"  =>  $sql_Fila->cCosto_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
