function fn_CargarModulos(pvi_UsuarioId){
  var sql_Modulos = '';
  $.ajax({
    url:  'models/Administracion/Configuraciones/sel_Configuracion.php',
  	type: 'post',
  	data: {fn_Funcion:'CargarModulos'},
  	async: false,
  	success: function(res){
  	   sql_Modulos = $.parseJSON(res);
  	}
  });

  var vls_ModulosSistema = '';
  for(i=0;i<sql_Modulos.length;i++){
    var sql_Permisos = '';
    $.ajax({
      url:  'models/Administracion/Configuraciones/sel_Configuracion.php',
    	type: 'post',
    	data: {fn_Funcion:'CargarPermisos', pvi_ModuloId:sql_Modulos[i]['modulo_Id'], pvi_UsuarioId:pvi_UsuarioId},
    	async: false,
    	success: function(res){
        sql_Permisos = $.parseJSON(res);
      }
    });

    var vls_Permisos = '';
    vls_Permisos += '<label style="display: inline-block; width: 10%;">'+
                      '<input type="checkbox" name="chk_Permisos[]" value="lee_'+sql_Modulos[i]['modulo_Id']+'" '+ (sql_Permisos ? (sql_Permisos['permiso_Leer'] == 'A' ? "checked" : "") : "") +'>'+
                      '<span class="text">Leer</span>'+
                    '</label>';
    vls_Permisos += '<label style="display: inline-block; width: 10%;">'+
                      '<input type="checkbox" name="chk_Permisos[]" value="cre_'+sql_Modulos[i]['modulo_Id']+'" '+ (sql_Permisos ? (sql_Permisos['permiso_Crear'] == 'A' ? "checked" : "") : "") +'>'+
                      '<span class="text">Crear</span>'+
                    '</label>';
    vls_Permisos += '<label style="display: inline-block; width: 10%;">'+
                      '<input type="checkbox" name="chk_Permisos[]" value="act_'+sql_Modulos[i]['modulo_Id']+'" '+ (sql_Permisos ? (sql_Permisos['permiso_Actualizar'] == 'A' ? "checked" : "") : "") +'>'+
                      '<span class="text">Actualizar</span>'+
                    '</label>';
    vls_Permisos += '<label style="display: inline-block; width: 10%;">'+
                      '<input type="checkbox" name="chk_Permisos[]" value="eli_'+sql_Modulos[i]['modulo_Id']+'" '+ (sql_Permisos ? (sql_Permisos['permiso_Eliminar'] == 'A' ? "checked" : "") : "") +'>'+
                      '<span class="text">Eliminar/Anular</span>'+
                    '</label>';
    if(sql_Modulos[i]['modulo_Sigla'] == 'RES'){
      vls_Permisos += '<label style="display: inline-block; width: 10%;">'+
                        '<input type="checkbox" name="chk_Permisos[]" value="pag_'+sql_Modulos[i]['modulo_Id']+'" '+ (sql_Permisos ? (sql_Permisos['permiso_Pagar'] == 'A' ? "checked" : "") : "") +'>'+
                        '<span class="text">Pagar</span>'+
                      '</label>';
    }


    $("#tbl_Permisos tr:last").after(
      '<tr>'+
        '<td style="vertical-align: middle;">'+sql_Modulos[i]['modulo_Nombre']+'</td>'+
        '<td>'+vls_Permisos+'</td>'+
      '</tr>'
    );
  }
}


$(document).ready(function (){
  fn_CargaComboBox('models/Administracion/Usuarios/sel_Usuario.php', 'cmb_UsuarioId', 'CargarUsuarios', '');

  $("#cmb_UsuarioId").change(function(){
    if($("#cmb_UsuarioId").val() != 0){
      $('#tbl_Permisos').find('td').each(function(){
  			$("#tbl_Permisos").find("tr:gt(0)").remove();
  		});

      fn_CargarModulos($("#cmb_UsuarioId").val());
    }
  });

  $('#pnl_Permisos').on('change', 'input[type=checkbox]', function(){
    var vls_Permiso   = $(this).val();
    vls_Permiso = vls_Permiso.split("_");

    var vli_ModuloId  = vls_Permiso[1];
    var vli_UsuarioId = $("#cmb_UsuarioId").val();
    var vls_Check     = ($(this).prop("checked") == true ? 'A':'I');
    var vls_Columna   = '';

    if(vls_Permiso[0] == 'cre'){
      vls_Columna = 'permiso_Crear';
    }else if(vls_Permiso[0] == 'lee'){
      vls_Columna = 'permiso_Leer';
    }else if(vls_Permiso[0] == 'act'){
      vls_Columna = 'permiso_Actualizar';
    }else if(vls_Permiso[0] == 'eli'){
      vls_Columna = 'permiso_Eliminar';
    }else if(vls_Permiso[0] == 'pag'){
      vls_Columna = 'permiso_Pagar';
    }

    if(vli_UsuarioId != ''){
      var json_Permiso = {
				vli_ModuloId  : vli_ModuloId,
        vli_UsuarioId : vli_UsuarioId,
        vls_Check     : vls_Check,
        vls_Columna   : vls_Columna
			};

			$.post('models/Administracion/Configuraciones/upd_Configuracion.php',{fn_Funcion:'EditarPermiso', json_Permiso:json_Permiso}, function(res){
        var vls_Mensaje = '';
        if(vls_Check == 'A'){
          vls_Mensaje = 'activado';
        }else{
          vls_Mensaje = 'desactivado';
        }

        if(res == 1){
          fn_Alerta("success", "Guardado!", "Permiso " + vls_Mensaje + " de forma correcta.", "Aceptar");
				}else{
					fn_Alerta("warning", "Error!", "No se pudo " + vls_Mensaje + " el permiso por el siguiente error: " + res, "Aceptar");
				}
			});
    }else{
      alert("Debe escoger un usuario");
    }
  });
});
