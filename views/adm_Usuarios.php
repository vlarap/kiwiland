<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[4]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_adm_Usuarios.js"></script>

<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header bordered-bottom bordered-blue">
          <span class="widget-caption">Usuarios</span>
        </div>
        <div class="widget-body">
          <div class="form-group row">
            <div class="col-lg-3 col-sm-3">
              <?php if($arr_Permisos[4]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
              <a id="btn_AbrirModalUsuario" data-toggle="modal" class="btn btn-primary btn-sm" href="#mdl_Usuario"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</a>
              <?php } ?>
            </div>
          </div>
          <div class="hr-line-dashed"></div>

          <table id="tbl_Usuarios" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Funcionario</th>
                <th>Nombre de Usuario</th>
                <th>Nivel</th>
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

<div id="mdl_Usuario" class="modal fade" aria-hidden="true" data-backdrop="static" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="frm_Usuario">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="lbl_TituloModalUsuario"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Funcionario[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <select id="cmb_FuncionarioId" class="form-control input-sm">
                    <option value="">-- Seleccionar --</option>
                  </select>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Nivel de usuario[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <select id="cmb_UsuarioNivel" class="form-control input-sm">
                    <option value="">-- Seleccionar --</option>
                    <option value="USU">Usuario</option>
                    <option value="ADM">Administrador</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Nombre de usuario[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="text" id="txt_UsuarioNombre" class="form-control input-sm" required>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Contraseña[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="password" id="txt_UsuarioContrasena" class="form-control input-sm">
                  <small id="lbl_AvisoContrasena" class="form-text text-muted">Si no desea cambiarla, dejar en blanco.</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btn_GuardarUsuario" class="btn btn-success input-sm" type="submit"><i class="glyphicon glyphicon-log-out"></i> Guardar</button>
          <button id="btn_SalirUsuario" class="btn btn-warning input-sm" data-dismiss="modal" data-backdrop="false"><i class="glyphicon glyphicon-log-out"></i> Salir</button>
        </div>
      </form>
    </div>
  </div>
</div>
