<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>
	      
<div class="jumbotron" style="max-width: 40%; margin:auto">
	<?php echo form_open("messages/send/", array('id'=>'send_sms_form', 'enctype'=>'multipart/form-data', 'method'=>'post', 'class'=>'form-horizontal')); ?>
		<fieldset>
			
			<?php $employee_id=$this->session->userdata('person_id');
			$employee_info = $this->Employee->get_info($employee_id);
			$username= $employee_info->username; 
			if($username=="admin" || $username=="mfnunez" || $username=="mreyes"){   ?>
				<legend style="text-align: center;"><?php echo "Cambiar Valor De Tasa"; ?></legend>
			<div class="form-group form-group-sm">
				<label for="phone" class="col-xs-3 control-label"><?php echo "Monto"; ?></label>
				<div class="col-xs-8">
					<?php echo form_input(array(
							'name' => 'tasa',
							'id' => 'tasa',
							'type'=>'text',
							'class' => 'form-control input-sm number_locale',
							'value'=>$this->config->item('tasa'))); ?>
					<span class="help-block" style="text-align:center;"><?php echo "Debe tomar en cuenta ingresar el valor de la tasa de cambio del BCV"; ?></span>
				</div>


			</div>

			
			<?php echo form_submit(array(
				'name'=>'submit_form',
				'id'=>'submit_form',
				'value'=>$this->lang->line('common_submit'),
				'class'=>'btn btn-primary btn-sm pull-right'));?>

				<?php }else{ ?> <legend style="text-align: center;"><?php echo "Usted no tiene permisos para usar este módulo."; ?></legend> <?php } ?> 
		</fieldset>
	<?php echo form_close(); ?>
</div>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{

	

	$('#send_sms_form').validate({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)	{
					$.notify( { message: response.message }, { type: response.success ? 'success' : 'danger'} )
				},
				dataType: 'json'
			});
		}
	});

	$('#tasa').on('input', function() {
		var valor = $(this).val();
		valor = valor.replace(/\,/g, '.');
		$(this).val(valor);
	});

});
</script>
