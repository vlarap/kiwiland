//CARGA TABLA DE REGIONES
function fn_CargarTablaRegiones(){
  $("#tbl_Regiones").dataTable().fnDestroy();
  $('#tbl_Regiones tbody').empty();

  $.post('models/Mantenedores/Regiones/sel_Region.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Regiones = $.parseJSON(res);

    $('#tbl_Regiones').dataTable({
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
			"data": json_Regiones,
			"aoColumns": [
        {"data":  "region_Nombre"},
        {"data":  "pais_Nombre"},
        {"data":  "region_Estado"},
        {"data":  "region_Acciones"}
			],
      columnDefs  : [
        {"targets": 2, "orderable": false},
        {"targets": 3, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado de la region
function fn_CambiarEstadoRegion(pvi_RegionId, pvs_Estado){
  $.post('models/Mantenedores/Regiones/upd_Region.php', {fn_Funcion:'CambiarEstado', pvi_RegionId:pvi_RegionId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivada!" : "Activada!"), "Región "+(pvs_Estado == 'D' ? "desactivada" : "activada")+" correctamente.", "Aceptar");

      fn_CargarTablaRegiones();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" la región por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información region
function fn_EditarRegion(pvi_RegionId){
  $.post('models/Mantenedores/Regiones/sel_Region.php', {fn_Funcion:'EditarRegion', pvi_RegionId:pvi_RegionId}, function(res){
    var json_Region = $.parseJSON(res);

    $("#lbl_TituloModalRegion").text("Region - Editar");

    window.vgi_RegionId = pvi_RegionId;
    $("#txt_RegionNombre").val(json_Region['region_Nombre']);

    $("#mdl_Region").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaRegiones();
  fn_CargaComboBox('models/Mantenedores/Ciudades/sel_Ciudad.php', 'cmb_CiudadId', 'CargarCiudades', ''); //CARGA RECINTOS

  //ABRIR FORMULARIO REGION
  $("#btn_AbrirModalRegion").click(function (){
    window.vgi_RegionId = 0;

    $("#lbl_TituloModalRegion").text("Región - Nuevo");

    $("#txt_RegionNombre").val('');
  });

  //GUARDAR FORMULARIO REGION
  $("#frm_Region").submit(function (e){
    e.preventDefault();

    var json_Region = {
      vls_RegionNombre  : $("#txt_RegionNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_RegionId == 0){ //CREANDO REGION
      $.ajax({
        url     : 'models/Mantenedores/Regiones/ins_Region.php',
        type    : 'post',
        data    : {json_Region:json_Region, fn_Funcion:'InsertarRegion'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO REGION
      $.ajax({
        url     : 'models/Mantenedores/Regiones/upd_Region.php',
        type    : 'post',
        data    : {json_Region:json_Region, fn_Funcion:'EditarRegion', pvi_RegionId:window.vgi_RegionId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Región guardada correctamente.", "Aceptar");

      $("#mdl_Region").modal("toggle");
      fn_CargarTablaRegiones();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardarla región por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
