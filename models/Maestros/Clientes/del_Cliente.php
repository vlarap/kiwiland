<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'EliminarCliente'){
    $sql_EliminarCliente = "DELETE FROM  ".MAE_CLIENTES."
                            WHERE cliente_Id = ".$_POST['pvi_ClienteId'];

    try{
      $sql_Query = $sql_DB->query($sql_EliminarCliente);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
