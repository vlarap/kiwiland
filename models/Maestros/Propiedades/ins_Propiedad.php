<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarPropiedad'){
    $json_Propiedad = $_POST['json_Propiedad'];

    $sql_InsertarPropiedad = "INSERT INTO ".MAE_PROPIEDADES." (
                                propiedad_Nombre,
                                propiedad_Capacidad,
                                propiedad_Descripcion,
                                propiedad_Mantencion
                              ) VALUES(
                                '".$json_Propiedad['vls_PropiedadNombre']."',
                                ".$json_Propiedad['vli_PropiedadCapacidad'].",
                                '".$json_Propiedad['vls_PropiedadDescripcion']."',
                                '".$json_Propiedad['vls_Mantencion']."'
                              )";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarPropiedad);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }else if($fn_Funcion == 'InsertarServicio'){
    $sql_InsertarServicio = "INSERT INTO ".MAE_SERVICIOSPROPIEDAD." (
                                servicio_Id,
                                propiedad_Id
                              ) VALUES(
                                ".$_POST['pvi_ServicioId'].",
                                ".$_POST['pvi_PropiedadId']."
                              )";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarServicio);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
