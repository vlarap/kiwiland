//CARGA TABLA DE CARGOS DE FUNCIONARIO
function fn_CargarTablaCargosFuncionario(){
  $("#tbl_CargosFuncionario").dataTable().fnDestroy();
  $('#tbl_CargosFuncionario tbody').empty();

  $.post('models/Mantenedores/CargosFuncionario/sel_CargoFuncionario.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_CargosFuncionario = $.parseJSON(res);

    $('#tbl_CargosFuncionario').dataTable({
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
			"data": json_CargosFuncionario,
			"aoColumns": [
        {"data":  "cargoFuncionario_Nombre"},
        {"data":  "cargoFuncionario_Estado"},
        {"data":  "cargoFuncionario_Acciones"}
			],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [1, 'asc']
		});
  });
}

//Cambia estado del cargo de funcionario
function fn_CambiarEstadoCargoFuncionario(pvi_CargoFuncionarioId, pvs_Estado){
  $.post('models/Mantenedores/CargosFuncionario/upd_CargoFuncionario.php', {fn_Funcion:'CambiarEstado', pvi_CargoFuncionarioId:pvi_CargoFuncionarioId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivada!" : "Activada!"), "Cargo de funcionario "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaCargosFuncionario();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el cargo de funcionario por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información cargo de funcionario
function fn_EditarCargoFuncionario(pvi_CargoFuncionarioId){
  $.post('models/Mantenedores/CargosFuncionario/sel_CargoFuncionario.php', {fn_Funcion:'EditarCargoFuncionario', pvi_CargoFuncionarioId:pvi_CargoFuncionarioId}, function(res){
    var json_CargoFuncionario = $.parseJSON(res);

    $("#lbl_TituloModalCargoFuncionario").text("Cargo de funcionario - Editar");

    window.vgi_CargoFuncionarioId = pvi_CargoFuncionarioId;
    $("#txt_CargoFuncionarioNombre").val(json_CargoFuncionario['cargoFuncionario_Nombre']);

    $("#mdl_CargoFuncionario").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaCargosFuncionario();

  //ABRIR FORMULARIO CARGO DE FUNCIONARIO
  $("#btn_AbrirModalCargoFuncionario").click(function (){
    window.vgi_CargoFuncionarioId = 0;

    $("#lbl_TituloModalCargoFuncionario").text("Cargo de funcionario - Nuevo");

    $("#txt_CargoFuncionarioNombre").val('');
  });

  //GUARDAR FORMULARIO CARGO DE FUNCIONARIO
  $("#frm_CargoFuncionario").submit(function (e){
    e.preventDefault();

    var json_CargoFuncionario = {
      vls_CargoFuncionarioNombre : $("#txt_CargoFuncionarioNombre").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_CargoFuncionarioId == 0){ //CREANDO CARGO DE FUNCIONARIO
      $.ajax({
        url     : 'models/Mantenedores/CargosFuncionario/ins_CargoFuncionario.php',
        type    : 'post',
        data    : {json_CargoFuncionario:json_CargoFuncionario, fn_Funcion:'InsertarCargoFuncionario'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO CARGO DE FUNCIONARIO
      $.ajax({
        url     : 'models/Mantenedores/CargosFuncionario/upd_CargoFuncionario.php',
        type    : 'post',
        data    : {json_CargoFuncionario:json_CargoFuncionario, fn_Funcion:'EditarCargoFuncionario', pvi_CargoFuncionarioId:window.vgi_CargoFuncionarioId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardado!", "Cargo de funcionario guardado correctamente.", "Aceptar");

      $("#mdl_CargoFuncionario").modal("toggle");
      fn_CargarTablaCargosFuncionario();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el cargo de funcionario por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
