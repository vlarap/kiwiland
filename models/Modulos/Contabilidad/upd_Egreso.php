<?php
  session_start();
  include('../../../includes/config.php');
  include('../../../includes/database.php');
  include('../../../core/fn_Generales.php');

  $fn_Funcion     = $_POST['fn_Funcion'];
  $vli_UsuarioId  = $_SESSION['vgi_UsuarioId'];

  if($fn_Funcion == 'EditarEgreso'){
    $json_Egreso = $_POST['json_Egreso'];

    try{
      $sql_DB->beginTransaction();

      //ACTUALIZAR CABECERA
      $sql_ActualizarEgreso = "UPDATE ".CON_EGRESOS."
                               SET    egreso_NroDoc           = ".$json_Egreso['vli_EgresoNroDocto'].",
                                      egreso_Fecha            = '".$json_Egreso['vld_EgresoFecha']."',
                                      tipoDoc_Id              = ".$json_Egreso['vli_TipoDocId'].",
                                      categoria_Id            = ".$json_Egreso['vli_CategoriaId'].",
                                      cliente_Id              = ".$json_Egreso['vli_ClienteId'].",
                                      egreso_Comentario       = '".$json_Egreso['vls_EgresoComentario']."',
                                      egreso_UsuarioModifica  = ".$vli_UsuarioId.",
                                      egreso_FechaModifica    = NOW()
                               WHERE  egreso_Id = " . $_POST['pvi_EgresoId'];

      $sql_DB->exec($sql_ActualizarEgreso);

      //ELIMINAMOS DETALLE
      $sql_EliminarEgresoDetalle = "DELETE FROM ".CON_EGRESOSCC." WHERE egreso_Id = " . $_POST['pvi_EgresoId'];
      $sql_DB->exec($sql_EliminarEgresoDetalle);

      //INSERTANDO DETALLES: CUOTA DE TERRENO
      for($i=0;$i<count($json_Egreso['vlARR_CCostoId']);$i++){
        $sql_InsertarEgresoDetalle = "INSERT INTO ".CON_EGRESOSCC." (
                                            egreso_Id,
                                            cCosto_Id,
                                            egresoCC_Monto
                                          )VALUES(
                                            ".$_POST['pvi_EgresoId'].",
                                            ".$json_Egreso['vlARR_CCostoId'][$i].",
                                            ".$json_Egreso['vlARR_Monto'][$i]."
                                      )";
        $sql_DB->exec($sql_InsertarEgresoDetalle);
      }

      //COMMIT FINAL
      $sql_DB->commit();
      echo 1; //RETORNO NUMERO DE INSERCIÓN
    }catch (PDOException $e){
      echo $e->getMessage();
      $sql_DB->rollBack();
    }
  }else if($fn_Funcion == 'AnularEgreso'){
    $sql_AnularEgreso = "UPDATE ".CON_EGRESOS."
                         SET    egreso_Estado = 'A'
                         WHERE  egreso_Id = ".$_POST['pvi_EgresoId'];
    try{
      $sql_Query = $sql_DB->query($sql_AnularEgreso);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }
?>
