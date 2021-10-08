<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion     = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'AnularIngreso'){
    $sql_AnularIngreso = "UPDATE  ".CON_INGRESOS."
                          SET     ingreso_Estado          = 'A',
                                  ingreso_Comentario      = CONCAT(ingreso_Comentario, ' Motivo anulación: ', '".$_POST['pvs_MotivoAnulación']."'),
                                  ingreso_UsuarioModifica = ".$vli_UsuarioId.",
                                  ingreso_FechaModifica   = NOW()
                          WHERE   ingreso_Id  = " . $_POST['pvi_IngresoId'];

    try{
      $sql_Query = $sql_DB->query($sql_AnularIngreso);
      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }else if($fn_Funcion == 'ContabilizarIngreso'){
    $sql_ContabilizarIngreso = "UPDATE  ".CON_INGRESOS."
                                SET     ingreso_Estado          = 'C',
                                        ingreso_UsuarioModifica = ".$vli_UsuarioId.",
                                        ingreso_FechaModifica   = NOW()
                                WHERE   ingreso_Id  = " . $_POST['pvi_IngresoId'];

    try{
      $sql_Query = $sql_DB->query($sql_ContabilizarIngreso);
      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
