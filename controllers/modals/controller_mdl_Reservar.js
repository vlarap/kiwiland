/*--------------------------------------------
--------LIMPIAR FORMULARIO DE RESERVA---------
---------------------------------------------*/
function fn_LimpiarFormulario(){
  $("#btn_EliminarReserva").hide();
  $("#btn_ValorarCliente").hide();

  $("#txt_ReservaId").val('');
  $("#txt_ReservaFechaCrea").val('');
  $("#txt_ReservaEstado").val('');

  $("#rb_Chileno").prop('checked', false);
  $("#rb_Extranjero").prop('checked', false);

  $("#cmb_ClienteIdMDL").val('');
  $("#txt_ClienteRut").val('');
  $("#txt_ClienteNombres").val('');
  $("#txt_ClienteApellidoPaterno").val('');
  $("#txt_ClienteApellidoMaterno").val('');

  $("#cmb_PaisId").val('');
  fn_ReiniciarComboBox('cmb_RegionId');
  fn_ReiniciarComboBox('cmb_CiudadId');
  fn_ReiniciarComboBox('cmb_ComunaId');

  $("#txt_ClienteDireccion").val('');
  $("#txt_ClienteCorreoElectronico").val('');
  $("#txt_ClienteTelefonoFijo").val('');
  $("#txt_ClienteCelular").val('');

  $("#cmb_PropiedadId").val('');

  $("#dtp_FechaDesde").val('');
  $("#dtp_HoraLlegada").val('14:00');
  $("#dtp_FechaHasta").val('');
  $("#dtp_HoraSalida").val('12:00');

  $("#dtp_FechaDesde").prop("disabled", false);
  $("#dtp_FechaHasta").prop("disabled", false);
  $("#cmb_PropiedadId").prop("disabled", false);

  $("#cmb_ReservaAdultos").val(1);
  $("#cmb_ReservaNinos").val(0);

  $("#cmb_OrigenId").val('');
}

function fn_EditarReserva(pvi_ReservaId){
  window.vgs_ReservaDesde = "reserva";

  $.post('models/Modulos/Reservas/sel_Reserva.php', {fn_Funcion:'CargarReserva', pvi_ReservaId:pvi_ReservaId}, function(res){
    var json_Reserva = $.parseJSON(res);

    $("#btn_EliminarReserva").show();
    $("#btn_ValorarCliente").show();

    //DESHABILITAMOS CAMPOS NO EDITABLES
    $("#cmb_PropiedadId").prop("disabled", true);
    $("#dtp_FechaDesde").prop("disabled", true);
    $("#dtp_FechaHasta").prop("disabled", true);


    $("#lbl_TituloModal").text("Editar Reserva");

    $("#txt_ReservaId").val(json_Reserva['reserva_Id']);
    window.vgi_ReservaId = json_Reserva['reserva_Id'];
    $("#txt_ReservaFechaCrea").val(json_Reserva['reserva_FechaCrea']);
    $("#txt_ReservaEstado").val(json_Reserva['reserva_Estado']);

    if(json_Reserva['cliente_Tipo'] == 'P'){
      $("#rb_Persona").prop('checked', true);
      $("#rb_Empresa").prop('checked', false);
    }else{
      $("#rb_Persona").prop('checked', false);
      $("#rb_Empresa").prop('checked', true);
    }
    $("#rb_Persona").trigger('change');
    $("#rb_Empresa").trigger('change');

    if(json_Reserva['pais_Sigla'] == 'CHI'){
      $("#rb_Chileno").prop('checked', true);
      $("#rb_Extranjero").prop('checked', false);
    }else{
      $("#rb_Chileno").prop('checked', false);
      $("#rb_Extranjero").prop('checked', true);
    }

    fn_CargaComboBox('models/Maestros/Clientes/sel_Cliente.php', 'cmb_ClienteIdMDL', 'CargarClientes', json_Reserva['cliente_Id']); //CARGA CLIENTES
    window.vgi_ClienteId = json_Reserva['cliente_Id'];
    $("#txt_ClienteRut").val(json_Reserva['cliente_Rut']);
    $("#txt_ClienteNombres").val(json_Reserva['cliente_Nombres']);
    $("#txt_ClienteApellidoPaterno").val(json_Reserva['cliente_ApellidoPaterno']);
    $("#txt_ClienteApellidoMaterno").val(json_Reserva['cliente_ApellidoMaterno']);

    if(json_Reserva['pais_Id']){
      fn_CargaComboBox('models/Mantenedores/Paises/sel_Pais.php', 'cmb_PaisId', 'CargarPaises', json_Reserva['pais_Id']); //CARGA PAISES
    }
    if(json_Reserva['region_Id']){
      fn_CargaComboBox('models/Mantenedores/Regiones/sel_Region.php', 'cmb_RegionId', 'CargarRegiones', json_Reserva['region_Id'], json_Reserva['pais_Id']); //CARGA REGIONES
    }
    if(json_Reserva['ciudad_Id']){
      fn_CargaComboBox('models/Mantenedores/Ciudades/sel_Ciudad.php', 'cmb_CiudadId', 'CargarCiudadesXRegion', json_Reserva['ciudad_Id'], json_Reserva['region_Id']); //CARGA CIUDADES
    }
    if(json_Reserva['comuna_Id']){
      fn_CargaComboBox('models/Mantenedores/Comunas/sel_Comuna.php', 'cmb_ComunaId', 'CargarComunasXCiudad', json_Reserva['comuna_Id'], json_Reserva['ciudad_Id']); //CARGA COMUNAS
    }

    $("#txt_ClienteDireccion").val(json_Reserva['cliente_Direccion']);
    $("#txt_ClienteCorreoElectronico").val(json_Reserva['cliente_CorreoElectronico']);
    $("#txt_ClienteTelefonoFijo").val(json_Reserva['cliente_TelefonoFijo']);
    $("#txt_ClienteCelular").val(json_Reserva['cliente_Celular']);

    fn_CargaComboBox('models/Maestros/Propiedades/sel_Propiedad.php', 'cmb_PropiedadId', 'CargarPropiedades', json_Reserva['propiedad_Id']); //CARGAR PROPIEDADES
    fn_CargarCapacidadPropiedad(json_Reserva['propiedad_Id']);

    $("#dtp_FechaDesde").val(json_Reserva['reserva_FechaDesde']);
    $("#dtp_HoraLlegada").val(json_Reserva['reserva_HoraLlegada']);
    $("#dtp_FechaHasta").val(json_Reserva['reserva_FechaHasta']);
    $("#dtp_HoraSalida").val(json_Reserva['reserva_HoraSalida']);

    $("#cmb_ReservaAdultos").val(json_Reserva['reserva_Adultos']);
    $("#cmb_ReservaNinos").val(json_Reserva['reserva_Ninos']);

    fn_CargaComboBox('models/Mantenedores/Origenes/sel_Origen.php', 'cmb_OrigenId', 'CargarOrigenes', json_Reserva['origen_Id']); //CARGAR ORIGENES
    $("#mdl_Reserva").modal();
  });
}

function fn_CargarCapacidadPropiedad(pvi_PropiedadId){
  $.ajax({
    url     : 'models/Maestros/Propiedades/sel_Propiedad.php',
    type    : 'post',
    data    : {fn_Funcion:'CargarPropiedadCapacidad', pvi_PropiedadId:pvi_PropiedadId},
    async   : false,
    success : function(res){
      window.vgi_PropiedadCapacidad = res;
    }
  });
}

function fn_ConfirmarCapacidad(){
  var vli_Adultos = $("#cmb_ReservaAdultos").val();
  var vli_Ninos   = $("#cmb_ReservaNinos").val();
  var vli_Total   = parseInt(vli_Adultos) + parseInt(vli_Ninos);

  if(vli_Total > window.vgi_PropiedadCapacidad){
    fn_Alerta("error", "Error!", "Cantidad de adultos/niños supera la capacidad máxima de la cabaña ("+window.vgi_PropiedadCapacidad+").", "Aceptar");
    $("#cmb_ReservaAdultos").val(1);
    $("#cmb_ReservaNinos").val(0);
  }
}

$(document).ready(function (){
  //LATECHECKOUT
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

  //SELECTOR DE TIPO DE CLIENTE
  $("#rb_Persona").change(function(){
    if($("#rb_Persona").prop('checked')){
      $("#rb_Empresa").prop('checked', false);
      $("#lbl_ClienteNombres").html("Nombres:[*]");
      $("#lbl_ClienteApellidoPaterno").html("Ap. Paterno:[*]");
      $("#lbl_ClienteApellidoMaterno").show();
      $("#pnl_ClienteApellidoMaterno").show();
    }
  });

  $("#rb_Empresa").change(function(){
    if($("#rb_Empresa").prop('checked')){
      $("#rb_Persona").prop('checked', false);
      $("#lbl_ClienteNombres").html("Razón social:[*]");
      $("#lbl_ClienteApellidoPaterno").html("Giro:[*]");
      $("#lbl_ClienteApellidoMaterno").hide();
      $("#pnl_ClienteApellidoMaterno").hide();
    }
  });

  //FIX RADIOBUTTON
  $("#rb_Chileno").change(function (){
    if($("#rb_Chileno").prop('checked')){
      $("#rb_Extranjero").prop('checked', false);
    }
  });

  $("#rb_Extranjero").change(function (){
    if($("#rb_Extranjero").prop('checked')){
      $("#rb_Chileno").prop('checked', false);
    }
  });

  $("#btn_EliminarReserva").hide();
  $("#btn_ValorarCliente").hide();
  window.vgi_PropiedadCapacidad = 0;
  fn_CargaComboBox('models/Maestros/Clientes/sel_Cliente.php', 'cmb_ClienteIdMDL', 'CargarClientes', ''); //CARGA CLIENTES
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

  fn_CargaComboBox('models/Maestros/Propiedades/sel_Propiedad.php', 'cmb_PropiedadId', 'CargarPropiedades', ''); //CARGAR PROPIEDADES
  fn_CargaComboBox('models/Mantenedores/Origenes/sel_Origen.php', 'cmb_OrigenId', 'CargarOrigenes', ''); //CARGAR ORIGENES

  //CARGAMOS DATOS DEL CLIENTES SI EXISTE EN LA BASE DE DATOS
  $("#cmb_ClienteIdMDL").change(function(){
    if($("#cmb_ClienteIdMDL").val() != ''){
      $.post('models/Maestros/Clientes/sel_Cliente.php', {fn_Funcion:'EditarCliente', pvi_ClienteId:$("#cmb_ClienteIdMDL").val()}, function(res){
        var json_Cliente = $.parseJSON(res);

        $("#txt_ClienteRut").val(json_Cliente['cliente_Rut']);
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
      });
    }
  });

  //VALORIZACIÓN CLIENTE
  $("#btn_ValorarCliente").click(function (){
    $("#mdl_Reserva").modal('toggle');
    fn_BuscarValoracion();
    $("#mdl_ValoracionCliente").modal();
  });

  /*NOTIFICAR LATECHECKOUT*/
  $("#dtp_HoraSalida").change(function (){
    if($("#dtp_HoraSalida").val() == window.vgd_LateCheckOut){
      fn_Alerta("warning", "Late CheckOut activado!", "Tiene un cobro adicional de " + window.vgi_LCOPrecio, "Aceptar");
    }
  });

  /*CARGAR CANTIDAD DE HUESPEDES*/
  $("#cmb_PropiedadId").change(function (){
    if($("#cmb_PropiedadId").val() != ''){
      fn_CargarCapacidadPropiedad($("#cmb_PropiedadId").val());
    }else{
      window.vgi_PropiedadCapacidad = 0;
    }
  });

  $("#cmb_ReservaAdultos").change(function (){
    fn_ConfirmarCapacidad();
  });

  $("#cmb_ReservaNinos").change(function (){
    fn_ConfirmarCapacidad();
  });

  //GUARDAR RESERVA
  $("#frm_Reserva").submit(function (e){
    e.preventDefault();

    var vls_Nacionalidad = '';
    if($("#rb_Chileno").prop('checked')){
      vls_Nacionalidad = 'CHI';
    }
    if($("#rb_Extranjero").prop('checked')){
      vls_Nacionalidad = 'EXT';
    }
    if(vls_Nacionalidad == ''){
      vls_Nacionalidad = 'CHI';
    }

    var vls_ClienteTipo = '';
    if($("#rb_Persona").prop('checked')){
      vls_ClienteTipo = 'P';
    }
    if($("#rb_Empresa").prop('checked')){
      vls_ClienteTipo = 'E';
    }
    if(vls_ClienteTipo == ''){
      vls_ClienteTipo = 'P';
    }

    var json_Reserva = {
      vls_ClienteTipo             : vls_ClienteTipo,
      vli_ClienteId               : $("#cmb_ClienteIdMDL").val(),
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
      vli_PropiedadId             : $("#cmb_PropiedadId").val(),
      vld_ReservaFechaDesde       : $("#dtp_FechaDesde").val(),
      vld_ReservaFechaHasta       : $("#dtp_FechaHasta").val(),
      vld_ReservaHoraDesde        : $("#dtp_HoraLlegada").val(),
      vld_ReservaHoraHasta        : $("#dtp_HoraSalida").val(),
      vli_ReservaAdultos          : $("#cmb_ReservaAdultos").val(),
      vli_ReservaNinos            : $("#cmb_ReservaNinos").val(),
      vli_OrigenId                : $("#cmb_OrigenId").val()
    };

    var sql_Respuesta = '';
    if($("#txt_ReservaId").val() == ''){ //CREANDO RESERVA
      $.ajax({
        url     : 'models/Modulos/Reservas/ins_Reserva.php',
        type    : 'post',
        data    : {json_Reserva:json_Reserva, fn_Funcion:'InsertarReserva'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO RESERVA
      $.ajax({
        url     : 'models/Modulos/Reservas/upd_Reserva.php',
        type    : 'post',
        data    : {json_Reserva:json_Reserva, fn_Funcion:'EditarReserva', pvi_ReservaId:$("#txt_ReservaId").val()},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Reserva guardada correctamente.", "Aceptar");

      if(window.vgs_ReservaDesde == "dashboard"){
        fn_ListarPropiedades();
        fn_ListarCheckInOut();
      }else{
        fn_CargarReservas();
      }

      $("#mdl_Reserva").modal("toggle");
    }else if(sql_Respuesta == -1){
      fn_Alerta("error", "Error!", "La reserva no puede cursarse dado que se superpone con otras fechas reservadas.", "Aceptar");
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar la reserva por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });

  //ELIMINAR RESERVA
  $("#btn_EliminarReserva").click(function(){
    var sql_Respuesta = '';
    $.ajax({
      url     : 'models/Modulos/Reservas/del_Reserva.php',
      type    : 'post',
      data    : {fn_Funcion:'EliminarReserva', pvi_ReservaId:$("#txt_ReservaId").val()},
      async   : false,
      success : function(res){
        sql_Respuesta = res;
      }
    });

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Eliminada!", "Reserva eliminada correctamente.", "Aceptar");

      if(window.vgs_ReservaDesde == "dashboard"){
        fn_ListarPropiedades();
        fn_ListarCheckInOut();
      }else{
        fn_CargarReservas();
      }

      $("#mdl_Reserva").modal("toggle");
    }else{
      fn_Alerta("error", "Error!", "No se pudo eliminar la reserva por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
