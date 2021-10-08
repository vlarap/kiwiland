<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  pais_Id,
                                pais_Nombre,
                                pais_Estado
                        FROM    ".MAN_PAISES;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->pais_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoPais(".$sql_Fila->pais_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoPais(".$sql_Fila->pais_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "pais_Nombre"   =>  $sql_Fila->pais_Nombre,
        "pais_Estado"   =>  fn_TransformarEstado($sql_Fila->pais_Estado),
        "pais_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarPais('.$sql_Fila->pais_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							              ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarPais'){
    $sql_EditarPais = "SELECT pais_Nombre
                       FROM   ".MAN_PAISES."
                       WHERE  pais_Id = ".$_POST['pvi_PaisId'];

    $sql_Query = $sql_DB->query($sql_EditarPais);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "pais_Nombre" =>  $sql_Fila->pais_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarPaises'){
    $sql_CargarPaises = "SELECT    pais_Id,
                                   pais_Nombre
                         FROM      ".MAN_PAISES."
                         WHERE     pais_Estado = 'A'
                         ORDER BY  pais_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarPaises);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->pais_Id,
        "nombre"  =>  $sql_Fila->pais_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
