<script type="text/javascript" src="controllers/modals/controller_mdl_ValoracionCliente.js"></script>

<div id="mdl_ValoracionCliente" class="modal fade" aria-hidden="true" data-backdrop="static" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="frm_ValoracionCliente">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h5 class="modal-title">Valorar a cliente</h5>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-md-12">
              <div class="form-group">
                <div class="col-md-3">
									<label>Puntaje cliente:</label>
                  <select id="cmb_ValoracionPuntaje" class="form-control input-sm" required>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                  </select>
                </div>
								<div class="col-md-9">
									<label>Observación:</label>
                  <textarea id="txt_ValoracionObservacion" class="form-control input-sm" rows="5" required></textarea>
                </div>
              </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button id="btn_GuardarValoracion" type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar</button>
					<button id="btn_SalirValoracion" type="button" class="btn btn-deafult btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
				</div>
			</form>
		</div>
	</div>
</div>
