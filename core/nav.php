<?php
  $arr_Permisos = $_SESSION['arr_Permisos'];
?>

<div class="page-sidebar" id="sidebar">
  <ul class="nav sidebar-menu">
    <li <?php if($_GET['c'] == 'index'){ echo "class='active'"; } ?>>
      <a href="?c=index">
        <i class="menu-icon glyphicon glyphicon-home"></i>
        <span class="menu-text"> Dashboard </span>
      </a>
    </li>
    <?php if($arr_Permisos[0]['permiso_Leer'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
    <li <?php if($_GET['c'] == 'pnl_Reservas'){ echo "class='active'"; } ?>>
      <a href="?c=pnl_Reservas">
        <i class="menu-icon glyphicon glyphicon-list-alt"></i>
        <span class="menu-text"> Reservas </span>
      </a>
    </li>
    <?php } ?>
    <?php if($arr_Permisos[1]['permiso_Leer'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
    <li <?php if($_GET['c'] == 'pnl_Ingresos' || $_GET['c'] == 'pnl_Egresos'){ echo "class='active open'"; } ?>>
      <a href="#" class="menu-dropdown"><i class="menu-icon fa fa-dollar"></i><span class="menu-text"> Ingresos/Egresos </span><i class="menu-expand"></i></a>
      <ul class="submenu">
        <li <?php if($_GET['c'] == 'pnl_Ingresos'){ echo "class='active'"; } ?>><a href="?c=pnl_Ingresos"><span class="menu-text">Ingresos</span></a></li>
        <li <?php if($_GET['c'] == 'pnl_Egresos'){ echo "class='active'"; } ?>><a href="?c=pnl_Egresos"><span class="menu-text">Egresos</span></a></li>
      </ul>
    </li>
    <?php } ?>
    <?php if($arr_Permisos[2]['permiso_Leer'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
    <li <?php if(substr($_GET['c'], 0, 3) == 'mae'){ echo "class='active open'"; } ?>>
      <a href="#" class="menu-dropdown"><i class="menu-icon fa fa-gears"></i><span class="menu-text"> Maestros </span><i class="menu-expand"></i></a>
      <ul class="submenu">
        <li <?php if($_GET['c'] == 'mae_Clientes'){ echo "class='active'"; } ?>><a href="?c=mae_Clientes"><span class="menu-text">Clientes</span></a></li>
        <li <?php if($_GET['c'] == 'mae_Funcionarios'){ echo "class='active'"; } ?>><a href="?c=mae_Funcionarios"><span class="menu-text">Funcionarios</span></a></li>
        <li <?php if($_GET['c'] == 'mae_Propiedades'){ echo "class='active'"; } ?>><a href="?c=mae_Propiedades"><span class="menu-text">Cabañas</span></a></li>
      </ul>
    </li>
    <?php } ?>
    <?php if($arr_Permisos[3]['permiso_Leer'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
    <li <?php if(substr($_GET['c'], 0, 3) == 'man'){ echo "class='active open'"; } ?>>
      <a href="#" class="menu-dropdown"><i class="menu-icon fa fa-gears"></i><span class="menu-text"> Mantenedores </span><i class="menu-expand"></i></a>
      <ul class="submenu">
        <li <?php if($_GET['c'] == 'man_CargosFuncionario'){ echo "class='active'"; } ?>><a href="?c=man_CargosFuncionario"><span class="menu-text">Cargos de funcionario</span></a></li>
        <li <?php if($_GET['c'] == 'man_Categorias'){ echo "class='active'"; } ?>><a href="?c=man_Categorias"><span class="menu-text">Categorias</span></a></li>
        <li <?php if($_GET['c'] == 'man_CentrosCosto'){ echo "class='active'"; } ?>><a href="?c=man_CentrosCosto"><span class="menu-text">Centros de costo</span></a></li>
        <li <?php if($_GET['c'] == 'man_Ciudades'){ echo "class='active'"; } ?>><a href="?c=man_Ciudades"><span class="menu-text">Ciudades</span></a></li>
        <li <?php if($_GET['c'] == 'man_Comunas'){ echo "class='active'"; } ?>><a href="?c=man_Comunas"><span class="menu-text">Comunas</span></a></li>
        <li <?php if($_GET['c'] == 'man_MediosPago'){ echo "class='active'"; } ?>><a href="?c=man_MediosPago"><span class="menu-text">Medios de pago</span></a></li>
        <li <?php if($_GET['c'] == 'man_Origenes'){ echo "class='active'"; } ?>><a href="?c=man_Origenes"><span class="menu-text">Origenes</span></a></li>
        <li <?php if($_GET['c'] == 'man_Paises'){ echo "class='active'"; } ?>><a href="?c=man_Paises"><span class="menu-text">Paises</span></a></li>
        <li <?php if($_GET['c'] == 'man_Regiones'){ echo "class='active'"; } ?>><a href="?c=man_Regiones"><span class="menu-text">Regiones</span></a></li>
        <li <?php if($_GET['c'] == 'man_Servicios'){ echo "class='active'"; } ?>><a href="?c=man_Servicios"><span class="menu-text">Servicios</span></a></li>
        <li <?php if($_GET['c'] == 'man_Tarifas'){ echo "class='active'"; } ?>><a href="?c=man_Tarifas"><span class="menu-text">Tarifas</span></a></li>
        <li <?php if($_GET['c'] == 'man_TiposDocumento'){ echo "class='active'"; } ?>><a href="?c=man_TiposDocumento"><span class="menu-text">Tipos de documento</span></a></li>
      </ul>
    </li>
    <?php } ?>
    <?php if($arr_Permisos[4]['permiso_Leer'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
    <li <?php if(substr($_GET['c'], 0, 3) == 'adm'){ echo "class='active open'"; } ?>>
      <a href="#" class="menu-dropdown"><i class="menu-icon fa fa-gear"></i><span class="menu-text"> Administración </span><i class="menu-expand"></i></a>
      <ul class="submenu">
        <li <?php if($_GET['c'] == 'adm_Configuraciones'){ echo "class='active'"; } ?>><a href="?c=adm_Configuraciones"><span class="menu-text">Configuraciones</span></a></li>
        <li <?php if($_GET['c'] == 'adm_Permisos'){ echo "class='active'"; } ?>><a href="?c=adm_Permisos"><span class="menu-text">Permisos</span></a></li>
        <li <?php if($_GET['c'] == 'adm_Usuarios'){ echo "class='active'"; } ?>><a href="?c=adm_Usuarios"><span class="menu-text">Usuarios</span></a></li>
      </ul>
    </li>
    <?php } ?>
  </ul>
</div>
