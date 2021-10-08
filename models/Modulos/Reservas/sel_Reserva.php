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
			$vls_Where = " LIMIT 500";
		}else{
			$json_Filtro = $_POST['json_Filtro'];

			//FILTRO DE NRO OT
			if($json_Filtro['vli_FiltroReservaId'] != ''){
				if($vli_ContadorWhere == 0){
					$vls_Where .= ' WHERE reserva_Id = '.$json_Filtro['vli_FiltroReservaId'];
				}else{
					$vls_Where .= ' AND reserva_Id = '.$json_Filtro['vli_FiltroReservaId'];
				}
				$vli_ContadorWhere++;
			}

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
					$vls_Where .= ' WHERE reserva_FechaDesde BETWEEN "'.fn_TransformarFechaSQL($vld_FechaDesde).'" AND "'.fn_TransformarFechaSQL($vld_FechaHasta).'"';
				}else{
					$vls_Where .= ' AND reserva_FechaDesde BETWEEN "'.fn_TransformarFechaSQL($vld_FechaDesde).'" AND "'.fn_TransformarFechaSQL($vld_FechaHasta).'"';
				}
				$vli_ContadorWhere++;
			}

      //FILTRO DE ESTADO
			if($json_Filtro['vls_FiltroEstado'] != ""){
				if($vli_ContadorWhere == 0){
					$vls_Where .= ' WHERE reserva_EstadoComercial = "'.$json_Filtro['vls_FiltroEstado'] .'"';
				}else{
					$vls_Where .= ' AND reserva_EstadoComercial = "'.$json_Filtro['vls_FiltroEstado'].'"';
				}
				$vli_ContadorWhere++;
			}
		}

    $sql_CargarPanel = "SELECT  reserva_Id,
                                propiedad_Nombre,
                                reserva_FechaDesde,
                                reserva_HoraLlegada,
                                reserva_FechaHasta,
                                reserva_HoraSalida,
                                CONCAT(A.cliente_Nombres, ' ', A.cliente_ApellidoPaterno, ' ', A.cliente_ApellidoMaterno) AS cliente_Nombre,
                                A.cliente_Celular,
                                reserva_EstadoComercial
                        FROM    ".RES_RESERVAS." A           INNER JOIN ".MAE_PROPIEDADES." B
                        ON      A.propiedad_Id = B.propiedad_Id";

    if($vls_Where != ''){
      $sql_CargarPanel .= $vls_Where;
    }

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $vls_BotonEditar    = ($arr_Permisos[0]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Editar" type="button" class="btn btn-info icon-only btn-xs" onclick="fn_EditarReserva('.$sql_Fila->reserva_Id.');"><i class="fa fa-edit"></i></button> ' : '');
      $vls_BotonPagar     = ($arr_Permisos[0]['permiso_Pagar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Pagar" type="button" class="btn btn-warning icon-only btn-xs" onclick="fn_PagarReserva('.$sql_Fila->reserva_Id.');"><i class="fa fa-credit-card"></i></button> ' : '');
      $vls_BotonEliminar  = ($arr_Permisos[0]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? '<button title="Eliminar" type="button" class="btn btn-danger icon-only btn-xs" onclick="fn_EliminarReserva('.$sql_Fila->reserva_Id.');"><i class="fa fa-trash"></i></button>' : '');


      if($sql_Fila->reserva_EstadoComercial == 'PP'){
        $vls_ReservaEstado = 'Pendiente de pago';
      }else if($sql_Fila->reserva_EstadoComercial == 'PA'){
        $vls_ReservaEstado  = 'Pagada';
        $vls_BotonEditar    = '';
        $vls_BotonPagar     = '';
        $vls_BotonEliminar  = '';
      }


      $sql_Res[] = array(
        "reserva_Id"              =>  $sql_Fila->reserva_Id,
        "propiedad_Nombre"        =>  $sql_Fila->propiedad_Nombre,
        "fechaHora_Llegada"       =>  date_format(date_create($sql_Fila->reserva_FechaDesde),"d-m-Y")." ".$sql_Fila->reserva_HoraLlegada,
        "fechaHora_Salida"        =>  date_format(date_create($sql_Fila->reserva_FechaHasta),"d-m-Y")." ".$sql_Fila->reserva_HoraSalida,
        "cliente_Nombre"          =>  $sql_Fila->cliente_Nombre,
        "cliente_Celular"         =>  $sql_Fila->cliente_Celular,
        "reserva_EstadoComercial" =>  $vls_ReservaEstado,
        "reserva_Acciones"        =>  $vls_BotonEditar.$vls_BotonPagar.$vls_BotonEliminar
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarReserva'){
    $sql_CargarReserva = "SELECT  reserva_Id,
                                  reserva_FechaCrea,
                                  reserva_Estado,
                                  pais_Sigla,
                                  cliente_Tipo,
                                  cliente_Id,
                                  cliente_Rut,
                                  cliente_Nombres,
                                  cliente_ApellidoPaterno,
                                  cliente_ApellidoMaterno,
                                  A.pais_Id,
                                  region_Id,
                                  ciudad_Id,
                                  comuna_Id,
                                  cliente_Direccion,
                                  cliente_CorreoElectronico,
                                  cliente_TelefonoFijo,
                                  cliente_Celular,
                                  propiedad_Id,
                                  reserva_FechaDesde,
                                  reserva_HoraLlegada,
                                  reserva_FechaHasta,
                                  reserva_HoraSalida,
                                  reserva_Adultos,
                                  reserva_Ninos,
                                  origen_Id
                          FROM    ".RES_RESERVAS." A   LEFT JOIN  ".MAN_PAISES." B
                          ON      A.nacionalidad_Id = B.pais_Id ";

    if($_POST['pvi_ReservaId']){
      $vls_Where = "WHERE   reserva_Id = " . $_POST['pvi_ReservaId'];
    }else{
      $vls_Where = "WHERE   propiedad_Id = ".$_POST['pvi_PropiedadId']." AND '".$_POST['pvi_Dia']."' BETWEEN reserva_FechaDesde AND reserva_FechaHasta";
    }

    $sql_CargarReserva .= $vls_Where;

    $sql_Query = $sql_DB->query($sql_CargarReserva);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "reserva_Id"                =>  $sql_Fila->reserva_Id,
        "reserva_FechaCrea"         =>  date_format(date_create($sql_Fila->reserva_FechaCrea),"d-m-Y"),
        "reserva_Estado"            =>  ($sql_Fila->reserva_Estado=='A' ? "Reserva" : "Disponible"),
        "pais_Sigla"                =>  $sql_Fila->pais_Sigla,
        "cliente_Tipo"              =>  $sql_Fila->cliente_Tipo,
        "cliente_Id"                =>  $sql_Fila->cliente_Id,
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
        "cliente_Celular"           =>  $sql_Fila->cliente_Celular,
        "propiedad_Id"              =>  $sql_Fila->propiedad_Id,
        "reserva_FechaDesde"        =>  $sql_Fila->reserva_FechaDesde,
        "reserva_HoraLlegada"       =>  $sql_Fila->reserva_HoraLlegada,
        "reserva_FechaHasta"        =>  $sql_Fila->reserva_FechaHasta,
        "reserva_HoraSalida"        =>  $sql_Fila->reserva_HoraSalida,
        "reserva_Adultos"           =>  $sql_Fila->reserva_Adultos,
        "reserva_Ninos"             =>  $sql_Fila->reserva_Ninos,
        "origen_Id"                 =>  $sql_Fila->origen_Id
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarCheckInOut'){
    $sql_CargarCheckInOut = "SELECT   propiedad_Nombre,
                                      reserva_FechaDesde,
                                      reserva_HoraLlegada,
                                      'CHECKIN' AS tipo
                             FROM     ".RES_RESERVAS." A INNER JOIN ".MAE_PROPIEDADES." B
                             ON	      A.propiedad_Id = B.propiedad_Id
                             WHERE    CONCAT(reserva_FechaDesde, ' ', reserva_HoraLlegada) >= NOW()
                             ORDER BY CONCAT(reserva_FechaDesde, ' ', reserva_HoraLlegada)";

    $sql_Query = $sql_DB->query($sql_CargarCheckInOut);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "propiedad_Nombre"  =>  $sql_Fila->propiedad_Nombre,
        "reserva_Fecha"     =>  date_format(date_create($sql_Fila->reserva_FechaDesde),"d-m-Y"),
        "reserva_Hora"      =>  $sql_Fila->reserva_HoraLlegada,
        "tipo"              =>  $sql_Fila->tipo,
      );
    }

    $sql_CargarCheckInOut = "SELECT   propiedad_Nombre,
                                      DATE_ADD(reserva_FechaHasta, INTERVAL 1 DAY) AS reserva_FechaHasta,
                                      reserva_HoraSalida,
                                      'CHECKOUT' AS tipo
                             FROM     ".RES_RESERVAS." A INNER JOIN ".MAE_PROPIEDADES." B
                             ON	      A.propiedad_Id = B.propiedad_Id
                             WHERE    CONCAT(reserva_FechaHasta, ' ', reserva_HoraSalida) >= NOW()
                             ORDER BY CONCAT(reserva_FechaHasta, ' ', reserva_HoraSalida)";

    $sql_Query = $sql_DB->query($sql_CargarCheckInOut);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "propiedad_Nombre"  =>  $sql_Fila->propiedad_Nombre,
        "reserva_Fecha"     =>  date_format(date_create($sql_Fila->reserva_FechaHasta),"d-m-Y"),
        "reserva_Hora"      =>  $sql_Fila->reserva_HoraSalida,
        "tipo"              =>  $sql_Fila->tipo,
      );
    }

    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarPagoPropiedad'){
    $sql_CargarPagoPropiedad = "SELECT  DATEDIFF(reserva_FechaHasta, reserva_FechaDesde) + 1 AS totalDias,
                                        (SELECT tarifa_Valor
                                         FROM   ".MAN_TARIFAS."
                                         WHERE  tarifa_CantPersonas = (reserva_Adultos + reserva_Ninos) AND tarifa_Tipo = A.cliente_Tipo) AS valorDia,
                                        CASE
                                          WHEN reserva_HoraSalida = (SELECT cfg_LateCheckOut FROM ".ADM_CONFIGURACIONES." WHERE cfg_Id = 1) THEN (SELECT cfg_LCOPrecio FROM ".ADM_CONFIGURACIONES." WHERE cfg_Id = 1)
                                          ELSE 0
                                        END AS lateCheckOut,
                                        (SELECT SUM(ingreso_Monto)
                                         FROM   ".CON_INGRESOS." AA
                                         WHERE  AA.reserva_Id = A.reserva_Id AND AA.ingreso_Estado = 'C' AND AA.categoria_Id = (SELECT categoria_Id FROM ".MAN_CATEGORIAS." WHERE categoria_Sigla = 'EST')) AS montoPagado
                                FROM    ".RES_RESERVAS." A
                                WHERE   reserva_Id = ".$_POST['pvi_ReservaId'];

    $sql_Query = $sql_DB->query($sql_CargarPagoPropiedad);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $vls_TotalPagar = ($sql_Fila->totalDias*$sql_Fila->valorDia) + $sql_Fila->lateCheckOut;
      $vls_TotalDias  = ($sql_Fila->totalDias*$sql_Fila->valorDia);

      $sql_Res = array(
        "totalDias"           =>  $sql_Fila->totalDias,
        "valorDia"            =>  $sql_Fila->valorDia,
        "totalPagar"          =>  $vls_TotalPagar,
        "totalDiasFormat"     =>  money_format2("%.0n", $vls_TotalDias),
        "valorDiaFormat"      =>  money_format2("%.0n", $sql_Fila->valorDia),
        "lateCheckOut"        =>  $sql_Fila->lateCheckOut,
        "lateCheckOutFormat"  =>  money_format2("%.0n", $sql_Fila->lateCheckOut),
        "montoPagado"         =>  $sql_Fila->montoPagado
      );
    }

    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'BuscarDisponibilidad'){
    $json_Disponibilidad = $_POST['json_Disponibilidad'];

    $sql_BuscarDisponibilidad = "SELECT   A.propiedad_Id,
                                          B.propiedad_Imagen,
                                          A.propiedad_Nombre,
                                          A.propiedad_Descripcion
                                 FROM     ".MAE_PROPIEDADES." A           LEFT JOIN ".MAE_IMAGENESPROPIEDAD." B
                                 ON       A.propiedad_Id = B.propiedad_Id
                                 WHERE    A.propiedad_Capacidad >= ".($json_Disponibilidad['pvi_Adultos']+$json_Disponibilidad['pvi_Ninos'])." AND
                                          A.propiedad_Mantencion = 'D' AND A.propiedad_Estado = 'A' AND
                                          A.propiedad_Id NOT IN (SELECT propiedad_Id
                                                                 FROM   ".RES_RESERVAS."
                                                                 WHERE  ((reserva_FechaDesde <= '".$json_Disponibilidad['pvd_FechaDesde']."' AND reserva_FechaHasta >= '".$json_Disponibilidad['pvd_FechaHasta']."')
                                                                         OR reserva_FechaHasta BETWEEN '".$json_Disponibilidad['pvd_FechaDesde']."' AND '".$json_Disponibilidad['pvd_FechaHasta']."'
                                                                         OR reserva_FechaDesde BETWEEN '".$json_Disponibilidad['pvd_FechaDesde']."' AND '".$json_Disponibilidad['pvd_FechaHasta']."'))
                                ORDER BY  A.propiedad_Nombre";

    $sql_Query = $sql_DB->query($sql_BuscarDisponibilidad);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $vls_Funcion = "fn_Reservar(".$sql_Fila->propiedad_Id.",'".$sql_Fila->propiedad_Nombre."')";
      $sql_Res[] = array(
        "propiedad_Id"          => $sql_Fila->propiedad_Id,
        "propiedad_Imagen"      => $sql_Fila->propiedad_Imagen,
        "propiedad_Nombre"      => $sql_Fila->propiedad_Nombre,
        "propiedad_Descripcion" => $sql_Fila->propiedad_Descripcion,
        "servicio_Nombre"       => $sql_Fila->servicio_Nombre,
        "propiedad_Boton"       => '<button title="Reservar" type="button" class="btn btn-info" onclick="'.$vls_Funcion.'"><i class="fa fa-ok"></i> Reservar</button>'
      );
    }

    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarServicios'){
    $sql_CargarServicios = "SELECT   servicio_Nombre
                            FROM     ".MAE_SERVICIOSPROPIEDAD." A  LEFT JOIN ".MAN_SERVICIOS." B
                            ON       A.servicio_Id = B.servicio_Id
                            WHERE    A.propiedad_Id = ".$_POST['pvi_PropiedadId']."
                            ORDER BY servicio_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarServicios);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "servicio_Nombre" => $sql_Fila->servicio_Nombre
      );
    }

    echo json_encode($sql_Res);
  }
?>
