<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  ciudad_Id,
                                ciudad_Nombre,
                                region_Nombre,
                                ciudad_Estado
                        FROM    ".MAN_CIUDADES." A INNER JOIN ".MAN_REGIONES." B
                        ON      A.region_Id = B.region_Id";

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->ciudad_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoCiudad(".$sql_Fila->ciudad_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoCiudad(".$sql_Fila->ciudad_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "ciudad_Nombre"   =>  $sql_Fila->ciudad_Nombre,
        "region_Nombre"   =>  $sql_Fila->region_Nombre,
        "ciudad_Estado"   =>  fn_TransformarEstado($sql_Fila->ciudad_Estado),
        "ciudad_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarCiudad('.$sql_Fila->ciudad_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarCiudad'){
    $sql_EditarCiudad = "SELECT ciudad_Nombre,
                                region_Id
                         FROM   ".MAN_CIUDADES."
                         WHERE  ciudad_Id = ".$_POST['pvi_CiudadId'];

    $sql_Query = $sql_DB->query($sql_EditarCiudad);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "ciudad_Nombre" =>  $sql_Fila->ciudad_Nombre,
        "region_Id"     =>  $sql_Fila->region_Id
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarCiudades'){
    $sql_CargarCiudades = "SELECT    ciudad_Id,
                                     ciudad_Nombre
                           FROM      ".MAN_CIUDADES."
                           WHERE     ciudad_Estado = 'A'
                           ORDER BY  ciudad_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarCiudades);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->ciudad_Id,
        "nombre"  =>  $sql_Fila->ciudad_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarCiudadesXRegion'){
    $sql_CargarCiudades = "SELECT    ciudad_Id,
                                     ciudad_Nombre
                           FROM      ".MAN_CIUDADES."
                           WHERE     ciudad_Estado = 'A' AND region_Id = ".$_POST['id']."
                           ORDER BY  ciudad_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarCiudades);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->ciudad_Id,
        "nombre"  =>  $sql_Fila->ciudad_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
