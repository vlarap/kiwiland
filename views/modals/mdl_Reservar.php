<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
?>
<script type="text/javascript" src="controllers/modals/controller_mdl_Reservar.js"></script>

<div id="mdl_Reserva" class="modal fade" aria-hidden="true" data-backdrop="static" tabindex="-1" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="frm_Reserva">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h5 class="modal-title" id="lbl_TituloModal"></h5>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="idtexto" class="col-md-5 control-label no-padding-right">Nro. Reserva:</label>
												<div class="col-md-7">
													<input type="text" class="form-control input-sm text-important text-center" id="txt_ReservaId" readonly />
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="fecha_crea" class="col-md-5 control-label no-padding-right">Fecha:</label>
												<div class="col-md-7">
													<input type="text" class="form-control input-sm text-important text-center" id="txt_ReservaFechaCrea" readonly />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="estado_idtexto" class="col-md-5 control-label no-padding-right">Estado:</label>
												<div class="col-md-7">
													<input type="text" class="form-control input-sm text-important text-center" id="txt_ReservaEstado" readonly />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-subtitulo">DATOS PERSONALES</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="cliente_id" class="col-md-4 control-label no-padding-right">Cliente:</label>
										<div class="col-md-8">
											<select id="cmb_ClienteIdMDL" class="form-control input-sm sinvalidacion">
												<option value=''>-- Seleccionar --</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
                <div class="col-md-6">
									<fieldset>
										<legend>Tipo de cliente</legend>
										<div class="row">
											<div class="col-md-12">
												<div class="control-group">
													<div class="radio">
														<label class="radio-inline col-md-5">
															<input type="radio" id="rb_Persona" value="P" />
															<span class="text">Persona</span>
														</label>
														<label class="radio-inline col-md-5">
															<input type="radio" id="rb_Empresa" value="E" />
															<span class="text">Empresa</span>
														</label>
													</div>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
								<div class="col-md-6">
									<fieldset>
										<legend>Nacionalidad</legend>
										<div class="row">
											<div class="col-md-12">
												<div class="control-group">
													<div class="radio">
														<label class="radio-inline col-md-5">
															<input type="radio" id="rb_Chileno" value="1" />
															<span class="text">Chileno</span>
														</label>
														<label class="radio-inline col-md-5">
															<input type="radio" id="rb_Extranjero" value="2" />
															<span class="text">Extranjero</span>
														</label>
													</div>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
							<fieldset>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="rut" class="col-md-4 control-label no-padding-right">RUT:</label>
											<div class="col-md-8">
												<input type="text" class="form-control input-sm" id="txt_ClienteRut"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="nombre" class="col-md-4 control-label no-padding-right" id="lbl_ClienteNombres">Nombres:[*]</label>
											<div class="col-md-8">
												<input type="text" class="form-control input-sm" id="txt_ClienteNombres" required/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="apaterno" class="col-md-4 control-label no-padding-right" id="lbl_ClienteApellidoPaterno">Ap. Paterno:[*]</label>
											<div class="col-md-8">
												<input type="text" class="form-control input-sm" id="txt_ClienteApellidoPaterno" required/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="amaterno" class="col-md-4 control-label no-padding-right" id="lbl_ClienteApellidoMaterno">Ap. Materno:</label>
											<div class="col-md-8" id="pnl_ClienteApellidoMaterno">
												<input type="text" class="form-control input-sm" id="txt_ClienteApellidoMaterno" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="pais_id" class="col-md-4 control-label no-padding-right">País:</label>
											<div class="col-md-8">
												<select id="cmb_PaisId" class="form-control input-sm">
													<option value=''>-- Seleccionar --</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="region_id" class="col-md-4 control-label no-padding-right">Región:</label>
											<div class="col-md-8">
												<select id="cmb_RegionId" class="form-control input-sm">
													<option value=''>-- Seleccionar --</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="ciudad_id" class="col-md-4 control-label no-padding-right">Ciudad:</label>
											<div class="col-md-8">
												<select id="cmb_CiudadId" class="form-control input-sm">
													<option value=''>-- Seleccionar --</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="comuna_id" class="col-md-4 control-label no-padding-right">Comuna:</label>
											<div class="col-md-8">
												<select id="cmb_ComunaId" class="form-control input-sm">
													<option value=''>-- Seleccionar --</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="direccion" class="col-md-4 control-label no-padding-right">Dirección:</label>
											<div class="col-md-8">
												<input type="text" class="form-control input-sm" id="txt_ClienteDireccion" />
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="email" class="col-md-4 control-label no-padding-right">E-Mail:[*]</label>
											<div class="col-md-8">
												<input type="email" class="form-control input-sm" id="txt_ClienteCorreoElectronico" required/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="fono1" class="col-md-4 control-label no-padding-right">Teléfono Fijo:</label>
											<div class="col-md-8">
												<input type="text" class="form-control input-sm" id="txt_ClienteTelefonoFijo" />
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="fono2" class="col-md-4 control-label no-padding-right">Celular:[*]</label>
											<div class="col-md-8">
												<input type="text" class="form-control input-sm" id="txt_ClienteCelular" required/>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<div class="form-subtitulo">RESERVA</div>
							<div class="row">
								<div class="col-md-6">
									<fieldset>
										<legend>Propiedad</legend>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="propiedad_id" class="col-md-4 control-label no-padding-right">Propiedad:[*]</label>
													<div class="col-md-8">
														<select id="cmb_PropiedadId" class="form-control input-sm" required>
															<option value=''>-- Seleccionar --</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="row">
								<div class="col-md-8">
									<fieldset>
										<legend>Fechas</legend>
										<div class="row">
											<div class="col-md-7">
												<div class="form-group">
													<label for="llegadafecha" class="col-md-5 control-label no-padding-right">Fecha llegada:[*]</label>
													<div class="col-md-7">
														<input type="date" id="dtp_FechaDesde" class="form-control input-sm" required>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group">
													<label for="llegadahora" class="col-md-6 control-label no-padding-right">Hora llegada:[*]</label>
													<div class="col-md-6">
														<select id="dtp_HoraLlegada" class="form-control input-sm" required>
															<option value='14:00'>14:00</option>
															<option value='14:30'>14:30</option>
															<option value='15:00'>15:00</option>
															<option value='15:30'>15:30</option>
															<option value='16:00'>16:00</option>
															<option value='16:30'>16:30</option>
															<option value='17:00'>17:00</option>
															<option value='17:30'>17:30</option>
															<option value='18:00'>18:00</option>
															<option value='18:30'>18:30</option>
															<option value='19:00'>19:00</option>
															<option value='19:30'>19:30</option>
															<option value='20:00'>20:00</option>
															<option value='20:30'>20:30</option>
															<option value='21:00'>21:00</option>
															<option value='21:30'>21:30</option>
															<option value='22:00'>22:00</option>
															<option value='22:30'>22:30</option>
															<option value='23:00'>23:00</option>
															<option value='23:30'>23:30</option>
														</select>
													</div>
													<!-- <div class="col-md-6">
														<input type="time" id="dtp_HoraLlegada" class="form-control input-sm">
													</div> -->
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-7">
												<div class="form-group">
													<label for="salidafecha" class="col-md-5 control-label no-padding-right">Fecha salida:[*]</label>
													<div class="col-md-7">
														<input type="date" id="dtp_FechaHasta" class="form-control input-sm" required>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group">
													<label for="salidahora" class="col-md-6 control-label no-padding-right">Hora salida:[*]</label>
													<div class="col-md-6">
														<select id="dtp_HoraSalida" class="form-control input-sm" required>
															<option value='12:00'>12:00</option>
															<option value='17:00'>17:00</option>
														</select>
													</div>
													<!-- <div class="col-md-6">
														<input type="time" id="dtp_HoraSalida" class="form-control input-sm">
													</div> -->
												</div>
											</div>
										</div>
									</fieldset>
								</div>
								<div class="col-md-4">
									<fieldset>
										<legend>Cantidad</legend>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="adultosnro" class="col-md-5 control-label no-padding-right">Adultos:[*]</label>
													<div class="col-md-7">
														<select id="cmb_ReservaAdultos" class="form-control input-sm" required>
															<option value='1'>1</option>
															<option value='2'>2</option>
															<option value='3'>3</option>
															<option value='4'>4</option>
															<option value='5'>5</option>
															<option value='6'>6</option>
															<option value='7'>7</option>
															<option value='8'>8</option>
															<option value='9'>9</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="niniosnro" class="col-md-5 control-label no-padding-right">Niños:[*]</label>
													<div class="col-md-7">
														<select id="cmb_ReservaNinos" class="form-control input-sm" required>
															<option value='0'>0</option>
															<option value='1'>1</option>
															<option value='2'>2</option>
															<option value='3'>3</option>
															<option value='4'>4</option>
															<option value='5'>5</option>
															<option value='6'>6</option>
															<option value='7'>7</option>
															<option value='8'>8</option>
															<option value='9'>9</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
							<fieldset>
								<legend>Datos</legend>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="origen_id" class="col-md-4 control-label no-padding-right">Origen:[*]</label>
											<div class="col-md-8">
												<select id="cmb_OrigenId" class="form-control input-sm" required>
													<option value=''>-- Seleccionar --</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<?php if($arr_Permisos[0]['permiso_Crear'] == 'A' || $arr_Permisos[0]['permiso_Actualizar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
					<button id="btn_GuardarReserva" type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar</button>
					<button id="btn_ValorarCliente" type="button" class="btn btn-info btn-sm"><i class="fa fa-check-square-o"></i> Valorar</button>
					<?php } ?>
					<?php if($arr_Permisos[0]['permiso_Crear'] == 'A' || $arr_Permisos[0]['permiso_Eliminar'] == 'A' || $_SESSION['vgs_UsuarioNivel'] == 'ADM'){ ?>
					<button id="btn_EliminarReserva" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Eliminar</button>
					<?php } ?>
					<button id="btn_SalirReserva" type="button" class="btn btn-deafult btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
				</div>
			</form>
		</div>
	</div>
</div>
