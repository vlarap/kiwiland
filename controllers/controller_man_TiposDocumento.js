//CARGA TABLA DE TIPOS DE DOCUMENTO
function fn_CargarTablaTiposDocumento(){
  $("#tbl_TiposDocumento").dataTable().fnDestroy();
  $('#tbl_TiposDocumento tbody').empty();

  $.post('models/Mantenedores/TiposDocumento/sel_TipoDocumento.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_TiposDocumento = $.parseJSON(res);
    $('#tbl_TiposDocumento').dataTable({
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
			"data": json_TiposDocumento,
			"aoColumns": [
        {"data":  "tipoDoc_Nombre"},
        {"data":  "tipoDoc_Estado"},
        {"data":  "tipoDoc_Acciones"}
			],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del tipo de documento
function fn_CambiarEstadoTipoDocumento(pvi_TipoDocumentoId, pvs_Estado){
  $.post('models/Mantenedores/TiposDocumento/upd_TipoDocumento.php', {fn_Funcion:'CambiarEstado', pvi_TipoDocumentoId:pvi_TipoDocumentoId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Tipo de documento "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaTiposDocumento();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el tipo de documento por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información tipo de documento
function fn_EditarTipoDocumento(pvi_TipoDocumentoId){
  $.post('models/Mantenedores/TiposDocumento/sel_TipoDocumento.php', {fn_Funcion:'EditarTipoDocumento', pvi_TipoDocumentoId:pvi_TipoDocumentoId}, function(res){
    var json_TipoDocumento = $.parseJSON(res);

    $("#lbl_TituloModalTipoDocumento").text("Tipo de documento - Editar");

    window.vgi_TipoDocumentoId = pvi_TipoDocumentoId;
    $("#txt_TipoDocumentoNombre").val(json_TipoDocumento['tipoDoc_Nombre']);

    $("#mdl_TipoDocumento").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaTiposDocumento();

  //ABRIR FORMULARIO TIPO DE DOCUMENTO
  $("#btn_AbrirModalTipoDocumento").click(function (){
    window.vgi_TipoDocumentoId = 0;

    $("#lbl_TituloModalTipoDocumento").text("Tipo de documento - Nuevo");

    $("#txt_TipoDocumentoNombre").val('');
  });

  //GUARDAR FORMULARIO TIPO DE DOCUMENTO
  $("#frm_TipoDocumento").submit(function (e){
    e.preventDefault();

    var json_TipoDocumento = {
      vls_TipoDocumentoNombre  : $("#txt_TipoDocumentoNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_TipoDocumentoId == 0){ //CREANDO TIPO DE DOCUMENTO
      $.ajax({
        url     : 'models/Mantenedores/TiposDocumento/ins_TipoDocumento.php',
        type    : 'post',
        data    : {json_TipoDocumento:json_TipoDocumento, fn_Funcion:'InsertarTipoDocumento'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO TIPO DE DOCUMENTO
      $.ajax({
        url     : 'models/Mantenedores/TiposDocumento/upd_TipoDocumento.php',
        type    : 'post',
        data    : {json_TipoDocumento:json_TipoDocumento, fn_Funcion:'EditarTipoDocumento', pvi_TipoDocumentoId:window.vgi_TipoDocumentoId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Tipo de documento guardado correctamente.", "Aceptar");

      $("#mdl_TipoDocumento").modal("toggle");
      fn_CargarTablaTiposDocumento();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el tipo de documento por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
