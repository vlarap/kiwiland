//CARGA TABLA DE COMUNAS
function fn_CargarTablaComunas(){
  $("#tbl_Comunas").dataTable().fnDestroy();
  $('#tbl_Comunas tbody').empty();

  $.post('models/Mantenedores/Comunas/sel_Comuna.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Comunas = $.parseJSON(res);

    $('#tbl_Comunas').dataTable({
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
			"data": json_Comunas,
			"aoColumns": [
        {"data":  "comuna_Nombre"},
        {"data":  "ciudad_Nombre"},
        {"data":  "comuna_Estado"},
        {"data":  "comuna_Acciones"}
			],
      columnDefs  : [
        {"targets": 2, "orderable": false},
        {"targets": 3, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado de la comuna
function fn_CambiarEstadoComuna(pvi_ComunaId, pvs_Estado){
  $.post('models/Mantenedores/Comunas/upd_Comuna.php', {fn_Funcion:'CambiarEstado', pvi_ComunaId:pvi_ComunaId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivada!" : "Activada!"), "Comuna "+(pvs_Estado == 'D' ? "desactivada" : "activada")+" correctamente.", "Aceptar");

      fn_CargarTablaComunas();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" la comuna por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información comuna
function fn_EditarComuna(pvi_ComunaId){
  $.post('models/Mantenedores/Comunas/sel_Comuna.php', {fn_Funcion:'EditarComuna', pvi_ComunaId:pvi_ComunaId}, function(res){
    var json_Comuna = $.parseJSON(res);

    $("#lbl_TituloModalComuna").text("Comuna - Editar");

    window.vgi_ComunaId = pvi_ComunaId;
    $("#txt_ComunaNombre").val(json_Comuna['comuna_Nombre']);
    $("#cmb_CiudadId").val(json_Comuna['ciudad_Id']);

    $("#mdl_Comuna").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaComunas();
  fn_CargaComboBox('models/Mantenedores/Ciudades/sel_Ciudad.php', 'cmb_CiudadId', 'CargarCiudades', ''); //CARGA RECINTOS

  //ABRIR FORMULARIO COMUNA
  $("#btn_AbrirModalComuna").click(function (){
    window.vgi_ComunaId = 0;

    $("#lbl_TituloModalComuna").text("Comuna - Nuevo");

    $("#txt_ComunaNombre").val('');
    $("#cmb_CiudadId").val('');
  });

  //GUARDAR FORMULARIO COMUNA
  $("#frm_Comuna").submit(function (e){
    e.preventDefault();

    var json_Comuna = {
      vls_ComunaNombre  : $("#txt_ComunaNombre").val(),
      vli_CiudadId      : $("#cmb_CiudadId").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_ComunaId == 0){ //CREANDO COMUNA
      $.ajax({
        url     : 'models/Mantenedores/Comunas/ins_Comuna.php',
        type    : 'post',
        data    : {json_Comuna:json_Comuna, fn_Funcion:'InsertarComuna'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO COMUNA
      $.ajax({
        url     : 'models/Mantenedores/Comunas/upd_Comuna.php',
        type    : 'post',
        data    : {json_Comuna:json_Comuna, fn_Funcion:'EditarComuna', pvi_ComunaId:window.vgi_ComunaId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Comuna guardada correctamente.", "Aceptar");

      $("#mdl_Comuna").modal("toggle");
      fn_CargarTablaComunas();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardarla comuna por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
