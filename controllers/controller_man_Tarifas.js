//CARGA TABLA DE TARIFAS
function fn_CargarTablaTarifas(){
  $("#tbl_Tarifas").dataTable().fnDestroy();
  $('#tbl_Tarifas tbody').empty();

  $.post('models/Mantenedores/Tarifas/sel_Tarifa.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Tarifas = $.parseJSON(res);

    $('#tbl_Tarifas').dataTable({
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
			"data": json_Tarifas,
			"aoColumns": [
        {"data":  "tarifa_Tipo"},
        {"data":  "tarifa_CantPersonas"},
        {"data":  "tarifa_Valor"},
        {"data":  "tarifa_Estado"},
        {"data":  "tarifa_Acciones"}
			],
      columnDefs  : [
        {"targets": 2, "orderable": false},
        {"targets": 3, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del tarifa
function fn_CambiarEstadoTarifa(pvi_TarifaId, pvs_Estado){
  $.post('models/Mantenedores/Tarifas/upd_Tarifa.php', {fn_Funcion:'CambiarEstado', pvi_TarifaId:pvi_TarifaId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivada!" : "Activada!"), "Tarifa "+(pvs_Estado == 'D' ? "desactivada" : "activada")+" correctamente.", "Aceptar");

      fn_CargarTablaTarifas();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" la tarifa por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información tarifa
function fn_EditarTarifa(pvi_TarifaId){
  $.post('models/Mantenedores/Tarifas/sel_Tarifa.php', {fn_Funcion:'EditarTarifa', pvi_TarifaId:pvi_TarifaId}, function(res){
    var json_Tarifa = $.parseJSON(res);

    $("#lbl_TituloModalTarifa").text("Tarifa - Editar");

    window.vgi_TarifaId = pvi_TarifaId;
    $("#cmb_TarifaTipo").val(json_Tarifa['tarifa_Tipo']);
    $("#txt_TarifaCantPersonas").val(json_Tarifa['tarifa_CantPersonas']);
    $("#txt_TarifaValor").val(json_Tarifa['tarifa_Valor']);

    $("#mdl_Tarifa").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaTarifas();

  //ABRIR FORMULARIO TARIFA
  $("#btn_AbrirModalTarifa").click(function (){
    window.vgi_TarifaId = 0;

    $("#lbl_TituloModalTarifa").text("Tarifa - Nuevo");

    $("#cmb_TarifaTipo").val('');
    $("#txt_TarifaCantPersonas").val('');
    $("#txt_TarifaValor").val('');
  });

  //GUARDAR FORMULARIO TARIFA
  $("#frm_Tarifa").submit(function (e){
    e.preventDefault();

    var json_Tarifa = {
      vls_TarifaTipo          : $("#cmb_TarifaTipo").val(),
      vli_TarifaCantPersonas  : $("#txt_TarifaCantPersonas").val(),
      vli_TarifaValor         : $("#txt_TarifaValor").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_TarifaId == 0){ //CREANDO TARIFA
      $.ajax({
        url     : 'models/Mantenedores/Tarifas/ins_Tarifa.php',
        type    : 'post',
        data    : {json_Tarifa:json_Tarifa, fn_Funcion:'InsertarTarifa'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO TARIFA
      $.ajax({
        url     : 'models/Mantenedores/Tarifas/upd_Tarifa.php',
        type    : 'post',
        data    : {json_Tarifa:json_Tarifa, fn_Funcion:'EditarTarifa', pvi_TarifaId:window.vgi_TarifaId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Tarifa guardada correctamente.", "Aceptar");

      $("#mdl_Tarifa").modal("toggle");
      fn_CargarTablaTarifas();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar la tarifa por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
