<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[1]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_pnl_Egresos.js"></script>

<div class="wrapper wrapper-content">
  <form>
    <div class="row">
      <div class="col-lg-12">
        <div class="widget">
          <div class="widget-header bordered-bottom bordered-blue">
            <span class="widget-caption">Egresos</span>
          </div>
          <div class="widget-body">
            <div class="form-group row">
              <div class="col-lg-3 col-sm-3">
                <h5>Filtros de búsqueda</h5>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-4">
                <label>Cliente</label>
                <select class="form-control m-b input-sm" id="cmb_ClienteIdFiltro">
                  <option value="">-- Seleccionar --</option>
                </select>
              </div>

              <div class="col-lg-4">
                <label>Categoria</label>
                <select class="form-control m-b input-sm" id="cmb_CategoriaIdFiltro">
                  <option value="">-- Seleccionar --</option>
                </select>
              </div>

              <div class="controls col-lg-3">
                <label>Rango de fechas</label>
                <span class="input-icon inverted">
                  <input type="text" class="form-control input-sm" name="RangoFecha" id="dtp_RangoFechaFiltro" value=""/>
                  <i class="fa fa-calendar bg-blue"></i>
                </span>
              </div>
            </div>

            <button type="button" id="btn_Buscar" class="btn btn-info btn-sm pull-right"><i class="fa fa-search"></i> Buscar</button>
            <br>
            <hr class="wide">

            <div class="form-group row">
              <div class="col-md-offset-4 col-lg-4">
                <div class="databox radius-bordered databox-shadowed databox-graded databox-vertical" style="height: auto !important;">
                  <div class="databox-top bg-blue" style="height: auto !important;">
                    <span class="databox-text" style="font-size: 15px;" id="lbl_EgresosTotales"></span>
                  </div>
                  <div class="databox-bottom text-align-center" style="height: auto !important;">
                    <span class="databox-text">Egresos totales</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-lg-3 col-sm-3">
                <?php if($arr_Permisos[1]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
                <button type="button" id="btn_Nueva" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Nueva</a>
                <?php } ?>
              </div>
            </div>

            <table id="tbl_Egresos" class="table table-striped table-bordered table-hover" style="width:100%;">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Nro. Docto.</th>
                  <th>Tipo</th>
                  <th>Nombre</th>
                  <th>Categoria</th>
                  <th>Monto</th>
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
  </form>
</div>

<div id="mdl_Egreso" class="modal fade" aria-hidden="true" data-backdrop="static" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="frm_IngresarEgreso">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h5 class="modal-title">Egreso</h5>
				</div>
				<div class="modal-body">
          <div class="form-group row">
            <div class="col-md-3">
              <label for="nrodocto">Nro. Docto[*]:</label>
              <input type="number" id="txt_EgresoNroDocto" class="form-control input-sm" required>
            </div>
            <div class="col-md-3">
              <label for="tipodoc">Tipo de documento[*]:</label>
              <select id="cmb_TipoDocId" class="form-control input-sm" style="width: 100%" required>
                <option value=''>-- Seleccionar --</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="categoria">Categoria[*]:</label>
              <select id="cmb_CategoriaId" class="form-control m-b input-sm" style="width: 100%" required>
                <option value=''>-- Seleccionar --</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="totalpagar">Monto total:</label>
              <input type="number" id="txt_EgresoTotal" class="form-control input-sm" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <label for="fechadoc">Fecha de documento[*]:</label>
              <input type="date" id="dtp_EgresoFecha" class="form-control input-sm" required>
            </div>
            <div class="col-md-6">
              <label for="cliente">Cliente[*]:</label>
              <select id="cmb_ClienteId" class="form-control m-b input-sm" style="width: 100%" required>
                <option value=''>-- Seleccionar --</option>
              </select>
            </div>
          </div>
          <div class="row">
						<div class="col-md-12">
							<label for="mediopago">Comentario:</label>
              <input type="text" id="txt_EgresoComentario" class="form-control input-sm">
            </div>
          </div>

          <br><br>
          <h5><b>Detalle de Egreso</b></h5>
          <table id="tbl_DetalleEgreso" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Centro de Costo</th>
                <th>Monto asignado</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <br>
          <div class="row">
            <div class="col-md-2 col-md-offset-5">
              <button type="button" class="btn input-sm btn-block btn-info" id="btn_AgregarCC">+</button>
            </div>
          </div>
				</div>
				<div class="modal-footer">
					<button id="btn_GuardarEgreso" type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar</button>
					<button id="btn_SalirEgreso" type="button" class="btn btn-deafult btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
				</div>
			</form>
		</div>
	</div>
</div>
