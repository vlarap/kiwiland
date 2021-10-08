function fn_IniciarSesion(){
	$.post('includes/login.php', {pvs_UsuarioNombre:$("#txt_UsuarioNombre").val(), pvs_UsuarioContrasena:$("#txt_UsuarioContrasena").val()}, function(res){
		if(res == 1){
			location.href="index.php";
		}else{
			fn_Alerta("error", "Error!", "No se pudo iniciar sesión por el siguiente error: " + res, "Aceptar");
		}
	});
};

$(document).ready(function(){
	$("#txt_UsuarioNombre").focus();

  $("#frm_Login").submit(function (e){
    e.preventDefault();

    fn_IniciarSesion();
  });
});
