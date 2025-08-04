<style type="text/css">
	.modal-dlg .modal-dialog {
    width: 80%;
    height: 50%;
}
</style>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open("sales/save/".$sale_info['sale_id'], array('id'=>'sales_edit_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="sale_basic_info">
		<div class="row mt-3">
				<div class="col-sm-3 pb-3">
		            <label for="exampleAccount"><?php echo $this->lang->line('sales_customer'); ?></label>
				      <div class="input-group mb-2">
				        <div class="input-group-prepend">
				          <div class="input-group-text"> </div>
				        </div>
				        	<?php if( !empty($payment_tocredit_due) and $payment_tocredit_due > 0 ): ?>
							<?php echo form_input(array('name'=>'customer_name', 'value'=>$selected_customer_name, 'id'=>'customer_name', 'readonly'=>'readonly', 'class'=>'form-control input-sm'));?>
							<?php else: ?>
								<?php echo form_input(array('name'=>'customer_name', 'value'=>$selected_customer_name, 'id'=>'customer_name', 'class'=>'form-control input-sm'));?>
							<?php endif; ?>
							<?php echo form_hidden('customer_id', $selected_customer_id);?> <!-- client cannot be changed if a credit payment are involucrated-->
				      </div>
		        </div>

		        <div class="col-sm-3 pb-3">
		            <label for="exampleCtrl"><?php echo $this->lang->line('sales_invoice_number'); ?></label>
				      <div class="input-group mb-2">
				        <div class="input-group-prepend">
				          <div class="input-group-text"> </div>
				        </div>
				        <?php echo form_input(array('name'=>'tocredit_invoice_number', 'value'=>'F'.date('YmdHis'), 'id'=>'invoice_number', 'id'=>'invoice_number', 'readonly'=>true, 'class'=>'form-control input-sm'));?>
				      </div>
		        </div>
		        
		       <div class="col-sm-3 pb-3">
		            <label for="exampleAmount"><?php echo $this->lang->line('sales_date'); ?></label>
				      <div class="input-group mb-2">
				        <div class="input-group-prepend">
				          <div class="input-group-text"></div>
				        </div>
				        <?php echo form_input(array('readonly'=>'readonly','name'=>'date','value'=>date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), strtotime($sale_info['sale_time'])), 'id'=>'datetime', 'class'=>'form-control input-sm'));?>
				      </div>
		        </div>



		<?php 
		$i = 0;
		foreach($payments as $row)
		{
			/**
			 *
			 * el truco que hicieron era que dado tenian que llevar cuentas, era complicado asi que 
			 * en el momento hicieron editable la venta, pero no el monto
			 * esto hacia que el monto total se conservase, no importando los pagos..
			 * 
			 * esto no sirve con credito, porque no es lo mismo cambiar el monto total de creditos
			 * asi que ahora el total delmonto "a credito" no debe ser alterado, los otros si
			 * sin embargo, cualer otro de los montos no debe ser "a credito" sino solo uno..
			 * es decir solo un "acredito" en la lista y no alterable
			 * 
			 * Porque? facil esto altera las cuentas 
			 * 
			 */
		?>
			<?php if( !empty(strstr($row->payment_type, $this->lang->line('sales_due'))) ): ?>
				<div class="col-sm-3 pb-3">
		            <label for="exampleZip"><?php echo $this->lang->line('sales_payment'); ?></label>
				    <label class="sr-only" for="inlineFormInputGroup"></label>
				      <div class="input-group mb-2">
				        <div class="input-group-prepend">
				         <div class="input-group-text"> </div>
				        </div>
				         
				        <?php 
                          
				        $payment_options = $this->Sale->get_payment_options(TRUE, TRUE);
				        $excluded_options = array(
				          $this->lang->line('sales_due'), 
                          $this->lang->line('sales_check'), 
                          $this->lang->line('sales_rewards'), 
                          $this->lang->line('sales_giftcard')
                      );
                        $new_options = array_diff_key($payment_options, array_flip($excluded_options));
				         echo form_dropdown('payment_type_selected', $new_options, '', array('id'=>'payment_type_selected', 'class'=>'form-control input-sm', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit'));

				          ?>
				          <input type="hidden" name="payment_type_1" id="payment_type_1" value="Cash">
							<?php if (currency_side()): ?>
								<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
							<?php endif; ?>
				      </div>
		        </div>

		        <div class="col-sm-3 pb-3">
		            <label for="exampleZip"><?php echo   $this->lang->line('sales_due'); ?></label>
				    <label class="sr-only" for="inlineFormInputGroup"></label>
				      <div class="input-group mb-">
				        
				        	<?php echo form_input(array('name'=>'paymxent_amount_'.$i, 'value'=>$row->payment_amount, 'id'=>'payment_axmount_'.$i, 'class'=>'form-control input-sm', 'readonly'=>'true'));?> 
					 
				      </div>
		        </div>
				<?php else: ?>
					<!-- <?php echo form_hidden('payment_type_'.$i, $row->payment_type);?> -->
					<?php echo form_hidden('payment_amount_'.$i, $row->payment_amount);?>
				<?php endif; ?>
			<?php 
				++$i;
			}
			echo form_hidden('number_of_payments', $i);			
			?>
<!-- ################################################################################################# -->
			<?php if( !empty(strstr($payment_tocredit_label, $this->lang->line('sales_due'))) ): ?>
			<?php if($payment_tocredit_due > 0): ?>
			<div class="col-sm-3 pb-3">
	            <label for="exampleZip" style="color:#DC6965;"> <?php echo $this->lang->line('statements_paymentamount'); ?></label>
			      <label class="sr-only" for="inlineFormInputGroup"></label>
			      <div class="input-group mb-2">
			         
			        <?php echo form_input(array('name'=>'payment_tocredit_valuepaid', 'value'=>$payment_tocredit_paidup, 'id'=>'payment_tocredit_valuepaid', 'class'=>'form-control input-sm', 'readonly'=>'true'));?>
					 
			      </div>
        	</div>

        	<div class="col-sm-3 pb-3">
	            <label for="exampleZip" style="color:#DC6965;">  <?php echo $this->lang->line('statements_deuda'); ?></label>
			      <label class="sr-only" for="inlineFormInputGroup"></label>
			      <div class="input-group mb-2">
			         
			        <?php echo form_input(array('name'=>'payment_tocredit_todue', 'value'=>$payment_tocredit_due, 'id'=>'payment_tocredit_due', 'class'=>'form-control input-sm', 'readonly'=>'true'));?>
						 
			      </div>
        	</div>

        	<div class="col-sm-3 pb-3">
		        <label for="exampleFirst" style="color:#DC6965;"><?php echo $this->lang->line('statements_add_amount'); ?></label> 
				<div class="input-group mb-2">
			        <div class="input-group-prepend">
			          <div class="input-group-text"></div>
			        </div>
				        <?php echo form_input(array('name'=>'payment_tocredit_amount', 'value'=>0, 'id'=>'payment_tocredit_amount', 'class'=>'form-control input-sm', 'onClick'=>'this.select();'));?>
				         <span   id=montomoneda style="color: #007bff;"></span>
			    </div>
		    </div>
			<?php endif; ?>
		<?php else: ?>
			<?php echo form_hidden('payment_tocredit_todue', 0); ?>
		<?php endif; ?>

		<?php echo form_hidden('employee_id', $sale_info['employee_id']);?>

		<div class="col-md-12 pb-3">
                    <label for="exampleMessage"><?php echo $this->lang->line('sales_comment'); ?></label>
					<?php echo form_textarea(array(
						'name'=>'comment',
						'value'=>$sale_info['comment'],
						'id'=>'comment',
						'rows'=>'3',
						'class'=>'form-control'));?>
						<small class="text-muted">Agregue las notas aquí.</small>
        </div>

      

 





	<div class="form-group form-group-sm">
		<input type="hidden" name="payment_id_v_<?php echo $i;?>" id="payment_id_v_<?php echo $i;?>" value="<?php echo $row->payment_id; ?>">
	</div>

		</div>
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function()
{	

	$('#payment_tocredit_amount').on('input', function() {
		var valor = $(this).val();
		valor = valor.replace(/\,/g, '.');
		$(this).val(valor);
	});

		$('#payment_type_selected').on('change', function() { 
		$('#payment_type_1').val($('#payment_type_selected').val());
         var valuem1="<?php echo $this->config->item('tasa'); ?>";
         valuem1 = valuem1.replace(/\,/g, '.');
         var monto=$('#payment_tocredit_amount').val(); 

         
		    var total=monto/valuem1; 
		       
              if($("#payment_type_selected").val() == "CashBs" || $("#payment_type_selected").val() == "Debit"  || $("#payment_type_selected").val() == "PGM"){ 
             
	                
	                 if (monto>0) {
	                 	$("#montomoneda").html( " = "+ total.toFixed(2)+" "+ "<?php echo $this->config->item('currency_symbol');  ?>");
	                 } else if (monto<=0) {
	                 	 $("#montomoneda").html("  ");
	                 }
		     	 

             }else{

            	 $("#montomoneda").html("  ");

            }  

	});

 $('#payment_tocredit_amount').on('keyup', function() { 
		 
		   var valuem1="<?php echo $this->config->item('tasa'); ?>";
		   valuem1 = valuem1.replace(/\,/g, '.');
         var monto=$(this).val(); 
  
   
		    var total=monto/valuem1; 
		       
             
            if($("#payment_type_selected").val() == "CashBs" || $("#payment_type_selected").val() == "Debit"  || $("#payment_type_selected").val() == "PGM"){ 
	                
	                 if (monto>0) {
	                 	$("#montomoneda").html( " = "+ total.toFixed(2)+" "+ "<?php echo $this->config->item('currency_symbol');  ?>");
	                 } else if (monto<=0) {
	                 	 $("#montomoneda").html("  ");
	                 }
		     	 

             }else{

            	 $("#montomoneda").html("  ");

            }  

	});		

 

	<?php if(!empty($sale_info['email'])): ?>
		$("#send_invoice").click(function(event) {
			if (confirm("<?php echo $this->lang->line('sales_invoice_confirm') . ' ' . $sale_info['email'] ?>")) {
				$.get('<?php echo site_url() . "/sales/send_pdf/" . $sale_info['sale_id']; ?>',
					function(response) {
						dialog_support.hide();
						table_support.handle_submit('<?php echo site_url('sales'); ?>', response);
					}, "json"
				);	
			}
		});
	<?php endif; ?>
	
	<?php $this->load->view('partial/datepicker_locale'); ?>
	
	$('#datetime').datetimepicker({
		format: "<?php echo dateformat_bootstrap($this->config->item('dateformat')) . ' ' . dateformat_bootstrap($this->config->item('timeformat'));?>",
		startDate: "<?php echo date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), mktime(0, 0, 0, 1, 1, 2010));?>",
		<?php
		$t = $this->config->item('timeformat');
		$m = $t[strlen($t)-1];
		if( strpos($this->config->item('timeformat'), 'a') !== false || strpos($this->config->item('timeformat'), 'A') !== false )
		{ 
		?>
			showMeridian: true,
		<?php 
		}
		else
		{
		?>
			showMeridian: false,
		<?php 
		}
		?>
		minuteStep: 1,
		autoclose: true,
		todayBtn: true,
		todayHighlight: true,
		bootcssVer: 3,
		language: '<?php echo current_language_code(); ?>'
	});

	var fill_value =  function(event, ui) {
		event.preventDefault();
		$("input[name='customer_id']").val(ui.item.value);
		$("input[name='customer_name']").val(ui.item.label);
	};

	$("#customer_name").autocomplete(
	{
		source: '<?php echo site_url("customers/suggest"); ?>',
		minChars: 0,
		delay: 15, 
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});

	$('button#delete').click(function() {
		dialog_support.hide();
		table_support.do_delete('<?php echo site_url('sales'); ?>', <?php echo $sale_info['sale_id']; ?>);
	});

	$('button#restore').click(function() {
		dialog_support.hide();
		table_support.do_restore('<?php echo site_url('sales'); ?>', <?php echo $sale_info['sale_id']; ?>);
	});

	var submit_form = function()
	{ 
		$(this).ajaxSubmit(
		{
			success: function(response)
			{
				dialog_support.hide();
				table_support.handle_submit('<?php echo site_url('sales'); ?>', response);
				 location.reload(); // recarga la página actual
			},
			dataType: 'json'
		});
		$('#modal-dlg').modal('hide');
	};

	$('#sales_edit_form').validate($.extend(
	{
		submitHandler: function(form)
		{
			submit_form.call(form);
		},
		rules:
		{
			invoice_number:
			{
				remote:
				{
					url: "<?php echo site_url($controller_name . '/check_invoice_number')?>",
					type: "POST",
					data: $.extend(csrf_form_base(),
					{
						"sale_id" : <?php echo $sale_info['sale_id']; ?>,
						"invoice_number" : function()
						{
							return $("#invoice_number").val();
						}
					})
				}
			}
		},
		messages: 
		{
			invoice_number: '<?php echo $this->lang->line("sales_invoice_number_duplicate"); ?>'
		}
	}, form_support.error));
});
</script>
