function fn_CargarConfiguracion(){
	$.post('models/Administracion/Configuraciones/sel_Configuracion.php', {fn_Funcion:'CargarConfiguracion'}, function(res){
		var json_Configuracion = $.parseJSON(res);

		$("#txt_CFGCheckIn").val(json_Configuracion['cfg_CheckIn']);
		$("#txt_CFGCheckOut").val(json_Configuracion['cfg_CheckOut']);
		$("#txt_CFGLateCheckOut").val(json_Configuracion['cfg_LateCheckOut']);
		$("#txt_CFGLCOPrecio").val(json_Configuracion['cfg_LCOPrecio']);
	});
};

$(document).ready(function(){
	fn_CargarConfiguracion();

	$("#frm_Configuracion").submit(function (e){
    e.preventDefault();

    var json_Configuracion = {
    	vld_CFGCheckIn			: $("#txt_CFGCheckIn").val(),
      vld_CFGCheckOut			: $("#txt_CFGCheckOut").val(),
      vld_CFGLateCheckOut	: $("#txt_CFGLateCheckOut").val(),
      vli_CFGLCOPrecio		: $("#txt_CFGLCOPrecio").val()
    };

    var sql_Respuesta = '';
    $.ajax({
    	url     : 'models/Administracion/Configuraciones/upd_Configuracion.php',
    	type    : 'post',
    	data    : {json_Configuracion:json_Configuracion, fn_Funcion:'EditarConfiguracion'},
    	async   : false,
    	success : function(res){
        sql_Respuesta = res;
      }
    });

    if($.isNumeric(sql_Respuesta)){
      fn_Alerta("success", "Guardado!", "Configuración actualizada correctamente.", "Aceptar");
    }else{
      fn_Alerta("warning", "Error!", "No se pudo guardar la configuración por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
