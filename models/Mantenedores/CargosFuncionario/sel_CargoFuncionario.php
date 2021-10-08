<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  cargoFuncionario_Id,
                                cargoFuncionario_Nombre,
                                cargoFuncionario_DeSistema,
                                cargoFuncionario_Estado
                        FROM    ".MAN_CARGOSFUNCIONARIO;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->cargoFuncionario_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoCargoFuncionario(".$sql_Fila->cargoFuncionario_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoCargoFuncionario(".$sql_Fila->cargoFuncionario_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      if($sql_Fila->cargoFuncionario_DeSistema == 'S'){
        $vls_BotonEditar = '';
      }else{
        $vls_BotonEditar = '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarCargoFuncionario('.$sql_Fila->cargoFuncionario_Id.');"><i class="fa fa-edit"></i></button> ';
      }

      $sql_Res[] = array(
        "cargoFuncionario_Nombre"   =>  $sql_Fila->cargoFuncionario_Nombre,
        "cargoFuncionario_Estado"   =>  fn_TransformarEstado($sql_Fila->cargoFuncionario_Estado),
        "cargoFuncionario_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEditar : '').
							                          ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarCargoFuncionario'){
    $sql_EditarCargoFuncionario = "SELECT cargoFuncionario_Nombre
                                   FROM   ".MAN_CARGOSFUNCIONARIO."
                                   WHERE  cargoFuncionario_Id = ".$_POST['pvi_CargoFuncionarioId'];

    $sql_Query = $sql_DB->query($sql_EditarCargoFuncionario);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "cargoFuncionario_Nombre"  =>  $sql_Fila->cargoFuncionario_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarCargosFuncionario'){
    $sql_CargarCargosFuncionario = "SELECT    cargoFuncionario_Id,
                                              cargoFuncionario_Nombre
                                    FROM      ".MAN_CARGOSFUNCIONARIO."
                                    WHERE     cargoFuncionario_Estado = 'A'
                                    ORDER BY  cargoFuncionario_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarCargosFuncionario);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->cargoFuncionario_Id,
        "nombre"  =>  $sql_Fila->cargoFuncionario_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
