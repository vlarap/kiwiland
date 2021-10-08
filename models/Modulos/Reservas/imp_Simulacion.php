<?php
	include('../../../includes/config.php');
  include('../../../includes/database.php');
	require('inf_Simulacion.php');

	$vli_Id = base64_decode($_GET["id"]);
  global $sql_DB;

	$vlARR_formato = array(216,330); //Tamaño oficio
	$pdf = new inf_Simulacion("P", "mm", $vlARR_formato);
	$pdf->sql_db = $sql_DB;
	$pdf->pvi_Id = $vli_Id;

	/*CONFIGURACIONES GENERALES*/
	$pdf->SetMargins(0.0,0.0,0.0);
	$pdf->AddFont('sans_serif', '', 'sans_serif.php');
	$pdf->SetFont('sans_serif', '', 9);

	$pdf->fn_CargarCabecera();
	$pdf->Open();
	$pdf->AddPage();
	$pdf->fn_CargarHeader();
	$pdf->fn_CargarDetalles();
	$pdf->Output();
?>
