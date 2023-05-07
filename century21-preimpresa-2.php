<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title></title>
</head>

<body>
	<?php
	$tax_5  = 0;
	$tax_10 = 0;
	
	$raw_date 	  = $_GET['date'];
	$raw_due_date = $_GET['due_date'];
	$cod_moneda = $_GET['currency_code'];
	
	$raw_total_monto = $_GET['total'];
	$raw_moneda		 = $_GET['currency_code'];
	$raw_terms 		 = $_GET['terms'];
	$raw_subtotal 	 = $_GET['sub_total'];
	$raw_tax_total 	 = $_GET['tax_total'];
	
	$line_items = explode('|', $_GET['line_items']);
	
	$moneda    		= ($raw_moneda == 'PYG') ? 'Gs.' : '$' ;
	$total_monto    = $moneda . ' ' . number_format($raw_total_monto, 0, ',', '.');
	$total_monto    = ($_GET['currency_code'] == 'PYG') ? number_format($raw_total_monto, 0, ',', '.') : number_format($raw_total_monto, 2, ',', '.');
	$total_palabras = strtoupper(trim(valorEnLetras($raw_total_monto)));
	$total_palabras_usd = strtoupper(trim(valorEnLetrasUSD($raw_total_monto,"dolares","y","centavos")));
	
	?>

    <div class="grid_1">
        <div class="titulo_divisor">
           </div>
            <div class="titulo_fecha">
            <h3><?php echo strftime('%d/%m/%Y', strtotime($_GET['date'])); ?></h3>
        </div>
        <div class="contado">
        <h3><?php echo ($raw_date == $raw_due_date) ? 'X' : '&nbsp;' ; ?></h3>
        </div>
        <div class="credito">
        <h3><?php echo ($raw_date != $raw_due_date) ? 'X' : '&nbsp;' ; ?></h3>
        </div>
        <div class="ruc_cliente">
            <h3> <?php echo $_GET['customer_ruc']; ?></h3>
        </div>
        <div class="cliente">
            <h3><?php echo $_GET['cliente']; ?></h3>
        </div>
      <div class="divisor"></div>
            <div class="cantidad">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php 
				$line_item = explode('~', $raw_item); 
				?>
				<h3><?php echo $line_item[2]; ?></h3>
				<h3 style="color: white;">-</h3>
			<?php } ?>
        </div>
        <div class="descripcion">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php 
				$line_item = explode('~', $raw_item); 
				?>
				<h3><?php echo $line_item[6]; ?></h3>
				<h3><?php echo $line_item[1]; ?></h3>
			<?php } ?>
        </div>
        <div class="precio_unit">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php 
				$line_item = explode('~', $raw_item); 
				?>
				  <h3><?php echo ($_GET['currency_code'] == 'PYG') ? number_format($line_item[3], 0, ',', '.') : number_format($line_item[3], 2, ',', '.') ?>
                <?php echo $moneda ?></h3>
            <h3 style="color: white;">-</h3>
			<?php } ?>
        </div>
        <div class="exentas">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php 
				$line_item = explode('~', $raw_item); 
				?>
				 <h3 <?php echo (strpos($line_item[5], 'Exenta') ? '' : 'style="color: white;"') ?>><?php echo (strpos($line_item[5], 'Exenta') ? ($_GET['currency_code'] == 'PYG') ? number_format($line_item[3], 0, ',', '.') : number_format($line_item[3], 2, ',', '.') : '-') ?>
                <?php echo (strpos($line_item[5], 'Exenta') ? $moneda : '') ?></h3>
            <h3 style="color: white;">-</h3>
			<?php } ?>
        </div>
        <div class="gravada_5">
        <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php 
				$line_item = explode('~', $raw_item); 
				?>
			 <h3 <?php echo (strpos($line_item[5], '5%') ? '' : 'style="color: white;"') ?>><?php echo (strpos($line_item[5], '5%') ? ($_GET['currency_code'] == 'PYG') ? number_format($line_item[2] * $line_item[3], 0, ',', '.') : number_format($line_item[2] * $line_item[3], 2, ',', '.') : '-') ?>
                <?php echo (strpos($line_item[5], '5%') ? $moneda : '') ?></h3>
            <h3 style="color: white;">-</h3>
			<?php } ?>
        </div>
        <div class="gravada_10">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php 
				$line_item = explode('~', $raw_item); 
				?>
			  <h3 <?php echo (strpos($line_item[5], '10%') ? '' : 'style="color: white;"')?>><?php echo (strpos($line_item[5], '10%') ? ($_GET['currency_code'] == 'PYG') ? number_format($line_item[2] * $line_item[3], 0, ',', '.') : number_format($line_item[2] * $line_item[3], 2, ',', '.') : '-') ?>
                <?php echo (strpos($line_item[5], '10%') ? $moneda : '') ?></h3>
            <h3 style="color: white;">-</h3>
			<?php } ?>
        </div>
      <div class="liq_iva_5">
        <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php
				$line_item = explode('~', $raw_item); 
				?>
				<?php $line_total = $line_item[2] * $line_item[3]; ?>
				<?php 				
				if(strpos($line_item[5], '10%') !== false){
					$tax_10 += $line_total - $line_item[4];
				}
				if(strpos($line_item[5], '5%') !== false){
					$tax_5 += $line_total - $line_item[4];
				}
				?>
			<?php } ?>
            <h3><?php echo $tax_5 == 0 ? '' : (($_GET['currency_code'] == 'PYG') ? number_format($tax_5, 0, ',', '.') : number_format($tax_5, 2, ',', '.')) ?>
                <?php echo $tax_5 == 0 ? '' : $moneda; ?></h3>
            <?php
				$line_total = 0;
				$tax_10 = 0;
				$tax_5 = 0;
			?>
        </div>
        <div class="liq_iva_10">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php
				$line_item = explode('~', $raw_item); 
				?>
				<?php $line_total = $line_item[2] * $line_item[3]; ?>
				<?php 				
				if(strpos($line_item[5], '10%') !== false){
					$tax_10 += $line_total - $line_item[4];
				}
				if(strpos($line_item[5], '5%') !== false){
					$tax_5 += $line_total - $line_item[4];
				}
				?>
			<?php } ?>
            <h3><?php echo $tax_10 == 0 ? '' : (($_GET['currency_code'] == 'PYG') ? number_format($tax_10, 0, ',', '.') : number_format($tax_10, 2, ',', '.'))?>
                <?php echo $tax_10 == 0 ? '' : $moneda;?></h3>
            <?php
				$line_total = 0;
				$tax_10 = 0;
				$tax_5 = 0;
			?>
        </div>
        <div class="valor_parcial_exenta">
        <?php foreach($line_items as $raw_item) { ?>
            <?php if($raw_item == '') { continue; } ?>
            <?php
				$line_item = explode('~', $raw_item); 
				?>
            <?php $line_total = $line_item[2] * $line_item[3]; ?>
            <?php 				
				if(strpos($line_item[5], 'Exenta') !== false){
					$exenta= $line_total;
				}
				?>
            <?php } ?>
            <h3><?php echo $exenta == 0 ? '' : (($_GET['currency_code'] == 'PYG') ? number_format($exenta, 0, ',', '.') : number_format($exenta, 2, ',', '.')) ?>
            <?php echo $exenta == 0 ? '' : $moneda?></h3>
            <?php
				$line_total = 0;
				$exenta = 0;
			?>
        </div>
        <div class="valor_parcial_5">
        <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php
				$line_item = explode('~', $raw_item); 
				?>
				<?php $line_total = $line_item[2] * $line_item[3]; ?>
				<?php 				
				if(strpos($line_item[5], '5%') !== false){
					$tax_5= $line_total;
				}
				?>
			<?php } ?>
            <h3><?php echo $tax_5 == 0 ? '' : (($_GET['currency_code'] == 'PYG') ? number_format($tax_5, 0, ',', '.') : number_format($tax_5, 2, ',', '.')) ?>
            <?php echo $tax_5 == 0 ? '' : $moneda?></h3>
            <?php
				$line_total = 0;
				$tax_5 = 0;
			?>
        </div>
        <div class="valor_parcial_10">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php
				$line_item = explode('~', $raw_item); 
				?>
				<?php $line_total = $line_item[2] * $line_item[3]; ?>
				<?php 				
				if(strpos($line_item[5], '10%') !== false){
					$tax_10 += $line_total;
				}
				?>
			<?php } ?>
            <h3><?php echo $tax_10 == 0 ? '' : (($_GET['currency_code'] == 'PYG') ? number_format($tax_10, 0, ',', '.') : number_format($tax_10, 2, ',', '.')) ?>
            <?php echo $tax_10 == 0 ? '' : $moneda?></h3>
            <?php
				$line_total = 0;
				$tax_10 = 0;
			?>
        </div>
        <div class="total_iva">
            <?php foreach($line_items as $raw_item) { ?>
				<?php if($raw_item == '') { continue; } ?>
				<?php
				$line_item = explode('~', $raw_item); 
				?>
				<?php $line_total = $line_item[2] * $line_item[3]; ?>
				<?php 				
				if(strpos($line_item[5], '10%') !== false){
					$tax_10 += $line_total - $line_item[4];
				}
				if(strpos($line_item[5], '5%') !== false){
					$tax_5 += $line_total - $line_item[4];
				}
				?>
			<?php } ?>
            <h3><?php echo $tax_10+$tax_5 == 0 ? '' : (($_GET['currency_code'] == 'PYG') ? number_format($tax_5 + $tax_10, 0, ',', '.') : number_format($tax_5 + $tax_10, 2, ',', '.')) ?>
                <?php echo $tax_10+$tax_5 == 0 ? '' : $moneda; ?></h3>
            <?php
				$line_total = 0;
				$tax_10 = 0;
				$tax_5 = 0;
			?>
        </div>
        <div class="subtotal">
            <h3><?php echo $total_monto; ?> <?php echo $moneda ?></h3>
        </div>
        <div class="total_letras">
            <h3><?php echo ($_GET['currency_code'] == 'PYG') ? $total_palabras : $total_palabras_usd ?>. ----</h3>
        </div>
    </div>
  
    
    
</body></html>

<?php 

function valorEnLetrasUSD($valor,$desc_moneda, $sep, $desc_decimal) {
    $arr = explode(".", $valor);
    $entero = $arr[0];
    if (isset($arr[1])) {
        $decimos = strlen($arr[1]) == 1 ? $arr[1] . '0' : $arr[1];
    }
    
    $fmt = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
    if (is_array($arr)) {
        $num_word = ($arr[0]>=1000000) ? "{$fmt->format($entero)} de $desc_moneda" : "{$fmt->format($entero)} $desc_moneda";
        if (isset($decimos) && $decimos > 0) {
            $num_word .= " $sep {$fmt->format($decimos)} $desc_decimal";
        }
    }
    return $num_word;
}

function valorEnLetras($x) 
	{ 
	if ($x<0) { $signo = "menos ";} 
	else      { $signo = "";} 
	$x = abs ($x); 
	$C1 = $x; 

	$G6 = floor($x/(1000000));  // 7 y mas 

	$E7 = floor($x/(100000)); 
	$G7 = $E7-$G6*10;   // 6 

	$E8 = floor($x/1000); 
	$G8 = $E8-$E7*100;   // 5 y 4 

	$E9 = floor($x/100); 
	$G9 = $E9-$E8*10;  //  3 

	$E10 = floor($x); 
	$G10 = $E10-$E9*100;  // 2 y 1 


	$G11 = round(($x-$E10)*100,0);  // Decimales 
	////////////////////// 

	$H6 = unidades($G6); 

	if($G7==1 AND $G8==0) { $H7 = "Cien "; } 
	else {    $H7 = decenas($G7); } 

	$H8 = unidades($G8); 

	if($G9==1 AND $G10==0) { $H9 = "Cien "; } 
	else {    $H9 = decenas($G9); } 

	$H10 = unidades($G10); 

	if($G11 < 10) { $H11 = "0".$G11; } 
	else { $H11 = $G11; } 

	///////////////////////////// 
		if($G6==0) { $I6=" "; } 
	elseif($G6==1) { $I6="Mill贸n "; } 
			 else { $I6="Millones "; } 
			  
	if ($G8==0 AND $G7==0) { $I8=" "; } 
			 else { $I8="Mil "; } 

	$C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10; 

	return $C3; //Retornar el resultado 

} 

function unidades($u) { 
    if ($u==0)  {$ru = " ";} 
	elseif ($u==1)  {$ru = "Un ";} 
	elseif ($u==2)  {$ru = "Dos ";} 
	elseif ($u==3)  {$ru = "Tres ";} 
	elseif ($u==4)  {$ru = "Cuatro ";} 
	elseif ($u==5)  {$ru = "Cinco ";} 
	elseif ($u==6)  {$ru = "Seis ";} 
	elseif ($u==7)  {$ru = "Siete ";} 
	elseif ($u==8)  {$ru = "Ocho ";} 
	elseif ($u==9)  {$ru = "Nueve ";} 
	elseif ($u==10) {$ru = "Diez ";} 

	elseif ($u==11) {$ru = "Once ";} 
	elseif ($u==12) {$ru = "Doce ";} 
	elseif ($u==13) {$ru = "Trece ";} 
	elseif ($u==14) {$ru = "Catorce ";} 
	elseif ($u==15) {$ru = "Quince ";} 
	elseif ($u==16) {$ru = "Dieciseis ";} 
	elseif ($u==17) {$ru = "Decisiete ";} 
	elseif ($u==18) {$ru = "Dieciocho ";} 
	elseif ($u==19) {$ru = "Diecinueve ";} 
	elseif ($u==20) {$ru = "Veinte ";} 

	elseif ($u==21) {$ru = "Veintiun ";} 
	elseif ($u==22) {$ru = "Veintidos ";} 
	elseif ($u==23) {$ru = "Veintitres ";} 
	elseif ($u==24) {$ru = "Veinticuatro ";} 
	elseif ($u==25) {$ru = "Veinticinco ";} 
	elseif ($u==26) {$ru = "Veintiseis ";} 
	elseif ($u==27) {$ru = "Veintisiente ";} 
	elseif ($u==28) {$ru = "Veintiocho ";} 
	elseif ($u==29) {$ru = "Veintinueve ";} 
	elseif ($u==30) {$ru = "Treinta ";} 

	elseif ($u==31) {$ru = "Treinta y un ";} 
	elseif ($u==32) {$ru = "Treinta y dos ";} 
	elseif ($u==33) {$ru = "Treinta y tres ";} 
	elseif ($u==34) {$ru = "Treinta y cuatro ";} 
	elseif ($u==35) {$ru = "Treinta y cinco ";} 
	elseif ($u==36) {$ru = "Treinta y seis ";} 
	elseif ($u==37) {$ru = "Treinta y siete ";} 
	elseif ($u==38) {$ru = "Treinta y ocho ";} 
	elseif ($u==39) {$ru = "Treinta y nueve ";} 
	elseif ($u==40) {$ru = "Cuarenta ";} 

	elseif ($u==41) {$ru = "Cuarenta y un ";} 
	elseif ($u==42) {$ru = "Cuarenta y dos ";} 
	elseif ($u==43) {$ru = "Cuarenta y tres ";} 
	elseif ($u==44) {$ru = "Cuarenta y cuatro ";} 
	elseif ($u==45) {$ru = "Cuarenta y cinco ";} 
	elseif ($u==46) {$ru = "Cuarenta y seis ";} 
	elseif ($u==47) {$ru = "Cuarenta y siete ";} 
	elseif ($u==48) {$ru = "Cuarenta y ocho ";} 
	elseif ($u==49) {$ru = "Cuarenta y nueve ";} 
	elseif ($u==50) {$ru = "Cincuenta ";} 

	elseif ($u==51) {$ru = "Cincuenta y un ";} 
	elseif ($u==52) {$ru = "Cincuenta y dos ";} 
	elseif ($u==53) {$ru = "Cincuenta y tres ";} 
	elseif ($u==54) {$ru = "Cincuenta y cuatro ";} 
	elseif ($u==55) {$ru = "Cincuenta y cinco ";} 
	elseif ($u==56) {$ru = "Cincuenta y seis ";} 
	elseif ($u==57) {$ru = "Cincuenta y siete ";} 
	elseif ($u==58) {$ru = "Cincuenta y ocho ";} 
	elseif ($u==59) {$ru = "Cincuenta y nueve ";} 
	elseif ($u==60) {$ru = "Sesenta ";} 

	elseif ($u==61) {$ru = "Sesenta y un ";} 
	elseif ($u==62) {$ru = "Sesenta y dos ";} 
	elseif ($u==63) {$ru = "Sesenta y tres ";} 
	elseif ($u==64) {$ru = "Sesenta y cuatro ";} 
	elseif ($u==65) {$ru = "Sesenta y cinco ";} 
	elseif ($u==66) {$ru = "Sesenta y seis ";} 
	elseif ($u==67) {$ru = "Sesenta y siete ";} 
	elseif ($u==68) {$ru = "Sesenta y ocho ";} 
	elseif ($u==69) {$ru = "Sesenta y nueve ";} 
	elseif ($u==70) {$ru = "Setenta ";} 

	elseif ($u==71) {$ru = "Setenta y un ";} 
	elseif ($u==72) {$ru = "Setenta y dos ";} 
	elseif ($u==73) {$ru = "Setenta y tres ";} 
	elseif ($u==74) {$ru = "Setenta y cuatro ";} 
	elseif ($u==75) {$ru = "Setenta y cinco ";} 
	elseif ($u==76) {$ru = "Setenta y seis ";} 
	elseif ($u==77) {$ru = "Setenta y siete ";} 
	elseif ($u==78) {$ru = "Setenta y ocho ";} 
	elseif ($u==79) {$ru = "Setenta y nueve ";} 
	elseif ($u==80) {$ru = "Ochenta ";} 

	elseif ($u==81) {$ru = "Ochenta y un ";} 
	elseif ($u==82) {$ru = "Ochenta y dos ";} 
	elseif ($u==83) {$ru = "Ochenta y tres ";} 
	elseif ($u==84) {$ru = "Ochenta y cuatro ";} 
	elseif ($u==85) {$ru = "Ochenta y cinco ";} 
	elseif ($u==86) {$ru = "Ochenta y seis ";} 
	elseif ($u==87) {$ru = "Ochenta y siete ";} 
	elseif ($u==88) {$ru = "Ochenta y ocho ";} 
	elseif ($u==89) {$ru = "Ochenta y nueve ";} 
	elseif ($u==90) {$ru = "Noventa ";} 

	elseif ($u==91) {$ru = "Noventa y un ";} 
	elseif ($u==92) {$ru = "Noventa y dos ";} 
	elseif ($u==93) {$ru = "Noventa y tres ";} 
	elseif ($u==94) {$ru = "Noventa y cuatro ";} 
	elseif ($u==95) {$ru = "Noventa y cinco ";} 
	elseif ($u==96) {$ru = "Noventa y seis ";} 
	elseif ($u==97) {$ru = "Noventa y siete ";} 
	elseif ($u==98) {$ru = "Noventa y ocho ";} 
	else            {$ru = "Noventa y nueve ";} 
	
	return $ru; //Retornar el resultado 
} 

function decenas($d) 
{ 
    if ($d==0)  {$rd = "";} 
	elseif ($d==1)  {$rd = "Ciento ";} 
	elseif ($d==2)  {$rd = "Doscientos ";} 
	elseif ($d==3)  {$rd = "Trescientos ";} 
	elseif ($d==4)  {$rd = "Cuatrocientos ";} 
	elseif ($d==5)  {$rd = "Quinientos ";} 
	elseif ($d==6)  {$rd = "Seiscientos ";} 
	elseif ($d==7)  {$rd = "Setecientos ";} 
	elseif ($d==8)  {$rd = "Ochocientos ";} 
	else            {$rd = "Novecientos ";} 
	
	return $rd; //Retornar el resultado 
} 
?>