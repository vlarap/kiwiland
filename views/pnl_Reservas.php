<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[0]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_pnl_Reservas.js"></script>

<div class="wrapper wrapper-content">
  <form>
    <div class="row">
      <div class="col-lg-12">
        <div class="widget">
          <div class="widget-header bordered-bottom bordered-blue">
            <span class="widget-caption">Reservas</span>
          </div>
          <div class="widget-body">
            <div class="form-group row">
              <div class="col-lg-3 col-sm-3">
                <h5>Filtros de búsqueda</h5>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-2">
                <label>Nro de reserva:</label>
                <span class="input-icon inverted">
                  <input type="text" id="txt_ReservaId" class="form-control input-sm">
                  <i class="fa fa-sort-numeric-asc bg-blue"></i>
                </span>
              </div>

              <div class="col-lg-4">
                <label>Cliente</label>
                <select class="form-control m-b input-sm" id="cmb_ClienteId">
                  <option value="">-- Seleccionar --</option>
                </select>
              </div>

              <div class="controls col-lg-3">
                <label>Fechas inicio reserva</label>
                <span class="input-icon inverted">
                  <input type="text" class="form-control input-sm" name="RangoFecha" id="dtp_FechasReserva" value=""/>
                  <i class="fa fa-calendar bg-blue"></i>
                </span>
              </div>

              <div class="col-lg-3">
                <label>Estado comercial</label>
                <select class="form-control m-b input-sm" id="cmb_EstadoComercialId">
                  <option value="">-- Seleccionar --</option>
                  <option value="PP">Pendiente de pago</option>
                  <option value="PA">Pagada</option>
                </select>
              </div>
            </div>

            <button type="button" id="btn_Buscar" class="btn btn-info btn-sm pull-right"><i class="fa fa-search"></i> Buscar</button>
            <br>
            <hr class="wide">

            <div class="form-group row">
              <div class="col-lg-3 col-sm-3">
                <?php if($arr_Permisos[0]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
                <button type="button" id="btn_Nueva" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Nueva</a>
                <?php } ?>
              </div>
            </div>

            <table id="tbl_Reservas" class="table table-striped table-bordered table-hover" style="width:100%;">
              <thead>
                <tr>
                  <th>Nro. de reserva</th>
                  <th>Cabaña</th>
                  <th>Fecha/Hora Llegada</th>
                  <th>Fecha/Hora Salida</th>
                  <th>Cliente</th>
                  <th>Teléfono Celular</th>
                  <th>Estado comercial</th>
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

<div id="mdl_PagarReserva" class="modal fade" aria-hidden="true" data-backdrop="static" data-backdrop="false">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<form id="frm_PagarReserva">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h5 class="modal-title">Pagar reserva</h5>
				</div>
				<div class="modal-body">
          <div class="row">
            <div class="col-md-7">
              <h5><b>Estadía</b></h5>
              <div class="row">
                <div class="col-md-12">
                  <table id="tbl_DetallesPago" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Valor unitario</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <h5><b>Otros</b></h5>
              <div class="row">
                <div class="col-md-12">
                  <table id="tbl_OtrosPagos" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Categoría</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <hr class="wide">

          <h5><b>Pagos recibidos</b></h5>
          <div class="row">
            <div class="col-md-12">
              <table id="tbl_PagosEfectuados" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Categoría</th>
                    <th>Medio de pago</th>
                    <th>Monto</th>
                    <th>Comentario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
          <hr class="wide">

          <h5><b>Ingreso de pago</b></h5>
          <div class="row">
						<div class="col-md-12">
              <div class="form-group">
                <div class="col-md-2">
									<label for="fechapago">Fecha de pago[*]:</label>
                  <input type="date" id="dtp_FechaPago" class="form-control input-sm" required>
                </div>
                <div class="col-md-2">
									<label for="mediopago">Categoría[*]:</label>
                  <select id="cmb_CategoriaId" class="form-control input-sm" required>
                    <option value=''>-- Seleccionar --</option>
                  </select>
                </div>
                <div class="col-md-2">
									<label for="mediopago">Medio de pago[*]:</label>
                  <select id="cmb_MedioPagoId" class="form-control input-sm" required>
                    <option value=''>-- Seleccionar --</option>
                  </select>
                </div>
								<div class="col-md-2">
									<label for="totalpagar">Total a pagar[*]:</label>
                  <input type="number" id="txt_TotalPagar" class="form-control input-sm" required>
                </div>
                <div class="col-md-4">
                  <label for="comentario">Comentario:</label>
                  <textarea name="txt_Comentario" id="txt_Comentario" class="form-control input-sm" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>
				</div>
				<div class="modal-footer">
					<button id="btn_GuardarPagar" type="submit" class="btn btn-success btn-sm"><i class="fa fa-credit-card"></i> Agregar</button>
					<button id="btn_CheckOut" type="button" class="btn btn-magenta btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> CheckOut</button>
					<button id="btn_SalirPagar" type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include("modals/mdl_ValoracionCliente.php"); //MODAL PARA VALORAR AL CLIENTE ?>
<?php include("modals/mdl_Reservar.php"); //MODAL PARA RESERVAR ?>
