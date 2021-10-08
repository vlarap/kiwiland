<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  region_Id,
                                region_Nombre,
                                pais_Nombre,
                                region_Estado
                        FROM    ".MAN_REGIONES." A INNER JOIN ".MAN_PAISES." B
                        ON      A.pais_Id = B.pais_Id";

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->region_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoRegion(".$sql_Fila->region_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoRegion(".$sql_Fila->region_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "region_Nombre"   =>  $sql_Fila->region_Nombre,
        "pais_Nombre"     =>  $sql_Fila->pais_Nombre,
        "region_Estado"   =>  fn_TransformarEstado($sql_Fila->region_Estado),
        "region_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarRegion('.$sql_Fila->region_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarRegion'){
    $sql_EditarRegion = "SELECT region_Nombre
                         FROM   ".MAN_REGIONES."
                         WHERE  region_Id = ".$_POST['pvi_RegionId'];

    $sql_Query = $sql_DB->query($sql_EditarRegion);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "region_Nombre" =>  $sql_Fila->region_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarRegiones'){
    $sql_CargarRegiones = "SELECT    region_Id,
                                     region_Nombre
                           FROM      ".MAN_REGIONES."
                           WHERE     region_Estado = 'A' AND pais_Id = ".$_POST['id']."
                           ORDER BY  region_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarRegiones);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->region_Id,
        "nombre"  =>  $sql_Fila->region_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarRegiones2'){
    $sql_CargarRegiones = "SELECT    region_Id,
                                     region_Nombre
                           FROM      ".MAN_REGIONES."
                           WHERE     region_Estado = 'A'
                           ORDER BY  region_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarRegiones);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->region_Id,
        "nombre"  =>  $sql_Fila->region_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
