<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
    // when any filter is clicked and the dropdown window is closed
    $('#filters').on('hidden.bs.select', function(e) {
        table_support.refresh();
    });
    
    // load the preset daterange picker
    <?php $this->load->view('partial/daterangepicker'); ?>

    $("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
        table_support.refresh();
    });

    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    table_support.init({
        resource: '<?php echo site_url($controller_name);?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'expense_id',
        onLoadSuccess: function(response) {
            if ($("#table tbody tr").length > 1) {
                var paymentSummary = response.payment_summary + 
                '<div style="text-align: center; margin-top: 5px;"><strong>Total Gastos: ' + response.total_expenses + '</strong></div>';
                $("#payment_summary").html(paymentSummary);
                $("#accounts_payable_summary").html(response.accounts_payable_summary);
                $("#table tbody tr:last td:first").html("");
                $("#table tbody tr:last").css('font-weight', 'bold');
            }
        },
        queryParams: function() {
        return $.extend(arguments[0], {
            start_date: start_date,
            end_date: end_date,
            filters: $("#filters").val() || [""]
            });
        }
    });
});
</script>

<?php $this->load->view('partial/print_receipt', array('print_after_sale' => false, 'selected_printer' => 'takings_printer')); ?>

<div id="title_bar" class="print_hide btn-toolbar">
    <button onclick="javascript:printdoc()" class='btn btn-info btn-sm pull-right'>
        <span class="glyphicon glyphicon-print">&nbsp;</span><?php echo $this->lang->line('common_print'); ?>
    </button>
    <button class='btn btn-info btn-sm pull-right modal-dlg'
        data-btn-submit='<?php echo $this->lang->line('common_submit') ?>'
        data-href='<?php echo site_url($controller_name . "/view/-1"); ?>'
        title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
    <span class="glyphicon glyphicon-tags">&nbsp;</span>
    <?php echo $this->lang->line($controller_name . '_new'); ?>
</button>
</div>

<div id="toolbar">
    <div class="pull-left form-inline" role="toolbar">
        <button id="delete" class="btn btn-default btn-sm print_hide">
            <span class="glyphicon glyphicon-trash">&nbsp;</span><?php echo $this->lang->line("common_delete");?>
        </button>
        <?php echo form_input(array('name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker')); ?>
        <?php echo form_multiselect('filters[]', $filters, '', array('id' => 'filters', 'data-none-selected-text' => $this->lang->line('common_none_selected_text'), 'class' => 'selectpicker show-menu-arrow', 'data-selected-text-format' => 'count > 1', 'data-style' => 'btn-default btn-sm', 'data-width' => 'fit')); ?>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<div id="payment_summary" style="text-align: center;"></div>
<div id="accounts_payable_summary"></div>

<!-- === INICIO SCRIPT INLINE EDIT DE payment_status === -->
<script>
const lang_pending = '<?php echo $this->lang->line("expenses_pending"); ?>';
const lang_partial = '<?php echo $this->lang->line("expenses_partial"); ?>';
const lang_paid    = '<?php echo $this->lang->line("expenses_paid"); ?>';

// Formatter para mostrar el dropdown en la tabla
function expenseStatusFormatter(value, row) {
  switch (value) {
    case 'pending': return lang_pending;
    case 'partial': return lang_partial;
    case 'paid':    return lang_paid;
    default:        return value;
  }
}


$(function(){
  // Reconfigurar columnas para usar el formatter
  $('#table').bootstrapTable('refreshOptions', {
    columns: (function(cols){
      return cols.map(function(col){
        if (col.field === 'payment_status') {
          col.formatter = expenseStatusFormatter;
        }
        return col;
      });
    })(<?php echo $table_headers; ?>)
  });

  // Detectar cambio en el dropdown
  $('#table').on('change', '.status-dropdown', function(){
    var expenseId = this.dataset.id;
    var newStatus = this.value;
    $.post('<?php echo site_url("$controller_name/save"); ?>/' + expenseId, {
      payment_status: newStatus
    }, function(response){
      if (!response.success) {
        alert(response.message);
      }
    }, 'json');
  });
});
</script>
<!-- === FIN SCRIPT INLINE EDIT === -->

<?php $this->load->view("partial/footer"); ?>
