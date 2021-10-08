<?php
  session_start();

  //CFG GLOBLAL
  require_once 'includes/config.php';

  $vld_FechaDesde = '';
  $vld_FechaHasta = '';
  $vli_Adultos    = 1;
  $vli_Ninos      = 0;
  if(isset($_POST['check_in_date']) && isset($_POST['check_out_date']) && isset($_POST['adults_capacity']) && isset($_POST['max_child'])){
    $vld_FechaDesde = $_POST['check_in_date'];
    $vld_FechaHasta = $_POST['check_out_date'];
    $vli_Adultos    = $_POST['adults_capacity'];
    $vli_Ninos      = $_POST['max_child'];
    echo '<script> var vli_DesdeWeb = 1;</script>';
  }else{
    echo '<script> var vli_DesdeWeb = 0;</script>';
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" href="assets/favicon.ico">

    <title>Kiwiland | Qmo Sistemas</title>

    <!--Basic Styles-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link id="bootstrap-rtl-link" href="" rel="stylesheet" />
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/weather-icons.min.css" rel="stylesheet" />

    <!--Fonts-->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!--Beyond styles-->
    <link id="beyond-link" href="assets/css/beyond.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.css" rel="stylesheet" />
    <link href="assets/css/demo.min.css" rel="stylesheet" />
    <link href="assets/css/typicons.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css/calendariodisponibilidad.css" rel="stylesheet" />
    <link id="skin-link" href="" rel="stylesheet" type="text/css" />
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet" />

    <!-- Select2 -->
    <link href="assets/css/select2/select2-bootstrap4.min.css" rel="stylesheet">
    <link href="assets/css/select2/select2.min.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="assets/css/sweetalert/sweetalert.css" rel="stylesheet">

    <!--Skin Script: Place this script in head to load scripts for skins and rtl support-->
    <script src="assets/js/skins.min.js"></script>
  </head>

  <body>
    <div class="loading-container">
      <div class="loader"></div>
    </div>
    <div class="navbar">
      <div class="navbar-inner">
        <div class="navbar-container">
          <div class="navbar-header pull-left">
            <a href="#" class="navbar-brand">
              <small>
                <img src="assets/img/logo.png" alt="" />
              </small>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="main-container container-fluid">
      <div class="page-container">
        <div id="page-wrapper" class="page-body">
          <h2 class="text-center">Haz tu reserva</h2>
          <div class="row">
            <div class="col-md-offset-4 col-md-4">
              <hr class="wide" style="border-color:#87c001;">
            </div>
          </div>
          <form id="frm_Disponibilidad">
            <div class="row form-group">
              <div class="col-md-offset-2 col-md-2">
                <label for="fechaDesde">Llegada[*]:</label>
                <input type="date" id="dtp_FechaDesde" class="form-control input-sm" value="<?php echo $vld_FechaDesde; ?>" required>
              </div>
              <div class="col-md-2">
                <label for="fechaSalida">Salida[*]:</label>
                <input type="date" id="dtp_FechaHasta" class="form-control input-sm" value="<?php echo $vld_FechaHasta; ?>" required>
              </div>
              <div class="col-md-2">
                <label for="adultos">Adultos[*]:</label>
                <input type="number" id="txt_Adultos" class="form-control input-sm" value="<?php echo $vli_Adultos; ?>" min="1" max="9">
              </div>
              <div class="col-md-2">
                <label for="adultos">Niños[*]:</label>
                <input type="number" id="txt_Ninos" class="form-control input-sm" value="<?php echo $vli_Ninos; ?>" min="0" max="8">
              </div>
            </div>
            <div class="row">
              <div class="col-md-offset-5 col-md-2">
                <button type="submit" id="btn_Buscar" class="btn btn-success btn-block"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </form>
          <hr class="wide">

          <div id="pnl_Resultados" class="container"></div>
        </div>
      </div>
    </div>
  </body>

  <div id="mdl_Reserva" class="modal fade" aria-hidden="true" data-backdrop="static" tabindex="-1" data-backdrop="false">
  	<div class="modal-dialog modal-lg">
  		<div class="modal-content">
  			<form id="frm_Reserva">
  				<div class="modal-header">
  					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  					<h5 class="modal-title">Reservar - <span id="lbl_PropiedadNombre"></span> </h5>
  				</div>
  				<div class="modal-body">
  					<div class="row">
  						<div class="col-xs-12 col-md-12">
  							<div class="form-subtitulo">DATOS PERSONALES</div>
  							<div class="row">
  								<div class="col-md-6">
  									<fieldset>
  										<legend>Nacionalidad</legend>
  										<div class="row">
  											<div class="col-md-12">
  												<div class="control-group">
  													<div class="radio">
  														<label class="radio-inline col-md-5">
  															<input type="radio" id="rb_Chileno" value="1" />
  															<span class="text">Chileno</span>
  														</label>
  														<label class="radio-inline col-md-5">
  															<input type="radio" id="rb_Extranjero" value="2" />
  															<span class="text">Extranjero</span>
  														</label>
  													</div>
  												</div>
  											</div>
  										</div>
  									</fieldset>
  								</div>
  							</div>
  							<fieldset>
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="rut" class="col-md-4 control-label no-padding-right">RUT:[*]</label>
  											<div class="col-md-8">
  												<input type="text" class="form-control input-sm" id="txt_ClienteRut" required/>
  											</div>
  										</div>
  									</div>
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="nombre" class="col-md-4 control-label no-padding-right">Nombres:[*]</label>
  											<div class="col-md-8">
  												<input type="text" class="form-control input-sm" id="txt_ClienteNombres" required/>
  											</div>
  										</div>
  									</div>
  								</div>
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="apaterno" class="col-md-4 control-label no-padding-right">Ap. Paterno:[*]</label>
  											<div class="col-md-8">
  												<input type="text" class="form-control input-sm" id="txt_ClienteApellidoPaterno" required/>
  											</div>
  										</div>
  									</div>
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="amaterno" class="col-md-4 control-label no-padding-right">Ap. Materno:</label>
  											<div class="col-md-8">
  												<input type="text" class="form-control input-sm" id="txt_ClienteApellidoMaterno" />
  											</div>
  										</div>
  									</div>
  								</div>
  								<hr />
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="pais_id" class="col-md-4 control-label no-padding-right">País:</label>
  											<div class="col-md-8">
  												<select id="cmb_PaisId" class="form-control input-sm">
  													<option value=''>-- Seleccionar --</option>
  												</select>
  											</div>
  										</div>
  									</div>
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="region_id" class="col-md-4 control-label no-padding-right">Región:</label>
  											<div class="col-md-8">
  												<select id="cmb_RegionId" class="form-control input-sm">
  													<option value=''>-- Seleccionar --</option>
  												</select>
  											</div>
  										</div>
  									</div>
  								</div>
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="ciudad_id" class="col-md-4 control-label no-padding-right">Ciudad:</label>
  											<div class="col-md-8">
  												<select id="cmb_CiudadId" class="form-control input-sm">
  													<option value=''>-- Seleccionar --</option>
  												</select>
  											</div>
  										</div>
  									</div>
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="comuna_id" class="col-md-4 control-label no-padding-right">Comuna:</label>
  											<div class="col-md-8">
  												<select id="cmb_ComunaId" class="form-control input-sm">
  													<option value=''>-- Seleccionar --</option>
  												</select>
  											</div>
  										</div>
  									</div>
  								</div>
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="direccion" class="col-md-4 control-label no-padding-right">Dirección:</label>
  											<div class="col-md-8">
  												<input type="text" class="form-control input-sm" id="txt_ClienteDireccion" />
  											</div>
  										</div>
  									</div>
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="email" class="col-md-4 control-label no-padding-right">E-Mail:[*]</label>
  											<div class="col-md-8">
  												<input type="email" class="form-control input-sm" id="txt_ClienteCorreoElectronico" required/>
  											</div>
  										</div>
  									</div>
  								</div>
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="fono1" class="col-md-4 control-label no-padding-right">Teléfono Fijo:</label>
  											<div class="col-md-8">
  												<input type="text" class="form-control input-sm" id="txt_ClienteTelefonoFijo" />
  											</div>
  										</div>
  									</div>
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="fono2" class="col-md-4 control-label no-padding-right">Celular:[*]</label>
  											<div class="col-md-8">
  												<input type="text" class="form-control input-sm" id="txt_ClienteCelular" required/>
  											</div>
  										</div>
  									</div>
  								</div>
  							</fieldset>
  							<div class="form-subtitulo">RESERVA</div>
  							<div class="row">
  								<div class="col-md-8">
  									<fieldset>
  										<legend>Fechas</legend>
  										<div class="row">
  											<div class="col-md-7">
  												<div class="form-group">
  													<label for="llegadafecha" class="col-md-5 control-label no-padding-right">Fecha llegada:[*]</label>
  													<div class="col-md-7">
  														<input type="date" id="dtp_FechaDesdeMDL" class="form-control input-sm" required disabled>
  													</div>
  												</div>
  											</div>
  											<div class="col-md-5">
  												<div class="form-group">
  													<label for="llegadahora" class="col-md-6 control-label no-padding-right">Hora llegada:[*]</label>
  													<div class="col-md-6">
  														<select id="dtp_HoraLlegada" class="form-control input-sm" required>
  															<option value='14:00'>14:00</option>
  															<option value='14:30'>14:30</option>
  															<option value='15:00'>15:00</option>
  															<option value='15:30'>15:30</option>
  															<option value='16:00'>16:00</option>
  															<option value='16:30'>16:30</option>
  															<option value='17:00'>17:00</option>
  															<option value='17:30'>17:30</option>
  															<option value='18:00'>18:00</option>
  															<option value='18:30'>18:30</option>
  															<option value='19:00'>19:00</option>
  															<option value='19:30'>19:30</option>
  															<option value='20:00'>20:00</option>
  															<option value='20:30'>20:30</option>
  															<option value='21:00'>21:00</option>
  															<option value='21:30'>21:30</option>
  															<option value='22:00'>22:00</option>
  															<option value='22:30'>22:30</option>
  															<option value='23:00'>23:00</option>
  															<option value='23:30'>23:30</option>
  														</select>
  													</div>
  												</div>
  											</div>
  										</div>
  										<div class="row">
  											<div class="col-md-7">
  												<div class="form-group">
  													<label for="salidafecha" class="col-md-5 control-label no-padding-right">Fecha salida:[*]</label>
  													<div class="col-md-7">
  														<input type="date" id="dtp_FechaHastaMDL" class="form-control input-sm" required disabled>
  													</div>
  												</div>
  											</div>
  											<div class="col-md-5">
  												<div class="form-group">
  													<label for="salidahora" class="col-md-6 control-label no-padding-right">Hora salida:[*]</label>
  													<div class="col-md-6">
  														<select id="dtp_HoraSalida" class="form-control input-sm" required>
  															<option value='12:00'>12:00</option>
  															<option value='17:00'>17:00</option>
  														</select>
  													</div>
  												</div>
  											</div>
  										</div>
  									</fieldset>
  								</div>
  								<div class="col-md-4">
  									<fieldset>
  										<legend>Cantidad</legend>
  										<div class="row">
  											<div class="col-md-12">
  												<div class="form-group">
  													<label for="adultosnro" class="col-md-6 control-label no-padding-right">Adultos:[*]</label>
                            <div class="col-md-6">
                              <input type="number" id="txt_AdultosMDL" class="form-control input-sm" required disabled>
  												  </div>
                          </div>
  											</div>
  										</div>
  										<div class="row">
  											<div class="col-md-12">
  												<div class="form-group">
  													<label for="niniosnro" class="col-md-6 control-label no-padding-right">Niños:[*]</label>
                            <div class="col-md-6">
                              <input type="number" id="txt_NinosMDL" class="form-control input-sm" required disabled>
  												  </div>
                          </div>
  											</div>
  										</div>
  									</fieldset>
  								</div>
  							</div>
  						</div>
  					</div>
  				</div>
  				<div class="modal-footer">
  					<button id="btn_GuardarReserva" type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar</button>
  					<button id="btn_SalirReserva" type="button" class="btn btn-deafult btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
  				</div>
  			</form>
  		</div>
  	</div>
  </div>

  <!-- scripts -->
  <!--Basic Scripts-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/slimscroll/jquery.slimscroll.min.js"></script>

  <!--Beyond Scripts-->
  <script src="assets/js/beyond.js"></script>


  <!--Page Related Scripts-->
  <!--Sparkline Charts Needed Scripts-->
  <script src="assets/js/charts/sparkline/jquery.sparkline.js"></script>
  <script src="assets/js/charts/sparkline/sparkline-init.js"></script>

  <!--Easy Pie Charts Needed Scripts-->
  <script src="assets/js/charts/easypiechart/jquery.easypiechart.js"></script>
  <script src="assets/js/charts/easypiechart/easypiechart-init.js"></script>

  <!--Flot Charts Needed Scripts-->
  <script src="assets/js/charts/flot/jquery.flot.js"></script>
  <script src="assets/js/charts/flot/jquery.flot.resize.js"></script>
  <script src="assets/js/charts/flot/jquery.flot.pie.js"></script>
  <script src="assets/js/charts/flot/jquery.flot.tooltip.js"></script>
  <script src="assets/js/charts/flot/jquery.flot.orderBars.js"></script>

  <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
  <script src="assets/js/datatable/ZeroClipboard.js"></script>
  <script src="assets/js/datatable/dataTables.tableTools.min.js"></script>
  <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
  <script src="assets/js/datatable/datatables-init.js"></script>

  <!-- Sweet alert -->
  <script src="assets/js/sweetalert/sweetalert.min.js"></script>

  <!-- Sweet alert -->
  <script src="assets/js/select2/select2.min.js"></script>

  <script src="assets/js/datetime/moment.js"></script>
  <script src="assets/js/datetime/daterangepicker.js"></script>

  <!-- Custom JS Controlador -->
  <script type="text/javascript" src="core/fn_Generales.js"></script>
  <script type="text/javascript" src="controllers/controller_buscarReserva.js"></script>
</html>
