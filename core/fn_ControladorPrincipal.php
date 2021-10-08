<?php
  function fn_CargarControlador($pvs_Controlador){
    // $vls_Controlador          = 'controller_'.$pvs_Controlador;
    // $vls_ArchivoControlador   = 'controllers/'.$vls_Controlador.'.js';
    //
    // if(!is_file($vls_ArchivoControlador)){
    //   $vls_ArchivoControlador = 'controllers/controller_'.CONTROLADOR_DEFECTO.'.js';
    // }

    $vls_ArchivoControlador   = 'views/'.$pvs_Controlador.'.php';
    if(!is_file($vls_ArchivoControlador)){
      $vls_ArchivoControlador = 'views/'.CONTROLADOR_DEFECTO.'.php';
    }

    return $vls_ArchivoControlador;
  }
?>
