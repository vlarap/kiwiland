<?php
  define("CONTROLADOR_DEFECTO", "pnl_Dashboard");

  /************************************************************************************/
  /*******************************VARIABLES DE TABLAS BD*******************************/
  /************************************************************************************/
  //ADMINISTRACION
  define("ADM_CONFIGURACIONES"   , "tbl_adm_configuraciones");
  define("ADM_USUARIOS"          , "tbl_adm_usuarios");
  define("ADM_MODULOS"           , "tbl_adm_modulos");
  define("ADM_PERMISOS"          , "tbl_adm_permisos");


  //RESERVAS DE PROPIEDAD
  define("RES_RESERVAS"   , "tbl_res_reservas");
  define("CON_INGRESOS"   , "tbl_con_ingresos");
  define("CON_EGRESOS"    , "tbl_con_egresos");
  define("CON_EGRESOSCC"  , "tbl_con_egresoccosto");

  //MAESTROS
  define("MAE_PROPIEDADES"          , "tbl_mae_propiedades");
  define("MAE_IMAGENESPROPIEDAD"    , "tbl_mae_imagenespropiedad");
  define("MAE_SERVICIOSPROPIEDAD"   , "tbl_mae_serviciospropiedad");
  define("MAE_CLIENTES"             , "tbl_mae_clientes");
  define("MAE_CLIENTEVALORACIONES"  , "tbl_mae_clientevaloraciones");
  define("MAE_FUNCIONARIOS"         , "tbl_mae_funcionarios");

  //MANTENEDORES
  define("MAN_PAISES"             ,   "tbl_man_paises");
  define("MAN_REGIONES"           ,   "tbl_man_regiones");
  define("MAN_CIUDADES"           ,   "tbl_man_ciudades");
  define("MAN_COMUNAS"            ,   "tbl_man_comunas");
  define("MAN_ORIGENES"           ,   "tbl_man_origenes");
  define("MAN_CARGOSFUNCIONARIO"  ,   "tbl_man_cargosfuncionario");
  define("MAN_TARIFAS"            ,   "tbl_man_tarifas");
  define("MAN_SERVICIOS"          ,   "tbl_man_servicios");
  define("MAN_MEDIOSPAGO"         ,   "tbl_man_mediospago");
  define("MAN_TIPOSDOCUMENTO"     ,   "tbl_man_tiposdocumento");
  define("MAN_CENTROSCOSTO"       ,   "tbl_man_centroscosto");
  define("MAN_CATEGORIAS"         ,   "tbl_man_categorias");

  /************************************************************************************/
  /*****************************VARIABLES DE BASE DE DATOS*****************************/
  /************************************************************************************/
  define('SQL_SERVER'   , 'localhost');
  define('SQL_USERNAME' , 'root');
  define('SQL_PASSWORD' , '');
  /*define('SQL_USERNAME' , 'qmo_sistema');*/
  /*define('SQL_PASSWORD' , 'sis1234qmoadmin');*/
  define('SQL_DATABASE' , 'qmo_kiwiland');
?>
