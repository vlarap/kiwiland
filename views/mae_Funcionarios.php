<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[2]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_mae_Funcionarios.js"></script>

<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header bordered-bottom bordered-blue">
          <span class="widget-caption">Funcionarios</span>
        </div>
        <div class="widget-body">
          <div class="form-group row">
            <div class="col-lg-3 col-sm-3">
              <?php if($arr_Permisos[2]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
              <a id="btn_AbrirModalFuncionario" data-toggle="modal" class="btn btn-primary btn-sm" href="#mdl_Funcionario"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</a>
              <?php } ?>
            </div>
          </div>

          <table id="tbl_Funcionarios" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>RUT</th>
                <th>Nombre</th>
                <th>Cargo</th>
                <th>Dirección</th>
                <th>Teléfono</th>
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

<div id="mdl_Funcionario" class="modal fade" aria-hidden="true" data-backdrop="static" tabindex="-1" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="frm_Funcionario">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="lbl_TituloModalFuncionario"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">RUT[*]</label>
                <div class="col-lg-3 col-sm-3">
                  <input type="text" id="txt_FuncionarioRut" class="form-control input-sm" required>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Cargo[*]</label>
                <div class="col-lg-5 col-sm-3">
                  <select id="cmb_CargoFuncionarioId" class="form-control m-b input-sm" required>
                    <option value="">-- Seleccionar --</option>
                  </select>
                </div>
              </div>
              <hr class="wide">

              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Nombres[*]</label>
                <div class="col-lg-10 col-sm-3">
                  <input type="text" id="txt_FuncionarioNombres" class="form-control input-sm" required>
                </div>
              </div>
              <hr class="wide">

              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Apellido paterno[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="text" id="txt_FuncionarioApellidoP" class="form-control input-sm" required>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Apellido materno</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="text" id="txt_FuncionarioApellidoM" class="form-control input-sm">
                </div>
              </div>
              <hr class="wide">

              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Dirección</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="text" id="txt_FuncionarioDireccion" class="form-control input-sm">
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Teléfono</label>
                <div class="col-lg-4 col-sm-3">
                  <span class="input-icon inverted">
                    <input type="text" id="txt_FuncionarioTelefono" class="form-control input-sm">
                    <i class="fa fa-phone bg-blue"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btn_GuardarFuncionario" class="btn btn-success input-sm" type="submit"><i class="glyphicon glyphicon-log-out"></i> Guardar</button>
          <button id="btn_SalirFuncionario" class="btn btn-warning input-sm" data-dismiss="modal" data-backdrop="false"><i class="glyphicon glyphicon-log-out"></i> Salir</button>
        </div>
      </form>
    </div>
  </div>
</div>
