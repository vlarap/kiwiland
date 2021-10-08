<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  tarifa_Id,
                                tarifa_Tipo,
                                tarifa_CantPersonas,
                                tarifa_Valor,
                                tarifa_Estado
                        FROM    ".MAN_TARIFAS;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      if($sql_Fila->tarifa_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoTarifa(".$sql_Fila->tarifa_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoTarifa(".$sql_Fila->tarifa_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      if($sql_Fila->tarifa_Tipo == 'P'){
        $vls_TarifaTipo = 'Persona';
      }else{
        $vls_TarifaTipo = 'Empresa';
      }

      $sql_Res[] = array(
        "tarifa_Tipo"         => $vls_TarifaTipo,
        "tarifa_CantPersonas" => $sql_Fila->tarifa_CantPersonas,
        "tarifa_Valor"        => money_format2("%.0n", $sql_Fila->tarifa_Valor),
        "tarifa_Estado"       => fn_TransformarEstado($sql_Fila->tarifa_Estado),
        "tarifa_Acciones"     => ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarTarifa('.$sql_Fila->tarifa_Id.');"><i class="fa fa-edit"></i></button> ' : '').
							                   ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarTarifa'){
    $sql_EditarTarifa = "SELECT tarifa_Tipo,
                                tarifa_CantPersonas,
                                tarifa_Valor
                         FROM   ".MAN_TARIFAS."
                         WHERE  tarifa_Id = ".$_POST['pvi_TarifaId'];

    $sql_Query = $sql_DB->query($sql_EditarTarifa);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "tarifa_Tipo"         => $sql_Fila->tarifa_Tipo,
        "tarifa_CantPersonas" => $sql_Fila->tarifa_CantPersonas,
        "tarifa_Valor"        => $sql_Fila->tarifa_Valor
      );
    }
    echo json_encode($sql_Res);
  }
?>
