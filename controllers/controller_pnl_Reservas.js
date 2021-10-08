 //CARGA TABLA DE RESERVAS
function fn_CargarReservas(json_Filtro){
  $("#tbl_Reservas").dataTable().fnDestroy();
  $('#tbl_Reservas tbody').empty();

  if(json_Filtro === undefined){
		json_Filtro = 0;
	}

  $.post('models/Modulos/Reservas/sel_Reserva.php', {fn_Funcion:'CargarPanel', json_Filtro:json_Filtro}, function(res){
		var json_Reservas = $.parseJSON(res);

    $('#tbl_Reservas').dataTable({
			"sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
			"iDisplayLength": 25,
			"oTableTools": {
				"aButtons": [
					"xls"
				],
				"sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
			},
			"language": {
				"search": "",
				"emptyTable": "No hay registros.",
				"zeroRecords": "No existe ninguna coincidencias en los registros.",
				"sLengthMenu": "_MENU_",
				"oPaginate": {
					"sPrevious": "Ant",
					"sNext": "Sig"
				}
			},
			"data": json_Reservas,
			"aoColumns": [
        {"data":  "reserva_Id"},
        {"data":  "propiedad_Nombre"},
        {"data":  "fechaHora_Llegada"},
        {"data":  "fechaHora_Salida"},
        {"data":  "cliente_Nombre"},
        {"data":  "cliente_Celular"},
        {"data":  "reserva_EstadoComercial"},
        {"data":  "reserva_Acciones"}
			],
      columnDefs  : [
        {"targets": 0, "width": "8%"},
        {"targets": 4, "width": "20%"},
        {"targets": 2, "orderable": false},
        {"targets": 3, "orderable": false},
        {"targets": 7, "width": "8%", "orderable": false}
      ],
			"aaSorting": [0, 'desc']
		});
  });
}

function fn_CargarPagosReserva(pvi_ReservaId){
  $("#tbl_PagosEfectuados").dataTable().fnDestroy();
	$("#tbl_PagosEfectuados tbody").empty();

  var json_Ingresos;
  $.ajax({
    url     : 'models/Modulos/Contabilidad/sel_Ingreso.php',
    type    : 'post',
    data    : {fn_Funcion:'CargarPagosReserva', pvi_ReservaId:pvi_ReservaId},
    async   : false,
    success : function(res){
      json_Ingresos = $.parseJSON(res);
    }
  });

  $('#tbl_PagosEfectuados').dataTable({
    "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
    "iDisplayLength": 25,
    "oTableTools": {
      "aButtons": [
        "xls"
      ],
      "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
    },
    "language": {
      "search": "",
      "emptyTable": "No hay registros.",
      "zeroRecords": "No existe ninguna coincidencias en los registros.",
      "sLengthMenu": "_MENU_",
      "oPaginate": {
        "sPrevious": "Ant",
        "sNext": "Sig"
      }
    },
    "data": json_Ingresos,
    "aoColumns": [
      {"data":  "ingreso_Fecha"},
      {"data":  "categoria_Nombre"},
      {"data":  "medioPago_Nombre"},
      {"data":  "ingreso_Monto"},
      {"data":  "ingreso_Comentario"},
      {"data":  "ingreso_Estado"},
      {"data":  "ingreso_Acciones"}
    ],
    columnDefs  : [
      {"targets": 3, "orderable": false},
      {"targets": 4, "orderable": false}
    ],
    "aaSorting": [0, 'desc']
  });
}

function fn_CargarOtros(pvi_ReservaId){
  $('#tbl_OtrosPagos').find('td').each(function(){
    $("#tbl_OtrosPagos").find("tr:gt(0)").remove();
  });

  var json_Pagos;
  $.ajax({
    url     : 'models/Modulos/Contabilidad/sel_Ingreso.php',
    type    : 'post',
    data    : {fn_Funcion:'CargarOtrosPagos', pvi_ReservaId:pvi_ReservaId},
    async   : false,
    success : function(res){
      json_Pagos = $.parseJSON(res);
    }
  });

  if(json_Pagos){
    for(i=0;i<json_Pagos.length;i++){
      $("#tbl_OtrosPagos tr:last").after('<tr>'+
        '<td>'+json_Pagos[i]['categoria_Nombre']+'</td>'+
        '<td>'+json_Pagos[i]['ingreso_Monto']+'</td>'+
       '</tr>');
    }
  }  
}

function fn_CargarPagoPropiedad(pvi_ReservaId){
  $('#tbl_DetallesPago').find('td').each(function(){
    $("#tbl_DetallesPago").find("tr:gt(0)").remove();
  });

  var json_Pago;
  $.ajax({
    url     : 'models/Modulos/Reservas/sel_Reserva.php',
    type    : 'post',
    data    : {fn_Funcion:'CargarPagoPropiedad', pvi_ReservaId:pvi_ReservaId},
    async   : false,
    success : function(res){
      json_Pago = $.parseJSON(res);
    }
  });

  $("#cmb_MedioPagoId").val('');
  $("#dtp_FechaPago").val('');
  $("#txt_Comentario").val('');
  $("#cmb_CategoriaId").val('');
  $("#txt_TotalPagar").val('');
  //$("#txt_TotalPagar").val(json_Pago['totalPagar'] - json_Pago['montoPagado']);
  window.vgi_TotalPagar = json_Pago['totalPagar'] - json_Pago['montoPagado'];

  $("#tbl_DetallesPago tr:last").after('<tr>'+
    '<td>Estadía</td>'+
    '<td>'+json_Pago['totalDias']+' día(s)</td>'+
    '<td>'+json_Pago['valorDiaFormat']+'</td>'+
    '<td>'+json_Pago['totalDiasFormat']+'</td>'+
   '</tr>');

   if(json_Pago['lateCheckOut'] > 0){
     $("#tbl_DetallesPago tr:last").after('<tr>'+
       '<td>Late CheckOut</td>'+
       '<td>1 Un.</td>'+
       '<td>'+json_Pago['lateCheckOutFormat']+'</td>'+
       '<td>'+json_Pago['lateCheckOutFormat']+'</td>'+
      '</tr>');
   }
}

function fn_PagarReserva(pvi_ReservaId){
  window.vgi_ReservaId = pvi_ReservaId;

  fn_CargarPagosReserva(pvi_ReservaId);
  fn_CargarPagoPropiedad(pvi_ReservaId);
  fn_CargarOtros(pvi_ReservaId);

  $("#mdl_PagarReserva").modal();
}

function fn_AnularPago(pvi_IngresoId, pvi_ReservaId){
  swal({
    title:      "¿Está seguro de anular el ingreso?",
    icon:       "warning",
    buttons:    ["Cancelar", "Anular"],
    dangerMode: true
  })
  .then((Anular) => {
    if (Anular) {
      swal("Motivo de anulación:", {
        icon:       "warning",
        content:    "input",
        button:     "Anular",
        dangerMode: true
      })
      .then((value) => {
        $.post('models/Modulos/Contabilidad/upd_Ingreso.php', {fn_Funcion:'AnularIngreso', pvi_IngresoId:pvi_IngresoId, pvs_MotivoAnulación:`${value}`}, function(res){
          if(res == 1){
            fn_Alerta("success", "Anulado!", "Pago anulado correctamente.", "Aceptar");

            fn_CargarPagosReserva(pvi_ReservaId);
            fn_CargarPagoPropiedad(pvi_ReservaId);
          }else{
            fn_Alerta("error", "Error!", "No se pudo anular el pago por el siguiente error: " + sql_Respuesta, "Aceptar");
          }
        });
      });
    }

  });

}

function fn_ContabilizarPago(pvi_IngresoId, pvi_ReservaId){
  $.post('models/Modulos/Contabilidad/upd_Ingreso.php', {fn_Funcion:'ContabilizarIngreso', pvi_IngresoId:pvi_IngresoId}, function(res){
    if(res == 1){
      fn_Alerta("success", "Contabilizado!", "Pago contabilizado correctamente.", "Aceptar");

      fn_CargarPagosReserva(pvi_ReservaId);
      fn_CargarPagoPropiedad(pvi_ReservaId);
    }else{
      fn_Alerta("error", "Error!", "No se pudo contabilizado el pago por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
}

//
// function fn_ImprimirSimulacion(pvi_SimulacionId){
//   window.open('models/Modulos/Simulaciones/imp_Simulacion.php?id=' + fn_EncodeId(pvi_SimulacionId));
// }
//
// function fn_AnularSimulacion(pvi_SimulacionId){
//   swal({
//     title:      "¿Está seguro de anular la simulación?",
//     icon:       "warning",
//     buttons:    ["Cancelar", "Anular"],
//     dangerMode: true
//   })
//   .then((Anular) => {
//     if (Anular) {
//       $.post('models/Modulos/Simulaciones/upd_Simulacion.php', {fn_Funcion:'AnularSimulacion', pvi_SimulacionId:pvi_SimulacionId}, function(res){
//         if(res == 1){
//           fn_Alerta("success", "Anulada!", "Simulación anulada correctamente.", "Aceptar");
//
//           fn_CargarSimulaciones();
//         }else{
//           fn_Alerta("error", "Error!", "No se pudo anular la simulación por el siguiente error: " + res, "Aceptar");
//         }
//       });
//     }
//   });
// }

$(document).ready(function (){
  /* CARGAS INICIALES */
  fn_CargarReservas();
  fn_CargaComboBox('models/Mantenedores/MediosPago/sel_MedioPago.php', 'cmb_MedioPagoId', 'CargarMediosPago', ''); //CARGA MEDIOS DE PAGO
  fn_CargaComboBox('models/Mantenedores/Categorias/sel_Categoria.php', 'cmb_CategoriaId', 'CargarCategoriasXSigla', '', 'I'); //CARGA CATEGORIAS

  $('input[name="RangoFecha"]').daterangepicker({
    format: 'dd-mm-yyyy',
    showDropdowns: true,
    autoUpdateInput: false,
    locale: {
      "daysOfWeek": [
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa",
            "Do"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      applyLabel: "Aceptar",
      cancelLabel: "Limpiar"
    }
  });

  $('input[name="RangoFecha"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
  });

  $('input[name="RangoFecha"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });

  fn_CargaComboBox('models/Maestros/Clientes/sel_Cliente.php', 'cmb_ClienteId', 'CargarClientes', ''); //CARGA CLIENTES
  $("#cmb_ClienteId").select2({
    theme: 'bootstrap4'
  });

  $("#cmb_CategoriaId").change(function (){
    if($("#cmb_CategoriaId").val() == 'EST'){
      $("#txt_TotalPagar").val(window.vgi_TotalPagar);
    }else{
      $("#txt_TotalPagar").val('');
    }
  });

  $("#btn_Nueva").click(function (){
    window.vgs_ReservaDesde = "reserva";
    fn_LimpiarFormulario();
    $("#mdl_Reserva").modal();
  });

  $("#frm_PagarReserva").submit(function (e){
    e.preventDefault();
    var json_Ingreso = {
      vli_MedioPagoId       : $("#cmb_MedioPagoId").val(),
      vls_CategoriaId       : $("#cmb_CategoriaId").val(),
      vld_IngresoFecha      : $("#dtp_FechaPago").val(),
      vli_IngresoMonto      : $("#txt_TotalPagar").val(),
      vls_IngresoComentario : $("#txt_Comentario").val()
    };

    var sql_Respuesta = '';
    $.ajax({
      url     : 'models/Modulos/Contabilidad/ins_Ingreso.php',
      type    : 'post',
      data    : {json_Ingreso:json_Ingreso, fn_Funcion:'IngresarPagoReserva', pvi_ReservaId:window.vgi_ReservaId},
      async   : false,
      success : function(res){
        sql_Respuesta = res;
      }
    });

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardado!", "Pago guardado correctamente.", "Aceptar");

      $("#mdl_PagarReserva").modal("toggle");
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el pago por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });

  $("#btn_CheckOut").click(function (){
    swal({
      title:      "¿Está seguro de realizar el Check-Out?",
      icon:       "warning",
      buttons:    ["Cancelar", "Sí"],
      dangerMode: true
    })
    .then((CheckOut) => {
      if (CheckOut) {
        $.post('models/Modulos/Reservas/upd_Reserva.php', {fn_Funcion:'CheckOut', pvi_ReservaId:window.vgi_ReservaId}, function(res){
          if(res == 1){
            fn_Alerta("success", "Check-out!", "Check-out realizado correctamente.", "Aceptar");

            fn_CargarReservas();
          }else{
            fn_Alerta("error", "Error!", "No se pudo realizar el Check-out por el siguiente error: " + res, "Aceptar");
          }
        });
      }
    });
  });

  $("#btn_Buscar").click(function(){
    var vli_FiltroReservaId = $("#txt_ReservaId").val();
    var vli_FiltroClienteId = $("#cmb_ClienteId").val();
    var vld_FiltroFechas    = $("#dtp_FechasReserva").val();
    var vls_FiltroEstado    = $("#cmb_EstadoComercialId").val();

    var json_Filtro = {
      vli_FiltroReservaId : vli_FiltroReservaId,
      vli_FiltroClienteId : vli_FiltroClienteId,
      vld_FiltroFechas    : vld_FiltroFechas,
      vls_FiltroEstado		: vls_FiltroEstado
    };

    fn_CargarReservas(json_Filtro);
  });
});
