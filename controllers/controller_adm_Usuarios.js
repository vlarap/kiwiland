//CARGA TABLA DE USUARIOS
function fn_CargarTablaUsuarios(){
  $("#tbl_Usuarios").dataTable().fnDestroy();
  $('#tbl_Usuarios tbody').empty();

  $.post('models/Administracion/Usuarios/sel_Usuario.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Usuarios = $.parseJSON(res);

    $('#tbl_Usuarios').dataTable({
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
			"data": json_Usuarios,
			"aoColumns": [
        {"data":  "funcionario_Nombre"},
        {"data":  "usuario_Nombre"},
        {"data":  "usuario_Nivel"},
        {"data":  "usuario_Estado"},
        {"data":  "usuario_Acciones"}
			],
      columnDefs  : [
        {"targets": 3, "orderable": false},
        {"targets": 4, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del usuario
function fn_CambiarEstadoUsuario(pvi_UsuarioId, pvs_Estado){
  $.post('models/Administracion/Usuarios/upd_Usuario.php', {fn_Funcion:'CambiarEstado', pvi_UsuarioId:pvi_UsuarioId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Usuario "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaUsuarios();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el usuario por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información usuario
function fn_EditarUsuario(pvi_UsuarioId){
  $.post('models/Administracion/Usuarios/sel_Usuario.php', {fn_Funcion:'EditarUsuario', pvi_UsuarioId:pvi_UsuarioId}, function(res){
    var json_Usuario = $.parseJSON(res);

    $("#lbl_TituloModalUsuario").text("Usuario - Editar");

    window.vgi_UsuarioId = pvi_UsuarioId;
    $("#cmb_FuncionarioId").val(json_Usuario['funcionario_Id']);
    $("#cmb_UsuarioNivel").val(json_Usuario['usuario_Nivel']);
    $("#txt_UsuarioNombre").val(json_Usuario['usuario_Nombre']);
    $("#txt_UsuarioContrasena").val('');
    $("#lbl_AvisoContrasena").show();

    $("#mdl_Usuario").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaUsuarios();
  fn_CargaComboBox('models/Maestros/Funcionarios/sel_Funcionario.php', 'cmb_FuncionarioId', 'CargarFuncionarios', ''); //CARGA FUNCIONARIOS

  //ABRIR FORMULARIO DE USUARIO
  $("#btn_AbrirModalUsuario").click(function (){
    window.vgi_UsuarioId = 0;

    $("#lbl_TituloModalUsuario").text("Usuario - Nuevo");

    $("#cmb_FuncionarioId").val('');
    $("#cmb_UsuarioNivel").val('');
    $("#txt_UsuarioNombre").val('');
    $("#txt_UsuarioContrasena").val('');

    $("#lbl_AvisoContrasena").hide();
  });

  //GUARDAR FORMULARIO DE USUARIO
  $("#frm_Usuario").submit(function (e){
    e.preventDefault();

    var json_Usuario = {
      vli_FuncionarioId     : $("#cmb_FuncionarioId").val(),
      vls_UsuarioNivel      : $("#cmb_UsuarioNivel").val(),
      vls_UsuarioNombre     : $("#txt_UsuarioNombre").val(),
      vls_UsuarioContrasena : $("#txt_UsuarioContrasena").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_UsuarioId == 0){ //CREANDO USUARIO
      $.ajax({
        url     : 'models/Administracion/Usuarios/ins_Usuario.php',
        type    : 'post',
        data    : {json_Usuario:json_Usuario, fn_Funcion:'InsertarUsuario'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO USUARIO
      $.ajax({
        url     : 'models/Administracion/Usuarios/upd_Usuario.php',
        type    : 'post',
        data    : {json_Usuario:json_Usuario, fn_Funcion:'EditarUsuario', pvi_UsuarioId:window.vgi_UsuarioId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardado!", "Usuario guardado correctamente.", "Aceptar");

      $("#mdl_Usuario").modal("toggle");
      fn_CargarTablaUsuarios();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar el usuario por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
