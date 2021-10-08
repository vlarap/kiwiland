//CARGA TABLA DE SERVICIOS
function fn_CargarTablaServicios(){
  $("#tbl_Servicios").dataTable().fnDestroy();
  $('#tbl_Servicios tbody').empty();

  $.post('models/Mantenedores/Servicios/sel_Servicio.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Servicios = $.parseJSON(res);

    $('#tbl_Servicios').dataTable({
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
			"data": json_Servicios,
			"aoColumns": [
        {"data":  "servicio_Nombre"},
        {"data":  "servicio_Estado"},
        {"data":  "servicio_Acciones"}
			],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del servicio
function fn_CambiarEstadoServicio(pvi_ServicioId, pvs_Estado){
  $.post('models/Mantenedores/Servicios/upd_Servicio.php', {fn_Funcion:'CambiarEstado', pvi_ServicioId:pvi_ServicioId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Servicio "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaServicios();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el servicio por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información servicio
function fn_EditarServicio(pvi_ServicioId){
  $.post('models/Mantenedores/Servicios/sel_Servicio.php', {fn_Funcion:'EditarServicio', pvi_ServicioId:pvi_ServicioId}, function(res){
    var json_Servicio = $.parseJSON(res);

    $("#lbl_TituloModalServicio").text("Servicio - Editar");

    window.vgi_ServicioId = pvi_ServicioId;
    $("#txt_ServicioNombre").val(json_Servicio['servicio_Nombre']);

    $("#mdl_Servicio").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaServicios();

  //ABRIR FORMULARIO SERVICIO
  $("#btn_AbrirModalServicio").click(function (){
    window.vgi_ServicioId = 0;

    $("#lbl_TituloModalServicio").text("Servicio - Nuevo");

    $("#txt_ServicioNombre").val('');
  });

  //GUARDAR FORMULARIO SERVICIO
  $("#frm_Servicio").submit(function (e){
    e.preventDefault();

    var json_Servicio = {
      vls_ServicioNombre : $("#txt_ServicioNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_ServicioId == 0){ //CREANDO SERVICIO
      $.ajax({
        url     : 'models/Mantenedores/Servicios/ins_Servicio.php',
        type    : 'post',
        data    : {json_Servicio:json_Servicio, fn_Funcion:'InsertarServicio'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO SERVICIO
      $.ajax({
        url     : 'models/Mantenedores/Servicios/upd_Servicio.php',
        type    : 'post',
        data    : {json_Servicio:json_Servicio, fn_Funcion:'EditarServicio', pvi_ServicioId:window.vgi_ServicioId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardado!", "Servicio guardado correctamente.", "Aceptar");

      $("#mdl_Servicio").modal("toggle");
      fn_CargarTablaServicios();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el servicio por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
