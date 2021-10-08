<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  cliente_Id,
                                cliente_Tipo,
                                cliente_Rut,
                                CONCAT(cliente_Nombres, ' ', cliente_ApellidoPaterno, ' ', cliente_ApellidoMaterno) AS cliente_Nombre,
                                cliente_Direccion,
                                cliente_Celular,
                                cliente_CorreoElectronico,
                                IFNULL((SELECT AVG(valoracion_Puntaje) FROM ".MAE_CLIENTEVALORACIONES." AA WHERE AA.cliente_Id = A.cliente_Id), 5) AS cliente_Valoracion,
                                cliente_Estado
                        FROM    ".MAE_CLIENTES." A";
    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->cliente_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoCliente(".$sql_Fila->cliente_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoCliente(".$sql_Fila->cliente_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      if($sql_Fila->cliente_Tipo == 'P'){
        $vls_ClienteTipo = 'Persona';
      }else{
        $vls_ClienteTipo = 'Empresa';
      }

      $sql_Res[] = array(
        "cliente_Rut"               =>  fn_FormatearRut($sql_Fila->cliente_Rut),
        "cliente_Tipo"              =>  $vls_ClienteTipo,
        "cliente_Nombre"            =>  $sql_Fila->cliente_Nombre,
        "cliente_Direccion"         =>  $sql_Fila->cliente_Direccion,
        "cliente_Celular"           =>  $sql_Fila->cliente_Celular,
        "cliente_CorreoElectronico" =>  $sql_Fila->cliente_CorreoElectronico,
        "cliente_Valoracion"        =>  round($sql_Fila->cliente_Valoracion, 1),
        "cliente_Estado"            =>  fn_TransformarEstado($sql_Fila->cliente_Estado),
        "cliente_Acciones"          =>  ($arr_Permisos[2]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarCliente('.$sql_Fila->cliente_Id.');"><i class="fa fa-edit"></i></button> ' : '').
                                        ($arr_Permisos[2]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Valoraciones" class="btn btn-warning icon-only btn-xs" onclick="fn_CargarValoraciones('.$sql_Fila->cliente_Id.');"><i class="fa fa-check-square-o"></i></button> ' : '').
							                          ($arr_Permisos[2]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarCliente'){
    $sql_EditarCliente = "SELECT  cliente_Tipo,
                                  nacionalidad_Id,
                                  cliente_Rut,
                                  cliente_Nombres,
                                  cliente_ApellidoPaterno,
                                  cliente_ApellidoMaterno,
                                  pais_Id,
                                  region_Id,
                                  ciudad_Id,
                                  comuna_Id,
                                  cliente_Direccion,
                                  cliente_CorreoElectronico,
                                  cliente_TelefonoFijo,
                                  cliente_Celular
                          FROM    ".MAE_CLIENTES."
                          WHERE   cliente_Id = ".$_POST['pvi_ClienteId'];

    $sql_Query = $sql_DB->query($sql_EditarCliente);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "cliente_Tipo"              =>  $sql_Fila->cliente_Tipo,
        "nacionalidad_Id"           =>  $sql_Fila->nacionalidad_Id,
        "cliente_Rut"               =>  $sql_Fila->cliente_Rut,
        "cliente_Nombres"           =>  $sql_Fila->cliente_Nombres,
        "cliente_ApellidoPaterno"   =>  $sql_Fila->cliente_ApellidoPaterno,
        "cliente_ApellidoMaterno"   =>  $sql_Fila->cliente_ApellidoMaterno,
        "pais_Id"                   =>  $sql_Fila->pais_Id,
        "region_Id"                 =>  $sql_Fila->region_Id,
        "ciudad_Id"                 =>  $sql_Fila->ciudad_Id,
        "comuna_Id"                 =>  $sql_Fila->comuna_Id,
        "cliente_Direccion"         =>  $sql_Fila->cliente_Direccion,
        "cliente_CorreoElectronico" =>  $sql_Fila->cliente_CorreoElectronico,
        "cliente_TelefonoFijo"      =>  $sql_Fila->cliente_TelefonoFijo,
        "cliente_Celular"           =>  $sql_Fila->cliente_Celular
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarClientes'){
    $sql_CargarClientes = "SELECT   cliente_Id,
                                    CONCAT(cliente_Nombres, ' ', cliente_ApellidoPaterno, ' ', cliente_ApellidoMaterno) AS cliente_Nombre
                           FROM     ".MAE_CLIENTES."
                           WHERE    cliente_Estado = 'A'
                           ORDER BY cliente_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarClientes);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->cliente_Id,
        "nombre"  =>  $sql_Fila->cliente_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'ComprobarCliente'){
    $sql_ComprobarCliente = "SELECT   COUNT(*) AS Contador
                             FROM     ".MAE_CLIENTES."
                             WHERE    cliente_Rut = '".$_POST['pvs_ClienteRut']."'";

    $sql_Query = $sql_DB->query($sql_ComprobarCliente);
    $sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ);
    echo $sql_Fila->Contador;
  }else if($fn_Funcion == 'BuscarValoracion'){
    $sql_BuscarValoracion = "SELECT   valoracion_Id,
                                      valoracion_Puntaje,
                                      valoracion_Observacion
                             FROM     ".MAE_CLIENTEVALORACIONES."
                             WHERE    cliente_Id = ".$_POST['pvi_ClienteId']." AND reserva_Id = ".$_POST['pvi_ReservaId'];

    $sql_Query = $sql_DB->query($sql_BuscarValoracion);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "valoracion_Id"          =>  $sql_Fila->valoracion_Id,
        "valoracion_Puntaje"     =>  $sql_Fila->valoracion_Puntaje,
        "valoracion_Observacion" =>  $sql_Fila->valoracion_Observacion
      );
    }

    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarValoraciones'){
    $sql_CargarPanel = "SELECT  reserva_Id,
                                valoracion_Puntaje,
                                valoracion_Observacion
                        FROM    ".MAE_CLIENTEVALORACIONES."
                        WHERE   cliente_Id = ".$_POST['pvi_ClienteId'];
    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "reserva_Id"              =>  $sql_Fila->reserva_Id,
        "valoracion_Puntaje"      =>  $sql_Fila->valoracion_Puntaje,
        "valoracion_Observacion"  =>  $sql_Fila->valoracion_Observacion
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarClienteXRut'){
    $sql_CargarCliente = "SELECT  cliente_Id,
                                  cliente_Tipo,
                                  cliente_Nombres,
                                  cliente_ApellidoPaterno,
                                  cliente_ApellidoMaterno,
                                  pais_Id,
                                  region_Id,
                                  ciudad_Id,
                                  comuna_Id,
                                  cliente_Direccion,
                                  cliente_CorreoElectronico,
                                  cliente_TelefonoFijo,
                                  cliente_Celular
                          FROM    ".MAE_CLIENTES."
                          WHERE   cliente_Rut = ".$_POST['pvs_ClienteRut'];

    $sql_Query = $sql_DB->query($sql_CargarCliente);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "cliente_Id"                =>  $sql_Fila->cliente_Id,
        "cliente_Tipo"              =>  $sql_Fila->cliente_Tipo,
        "cliente_Nombres"           =>  $sql_Fila->cliente_Nombres,
        "cliente_ApellidoPaterno"   =>  $sql_Fila->cliente_ApellidoPaterno,
        "cliente_ApellidoMaterno"   =>  $sql_Fila->cliente_ApellidoMaterno,
        "pais_Id"                   =>  $sql_Fila->pais_Id,
        "region_Id"                 =>  $sql_Fila->region_Id,
        "ciudad_Id"                 =>  $sql_Fila->ciudad_Id,
        "comuna_Id"                 =>  $sql_Fila->comuna_Id,
        "cliente_Direccion"         =>  $sql_Fila->cliente_Direccion,
        "cliente_CorreoElectronico" =>  $sql_Fila->cliente_CorreoElectronico,
        "cliente_TelefonoFijo"      =>  $sql_Fila->cliente_TelefonoFijo,
        "cliente_Celular"           =>  $sql_Fila->cliente_Celular
      );
    }
    echo json_encode($sql_Res);
  }
?>
