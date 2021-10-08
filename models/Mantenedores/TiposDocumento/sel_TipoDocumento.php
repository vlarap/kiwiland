<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  tipoDoc_Id,
                                tipoDoc_Nombre,
                                tipoDoc_Estado,
                                tipoDoc_Eliminar
                        FROM    ".MAN_TIPOSDOCUMENTO;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->tipoDoc_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoTipoDocumento(".$sql_Fila->tipoDoc_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoTipoDocumento(".$sql_Fila->tipoDoc_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $vls_BotonEditar = '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarTipoDocumento('.$sql_Fila->tipoDoc_Id.');"><i class="fa fa-edit"></i></button> ';

      if($sql_Fila->tipoDoc_Eliminar == 'N'){
        $vls_BotonEstado = '';
        $vls_BotonEditar = '';
      }

      $sql_Res[] = array(
        "tipoDoc_Nombre"   =>  $sql_Fila->tipoDoc_Nombre,
        "tipoDoc_Estado"   =>  fn_TransformarEstado($sql_Fila->tipoDoc_Estado),
        "tipoDoc_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEditar : '').
							                 ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarTipoDocumento'){
    $sql_EditarTipoDocumento = "SELECT tipoDoc_Nombre
                                FROM   ".MAN_TIPOSDOCUMENTO."
                                WHERE  tipoDoc_Id = ".$_POST['pvi_TipoDocumentoId'];

    $sql_Query = $sql_DB->query($sql_EditarTipoDocumento);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "tipoDoc_Nombre" =>  $sql_Fila->tipoDoc_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarTiposDocumento'){
    $sql_CargarTipoDocumento = "SELECT    tipoDoc_Id,
                                          tipoDoc_Nombre
                                FROM      ".MAN_TIPOSDOCUMENTO."
                                WHERE     tipoDoc_Estado = 'A'
                                ORDER BY  tipoDoc_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarTipoDocumento);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->tipoDoc_Id,
        "nombre"  =>  $sql_Fila->tipoDoc_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
