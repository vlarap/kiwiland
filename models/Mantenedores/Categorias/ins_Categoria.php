<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'InsertarCategoria'){
    $json_Categoria = $_POST['json_Categoria'];

    $sql_InsertarCategoria = "INSERT INTO ".MAN_CATEGORIAS." (categoria_Nombre, categoria_Sigla, categoria_Tipo)
                              VALUES('".$json_Categoria['vls_CategoriaNombre']."', '".$json_Categoria['vls_CategoriaSigla']."', '".$json_Categoria['vls_CategoriaTipo']."')";

    try{
      $sql_Query = $sql_DB->query($sql_InsertarCategoria);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
