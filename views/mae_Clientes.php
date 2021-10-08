<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[2]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_mae_Clientes.js"></script>

<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header bordered-bottom bordered-blue">
          <span class="widget-caption">Clientes</span>
        </div>
        <div class="widget-body">
          <div class="form-group row">
            <div class="col-lg-3 col-sm-3">
              <?php if($arr_Permisos[2]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
              <a id="btn_AbrirModalCliente" data-toggle="modal" class="btn btn-primary btn-sm" href="#mdl_Cliente"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</a>
              <?php } ?>
            </div>
          </div>

          <table id="tbl_Clientes" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Tipo</th>
                <th>RUT</th>
                <th>Nombre</th>
                <th>Direccion</th>
                <th>Teléfono</th>
                <th>Correo electrónico</th>
                <th>Valoración</th>
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

<div id="mdl_Cliente" class="modal fade" aria-hidden="true" data-backdrop="static" tabindex="-1" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="frm_Cliente">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="lbl_TituloModalCliente"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group row">
                <label for="ciudad_id" class="col-md-2 control-label no-padding-right">Tipo de cliente:[*]</label>
                <div class="col-md-4">
                  <select id="cmb_ClienteTipo" class="form-control input-sm" required>
                    <option value=''>-- Seleccionar --</option>
                    <option value='P'>Persona</option>
                    <option value='E'>Empresa</option>
                  </select>
                </div>
                <label class="col-lg-2 col-sm-3 col-form-label">RUT:</label>
                <div class="col-lg-3 col-sm-3">
                  <input type="text" id="txt_ClienteRut" class="form-control input-sm">
                  <small class="form-text text-muted">Sin puntos ni guión</small>
                </div>
              </div>
              <hr class="wide">

              <div class="form-group row">
                <label for="ciudad_id" class="col-md-2 control-label no-padding-right">Nacionalidad:[*]</label>
                <div class="col-md-4">
                  <select id="cmb_NacionalidadId" class="form-control input-sm" required>
                    <option value=''>-- Seleccionar --</option>
                  </select>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label" id="lbl_ClienteNombres">Nombres:[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="text" id="txt_ClienteNombres" class="form-control input-sm" required>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label" id="lbl_ClienteApellidoPaterno">Apellido Paterno:[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <input type="text" id="txt_ClienteApellidoPaterno" class="form-control input-sm" required>
                </div>
                <label class="col-lg-2 col-sm-3 col-form-label" id="lbl_ClienteApellidoMaterno">Apellido Materno</label>
                <div class="col-lg-4 col-sm-3" id="pnl_ClienteApellidoMaterno">
                  <input type="text" id="txt_ClienteApellidoMaterno" class="form-control input-sm">
                </div>
              </div>
              <hr class="wide">

              <h5>Datos de contacto</h5>
              <div class="form-group row">
                <label for="pais_id" class="col-md-2 control-label no-padding-right">País:</label>
                <div class="col-md-4">
                  <select id="cmb_PaisId" class="form-control input-sm">
                    <option value=''>-- Seleccionar --</option>
                  </select>
                </div>

                <label for="region_id" class="col-md-2 control-label no-padding-right">Región:</label>
                <div class="col-md-4">
                  <select id="cmb_RegionId" class="form-control input-sm">
                    <option value=''>-- Seleccionar --</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="ciudad_id" class="col-md-2 control-label no-padding-right">Ciudad:</label>
                <div class="col-md-4">
                  <select id="cmb_CiudadId" class="form-control input-sm">
                    <option value=''>-- Seleccionar --</option>
                  </select>
                </div>
                <label for="comuna_id" class="col-md-2 control-label no-padding-right">Comuna:</label>
                <div class="col-md-4">
                  <select id="cmb_ComunaId" class="form-control input-sm">
                    <option value=''>-- Seleccionar --</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Dirección:</label>
                <div class="col-lg-10 col-sm-3">
                  <span class="input-icon inverted">
                    <input type="text" id="txt_ClienteDireccion" class="form-control input-sm">
                    <i class="fa fa-map-marker bg-blue"></i>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Correo Eléctrónico:[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <span class="input-icon inverted">
                    <input type="email" id="txt_ClienteCorreoElectronico" class="form-control input-sm" required>
                    <i class="fa fa-at bg-blue"></i>
                  </span>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Teléfono Fijo:</label>
                <div class="col-lg-4 col-sm-3">
                  <span class="input-icon inverted">
                    <input type="tel" id="txt_ClienteTelefonoFijo" class="form-control input-sm">
                    <i class="fa fa-phone bg-blue"></i>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Celular:[*]</label>
                <div class="col-lg-4 col-sm-3">
                  <span class="input-icon inverted">
                    <input type="tel" id="txt_ClienteCelular" class="form-control input-sm" required>
                    <i class="fa fa-mobile-phone bg-blue"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btn_GuardarCliente" class="btn btn-success input-sm" type="submit" value=""><i class="glyphicon glyphicon-log-out"></i> Guardar</button>
          <button id="btn_SalirCliente" class="btn btn-warning input-sm" data-dismiss="modal" data-backdrop="false"><i class="glyphicon glyphicon-log-out"></i> Salir</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="mdl_Valoraciones" class="modal fade" aria-hidden="true" data-backdrop="static" tabindex="-1" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="lbl_TituloModalCliente">Valoraciones de Cliente</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <table id="tbl_Valoraciones" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>Nro. de reserva</th>
                  <th>Puntaje</th>
                  <th>Observación</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btn_SalirValoracion" class="btn btn-warning input-sm" data-dismiss="modal" data-backdrop="false"><i class="glyphicon glyphicon-log-out"></i> Salir</button>
      </div>
    </div>
  </div>
</div>
