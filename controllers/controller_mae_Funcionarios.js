//CARGA TABLA DE FUNCIONARIOS
function fn_CargarTablaFuncionarios(){
  $("#tbl_Funcionarios").dataTable().fnDestroy();
  $('#tbl_Funcionarios tbody').empty();

  $.post('models/Maestros/Funcionarios/sel_Funcionario.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Funcionarios = $.parseJSON(res);

    $('#tbl_Funcionarios').dataTable({
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
			"data": json_Funcionarios,
			"aoColumns": [
        {"data":  "funcionario_Rut"},
        {"data":  "funcionario_Nombre"},
        {"data":  "cargoFuncionario_Nombre"},
        {"data":  "funcionario_Direccion"},
        {"data":  "funcionario_Telefono"},
        {"data":  "funcionario_Estado"},
        {"data":  "funcionario_Acciones"}
			],
      columnDefs  : [
        {"targets": 4, "orderable": false},
        {"targets": 5, "orderable": false},
        {"targets": 6, "orderable": false}
      ],
			"aaSorting": [1, 'desc']
		});
  });
}

//Cambia estado de funcionario
function fn_CambiarEstadoFuncionario(pvi_FuncionarioId, pvs_Estado){
  $.post('models/Maestros/Funcionarios/upd_Funcionario.php', {fn_Funcion:'CambiarEstado', pvi_FuncionarioId:pvi_FuncionarioId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Funcionario "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaFuncionarios();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el funcionario por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información de funcionario
function fn_EditarFuncionario(pvi_FuncionarioId){
  $.post('models/Maestros/Funcionarios/sel_Funcionario.php', {fn_Funcion:'EditarFuncionario', pvi_FuncionarioId:pvi_FuncionarioId}, function(res){
    var json_Funcionario = $.parseJSON(res);

    $("#lbl_TituloModalFuncionario").text("Funcionario - Editar");
    window.vgi_FuncionarioId = pvi_FuncionarioId;
    $("#txt_FuncionarioRut").val(json_Funcionario['funcionario_Rut']);
    $("#cmb_CargoFuncionarioId").val(json_Funcionario['cargoFuncionario_Id']);
    $("#txt_FuncionarioNombres").val(json_Funcionario['funcionario_Nombres']);
    $("#txt_FuncionarioApellidoP").val(json_Funcionario['funcionario_ApellidoPaterno']);
    $("#txt_FuncionarioApellidoM").val(json_Funcionario['funcionario_ApellidoMaterno']);
    $("#txt_FuncionarioDireccion").val(json_Funcionario['funcionario_Direccion']);
    $("#txt_FuncionarioTelefono").val(json_Funcionario['funcionario_Telefono']);

    $("#mdl_Funcionario").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaFuncionarios();
  fn_CargaComboBox('models/Mantenedores/CargosFuncionario/sel_CargoFuncionario.php', 'cmb_CargoFuncionarioId', 'CargarCargosFuncionario', ''); //CARGA CARGOS DE FUNCIONARIO

  //ABRIR FORMULARIO FUNCIONARIO
  $("#btn_AbrirModalFuncionario").click(function (){
    window.vgi_FuncionarioId = 0;

    $("#lbl_TituloModalFuncionario").text("Funcionario - Nuevo");

    $("#txt_FuncionarioRut").val('');
    $("#cmb_CargoFuncionarioId").val('');
    $("#txt_FuncionarioNombres").val('');
    $("#txt_FuncionarioApellidoP").val('');
    $("#txt_FuncionarioApellidoM").val('');
    $("#txt_FuncionarioDireccion").val('');
    $("#txt_FuncionarioTelefono").val('');
  });

  //GUARDAR FORMULARIO FUNCIONARIO
  $("#frm_Funcionario").submit(function (e){
    e.preventDefault();
    var vlb_ErrorRut = fn_ValidarRut($("#txt_FuncionarioRut").val());

    if(vlb_ErrorRut == true){ //ErrorRut = TRUE, rut validado
      var json_Funcionario = {
        vls_FuncionarioRut        : fn_LimpiarRut($("#txt_FuncionarioRut").val()),
        vli_CargoFuncionarioId    : $("#cmb_CargoFuncionarioId").val(),
        vls_FuncionarioNombres    : $("#txt_FuncionarioNombres").val(),
        vls_FuncionarioApellidoP  : $("#txt_FuncionarioApellidoP").val(),
        vls_FuncionarioApellidoM  : $("#txt_FuncionarioApellidoM").val(),
        vls_FuncionarioDireccion  : $("#txt_FuncionarioDireccion").val(),
        vls_FuncionarioTelefono   : $("#txt_FuncionarioTelefono").val()
      };

      var sql_Respuesta = '';
      if(window.vgi_FuncionarioId == 0){ //CREANDO FUNCIONARIO
        $.ajax({
  				url     : 'models/Maestros/Funcionarios/ins_Funcionario.php',
  				type    : 'post',
  				data    : {json_Funcionario:json_Funcionario, fn_Funcion:'InsertarFuncionario'},
  				async   : false,
  				success : function(res){
            sql_Respuesta = res;
          }
  			});
      }else{ //EDITANDO FUNCIONARIO
        $.ajax({
  				url     : 'models/Maestros/Funcionarios/upd_Funcionario.php',
  				type    : 'post',
  				data    : {json_Funcionario:json_Funcionario, fn_Funcion:'EditarFuncionario', pvi_FuncionarioId:window.vgi_FuncionarioId},
  				async   : false,
  				success : function(res){
            sql_Respuesta = res;
          }
  			});
      }

      if(sql_Respuesta == 1){
        fn_Alerta("success", "Guardado!", "Funcionario guardado correctamente.", "Aceptar");

        $("#mdl_Funcionario").modal("toggle");
        fn_CargarTablaFuncionarios();
      }else{
        fn_Alerta("error", "Error!", "No se pudo guardar el funcionario por el siguiente error: " + sql_Respuesta, "Aceptar");
      }
    }
  });
});
