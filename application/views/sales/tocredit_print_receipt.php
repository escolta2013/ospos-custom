<?php 
	// Temporarily loads the system language for _lang to print invoice in the system language rather than user defined.
	load_language(TRUE,array('customers','sales','employees'));
?>

<div id="receipt_wrapper" style="font-size:<?php echo $this->config->item('receipt_font_size');?>px">
	<div id="receipt_header">
		<?php
		if($this->config->item('company_logo') != '')
		{
		?>
			<div id="company_name">
				<img id="image" src="<?php echo base_url('uploads/' . $this->config->item('company_logo')); ?>" alt="company_logo" />
			</div>
		<?php
		}
		?>

		<?php
		if($this->config->item('receipt_show_company_name'))
		{
		?>
			<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<?php
		}
		?>

		<span style="text-align: left;">
		<div id="company_address"><strong>Dirección:</strong><?php echo nl2br($this->config->item('address')); ?></div>
		<div ><strong>Teléfono:</strong><?php echo $this->config->item('phone'); ?></div>
		<!-- <div id="sale_receipt"><?php echo $this->lang->line('sales_show_receipt'); ?>: <?php echo $sale_id; ?></div> -->
		<div id="sale_time"><?php echo $transaction_time ?></div>
		<div id="sale_date"><?php echo $this->lang->line('statements_payment_time'); ?>: <?php echo $fecha_pago_rec; ?></div>
		 
		</span>
	</div>



	<div id="receipt_general_info" >
		<!-- how stupid how ospos developers makes the things very complicated, we use table helper right now! -->
		<?php
		$template = array('table_open' => '<table border="0" cellpadding="2" cellspacing="2" id="receipt_items">');
		$this->table->set_template($template);
		$col1line1 = '<div id="sale_id">'. $this->lang->line('sales_id').': <strong>'.$sale_info['sale_id'].'</strong></div>';
		$col1line2 = '<div id="invoice_number">'.$this->lang->line('sales_show_receipt').' #: <strong>'.$paid_id.'</strong></div>';
		$col1line3 = '<div id="customer_name">'.$this->lang->line('sales_customer').': <strong>'.$sale_info['customer_name'].'</strong></div>';
		$col2line1 = $col2line2 = $col2line3 = '<div></div>'; // make a center colum empty to make separation dummy
		$col3line1 = '<div id="total" align="right">'.$this->lang->line('sales_customer_total').': <strong>'.to_currency($payment_amount_totalsale).'</strong><div>';
		$col3line2 = '<div id="total" align="right">'.$this->lang->line('sales_due').': <strong>'.to_currency($payment_tocredit_value).'</strong><div>';//if ($payment_tocredit_due > 0)
		$col3line3 = '<div id="total" align="right">'.$this->lang->line('balance_due').': <strong>'.to_currency($payment_tocredit_due).'</strong><div>';
		$this->table->add_row($col1line1, $col2line1, $col3line1);
		$this->table->add_row($col1line2, $col2line2, $col3line2);
		$this->table->add_row($col1line3, $col2line3, $col3line3);
		echo $this->table->generate();
		?>
		<div id="total" align="right">

			<div align="center">
        	<strong>Descripcion de Articulos</strong>
        	<table class="table">
        		<thead>
        			<tr> 

        				<th><b><small>Codigo </small></b></th>
        				<th><b><small>Descripcion</small></b></th>
        				<th><b><small>Cantidad</small></b></th>
        				<th><b><small>Precio</small></b></th>
        				<th><b><small>Total</small></b></th>
        			</tr>
        		</thead>
        		<tbody>

        			 <?php
               foreach ($sale_items as $productos) {
               	$total+=$productos['item_unit_price']*$productos['quantity_purchased'];
                 ?>
                  <tr>
                     <td><small><?php echo $productos['item_id'];?></small></td>
        			 <td><small><?php echo $productos['name'];?></small></td>
        			 <td><small><?php echo $productos['quantity_purchased'];?></small></td>
        			 <td><small><?php echo $productos['item_unit_price'];?></small></td>
        			 <td><small><?php echo $productos['item_unit_price']*$productos['quantity_purchased'];?></small></td>
        		 </tr>
                 <?php
                 } ?> 
        			

        		</tbody>
        	</table>
        </div>
        <br>
        <div align="center"> 
        	<table class="tabley">
        		<thead>
        			<tr> 
        				<td><b>Total Venta:</b> <?php echo to_currency($payment_amount_totalsale);?></td> 
        			</tr>
        			<tr> 
                <?php   foreach ($prima as $primas) { ?>
              <tr> 
                <td><b>Prima Inicial:</b> <?php echo $primas['payment_type'].' '.to_currency($primas['payment_amount']);?></td> 
              </tr>
            <?php } ?>
              <tr> 
        				<td><b>Credito #:</b>  <?php echo to_currency($payment_tocredit_value);?></td> 
        			</tr> 
        			<tr> 
        			 <td align="center"><?php echo '<b> Abonos Recibidos</b>'; 
                 
                  foreach ($pagos_recibido as $pagos_recibidos) {
                     $pagos=$pagos_recibidos['payment_amount'];
                     $total_r+=$pagos_recibidos['payment_amount'];
                     $date = date_create($pagos_recibidos['fecha_pago']);
                     $fecha_pago=date_format($date,"d/m/Y");

                    echo  "<br><b>Abonado:</b> ", to_currency($pagos).' <b>Fecha: </b>'.$fecha_pago;
                  }
                 

               ?></td> 
        			</tr>
        			
        			<!-- <tr> 
        				<td><b>Saldo:</b>  <?php echo to_currency($payment_tocredit_due);?></td> 
        			</tr>  -->

        		</thead>
        		 

        	</table>
        </div>

			<h6><?php echo $this->lang->line('credit_received').': '.to_currency($payment_amount); ?></h6>
      <h6><?php echo "Total Pagado".': '.to_currency($total_r); ?></h6>
      <h6><?php echo "Saldo".': '.to_currency($payment_tocredit_due); ?></h6>
      <div>
	</div>

    


	<br>RECIBI CONFORME_________________________
 <div id="sale_return_policy">
    <?php echo nl2br('Codigo'); ?>
  </div>
	<div id="barcode">
		<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
		<?php echo $paid_id; ?>
	</div>
</div>
</div>
</div>
