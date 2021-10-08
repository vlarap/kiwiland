<?php
  include('config.php');
  include('database.php');
  session_start();

  $vli_Respuesta = 0; //VARIABLE QUE CONTROLA SI LOS DATOS INGRESADOS SON CORRECTOS

  $sql_Consulta   = "SELECT COUNT(*) AS Contador FROM ".ADM_USUARIOS." WHERE usuario_Nombre = '".$_POST['pvs_UsuarioNombre']."' AND usuario_Contrasena ='".md5($_POST['pvs_UsuarioContrasena'])."'";
  $sql_Query      = $sql_DB->query($sql_Consulta);
  $sql_Fila       = $sql_Query->fetch(PDO::FETCH_OBJ);
  $vli_Respuesta  = $sql_Fila->Contador;

  if($vli_Respuesta == 1){ //SI LOS DATOS INGRESADOS SON CORRECTOS Y EXISTEN EN BASE DE DATOS PERMITE EL INICIO DE SESION
    $sql_Consulta   = "SELECT   usuario_Id,
                                funcionario_Rut,
                                CONCAT(funcionario_Nombres, ' ', funcionario_ApellidoPaterno, ' ', funcionario_ApellidoMaterno) AS usuario_NombreC,
                                usuario_Nivel
                       FROM     ".ADM_USUARIOS." A INNER JOIN ".MAE_FUNCIONARIOS." B
                       ON       A.funcionario_Id = B.funcionario_Id
                       WHERE    usuario_Nombre = '".$_POST['pvs_UsuarioNombre']."' AND usuario_Contrasena ='".md5($_POST['pvs_UsuarioContrasena'])."'";
    $sql_Query      = $sql_DB->query($sql_Consulta);
    $sql_Fila       = $sql_Query->fetch(PDO::FETCH_OBJ);

    $_SESSION['vgi_UsuarioId']          = $sql_Fila->usuario_Id;
    $_SESSION['vgi_UsuarioRut']         = $sql_Fila->funcionario_Rut;
    $_SESSION['vgs_UsuarioNombre']      = $_POST['pvs_UsuarioNombre'];
    $_SESSION['vgs_FuncionarioNombre']  = $sql_Fila->usuario_NombreC;
    $_SESSION['vgs_Autentificado']      = 'S';
    $_SESSION['vgi_Tiempo']             = time();
    $_SESSION['vgs_UsuarioNivel']       = $sql_Fila->usuario_Nivel;

    $sql_Permisos = "SELECT modulo_Sigla, permiso_Leer, permiso_Crear, permiso_Actualizar, permiso_Eliminar, permiso_Pagar
                     FROM   ".ADM_PERMISOS." A INNER JOIN ".ADM_MODULOS." B
                     ON     A.modulo_Id = B.modulo_Id
                     WHERE  usuario_Id = " . $_SESSION['vgi_UsuarioId'];
    $sql_Query      = $sql_DB->query($sql_Permisos);
    while($sql_Fila = $sql_Query->fetch(PDO::FETCH_OBJ)){
      $sql_Res[] = array(
        "modulo_Sigla"        =>  $sql_Fila->modulo_Sigla,
        "permiso_Leer"        =>  $sql_Fila->permiso_Leer,
        "permiso_Crear"       =>  $sql_Fila->permiso_Crear,
        "permiso_Actualizar"  =>  $sql_Fila->permiso_Actualizar,
        "permiso_Eliminar"    =>  $sql_Fila->permiso_Eliminar,
        "permiso_Pagar"       =>  $sql_Fila->permiso_Pagar,
      );
    }
    $_SESSION['arr_Permisos'] = $sql_Res;

    echo 1;
  }else{
    echo "Los datos ingresados son incorrectos";
  }
?>
