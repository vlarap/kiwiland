function fn_BuscarValoracion(){
  $.post('models/Maestros/Clientes/sel_Cliente.php', {fn_Funcion:'BuscarValoracion', pvi_ClienteId:window.vgi_ClienteId, pvi_ReservaId:window.vgi_ReservaId}, function(res){
		var json_Valoracion = $.parseJSON(res);

    if(json_Valoracion){
      window.vgi_ValoracionId = json_Valoracion['valoracion_Id'];
      $("#cmb_ValoracionPuntaje").val(json_Valoracion['valoracion_Puntaje']);
      $("#txt_ValoracionObservacion").val(json_Valoracion['valoracion_Observacion']);
    }else{
      window.vgi_ValoracionId = 0;
      $("#cmb_ValoracionPuntaje").val('1');
      $("#txt_ValoracionObservacion").val('');
    }
  });
}

$(document).ready(function (){
  window.vgi_ValoracionId = 0;

  //GUARDAR RESERVA
  $("#frm_ValoracionCliente").submit(function (e){
    e.preventDefault();

    var json_Valoracion = {
      vli_ReservaId             : window.vgi_ReservaId,
      vli_ClienteId             : window.vgi_ClienteId,
      vli_ValoracionPuntaje     : $("#cmb_ValoracionPuntaje").val(),
      vls_ValoracionObservacion : $("#txt_ValoracionObservacion").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_ValoracionId == 0){ //CREANDO VALORACIÓN
      $.ajax({
        url     : 'models/Maestros/Clientes/ins_Cliente.php',
        type    : 'post',
        data    : {json_Valoracion:json_Valoracion, fn_Funcion:'InsertarValoracion'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO VALORACIÓN
      $.ajax({
        url     : 'models/Maestros/Clientes/upd_Cliente.php',
        type    : 'post',
        data    : {json_Valoracion:json_Valoracion, fn_Funcion:'EditarValoracion', pvi_ValoracionId:window.vgi_ValoracionId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Valoración a cliente guardada correctamente.", "Aceptar");
      $("#mdl_ValoracionCliente").modal("toggle");
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar la valoración a cliente por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
