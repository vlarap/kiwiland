<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $vls_Where 					= '';
		$vli_ContadorWhere	= 0;

		if($_POST['json_Filtro'] == 0){
			$vls_Where = " WHERE egreso_Fecha BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND LAST_DAY(NOW())";
		}else{
			$json_Filtro = $_POST['json_Filtro'];

			//FILTRO DE CLIENTE
			if($json_Filtro['vli_FiltroClienteId'] != ""){
				if($vli_ContadorWhere == 0){
					$vls_Where .= ' WHERE A.cliente_Id = '.$json_Filtro['vli_FiltroClienteId'];
				}else{
					$vls_Where .= ' AND A.cliente_Id = '.$json_Filtro['vli_FiltroClienteId'];
				}
				$vli_ContadorWhere++;
			}

      //FILTRO DE CCOSTO
			if($json_Filtro['vli_FiltroCategoriaId'] != ""){
				if($vli_ContadorWhere == 0){
					$vls_Where .= ' WHERE categoria_Id = '.$json_Filtro['vli_FiltroCategoriaId'];
				}else{
					$vls_Where .= ' AND categoria_Id = '.$json_Filtro['vli_FiltroCategoriaId'];
				}
				$vli_ContadorWhere++;
			}

			//FILTRO RANGO DE FECHAS
			if($json_Filtro['vld_FiltroFechas'] != ''){
				$vlARR_Fechas		= explode(' - ',$json_Filtro['vld_FiltroFechas']);
        $vld_FechaDesde = $vlARR_Fechas[0];
        $vld_FechaHasta = $vlARR_Fechas[1];

				if($vli_ContadorWhere == 0){
					$vls_Where .= ' WHERE egreso_Fecha BETWEEN "'.fn_TransformarFechaSQL($vld_FechaDesde).'" AND "'.fn_TransformarFechaSQL($vld_FechaHasta).'"';
				}else{
					$vls_Where .= ' AND egreso_Fecha BETWEEN "'.fn_TransformarFechaSQL($vld_FechaDesde).'" AND "'.fn_TransformarFechaSQL($vld_FechaHasta).'"';
				}
				$vli_ContadorWhere++;
			}
		}

    if($vli_ContadorWhere == 0){
      $sql_SubQueryTotal = "(SELECT SUM(AA.egresoCC_Monto) FROM ".CON_EGRESOSCC." AA INNER JOIN ".CON_EGRESOS." BB ON AA.egreso_Id = BB.egreso_Id WHERE BB.egreso_Estado = 'I' AND BB.egreso_Fecha BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND LAST_DAY(NOW()))";
    }else{
      $sql_SubQueryTotal = "(SELECT SUM(AA.egresoCC_Monto) FROM ".CON_EGRESOSCC." AA INNER JOIN ".CON_EGRESOS." BB ON AA.egreso_Id = BB.egreso_Id WHERE BB.egreso_Estado = 'I' AND BB.egreso_Fecha BETWEEN '".fn_TransformarFechaSQL($vld_FechaDesde)."' AND '".fn_TransformarFechaSQL($vld_FechaHasta)."')";
    }

    $sql_CargarPanel = "SELECT	A.egreso_Id,
                                A.egreso_Fecha,
		                            A.egreso_NroDoc,
                                B.tipoDoc_Nombre,
		                            CONCAT(C.cliente_Nombres, ' ', C.cliente_ApellidoPaterno, ' ', C.cliente_ApellidoMaterno) AS cliente_Nombre,
                                (SELECT SUM(CC.egresoCC_Monto) FROM ".CON_EGRESOSCC." CC WHERE CC.egreso_Id = A.egreso_Id) AS egreso_Monto,
                                ".$sql_SubQueryTotal." AS egreso_Total,
                                D.categoria_Nombre,
                                A.egreso_Estado
                        FROM	  ".CON_EGRESOS." A               INNER JOIN ".MAN_TIPOSDOCUMENTO." B
                        ON		  A.tipoDoc_Id = B.tipoDoc_Id     INNER JOIN ".MAE_CLIENTES." C
                        ON		  A.cliente_Id = C.cliente_Id     INNER JOIN ".MAN_CATEGORIAS." D
                        ON      A.categoria_Id = D.categoria_Id";

    if($vls_Where != ''){
      $sql_CargarPanel .= $vls_Where;
    }

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $vls_BotonEditar    = ($arr_Permisos[1]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" type="button" class="btn btn-info icon-only btn-xs" onclick="fn_EditarEgreso('.$sql_Fila->egreso_Id.');"><i class="fa fa-edit"></i></button> ' : '');
      $vls_BotonAnular    = ($arr_Permisos[1]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Anular" type="button" class="btn btn-danger icon-only btn-xs" onclick="fn_AnularEgreso('.$sql_Fila->egreso_Id.');"><i class="fa fa-ban"></i></button>' : '');

      if($sql_Fila->egreso_Estado == 'A'){
        $vls_Estado = 'Anulado';
        $vls_BotonEditar = '';
        $vls_BotonAnular = '';
      }else{
        $vls_Estado = 'Ingresado';
      }

      $sql_Res[] = array(
        "egreso_Fecha"     =>  date_format(date_create($sql_Fila->egreso_Fecha),"d-m-Y"),
        "egreso_NroDoc"    =>  $sql_Fila->egreso_NroDoc,
        "tipoDoc_Nombre"   =>  $sql_Fila->tipoDoc_Nombre,
        "cliente_Nombre"   =>  $sql_Fila->cliente_Nombre,
        "categoria_Nombre" =>  $sql_Fila->categoria_Nombre,
        "egreso_Monto"     =>  $sql_Fila->egreso_Monto,
        "egreso_Total"     =>  $sql_Fila->egreso_Total,
        "egreso_Estado"    =>  $vls_Estado,
        "egreso_Acciones"  =>  $vls_BotonEditar . $vls_BotonAnular
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarEgreso'){
    $sql_EditarEgreso = "SELECT	A.egreso_NroDoc,
		                            A.tipoDoc_Id,
                                A.categoria_Id,
                                (SELECT SUM(AA.egresoCC_Monto) FROM ".CON_EGRESOSCC." AA WHERE AA.egreso_Id = A.egreso_Id) AS egreso_Total,
                                A.egreso_Fecha,
                                A.cliente_Id,
                                A.egreso_Comentario,
                                B.cCosto_Id,
                                B.egresoCC_Monto
                         FROM	  ".CON_EGRESOS." A INNER JOIN ".CON_EGRESOSCC." B
                         ON     A.egreso_Id = B.egreso_Id
                         WHERE  A.egreso_Id = " . $_POST['pvi_EgresoId'];

    $sql_Query = $sql_DB->query($sql_EditarEgreso);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "egreso_NroDoc"     =>  $sql_Fila->egreso_NroDoc,
        "tipoDoc_Id"        =>  $sql_Fila->tipoDoc_Id,
        "categoria_Id"      =>  $sql_Fila->categoria_Id,
        "egreso_Total"      =>  $sql_Fila->egreso_Total,
        "egreso_Fecha"      =>  $sql_Fila->egreso_Fecha,
        "cliente_Id"        =>  $sql_Fila->cliente_Id,
        "egreso_Comentario" =>  $sql_Fila->egreso_Comentario,
        "cCosto_Id"         =>  $sql_Fila->cCosto_Id,
        "egresoCC_Monto"    =>  $sql_Fila->egresoCC_Monto
      );
    }
    echo json_encode($sql_Res);
  }
?>
