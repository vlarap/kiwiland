<?php
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CambiarEstado'){
    fn_CambiarEstado($_POST['pvs_Estado'], MAN_CATEGORIAS, "categoria_Estado", "categoria_Id", $_POST['pvi_CategoriaId']);
  }else if($fn_Funcion == 'EditarCategoria'){
    $json_Categoria = $_POST['json_Categoria'];

    $sql_EditarCategoria = "UPDATE ".MAN_CATEGORIAS."
                            SET    categoria_Nombre = '".$json_Categoria['vls_CategoriaNombre']."',
                                   categoria_Sigla  = '".$json_Categoria['vls_CategoriaSigla']."'
                            WHERE  categoria_Id = ".$_POST['pvi_CategoriaId'];

    try{
      $sql_Query = $sql_DB->query($sql_EditarCategoria);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
