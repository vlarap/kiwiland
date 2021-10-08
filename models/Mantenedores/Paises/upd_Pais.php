<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_PAISES, "pais_Estado", "pais_Id", $_POST['pvi_PaisId']);
  }else if($fn_Funcion == 'EditarPais'){
    $json_Pais = $_POST['json_Pais'];

    $sql_EditarPais = "UPDATE ".MAN_PAISES."
                         SET    pais_Nombre = '".$json_Pais['vls_PaisNombre']."'
                         WHERE  pais_Id = ".$_POST['pvi_PaisId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarPais);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
