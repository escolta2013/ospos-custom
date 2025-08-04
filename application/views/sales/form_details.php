<?php echo form_open("sales/save/".$sale_info['sale_id'], array('id'=>'sales_edit_form', 'class'=>'form-horizontal')); ?>

		dddddddddddddddd
			<?php echo $this->lang->line('sales_receipt_number'); ?>
			
	
		
	
			<?php echo $this->lang->line('sales_date'); ?>
			
				<?php echo $sale_info['sale_time'];?>
		

		<?php
		if($this->config->item('invoice_enable') == TRUE)
		{
		?>
			
				<?php echo $this->lang->line('sales_invoice_number'); ?>
				
					<?php if(!empty($sale_info["invoice_number"]) && isset($sale_info['customer_id']) && !empty($sale_info['email'])): ?>
						<?php echo $sale_info['invoice_number'];?>
						<a id="send_invoice" href="javascript:void(0);"><?php echo $this->lang->line('sales_send_invoice');?></a>
					<?php else: ?>
						<?php echo form_input(array('name'=>'invoice_number', 'value'=>$sale_info['invoice_number'], 'id'=>'invoice_number', 'class'=>'form-control input-sm'));?>
					<?php endif; ?>
			
		<?php
		}
		?>
<?php echo form_close(); ?>






<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open("sales/edit/".$sale_info['sale_id'], array('id'=>'sales_edit_form', 'class'=>'form-horizontal')); ?>	

<?php if( !empty(strstr($payment_tocredit_label, $this->lang->line('sales_tocredit'))) ): ?>

	<!-- font awesome ! -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"/>
	
	<!-- bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>

	<!-- google font -->
	 <link href="https://fonts.googleapis.com/css?family=Yantramanav" rel="stylesheet">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<style type="text/css">
	
body{
  	background-image:url("https://s18.postimg.org/r423iicnb/grayback.jpg");
	background-repeat: no-repeat;
	background-attachment:fixed;
	background-size:cover;
	font-family: 'Yantramanav', sans-serif;
}

.app{
  /*margin-left:100px;
  margin-right:100px;*/
  margin-left:auto;
  margin-right:auto;
  max-width:765px;
}

#efi{
  background-color: #13bdd2 !important;
  background-image:url("https://s18.postimg.org/f3tfloiih/efi3.jpg");
  background-size:cover;
  border-color: #ffffff;
  border-width: .1 px;
  font-size: 38px;
  /*margin-top:15px;
  margin-bottom: 15px;*/
  margin:15px 0px 15px 0px;
  width: 60px;
  height: 60px;
}
.btn-secondary{
  background-color: #13bdd2 !important;
  /*border-style: solid;*/
  border-color: #ffffff;
  border-width: .1 px;
}
#avatar{
  width: 60px;
  height: 60px;
  background-image:url("https://i.ytimg.com/vi/jYRtFFa4hT8/hqdefault.jpg");
  background-size:cover;
}
/*mail and cog icons, top navigation bar*/
.mailCogContainter{
	position: relative;
}
.mailCog{
	position: absolute;
    bottom: -20px;
}
.fa-cog{
	margin-left:8px;
}
.numberIndicator{
	position: relative;
	color:black;
	margin-left:-7px;
	margin-bottom:-10px;
}
/*name next to the avatar*/
.nameBlock{
	position: relative;
}
.jan{
	position: absolute;
    bottom: 10px;
	font-size:14px;
}

/*naviagtion*/
.navList{
	margin:0;
	padding: 0;
	/*margin-left:auto;
  	margin-right:auto;*/
  	max-width:765px;
    list-style-type:none;
    background-color: #13bdd2;
}
.navContainer{
	margin-left:auto;
  	margin-right:auto;
  	max-width:765px;
    background-color: #13bdd2;
    /*height:102px;
    max-height:250px;*/
}
.navBackground{
	background-color: #13bdd2;
}

.navItem0{
    display:inline-block;
    width:60px;
}
.navItem1{
    display:inline-block;
    width:60px;
}
.navItem2{
    display:inline-block;
    width:170px;
    margin-left:20px;
    color:white;
    max-height:60px;
}
/*black navigation*/
.navItem3{
	position:relative;
	font-size: 30px;
    display:inline-block;
    width:300px;
    /*margin-left:70px;*/
    color:white;
    max-height:60px;
    margin-left: -400px;
}
.blackNav{
    height:102px;
    /*margin-top:10px;*/
}
.homeCaption{
    margin-left:17px;
    margin-top:-10px;
    position:absolute;
}
.analyseCaption{
    margin-left:10px;
    margin-top:-10px;
    position:absolute;
}
.planCaption{
    margin-left:21px;
    margin-top:-10px;
    position:absolute;
}
.earnCaption{
    margin-left:20px;
    margin-top:-10px;
    position:absolute;
}

/*home*/
#iconMain1{
	position:absolute;
    display:inline-block;
    width:70px;
    height:102px;
    margin-top:0px;
}
.fa-home{
    margin-top:35px;
    margin-left:23px;
}
/*chart*/
#iconMain2{
	position:absolute;
    display:inline-block;
    width:70px;
    height:102px;
    margin-top:0px;
    margin-left:70px;
}
.fa-area-chart{
    margin-top:35px;
    margin-left:20px;
}
/*notepad*/
#iconMain3{
	position:absolute;
	margin-left:140px;
    display:inline-block;
    width:70px;
    height:102px;
    margin-top:0px;
}
.fa-calendar-o{
    margin-top:35px;
    margin-left:23px;
}
/*earn*/
#iconMain4{
	position:absolute;
	margin-left:210px;
    display:inline-block;
    width:70px;
    height:102px;
    margin-top:0px;
}
.fa-bars{
    margin-top:35px;
    margin-left:23px;
}
/*right buttons in out*/
.navItem4{
	display:inline-block;
    width:100px;
    margin-left:320px;
    color:white;
    max-height:60px;
}
.seachIOContainer{
    position:relative;
    float:right;
    font-size:22px;
    top:17px;
    right:-18px;
}
.searchContainer{
    display:inline-block;
    margin-right:10px;
    border-right: 1px solid;
    padding-right:15px;
    height:40px;    
}
.fa-search{
    padding-top:10px;
}
.inOutContainer{
    display:inline-block;
    color:#E21A61;
}

/*----------------------------*/
    /*circles*/
#circle1{
    position:absolute;
    border-radius: 50%;
	width: 15px;
	height: 15px; 
    background-color:black;
    margin-top:25px;
    margin-left:28px;
}
#circle2{
    position:absolute;
    border-radius: 50%;
	width: 15px;
	height: 15px; 
    background-color:black;
    margin-top:25px;
    margin-left:28px;
}
#circle3{
    position:absolute;
    border-radius: 50%;
	width: 15px;
	height: 15px; 
    background-color:black;
    margin-top:25px;
    margin-left:28px;
}
#circle4{
    position:absolute;
    border-radius: 50%;
	width: 15px;
	height: 15px; 
    background-color:black;
    margin-top:25px;
    margin-left:28px;
}

/*rwd*/
@media screen and (max-width: 700px) {
	.diagramContainer{
		width:auto;
	}
  .seachIOContainer{
    float: none;
    width: auto;
    position:relative;

    top:10px;
    right:0px;
  }
.navList{
    height:192px;
    margin-left:auto;
    margin-right:auto;
    text-align:center;
}
    .navItem0{
        float:none;
        margin-top:35;
        /*margin-left:40px;*/
    }
    .navItem1{
        float:none;
        margin-top:35;
        /*margin-left:80px;*/
    }
    .navItem2{
        width:90px;
        margin-left:20px;
    }
    #efi{
    /*margin:15px 0px 15px 80px;*/

    }
    .navItem3{
        text-align:center;
        display:block;
        position:relative;
        font-size: 30px;
        /*display:inline-block;*/
        width:400px;
        margin-left:auto;
        margin-right:auto;
    }
    .navItem4{
        display:inline-block;
        width:100px;
        /*margin-left:320px;*/
        color:white;
        max-height:60px;
        float:none;
        margin-left:0px;
    }
    /*blacknav details:*/
        /*home*/
        .blackNav{
            margin-left:auto;
            margin-right:auto;
        }
        .fa-home{
            margin-left:2px;
        }
        /*chart*/ /*notepad*/ /*earn*/
        .fa-area-chart,
        .fa-bars,
        .fa-calendar-o{
            margin-left:5px;
        }
    #iconMain1,
    #iconMain2,
    #iconMain3,
    #iconMain4{
        position:relative;
        display:inline-block;
        margin-left:0px;
    }
    .jan{
        position:relative;
    }
    .nameBlock{
        text-align:left;
    }
}

.diagramContainer{
  position:relative;
    height:200px;
	margin-top:40px;
  	margin-bottom:20px;
}
.diagram{
	/*position: relative;*/
  position:absolute;
  background-color:white;
  width:100%;
  height:200px;

}

/*finance middle block*/
.secondLine{
	position: relative;
	height:100px;
	/*max-height:200px;*/
}
.financesleftContainer{
	float:left;
	width:70%;
	height:120px;
	position:relative;
}
/*finance left*/
.financeCaptionBlock{
	position:relative;
	height: 70px;
}
.financeCaption{	
	float:left;
	margin-top: 15px;
	font-size: 30px;
}
/*money caption*/
.moneyContainer{
	position: relative;
}
.moneyBlock{
	width:150px;
	float:left;
	font-size:20px;
}
.moneyBlock2{
	width:150px;
	float:left;
	display:inline-block;
	margin-left:5px;
	font-size:20px;
}
.moneyBlock3{
	width:200px;
	float:left;
	display:inline-block;
	margin-left:5px;
	font-size:20px;
}
/*---------------------------------------------*/

/*buttons right*/
.buttonsRightContainer{
	float:right;
	max-width:250px;
	height:120px;
	position:relative;
}
.rightUpContainer{
	margin-top:30px;
	position :relative;
	height: 50px;
}
.rightDownContainer{
	margin-top:10px;
	position:relative;
	height:50px;
}

.bankButtonContainer{
	display:inline-block;
	margin-left:11px;
}
.paymentButtonContainer{
	display:inline-block;
	margin-left:5px;
}

.btn-primary1{
	padding:2px 5px 2px 5px;
	background-color: #13bdd2;
	border-color:#13bdd2;
	width:65px;
	height:25px;
	color:white;
}
.btn1 {
	border-radius:2px;
    display: inline-block;
    /* padding: 6px 12px; */
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}
/*---------------------------------------------*/
/*swtich button right side*/
.switch {
  position: relative;
  display: inline-block;
  width: 65px;
  height: 25px;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #13bdd2;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 17px;
  width: 27px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: white;
}

input:focus + .slider {
  box-shadow: 0 0 1px white;
}

input:checked + .slider:before {
  -webkit-transform: translateX(29px);
  -ms-transform: translateX(29px);
  transform: translateX(29px);
  background-color:#07B209;

}

.offCaption{
	position: absolute;
	font-size:12px;
	top:-5px;
	right:37px;
}
.onCaption{
	font-size:12px;
	position: absolute;
	top:-5px;
	right:10px;
}
#paymentButton{
	width:80px;
}
/*--------------------------------------------*/

/*rwd*/
@media screen and (max-width: 700px) {

  .secondLine{
	height:370px;
  }
  .financesleftContainer{
	float:none;
	width:auto;
	position:relative;
	margin-left:auto;
	margin-right:auto;
	}
  .buttonsRightContainer{
	float:none;
	margin-left:auto;
	margin-right:auto;
	width: 250px;
	margin-bottom:10px;
	position:relative;
	height:120px;
	margin-top:140px;
  }
  .financeCaption{
  	float:none;
  }
  .financeCaptionBlock,
  .moneyBlock2,
  .moneyBlock3,
  .moneyBlock{
	  text-align:center;
  	width:250px;
  	float:none;
  	font-size:20px;
	margin-left:auto;
	margin-right:auto;
	display:block;
  }
  .rightUpContainer,
  .rightDownContainer{
	text-align:center;
	margin-top:30px;
	position :relative;
	height: 50px;
  }

}

.floating-box {
  float: left;
  width:180px;
  height:80px;
  margin:5px;
  background-color:white; 
}
.trueRight{
  float: right;
  width:375px;
  height:80px;
  margin-left:auto;
  margin-right:auto;
  height:280px;

}
.floating-box2 {
  float: right;
  width:375px;
  margin: 1px;
  background-color:white; 
  margin-left:auto;
  margin-right:auto;
  height:280px;
  font-size:12px;
}

.botList{
	position:relative;
    margin-left: -40px;
    max-width:800px;;
    list-style-type:none;
}
/*left side*/
.botListItem1{
    position:relative;
    max-width:380px;
	float:left;
}

.leftBoxBottom{
    font-size:45px;
    float:left;
    color: #B2B2B2;
    margin-top:8px;
    margin-left:17px;
}
.rightBoxBottom{
    float: left;
    display:inline-block;
    font-size:14px;
    margin-top:18px;
    margin-left:8px;
}
/*right side - seek bar table*/
.botListItem2{
    position:relative;
	float: right;
    max-width:380px;
    /*display:inline-block;*/
}


.scrollspy-example {
    position: relative;
    height: 280px;
    margin-top: .5rem;
    overflow: auto;
}
.fa-angle-down{
    position:absolute;
    padding-top:-1px;
    padding-left:4px;
    font-size:20px;
    color:#13bdd2;
}
.btn-secondary{
    width:25px;
    height:22px;
    background-color:#13bdd2;
    color:white;

}
.captionOK{
    margin-bottom:10px;
}

.productsCaption{
    margin-left:4px;
}

.btnOK{
    display: inline-block;
    padding: 1px 0px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
}
.greenNum{
    color:green;
}

/*rwd*/
@media screen and (max-width: 700px) {
  .botListItem1,
  .botListItem2{
      margin-left:auto;
      margin-right:auto;
    float: none;
    width: auto;
  }
}
</style>

<div class="navBackground">
		<div class="navContainer">
			<ul class="navList">
				<!--left side navigation bar-->
				<li class="navItem0">	<button type="button" class="btn btn-secondary" id="efi"></button>	</li>
				<li class="navItem1">	<button type="button" class="btn btn-secondary" id="avatar"></button> </li>
				<li class="navItem2">
					<div class="nameBlock">
						<span class="jan"><?php if ($avatar_customer) {
              ?>
               <img class="imgRedonda" style="height: 60px; box-shadow: 0 3px 6px rgb(0 0 0 / 16%), 0 3px 6px rgb(0 0 0 / 23%) !important;" src="<?php echo base_url('uploads/avatars/'.$avatar_customer)?>" alt="Customer Avatar">
              <?php
            } else if ($avatar_customer==0){
              if ($gender==1) { 

                ?>
                 <img class="imgRedonda" style="height: 60px; box-shadow: 0 3px 6px rgb(0 0 0 / 16%), 0 3px 6px rgb(0 0 0 / 23%) !important;" src="<?php echo base_url('images/avatars/male.png')?>" alt="Customer Avatar">
                <?php
                }else if ($gender==0) { 
                
                ?>
                 <img class="imgRedonda" style="height: 60px; box-shadow: 0 3px 6px rgb(0 0 0 / 16%), 0 3px 6px rgb(0 0 0 / 23%) !important;" src="<?php echo base_url('images/avatars/female.png')?>" alt="Customer Avatar">
                <?php
                }
              }
             ?><?php echo $selected_employee_name;?></span>
					</div>
					<div class="mailCogContainter">
							<div class="mailCog">
							<i class="fa fa-envelope" aria-hidden="true"> Vendedor</i>
							<i class="fa fa-cog" aria-hidden="true"></i>
						<!--<i class="fa fa-circle" aria-hidden="true"></i>-->
							</div>
					</div>
				</li>
				<!--right side navigation bar-->
				<li class="navItem4">
					<div class="seachIOContainer">
						<div class="searchContainer">
							<i class="fa fa-search" aria-hidden="true"></i>
						</div>
						<div class="inOutContainer">
							<i class="fa fa-power-off" aria-hidden="true"></i>
						</div>
					</div>
				</li>
				<!--middle part navigation bar-->
				<li class="navItem3">
					<div class="blackNav">
						<div id="iconMain1">
							<i class="bi bi-receipt" aria-hidden="true"></i> 
							<div class="homeCaption"> <h5><?php echo $sale_info['sale_id'];?></h5> </div>
							<div style="display: none" id="circle1"></div>
						</div>
						<div id="iconMain2">
							<i class="fa fa-area-chart" aria-hidden="true"></i> 
							<div class="analyseCaption"> <h5>ANALYSE</h5> </div>
							<div style="display: none" id="circle2"></div> 
						</div>
						<div id="iconMain3">
							<i class="fa fa-calendar-o" aria-hidden="true"></i> 
							<div class="planCaption"> <h5>PLAN</h5> </div>
							<div style="display: none" id="circle3"></div>
						</div>
						<div id="iconMain4">
							<i class="fa fa-bars" aria-hidden="true"></i> 
							<div class="earnCaption"> <h5>EARN</h5> </div> 
							<div style="display: none" id="circle4"></div> 
						</div>
					</div>
				</li>
				
			</ul>
		</div>
		</div>

<div class="app">

<!-- app -->
	<div class="secondLine">
		<div class="financesleftContainer">
			<!--finance caption above moeny-->
			<div class="financeCaptionBlock">
				<p class="financeCaption"><?php echo $selected_customer_name;?></p>
			</div>

			<!--money caption blocks-->
			<div class="moneyContainer">
				<div class="moneyBlock">
					<?php 
		$i = 0;
		foreach($payments as $row)
		{
			/**
			 *
			 * el truco que hicieron era que dado tenian que llevar cuentas, era complicado asi que 
			 * en el momento hicieron editable la venta, pero no el monto
			 * esto hacia que el monto total se conservase, no importando los pagos..
			 * 
			 * esto no sirve con credito, porque no es lo mismo cambiar el monto total de creditos
			 * asi que ahora el total delmonto "a credito" no debe ser alterado, los otros si
			 * sin embargo, cualer otro de los montos no debe ser "a credito" sino solo uno..
			 * es decir solo un "acredito" en la lista y no alterable
			 * 
			 * Porque? facil esto altera las cuentas 
			 * 
			 */
		?>
			<?php if( !empty(strstr($row->payment_type, $this->lang->line('sales_tocredit'))) ): ?>
					<span><?php echo $row->payment_type;?></span> <br>
					<span><b><?php echo $this->config->item('currency_symbol'); ?></b> <?php echo $row->payment_amount;?></span>
					
					
			<?php else: ?>
				<?php echo form_hidden('payment_type_'.$i, $row->payment_type);?>
				<?php echo form_hidden('payment_amount_'.$i, $row->payment_amount);?>
			<?php endif; ?>
		<?php 
			++$i;
		}
		echo form_hidden('number_of_payments', $i);			
		?>
				</div>
				<div class="moneyBlock2">
					<span>Abonado</span> <br>
					<span><b><?php echo $this->config->item('currency_symbol'); ?></b> <?php echo $payment_tocredit_paidup;?></span>
				</div>
				<div class="moneyBlock3">
					<span>Saldo</span> <br>
					<span><b><?php echo $this->config->item('currency_symbol'); ?></b> <?php echo $payment_tocredit_due;?></span>
				</div>
			</div>
		</div>
		<!--buttons to the right of money blocks-->
		<div class="buttonsRightContainer">
		</div>
	</div>
	<!--end 2nd line--><!--
	<div class="diagramContainer">
		<div class="diagram">
			<div id="chart_div" style="width: 100%; height: 200px;"></div>
		</div>
	</div>-->


	<div class="grid">
		<?php 
		$i = 0;
		foreach($payments as $row)
		{?>
		<?php if( !empty(strstr($row->payment_type, $this->lang->line('sales_tocredit'))) ): ?>
  <section>
    <h2>Valor Gráfico</h2>
    <svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" width="200" height="200" xmlns="http://www.w3.org/2000/svg">
      <circle class="circle-chart__background" stroke="#efefef" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
      <circle class="circle-chart__circle" stroke="#00acc1" stroke-width="2" stroke-dasharray="<?php echo number_format($payment_tocredit_paidup/$row->payment_amount*100, 2);?>,100" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
      <g class="circle-chart__info">
        <text class="circle-chart__percent" x="16.91549431" y="15.5" alignment-baseline="central" text-anchor="middle" font-size="8"><?php echo number_format($payment_tocredit_paidup/$row->payment_amount*100, 2);?>%</text>
        <text class="circle-chart__subline" x="16.91549431" y="20.5" alignment-baseline="central" text-anchor="middle" font-size="2">Hurra <?php echo number_format($payment_tocredit_paidup/$row->payment_amount*100-100, 2);?>% Para finalizar!</text>
      </g>
    </svg>
  </section>
			<?php endif; ?>
		<?php 
			++$i;
		}
		echo form_hidden('number_of_payments', $i);			
		?>

<!--<section>
    <h2>Negative chart value</h2>
    <svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" width="200" height="200" xmlns="http://www.w3.org/2000/svg">
      <circle class="circle-chart__background" stroke="#efefef" stroke-width="2" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
      <circle class="circle-chart__circle circle-chart__circle--negative" stroke="#00acc1" stroke-width="2" stroke-dasharray="10,100" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
      <g class="circle-chart__info">
        <text class="circle-chart__percent" x="16.91549431" y="15.5" alignment-baseline="central" text-anchor="middle" font-size="8">-10%</text>
        <text class="circle-chart__subline" x="16.91549431" y="20.5" alignment-baseline="central" text-anchor="middle" font-size="2">Oh no :(</text>
      </g>
    </svg>
  </section>-->
</div>

<style type="text/css">
	/**
 * 1. The `reverse` animation direction plays the animation backwards
 *    which makes it start at the stroke offset 100 which means displaying
 *    no stroke at all and animating it to the value defined in the SVG
 *    via the inline `stroke-dashoffset` attribute.
 * 2. Rotate by -90 degree to make the starting point of the
 *    stroke the top of the circle.
 * 3. Using CSS transforms on SVG elements is not supported by Internet Explorer
 *    and Edge, use the transform attribute directly on the SVG element as a
 * .  workaround (https://markus.oberlehner.net/blog/pure-css-animated-svg-circle-chart/#part-4-internet-explorer-strikes-back).
 */
.circle-chart__circle {
  animation: circle-chart-fill 2s reverse; /* 1 */ 
  transform: rotate(-90deg); /* 2, 3 */
  transform-origin: center; /* 4 */
}

/**
 * 1. Rotate by -90 degree to make the starting point of the
 *    stroke the top of the circle.
 * 2. Scaling mirrors the circle to make the stroke move right
 *    to mark a positive chart value.
 * 3. Using CSS transforms on SVG elements is not supported by Internet Explorer
 *    and Edge, use the transform attribute directly on the SVG element as a
 * .  workaround (https://markus.oberlehner.net/blog/pure-css-animated-svg-circle-chart/#part-4-internet-explorer-strikes-back).
 */
.circle-chart__circle--negative {
  transform: rotate(-90deg) scale(1,-1); /* 1, 2, 3 */
}

.circle-chart__info {
  animation: circle-chart-appear 2s forwards;
  opacity: 0;
  transform: translateY(0.3em);
}

@keyframes circle-chart-fill {
  to { stroke-dasharray: 0 100; }
}

@keyframes circle-chart-appear {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Layout styles only, not needed for functionality */
html {
  font-family: sans-serif;
  padding-right: 1em;
  padding-left: 1em;
}

.grid {
  display: grid;
  grid-column-gap: 1em;
  grid-row-gap: 1em;
  grid-template-columns: repeat(1, 1fr);
}

@media (min-width: 31em) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
	
<ul class="botList">
	<li class="botListItem1">
		<h4 class="productsCaption" >Pagos</h4>
		<?php 
		$i = 0;
		foreach($payments as $row)
		{
		?>
		<div id="walletBox" class="floating-box">
			<div class="leftBoxBottom">
				<i class="fa fa-inbox" aria-hidden="true"></i>
			</div>
			<div class="rightBoxBottom">
				<span><?php echo $row->payment_type; ?></span> <br>
				<span><?php echo $row->payment_amount;?> C$</span> <br>
				<span><?php echo $row->cash_refund;?> Reembolso</span>
			</div>
		</div>
		<?php 
		++$i;
		}
		echo form_hidden('number_of_payments', $i);			
		?>
	</li>
<!--<li class="botListItem1">
		<h4 class="productsCaption" >Pagos</h4>
		<div id="walletBox" class="floating-box">
			<div class="leftBoxBottom">
				<i class="fa fa-inbox" aria-hidden="true"></i>
			</div>
			<div class="rightBoxBottom">
				<span>Wallets [3]</span> <br>
				<span>500,00 PLN</span>
			</div>
		</div>
		<div id="depositsBox" class="floating-box">
			<div class="leftBoxBottom">
				<i class="fa fa-money" aria-hidden="true"></i>
			</div>
			<div class="rightBoxBottom">
				<span>Deposits [2]</span> <br>
				<span>10 000,00 PLN</span>
			</div>	
		</div>
		<div id="accountsBox" class="floating-box">
			<div class="leftBoxBottom">
				<i class="fa fa-university" aria-hidden="true"></i>	
			</div>
			<div class="rightBoxBottom">
				<span>Accounts</span> <br>
				<span>7 200,00 PLN</span>
			</div>
		</div>
		<div id="fundsBox" class="floating-box">
			<div class="leftBoxBottom">
				<i class="fa fa-bar-chart" aria-hidden="true"></i>
			</div>
			<div class="rightBoxBottom">
				<span>Funds [3]</span> <br>
				<span>7 000,00 PLN</span>
			</div>
		</div>
		<div id="bankLoansBox" class="floating-box">
			<div class="leftBoxBottom">
				<i class="fa fa-hand-o-right" aria-hidden="true"></i>
			</div>
			<div class="rightBoxBottom">
				<span>Bank loans</span> <br>
				<span>-127 000,00 PLN</span>
			</div>		
		</div>
	</li>-->
	<!--list in the bottom-right corner-->
	<li class="botListItem2">
		<div class="trueRight">
			<h4>Historial de pagos</h4>
			<div class="floating-box2">
				<!--scrolling list-->
					<div data-spy="scroll" data-target="#navbar-example2" data-offset="0" class="scrollspy-example">
					<!--table-->
						<table class="table">
						<tbody>
							<?php 
								$i = 0;
								foreach($payment_tocredit_details as $payment_tocredit_row)
								{
								?>

							<tr>
								<div class="row1Date">
									<th class="row1" scope="row"><?php echo anchor('sales/edit/'.$sale_info['sale_id'].'/1/'.$payment_tocredit_row['payment_tocredit'], '<span class="btn btn-primary bi bi-receipt-cutoff"></span>',
												array('target'=>"_blank", 'class' => 'modal-dlg print_hide submit_hide', 'data-btn-submit' => $this->lang->line('common_submit'), 'title' => $this->lang->line('sales_receipt'))); ?></th>
								</div>
								<td>
									<div>
									<span><?php echo $payment_tocredit_row['payment_tocredit'];?></span> <br>
									<span>Transacción</span> <i class="fa fa-angle-down" aria-hidden="true"></i>
									</div>
								</td>
								<td><b><?php echo $this->config->item('currency_symbol'); ?> <?php echo $payment_tocredit_row['payment_amount'];?> </b>PLN</td>
							</tr>
						    <?php 
									++$i;
								}
								?>
							<!--row 1-->
						</tbody>
						</table>
					</div>
			</div>
		</div>
	</li>
</ul>
	<!-- end app-->
		</div>
<?php endif; ?>

		
	<!--<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('sales_customer'), 'customer', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php if( !empty($payment_tocredit_due) and $payment_tocredit_due > 0 ): ?>
					<?php echo form_input(array('name'=>'customer_name', 'value'=>$selected_customer_name, 'id'=>'customer_name', 'readonly'=>'readonly', 'class'=>'form-control input-sm'));?>
				<?php else: ?>
					<?php echo form_input(array('name'=>'customer_name', 'value'=>$selected_customer_name, 'id'=>'customer_name', 'class'=>'form-control input-sm'));?>
				<?php endif; ?>
				<?php echo form_hidden('customer_id', $selected_customer_id);?>--> <!-- client cannot be changed if a credit payment are involucrated--><!--
			</div>
		</div>-->
		<?php echo form_hidden('employee_id', $sale_info['employee_id']);?>		

<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function()
{	
	<?php if(!empty($sale_info['email'])): ?>
		$("#send_invoice").click(function(event) {
			if (confirm("<?php echo $this->lang->line('sales_invoice_confirm') . ' ' . $sale_info['email'] ?>")) {
				$.get('<?php echo site_url() . "/sales/send_pdf/" . $sale_info['sale_id']; ?>',
					function(response) {
						dialog_support.hide();
						table_support.handle_submit('<?php echo site_url('sales'); ?>', response);
					}, "json"
				);	
			}
		});
	<?php endif; ?>
	
	<?php $this->load->view('partial/datepicker_locale'); ?>
	
	$('#datetime').datetimepicker({
		format: "<?php echo dateformat_bootstrap($this->config->item('dateformat')) . ' ' . dateformat_bootstrap($this->config->item('timeformat'));?>",
		startDate: "<?php echo date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), mktime(0, 0, 0, 1, 1, 2010));?>",
		<?php
		$t = $this->config->item('timeformat');
		$m = $t[strlen($t)-1];
		if( strpos($this->config->item('timeformat'), 'a') !== false || strpos($this->config->item('timeformat'), 'A') !== false )
		{ 
		?>
			showMeridian: true,
		<?php 
		}
		else
		{
		?>
			showMeridian: false,
		<?php 
		}
		?>
		minuteStep: 1,
		autoclose: true,
		todayBtn: true,
		todayHighlight: true,
		bootcssVer: 3,
		language: '<?php echo current_language_code(); ?>'
	});

	var fill_value =  function(event, ui) {
		event.preventDefault();
		$("input[name='customer_id']").val(ui.item.value);
		$("input[name='customer_name']").val(ui.item.label);
	};

	$("#customer_name").autocomplete(
	{
		source: '<?php echo site_url("customers/suggest"); ?>',
		minChars: 0,
		delay: 15, 
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});

	$('button#delete').click(function() {
		dialog_support.hide();
		table_support.do_delete('<?php echo site_url('sales'); ?>', <?php echo $sale_info['sale_id']; ?>);
	});

	$('button#restore').click(function() {
		dialog_support.hide();
		table_support.do_restore('<?php echo site_url('sales'); ?>', <?php echo $sale_info['sale_id']; ?>);
	});

	var submit_form = function()
	{ 
		$(this).ajaxSubmit(
		{
			success: function(response)
			{
				dialog_support.hide();
				table_support.handle_submit('<?php echo site_url('sales'); ?>', response);
			},
			dataType: 'json'
		});
	};

	$('#sales_edit_form').validate($.extend(
	{
		submitHandler: function(form)
		{
			submit_form.call(form);
		},
		rules:
		{
			invoice_number:
			{
				remote:
				{
					url: "<?php echo site_url($controller_name . '/check_invoice_number')?>",
					type: "POST",
					data: $.extend(csrf_form_base(),
					{
						"sale_id" : <?php echo $sale_info['sale_id']; ?>,
						"invoice_number" : function()
						{
							return $("#invoice_number").val();
						}
					})
				}
			}
		},
		messages: 
		{
			invoice_number: '<?php echo $this->lang->line("sales_invoice_number_duplicate"); ?>'
		}
	}, form_support.error));
});
</script>
