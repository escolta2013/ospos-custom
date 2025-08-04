<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>




<!-- ########################################################### -->
<div class="o_action_manager module">
  <div class="o_action o_view_controller">
    <div data-command-category="actions" class="o_control_panel">
      <div class="o_cp_top hidden-xs">
        <div class="o_cp_top_left">
          <ol role="navigation" class="breadcrumb">
           
            <li accesskey="b"><a href="<?php echo site_url('/billtocolects');?>"><?php echo $this->lang->line('module_billtocollects'); ?></a></li>
            
          </ol>
        </div>
      </div>
      
    </div>
<!--   <div class="o_content">
    <div class="o_list_view o_list_optional_columns">
      <div class="table-responsive">  -->

       <div id="page_title"><?php echo $this->lang->line('module_billtocollects'); ?></div>

       <?php
       if(isset($error))
       {
         echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
       }
       ?>

       <?php echo form_open('#', array('id'=>'item_form', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>
       <div class="form-group form-group-sm">
        <?php echo form_label($this->lang->line('reports_date_range'), 'report_date_range_label', array('class'=>'control-label col-xs-2 required')); ?>
        <div class="col-xs-3">
          <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
        </div>
      </div>
      
      
      <div class="form-group form-group-sm">
       <?php echo form_label($this->lang->line('employees_employee'), 'employees_employee', array('class'=>'required control-label col-xs-2')); ?>
       <div id='report_stock_location' class="col-xs-3">
        <?php  
        echo form_dropdown('employee', $employees, 'all', array('id' => 'employee_id', 'class' => 'form-control selectpicker" data-live-search="true"')); ?>
      </div>
    </div>

    <div class="form-group form-group-sm">
      <?php echo form_label($this->lang->line('customers_customer'), 'customers_customer', array('class'=>'required control-label col-xs-2')); ?>
      <div id='report_stock_location' class="col-xs-3">
        <?php echo form_dropdown('customer', $customers, 'all', array('id'=>'customer_id', 'class'=>'form-control selectpicker" data-live-search="true"')); ?>
      </div>
    </div>
    
    
    <?php
    echo form_button(array(
      'name'=>'generate_report',
      'id'=>'generate_report',
      'content'=>$this->lang->line('common_submit'),
      'class'=>'btn btn-primary btn-sm')
  );
  ?>
  <?php echo form_close(); ?> 

</div>

</div>
 <!--  </div>
  </div>
</div> -->
<div>
  <div class="o_loading_indicator "></div>
  <div class="o_notification_manager"></div>
  <div></div>
  <div class="o_effects_manager"></div>
  <div class="o_dialog_container"></div>
  <div class="o_popover_container"></div>
</div>
<div class="o_ChatWindowManager"></div>
<div class="o_DialogManager"></div>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript">
  $(document).ready(function()
  {
   <?php $this->load->view('partial/daterangepicker'); ?>

   $("#generate_report").click(function()
   {	
    /*re*/	 
    window.location = [window.location,'manages', start_date, end_date,  $("#employee_id").val()  || 'all' ,  $("#customer_id").val()  || 'all' ].join("/");
    
  });/*/re*/
   

 });
</script>

