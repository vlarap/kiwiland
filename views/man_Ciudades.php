<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[3]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_man_Ciudades.js"></script>

<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header bordered-bottom bordered-blue">
          <span class="widget-caption">Ciudades</span>
        </div>
        <div class="widget-body">
          <div class="form-group row">
            <div class="col-lg-3 col-sm-3">
              <?php if($arr_Permisos[3]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
              <a id="btn_AbrirModalCiudad" data-toggle="modal" class="btn btn-primary btn-sm" href="#mdl_Ciudad"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</a>
              <?php } ?>
            </div>
          </div>

          <table id="tbl_Ciudades" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Región</th>
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

<div id="mdl_Ciudad" class="modal fade" aria-hidden="true" data-backdrop="static" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="frm_Ciudad">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="lbl_TituloModalCiudad"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Nombre[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="text" id="txt_CiudadNombre" class="form-control input-sm" required>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Región[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <div class="input-group m-b">
                    <select id="cmb_RegionId" class="form-control m-b input-sm" required>
                      <option value="">-- Seleccionar --</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btn_GuardarCiudad" class="btn btn-success input-sm" type="submit"><i class="glyphicon glyphicon-log-out"></i> Guardar</button>
          <button id="btn_SalirCiudad" class="btn btn-warning input-sm" data-dismiss="modal" data-backdrop="false"><i class="glyphicon glyphicon-log-out"></i> Salir</button>
        </div>
      </form>
    </div>
  </div>
</div>
