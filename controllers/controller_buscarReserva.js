function fn_BuscarDisponibilidad(){
  var json_Disponibilidad = {
    pvd_FechaDesde  : $("#dtp_FechaDesde").val(),
    pvd_FechaHasta  : $("#dtp_FechaHasta").val(),
    pvi_Adultos     : $("#txt_Adultos").val(),
    pvi_Ninos       : $("#txt_Ninos").val()
  };

  $.ajax({
    url     : 'models/Modulos/Reservas/sel_Reserva.php',
    type    : 'post',
    data    : {json_Disponibilidad:json_Disponibilidad, fn_Funcion:'BuscarDisponibilidad'},
    async   : false,
    success : function(res){
      json_Propiedades = $.parseJSON(res);
    }
  });

  var vls_Contenido = '';
  for(i=0;i<json_Propiedades.length;i++){
    vls_Contenido += "<div class='row'>";
    vls_Contenido +=  "<div class='row cuadroDisponibilidad'>";
    vls_Contenido +=    "<div class='col-md-5'>";

    if(json_Propiedades[i]['propiedad_Imagen']){
      vls_Contenido +=    "<img class='img-responsive' src='img/propiedades/"+json_Propiedades[i]['propiedad_Id']+"/"+json_Propiedades[i]['propiedad_Imagen']+"'>";
    }

    vls_Contenido +=    "</div>";
    vls_Contenido +=    "<div class='col-md-7'>";
    vls_Contenido +=      "<h4>"+json_Propiedades[i]['propiedad_Nombre']+"</h4>";
    vls_Contenido +=      "<hr class='wide' style='width:60%; margin-left:0px; margin-top:5px;'>";
    vls_Contenido +=      "<p class='text-justify'>"+json_Propiedades[i]['propiedad_Descripcion']+"</p><br/>";
    vls_Contenido +=      "<h5 style='margin-bottom:1px;'>Servicios</h5>";
    vls_Contenido +=      "<hr class='wide' style='width:60%; margin-left:0px; margin-top:5px;' style='width:60%;'>";

    $.ajax({
      url     : 'models/Modulos/Reservas/sel_Reserva.php',
      type    : 'post',
      data    : {pvi_PropiedadId:json_Propiedades[i]['propiedad_Id'], fn_Funcion:'CargarServicios'},
      async   : false,
      success : function(res){
        json_Servicios = $.parseJSON(res);
      }
    });
    if(json_Servicios){
      for(j=0;j<json_Servicios.length;j++){
        vls_Contenido +=  "<span style='display:block;'>- "+json_Servicios[j]['servicio_Nombre']+"</span>";
      }
    }

    vls_Contenido +=      "<br/><div>"+json_Propiedades[i]['propiedad_Boton']+"</div>";
    vls_Contenido +=    "</div>";
    vls_Contenido +=  "</div>";
    vls_Contenido += "</div>";
    vls_Contenido += "<br/><br/>";
  }

  vls_Contenido += "</div>"
  $("#pnl_Resultados").html(vls_Contenido);
}

function fn_Reservar(pvi_PropiedadId, pvs_PropiedadNombre){
  var pvd_FechaDesde      = $("#dtp_FechaDesde").val();
  var pvd_FechaHasta      = $("#dtp_FechaHasta").val();
  var pvi_Adultos         = $("#txt_Adultos").val();
  var pvi_Ninos           = $("#txt_Ninos").val();
  window.vgs_FechaDesde   = pvd_FechaDesde;
  window.vgs_FechaHasta   = pvd_FechaHasta;
  window.vgi_Adultos      = pvi_Adultos;
  window.vgi_Ninos        = pvi_Ninos;
  window.pvs_PropiedadId  = pvi_PropiedadId;

  $("#lbl_PropiedadNombre").text(pvs_PropiedadNombre);
  $("#dtp_FechaDesdeMDL").val(pvd_FechaDesde);
  $("#dtp_FechaHastaMDL").val(pvd_FechaHasta);
  $("#txt_AdultosMDL").val(pvi_Adultos);
  $("#txt_NinosMDL").val(pvi_Ninos);

  $("#mdl_Reserva").modal();
}



$(document).ready(function (){
  if(vli_DesdeWeb == 1){
    fn_BuscarDisponibilidad();
  }

  $("#frm_Disponibilidad").submit(function (e){
    e.preventDefault();
    fn_BuscarDisponibilidad();
  });

  $.ajax({
    url     : 'models/Administracion/Configuraciones/sel_Configuracion.php',
    type    : 'post',
    data    : {fn_Funcion:'CargarConfiguracion'},
    async   : false,
    success : function(res){
      json_Configuracion = $.parseJSON(res);
      window.vgd_LateCheckOut = json_Configuracion['cfg_LateCheckOut'];
      window.vgi_LCOPrecio    = json_Configuracion['cfg_LCOPrecioFormat'];
    }
  });

  fn_CargaComboBox('models/Mantenedores/Paises/sel_Pais.php', 'cmb_PaisId', 'CargarPaises', ''); //CARGA PAISES
  $("#cmb_PaisId").change(function(){
    if($("#cmb_PaisId").val() != ''){
      fn_CargaComboBox('models/Mantenedores/Regiones/sel_Region.php', 'cmb_RegionId', 'CargarRegiones', '', $("#cmb_PaisId").val()); //CARGA REGIONES
    }else{
      fn_ReiniciarComboBox('cmb_RegionId');
      fn_ReiniciarComboBox('cmb_CiudadId');
      fn_ReiniciarComboBox('cmb_ComunaId');
    }
  });

  $("#cmb_RegionId").change(function(){
    if($("#cmb_RegionId").val() != ''){
      fn_CargaComboBox('models/Mantenedores/Ciudades/sel_Ciudad.php', 'cmb_CiudadId', 'CargarCiudadesXRegion', '', $("#cmb_RegionId").val()); //CARGA CIUDADES
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

  $("#txt_ClienteRut").blur(function (){
    $.post('models/Maestros/Clientes/sel_Cliente.php', {fn_Funcion:'CargarClienteXRut', pvs_ClienteRut:$("#txt_ClienteRut").val()}, function(res){
      var json_Cliente = $.parseJSON(res);

      if(json_Cliente){
        window.vgi_ClienteId = json_Cliente['cliente_Id'];
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
      }else{
        window.vgi_ClienteId = '';
      }
    });
  });

  $("#dtp_HoraSalida").change(function (){
    if($("#dtp_HoraSalida").val() == window.vgd_LateCheckOut){
      fn_Alerta("warning", "Late CheckOut activado!", "Tiene un cobro adicional de " + window.vgi_LCOPrecio, "Aceptar");
    }
  });

  $("#frm_Reserva").submit(function (e){
    e.preventDefault();

    var vls_Nacionalidad = '';
    if($("#rb_Chileno").prop('checked')){
      vls_Nacionalidad = 'CHI';
    }
    if($("#rb_Extranjero").prop('checked')){
      vls_Nacionalidad = 'EXT';
    }

    var json_Reserva = {
      vli_ClienteId               : window.vgi_ClienteId,
      vls_Nacionalidad            : vls_Nacionalidad,
      vls_ClienteRut              : $("#txt_ClienteRut").val(),
      vls_ClienteNombres          : $("#txt_ClienteNombres").val(),
      vls_ClienteApellidoPaterno  : $("#txt_ClienteApellidoPaterno").val(),
      vls_ClienteApellidoMaterno  : $("#txt_ClienteApellidoMaterno").val(),
      vli_PaisId                  : $("#cmb_PaisId").val(),
      vli_RegionId                : $("#cmb_RegionId").val(),
      vli_CiudadId                : $("#cmb_CiudadId").val(),
      vli_ComunaId                : $("#cmb_ComunaId").val(),
      vls_ClienteDireccion        : $("#txt_ClienteDireccion").val(),
      vls_ClienteCorreo           : $("#txt_ClienteCorreoElectronico").val(),
      vls_ClienteTelefonoFijo     : $("#txt_ClienteTelefonoFijo").val(),
      vls_ClienteCelular          : $("#txt_ClienteCelular").val(),
      vli_PropiedadId             : window.pvs_PropiedadId,
      vld_ReservaFechaDesde       : window.vgs_FechaDesde,
      vld_ReservaFechaHasta       : window.vgs_FechaHasta,
      vld_ReservaHoraDesde        : $("#dtp_HoraLlegada").val(),
      vld_ReservaHoraHasta        : $("#dtp_HoraSalida").val(),
      vli_ReservaAdultos          : window.vgi_Adultos,
      vli_ReservaNinos            : window.vgi_Ninos,
      vli_OrigenId                : "Web"
    };

    var sql_Respuesta = '';
    $.ajax({
      url     : 'models/Modulos/Reservas/ins_Reserva.php',
      type    : 'post',
      data    : {json_Reserva:json_Reserva, fn_Funcion:'InsertarReserva'},
      async   : false,
      success : function(res){
        sql_Respuesta = res;
      }
    });

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Reserva realizada correctamente, llegará un correo con las indicaciones para realizar el pago de tu reserva.", "Aceptar");
      $("#mdl_Reserva").modal("toggle");

      $("#pnl_Resultados").html('');
      $("#dtp_FechaDesde").val('');
      $("#dtp_FechaHasta").val('');
      $("#txt_Adultos").val(1);
      $("#txt_Ninos").val(0);
    }else if(sql_Respuesta == -1){
      fn_Alerta("error", "Error!", "La reserva no puede cursarse dado que se superpone con otras fechas reservadas.", "Aceptar");
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar la reserva por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
