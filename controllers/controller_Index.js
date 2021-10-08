/*--------------------------------------------
-------CARGA DASHBOARD DE PROPIEDADES---------
---------------------------------------------*/
function fn_ListarPropiedades(){
  /* TITULO CALENDARIO */
  var vld_FechaActual = new Date();
  var vls_TituloCalendario = '<span style="width: 100%; min-width: 648px">' +
                                //'<a href="" class="abCal-prev-month"><< Mes Anterior</a>' +
                                fn_MesActual(vld_FechaActual.getMonth() + 1) + ' ' + vld_FechaActual.getFullYear() +
                                //'<a href="" class="abCal-next-month">Próximo Mes >></a>' +
                             '</span>';
  $("#pnl_TituloCalendario").html(vls_TituloCalendario);


  /* DIAS DEL MES */
  var vli_CantidadDias = new Date(vld_FechaActual.getFullYear(), (vld_FechaActual.getMonth() + 1), 0).getDate();
  var vls_Dias = '';
  for(i=1;i<=vli_CantidadDias;i++){
    vls_Dias += '<span>' + i + '</span>';
  }
  $("#pnl_Dias").html(vls_Dias);


  /* CARGAMOS PROPIEDADES ACTIVAS */
  $.ajax({
    url     : 'models/Maestros/Propiedades/sel_Propiedad.php',
    type    : 'post',
    data    : {fn_Funcion:'ListadoPropiedades'},
    async   : false,
    success : function(res){
      json_Propiedades = $.parseJSON(res);
    }
  });


  /* CARGA NOMBRE DE PROPIEDADES*/
  var vls_ListadoPropiedades = '<div class="abCal-title" style="height: 64px">' +
                                 '<div class="abCal-note">LISTADO DE CABAÑAS</div>' +
                               '</div>';
  for(i=0;i<json_Propiedades.length;i++){
    vls_ListadoPropiedades+= '<div class="abCal-title">'+json_Propiedades[i]['propiedad_Nombre']+'</div>';
  }
  $("#pnl_ListadoPropiedades").html(vls_ListadoPropiedades);


  /* CARGAMOS RESERVAS REALIZADAS EN EL MES ACTUAL */
  $.ajax({
    url     : 'models/Maestros/Propiedades/sel_Propiedad.php',
    type    : 'post',
    data    : {fn_Funcion:'ListadoReservas'},
    async   : false,
    success : function(res){
      json_Reservas = $.parseJSON(res);
    }
  });


  /* CARGAR DISPONIBILIDAD */
  var vls_Disponibilidad = '';
  for(i=0;i<json_Propiedades.length;i++){
    vls_Disponibilidad+= '<div class="abCal-program abCal-id-1 abCal-link">';
    vli_PropiedadId = json_Propiedades[i]['propiedad_Id'];

    for(j=1;j<=vli_CantidadDias;j++){
      var vli_Comprobar = 0;

      if(json_Reservas){
        for(z=0;z<json_Reservas.length;z++){
          if(vli_PropiedadId == json_Reservas[z]['propiedad_Id'] && j == json_Reservas[z]['reserva_Dia']){
            vli_Comprobar = 1;
          }
        }
      }

      var vld_FechaComprobar = new Date(vld_FechaActual.getFullYear(), vld_FechaActual.getMonth(), j);
      var vls_Clase = '';
      var vli_TempReserva = 0;
      if(vld_FechaComprobar <= vld_FechaActual && vli_Comprobar == 1){
        vls_Clase = 'class = abCalendarOcupada';
        vli_TempReserva = 1;
      }else if(vld_FechaComprobar >= vld_FechaActual && vli_Comprobar == 1){
        vls_Clase = 'class = abCalendarReservadaPagada';
        vli_TempReserva = 1;
      }

      if(json_Propiedades[i]['propiedad_Mantencion'] == 'D' && vli_TempReserva == 1){
        vls_Disponibilidad += '<span ' + vls_Clase + ' onclick="fn_Reservar('+vli_PropiedadId+','+j+','+vld_FechaActual.getMonth()+','+vld_FechaActual.getFullYear()+');" ">&nbsp;</span>';
      }else if(json_Propiedades[i]['propiedad_Mantencion'] == 'D' && vli_TempReserva == 0){
        vls_Disponibilidad += '<span ' + vls_Clase + ' onclick="fn_Reservar('+vli_PropiedadId+','+j+','+vld_FechaActual.getMonth()+','+vld_FechaActual.getFullYear()+');" ">&nbsp;</span>';
      }else if(json_Propiedades[i]['propiedad_Mantencion'] == 'A' && vli_TempReserva == 1){
        vls_Disponibilidad += '<span ' + vls_Clase + ' onclick="fn_Reservar('+vli_PropiedadId+','+j+','+vld_FechaActual.getMonth()+','+vld_FechaActual.getFullYear()+');" ">&nbsp;</span>';
      }else{
        vls_Disponibilidad += '<span class = "abCalendarMantencion">&nbsp;</span>';
      }
    }
    vls_Disponibilidad+= '</div>';
  }
  $("#pnl_Disponibilidad").html(vls_Disponibilidad);
};

/*--------------------------------------------
--------RESERVA/CARGA DATOS-------------------
---------------------------------------------*/
function fn_Reservar(pvi_PropiedadId, pvi_Dia, pvi_Mes, pvi_Anho){
  window.vgs_ReservaDesde = "dashboard";
  var vld_Fecha = pvi_Anho+'-'+(pvi_Mes+1)+'-'+pvi_Dia;

  $.post('models/Modulos/Reservas/sel_Reserva.php', {fn_Funcion:'CargarReserva', pvi_PropiedadId:pvi_PropiedadId, pvi_Dia:vld_Fecha}, function(res){
    var json_Reserva = $.parseJSON(res);

    if(json_Reserva == null){
      fn_LimpiarFormulario();

      var vld_FechaReal = pvi_Anho+'-'+fn_RellenarCeroFecha(pvi_Mes+1)+'-'+fn_RellenarCeroFecha(pvi_Dia);
      $("#dtp_FechaDesde").val(vld_FechaReal);
      $("#dtp_FechaHasta").val(vld_FechaReal);
      $("#cmb_PropiedadId").val(pvi_PropiedadId);
      fn_CargarCapacidadPropiedad(pvi_PropiedadId);

      $("#lbl_TituloModal").text("Crear Reserva");
    }else{
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
    }
  });
  $("#mdl_Reserva").modal();
}

/*--------------------------------------------
--------LISTA CHECKIN Y CHECKOUT PROXIMOS-----
---------------------------------------------*/
function fn_ListarCheckInOut(){
  $("#tbl_CheckIn").find('td').each(function(){
    $("#tbl_CheckIn").find("tr:gt(0)").remove();
  });

  $("#tbl_CheckOut").find('td').each(function(){
    $("#tbl_CheckOut").find("tr:gt(0)").remove();
  });


  $.post('models/Modulos/Reservas/sel_Reserva.php', {fn_Funcion:'CargarCheckInOut'}, function(res){
    var json_CheckInOut = $.parseJSON(res);

    if(json_CheckInOut){
      for(i=0;i<json_CheckInOut.length;i++){
        var vls_Tipo = "";
        if(json_CheckInOut[i]['tipo'] == "CHECKIN"){
          vls_Tipo = "tbl_CheckIn";
        }else{
          vls_Tipo = "tbl_CheckOut";
        }

  			$("#"+vls_Tipo+" tr:last").after('<tr>'+
  				'<td>'+json_CheckInOut[i]['propiedad_Nombre']+'</td>'+
  				'<td>'+json_CheckInOut[i]['reserva_Fecha']+'</td>'+
  				'<td>'+json_CheckInOut[i]['reserva_Hora']+'</td>'+
  			 '</tr>');
  		}
    }
  });
}

$(document).ready(function (){
  fn_ListarPropiedades();
  fn_ListarCheckInOut();

  $("#btn_NuevaReserva").click(function (){
    window.vgs_ReservaDesde = "dashboard";
    fn_LimpiarFormulario();
    $("#mdl_Reserva").modal();
  });
});
