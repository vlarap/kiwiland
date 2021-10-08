<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion     = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'EliminarReserva'){
    $sql_EliminarReserva = "DELETE FROM ".RES_RESERVAS."
                            WHERE reserva_Id = ".$_POST['pvi_ReservaId'];

    try{
      $sql_Query = $sql_DB->query($sql_EliminarReserva);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
