<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[3]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_man_Tarifas.js"></script>

<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header bordered-bottom bordered-blue">
          <span class="widget-caption">Tarifas</span>
        </div>
        <div class="widget-body">
          <div class="form-group row">
            <div class="col-lg-3 col-sm-3">
              <?php if($arr_Permisos[3]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
              <a id="btn_AbrirModalTarifa" data-toggle="modal" class="btn btn-primary btn-sm" href="#mdl_Tarifa"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</a>
              <?php } ?>
            </div>
          </div>

          <table id="tbl_Tarifas" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Tipo</th>
                <th>Cantidad de personas</th>
                <th>Valor</th>
                <th>Estado</th>
                <th>Acciones</th>
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

<div id="mdl_Tarifa" class="modal fade" aria-hidden="true" data-backdrop="static" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="frm_Tarifa">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="lbl_TituloModalTarifa"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <label for="ciudad_id" class="col-md-3 control-label no-padding-right">Tipo de cliente:[*]</label>
            <div class="col-md-3">
              <select id="cmb_TarifaTipo" class="form-control input-sm" required>
                <option value=''>-- Seleccionar --</option>
                <option value='P'>Persona</option>
                <option value='E'>Empresa</option>
              </select>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group row">
                <label class="col-lg-3 col-sm-3 col-form-label">Cantidad de personas[*]</label>
                <div class="col-lg-3 col-sm-3">
                  <input type="number" id="txt_TarifaCantPersonas" class="form-control input-sm" required>
                </div>

                <label class="col-lg-3 col-sm-3 col-form-label">Valor[*]</label>
                <div class="col-lg-3 col-sm-3">
                  <input type="number" id="txt_TarifaValor" class="form-control input-sm" required>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btn_GuardarTarifa" class="btn btn-success input-sm" type="submit"><i class="glyphicon glyphicon-log-out"></i> Guardar</button>
          <button id="btn_SalirTarifa" class="btn btn-warning input-sm" data-dismiss="modal" data-backdrop="false"><i class="glyphicon glyphicon-log-out"></i> Salir</button>
        </div>
      </form>
    </div>
  </div>
</div>
