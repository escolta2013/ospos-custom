<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});

	 $("#location_id").change(function() {
       table_support.refresh();
    });
	
	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>

	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
		table_support.refresh();
	});

	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.query_params = function()
	{
		return {
			start_date: start_date,
			end_date: end_date,
			location_id: $("#location_id").val(),
			filters: $("#filters").val() || [""]
		}
	};

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'sale_id',
		onLoadSuccess: function(response) { 
			if($("#table tbody tr").length > 1) {
				$("#payment_summary").html(response.payment_summary);
				$("#table tbody tr:last td:first").html("");
				$("#table tbody tr:last").css('font-weight', 'bold');
			}else{
				$("#payment_summary").html('<div id="report_summary">Sin Datos Que Mostrar</div>');
				$("#table tbody tr:last td:first").html("");
				$("#table tbody tr:last").css('font-weight', 'bold');

			}
		},
		queryParams: function() {
			return $.extend(arguments[0], table_support.query_params());
		},
		columns: {
			'invoice': {
				align: 'center'
			}
		}
	});
});
</script>

<?php $this->load->view('partial/print_receipt', array('print_after_sale'=>false, 'selected_printer'=>'takings_printer')); ?>

<div id="title_bar" class="print_hide btn-toolbar">
	<button onclick="javascript:printdoc()" class='btn btn-info btn-sm pull-right'>
		<span class="glyphicon glyphicon-print">&nbsp</span><?php echo $this->lang->line('common_print'); ?>
	</button>
	<?php echo anchor("sales", '<span class="glyphicon glyphicon-shopping-cart">&nbsp</span>' . $this->lang->line('sales_register'), array('class'=>'btn btn-info btn-sm pull-right', 'id'=>'show_sales_button')); ?>
</div>

<div id="toolbar">
	<div class="pull-left form-inline" role="toolbar">
		<button id="delete" class="btn btn-default btn-sm print_hide">
			<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
		</button>

		<?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
		<?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'class'=>'selectpicker show-menu-arrow', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>

		 <?php
           $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		   $employee_info = $this->Employee->get_info($employee_id);
		   $is_admin = $employee_info->is_admin;

		   if (count($stock_locations)>1 && $is_admin==1) {
		    $stock_locations = ['all' => 'Todas Las Ubicaciones'] + $stock_locations; 
		   }
          

       
            $dropdown_attributes = array(
                'id' => 'location_id',
                'class' => 'selectpicker show-menu-arrow',
                'data-style' => 'btn-default btn-sm',
                'data-width' => 'fit'
            );


             if (count($stock_locations)>0) {
             	 echo form_dropdown('location_id', $stock_locations, $stock_location, $dropdown_attributes);
             }
           
           
        
        ?>
	</div>
</div>

<div id="table_holder">
	<table id="table"></table>
</div>

<div id="payment_summary">
</div>

<?php $this->load->view("partial/footer"); ?>
