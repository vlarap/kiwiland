<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[4]['permiso_Leer'] == 'I' || $arr_Permisos[4]['permiso_Crear'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_adm_Permisos.js"></script>

<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header bordered-bottom bordered-blue">
          <span class="widget-caption">Permisos</span>
        </div>
        <div class="widget-body">
          <div class="form-group row">
            <label class="col-lg-2 col-sm-3 col-form-label">Usuario[*]</label>
            <div class="col-lg-3 col-sm-3">
              <select id="cmb_UsuarioId" class="form-control input-sm">
                <option value="">Elegir...</option>
              </select>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <div id="pnl_Permisos">
            <table id="tbl_Permisos" class="table table-striped table-hover table-condensed">
              <thead>
                <tr>
                  <th style="width: 15%;" class="text-left">MÓDULO</th>
                  <th style="width: 85%;" class="text-left">PERMISOS</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
					</div>
        </div>
      </div>
    </div>
  </div>
</div>
