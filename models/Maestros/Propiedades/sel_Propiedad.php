<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  propiedad_Id,
                                propiedad_Nombre,
                                propiedad_Capacidad,
                                propiedad_Mantencion,
                                propiedad_Estado
                        FROM    ".MAE_PROPIEDADES;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->propiedad_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoPropiedad(".$sql_Fila->propiedad_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoPropiedad(".$sql_Fila->propiedad_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      $sql_Res[] = array(
        "propiedad_Nombre"      =>  $sql_Fila->propiedad_Nombre,
        "propiedad_Capacidad"   =>  $sql_Fila->propiedad_Capacidad,
        "propiedad_Mantencion"  =>  fn_TransformarEstado($sql_Fila->propiedad_Mantencion),
        "propiedad_Estado"      =>  fn_TransformarEstado($sql_Fila->propiedad_Estado),
        "propiedad_Acciones"    =>  ($arr_Permisos[2]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarPropiedad('.$sql_Fila->propiedad_Id.');"><i class="fa fa-edit"></i></button> ' : '').
                                    ($arr_Permisos[2]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarPropiedad'){
    $sql_EditarPropiedad = "SELECT    A.propiedad_Id,
                                      propiedad_Nombre,
                                      propiedad_Capacidad,
                                      propiedad_Descripcion,
                                      propiedad_Mantencion,
                                      propiedad_Imagen
                            FROM      ".MAE_PROPIEDADES." A             LEFT JOIN  ".MAE_IMAGENESPROPIEDAD." B
                            ON        A.propiedad_Id = B.propiedad_Id
                            WHERE     A.propiedad_Id = ".$_POST['pvi_PropiedadId']."
                            ORDER BY  propiedad_Imagen";

    $sql_Query = $sql_DB->query($sql_EditarPropiedad);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "propiedad_Nombre"      =>  $sql_Fila->propiedad_Nombre,
        "propiedad_Capacidad"   =>  $sql_Fila->propiedad_Capacidad,
        "propiedad_Descripcion" =>  $sql_Fila->propiedad_Descripcion,
        "propiedad_Mantencion"  =>  $sql_Fila->propiedad_Mantencion,
        "propiedad_Imagen"      =>  $sql_Fila->propiedad_Imagen
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarPropiedades'){
    $sql_CargarPropiedades = "SELECT propiedad_Id,
                                     propiedad_Nombre
                              FROM   ".MAE_PROPIEDADES."
                              WHERE  propiedad_Estado = 'A'";

    $sql_Query = $sql_DB->query($sql_CargarPropiedades);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->propiedad_Id,
        "nombre"  =>  $sql_Fila->propiedad_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'ListadoPropiedades'){
    $sql_ListadoPropiedades = "SELECT propiedad_Id,
                                      propiedad_Nombre,
                                      propiedad_Mantencion
                               FROM   ".MAE_PROPIEDADES."
                               WHERE  propiedad_Estado = 'A'";

    $sql_Query = $sql_DB->query($sql_ListadoPropiedades);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "propiedad_Id"          =>  $sql_Fila->propiedad_Id,
        "propiedad_Nombre"      =>  $sql_Fila->propiedad_Nombre,
        "propiedad_Mantencion"  =>  $sql_Fila->propiedad_Mantencion
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'ListadoReservas'){
    $sql_ListadoReservas = "SELECT    A.propiedad_Id,
                                      reserva_FechaDesde,
                                      reserva_FechaHasta
                            FROM      ".RES_RESERVAS." A
                            WHERE     reserva_Estado = 'A' AND
                                      (reserva_FechaDesde BETWEEN (DATE(NOW() - INTERVAL DAYOFMONTH(NOW()) - 1 DAY)) AND DATE(LAST_DAY(NOW())) OR
                                       reserva_FechaHasta BETWEEN (DATE(NOW() - INTERVAL DAYOFMONTH(NOW()) - 1 DAY)) AND DATE(LAST_DAY(NOW()))
                                      )
                            ORDER BY  A.propiedad_Id, reserva_FechaDesde";
    $sql_Query = $sql_DB->query($sql_ListadoReservas);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      for ($vld_Fecha = strtotime($sql_Fila->reserva_FechaDesde); $vld_Fecha <= strtotime($sql_Fila->reserva_FechaHasta); $vld_Fecha = strtotime("+1 day", $vld_Fecha)){
        if(date("m", $vld_Fecha) == date("m")){
          $sql_Res[] = array(
            "propiedad_Id"          =>  $sql_Fila->propiedad_Id,
            "reserva_Dia"           =>  date("j", $vld_Fecha)
          );
        }
      }
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'ListadoServicios'){
    $sql_ListadoServicios = "SELECT    A.servicio_Id,
                                       servicio_Nombre
                             FROM      ".MAE_SERVICIOSPROPIEDAD." A  INNER JOIN ".MAN_SERVICIOS." B
                             ON        A.servicio_Id = B.servicio_Id
                             WHERE     A.propiedad_Id = ".$_POST['pvi_PropiedadId']."
                             ORDER BY  servicio_Nombre";
    $sql_Query = $sql_DB->query($sql_ListadoServicios);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "servicio_Nombre"   => $sql_Fila->servicio_Nombre,
        "servicio_Acciones" => '<button title="Eliminar" class="btn btn-danger icon-only btn-xs" onclick="fn_EliminarServicioPropiedad('.$sql_Fila->servicio_Id.');"><i class="fa fa-trash"></i></button>'
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarPropiedadCapacidad'){
    $sql_CargarPropiedadCapacidad = "SELECT    propiedad_Capacidad
                                     FROM      ".MAE_PROPIEDADES."
                                     WHERE     propiedad_Id = ".$_POST['pvi_PropiedadId'];
    $sql_Query = $sql_DB->query($sql_CargarPropiedadCapacidad);
    $sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ);
    echo $sql_Fila->propiedad_Capacidad;
  }
?>
