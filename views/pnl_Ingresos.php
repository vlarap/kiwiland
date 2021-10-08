<?php
  session_start();
  $arr_Permisos = $_SESSION['arr_Permisos'];
  if($arr_Permisos[1]['permiso_Leer'] == 'I'){
    header("Location: ../index.php");
    exit();
  }
?>
<script type="text/javascript" src="controllers/controller_pnl_Ingresos.js"></script>

<div class="wrapper wrapper-content">
  <form>
    <div class="row">
      <div class="col-lg-12">
        <div class="widget">
          <div class="widget-header bordered-bottom bordered-blue">
            <span class="widget-caption">Ingresos</span>
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
                <select class="form-control m-b input-sm" id="cmb_ClienteId">
                  <option value="">-- Seleccionar --</option>
                </select>
              </div>

              <div class="controls col-lg-3">
                <label>Rango de fechas</label>
                <span class="input-icon inverted">
                  <input type="text" class="form-control input-sm" name="RangoFecha" id="dtp_RangoFecha" value=""/>
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
                    <span class="databox-text" style="font-size: 15px;" id="lbl_IngresosTotales"></span>
                  </div>
                  <div class="databox-bottom text-align-center" style="height: auto !important;">
                    <span class="databox-text">Ingresos totales</span>
                  </div>
                </div>
              </div>
            </div>

            <table id="tbl_Ingresos" class="table table-striped table-bordered table-hover" style="width:100%;">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Tipo de Docto.</th>
                  <th>Nro. de Docto.</th>
                  <th>Categoria</th>
                  <th>Medio de pago</th>
                  <th>Cabaña</th>                  
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
  </form>
</div>
