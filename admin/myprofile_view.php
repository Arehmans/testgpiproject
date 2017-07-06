

<?php if($this->session->flashdata('gpi_id') != "") { ?><div class="alert alert-success" style="margin-top: 20px; text-align: center; padding: 20px;"><strong><?php echo $this->session->flashdata('gpi_id'); ?></strong></div><?php } ?>
<div class="clear"></div>
<form method="post" action="<?php echo site_url('user/deactivate_membership') ?>">
<div class="modal fade" id="myModal" style="margin-top: 60px;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">De-Activate Membership</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="deactive_id" name="deactive_id" value="" />
        <p>Are you sure you want to Cancel this membership?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="submit" class="btn btn-primary">Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<div class="container-fluid">
	<div class="row">
    	<div class="col-xs-offset-2 col-sm-10">
        	<div class="clear pad-40"></div>
            <div class="well">
                <div class="media">
                	<div class="media-left">
                    <?php
                    $qry= $this->gpi_model->getrecordbyid('users','user_id', $this->session->userdata('gpi_id'));
					
                    foreach($qry as $res)
                    {
						 $level=$this->gpi_model->get_levels("levels",$res->level_id);  
                    ?>
                    <h3 class="text_red text-center mr-20"></h3>
                    </div>
                    
                  
                    <div class="media-body">
                    	<h4 class="media-heading font_regular text_blck"><?php echo $res->last_name;?>&nbsp;<?php echo $res->first_name;?></h4>
                        <div class="clear pad-5"></div>
                        <?php echo $res->address1;?>
                       <?php 
						$mem_id = $this->common_model->select_single_field('mem_id','users',array('user_id'=>$this->session->userdata('gpi_id')));
					    $mem_id = explode(',', $mem_id);
						$mem_id = end($mem_id);
						$mem_name = $this->common_model->select_single_field('mem_name','tbl_membership',array('mem_id'=>$mem_id));
					   ?>
        				<h3 class="text_red"><?php echo $level->level_name;?></h3>
						<h4 class="" style="color:black;"><?php echo $mem_name;?></h4>
      				</div>
      				<div class="media-right text-center"><h1 class="mt-0 mb-0 text_green"><?php echo $res->score;?></h1>Score</div>
					<form name="upgrade" method="POST" action="<?php echo site_url('user/upgradeMembership/'.$this->session->userdata('gpi_id')) ?>">
					<input type="hidden" id="activate" name="activate" value="" />
			
			<?php 
			$active_Status = "";
								 $level_id = $this->common_model->select_single_field('level_id','users',array('user_id'=>$this->session->userdata('gpi_id')));
								 $memObj = $this->common_model->select_where('mem_id','users',array('user_id'=> $this->session->userdata('gpi_id')));
								 
								 $mem_array = $memObj->result_array();
								 $mem_id = explode(',', $mem_id);
								 $mem_id = end($mem_id);
							    if(!empty($mem_id)){
								
									$mem_array = array_column($mem_array,'mem_id');
									$mem_array = implode(", ", $mem_array);
								   
								   
									$mem_cond = "tbl_membership_level.level_id = ".$level_id." AND tbl_membership.mem_id NOT IN (".$mem_array.")";
									$allMemberships  = $this->common_model->join_two_tab_where_nolimit( '*', 'tbl_membership', 'tbl_membership_level', "tbl_membership.mem_id = tbl_membership_level.mem_id", $mem_cond,"tbl_membership.mem_id", "ASC" );
									$rowz = $allMemberships->num_rows();
							
							
							if($rowz !=0){
							?>
			<input type="button" class="btn btn-success" id="btnUpgrade" name="btnUpgrade" style="height:30px;cursor:pointer;float:right;margin-top:-40px;padding-bottom:27px;" value="Upgrade Now" onclick="document.forms['upgrade'].submit();" />
	<?php	/*	<img src="<?php echo site_url().'assets/images/upgrade.png' ?>" style="height:30px;cursor:pointer;float:right;margin-top:-40px" id="btnUpgrade" name="btnUpgrade"  onclick="document.forms['upgrade'].submit();"> */ ?>
			<?php } else{ ?>
			<input type="button" class="btn btn-success" id="btnUpgrade" name="btnUpgrade" style="height:30px;cursor:pointer;float:right;margin-top:-40px;padding-bottom:27px;" disabled="disabled" value="Upgrade Now" />
		<?php /*	<img src="<?php echo site_url().'assets/images/upgrade.png' ?>" style="height:30px;cursor:pointer;float:right;margin-top:-40px" disabled="disabled" id="btnUpgrade" name="btnUpgrade"> */ ?>
			<?php }   ?>
			
			<?php $user_id =  $this->session->userdata('gpi_id');
				$active_Status = $this->common_model->select_single_field('activate_membership','users',array('user_id'=>$user_id));
				if($active_Status == 1) {
			?>
				<input type="button" class="btn btn-danger" id="btnDeactivate" onclick="deactive(<?php echo $user_id; ?>)" value="Cancel Membership" style="background-color: red;height:30px;margin-right:357px;cursor:pointer;float:right;margin-top:-40px;padding-bottom:27px;" />
				<input type="button" class="btn btn-warning"  value="Re-activate Membership" id="btnActivate" style="height:30px;margin-right:140px;cursor:pointer;float:right;margin-top:-40px;padding-bottom:27px;" />
				<?php  } ?>
			
		<?php	}?>
			
			
			
            </form>
    			</div>
                
            </div>
			<?php 
			
			if($active_Status == 1){
			date_default_timezone_set('America/New_York');//or change to whatever timezone you want

				$myObject =  $this->common_model->select_where('dateadded,frequancy','tbl_order',array('user_id'=>$user_id,'frequancy <>'=>0)); 
					$myObject = $myObject->row_array();
					$db_time = $myObject['dateadded'];
					$frequancy = $myObject['frequancy'];
					$expDate = date('Y-m-d', strtotime("+".$frequancy." months", $db_time));
					
					$todayDate = date('Y-m-d');
					
					$datediff = strtotime($expDate) - strtotime($todayDate);
					$daysDiff =  floor($datediff/(60*60*24));
					$daysDiff+=2;
				
				?>
				
				<div><p style="color:red"> 
				Your membership is going to be expired within (<?php echo $daysDiff; ?>) days. Please re-activate to continue using GPI PORTAL.
				</p> </div>
			<?php  } ?>
            <div class="clear pad-5"></div>
			
			<hr>
            <h3>Basic Information</h3>
            <div class="row">
                <div class="col-xs-offset-2 col-sm-8">  
				
	
	
	<!--http://stackoverflow.com/questions/11516352/how-to-set-end-date-for-paypal-recurring-payments-with-direct-payment -->
	
	
	
	



<!--
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="W82CUUCG9GFBE">
<table>
<tr><td><input type="hidden" name="on0" value="payment">payment</td></tr><tr><td><select name="os0">
	<option value="daily charges">daily charges : $0.2 USD - daily</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">



<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"> 
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="JUYKVGSZX4KTE">
<table>
<tr><td><input type="hidden" name="on0" value="Packages">Packages</td></tr>
<tr><td>
<select name="os0">
	<option value="Daily Bundle">Daily Bundle : $1.50 USD - daily</option>
	<option value="Weekly Bundle">Weekly Bundle : $10.50 USD - weekly</option>
	<option value="Monthly Bundle">Monthly Bundle : $90.00 USD - monthly</option>
	<option value="Year Bundle">Year Bundle : $500.00 USD - yearly</option>
</select>
</td></tr>
</table>
<input type="hidden" name="srt" value="5">
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" alt="No" width="1" height="1">
</form>

<form name = "myform" action = "https://www.sandbox.paypal.com/cgi-bin/webscr" method = "post" target = "_top">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type = "hidden" name = "business" value = "sh.furqan.shafique-facilitator@gmail.com">
<input type="hidden" name="lc" value="IN">
<input type = "hidden" name = "item_name" value = "TEsting">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="src" value="1">
<input type="hidden" name="srt" value="5">
<input type="hidden" name="a3" value="2">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="M">
<input type="hidden" name="currency_code" value="USD">
<input type = "hidden" name = "cancel_return" value = "http://gpi.appexos.com/user/cancelPaypayl/">
<input type = "hidden" name = "return" value = "http://gpi.appexos.com/user/sucessPaypal/">
<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
<img alt="nnn" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" alt="No" width="1" height="1">
<input type="submit" value="Paypal" />
</form>
-->






              	
                    <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">First Name</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->first_name;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Last Name</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->last_name;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->email;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Zip Code</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->zip_code;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Organization</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->organization;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Phone No.</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->phone_no;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Address 1</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->address1;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Address 2</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $res->address2;?></p>
                    </div>
                </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Profile</label>
                    <div class="col-sm-9">
                        <?php if($res->profile!=""){ ?>
                        <p class="form-control-static"><img src="<?php echo base_url()."assets/uploads/".$res->profile; ?>" width="80" height="80" class="reviewed-thumb"> </p><?php } else { ?>
                        <p class="form-control-static"><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" width="80" height="80" class="reviewed-thumb"> </p>
                        <?php } ?>   
                    </div>
                </div>
                <div class="col-xs-offset-3 col-sm-6">
                    <a href="<?php echo base_url(); ?>user/myprofile_update/<?php echo $res->user_id;?>" class="btn btn-warning">Edit Profile</a> 
                  <!--  <a href="#" class="btn btn-danger">Save Changes</a>-->
                </div>
                 <?php }?>	
            </form>
                </div>
            </div>
           
            <div class="clear pad-15"></div>
        </div>
    </div>
</div>





