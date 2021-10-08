<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  comuna_Id,
                                comuna_Nombre,
                                ciudad_Nombre,
                                comuna_Estado
                        FROM    ".MAN_COMUNAS." A INNER JOIN ".MAN_CIUDADES." B
                        ON      A.ciudad_Id = B.ciudad_Id";

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->comuna_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoComuna(".$sql_Fila->comuna_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoComuna(".$sql_Fila->comuna_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "comuna_Nombre"   =>  $sql_Fila->comuna_Nombre,
        "ciudad_Nombre"   =>  $sql_Fila->ciudad_Nombre,
        "comuna_Estado"   =>  fn_TransformarEstado($sql_Fila->comuna_Estado),
        "comuna_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarComuna('.$sql_Fila->comuna_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarComuna'){
    $sql_EditarComuna = "SELECT comuna_Nombre,
                                ciudad_Id
                         FROM   ".MAN_COMUNAS."
                         WHERE  comuna_Id = ".$_POST['pvi_ComunaId'];

    $sql_Query = $sql_DB->query($sql_EditarComuna);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "comuna_Nombre" =>  $sql_Fila->comuna_Nombre,
        "ciudad_Id"     =>  $sql_Fila->ciudad_Id
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarComunas'){
    $sql_CargarComunas = "SELECT    comuna_Id,
                                    comuna_Nombre
                          FROM      ".MAN_COMUNAS."
                          WHERE     comuna_Estado = 'A'
                          ORDER BY  comuna_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarComunas);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->comuna_Id,
        "nombre"  =>  $sql_Fila->comuna_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarComunasXCiudad'){
    $sql_CargarComunas = "SELECT    comuna_Id,
                                    comuna_Nombre
                          FROM      ".MAN_COMUNAS."
                          WHERE     comuna_Estado = 'A' AND ciudad_Id = ".$_POST['id']."
                          ORDER BY  comuna_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarComunas);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->comuna_Id,
        "nombre"  =>  $sql_Fila->comuna_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
