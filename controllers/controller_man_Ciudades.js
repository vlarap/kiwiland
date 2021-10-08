//CARGA TABLA DE CIUDADES
function fn_CargarTablaCiudades(){
  $("#tbl_Ciudades").dataTable().fnDestroy();
  $('#tbl_Ciudades tbody').empty();

  $.post('models/Mantenedores/Ciudades/sel_Ciudad.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Ciudades = $.parseJSON(res);

    $('#tbl_Ciudades').dataTable({
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
			"data": json_Ciudades,
			"aoColumns": [
        {"data":  "ciudad_Nombre"},
        {"data":  "region_Nombre"},
        {"data":  "ciudad_Estado"},
        {"data":  "ciudad_Acciones"}
			],
      columnDefs  : [
        {"targets": 2, "orderable": false},
        {"targets": 3, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado de la ciudad
function fn_CambiarEstadoCiudad(pvi_CiudadId, pvs_Estado){
  $.post('models/Mantenedores/Ciudades/upd_Ciudad.php', {fn_Funcion:'CambiarEstado', pvi_CiudadId:pvi_CiudadId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivada!" : "Activada!"), "Ciudad "+(pvs_Estado == 'D' ? "desactivada" : "activada")+" correctamente.", "Aceptar");

      fn_CargarTablaCiudades();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" la ciudad por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información ciudad
function fn_EditarCiudad(pvi_CiudadId){
  $.post('models/Mantenedores/Ciudades/sel_Ciudad.php', {fn_Funcion:'EditarCiudad', pvi_CiudadId:pvi_CiudadId}, function(res){
    var json_Ciudad = $.parseJSON(res);

    $("#lbl_TituloModalCiudad").text("Ciudad - Editar");

    window.vgi_CiudadId = pvi_CiudadId;
    $("#txt_CiudadNombre").val(json_Ciudad['ciudad_Nombre']);
    $("#cmb_RegionId").val(json_Ciudad['region_Id']);

    $("#mdl_Ciudad").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaCiudades();
  fn_CargaComboBox('models/Mantenedores/Regiones/sel_Region.php', 'cmb_RegionId', 'CargarRegiones2', ''); //CARGA RECINTOS

  //ABRIR FORMULARIO CIUDAD
  $("#btn_AbrirModalCiudad").click(function (){
    window.vgi_CiudadId = 0;

    $("#lbl_TituloModalCiudad").text("Ciudad - Nuevo");

    $("#txt_CiudadNombre").val('');
    $("#cmb_RegionId").val('');
  });

  //GUARDAR FORMULARIO CIUDAD
  $("#frm_Ciudad").submit(function (e){
    e.preventDefault();

    var json_Ciudad = {
      vls_CiudadNombre  : $("#txt_CiudadNombre").val(),
      vli_RegionId      : $("#cmb_RegionId").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_CiudadId == 0){ //CREANDO CIUDAD
      $.ajax({
        url     : 'models/Mantenedores/Ciudades/ins_Ciudad.php',
        type    : 'post',
        data    : {json_Ciudad:json_Ciudad, fn_Funcion:'InsertarCiudad'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO CIUDAD
      $.ajax({
        url     : 'models/Mantenedores/Ciudades/upd_Ciudad.php',
        type    : 'post',
        data    : {json_Ciudad:json_Ciudad, fn_Funcion:'EditarCiudad', pvi_CiudadId:window.vgi_CiudadId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Ciudad guardada correctamente.", "Aceptar");

      $("#mdl_Ciudad").modal("toggle");
      fn_CargarTablaCiudades();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardarla ciudad por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
