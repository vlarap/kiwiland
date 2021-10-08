<?php
  function fn_FormatearRut($pvs_Rut){
    $vls_RutFinal = '';
    if(strlen($pvs_Rut) == 9){
      $vls_RutFinal = substr($pvs_Rut, 0, 2) . "." . substr($pvs_Rut, 2, 3) . "." . substr($pvs_Rut, 5, 3) . "-" . substr($pvs_Rut, 8, 1);
    }else if(strlen($pvs_Rut) == 8){
      $vls_RutFinal = substr($pvs_Rut, 0, 1) . "." . substr($pvs_Rut, 1, 3) . "." . substr($pvs_Rut, 4, 3) . "-" . substr($pvs_Rut, 7, 1);
    }else{
      $vls_RutFinal = $pvs_Rut;
    }

    return $vls_RutFinal;
  }

  function fn_TransformarEstado($pvs_Estado){
    $pvs_EstadoFinal = '';
    if($pvs_Estado == 'A'){
      $pvs_EstadoFinal = '<center><i title="Activo" style="color:green;" class="glyphicon glyphicon-ok-sign"></i></center>';
    }else if($pvs_Estado == 'D'){
      $pvs_EstadoFinal = '<center><i title="Desactivado" style="color:red;" class="glyphicon glyphicon-remove-sign"></i></center>';
    }

    return $pvs_EstadoFinal;
  }

  function fn_CambiarEstado($pvs_Estado, $pvs_Tabla, $pvs_Columna, $pvs_Condicion, $pvi_Id){
    global $sql_DB;

    $sql_CambiarEstado = "UPDATE  ".$pvs_Tabla."
                          SET     ".$pvs_Columna." = '".$pvs_Estado."'
                          WHERE   ".$pvs_Condicion." = ".$pvi_Id;

    try{
      $sql_Query = $sql_DB->query($sql_CambiarEstado);

      echo 1;
    }catch (PDOException $e){
      echo $e->getMessage();
    }
  }

  function fn_TransformarFechaSQL($pvd_Fecha){
		$vld_FechaExplode  = explode('-',$pvd_Fecha);
		$vld_FechaFinal    = $vld_FechaExplode[2].'-'.$vld_FechaExplode[1].'-'.$vld_FechaExplode[0];

    return $vld_FechaFinal;
  }

  function fn_SacarDecimales($pvi_Valor){
    $vli_Partes = explode(".", $pvi_Valor);
    if ($vli_Partes[1] == 0) {
      return $vli_Partes[0];
    }else{
      return $pvi_Valor;
    }
  }

  setlocale(LC_MONETARY, 'es_CL');
  function money_format2($format, $number){
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
    if (setlocale(LC_MONETARY, 0) == 'C') {
        setlocale(LC_MONETARY, '');
    }
    $locale = localeconv();
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
    foreach ($matches as $fmatch) {
        $value = floatval($number);
        $flags = array(
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                           $match[1] : ' ',
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                           $match[0] : '+',
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
        );
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
        $conversion = $fmatch[5];

        $positive = true;
        if ($value < 0) {
            $positive = false;
            $value  *= -1;
        }
        $letter = $positive ? 'p' : 'n';

        $prefix = $suffix = $cprefix = $csuffix = $signal = '';

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
        switch (true) {
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                $prefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                $suffix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                $cprefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                $csuffix = $signal;
                break;
            case $flags['usesignal'] == '(':
            case $locale["{$letter}_sign_posn"] == 0:
                $prefix = '(';
                $suffix = ')';
                break;
        }
        if (!$flags['nosimbol']) {
            $currency = $cprefix .
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                        $csuffix;
        } else {
            $currency = '';
        }
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

        $value = number_format($value, $right, $locale['mon_decimal_point'],
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
        $value = @explode($locale['mon_decimal_point'], $value);

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
        if ($left > 0 && $left > $n) {
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
        }
        $value = implode($locale['mon_decimal_point'], $value);
        if ($locale["{$letter}_cs_precedes"]) {
            $value = $prefix . $currency . $space . $value . $suffix;
        } else {
            $value = $prefix . $value . $space . $currency . $suffix;
        }
        if ($width > 0) {
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                     STR_PAD_RIGHT : STR_PAD_LEFT);
        }

        $format = str_replace($fmatch[0], $value, $format);
    }
    return $format;
  }
?>
