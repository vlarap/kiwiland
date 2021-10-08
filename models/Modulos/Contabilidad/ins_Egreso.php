<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion     = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'InsertarEgreso'){
    $json_Egreso = $_POST['json_Egreso'];

    try{
      $sql_DB->beginTransaction();

      //INSERTANDO CABECERA
      $sql_InsertarEgreso = "INSERT INTO ".CON_EGRESOS." (
                                  egreso_NroDoc,
                                  egreso_Fecha,
                                  tipoDoc_Id,
                                  categoria_Id,
                                  cliente_Id,
                                  egreso_Comentario,
                                  egreso_UsuarioCrea,
                                  egreso_FechaCrea,
                                  egreso_UsuarioModifica,
                                  egreso_FechaModifica
                                ) VALUES(
                                  ".$json_Egreso['vli_EgresoNroDocto'].",
                                  '".$json_Egreso['vld_EgresoFecha']."',
                                  ".$json_Egreso['vli_TipoDocId'].",
                                  ".$json_Egreso['vli_CategoriaId'].",
                                  ".$json_Egreso['vli_ClienteId'].",
                                  '".$json_Egreso['vls_EgresoComentario']."',
                                  ".$vli_UsuarioId.",
                                  NOW(),
                                  ".$vli_UsuarioId.",
                                  NOW()
                                )";

      $sql_DB->exec($sql_InsertarEgreso);
      $vli_EgresoId = $sql_DB->lastInsertId();

      //INSERTANDO DETALLES: CUOTA DE TERRENO
      for($i=0;$i<count($json_Egreso['vlARR_CCostoId']);$i++){
        $sql_InsertarEgresoDetalle = "INSERT INTO ".CON_EGRESOSCC." (
                                            egreso_Id,
                                            cCosto_Id,
                                            egresoCC_Monto
                                          )VALUES(
                                            ".$vli_EgresoId.",
                                            ".$json_Egreso['vlARR_CCostoId'][$i].",
                                            ".$json_Egreso['vlARR_Monto'][$i]."
                                      )";
        $sql_DB->exec($sql_InsertarEgresoDetalle);
      }

      //COMMIT FINAL
      $sql_DB->commit();
      echo $vli_EgresoId; //RETORNO NUMERO DE INSERCIÓN
    }catch (PDOException $e){
      echo $e->getMessage();
      $sql_DB->rollBack();
    }
  }
?>
