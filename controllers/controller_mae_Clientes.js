//CARGA TABLA DE CLIENTES
function fn_CargarTablaClientes(){
  $("#tbl_Clientes").dataTable().fnDestroy();
  $('#tbl_Clientes tbody').empty();

  $.post('models/Maestros/Clientes/sel_Cliente.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Clientes = $.parseJSON(res);

    $('#tbl_Clientes').dataTable({
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
			"data": json_Clientes,
			"aoColumns": [
        {"data":  "cliente_Tipo"},
        {"data":  "cliente_Rut"},
        {"data":  "cliente_Nombre"},
        {"data":  "cliente_Direccion"},
        {"data":  "cliente_Celular"},
        {"data":  "cliente_CorreoElectronico"},
        {"data":  "cliente_Valoracion"},
        {"data":  "cliente_Estado"},
        {"data":  "cliente_Acciones"}
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

//Cambia estado de cliente
function fn_CambiarEstadoCliente(pvi_ClienteId, pvs_Estado){
  $.post('models/Maestros/Clientes/upd_Cliente.php', {fn_Funcion:'CambiarEstado', pvi_ClienteId:pvi_ClienteId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivado!" : "Activado!"), "Cliente "+(pvs_Estado == 'D' ? "desactivado" : "activado")+" correctamente.", "Aceptar");

      fn_CargarTablaClientes();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" el cliente por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información de cliente
function fn_EditarCliente(pvi_ClienteId){
  $.post('models/Maestros/Clientes/sel_Cliente.php', {fn_Funcion:'EditarCliente', pvi_ClienteId:pvi_ClienteId}, function(res){
    var json_Cliente = $.parseJSON(res);

    $("#lbl_TituloModalCliente").text("Cliente - Editar");
    $("#cmb_ClienteTipo").val(json_Cliente['cliente_Tipo']);
    window.vgi_ClienteId = pvi_ClienteId;
    $("#txt_ClienteRut").val(json_Cliente['cliente_Rut']);

    fn_CargaComboBox('models/Mantenedores/Paises/sel_Pais.php', 'cmb_NacionalidadId', 'CargarPaises', json_Cliente['nacionalidad_Id']);
    $("#txt_ClienteNombres").val(json_Cliente['cliente_Nombres']);
    $("#txt_ClienteApellidoPaterno").val(json_Cliente['cliente_ApellidoPaterno']);
    $("#txt_ClienteApellidoMaterno").val(json_Cliente['cliente_ApellidoMaterno']);

    if(json_Cliente['pais_Id']){
      fn_CargaComboBox('models/Mantenedores/Paises/sel_Pais.php', 'cmb_PaisId', 'CargarPaises', json_Cliente['pais_Id']); //CARGA PAISES
    }
    if(json_Cliente['region_Id']){
      fn_CargaComboBox('models/Mantenedores/Regiones/sel_Region.php', 'cmb_RegionId', 'CargarRegiones', json_Cliente['region_Id'], json_Cliente['pais_Id']); //CARGA REGIONES
    }
    if(json_Cliente['ciudad_Id']){
      fn_CargaComboBox('models/Mantenedores/Ciudades/sel_Ciudad.php', 'cmb_CiudadId', 'CargarCiudadesXRegion', json_Cliente['ciudad_Id'], json_Cliente['region_Id']); //CARGA CIUDADES
    }
    if(json_Cliente['comuna_Id']){
      fn_CargaComboBox('models/Mantenedores/Comunas/sel_Comuna.php', 'cmb_ComunaId', 'CargarComunasXCiudad', json_Cliente['comuna_Id'], json_Cliente['ciudad_Id']); //CARGA COMUNAS
    }

    $("#txt_ClienteDireccion").val(json_Cliente['cliente_Direccion']);
    $("#txt_ClienteCorreoElectronico").val(json_Cliente['cliente_CorreoElectronico']);
    $("#txt_ClienteTelefonoFijo").val(json_Cliente['cliente_TelefonoFijo']);
    $("#txt_ClienteCelular").val(json_Cliente['cliente_Celular']);

    $("#cmb_ClienteTipo").trigger("change");
    $("#mdl_Cliente").modal();
  });
}

function fn_CargarValoraciones(pvi_ClienteId){
  $("#tbl_Valoraciones").dataTable().fnDestroy();
  $('#tbl_Valoraciones tbody').empty();

  $.post('models/Maestros/Clientes/sel_Cliente.php', {fn_Funcion:'CargarValoraciones', pvi_ClienteId:pvi_ClienteId}, function(res){
		var json_Valoraciones = $.parseJSON(res);

    $('#tbl_Valoraciones').dataTable({
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
			"data": json_Valoraciones,
			"aoColumns": [
        {"data":  "reserva_Id"},
        {"data":  "valoracion_Puntaje"},
        {"data":  "valoracion_Observacion"}
			],
			"aaSorting": [0, 'desc']
		});

    $("#mdl_Valoraciones").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaClientes();
  fn_CargaComboBox('models/Mantenedores/Paises/sel_Pais.php', 'cmb_NacionalidadId', 'CargarPaises', '');
  fn_CargaComboBox('models/Mantenedores/Paises/sel_Pais.php', 'cmb_PaisId', 'CargarPaises', ''); //CARGA PAISES
  $("#cmb_PaisId").change(function(){
    if($("#cmb_PaisId").val() != ''){
      fn_CargaComboBox('models/Mantenedores/Regiones/sel_Region.php', 'cmb_RegionId', 'CargarRegiones', '', $("#cmb_PaisId").val()); //CARGA REGIONES
      fn_ReiniciarComboBox('cmb_CiudadId');
      fn_ReiniciarComboBox('cmb_ComunaId');
    }else{
      fn_ReiniciarComboBox('cmb_RegionId');
      fn_ReiniciarComboBox('cmb_CiudadId');
      fn_ReiniciarComboBox('cmb_ComunaId');
    }
  });

  $("#cmb_RegionId").change(function(){
    if($("#cmb_RegionId").val() != ''){
      fn_CargaComboBox('models/Mantenedores/Ciudades/sel_Ciudad.php', 'cmb_CiudadId', 'CargarCiudadesXRegion', '', $("#cmb_RegionId").val()); //CARGA CIUDADES
      fn_ReiniciarComboBox('cmb_ComunaId');
    }else{
      fn_ReiniciarComboBox('cmb_CiudadId');
      fn_ReiniciarComboBox('cmb_ComunaId');
    }
  });

  $("#cmb_CiudadId").change(function(){
    if($("#cmb_CiudadId").val() != ''){
      fn_CargaComboBox('models/Mantenedores/Comunas/sel_Comuna.php', 'cmb_ComunaId', 'CargarComunasXCiudad', '', $("#cmb_CiudadId").val()); //CARGA COMUNAS
    }else{
      fn_ReiniciarComboBox('cmb_CiudadId');
      fn_ReiniciarComboBox('cmb_ComunaId');
    }
  });

  //SELECTOR DE TIPO DE CLIENTE
  $("#cmb_ClienteTipo").change(function(){
    if($("#cmb_ClienteTipo").val() == 'E'){
      $("#lbl_ClienteNombres").html("Razón social:[*]");
      $("#lbl_ClienteApellidoPaterno").html("Giro:[*]");
      $("#lbl_ClienteApellidoMaterno").hide();
      $("#pnl_ClienteApellidoMaterno").hide();
    }else{
      $("#lbl_ClienteNombres").html("Nombres:[*]");
      $("#lbl_ClienteApellidoPaterno").html("Apellido Paterno:[*]");
      $("#lbl_ClienteApellidoMaterno").show();
      $("#pnl_ClienteApellidoMaterno").show();
    }
  });

  //ABRIR FORMULARIO CLIENTE
  $("#btn_AbrirModalCliente").click(function (){
    window.vgi_ClienteId = 0;

    $("#lbl_TituloModalCliente").text("Cliente - Nuevo");

    $("#cmb_ClienteTipo").val('');
    $("#txt_ClienteRut").val('');
    $("#cmb_NacionalidadId").val('');
    $("#txt_ClienteNombres").val('');
    $("#txt_ClienteApellidoPaterno").val('');
    $("#txt_ClienteApellidoMaterno").val('');
    $("#cmb_PaisId").val('');
    $("#cmb_PaisId").trigger("change");
    $("#txt_ClienteDireccion").val('');
    $("#txt_ClienteCorreoElectronico").val('');
    $("#txt_ClienteTelefonoFijo").val('');
    $("#txt_ClienteCelular").val('');
  });

  //GUARDAR FORMULARIO CLIENTE
  $("#frm_Cliente").submit(function (e){
    e.preventDefault();
    var vlb_ErrorRut = fn_ValidarRut($("#txt_ClienteRut").val());
    if($("#txt_ClienteRut").val() == ''){
      vlb_ErrorRut = true;
    }

    if(vlb_ErrorRut == true){ //ErrorRut = TRUE, rut validado
      var json_Cliente = {
        vls_ClienteTipo             : $("#cmb_ClienteTipo").val(),
        vli_NacionalidadId          : $("#cmb_NacionalidadId").val(),
        vls_ClienteRut              : fn_LimpiarRut($("#txt_ClienteRut").val()),
        vls_ClienteNombres          : $("#txt_ClienteNombres").val(),
        vls_ClienteApellidoPaterno  : $("#txt_ClienteApellidoPaterno").val(),
        vls_ClienteApellidoMaterno  : $("#txt_ClienteApellidoMaterno").val(),
        vli_PaisId                  : $("#cmb_PaisId").val(),
        vli_RegionId                : $("#cmb_RegionId").val(),
        vli_CiudadId                : $("#cmb_CiudadId").val(),
        vli_ComunaId                : $("#cmb_ComunaId").val(),
        vls_ClienteDireccion        : $("#txt_ClienteDireccion").val(),
        vls_ClienteCorreoElectronico: $("#txt_ClienteCorreoElectronico").val(),
        vls_ClienteTelefonoFijo     : $("#txt_ClienteTelefonoFijo").val(),
        vls_ClienteCelular          : $("#txt_ClienteCelular").val()
      };

      var sql_Respuesta = '';
      if(window.vgi_ClienteId == 0){ //CREANDO CLIENTE
        $.ajax({
  				url     : 'models/Maestros/Clientes/ins_Cliente.php',
  				type    : 'post',
  				data    : {json_Cliente:json_Cliente, fn_Funcion:'InsertarCliente'},
  				async   : false,
  				success : function(res){
            sql_Respuesta = res;
          }
  			});
      }else{ //EDITANDO CLIENTE
        $.ajax({
  				url     : 'models/Maestros/Clientes/upd_Cliente.php',
  				type    : 'post',
  				data    : {json_Cliente:json_Cliente, fn_Funcion:'EditarCliente', pvi_ClienteId:window.vgi_ClienteId},
  				async   : false,
  				success : function(res){
            sql_Respuesta = res;
          }
  			});
      }

      if(sql_Respuesta == 1){
        fn_Alerta("success", "Guardado!", "Cliente guardado correctamente.", "Aceptar");

        $("#mdl_Cliente").modal("toggle");
        fn_CargarTablaClientes();
      }else{
        fn_Alerta("error", "Error!", "No se pudo guardar el cliente por el siguiente error: " + sql_Respuesta, "Aceptar");
      }
    }
  });
});
