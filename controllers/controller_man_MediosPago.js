//CARGA TABLA DE MEDIOS DE PAGO
function fn_CargarTablaMediosPago(){
  $("#tbl_MediosPago").dataTable().fnDestroy();
  $('#tbl_MediosPago tbody').empty();

  $.post('models/Mantenedores/MediosPago/sel_MedioPago.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_MediosPago = $.parseJSON(res);

    $('#tbl_MediosPago').dataTable({
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
			"data": json_MediosPago,
			"aoColumns": [
        {"data":  "medioPago_Nombre"},
        {"data":  "medioPago_Estado"},
        {"data":  "medioPago_Acciones"}
			],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado de la medio de pago
function fn_CambiarEstadoMedioPago(pvi_MedioPagoId, pvs_Estado){
  $.post('models/Mantenedores/MediosPago/upd_MedioPago.php', {fn_Funcion:'CambiarEstado', pvi_MedioPagoId:pvi_MedioPagoId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Medio de pago "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaMediosPago();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el medio de pago por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información medio de pago
function fn_EditarMedioPago(pvi_MedioPagoId){
  $.post('models/Mantenedores/MediosPago/sel_MedioPago.php', {fn_Funcion:'EditarMedioPago', pvi_MedioPagoId:pvi_MedioPagoId}, function(res){
    var json_MedioPago = $.parseJSON(res);

    $("#lbl_TituloModalMedioPago").text("Medio de pago - Editar");

    window.vgi_MedioPagoId = pvi_MedioPagoId;
    $("#txt_MedioPagoNombre").val(json_MedioPago['medioPago_Nombre']);

    $("#mdl_MedioPago").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaMediosPago();

  //ABRIR FORMULARIO MedioPago
  $("#btn_AbrirModalMedioPago").click(function (){
    window.vgi_MedioPagoId = 0;

    $("#lbl_TituloModalMedioPago").text("Medio de pago - Nuevo");

    $("#txt_MedioPagoNombre").val('');
  });

  //GUARDAR FORMULARIO MedioPago
  $("#frm_MedioPago").submit(function (e){
    e.preventDefault();

    var json_MedioPago = {
      vls_MedioPagoNombre  : $("#txt_MedioPagoNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_MedioPagoId == 0){ //CREANDO MEDIO PAGO
      $.ajax({
        url     : 'models/Mantenedores/MediosPago/ins_MedioPago.php',
        type    : 'post',
        data    : {json_MedioPago:json_MedioPago, fn_Funcion:'InsertarMedioPago'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO MEDIO PAGO
      $.ajax({
        url     : 'models/Mantenedores/MediosPago/upd_MedioPago.php',
        type    : 'post',
        data    : {json_MedioPago:json_MedioPago, fn_Funcion:'EditarMedioPago', pvi_MedioPagoId:window.vgi_MedioPagoId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Medio de pago guardado correctamente.", "Aceptar");

      $("#mdl_MedioPago").modal("toggle");
      fn_CargarTablaMediosPago();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el medio de pago por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
