//CARGA TABLA DE PAISES
function fn_CargarTablaPaises(){
  $("#tbl_Paises").dataTable().fnDestroy();
  $('#tbl_Paises tbody').empty();

  $.post('models/Mantenedores/Paises/sel_Pais.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Paises = $.parseJSON(res);

    $('#tbl_Paises').dataTable({
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
			"data": json_Paises,
			"aoColumns": [
        {"data":  "pais_Nombre"},
        {"data":  "pais_Estado"},
        {"data":  "pais_Acciones"}
			],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del pais
function fn_CambiarEstadoPais(pvi_PaisId, pvs_Estado){
  $.post('models/Mantenedores/Paises/upd_Pais.php', {fn_Funcion:'CambiarEstado', pvi_PaisId:pvi_PaisId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Pais "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaPaises();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el pais por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información pais
function fn_EditarPais(pvi_PaisId){
  $.post('models/Mantenedores/Paises/sel_Pais.php', {fn_Funcion:'EditarPais', pvi_PaisId:pvi_PaisId}, function(res){
    var json_Pais = $.parseJSON(res);

    $("#lbl_TituloModalPais").text("Pais - Editar");

    window.vgi_PaisId = pvi_PaisId;
    $("#txt_PaisNombre").val(json_Pais['pais_Nombre']);

    $("#mdl_Pais").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaPaises();

  //ABRIR FORMULARIO PAIS
  $("#btn_AbrirModalPais").click(function (){
    window.vgi_PaisId = 0;

    $("#lbl_TituloModalPais").text("Pais - Nuevo");

    $("#txt_PaisNombre").val('');
  });

  //GUARDAR FORMULARIO PAIS
  $("#frm_Pais").submit(function (e){
    e.preventDefault();

    var json_Pais = {
      vls_PaisNombre  : $("#txt_PaisNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_PaisId == 0){ //CREANDO PAIS
      $.ajax({
        url     : 'models/Mantenedores/Paises/ins_Pais.php',
        type    : 'post',
        data    : {json_Pais:json_Pais, fn_Funcion:'InsertarPais'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO PAIS
      $.ajax({
        url     : 'models/Mantenedores/Paises/upd_Pais.php',
        type    : 'post',
        data    : {json_Pais:json_Pais, fn_Funcion:'EditarPais', pvi_PaisId:window.vgi_PaisId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardado!", "Pais guardado correctamente.", "Aceptar");

      $("#mdl_Pais").modal("toggle");
      fn_CargarTablaPaises();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el pais por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
