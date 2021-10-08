<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[2]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_mae_Propiedades.js"></script>

<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header bordered-bottom bordered-blue">
          <span class="widget-caption">Cabañas</span>
        </div>
        <div class="widget-body">
          <div class="form-group row">
            <div class="col-lg-3 col-sm-3">
              <?php if($arr_Permisos[2]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
              <a id="btn_AbrirModalPropiedad" data-toggle="modal" class="btn btn-primary btn-sm" href="#mdl_Propiedad"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo</a>
              <?php } ?>
            </div>
          </div>

          <table id="tbl_Propiedades" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Capacidad</th>
                <th>Mantención</th>
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

<div id="mdl_Propiedad" class="modal fade" aria-hidden="true" data-backdrop="static" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="frm_Imagen" action="core/upload.php" method="post" enctype="multipart/form-data"></form>
      <form id="frm_Propiedad">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="lbl_TituloModalPropiedad"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group row">
                <label class="col-lg-2 col-sm-3 col-form-label">Nombre[*]</label>
                <div class="col-lg-6 col-sm-3">
                  <input type="text" id="txt_PropiedadNombre" class="form-control input-sm" required>
                </div>

                <label class="col-lg-2 col-sm-3 col-form-label">Capacidad[*]</label>
                <div class="col-lg-2 col-sm-3">
                  <input type="text" id="txt_PropiedadCapacidad" class="form-control input-sm" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-form-label">Descripción[*]</label>
                <div class="col-lg-10">
                  <textarea id="txt_PropiedadDescripcion" class="form-control input-sm" rows="4" required></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-form-label">¿Mantención?[*]</label>
                <div class="col-lg-2">
                  <select id="cmb_Mantencion" class="form-control input-sm">
                    <option value="D">No</option>
                    <option value="A">Sí</option>
                  </select>
                </div>
              </div>

              <div id="pnl_Servicios">
                <hr class="wide">
                <h5>Servicios</h5>
                <div class="form-group row">
                  <label class="col-lg-2 col-sm-3 col-form-label">Servicio</label>
                  <div class="col-lg-4 col-sm-3">
                    <select id="cmb_ServicioId" class="form-control input-sm">
                      <option value="">-- Seleccionar --</option>
                    </select>
                  </div>

                  <div class="col-lg-1">
                    <button id="btn_AgregarServicio" type="button" class="btn btn-success input-sm" ><i class="fa fa-plus"></i> Agregar</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <table id="tbl_Servicios" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Nombre</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div id="pnl_Imagenes">
                <hr class="wide">
                <h5>Imágenes</h5>
                <div class="row">
                  <label class="col-lg-2 col-form-label">Subir imagen</label>
                  <div class="col-lg-6">
                    <input type="file" class="form-control input-sm" name="upl_Archivo" id="upl_PropiedadImagen" form="frm_Imagen">
                  </div>
                  <div class="col-lg-4">
                    <button id="btn_SubirImagen" type="submit" class="btn btn-info btn-xs" form="frm_Imagen"><i class="fa fa-upload"></i> Subir</button>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-12" id="pnl_ImagenesPropiedad">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btn_GuardarPropiedad" class="btn btn-success input-sm" type="submit"><i class="glyphicon glyphicon-log-out"></i> Guardar</button>
          <button id="btn_SalirPropiedad" class="btn btn-warning input-sm" data-dismiss="modal" data-backdrop="false"><i class="glyphicon glyphicon-log-out"></i> Salir</button>
        </div>
      </form>
    </div>
  </div>
</div>
