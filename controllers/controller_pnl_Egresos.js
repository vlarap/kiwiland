window.vgi_EgresoId = 0;

 //CARGA TABLA DE EGRESOS
function fn_CargarEgresos(json_Filtro){
  $("#tbl_Egresos").dataTable().fnDestroy();
  $('#tbl_Egresos tbody').empty();

  if(json_Filtro === undefined){
		json_Filtro = 0;
	}

  $.post('models/Modulos/Contabilidad/sel_Egreso.php', {fn_Funcion:'CargarPanel', json_Filtro:json_Filtro}, function(res){
    var json_Egresos = $.parseJSON(res);
    
    if(json_Egresos){
      $("#lbl_EgresosTotales").html("$ " + fn_FormatearNumero(json_Egresos[0]['egreso_Total']));
    }  

    $('#tbl_Egresos').dataTable({
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
			"data": json_Egresos,
			"aoColumns": [
        {"data":  "egreso_Fecha"},
        {"data":  "egreso_NroDoc"},
        {"data":  "tipoDoc_Nombre"},
        {"data":  "cliente_Nombre"},
        {"data":  "categoria_Nombre"},
        {"data":  "egreso_Monto"},
        {"data":  "egreso_Estado"},
        {"data":  "egreso_Acciones"}
			],
      columnDefs  : [
        {"targets": 0, "width": "90px"},
        {"targets": 1, "width": "60px"},
        {"targets": 7, "width": "50px", "orderable": false}
      ],
			"aaSorting": [0, 'desc']
		});
  });
}

function fn_CargarCCostos(pvi_CCostoId) {
    var cCosto_Id = '<option value="">-- Seleccionar --</option>';

    var json_CCostos;
    $.ajax({
      url     : 'models/Mantenedores/CentrosCosto/sel_CentroCosto.php',
      type    : 'post',
      data    : {fn_Funcion:'CargarCentrosCosto'},
      async   : false,
      success : function(res){
        json_CCostos = $.parseJSON(res);
      }
    });

    if (json_CCostos) {
        for (j = 0; j < json_CCostos.length; j++) {
            if (pvi_CCostoId == '') {
                cCosto_Id += '<option value="' + json_CCostos[j]['id'] + '">' + json_CCostos[j]['nombre'] + '</option>';
            } else {
                if ($.isNumeric(pvi_CCostoId)) {
                    if (pvi_CCostoId == json_CCostos[j]['id']) {
                        cCosto_Id += '<option value="' + json_CCostos[j]['id'] + '" selected>' + json_CCostos[j]['nombre'] + '</option>';
                    } else {
                        cCosto_Id += '<option value="' + json_CCostos[j]['id'] + '">' + json_CCostos[j]['nombre'] + '</option>';
                    }
                } else {
                    if (pvi_CCostoId == json_CCostos[j]['personal_id']) {
                        cCosto_Id += '<option value="' + json_CCostos[j]['id'] + '" selected>' + json_CCostos[j]['nombre'] + '</option>';
                    } else {
                        cCosto_Id += '<option value="' + json_CCostos[j]['id'] + '">' + json_CCostos[j]['nombre'] + '</option>';
                    }
                }
            }
        }
    }

    return cCosto_Id;
}

function fn_Limpiar(){
  $("#txt_EgresoNroDocto").val('');
  $("#cmb_TipoDocId").val('').trigger('change');
  $("#cmb_CategoriaId").val('').trigger('change');
  $("#txt_EgresoTotal").val('');
  $("#dtp_EgresoFecha").val('');
  $("#cmb_ClienteId").val('').trigger('change');
  $("#txt_EgresoComentario").val('');

  $('#tbl_DetalleEgreso').find('td').each(function(){
		$("#tbl_DetalleEgreso").find("tr:gt(0)").remove();
	});
}

function fn_EditarEgreso(pvi_EgresoId){
  fn_Limpiar();
  window.vgi_EgresoId = pvi_EgresoId;

  $.post('models/Modulos/Contabilidad/sel_Egreso.php', {fn_Funcion:'EditarEgreso', pvi_EgresoId:pvi_EgresoId}, function(res){
		var json_Egreso = $.parseJSON(res);

    $("#txt_EgresoNroDocto").val(json_Egreso[0]['egreso_NroDoc']);
    fn_CargaComboBox('models/Mantenedores/TiposDocumento/sel_TipoDocumento.php', 'cmb_TipoDocId', 'CargarTiposDocumento', json_Egreso[0]['tipoDoc_Id']); //CARGA TIPO DOC
    $("#cmb_TipoDocId").val(json_Egreso[0]['tipoDoc_Id']).trigger('change');
    fn_CargaComboBox('models/Mantenedores/Categorias/sel_Categoria.php', 'cmb_CategoriaId', 'CargarCategoriasXTipo', json_Egreso[0]['categoria_Id'], 'E'); //CARGA CATEGORIAS
    $("#cmb_CategoriaId").val(json_Egreso[0]['categoria_Id']).trigger('change');
    $("#txt_EgresoTotal").val(json_Egreso[0]['egreso_Total']);
    $("#dtp_EgresoFecha").val(json_Egreso[0]['egreso_Fecha']);
    fn_CargaComboBox('models/Maestros/Clientes/sel_Cliente.php', 'cmb_ClienteId', 'CargarClientes', json_Egreso[0]['cliente_Id']); //CARGA CLIENTES
    $("#cmb_ClienteId").val(json_Egreso[0]['cliente_Id']).trigger('change');
    $("#txt_EgresoComentario").val(json_Egreso[0]['egreso_Comentario']);

    for(i = 0; i < json_Egreso.length; i++){
      $("#tbl_DetalleEgreso tr:last").after('<tr>'+
  			'<td><select class="form-control input-sm" required>'+fn_CargarCCostos(json_Egreso[i]['cCosto_Id'])+'</select></td>'+
  			'<td><input type="number" class="form-control input-sm" value='+json_Egreso[i]['egresoCC_Monto']+' required/></td>'+
  			'<td style="width:25px;">'+
  				'<button data-toggle="tooltip" data-placement="top" title="Eliminar trabajo" class="btn btn-xs btn-danger fn_EliminarItem"><i class="fa fa-trash"></i></button>'+
  		  '</td>'+
  		 '</tr>');
    }

    $("#mdl_Egreso").modal();
  });
}

function fn_AnularEgreso(pvi_EgresoId){
  $.post('models/Modulos/Contabilidad/upd_Egreso.php', {fn_Funcion:'AnularEgreso', pvi_EgresoId:pvi_EgresoId}, function(res){
    if($.isNumeric(res)){
      fn_Alerta("success", "Anulado!", "Egreso anulado correctamente.", "Aceptar");
      fn_CargarEgresos();
    }else{
      fn_Alerta("error", "Error!", "No se pudo anular el egreso por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
}

$(document).on('click', 'button.fn_EliminarItem', function(){
	$(this).closest('tr').remove();
});

$(document).ready(function (){
  /* CARGAS INICIALES */
  fn_CargarEgresos();
  fn_CargaComboBox('models/Maestros/Clientes/sel_Cliente.php', 'cmb_ClienteIdFiltro', 'CargarClientes', ''); //CARGA CLIENTES
  fn_CargaComboBox('models/Maestros/Clientes/sel_Cliente.php', 'cmb_ClienteId', 'CargarClientes', ''); //CARGA CLIENTES
  $("#cmb_ClienteIdFiltro").select2({
    theme: 'bootstrap4'
  });
  $("#cmb_ClienteId").select2({
    theme: 'bootstrap4'
  });

  fn_CargaComboBox('models/Mantenedores/Categorias/sel_Categoria.php', 'cmb_CategoriaIdFiltro', 'CargarCategoriasXTipo', '', 'E'); //CARGA CATEGORIAS
  fn_CargaComboBox('models/Mantenedores/Categorias/sel_Categoria.php', 'cmb_CategoriaId', 'CargarCategoriasXTipo', '', 'E'); //CARGA CATEGORIAS
  $("#cmb_CategoriaIdFiltro").select2({
    theme: 'bootstrap4'
  });
  $("#cmb_CategoriaId").select2({
    theme: 'bootstrap4'
  });

  fn_CargaComboBox('models/Mantenedores/TiposDocumento/sel_TipoDocumento.php', 'cmb_TipoDocId', 'CargarTiposDocumento', ''); //CARGA TIPO DOC
  $("#cmb_TipoDocId").select2({
    theme: 'bootstrap4'
  });

  $('input[name="RangoFecha"]').daterangepicker({
    format: 'dd-mm-yyyy',
    showDropdowns: true,
    autoUpdateInput: false,
    locale: {
      "daysOfWeek": [
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa",
            "Do"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      applyLabel: "Aceptar",
      cancelLabel: "Limpiar"
    }
  });

  $('input[name="RangoFecha"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
  });

  $('input[name="RangoFecha"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });

  var vld_Fd        = Date.today().clearTime().moveToFirstDayOfMonth();
  var vld_PrimerDia = vld_Fd.toString("dd-MM-yyyy");
  var vld_Ld        = Date.today().clearTime().moveToLastDayOfMonth();
  var vld_UltimoDia = vld_Ld.toString("dd-MM-yyyy");
  $('input[name="RangoFecha"]').val(vld_PrimerDia + ' - ' + vld_UltimoDia);

  $("#btn_Buscar").click(function(){
    var vli_FiltroCCostoId  = $("#cmb_CentroCostoIdFiltro").val();
    var vli_FiltroClienteId = $("#cmb_ClienteIdFiltro").val();
    var vld_FiltroFechas    = $("#dtp_RangoFechaFiltro").val();

    var json_Filtro = {
      vli_FiltroCCostoId  : vli_FiltroCCostoId,
      vli_FiltroClienteId : vli_FiltroClienteId,
      vld_FiltroFechas    : vld_FiltroFechas
    };

    fn_CargarEgresos(json_Filtro);
  });


  /* MODAL PARA EGRESO */
  $("#btn_Nueva").click(function (){
    fn_Limpiar();
    $("#mdl_Egreso").modal();
  });

  $("#btn_AgregarCC").click(function(){
		$("#tbl_DetalleEgreso tr:last").after('<tr>'+
			'<td><select class="form-control input-sm" required>'+fn_CargarCCostos('')+'</select></td>'+
			'<td><input type="number" class="form-control input-sm" required/></td>'+
			'<td style="width:25px;">'+
				'<button data-toggle="tooltip" data-placement="top" title="Eliminar trabajo" class="btn btn-xs btn-danger fn_EliminarItem"><i class="fa fa-trash"></i></button>'+
		  '</td>'+
		 '</tr>');
	});

  $("#frm_IngresarEgreso").submit(function (e){
    e.preventDefault();

    if($("#tbl_DetalleEgreso tr").length > 1){
      /*CARGAMOS LOS TRABAJOS A REALIZAR*/
  	  var vlARR_CCostoId = [];
  		var vlARR_Monto 	 = [];

      for(i=1;i<$("#tbl_DetalleEgreso tr").length;i++){
  			vlARR_CCostoId.push($("#tbl_DetalleEgreso tr:eq("+(i)+")").find("td").eq(0).find("select").val());
  			vlARR_Monto.push($("#tbl_DetalleEgreso tr:eq("+(i)+")").find("td").eq(1).find("input").val());
  		}

      var json_Egreso = {
        vli_EgresoNroDocto   : $("#txt_EgresoNroDocto").val(),
        vli_TipoDocId        : $("#cmb_TipoDocId").val(),
        vli_CategoriaId      : $("#cmb_CategoriaId").val(),
        vld_EgresoFecha      : $("#dtp_EgresoFecha").val(),
        vli_ClienteId        : $("#cmb_ClienteId").val(),
        vls_EgresoComentario : $("#txt_EgresoComentario").val(),
        vlARR_CCostoId       : vlARR_CCostoId,
        vlARR_Monto          : vlARR_Monto
      };

      var sql_Respuesta = '';
      if(window.vgi_EgresoId == 0){
        $.ajax({
          url     : 'models/Modulos/Contabilidad/ins_Egreso.php',
          type    : 'post',
          data    : {json_Egreso:json_Egreso, fn_Funcion:'InsertarEgreso'},
          async   : false,
          success : function(res){
            sql_Respuesta = res;
          }
        });
      }else{
        $.ajax({
          url     : 'models/Modulos/Contabilidad/upd_Egreso.php',
          type    : 'post',
          data    : {json_Egreso:json_Egreso, fn_Funcion:'EditarEgreso', pvi_EgresoId:window.vgi_EgresoId},
          async   : false,
          success : function(res){
            sql_Respuesta = res;
          }
        });
      }

      if($.isNumeric(sql_Respuesta)){
        fn_Alerta("success", "Guardado!", "Egreso guardado correctamente.", "Aceptar");

        $("#mdl_Egreso").modal("toggle");
        fn_CargarEgresos();
      }else{
        fn_Alerta("error", "Error!", "No se pudo guardar el egreso por el siguiente error: " + sql_Respuesta, "Aceptar");
      }
    }else{
      fn_Alerta("error", "Error!", "Debe tener mínimo un detalle de egreso", "Aceptar");
    }
  });
});
