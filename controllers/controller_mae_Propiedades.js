//CARGA TABLA DE PROPIEDADES
function fn_CargarTablaPropiedades(){
  $("#tbl_Propiedades").dataTable().fnDestroy();
  $('#tbl_Propiedades tbody').empty();

  $.post('models/Maestros/Propiedades/sel_Propiedad.php', {fn_Funcion:'CargarPanel'}, function(res){
    var json_Propiedades = $.parseJSON(res);

    $('#tbl_Propiedades').dataTable({
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
			"data": json_Propiedades,
			"aoColumns": [
        {"data":  "propiedad_Nombre"},
        {"data":  "propiedad_Capacidad"},
        {"data":  "propiedad_Mantencion"},
        {"data":  "propiedad_Estado"},
        {"data":  "propiedad_Acciones"}
			],
      columnDefs  : [
        {"targets": 2, "orderable": false},
        {"targets": 3, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado de la propiedad
function fn_CambiarEstadoPropiedad(pvi_PropiedadId, pvs_Estado){
  $.post('models/Maestros/Propiedades/upd_Propiedad.php', {fn_Funcion:'CambiarEstado', pvi_PropiedadId:pvi_PropiedadId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivada!" : "Activada!"), "Cabaña "+(pvs_Estado == 'D' ? "desactivada" : "activada")+" correctamente.", "Aceptar");

      fn_CargarTablaPropiedades();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" la cabaña por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información propiedad
function fn_EditarPropiedad(pvi_PropiedadId){
  $('#pnl_ImagenesPropiedad').html('');

  $.post('models/Maestros/Propiedades/sel_Propiedad.php', {fn_Funcion:'EditarPropiedad', pvi_PropiedadId:pvi_PropiedadId}, function(res){
    var json_Propiedad = $.parseJSON(res);

    $("#lbl_TituloModalPropiedad").text("Cabaña - Editar");

    window.vgi_PropiedadId = pvi_PropiedadId;

    $("#txt_PropiedadNombre").val(json_Propiedad[0]['propiedad_Nombre']);
    $("#txt_PropiedadCapacidad").val(json_Propiedad[0]['propiedad_Capacidad']);
    $("#txt_PropiedadDescripcion").val(json_Propiedad[0]['propiedad_Descripcion']);
    $("#cmb_Mantencion").val(json_Propiedad[0]['propiedad_Mantencion']);

    $("#pnl_Imagenes").show();
    $("#pnl_Servicios").show();

    for(i=0;i<json_Propiedad.length;i++){
      if(json_Propiedad[i]['propiedad_Imagen']){
        $('#pnl_ImagenesPropiedad').prepend('<img src="img/propiedades/'+pvi_PropiedadId+'/'+json_Propiedad[i]['propiedad_Imagen']+'" width="200px" /> ')
      }
    }

    fn_CargarTablaServicios();

    $("#mdl_Propiedad").modal();
  });
}

function fn_CargarTablaServicios(){
  $('#tbl_Servicios').find('td').each(function(){
    $("#tbl_Servicios").find("tr:gt(0)").remove();
  });

  //LISTADO DE SERVICIOS
  $.post('models/Maestros/Propiedades/sel_Propiedad.php', {fn_Funcion:'ListadoServicios', pvi_PropiedadId:window.vgi_PropiedadId}, function(res){
    var json_Servicios = $.parseJSON(res);
    for(j=0;j<json_Servicios.length;j++){
      $("#tbl_Servicios tr:last").after('<tr>'+
        '<td>'+json_Servicios[j]['servicio_Nombre']+'</td>'+
        '<td>'+json_Servicios[j]['servicio_Acciones']+'</td>'+
       '</tr>');
    }
  });
}

function fn_EliminarServicioPropiedad(pvi_ServicioId){
  $.ajax({
    url     : 'models/Maestros/Propiedades/del_Propiedad.php',
    type    : 'post',
    data    : {pvi_ServicioId:pvi_ServicioId, pvi_PropiedadId:window.vgi_PropiedadId, fn_Funcion:'EliminarServicio'},
    async   : false,
    success : function(res){
      sql_Respuesta = res;
    }
  });

  if(sql_Respuesta == 1){
    fn_Alerta("success", "Eliminado!", "Servicio eliminado correctamente.", "Aceptar");

    fn_CargarTablaServicios();
  }else{
    fn_Alerta("error", "Error!", "No se pudo eliminar el servicio por el siguiente error: " + sql_Respuesta, "Aceptar");
  }
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaPropiedades();
  $("#pnl_Imagenes").hide();
  $("#pnl_Servicios").hide();
  fn_CargaComboBox('models/Mantenedores/Servicios/sel_Servicio.php', 'cmb_ServicioId', 'CargarServicios', ''); //CARGA CARGOS DE FUNCIONARIO

  //ABRIR FORMULARIO PROPIEDAD
  $("#btn_AbrirModalPropiedad").click(function (){
    window.vgi_PropiedadId = 0;

    $("#lbl_TituloModalPropiedad").text("Cabaña - Nuevo");

    $("#txt_PropiedadNombre").val('');
    $("#txt_PropiedadCapacidad").val('');
    $("#txt_PropiedadDescripcion").val('');
    $("#cmb_Mantencion").val('D');
    $("#pnl_Imagenes").hide();
    $("#pnl_Servicios").hide();
  });

  //AGREGAR SERVICIO ASOCIADO
  $("#btn_AgregarServicio").click(function (){
    if($("#cmb_ServicioId").val() != ''){
      $.ajax({
        url     : 'models/Maestros/Propiedades/ins_Propiedad.php',
        type    : 'post',
        data    : {pvi_ServicioId:$("#cmb_ServicioId").val(), pvi_PropiedadId:window.vgi_PropiedadId, fn_Funcion:'InsertarServicio'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });

      if(sql_Respuesta == 1){
        fn_Alerta("success", "Agregado!", "Servicio agregado correctamente.", "Aceptar");
        $("#cmb_ServicioId").val('');

        fn_CargarTablaServicios();
      }else{
        fn_Alerta("error", "Error!", "No se pudo agregar el servicio por el siguiente error: " + sql_Respuesta, "Aceptar");
      }
    }
  });

  //GUARDAR FORMULARIO PROPIEDAD
  $("#frm_Propiedad").submit(function (e){
    e.preventDefault();

    var json_Propiedad = {
      vls_PropiedadNombre      : $("#txt_PropiedadNombre").val(),
      vli_PropiedadCapacidad   : $("#txt_PropiedadCapacidad").val(),
      vls_PropiedadDescripcion : $("#txt_PropiedadDescripcion").val(),
      vls_Mantencion           : $("#cmb_Mantencion").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_PropiedadId == 0){ //CREANDO PROPIEDAD
      $.ajax({
        url     : 'models/Maestros/Propiedades/ins_Propiedad.php',
        type    : 'post',
        data    : {json_Propiedad:json_Propiedad, fn_Funcion:'InsertarPropiedad'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO PROPIEDAD
      $.ajax({
        url     : 'models/Maestros/Propiedades/upd_Propiedad.php',
        type    : 'post',
        data    : {json_Propiedad:json_Propiedad, fn_Funcion:'EditarPropiedad', pvi_PropiedadId:window.vgi_PropiedadId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Cabaña guardada correctamente.", "Aceptar");

      $("#mdl_Propiedad").modal("toggle");
      fn_CargarTablaPropiedades();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar la cabaña por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });

  //UPLOAD ARCHIVOS CASO
	$("#frm_Imagen").submit(function(e){
  	e.preventDefault();

		$.ajax({
    	url						: "core/upload.php?id="+window.vgi_PropiedadId,
   		type					: "POST",
   		data					: new FormData(this),
   		contentType		: false,
      cache					: false,
   		processData		: false,
   		beforeSend 		: function(){
			},
   		success: function(data){
    		if(data=='Invalido'){
     			fn_Alerta("error", "Error!", "Extensión no valida, sólo puede subir archivos: jpeg, jpg, png, gif, bmp.", "Aceptar");
    		}else{
          fn_EditarPropiedad(window.vgi_PropiedadId);
     			$("#frm_Imagen")[0].reset();
    		}
    	},
    	error: function(e){
    		fn_Alerta("error", "Error!", "Al subir tu archivo, inténtelo nuevamente.", "Aceptar");
    	}
  	});
	});
});
