 //CARGA TABLA DE INGRESOS
function fn_CargarIngresos(json_Filtro){
  $("#tbl_Ingresos").dataTable().fnDestroy();
  $('#tbl_Ingresos tbody').empty();

  if(json_Filtro === undefined){
		json_Filtro = 0;
	}

  $.post('models/Modulos/Contabilidad/sel_Ingreso.php', {fn_Funcion:'CargarPanel', json_Filtro:json_Filtro}, function(res){
		var json_Ingresos = $.parseJSON(res);

    if(json_Ingresos){
      $("#lbl_IngresosTotales").html("$ " + fn_FormatearNumero(json_Ingresos[0]['ingreso_Total']));
    }
    
    $('#tbl_Ingresos').dataTable({
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
			"data": json_Ingresos,
			"aoColumns": [
        {"data":  "ingreso_Fecha"},
        {"data":  "tipoDoc_Nombre"},
        {"data":  "reserva_Id"},
        {"data":  "categoria_Nombre"},
        {"data":  "propiedad_Nombre"},
        {"data":  "medioPago_Nombre"},
        {"data":  "ingreso_Monto"}
			],
      columnDefs  : [
        {"targets": 0, "width": "13%"},
        {"targets": 1, "width": "13%"},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'desc']
		});
  });
}

$(document).ready(function (){
  /* CARGAS INICIALES */
  fn_CargarIngresos();

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

  fn_CargaComboBox('models/Maestros/Clientes/sel_Cliente.php', 'cmb_ClienteId', 'CargarClientes', ''); //CARGA CLIENTES
  $("#cmb_ClienteId").select2({
    theme: 'bootstrap4'
  });

  $("#btn_Buscar").click(function(){
    var vli_FiltroClienteId = $("#cmb_ClienteId").val();
    var vld_FiltroFechas    = $("#dtp_RangoFecha").val();

    var json_Filtro = {
      vli_FiltroClienteId : vli_FiltroClienteId,
      vld_FiltroFechas    : vld_FiltroFechas
    };

    fn_CargarIngresos(json_Filtro);
  });
});
