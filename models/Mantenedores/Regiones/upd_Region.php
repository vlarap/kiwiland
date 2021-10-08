<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_REGIONES, "region_Estado", "region_Id", $_POST['pvi_RegionId']);
  }else if($fn_Funcion == 'EditarRegion'){
    $json_Region = $_POST['json_Region'];

    $sql_EditarRegion = "UPDATE ".MAN_REGIONES."
                         SET    region_Nombre = '".$json_Region['vls_RegionNombre']."'
                         WHERE  region_Id = ".$_POST['pvi_RegionId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarRegion);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
