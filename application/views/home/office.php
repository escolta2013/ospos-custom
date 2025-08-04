<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<h3 class="text-center"><?php echo $this->lang->line('common_welcome_message'); ?></h3>

<div id="office_module_list">
	<?php
	foreach($allowed_modules as $module)
	{
	?>  <?php
         if ($module->module_id=="messages") {
         	?>
         	<div class="module_item" title="<?php echo $this->lang->line('module_'.$module->module_id.'_desc');?>">
			<a href="<?php echo site_url("$module->module_id");?>"><img src="<?php echo base_url().'images/menubar/exchangue.png';?>" border="0" alt="Menubar Image" /></a>
			<a href="<?php echo site_url("$module->module_id");?>"><?php echo " Tasa De Cambio" ?></a>
		</div>
         	<?php
         }else{
         	?>
         	<div class="module_item" title="<?php echo $this->lang->line('module_'.$module->module_id.'_desc');?>">
			<a href="<?php echo site_url("$module->module_id");?>"><img src="<?php echo base_url().'images/menubar/'.$module->module_id.'.png';?>" border="0" alt="Menubar Image" /></a>
			<a href="<?php echo site_url("$module->module_id");?>"><?php echo $this->lang->line("module_".$module->module_id) ?></a>
		</div>
         	<?php
         }
	     ?>
		
	<?php
	}
	?>
</div>

<?php $this->load->view("partial/footer"); ?>
