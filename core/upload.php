<?php
  include('../includes/config.php');
  include('../includes/database.php');
  include('fn_Generales.php');
  $id = $_GET['id'];

  $arr_Extensiones = array('jpeg', 'jpg', 'png', 'gif', 'bmp'); //EXTENSIONES VALIDAS
  $vls_Ruta = '../img/propiedades/'.$id.'/'; //RUTA DIRECTORIO DE ARCHIVOS

  if($_FILES['upl_Archivo']){
    $vls_Archivo  = $_FILES['upl_Archivo']['name'];
    $vls_Temporal = $_FILES['upl_Archivo']['tmp_name'];

    $vls_Ext          = strtolower(pathinfo($vls_Archivo, PATHINFO_EXTENSION));
    $vls_FinalArchivo = rand(1000,1000000).$vls_Archivo;

    if (!file_exists($vls_Ruta)) {
      mkdir($vls_Ruta, 0777, true);
    }

    if(in_array($vls_Ext, $arr_Extensiones)){
      $vls_Ruta = $vls_Ruta.strtolower($vls_FinalArchivo);

      if(move_uploaded_file($vls_Temporal, $vls_Ruta)){
        $sql_InsertarImagen = "INSERT INTO ".MAE_IMAGENESPROPIEDAD." (propiedad_Id, propiedad_Imagen)
                                  VALUES(".$id.", '".$vls_FinalArchivo."')";

        try{
          $sql_Query = $sql_DB->query($sql_InsertarImagen);

          echo 1;
        }catch (PDOException $e){
          echo $e->getMessage();
        }

        echo $vls_FinalArchivo;
      }
    }else{
      echo 'Invalido';
    }
  }
?>
