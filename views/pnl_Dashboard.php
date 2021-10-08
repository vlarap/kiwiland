<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
?>
<script type="text/javascript" src="controllers/controller_Index.js"></script>

<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<h5 class="row-title before-darkorange"><i class="fa fa-th-list darkorange"></i> DASHBOARD - RESERVAS </h5>
      </div>
      <div class="col-md-6">
				<div class="btn-group pull-right">
					<?php if($arr_Permisos[0]['permiso_Crear'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
          <button id="btn_NuevaReserva" type="button" class="btn btn-primary btn-sm" tooltip="Nueva reserva"><i class="fa fa-plus"></i> Nueva</button>
					<?php } ?>
				</div>
			</div>
		</div>
		<!--Calendario de Disponibilidad-->
		<div class="well with-header with-footer">
			<div class="header bordered-primary">
				<h4>Calendario de Disponibilidad</h4>
			</div>
			<div>
				<div class="row">
					<div class="col-md-12">
						<ul class="list-inline">
							<li><div style="background-color: #80b369; display: inline;">&nbsp;&nbsp;&nbsp;&nbsp;</div> Disponible</li>
							<li><div style="background-color: #335086; display: inline;">&nbsp;&nbsp;&nbsp;&nbsp;</div> Reserva</li>
							<li><div style="background-color: #da5350; display: inline;">&nbsp;&nbsp;&nbsp;&nbsp;</div> Ocupada</li>
							<li><div style="background-color: #ffe102; display: inline;">&nbsp;&nbsp;&nbsp;&nbsp;</div> Mantención</li>
						</ul>
					</div>
				</div>
				<div class="row"><!--Listado-->
					<div class="col-md-12">
						<div style="height: auto;">
							<div id="abAvailability_95585" class="abAvailability">
								<div class="abErrorMessage" style="display: none"></div>
								<div class="abCal-container abCal-row">
									<div class="abCal-calendars">
										<div id="pnl_ListadoPropiedades"></div>
									</div>
									<div class="abCal-dates">
										<div class="abCal-scroll">
											<div>
												<div class="abCal-head">
													<div class="abCal-head-row" id="pnl_TituloCalendario"></div>
													<div class="abCal-head-row" id="pnl_Dias"></div>
												</div>
												<div id="pnl_Disponibilidad"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer"></div>
		</div>
		<!--Fin Calendario de Disponibilidad-->


		<!--Listado de Llegadas/Salidas-->
		<div class="row">
			<div class="col-md-6">
				<!--Llegadas-->
				<div class="well with-header with-footer">
					<div class="header bordered-primary">
						<h4>Próximas Llegadas (Check-In)</h4>
					</div>
					<div>
						<div class="row"><!--Listado-->
							<div class="col-md-12 table-responsive">
								<table id="tbl_CheckIn" class="table table-hover table-condensed display nowrap headestilo" width="100%">
									<thead class="bordered-darkorange">
										<tr>
											<th>Cabaña</th>
											<th>Fecha</th>
											<th>Hora</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="footer"></div>
				</div>
			</div>
			<div class="col-md-6">
				<!--Salidas-->
				<div class="well with-header with-footer">
					<div class="header bordered-primary">
						<h4>Próximas Salidas (Check-Out)</h4>
					</div>
					<div>
						<div class="row"><!--Listado-->
							<div class="col-md-12 table-responsive">
								<table id="tbl_CheckOut" class="table table-hover table-condensed display nowrap headestilo" width="100%">
									<thead class="bordered-darkorange">
										<tr>
											<th>Cabaña</th>
											<th>Fecha</th>
											<th>Hora</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="footer"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include("modals/mdl_ValoracionCliente.php"); //MODAL PARA VALORAR AL CLIENTE ?>
<?php include("modals/mdl_Reservar.php"); //MODAL PARA RESERVAR ?>
