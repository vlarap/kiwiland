//CARGA TABLA DE CENTROS DE COSTO
function fn_CargarTablaCentrosCosto(){
  $("#tbl_CentrosCosto").dataTable().fnDestroy();
  $('#tbl_CentrosCosto tbody').empty();

  $.post('models/Mantenedores/CentrosCosto/sel_CentroCosto.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_CentrosCosto = $.parseJSON(res);

    $('#tbl_CentrosCosto').dataTable({
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
			"data": json_CentrosCosto,
			"aoColumns": [
        {"data":  "cCosto_Nombre"},
        {"data":  "cCosto_Estado"},
        {"data":  "cCosto_Acciones"}
			],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del centro de costo
function fn_CambiarEstadoCentroCosto(pvi_CCostoId, pvs_Estado){
  $.post('models/Mantenedores/CentrosCosto/upd_CentroCosto.php', {fn_Funcion:'CambiarEstado', pvi_CCostoId:pvi_CCostoId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Centro de costo "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaCentrosCosto();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el centro de costo por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información de centro de costo
function fn_EditarCentroCosto(pvi_CCostoId){
  $.post('models/Mantenedores/CentrosCosto/sel_CentroCosto.php', {fn_Funcion:'EditarCentroCosto', pvi_CCostoId:pvi_CCostoId}, function(res){
    var json_CentroCosto = $.parseJSON(res);

    $("#lbl_TituloModalCentroCosto").text("Centro de costo - Editar");

    window.vgi_CCostoId = pvi_CCostoId;
    $("#txt_CCostoNombre").val(json_CentroCosto['cCosto_Nombre']);

    $("#mdl_CentroCosto").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaCentrosCosto();

  //ABRIR FORMULARIO CENTRO DE COSTO
  $("#btn_AbrirModalCentroCosto").click(function (){
    window.vgi_CCostoId = 0;

    $("#lbl_TituloModalCentroCosto").text("Centro de costo - Nuevo");

    $("#txt_CCostoNombre").val('');
  });

  //GUARDAR FORMULARIO CENTRO DE COSTO
  $("#frm_CentroCosto").submit(function (e){
    e.preventDefault();

    var json_CentroCosto = {
      vls_CCostoNombre  : $("#txt_CCostoNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_CCostoId == 0){ //CREANDO CENTRO DE COSTO
      $.ajax({
        url     : 'models/Mantenedores/CentrosCosto/ins_CentroCosto.php',
        type    : 'post',
        data    : {json_CentroCosto:json_CentroCosto, fn_Funcion:'InsertarCentroCosto'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO CENTRO DE COSTO
      $.ajax({
        url     : 'models/Mantenedores/CentrosCosto/upd_CentroCosto.php',
        type    : 'post',
        data    : {json_CentroCosto:json_CentroCosto, fn_Funcion:'EditarCentroCosto', pvi_CCostoId:window.vgi_CCostoId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardado!", "Centro de costo guardado correctamente.", "Aceptar");

      $("#mdl_CentroCosto").modal("toggle");
      fn_CargarTablaCentrosCosto();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el centro de costo por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
