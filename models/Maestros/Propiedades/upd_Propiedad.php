<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAE_PROPIEDADES, "propiedad_Estado", "propiedad_Id", $_POST['pvi_PropiedadId']);
  }else if($fn_Funcion == 'EditarPropiedad'){
    $json_Propiedad = $_POST['json_Propiedad'];

    $sql_EditarPropiedad = "UPDATE  ".MAE_PROPIEDADES."
                            SET     propiedad_Nombre      = '".$json_Propiedad['vls_PropiedadNombre']."',
                                    propiedad_Capacidad   = '".$json_Propiedad['vli_PropiedadCapacidad']."',
                                    propiedad_Descripcion = '".$json_Propiedad['vls_PropiedadDescripcion']."',
                                    propiedad_Mantencion  = '".$json_Propiedad['vls_Mantencion']."'
                            WHERE   propiedad_Id = ".$_POST['pvi_PropiedadId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarPropiedad);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
