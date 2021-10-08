<?php
	define('FPDF_FONTPATH', '../../../includes/font/');
	require("../../../includes/fpdf.php");
	require("../../../core/fn_Generales.php");

	class inf_Simulacion extends FPDF {
		//VARIABLES GENERALES
		var $colonnes;
		var $angle=0;
		var $sql_db;
		var $pvi_Id;
		var $vgi_NumDetalles = 52;
		var $numhojas = 0;

		//VARIABLES CABEZAL
		var $vgd_Fecha;
		var $vgs_LoteInterno;
		var $vgi_PrecioTerrenoUF;
		var $vgi_MtsCuadrados;
		var $vgi_PrecioMtCuadradoUF;
		var $vgi_ValorUF;
		var $vgi_PrecioTotal;
		var $vgi_PieTerrenoUF;
		var $vgi_PieTerrenoCLP;
		var $vgi_SaldoTerrenoUF;
		var $vgi_CuotasPie;
		var $vgi_CuotasTerreno;

		function fn_CargarCabecera(){
			$sql_Consulta = "SELECT DATE(simulacion_FechaCrea) AS simulacion_Fecha,
															terreno_LoteInterno,
															simulacion_PrecioTerrenoUF,
															simulacion_MtsCuadrados,
															simulacion_PrecioMtCuadradoUF,
															simulacion_ValorUF,
															simulacion_PrecioTotal,
															simulacion_PieTerrenoUF,
															simulacion_PieTerrenoCLP,
															simulacion_SaldoTerrenoUF,
															simulacion_CuotasPie,
															simulacion_CuotasTerreno
											 FROM 	".SIM_SIMULACIONES." A	INNER JOIN ".MAE_TERRENOS." B
											 ON			A.terreno_Id = B.terreno_Id
											 WHERE	A.simulacion_Id = " . $this->pvi_Id;
			$sql_Query 	= $this->sql_db->query($sql_Consulta);
			$sql_Fila 	= $sql_Query->fetch(PDO::FETCH_OBJ);

			$this->vgd_Fecha 							= $sql_Fila->simulacion_Fecha;
			$this->vgs_LoteInterno				= $sql_Fila->terreno_LoteInterno;
			$this->vgi_PrecioTerrenoUF		= $sql_Fila->simulacion_PrecioTerrenoUF;
			$this->vgi_MtsCuadrados				= $sql_Fila->simulacion_MtsCuadrados;
			$this->vgi_PrecioMtCuadradoUF	=	$sql_Fila->simulacion_PrecioMtCuadradoUF;
			$this->vgi_ValorUF						= $sql_Fila->simulacion_ValorUF;
			$this->vgi_PrecioTotal				= $sql_Fila->simulacion_PrecioTotal;
			$this->vgi_PieTerrenoUF				= $sql_Fila->simulacion_PieTerrenoUF;
			$this->vgi_PieTerrenoCLP			= $sql_Fila->simulacion_PieTerrenoCLP;
			$this->vgi_SaldoTerrenoUF			= $sql_Fila->simulacion_SaldoTerrenoUF;
			$this->vgi_CuotasPie					= $sql_Fila->simulacion_CuotasPie;
			$this->vgi_CuotasTerreno			= $sql_Fila->simulacion_CuotasTerreno;
		}

		function fn_CargarHeader(){
			$this->Cell(50, 4 ,$this->Image('../../../img/encabezado_qmo.png',$this->GetX(), $this->GetY(), 220));		//LOGO DE EMPRESA

			$this->SetXY(172, 12);
			$this->SetFont("Helvetica", "B", 12);
			$this->Cell(40, 6, utf8_decode("Nro. " . $this->pvi_Id), 1, 0, "C");																				//NRO. INFORME

			$this->SetXY(0, 35);
			$this->SetFont("Helvetica", "B", 16);
			$this->Cell(216, 6, utf8_decode("Simulación Terreno"), 0, 0, "C");																							//TITULO INFORME

			$vli_X = 5;
			$vli_Y = 45;

			$this->SetXY($vli_X, $vli_Y);
			$this->SetFont("Helvetica", "B", 10);
			$this->Cell(50, 6, utf8_decode("Fecha de simulación:"), 0, 0, "L");

			$this->SetXY($vli_X+40, $vli_Y);
			$this->SetFont("Helvetica", "", 10);
			$this->Cell(50, 6, fn_TransformarFechaSQL($this->vgd_Fecha), 0, 0, "L");

			$this->SetXY($vli_X+80, $vli_Y);
			$this->SetFont("Helvetica", "B", 10);
			$this->Cell(50, 6, utf8_decode("Lote Interno:"), 0, 0, "L");

			$this->SetXY($vli_X+105, $vli_Y);
			$this->SetFont("Helvetica", "", 10);
			$this->Cell(50, 6, $this->vgs_LoteInterno, 0, 0, "L");

			/* COLUMNA 1 DE DATOS SOLICITADOS */
			$this->SetXY($vli_X, $vli_Y+10);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("PRECIO TERRENO"), 1, 0, "L");

			$this->SetXY($vli_X+40, $vli_Y+10);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_PrecioTerrenoUF), 1, 0, "L");

			$this->SetXY($vli_X+90, $vli_Y+10);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "UF", 1, 0, "C");

			$this->SetXY($vli_X, $vli_Y+15);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("METROS CUADRADOS"), 1, 0, "L");

			$this->SetXY($vli_X+40, $vli_Y+15);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_MtsCuadrados), 1, 0, "L");

			$this->SetXY($vli_X+90, $vli_Y+15);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "M2", 1, 0, "C");

			$this->SetXY($vli_X, $vli_Y+20);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("PRECIO M2"), 1, 0, "L");

			$this->SetXY($vli_X+40, $vli_Y+20);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_PrecioMtCuadradoUF), 1, 0, "L");

			$this->SetXY($vli_X+90, $vli_Y+20);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "UF", 1, 0, "C");

			$this->SetXY($vli_X, $vli_Y+25);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("VALOR UF"), 1, 0, "L");

			$this->SetXY($vli_X+40, $vli_Y+25);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_ValorUF), 1, 0, "L");

			$this->SetXY($vli_X+90, $vli_Y+25);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "CLP", 1, 0, "C");

			$this->SetXY($vli_X, $vli_Y+30);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("PRECIO TOTAL"), 1, 0, "L");

			$this->SetXY($vli_X+40, $vli_Y+30);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_PrecioTotal), 1, 0, "L");

			$this->SetXY($vli_X+90, $vli_Y+30);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "CLP", 1, 0, "C");

			/* COLUMNA 2 DE DATOS SOLICITADOS */
			$this->SetXY($vli_X+107, $vli_Y+10);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("PIE TERRENO"), 1, 0, "L");

			$this->SetXY($vli_X+147, $vli_Y+10);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_PieTerrenoUF), 1, 0, "L");

			$this->SetXY($vli_X+197, $vli_Y+10);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "UF", 1, 0, "C");

			$this->SetXY($vli_X+107, $vli_Y+15);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("PIE TERRENO"), 1, 0, "L");

			$this->SetXY($vli_X+147, $vli_Y+15);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_PieTerrenoCLP), 1, 0, "L");

			$this->SetXY($vli_X+197, $vli_Y+15);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "CLP", 1, 0, "C");

			$this->SetXY($vli_X+107, $vli_Y+20);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("SALDO TERRENO"), 1, 0, "L");

			$this->SetXY($vli_X+147, $vli_Y+20);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(50, 5, fn_SacarDecimales($this->vgi_SaldoTerrenoUF), 1, 0, "L");

			$this->SetXY($vli_X+197, $vli_Y+20);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(10, 5, "UF", 1, 0, "C");

			$this->SetXY($vli_X+107, $vli_Y+25);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("NRO. CUOTAS PIE"), 1, 0, "L");

			$this->SetXY($vli_X+147, $vli_Y+25);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(60, 5, $this->vgi_CuotasPie, 1, 0, "L");

			$this->SetXY($vli_X+107, $vli_Y+30);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(40, 5, utf8_decode("NRO. CUOTAS TERRENO"), 1, 0, "L");

			$this->SetXY($vli_X+147, $vli_Y+30);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(60, 5, $this->vgi_CuotasTerreno, 1, 0, "L");

			/*ENCABEZADO DE COLUMNAS */
			$this->SetXY($vli_X, $vli_Y+38);
			$this->SetFont("Helvetica", "B", 9);
			$this->Cell(100, 7, utf8_decode("CUOTAS PIE"), 0, 0, "C");

			$this->SetXY($vli_X+107, $vli_Y+38);
			$this->SetFont("Helvetica", "B", 9);
			$this->Cell(100, 7, utf8_decode("CUOTAS TERRENO"), 0, 0, "C");
		}

	  function fn_CargarDetalles(){
			$y = 100;
			$z = 100;

			$this->SetFont('Helvetica', 'B', 8);
			$this->SetTextColor(0,0,0);

			$colsca=array("#" => 8, "CUOTA UF" => 30, "ACUMULADA UF"=> 31, "CUOTA CLP" => 31);
			$this->fn_AgregarColumnaDetallesIzq($colsca);

			$colsca=array("#" => 8, "CUOTA UF" => 30, "ACUMULADA UF"=> 31, "CUOTA CLP" => 31);
			$this->fn_AgregarColumnaDetallesDer($colsca);

			//Posiciona los nombres del cabezal.
			$cols=array("#" => "C", "CUOTA UF" => "R", "ACUMULADA UF" => "R", "CUOTA CLP" => "R");
			$this->fn_AgregarFormatoColumna($cols);

			$sql_Consulta = "SELECT simDetalle_NroCuota,
															simDetalle_CuotaUF,
															simDetalle_AcumuladaUF,
															simDetalle_CuotaCLP,
															simDetalle_Tipo
											 FROM 	".SIM_SIMULACIONDETALLES." A
											 WHERE	A.simulacion_Id = " . $this->pvi_Id;
			$sql_Query 	= $this->sql_db->query($sql_Consulta);

			$this->SetFont('Helvetica', '', 8);
			$this->SetTextColor(0,0,0);
			$i = 0;
			while($sql_Fila 	= $sql_Query->fetch(PDO::FETCH_OBJ)){
				if($i > $this->vgi_NumDetalles){
					$this->AddPage();
					$this->fn_CargarCabecera();
					$this->fn_CargarHeader();
					$i = 0;
					$y = 100;
					$z = 100;

					$this->SetFont('Helvetica', '', 8);
					$this->SetTextColor(0,0,0);
					$this->fn_AgregarColumnaDetallesIzq($colsca);
					$this->fn_AgregarColumnaDetallesDer($colsca);
					$this->fn_AgregarFormatoColumna($cols);
				}

				$line = $colsde=array("#" 						=> $sql_Fila->simDetalle_NroCuota,
															"CUOTA UF" 			=> fn_SacarDecimales($sql_Fila->simDetalle_CuotaUF),
															"ACUMULADA UF"	=> fn_SacarDecimales($sql_Fila->simDetalle_AcumuladaUF),
															"CUOTA CLP" 		=> fn_SacarDecimales($sql_Fila->simDetalle_CuotaCLP));

				if($sql_Fila->simDetalle_Tipo == 'CP'){
					$size = $this->fn_AgregarLineaDetallesIzq($y, $line);
					$y += $size + 3;
				}else{
					$size = $this->fn_AgregarLineaDetallesDer($z, $line);
					$z += $size + 3;
					$i ++;
				}
			}
		}

		function fn_AgregarColumnaDetallesIzq($tab){
			global $colonnes;

			$r1 = 5;
			$r2 = $this->w-116;
			$y1 = 90;
			$y2 = $this->h-5-$y1;
			$this->SetFillColor(0,99,163);
			$this->SetXY($r1, $y1);
			$this->Rect($r1, $y1, $r2, $y2, "D");
			$this->Line( $r1, $y1+6, $r1+$r2, $y1+6);
			$colX = $r1;
			$colonnes = $tab;
			while(list($lib, $pos) = each ($tab)){
				$this->SetXY($colX, $y1+3);
				$this->Cell($pos, 1, utf8_decode($lib), 0, 0, "C");
				$colX += $pos;
				$this->Line($colX, $y1, $colX, $y1+$y2);
			}
		}

		function fn_AgregarColumnaDetallesDer($tab){
			global $colonnes;

			$r1 = 112;
			$r2 = $this->w-116;
			$y1 = 90;
			$y2 = $this->h-5-$y1;
			$this->SetFillColor(0,99,163);
			$this->SetXY($r1, $y1);
			$this->Rect($r1, $y1, $r2, $y2, "D");
			$this->Line( $r1, $y1+6, $r1+$r2, $y1+6);
			$colX = $r1;
			$colonnes = $tab;
			while(list($lib, $pos) = each ($tab)){
				$this->SetXY($colX, $y1+3);
				$this->Cell($pos, 1, utf8_decode($lib), 0, 0, "C");
				$colX += $pos;
				$this->Line($colX, $y1, $colX, $y1+$y2);
			}
		}

		function fn_AgregarFormatoColumna($tab){
			global $format, $colonnes;

			while(list($lib, $pos) = each($colonnes)){
				if(isset($tab["$lib"]))
					$format[$lib] = $tab["$lib"];
			}
		}

		function fn_AgregarLineaDetallesIzq($ligne, $tab){
			global $colonnes, $format;

			$ordonnee = 5;
			$maxSize = $ligne;
			$borde=0;
			$tamanio=2;
			reset($colonnes);
			while(list($lib, $pos) = each($colonnes)){
				$longCell = $pos-0;
				$texte = $tab[$lib];
				$length = $this->GetStringWidth($texte);
				$tailleTexte = $this->sizeOfText($texte, $length);
				$formText = $format[$lib];
				$this->SetXY($ordonnee, $ligne-1);
				$this->MultiCell($longCell, $tamanio , $texte, $borde, $formText);
				if($maxSize < ($this->GetY()))
					$maxSize = $this->GetY();
				$ordonnee += $pos;
			}

			return ($maxSize-$ligne);
		}

		function fn_AgregarLineaDetallesDer($ligne, $tab){
			global $colonnes, $format;

			$ordonnee = 112;
			$maxSize = $ligne;
			$borde=0;
			$tamanio=2;
			reset($colonnes);
			while(list($lib, $pos) = each($colonnes)){
				$longCell = $pos-0;
				$texte = $tab[$lib];
				$length = $this->GetStringWidth($texte);
				$tailleTexte = $this->sizeOfText($texte, $length);
				$formText = $format[$lib];
				$this->SetXY($ordonnee, $ligne-1);
				$this->MultiCell($longCell, $tamanio , $texte, $borde, $formText);
				if($maxSize < ($this->GetY()))
					$maxSize = $this->GetY();
				$ordonnee += $pos;
			}

			return ($maxSize-$ligne);
		}

		function sizeOfText($texte, $largeur){
			$index    = 0;
			$nb_lines = 0;
			$loop     = TRUE;
			while($loop){
				$pos = strpos($texte, "\n");
				if(!$pos){
					$loop  = FALSE;
					$ligne = $texte;
				}else{
					$ligne  = substr($texte, $index, $pos);
					$texte = substr($texte, $pos+1);
				}
				$length = floor($this->GetStringWidth($ligne));
				$res = 1+floor($length/$largeur);
				$nb_lines += $res;
			}
			return $nb_lines;
		}

		function Footer(){
			$vli_X = 5;
			$vli_Y = 222;

			/* TOTAL FOOTER */
			/*$this->SetXY($vli_X+137, $vli_Y);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(35, 7, utf8_decode("TOTAL $"), 1, 0, "C");

			$this->SetXY($vli_X+172, $vli_Y);
			$this->SetFont("Helvetica", "B", 8);
			$this->Cell(35, 7, "", 1, 0, "C");*/
		}

		function _endpage(){
			if($this->angle!=0){
				$this->angle=0;
				$this->_out('Q');
			}
			parent::_endpage();
		}
	}
?>
