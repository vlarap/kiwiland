/********************************************************
Programador: Mahicol Castillo
Fecha: 22-04-2019
Nota: función general de carga de combo box
********************************************************/
function fn_CargaComboBox(ps_ruta, pi_id, ps_control, ps_defecto, pi_idEspecifico) {
  if (pi_idEspecifico === undefined) {
    var respuesta = '';
    $.ajax({
      url: ps_ruta,
      type: 'post',
      data: { fn_Funcion: ps_control },
      async: false,
      success: function(res) {
        respuesta = res;
      }
    });

    var json_resultado = $.parseJSON(respuesta);
    $('#' + pi_id).empty();

    $('#' + pi_id).append($('<option>', {
      value: '',
      text: '-- Seleccionar --'
    }));

    if (json_resultado == null) {
      $('#' + pi_id).val('');
    } else {
      for (i = 0; i < json_resultado.length; i++) {
        $('#' + pi_id).append($('<option>', {
          value: json_resultado[i]['id'],
          text: json_resultado[i]['nombre']
        }));
      }
    }
  } else {
    var respuesta = '';
    $.ajax({
      url: ps_ruta,
      type: 'post',
      data: { fn_Funcion: ps_control, id: pi_idEspecifico },
      async: false,
      success: function(res) {
        respuesta = res;
      }
    });

    var json_resultado = $.parseJSON(respuesta);
    $('#' + pi_id).empty();

    $('#' + pi_id).append($('<option>', {
      value: '',
      text: '-- Seleccionar --'
    }));

    if (json_resultado == null) {
      $('#' + pi_id).val('');
    } else {
      for (i = 0; i < json_resultado.length; i++) {
        $('#' + pi_id).append($('<option>', {
          value: json_resultado[i]['id'],
          text: json_resultado[i]['nombre']
        }));
      }
    }
  }

  //SELECCIONAMOS UN VALOR POR DEFECTO, SI ESTE ES NUMERICO ES DECIR QUE ESTA DENTRO DE LAS OPCIONES
  //SI ES STRING ES DECIR QUE ES UN VALOR EN EL ARCHIVO DE CONFIGURACION Y SE BUSCA EL VALOR GUARDADO
  if ($.isNumeric(ps_defecto)) {
    if (ps_defecto == -1) {
      $('#' + pi_id)[0].selectedIndex = 1
    } else {
      $('#' + pi_id).val(ps_defecto);
    }
  } /*else if (ps_defecto != '') {
    var respuesta = '';
    $.ajax({
      url: 'php/admin/configuracion/cargarConfiguracion.php',
      type: 'post',
      data: { control: 'buscarValor', valor: ps_defecto },
      async: false,
      success: function(res) {
        respuesta = res;
      }
    });

    $('#' + pi_id).val(respuesta);
  }*/
};

/********************************************************
Programador: Mahicol Castillo
Fecha: 23-12-2019
Nota: reinicia combobox a la opcion -- Seleccionar --
********************************************************/
function fn_ReiniciarComboBox(pvs_Id){
  $('#' + pvs_Id).empty();

  $('#' + pvs_Id).append($('<option>', {
    value: '',
    text: '-- Seleccionar --'
  }));
}


/********************************************************
Programador: Mahicol Castillo
Fecha: 22-04-2019
Nota: función que limpia la tabla para volver a cargar
********************************************************/
function fn_actualizarTabla(ps_nombreTabla) {
  $("#" + ps_nombreTabla).dataTable().fnDestroy();
  fn_cargarTabla(ps_nombreTabla);
};

/********************************************************
Programador: Mahicol Castillo
Fecha: 23-04-2019
Nota: carga tabla segun corresponda
********************************************************/
function fn_cargarTabla(ps_nombreTabla) {
  if (ps_nombreTabla == 'tbl_bancos') {
    fn_cargarBancos();
  }else if (ps_nombreTabla == 'tbl_usuarios') {
    fn_cargarUsuarios();
  }else if (ps_nombreTabla == 'tbl_personal') {
    fn_cargarPersonal();
  }else if (ps_nombreTabla == 'tbl_Partidas') {
    fn_CargarPartidas();
  }else if (ps_nombreTabla == 'tbl_Fuentes') {
    fn_CargarFuentes();
  }
}


/********************************************************
Programador: Mahicol Castillo
Fecha: 23-04-2019
Nota: modal mensaje de alerta segun tipo OK, ERROR
********************************************************/
function fn_Alerta(pvs_Tipo, pvs_Titulo, pvs_Texto, pvs_Boton) {
  swal({
    title             : pvs_Titulo,
    text              : pvs_Texto,
    icon              : pvs_Tipo,
    confirmButtonText : pvs_Boton
  });
}

/********************************************************
Programador: Mahicol Castillo
Fecha: 29-04-2019
Nota: retorna la fecha actual para cargar los datetimepicker
********************************************************/
function fn_fechaActual() {
  var d = new Date();

  var day = d.getDate();
  var month = d.getMonth() + 1;
  var currYear = d.getFullYear();

  var output = (day < 10 ? '0' : '') + day + "-" +
  (month < 10 ? '0' : '') + month + '-' +
  d.getFullYear();
  return output;
};

/**********************************************************
Programador: Mahicol Castillo
Fecha: 06-06-2019
Nota: función que valida rut
***********************************************************/
function fn_ValidarRut(rut) {
  var valor = rut.replace('.', '');
  valor = valor.replace('-', '');
  cuerpo = valor.slice(0, -1);
  dv = valor.slice(-1).toUpperCase();
  rut = cuerpo + '-' + dv
  if (cuerpo.length < 7) { return false; }
  suma = 0;
  multiplo = 2;

  for (i = 1; i <= cuerpo.length; i++) {
    index = multiplo * valor.charAt(cuerpo.length - i);
    suma = suma + index;
    if (multiplo < 7) { multiplo = multiplo + 1; } else { multiplo = 2; }
  }

  dvEsperado = 11 - (suma % 11);
  dv = (dv == 'K') ? 10 : dv;
  dv = (dv == 0) ? 11 : dv;

  if (dvEsperado != dv) {
    fn_Alerta("error", "Error!", "Rut no válido, por favor corregir.", "Aceptar");
    return false;
  } else {
    return true;
  }
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 06-06-2019
Nota: función que limpia el rut sacando puntos y guion
***********************************************************/
function fn_LimpiarRut(rut) {
  var rutLimpio = rut;
  rutLimpio = rutLimpio.replace(/\./g, '');
  rutLimpio = rutLimpio.replace(/\-/g, '');

  return rutLimpio;
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 05-07-2019
Nota: funcion de fecha actual
***********************************************************/
function fn_ActivarFecha(ps_control, pi_fechaActual) {

  if (pi_fechaActual == 1)
  $("#" + ps_control).val(fn_fechaActual());

  $("#" + ps_control).datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    language: 'es-ES'
  });
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 05-07-2019
Nota: funcion de fecha actual
***********************************************************/
function fn_ActivarRangoFecha(pvs_Id){
  $('#'+pvs_Id).daterangepicker({
    format: 'dd-mm-yyyy',
    showDropdowns: true,
    autoUpdateInput: false,
    locale: {
      "daysOfWeek": [
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa",
            "Do"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
      applyLabel: "Aceptar",
      cancelLabel: "Cancelar"
    }
  });
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 05-07-2019
Nota: reasignarItem
***********************************************************/
function fn_ReasignarItem(ps_tabla) {
  var tamTabla = $("#" + ps_tabla + " tr").length;
  for (i = 1; i < tamTabla; i++) {
    $("#" + ps_tabla + " tr:eq(" + i + ")").find("td").eq(0).html(i);
  }
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 05-07-2019
Nota: formatear numero
***********************************************************/
function fn_FormatearNumero(num) {
  if (num == 0) {
    return 0;
  } else {
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
    num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
    num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));

    return (((sign) ? '' : '-') + num);
  }
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 11-07-2019
Nota: sólo números en cuadro de texto
***********************************************************/
function fn_soloNumeros() {
  $('.input-numeros').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 11-07-2019
Nota: sólo correo
***********************************************************/
function fn_soloCorreo(correo) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(correo).toLowerCase());
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 11-07-2019
Nota: retorna solo un valor, ejemplo: porcentaje de impuesto
***********************************************************/
function fn_cargarSoloValor(ps_ruta, ps_control, pi_id) {
  var respuesta = '';
  $.ajax({
    url: 'php/' + ps_ruta,
    type: 'post',
    data: { control: ps_control, id: pi_id },
    async: false,
    success: function(res) {
      respuesta = res;
    }
  });

  return respuesta;
};

/**********************************************************
Programador: Mahicol Castillo
Fecha: 11-07-2019
Nota: actualiza estado especifico
***********************************************************/
function fn_actualizarEstado(ps_ruta, ps_valor, pi_id, ps_mensajeOK, ps_mensajeError) {
  var respuesta = '';
  $.ajax({
    url: 'php/' + ps_ruta,
    type: 'post',
    data: { control: 'actualizarEstado', valor: ps_valor, id: pi_id },
    async: false,
    success: function(res) {
      respuesta = res;
    }
  });

  if (respuesta == 1) {
    fn_alerta(ps_mensajeOK, "OK");
  } else {
    fn_alerta(ps_mensajeError + respuesta, "Error");
  }

  return respuesta;
};

/**********************************************************
Programador: Mahicol Castillo
Fecha: 11-07-2019
Nota: encode id
***********************************************************/
function fn_EncodeId(pi_id) {
  var encode = '';
  $.ajax({
    url: 'core/base64_encode.php',
    type: 'post',
    data: { codigo: pi_id },
    async: false,
    success: function(res) {
      encode = res;
    }
  });

  return encode;
};

function fn_limpiarNumero(pi_numero) {
  return pi_numero.replace(/\./g, '');
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 20-10-2019
Nota: solo numeros
***********************************************************/
document.addEventListener('keypress', function(e) {
  if($("td").hasClass('soloNumeros')) {
    var x = event.charCode || event.keyCode;
    if (isNaN(String.fromCharCode(e.which)) && x!=46) e.preventDefault();
  }

  if($("input").hasClass('soloNumeros')) {
    var x = event.charCode || event.keyCode;
    if (isNaN(String.fromCharCode(e.which)) && x!=46) e.preventDefault();
  }
}, false);

/**********************************************************
Programador: Mahicol Castillo
Fecha: 27-02-2020
Nota: Devuelve mes actual segun el numero enviado
***********************************************************/
function fn_MesActual(vli_MesActual){
  if(vli_MesActual == 1){
    return "Enero";
  }else if(vli_MesActual == 2){
    return "Febrero";
  }else if(vli_MesActual == 3){
    return "Marzo";
  }else if(vli_MesActual == 4){
    return "Abril";
  }else if(vli_MesActual == 5){
    return "Mayo";
  }else if(vli_MesActual == 6){
    return "Junio";
  }else if(vli_MesActual == 7){
    return "Julio";
  }else if(vli_MesActual == 8){
    return "Agosto";
  }else if(vli_MesActual == 9){
    return "Septiembre";
  }else if(vli_MesActual == 10){
    return "Octubre";
  }else if(vli_MesActual == 11){
    return "Noviembre";
  }else if(vli_MesActual == 12){
    return "Diciembre";
  }
}

/**********************************************************
Programador: Mahicol Castillo
Fecha: 09-03-2020
Nota: Devuelve la fecha con un cero adelante si es necesario
***********************************************************/
function fn_RellenarCeroFecha(pvi_Valor){
  if(pvi_Valor < 10){
    return "0" + pvi_Valor;
  }else{
    return pvi_Valor;
  }
}
