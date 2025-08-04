<?php if (!$this->input->is_ajax_request()): ?>
	<?php $this->load->view("partial/header"); ?>
<?php endif; ?> 
<style type="text/css">
	.print_hide {
		color: transparent;
		width: 0;
	} </style>

	<input type="hidden" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
	<input type="hidden" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
	<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id; ?>">
	<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>">
	<input type="hidden" id="sale_status" name="sale_status" value="<?php echo $sale_status; ?>">
	<script type="text/javascript">
		$(document).ready(function()
		{
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});
	
	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>

	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
		table_support.refresh();
	});

	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'sale_id',
		onLoadSuccess: function(response) {
			if($("#table tbody tr").length > 1) {
				$("#payment_summary").html(response.payment_summary);
				$("#table tbody tr:last td:first").html("");
			}
		},
		queryParams: function() {
			return $.extend(arguments[0], {
				start_date: $("#start_date").val(),
				end_date: $("#end_date").val() ,
				employee_id: $("#employee_id").val() ,
				customer_id: $("#customer_id").val() ,
				filters: $("#filters").val() || [""]
			});
		},
		columns: {
			'invoice': {
				align: 'center'
			}
		}
	});
});
</script>

<?php if (!$this->input->is_ajax_request()): ?>
	<!-- ########################################################### -->
	<div class="o_action_manager module">
		<div class="o_action o_view_controller">
			<div data-command-category="actions" class="o_control_panel">
				<div class="o_cp_top">
					<div class="o_cp_top_left">
						
					</div>
					
				</div>
				<div class="o_cp_bottom">
					<div class="o_cp_bottom_left">
						<div role="toolbar" aria-label="Control panel buttons" class="o_cp_buttons">
							<div class="o_list_buttons d-flex" role="toolbar" aria-label="Main actions">



								<div id="toolbar">
									<div class="pull-left form-inline" role="toolbar">

										<?php $this->load->view('partial/print_receipt', array('print_after_sale'=>false, 'selected_printer'=>'takings_printer')); ?>

										
									</div>
								</div> 
							</div>
						</div>
					</div>
					<div class="o_cp_bottom_right">
						
						<div role="search" class="o_cp_pager">
							
						</div>

						
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="o_content">

			<?php if (!$this->input->is_ajax_request()): ?>        
				
				<h5>
					<div id="payment_summary" align="center"> </h5>
						
					<?php endif; ?>


					<div class="o_list_view o_list_optional_columns">
						<div class="table-responsive">


							<table class="ui-sortable" id="table"></table>

							<?php if (!$this->input->is_ajax_request()): ?>        
								
								<div id="payment_summary" align="center">
									

								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div> 
			
			<?php $this->load->view("partial/footer"); ?>
		<?php endif; ?>
	</body>
	</html> 
