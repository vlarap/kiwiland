<?php
  session_start();

  //CFG GLOBLAL
  require_once 'includes/config.php';

  //CONTROLADOR DE VISTAS PRINCIPAL
  require_once 'core/fn_ControladorPrincipal.php';

  if($_SESSION['vgs_Autentificado'] == 'S'){
    if(isset($_SESSION['vgi_Tiempo'])){                       //VERIFICAMOS LA EXISTENCIA DE LA VARIABLE DE TIEMPO
      $vli_Inactividad  = 30000;                                //SETIAMOS EL POSIBLE TIEMPO DE INACTIVIDAD EN SEGUNDOS;
      $vli_VidaSesion   = time() - $_SESSION['vgi_Tiempo'];   //CALCULAMOS EL TIEMPO TRANSCURRIDO DESDE EL ULTIMO REFRESCO DE USO HASTA EL ACTUAL

      if($vli_VidaSesion > $vli_Inactividad){                 //COMPROBAMOS SI CERRAREMOS LA SESION POR INACTIVIDAD Y REDIRECCIONAMOS
        session_unset();                                      //REMOVEMOS LA SESION
        session_destroy();                                    //DESTRUIMOS LA SESION
        header("Location: index.php");                        //REDIRECCIONAMOS A LOGIN
        exit();
      }else{
        $_SESSION['vgi_Tiempo'] = time();                     //SI NO SE HA CUMPLICO EL TIEMPO, RENOVAMOS.
      }
    }

    /* ACTUALIZAMOS A CONTROLADOR CORRESPONDIENTE */
    if(isset($_GET["c"])){
      $obj_Controlador  = fn_CargarControlador($_GET["c"]);
    }else{
      $obj_Controlador  = fn_CargarControlador(CONTROLADOR_DEFECTO);
    }
  }else{
    $obj_Controlador  = fn_CargarControlador('login');
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
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

  <?php if($_SESSION['vgs_Autentificado'] == 'S'){ ?>
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
          <div class="sidebar-collapse" id="sidebar-collapse">
            <i class="collapse-icon fa fa-bars"></i>
          </div>
          <div class="navbar-header pull-right">
            <div class="navbar-account">
              <ul class="account-area">
                <li>
                  <a class="login-area dropdown-toggle" data-toggle="dropdown">
                    <div class="avatar" title="View your public profile">
                      <img src="assets/img/avatars/adam-jansen.jpg">
                    </div>
                    <section>
                      <h2><span class="profile"><span><?php echo $_SESSION['vgs_FuncionarioNombre'] ?></span></span></h2>
                    </section>
                  </a>
                  <ul class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
                    <li class="dropdown-footer">
                      <a href="includes/logout.php">
                        Cerrar sesión
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="main-container container-fluid">
      <div class="page-container">
        <?php include("core/nav.php"); //MENU RESPONSIVE ?>

        <div class="page-content">
          <div class="page-breadcrumbs">
            <ul class="breadcrumb">
              <li>
                <i class="fa fa-home"></i>
                <a href="#">Home</a>
              </li>
              <li class="active">Dashboard</li>
            </ul>
          </div>
          <div id="page-wrapper" class="page-body">
          </div>
        </div>
      </div>
    </div>
  </body>
  <?php }else{ ?>
  <body id="page-wrapper" class="gray-bg" style="width: 100% !important;"></body>
  <?php } ?>

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
  <script src="assets/js/datejs/date.js"></script>

  <!-- Custom JS Controlador -->
  <script type="text/javascript" src="core/fn_Generales.js"></script>
  <?php
  echo "<script type='text/javascript'>$('#page-wrapper').load('".$obj_Controlador."');</script>";
  ?>
  <?php
  if(isset($_GET['i'])){
    echo '<script type="text/javascript">var vgi_Id = '. base64_decode($_GET['i']) .';</script>';
  }else{
    echo '<script type="text/javascript">var vgi_Id = 0;</script>';
  }
  ?>
</html>
