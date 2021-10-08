<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_CIUDADES, "ciudad_Estado", "ciudad_Id", $_POST['pvi_CiudadId']);
  }else if($fn_Funcion == 'EditarCiudad'){
    $json_Ciudad = $_POST['json_Ciudad'];

    $sql_EditarCiudad = "UPDATE ".MAN_CIUDADES."
                         SET    ciudad_Nombre = '".$json_Ciudad['vls_CiudadNombre']."',
                                region_Id     = '".$json_Ciudad['vli_RegionId']."'
                         WHERE  ciudad_Id = ".$_POST['pvi_CiudadId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarCiudad);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
