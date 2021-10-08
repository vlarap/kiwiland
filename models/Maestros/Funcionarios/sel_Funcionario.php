<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  funcionario_Id,
                                funcionario_Rut,
                                CONCAT(funcionario_Nombres, ' ', funcionario_ApellidoPaterno, ' ', funcionario_ApellidoMaterno) AS funcionario_Nombre,
                                cargoFuncionario_Nombre,
                                funcionario_Direccion,
                                funcionario_Telefono,
                                funcionario_Estado
                        FROM    ".MAE_FUNCIONARIOS." A INNER JOIN ".MAN_CARGOSFUNCIONARIO." B
                        ON      A.cargoFuncionario_Id = B.cargoFuncionario_Id";

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->funcionario_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoFuncionario(".$sql_Fila->funcionario_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoFuncionario(".$sql_Fila->funcionario_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "funcionario_Rut"         =>  fn_FormatearRut($sql_Fila->funcionario_Rut),
        "funcionario_Nombre"      =>  $sql_Fila->funcionario_Nombre,
        "cargoFuncionario_Nombre" =>  $sql_Fila->cargoFuncionario_Nombre,
        "funcionario_Direccion"   =>  $sql_Fila->funcionario_Direccion,
        "funcionario_Telefono"    =>  $sql_Fila->funcionario_Telefono,
        "funcionario_Estado"      =>  fn_TransformarEstado($sql_Fila->funcionario_Estado),
        "funcionario_Acciones"    =>  ($arr_Permisos[2]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarFuncionario('.$sql_Fila->funcionario_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                        ($arr_Permisos[2]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarFuncionario'){
    $sql_EditarFuncionario = "SELECT  funcionario_Rut,
                                      cargoFuncionario_Id,
                                      funcionario_Nombres,
                                      funcionario_ApellidoPaterno,
                                      funcionario_ApellidoMaterno,
                                      funcionario_Direccion,
                                      funcionario_Telefono
                              FROM    ".MAE_FUNCIONARIOS."
                              WHERE   funcionario_Id = ".$_POST['pvi_FuncionarioId'];

    $sql_Query = $sql_DB->query($sql_EditarFuncionario);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "funcionario_Rut"             =>  $sql_Fila->funcionario_Rut,
        "cargoFuncionario_Id"         =>  $sql_Fila->cargoFuncionario_Id,
        "funcionario_Nombres"         =>  $sql_Fila->funcionario_Nombres,
        "funcionario_ApellidoPaterno" =>  $sql_Fila->funcionario_ApellidoPaterno,
        "funcionario_ApellidoMaterno" =>  $sql_Fila->funcionario_ApellidoMaterno,
        "funcionario_Direccion"       =>  $sql_Fila->funcionario_Direccion,
        "funcionario_Telefono"        =>  $sql_Fila->funcionario_Telefono
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarFuncionarios'){
    $sql_CargarFuncionarios = "SELECT   funcionario_Id,
                                        CONCAT(funcionario_Nombres, ' ', funcionario_ApellidoPaterno, ' ', funcionario_ApellidoMaterno) AS funcionario_Nombre
                               FROM     ".MAE_FUNCIONARIOS."
                               WHERE    funcionario_Estado = 'A'
                               ORDER BY funcionario_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarFuncionarios);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->funcionario_Id,
        "nombre"  =>  $sql_Fila->funcionario_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarFuncionariosPorTipo'){
    $sql_CargarFuncionarios = "SELECT   funcionario_Id,
                                        CONCAT(funcionario_Nombres, ' ', funcionario_ApellidoPaterno, ' ', funcionario_ApellidoMaterno) AS funcionario_Nombre
                               FROM     ".MAE_FUNCIONARIOS." A INNER JOIN ".MAN_CARGOSFUNCIONARIO." B
                               ON       A.cargoFuncionario_Id = B.cargoFuncionario_Id
                               WHERE    A.funcionario_Estado = 'A' AND B.cargoFuncionario_Codigo = '".$_POST['id']."'
                               ORDER BY funcionario_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarFuncionarios);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->funcionario_Id,
        "nombre"  =>  $sql_Fila->funcionario_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
