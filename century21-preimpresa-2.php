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
?>
