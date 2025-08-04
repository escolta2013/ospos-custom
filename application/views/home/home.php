<style>
.alert {
  padding: 200px; 
  margin-left: 410px;
  float: center;
  background-color: #f44336;
  color: white;
  opacity: 1;
  transition: opacity 0.6s;
  margin-bottom: 15px;
  width: 25%
}

.alert.success {background-color: #04AA6D;}
.alert.info {background-color: #2196F3;}
.alert.warning {background-color: #ff9800;}

.closebtn {
   margin-left: 15px;
   color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}
</style>

<?php $this->load->view("partial/header"); 

   if($cambiotasa==1) { ?>   
     <div class="alert alert-success" role="alert">
       <span class="closebtn">&times;</span> 
         <?php if ($this->config->item('tasa_ref')==1) { ?>
           <div class="module_item">  
            <h4 class="title-prome"><b>Actualizada Con Exito a Tasa BCV (Oficial)!</b></h4>
            <img src="<?php echo base_url('images/BCV.png'); ?>" title="logo-bcv" alt="logo-bcv" width="80" height="80">
            <h3><b><?php echo"Bs.: ",  $bcv; ?></b></h3> 
            <label id="contador"></label>
          </div>
        <?php   }else if ($this->config->item('tasa_ref')==2) { ?>

          <div class="module_item">  
            <h4 class="title-prome"><b>Actualizada Con Exito a Tasa @EnParaleloVzla3!</b></h4>
            <img src="<?php echo base_url('images/logo_paralelo.png'); ?>" title="logo-bcv" alt="logo-bcv" width="80" height="80">
            <h3><b><?php echo"Bs.: ",  $enparalelovzla; ?></b></h3> 
            <label id="contador"></label>
          </div>

       <?php   }?>

    </div>

  	  </div> 
  	<?php

  } 
?>

 

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<h6 class="text-center"><?php echo $this->lang->line('common_welcome_message'); ?></h6>

<div id="home_module_list">
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

<div id="home_module_list">

   <?php if ($this->config->item('tasa_ref')==1) { ?>

    <div class="module_item">  
    <h4 class="title-prome"><b>BCV (Oficial)</b></h4>
    <img src="<?php echo base_url('images/BCV.png'); ?>" title="logo-bcv" alt="logo-bcv" width="80" height="80">
    <h6><b><?php echo"Bs.: ",  $bcv; ?></b></h6> 
   </div>

   <?php   }else if ($this->config->item('tasa_ref')==2) { ?>

    <div class="module_item">  
    <h4 class="title-prome"><b>@EnParaleloVzla3</b></h4>
    <img src="<?php echo base_url('images/logo_paralelo.png'); ?>" title="logo-bcv" alt="logo-bcv" width="80" height="80">
    <h6><b><?php echo"Bs.: ",  $enparalelovzla; ?></b></h6> 
   </div>

   <?php   }?> 

  

</div>

<script>
  var close = document.getElementsByClassName("closebtn");
  var i;

  for (i = 0; i < close.length; i++) {
    close[i].onclick = function(){
      var div = this.parentElement;
      div.style.opacity = "0";
      setTimeout(function(){ div.style.display = "none"; }, 600);
    }
  }

  $(document).ready(function () {

    window.setTimeout(function() {
      $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
        $(this).remove(); 
      });
 
    }, 5000);


    var count = 6; // Inicializa el contador en 4 segundos
    var label = $("#contador"); // Obtiene una referencia al elemento <label>

    var countdown = setInterval(function() {
      count--; // Resta 1 al contador
     
        label.text("Cierra En " + count + " segundos"); // Actualiza el contenido del elemento <label> con el valor del contador
      
    }, 1000); // El temporizador se ejecuta cada segundo (1000 milisegundos)


  })
</script>
 
<?php $this->load->view("partial/footer"); ?>
