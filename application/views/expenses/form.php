<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('expenses/save/'.$expenses_info->expense_id,
    ['id'=>'expenses_edit_form','class'=>'form-horizontal']); ?>
	<fieldset id="item_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_info'), 'expenses_info', array('class'=>'control-label col-xs-3')); ?>
			<?php echo form_label(!empty($expenses_info->expense_id) ? $this->lang->line('expenses_expense_id') . ' ' . $expenses_info->expense_id : '', 'expenses_info_id', array('class'=>'control-label col-xs-8', 'style'=>'text-align:left')); ?>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_date'), 'date', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-6'>
			<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
					<?php echo form_input(array(
  'name'        => 'date',
  'id'          => 'date',
  'class'       => 'form-control input-sm datepicker',
  'value'       => isset($expenses_info->date) 
                     ? date('d/m/Y', strtotime($expenses_info->date)) 
                     : date('d/m/Y'),
  'placeholder' => 'DD/MM/AAAA',
  'required'    => 'required',
  'pattern'     => '\d{1,2}/\d{1,2}/\d{4}',
  'title'       => 'Ingrese la fecha en formato DD/MM/AAAA'
));?>
                   </div>
	            </div>
        </div> 


                        
                        <!-- Campo para Estado de Pago -->
<div class="form-group form-group-sm">
    <?php echo form_label($this->lang->line('expenses_payment_status'), 'payment_status', array('class'=>'control-label col-xs-3')); ?>
    <div class='col-xs-6'>
        <?php echo form_dropdown('payment_status', array(
                'pending' => $this->lang->line('expenses_pending'),
                'partial' => $this->lang->line('expenses_partial'),
                'paid'    => $this->lang->line('expenses_paid')
            ),
            isset($expenses_info->payment_status) ? $expenses_info->payment_status : 'pending',
            array('class'=>'form-control input-sm', 'id'=>'payment_status')
        );?>
    </div>
</div>




		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_supplier_name'), 'supplier_name', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_input(array(
						'name'=>'supplier_name',
						'id'=>'supplier_name',
						'class'=>'form-control input-sm',
						'value'=>$this->lang->line('expenses_start_typing_supplier_name'))
					);
					echo form_input(array(
						'type'=>'hidden',
						'name'=>'supplier_id',
						'id'=>'supplier_id')
						);?>
			</div>
			<div class="col-xs-2">
				<a id="remove_supplier_button" class="btn btn-danger btn-sm" title="Remove Supplier">
					<span class="glyphicon glyphicon-remove"></span>
				</a>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_supplier_tax_code'), 'supplier_tax_code', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_input(array(
						'name'=>'supplier_tax_code',
						'id'=>'supplier_tax_code',
						'class'=>'form-control input-sm',
						'value'=>$expenses_info->supplier_tax_code)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_amount'), 'amount', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'amount',
							'id'=>'amount',
							'class'=>'form-control input-sm',
							'value'=>to_currency_no_money($expenses_info->amount))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_tax_amount'), 'tax_amount', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'tax_amount',
							'id'=>'tax_amount',
							'class'=>'form-control input-sm',
							'value'=>to_currency_no_money($expenses_info->tax_amount))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_payment'), 'payment_type', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('payment_type', $payment_options, $expenses_info->payment_type, array('class'=>'form-control', 'id'=>'payment_type'));?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_categories_name'), 'category', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('expense_category_id', $expense_categories, $expenses_info->expense_category_id, array('class'=>'form-control', 'id'=>'category')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_employee'), 'employee', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('employee_id', $employees, $expenses_info->employee_id, 'id="employee_id" class="form-control"');?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('expenses_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_textarea(array(
					'name'=>'description',
					'id'=>'description',
					'class'=>'form-control input-sm',
					'value'=>$expenses_info->description)
					);?>
			</div>
		</div>

	
		<!-- Campo para Fecha de Vencimiento -->
        <div class="form-group form-group-sm">
            <?php echo form_label($this->lang->line('expenses_due_date'), 'due_date', array('class'=>'control-label col-xs-3')); ?>
            <div class='col-xs-6'>
                <div class="input-group">
                    <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
                    <?php echo form_input(array(
                        'name'        => 'due_date',
                        'id'          => 'due_date',
                        'class'       => 'form-control input-sm',
                        'value'       => isset($expenses_info->due_date) ? date('d/m/Y', strtotime($expenses_info->due_date)) : '',
                        'placeholder' => 'DD/MM/AAAA',
                        'pattern'     => '\d{1,2}/\d{1,2}/\d{4}',
                        'title'       => 'Ingrese la fecha en formato DD/MM/AAAA',
                        'required'    => 'required'
                    )); ?>

                </div>
            </div>
        </div>
	<?php
		if(!empty($expenses_info->expense_id))
		{
		?>
		
  
        <!-- Campo para Monto Adeudado -->
        <div class="form-group form-group-sm">
            <?php echo form_label($this->lang->line('expenses_amount_due'), 'amount_due', array('class'=>'control-label col-xs-3')); ?>
            <div class='col-xs-6'>
                <div class="input-group input-group-sm">
                    <?php if (!currency_side()): ?>
                        <span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                    <?php endif; ?>
                    <?php echo form_input(array(
                            'name'=>'amount_due',
                            'id'=>'amount_due',
                            'class'=>'form-control input-sm',
                            'value'=>isset($expenses_info->amount_due) ? to_currency_no_money($expenses_info->amount_due) : '')
                        );?>
                    <?php if (currency_side()): ?>
                        <span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
		
		
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('expenses_is_deleted').':', 'deleted', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-5'>
					<?php echo form_checkbox(array(
						'name'=>'deleted',
						'id'=>'deleted',
						'value'=>1,
						'checked'=>($expenses_info->deleted) ? 1 : 0)
					);?>
				</div>
			</div>
		<?php
		}
		?>
	</fieldset>
<?php echo form_close(); ?>	
<script type="text/javascript">
$(document).ready(function(){
  // 1) Autocomplete de proveedor
  $('#supplier_name').on('click', function(){
    $(this).val('');
  }).autocomplete({
    source: '<?php echo site_url("suppliers/suggest"); ?>',
    minChars: 0,
    delay: 10,
    select: function(event, ui){
      $('#supplier_id').val(ui.item.value);
      $(this).val(ui.item.label).attr('readonly','readonly');
      $('#remove_supplier_button').show();
      return false;
    }
  }).blur(function(){
    if (!$('#supplier_id').val()){
      $(this).val('<?php echo $this->lang->line("expenses_start_typing_supplier_name"); ?>');
    }
  });
  $('#remove_supplier_button').hide().click(function(){
    $('#supplier_id,#supplier_name').val('').removeAttr('readonly');
    $(this).hide();
  });
  <?php if (!empty($expenses_info->expense_id)): ?>
    $('#supplier_id').val('<?php echo $expenses_info->supplier_id; ?>');
    $('#supplier_name').val('<?php echo $expenses_info->supplier_name; ?>').attr('readonly','readonly');
    $('#remove_supplier_button').show();
  <?php endif; ?>

  // 2) Validacion de formato de fecha DD-MM-AAAA
  $.validator.addMethod("datePattern", function(value, element) {
    return this.optional(element) || /^\d{1,2}-\d{1,2}-\d{4}$/.test(value);
  }, "Por favor, ingrese la fecha en formato DD/MM/AAAA.");

  var amount_validator = function(field) {
    return {
      url: "<?php echo site_url($controller_name . '/ajax_check_amount'); ?>",
      type: 'POST',
      dataFilter: function(data){
        return JSON.parse(data).success;
      }
    };
  };

  // 3) Validacion y reglas
  $('#expenses_edit_form').validate({
    submitHandler: function(form){
      // AJAX submit
      $(form).ajaxSubmit({
        success: function(response){
          dialog_support.hide();
          table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
        },
        dataType: 'json'
      });
    },
    errorLabelContainer: '#error_message_box',
    ignore: '',
    rules: {
      category:        'required',
      date:            { required: true, datePattern: true },
      due_date:        { required: true, datePattern: true },
      payment_status:  { required: true },
      amount:          { required: true, remote: amount_validator('#amount') },
      tax_amount:      { remote: amount_validator('#tax_amount') },
      amount_due:      { required: true, remote: amount_validator('#amount_due') }
    },
    messages: {
      category:       "<?php echo $this->lang->line('expenses_category_required'); ?>",
      date:           "<?php echo $this->lang->line('expenses_date_required'); ?>",
      due_date:       "<?php echo $this->lang->line('expenses_due_date_required'); ?>",
      payment_status: "<?php echo $this->lang->line('expenses_payment_status_required'); ?>",
      amount: {
        required: "<?php echo $this->lang->line('expenses_amount_required'); ?>",
        remote:   "<?php echo $this->lang->line('expenses_amount_number'); ?>"
      },
      tax_amount: {
        remote: "<?php echo $this->lang->line('expenses_tax_amount_number'); ?>"
      },
      amount_due: {
        required: "<?php echo $this->lang->line('expenses_amount_due_required'); ?>",
        remote:   "<?php echo $this->lang->line('expenses_amount_number'); ?>"
      }
    }
  });

  // 4) Envio AJAX final: interceptamos el submit
  $('#expenses_edit_form')
    .off('submit')
    .on('submit', function(e){
      e.preventDefault();
      var $form = $(this);
      $.post(
        $form.attr('action'),
        $form.serialize(),
        function(response){
          if (response.success) {
            dialog_support.hide();
            table_support.refresh();
          } else {
            alert(response.message);
          }
        },
        'json'
      ).fail(function(){
        alert('Ocurrio un error al guardar el gasto.');
      });
    });

}); // fin document.ready
</script>

