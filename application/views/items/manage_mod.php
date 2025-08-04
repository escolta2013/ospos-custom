<?php $this->load->view('partial/header'); ?>

<script type="text/javascript">
$(document).ready(function()
{
    // Obtener location_id del empleado desde PHP
    var employee_location_id = <?php echo $this->session->userdata('location_id') ?? 0; ?>;

    $('#generate_barcodes').click(function()
    {
        window.open(
            'index.php/items/generate_barcodes/'+table_support.selected_ids().join(':'),
            '_blank'
        );
    });
    
    $('#filters').on('hidden.bs.select', function(e)
    {
        table_support.refresh();
    });

    <?php $this->load->view('partial/daterangepicker'); ?>
    
    $('#daterangepicker').data('daterangepicker').setStartDate("<?php echo date($this->config->item('dateformat'), mktime(0,0,0,01,01,2010));?>");
    
    var start_date = "<?php echo date('Y-m-d', mktime(0,0,0,01,01,2010));?>";
    
    $("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
        table_support.refresh();
    });

    $("#stock_location").change(function() {
        table_support.refresh();
    });

    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    table_support.init({
        employee_id: <?php echo $this->Employee->get_logged_in_employee_info()->person_id; ?>,
        resource: '<?php echo site_url($controller_name);?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'items.item_id',
        queryParams: function() {
            return $.extend(arguments[0], {
                start_date: start_date,
                end_date: end_date,
                stock_location: $("#stock_location").val(),
                filters: $("#filters").val() || [""],
                employee_location: employee_location_id // Enviamos location_id al servidor
            });
        },
        onLoadSuccess: function(response) {
            $('a.rollover').imgPreview({
                imgCSS: { width: 200 },
                distanceFromCursor: { top:10, left:-210 }
            });
            
            // Ocultar botones para items de otras ubicaciones
            $.each(response.rows, function(index, row) {
                if(row.location_id != employee_location_id) {
                    $('#table').bootstrapTable('hideRow', { 
                        index: index,
                        field: 'item_id',
                        values: [row.item_id]
                    });
                }
            });
        }
    });
});
</script>

<div id="title_bar" class="btn-toolbar print_hide">
    <?php if($this->Employee->has_grant('items', $this->Employee->get_logged_in_employee_info()->person_id)): ?>
        <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/csv_import"); ?>'
                title='<?php echo $this->lang->line('items_import_items_csv'); ?>'>
            <span class="glyphicon glyphicon-import">&nbsp;</span><?php echo $this->lang->line('common_import_csv'); ?>
        </button>

        <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-new='<?php echo $this->lang->line('common_new') ?>' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/view"); ?>'
                title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
            <span class="glyphicon glyphicon-tag">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?>
        </button>
    <?php endif; ?>
</div>

<div id="toolbar">
    <div class="pull-left form-inline" role="toolbar">
        <?php if($this->Employee->has_grant('items', $this->Employee->get_logged_in_employee_info()->person_id)): ?>
            <button id="delete" class="btn btn-default btn-sm print_hide">
                <span class="glyphicon glyphicon-trash">&nbsp;</span><?php echo $this->lang->line('common_delete'); ?>
            </button>
            <button id="bulk_edit" class="btn btn-default btn-sm modal-dlg print_hide" data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/bulk_edit"); ?>'
                    title='<?php echo $this->lang->line('items_edit_multiple_items'); ?>'>
                <span class="glyphicon glyphicon-edit">&nbsp;</span><?php echo $this->lang->line("items_bulk_edit"); ?>
            </button>
        <?php endif; ?>
        
        <button id="generate_barcodes" class="btn btn-default btn-sm print_hide" data-href='<?php echo site_url("$controller_name/generate_barcodes"); ?>' title='<?php echo $this->lang->line('items_generate_barcodes');?>'>
            <span class="glyphicon glyphicon-barcode">&nbsp;</span><?php echo $this->lang->line('items_generate_barcodes'); ?>
        </button>
        
        <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
        <?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
        
        <?php if(count($stock_locations) > 1): ?>
            <?php echo form_dropdown('stock_location', $stock_locations, $stock_location, array('id'=>'stock_location', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
        <?php endif; ?>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<?php $this->load->view('partial/footer'); ?>