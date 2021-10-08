<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  usuario_Id,
                                CONCAT(funcionario_Nombres, ' ', funcionario_ApellidoPaterno, ' ', funcionario_ApellidoMaterno) AS funcionario_Nombre,
                                usuario_Nombre,
                                usuario_Nivel,
                                usuario_Estado
                        FROM    ".ADM_USUARIOS." A INNER JOIN ".MAE_FUNCIONARIOS." B
                        ON      A.funcionario_Id = B.funcionario_Id";

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->usuario_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoUsuario(".$sql_Fila->usuario_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoUsuario(".$sql_Fila->usuario_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      if($sql_Fila->usuario_Nivel == 'USU'){
        $vls_UsuarioNivel = 'Usuario';
      }else{
        $vls_UsuarioNivel = 'Administrador';
      }

      $sql_Res[] = array(
        "funcionario_Nombre"  =>  $sql_Fila->funcionario_Nombre,
        "usuario_Nombre"      =>  $sql_Fila->usuario_Nombre,
        "usuario_Nivel"       =>  $vls_UsuarioNivel,
        "usuario_Estado"      =>  fn_TransformarEstado($sql_Fila->usuario_Estado),
        "usuario_Acciones"     => ($arr_Permisos[4]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarUsuario('.$sql_Fila->usuario_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                    ($arr_Permisos[4]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarUsuario'){
    $sql_EditarUsuario = "SELECT  funcionario_Id,
                                  usuario_Nivel,
                                  usuario_Nombre
                         FROM     ".ADM_USUARIOS."
                         WHERE    usuario_Id = ".$_POST['pvi_UsuarioId'];

    $sql_Query = $sql_DB->query($sql_EditarUsuario);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "funcionario_Id" =>  $sql_Fila->funcionario_Id,
        "usuario_Nivel"  =>  $sql_Fila->usuario_Nivel,
        "usuario_Nombre" =>  $sql_Fila->usuario_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarUsuarios'){
    $sql_CargarUsuarios = "SELECT   usuario_Id,
                                    usuario_Nombre
                           FROM     ".ADM_USUARIOS."
                           WHERE    usuario_Estado = 'A' AND usuario_Nivel = 'USU'
                           ORDER BY usuario_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarUsuarios);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->usuario_Id,
        "nombre"  =>  $sql_Fila->usuario_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
