<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[4]['permiso_Leer'] == 'I' || $arr_Permisos[4]['permiso_Actualizar'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_adm_Configuraciones.js"></script>

<div class="wrapper wrapper-content">
  <form id="frm_Configuracion">
    <div class="row">
      <div class="col-lg-12">
        <div class="widget">
          <div class="widget-header bordered-bottom bordered-blue">
            <span class="widget-caption">Configuraciones</span>
          </div>
          <div class="widget-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    Check In-Out
                  </div>
                  <div class="panel-body">
                    <div class="form-group row">
                      <div class="col-lg-2">
                        <label>Check In[*]</label>
                        <input type="time" id="txt_CFGCheckIn" class="form-control" required>
                      </div>

                      <div class="col-lg-2">
                        <label>Check Out[*]</label>
                        <input type="time" id="txt_CFGCheckOut" class="form-control" required>
                      </div>

                      <div class="col-lg-2">
                        <label>Late Check Out[*]</label>
                        <input type="time" id="txt_CFGLateCheckOut" class="form-control" required>
                      </div>

                      <div class="col-lg-2">
                        <label>Precio Late Check Out[*]</label>
                        <input type="number" id="txt_CFGLCOPrecio" min="0" class="form-control" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-12">
                <button type="submit" id="btn_Guardar" class="btn btn-primary btn-sm"><i class="fa fa-cloud" aria-hidden="true"></i> Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
