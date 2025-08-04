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


  <div class="o_content">

<?php if (!$this->input->is_ajax_request()): ?>   
			<h5>
			<div id="payment_summary" align="center"> </h5>
 
<?php endif; ?>

    <div class="o_list_view o_list_optional_columns">
      <div class="table-responsive">


        <table class="ui-sortable" id="table"></table>

<?php if (!$this->input->is_ajax_request()): ?>        
 

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
