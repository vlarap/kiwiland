<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $vls_Where 					= '';
		$vli_ContadorWhere	= 0;

		if($_POST['json_Filtro'] == 0){
			$vls_Where = " WHERE ingreso_Fecha BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND LAST_DAY(NOW())";
		}else{
			$json_Filtro = $_POST['json_Filtro'];

			//FILTRO DE CLIENTE
			if($json_Filtro['vli_FiltroClienteId'] != ""){
				if($vli_ContadorWhere == 0){
					$vls_Where .= ' WHERE cliente_Id = '.$json_Filtro['vli_FiltroClienteId'];
				}else{
					$vls_Where .= ' AND cliente_Id = '.$json_Filtro['vli_FiltroClienteId'];
				}
				$vli_ContadorWhere++;
			}

			//FILTRO RANGO DE FECHAS
			if($json_Filtro['vld_FiltroFechas'] != ''){
				$vlARR_Fechas		= explode(' - ',$json_Filtro['vld_FiltroFechas']);
        $vld_FechaDesde = $vlARR_Fechas[0];
        $vld_FechaHasta = $vlARR_Fechas[1];

				if($vli_ContadorWhere == 0){
					$vls_Where .= ' WHERE ingreso_Fecha BETWEEN "'.fn_TransformarFechaSQL($vld_FechaDesde).'" AND "'.fn_TransformarFechaSQL($vld_FechaHasta).'"';
				}else{
					$vls_Where .= ' AND ingreso_Fecha BETWEEN "'.fn_TransformarFechaSQL($vld_FechaDesde).'" AND "'.fn_TransformarFechaSQL($vld_FechaHasta).'"';
				}
				$vli_ContadorWhere++;
			}
		}

    if($vli_ContadorWhere == 0){
      $sql_SubQueryTotal = "(SELECT SUM(AA.ingreso_Monto) FROM ".CON_INGRESOS." AA WHERE AA.ingreso_Fecha BETWEEN DATE_FORMAT(NOW(),'%Y-%m-01') AND LAST_DAY(NOW()) AND ingreso_Estado = 'C')";
    }else{
      $sql_SubQueryTotal = "(SELECT SUM(AA.ingreso_Monto) FROM ".CON_INGRESOS." AA WHERE AA.ingreso_Fecha BETWEEN '".fn_TransformarFechaSQL($vld_FechaDesde)."' AND '".fn_TransformarFechaSQL($vld_FechaHasta)."' AND ingreso_Estado = 'C')";
    }

    $sql_CargarPanel = "SELECT	A.ingreso_Id,
		                            A.reserva_Id,
                                A.ingreso_Fecha,
		                            C.propiedad_Nombre,
                                D.medioPago_Nombre,
                                A.ingreso_Monto,
                                ".$sql_SubQueryTotal." AS ingreso_Total,
                                E.tipoDoc_Nombre,
                                F.categoria_Nombre
                        FROM	  ".CON_INGRESOS." A              INNER JOIN ".RES_RESERVAS." B
                        ON		  A.reserva_Id = B.reserva_Id     INNER JOIN ".MAE_PROPIEDADES." C
                        ON		  B.propiedad_Id = C.propiedad_Id INNER JOIN ".MAN_MEDIOSPAGO." D
                        ON		  A.medioPago_Id = D.medioPago_Id INNER JOIN ".MAN_TIPOSDOCUMENTO." E
                        ON      A.tipoDoc_Id = E.tipoDoc_Id     INNER JOIN ".MAN_CATEGORIAS." F
                        ON      A.categoria_Id = F.categoria_Id";

    if($vls_Where != ''){
      $sql_CargarPanel .= $vls_Where . " AND ingreso_Estado = 'C'";
    }else{
      $sql_CargarPanel .= "WHERE ingreso_Estado = 'C'";
    }

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "ingreso_Id"        =>  $sql_Fila->ingreso_Id,
        "reserva_Id"        =>  $sql_Fila->reserva_Id,
        "ingreso_Fecha"     =>  date_format(date_create($sql_Fila->ingreso_Fecha),"d-m-Y"),
        "propiedad_Nombre"  =>  $sql_Fila->propiedad_Nombre,
        "medioPago_Nombre"  =>  $sql_Fila->medioPago_Nombre,
        "ingreso_Monto"     =>  $sql_Fila->ingreso_Monto,
        "ingreso_Total"     =>  $sql_Fila->ingreso_Total,
        "tipoDoc_Nombre"    =>  $sql_Fila->tipoDoc_Nombre,
        "categoria_Nombre"  =>  $sql_Fila->categoria_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarPagosReserva'){
    $sql_CargarPagosReserva = "SELECT ingreso_Id,
                                      reserva_Id,
                                      ingreso_Fecha,
                                      medioPago_Nombre,
                                      categoria_Nombre,
                                      ingreso_Comentario,
                                      ingreso_Monto,
                                      ingreso_Estado
                               FROM   ".CON_INGRESOS." A              INNER JOIN ".MAN_MEDIOSPAGO." B
                               ON     A.medioPago_Id = B.medioPago_Id INNER JOIN ".MAN_CATEGORIAS." C
                               ON     A.categoria_Id = C.categoria_Id
                               WHERE  A.reserva_Id = " . $_POST['pvi_ReservaId'];

    $sql_Query = $sql_DB->query($sql_CargarPagosReserva);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $vls_BotonContabilizar = '<button title="Anular" type="button" class="btn btn-info icon-only btn-xs" onclick="fn_ContabilizarPago('.$sql_Fila->ingreso_Id.', '.$sql_Fila->reserva_Id.');"><i class="fa fa-check"></i></button> ';
      $vls_BotonAnular       = '<button title="Anular" type="button" class="btn btn-danger icon-only btn-xs" onclick="fn_AnularPago('.$sql_Fila->ingreso_Id.', '.$sql_Fila->reserva_Id.');"><i class="fa fa-ban"></i></button>';

      if($sql_Fila->ingreso_Estado == 'I'){
        $vls_IngresoEstado = 'Ingresado';
      }else if($sql_Fila->ingreso_Estado == 'A'){
        $vls_IngresoEstado     = 'Anulado';
        $vls_BotonAnular       = '';
        $vls_BotonContabilizar = '';
      }else if($sql_Fila->ingreso_Estado == 'C'){
        $vls_IngresoEstado     = 'Contabilizado';
        $vls_BotonAnular       = '';
        $vls_BotonContabilizar = '';
      }


      $sql_Res[] = array(
        "ingreso_Fecha"       =>  date_format(date_create($sql_Fila->ingreso_Fecha),"d-m-Y"),
        "medioPago_Nombre"    =>  $sql_Fila->medioPago_Nombre,
        "categoria_Nombre"    =>  $sql_Fila->categoria_Nombre,
        "ingreso_Comentario"  =>  $sql_Fila->ingreso_Comentario,
        "ingreso_Monto"       =>  $sql_Fila->ingreso_Monto,
        "ingreso_Estado"      =>  $vls_IngresoEstado,
        "ingreso_Acciones"    =>  $vls_BotonContabilizar . $vls_BotonAnular
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarOtrosPagos'){
    $sql_CargarOtrosPagos = "SELECT categoria_Nombre,
                                    ingreso_Monto
                             FROM   ".CON_INGRESOS." A INNER JOIN ".MAN_CATEGORIAS." B
                             ON     A.categoria_Id = B.categoria_Id
                             WHERE  A.reserva_Id = " . $_POST['pvi_ReservaId'] . " AND B.categoria_Sigla <> 'EST'";

    $sql_Query = $sql_DB->query($sql_CargarOtrosPagos);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "categoria_Nombre"  =>  $sql_Fila->categoria_Nombre,
        "ingreso_Monto"     =>  money_format2("%.0n", $sql_Fila->ingreso_Monto),
      );
    }
    echo json_encode($sql_Res);
  }
?>
