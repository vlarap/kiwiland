<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_COMUNAS, "comuna_Estado", "comuna_Id", $_POST['pvi_ComunaId']);
  }else if($fn_Funcion == 'EditarComuna'){
    $json_Comuna = $_POST['json_Comuna'];

    $sql_EditarComuna = "UPDATE ".MAN_COMUNAS."
                         SET    comuna_Nombre = '".$json_Comuna['vls_ComunaNombre']."',
                                ciudad_Id     = '".$json_Comuna['vli_CiudadId']."'
                         WHERE  comuna_Id = ".$_POST['pvi_ComunaId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarComuna);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
