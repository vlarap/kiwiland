<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $arr_Permisos = $_SESSION['arr_Permisos'];
  $fn_Funcion = $_POST['fn_Funcion'];

  if($fn_Funcion == 'CargarPanel'){
    $sql_CargarPanel = "SELECT  categoria_Id,
                                categoria_Nombre,
                                categoria_Sigla,
                                categoria_Tipo,
                                categoria_DeSistema,
                                categoria_Estado
                        FROM    ".MAN_CATEGORIAS;

    $sql_Query = $sql_DB->query($sql_CargarPanel);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $vls_BotonEditar = '<button title="Editar" class="btn btn-info icon-only btn-xs" onclick="fn_EditarCategoria('.$sql_Fila->categoria_Id.');"><i class="fa fa-edit"></i></button> ';
      if($sql_Fila->categoria_Estado == 'A'){
        $vls_CambiarEstado = "fn_CambiarEstadoCategoria(".$sql_Fila->categoria_Id.", 'D')";
        $vls_BotonEstado = '<button title="Desactivar" class="btn btn-danger icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-minus-circle"></i></button>';
      }else{
        $vls_CambiarEstado = "fn_CambiarEstadoCategoria(".$sql_Fila->categoria_Id.", 'A')";
        $vls_BotonEstado = '<button title="Activar" class="btn btn-success icon-only btn-xs" onclick="'.$vls_CambiarEstado.'"><i class="fa fa-check-square"></i></button>';
      }

      if($sql_Fila->categoria_DeSistema == 'S'){
        $vls_BotonEditar = '';
        $vls_BotonEstado = '';
      }

      if($sql_Fila->categoria_Tipo == 'I'){
        $vls_CategoriaTipo = 'Ingreso';
      }else if($sql_Fila->categoria_Tipo == 'E'){
        $vls_CategoriaTipo = 'Egreso';
      }else{
        $vls_CategoriaTipo = 'Ingreso/Egreso';
      }

      $sql_Res[] = array(
        "categoria_Nombre"   =>  $sql_Fila->categoria_Nombre,
        "categoria_Sigla"    =>  $sql_Fila->categoria_Sigla,
        "categoria_Tipo"     =>  $vls_CategoriaTipo,
        "categoria_Estado"   =>  fn_TransformarEstado($sql_Fila->categoria_Estado),
        "categoria_Acciones" =>  ($arr_Permisos[3]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEditar : '').
                                 ($arr_Permisos[3]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM' ? $vls_BotonEstado : '')
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'EditarCategoria'){
    $sql_EditarCategoria = "SELECT categoria_Nombre,
                                   categoria_Sigla,
                                   categoria_Tipo
                            FROM   ".MAN_CATEGORIAS."
                            WHERE  categoria_Id = ".$_POST['pvi_CategoriaId'];

    $sql_Query = $sql_DB->query($sql_EditarCategoria);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res = array(
        "categoria_Nombre" =>  $sql_Fila->categoria_Nombre,
        "categoria_Sigla"  =>  $sql_Fila->categoria_Sigla,
        "categoria_Tipo"   =>  $sql_Fila->categoria_Tipo
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarCategoriasXTipo'){
    $sql_CargarCategorias = "SELECT    categoria_Id,
                                       categoria_Nombre
                             FROM      ".MAN_CATEGORIAS."
                             WHERE     categoria_Estado = 'A' AND (categoria_Tipo = '".$_POST['id']."' OR categoria_Tipo = 'A')
                             ORDER BY  categoria_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarCategorias);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->categoria_Id,
        "nombre"  =>  $sql_Fila->categoria_Nombre
      );
    }
    echo json_encode($sql_Res);
  }else if($fn_Funcion == 'CargarCategoriasXSigla'){
    $sql_CargarCategorias = "SELECT    categoria_Sigla,
                                       categoria_Nombre
                             FROM      ".MAN_CATEGORIAS."
                             WHERE     categoria_Estado = 'A' AND (categoria_Tipo = '".$_POST['id']."' OR categoria_Tipo = 'A')
                             ORDER BY  categoria_Nombre";

    $sql_Query = $sql_DB->query($sql_CargarCategorias);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "id"      =>  $sql_Fila->categoria_Sigla,
        "nombre"  =>  $sql_Fila->categoria_Nombre
      );
    }
    echo json_encode($sql_Res);
  }
?>
