<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarConfiguracion'){
    $sql_CargarConfiguracion = "SELECT  *
                                FROM    ".ADM_CONFIGURACIONES."
                                WHERE   cfg_Id = 1";

    $sql_Query = $sql_DB->query($sql_CargarConfiguracion);
    $sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ);

    $sql_Res = array(
      "cfg_CheckIn"             => $sql_Fila->cfg_CheckIn,
      "cfg_CheckOut"            => $sql_Fila->cfg_CheckOut,
      "cfg_LateCheckOut"        => $sql_Fila->cfg_LateCheckOut,
      "cfg_LCOPrecio"           => $sql_Fila->cfg_LCOPrecio,
      "cfg_LCOPrecioFormat"     => money_format2("%.0n", $sql_Fila->cfg_LCOPrecio),
    );

    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarModulos'){
    $sql_CargarModulos = "SELECT  modulo_Id,
                                  modulo_Nombre,
                                  modulo_Sigla
                          FROM    ".ADM_MODULOS;

    $sql_Query = $sql_DB->query($sql_CargarModulos);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "modulo_Id"     =>  $sql_Fila->modulo_Id,
        "modulo_Nombre" =>  $sql_Fila->modulo_Nombre,
        "modulo_Sigla"  =>  $sql_Fila->modulo_Sigla,
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarPermisos'){
    $sql_CargarPermisos = "SELECT   permiso_Crear,
                                    permiso_Leer,
                                    permiso_Actualizar,
                                    permiso_Eliminar,
                                    permiso_Pagar
                           FROM     ".ADM_PERMISOS."
                           WHERE    modulo_Id = ".$_POST['pvi_ModuloId']." AND usuario_Id = ".$_POST['pvi_UsuarioId'];

    $sql_Query = $sql_DB->query($sql_CargarPermisos);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "permiso_Crear"       =>  $sql_Fila->permiso_Crear,
        "permiso_Leer"        =>  $sql_Fila->permiso_Leer,
        "permiso_Actualizar"  =>  $sql_Fila->permiso_Actualizar,
        "permiso_Eliminar"    =>  $sql_Fila->permiso_Eliminar,
        "permiso_Pagar"       =>  $sql_Fila->permiso_Pagar
      );
    }
    echo json_encode($sql_Res);
  }
?>
