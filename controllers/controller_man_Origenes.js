//CARGA TABLA DE ORIGENES
function fn_CargarTablaOrigenes(){
  $("#tbl_Origenes").dataTable().fnDestroy();
  $('#tbl_Origenes tbody').empty();

  $.post('models/Mantenedores/Origenes/sel_Origen.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Origenes = $.parseJSON(res);

    $('#tbl_Origenes').dataTable({
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
			"data": json_Origenes,
			"aoColumns": [
        {"data":  "origen_Nombre"},
        {"data":  "origen_Estado"},
        {"data":  "origen_Acciones"}
			],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del origen
function fn_CambiarEstadoOrigen(pvi_OrigenId, pvs_Estado){
  $.post('models/Mantenedores/Origenes/upd_Origen.php', {fn_Funcion:'CambiarEstado', pvi_OrigenId:pvi_OrigenId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Origen "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaOrigenes();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el origen por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información origen
function fn_EditarOrigen(pvi_OrigenId){
  $.post('models/Mantenedores/Origenes/sel_Origen.php', {fn_Funcion:'EditarOrigen', pvi_OrigenId:pvi_OrigenId}, function(res){
    var json_Origen = $.parseJSON(res);

    $("#lbl_TituloModalOrigen").text("Origen - Editar");

    window.vgi_OrigenId = pvi_OrigenId;
    $("#txt_OrigenNombre").val(json_Origen['origen_Nombre']);

    $("#mdl_Origen").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaOrigenes();

  //ABRIR FORMULARIO ORIGEN
  $("#btn_AbrirModalOrigen").click(function (){
    window.vgi_OrigenId = 0;

    $("#lbl_TituloModalOrigen").text("Origen - Nuevo");

    $("#txt_OrigenNombre").val('');
  });

  //GUARDAR FORMULARIO ORIGEN
  $("#frm_Origen").submit(function (e){
    e.preventDefault();

    var json_Origen = {
      vls_OrigenNombre  : $("#txt_OrigenNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_OrigenId == 0){ //CREANDO ORIGEN
      $.ajax({
        url     : 'models/Mantenedores/Origenes/ins_Origen.php',
        type    : 'post',
        data    : {json_Origen:json_Origen, fn_Funcion:'InsertarOrigen'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO ORIGEN
      $.ajax({
        url     : 'models/Mantenedores/Origenes/upd_Origen.php',
        type    : 'post',
        data    : {json_Origen:json_Origen, fn_Funcion:'EditarOrigen', pvi_OrigenId:window.vgi_OrigenId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardado!", "Origen guardado correctamente.", "Aceptar");

      $("#mdl_Origen").modal("toggle");
      fn_CargarTablaOrigenes();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el origen por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
