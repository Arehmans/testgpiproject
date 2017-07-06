<?php
  class Package extends CI_Controller
  {

	    function __construct() {

		   parent::__construct();

		  ini_set('error_reporting', E_ALL);
		  ini_set('display_errors', 'On');  //On or Off
		  if($this->session->userdata("gpi_id") == "") {
			 $this->load->model('common_model'); 
		  }
			
	   }
	   
	   
	   public function email_alreadyExists(){
		    $email =  $this->input->post('email');
			$mysql_userInfo = $this->common_model->select_where('*','users',array('email'=>$email));
			
			if($mysql_userInfo->num_rows() >0){
				echo 1;	
			}else{
				echo 0;	
			}
			
			
	   }


		public function remove_temprary_cartItems(){
			 $session_id = $this->input->post('session_id');	
			 $mysqlTemp = $this->common_model->select_where('*','tbl_temp_cart_items',array('session_id'=>$session_id));
			 $deleteIdsArr = array();
			 if($mysqlTemp->num_rows() >0 ){
				foreach($mysqlTemp->result_array() as $tempObj){
					$deleteIdsArr[] = $tempObj['item_id'];
				}	 
			 }
			 
			 if(!empty($deleteIdsArr)){
				$delete_sch_ids = implode(",",$deleteIdsArr);	
				$this->db->query("DELETE FROM  tbl_schedule_cart WHERE sch_id IN ('".$delete_sch_ids."')");
			 }
			 
			 
			  $this->common_model->delete_where(array('session_id'=>$session_id),'tbl_temp_cart_items');
			 $this->session->set_flashdata('promo_msg1','Cart Items has been delete sucessfully!.');
			 echo "1";
		}
		
		public function delete_single_item($cart_item_id){
			$itemId = $this->common_model->select_single_field('item_id','tbl_temp_cart_items',array('id'=>$cart_item_id));
			
			if($itemId !=""){
				$this->common_model->delete_where(array('sch_id'=>$itemId),'tbl_schedule_cart');
			}
			
			$this->common_model->delete_where(array('id'=>$cart_item_id),'tbl_temp_cart_items');
			//$this->session->set_flashdata('promo_msg1','Cart Items has been delete sucessfully!.');
			echo "1";
		}



	    public function filesPopup(){
			
		$pkg_id = $this->input->post("pkg_id");
		$resourceArray = $this->common_model->select_where('DISTINCT(resource_id) as resource_id','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
		$my_files = "";
		if($resourceArray->num_rows() > 0){
			$my_files .='<table class="table table-striped" style="width:100%"><tr><th>File Name</th><th>File Type</th><tr>';
			foreach($resourceArray->result_array() as $resObj){
				$fileObj = $this->common_model->select_where('*','resources',array('resources_id'=> $resObj['resource_id']));
                $fileObj = $fileObj->row_array();

                $fileName =  $fileObj['file_name'];

                if($fileName == ""){

                   $fileName =     $fileObj['resources'];

                }

                $my_files .='<tr><td>'. $fileName .'</td>';
			    if($fileObj['type']==1)
                $fileType = "pdf";
                 if($fileObj['type']==2)
                 $fileType = "word";
                 if($fileObj['type']==3)
                 $fileType = "word";

                 if($fileObj['type']==4)
                 $fileType = "excel";

                 if($fileObj['type']==5)
                 $fileType = "powerpoint";
                 if($fileObj['type']==6)
                 $fileType = "zip";

                 if($fileObj['type']==7)
                 $fileType = "image";






				$my_files .='<td>'.$fileType.'</td></tr>';
			}
			$my_files .='</table>';
		}
		echo $my_files;
		
	}

	   
	   
	   
	   public function package_tooltip($pkg_id){
		
		
		$resourceArray = $this->common_model->select_where('DISTINCT(resource_id) as resource_id','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
		$my_files = "Package is empty.";
		if($resourceArray->num_rows() > 0){
			$my_files="";
			//$my_files .='<ul class="list-group">';
			$i=0;
			foreach($resourceArray->result_array() as $resObj){
				$i++;
				$filename = $this->common_model->select_single_field('resources','resources',array('resources_id'=> $resObj['resource_id']));
				$my_files .= $filename."\n\n\n\n\n";
                // $my_files .='<li class="list-group-item">'.$filename.'</li>';	
			}
			
			$my_files = rtrim($my_files,',');
			//$my_files .='</ul>';
		}
		
		 echo  $my_files;
		
	}
	
	
	  public function add_temprary_cartItems(){
		  
			$item_id	= $this->input->post('item_id');
			$item_type  = $this->input->post('item_type'); 
			$session_id = $this->input->post('session_id');
			 
			if($item_type == "pkg"){
				
				$mysqlPackage = $this->common_model->select_where('*','tbl_package',array('pkg_id'=>$item_id));	
				$mysqlPackage = $mysqlPackage->row_array();
				$inserArray = array();
				
				$inserArray['item_name']  = $mysqlPackage['pkg_name'];
				$inserArray['item_type']  = 'pkg';
				$inserArray['item_qty']   = 1;
				$inserArray['item_id']	  = $item_id;
				$inserArray['session_id'] = $session_id;
				$inserArray['item_price'] = $mysqlPackage['pkg_price'];
				$this->common_model->insert_array("tbl_temp_cart_items",$inserArray);
				$this->session->set_flashdata('promo_msg1','Package has been added to cart.');
				echo "1";
				
			}else if($item_type == "res"){
				
				$mysqlResource = $this->common_model->select_where('*','resources',array('resources_id'=>$item_id));
				$mysqlResource = $mysqlResource->row_array();
				$fileName = $mysqlResource['file_name'];
				$price = $this->input->post('price');
				if($fileName == "")
					$fileName = $mysqlResource['resources'];
				
				$inserArrayRes['item_name']   = $fileName;
				$inserArrayRes['item_type']   = 'res';
				$inserArrayRes['item_qty'] 	  = 1;
				$inserArrayRes['item_id']	  = $item_id;
				$inserArrayRes['session_id']  = $session_id;
				$inserArrayRes['item_price']  =  $mysqlResource['res_price'];
				
				$this->common_model->insert_array("tbl_temp_cart_items",$inserArrayRes);
				$this->session->set_flashdata('promo_msg1','Package has been added to cart.');
				echo "1";				
			}else if($item_type == "class"){
				$qty = $this->input->post('qty');
				$price = $this->input->post('price');
				$class_id = $this->input->post('class_id');
				
				$mysqlClasses= $this->common_model->select_where('*','tickets',array('ticket_id'=>$item_id));
				$mysqlClasses 				  = $mysqlClasses->row_array();
				$inserArrayCls['item_name']   = $mysqlClasses['ticket_name'];
				$inserArrayCls['class_id']	  = $class_id;
				$inserArrayCls['item_type']   = 'class';
				$inserArrayCls['item_qty'] 	  = $qty;
				$inserArrayCls['item_id']	  = $item_id;
				$inserArrayCls['session_id']  = $session_id;
				$inserArrayCls['item_price']  = $price;
				
				$this->common_model->insert_array("tbl_temp_cart_items",$inserArrayCls);
				$this->session->set_flashdata('class_success_msg','Class has been added to cart.');
				echo "1";				
			}else if($item_type == "schedule"){
				
				$mainSch_id = explode("_",$item_id);
				$mainSch_id = $mainSch_id[1];
				$qty = $this->input->post('qty');
				$price = $this->input->post('price');
				$itemsArr = $this->input->post('itemsArr');
				$item_name = $this->input->post('item_name');
				$dropdownValue = $this->input->post('dropdown_value');
				$varify_start_date = $this->input->post('varify_start_date');
				$varify_end_date = $this->input->post('varify_end_date');
				
		
			//	for($i=0; $i <count($item_id);$i++){
					$ser_id = $this->common_model->select_single_field('id','tbl_service',array('ser_name'=>$dropdownValue));
					$insertSchedule['item_name']   = $itemsArr."_".$dropdownValue;
					$insertSchedule['item_type']   = 'schedule';
					$insertSchedule['item_qty']    = $qty;
					$insertSchedule['item_id']	   = $itemsArr."_".$ser_id;
					$insertSchedule['session_id']  = $session_id;
					$insertSchedule['item_price']  = $price;
					$insertSchedule['sch_id']  	   = $mainSch_id;
					$insertSchedule['date_varify_start'] = $varify_start_date;
					$insertSchedule['date_varify_end']   = $varify_end_date;
					//print_r($insertSchedule);die;
					$insert_id = $this->common_model->insert_array("tbl_temp_cart_items",$insertSchedule);
					
				//}
				$this->session->set_flashdata('schedule_success_msg','Schedule has been added to cart.');
				echo $insert_id;
			}
			
	  }
	  
	  public function save_notes(){
			$notes = $this->input->post('notes'); 
			$cart_id = $this->input->post('cart_id'); 
			$this->common_model->update_array(array('id'=>$cart_id),'tbl_temp_cart_items',array('notes'=>$notes));
			echo "1";
	  }
	   
	   public function index(){
		  
		  $mysqlPkg 	 =  $this->common_model->select_where('*','tbl_package',array('pkg_public'=>1));
		  $mysqlResource =  $this->common_model->select_where('*','resources',array('public_file'=>1));
		  
		  
		  $data['mysqlPkg'] = $mysqlPkg;
		  $data['mysqlResource'] = $mysqlResource;
		  $data['clsObj'] = $this;
		  $data['class'] = 'package';
		  $data['content'] = 'package_view';
	      $this->load->view('layout/layout',$data);
		   
	   }
	   
	   
		public function guest_payment(){
			
			$pkg_id = $this->input->post('guest_pkg_id');
			$res_id = $this->input->post('guest_res_id');
			
			if($this->input->post("poppop_login")==1){
				$login=$this->gpi_model->get_client_login($this->input->post("email"), $this->input->post('password'));
			if($login)
			{
				$this->session->set_userdata("gpi_id", $login->user_id);
				
			        $data=array(
		    		'online'=>1,
		  		);
			    $this->gpi_model->update($data,"users",$login->user_id,"user_id");
			}
			else
			{
				    $data1=$this->session->set_flashdata('login_error','Wrong Email And Password');
					redirect(base_url()."package/index/",$data1); 
					
			}
		}
		   if($this->session->userdata("gpi_id")=="")
		   { 
			 $name=$this->input->post("first_name");
			 $last=$this->input->post("last_name");
			 $email=$this->input->post("email");
			 
			 $data=array(	"first_name"=>$name,
			    	        "last_name"=>$last,
							"email" =>$email,
			  );
			
			 $guest=$this->gpi_model->insert($data,"guest_user");
			 $guest_id = $this->db->insert_id();
			 $this->session->set_userdata("guest_id",$guest_id);
			 $name = ""; $price=""; $qty="";
			 $item_id = "";
			 $item_type= "";
			 if($pkg_id !=""){
				 $item_id = $pkg_id;
				 $item_type = "pkg";
				 
				 $mysqlPkg =  $this->common_model->select_where('*','tbl_package',array('pkg_public'=>1,'pkg_id'=>$pkg_id));
				 $mysqlPkg = $mysqlPkg->row_array();
				 $name = $mysqlPkg['pkg_name'];
				 $price = $mysqlPkg['pkg_price'];
				 $qty = 1;
			 }else if($res_id !=""){
				 
				  $item_id = $res_id ;
				   $item_type = "res";
				 $mysqlRes =  $this->common_model->select_where('*','resources',array('resources_id'=>$res_id));
				 $mysqlRes = $mysqlRes->row_array();
				 $filename = $mysqlRes['resources'];
				 if($mysqlRes['file_name'] !=""){
					 $filename = $mysqlRes['file_name'];
				 }
				 
				 $name = $filename;
				 $price = $mysqlRes['res_price'];
				 $qty = 1;
			 }
			 
			
		//	$config['business'] 			= 'testingbusiness8877@gmail.com';
			//$config['business'] 			= 'Pwatts@gpiwin.com';
			$config['business']				=  $this->config->item('merchant_email');
			$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]

		//	$config['return'] 				= 'https://www.gpiwin.com/package/notify_payment/'.$item_id.'/'.$item_type;
		//	$config['cancel_return'] 		= 'https://www.gpiwin.com/';
		//	$config['notify_url'] 			= 'https://www.gpiwin.com/'; //IPN Post
			//echo $item_id."/".$item_type;die;
			
			$config['return'] 				= ''.site_url().'package/notify_payment/'.$item_id.'/'.$item_type;
			$config['cancel_return'] 		=    site_url();
			$config['notify_url'] 			=    site_url(); //IPN Post
		

			/*$config['return'] 				= 'http://localhost/gpi/package/notify_payment/'.$item_id.'/'.$item_type;
			$config['cancel_return'] 		= 'http://localhost/gpi/';
			$config['notify_url'] 			= 'http://localhost/gpi/'; //IPN Post*/

            //$config['cancel_return'] 		= 'http://gpi.appexos.com/';
			//$config['notify_url'] 		= 'http://gpi.appexos.com/'; //IPN Post

		//	$config['production'] 			= TRUE; //Its false by default and will use sandbox
			$config['production']			=  $this->config->item('production_mode');
			$config["invoice"]				= random_string('numeric',8); //The invoice id
			$this->load->library('paypal',$config);
			$this->paypal->add($name,$price,$qty); //First item
			$this->paypal->pay(); //Proccess the payment

		 }
		
	}
	
	
	
	
	
	public function notify_payment($pkg_id,$pkg_type){
		
		/*$mysqlPkg = $this->common_model->select_where('*','tbl_package_access',array('pkg_id'=>$pkg_id));
		if($mysqlPkg->num_rows() >0){
			$this->common_model->update_array(array('pkg_id'=>$pkg_id,'user_id'=>$user_id),'tbl_package_access',array('status'=>'free'));
		}
		*/

		$guest_id = $this->session->userdata("guest_id");
		$email = $this->common_model->select_single_field('email','guest_user',array('guest_user_id'=>$guest_id));
			
		if($pkg_type == "pkg"){	

			$download_mail_ref=$pkg_id."_".'pkg';
			$downlaod_link = "<a href=".base_url('package/download_mail_guest'.'/'.$download_mail_ref).">Download</a>";
			$pkg_array = array('item_id'=>$pkg_id,'item_type'=>'pkg');
			$this->common_model->insert_array('tbl_package_mail',$pkg_array);
			
			$mysqlPackage = $this->common_model->select_where('*','tbl_package',array('pkg_id'=>$pkg_id));
			$mysqlPackage = $mysqlPackage->row_array();

			
			 $html = "<tr style='text-align:center;background-color:whitesmoke;'>
						<td style='text-align:left;'>".$mysqlPackage['pkg_name']."</td>
						<td>$".$mysqlPackage['pkg_price']."</td> 					
						<td>".$downlaod_link."</td>
						
					  </tr>";
			
			
			 $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
					  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>PURCHASE ORDER SUMMARY</td></tr>
					  <tr style='background-color:whitesmoke;'>
						<th style='width: 20%;text-align:left;' style='border: 1px solid #dddddd;'>Item Name</th>
						<th style='width: 20%;border: 1px solid #dddddd;'>Price</th>
						<th style='width: 20%;text-align:left;border:1px solid #dddddd;'>Link</th>
					  </tr>
					
					  ".$html."
					  
					  
					  
					  
					<tr style='text-align:center;color: blue;'>
					  <td colspan='1' style='text-align:left;border: 1px solid #dddddd;'><b style='font-size:18px;color:black;'>Total</b></td>
					  <td  colspan='2' style='text-align:left;border: 1px solid #dddddd;'><b style='font-size:18px;color:black;margin-left:90px;'>$".$mysqlPackage['pkg_price']."</b></td>
					  </tr>
					</table>";
					
					 $table = '<table width="640" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
												<tr>
													<td>
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td height="60" align="center">
																	<h1 style="font-size:30px; font-family:Arial, Helvetica, sans-serif; color:#333; margin-top:0; padding:0;">
																		Order Detail
																	</h1>                                        
																</td>
															</tr>
															
															<tr>
																<td valign="top">
																	<table width="100%" border="0" cellspacing="2" cellpadding="5">
																	  <tr>
																		<td colspan="3" align="center" height="50" bgcolor="#666666" style="font-size:24px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:#FFF; background-color:#666; text-align:center;">PURCHASE ORDER SUMMARY</td>
																	  </tr>
																	  <tr>
																		<td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Item Name</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Link</td>
																		
																	  </tr>
																	  <tr>
																		<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$mysqlPackage['pkg_name'].'</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">$'.$mysqlPackage['pkg_price'].'</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$downlaod_link.'</td>
																		
																	  </tr>
																	  <tr>
																		<td colspan="2" height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">Total</td>
																		<td height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">$'.$mysqlPackage['pkg_price'].'</td>
																	  </tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td height="20">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>';
					
					
					
					
					
					$email_content = $this->common_model->select_single_field('before_content','email_template',array('id'=>14));
					$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>14));
					
					$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM guest_user WHERE guest_user_id = ".$guest_id."");
					$user = $userObj->row_array();
					$name = $user['name'];
					
					
					
					$healthy = array("{{name}}","{{order}}");
					$yummy   = array($name,$table);
					
					$string = str_replace($healthy,$yummy,$email_content);
					
					
					
					
					
										
					
					
					/*
					{
					$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
					$msgHTML ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html xmlns="http://www.w3.org/1999/xhtml">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
							<meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" name="viewport" />
							<title>Government Procurement Institute</title>
							
							<style type="text/css">
							
							<!-- ================ Start Media Queries ================== -->
								
							@media only screen and (min-width: 768px) {
									body[yahoofix] .mobileCenter                {width: 100%!important;}
									body[yahoofix] .contentWrapper              {width:100%!important;}
							}
							
							@media only screen and (max-width: 640px) {
									body[yahoofix] body                         {width:100%!important; -webkit-text-size-adjust: none;}
									body[yahoofix] table table                  {width:500px!important;}
									body[yahoofix] .emailWrapper             	{width:500px!important;}
									body[yahoofix] .contentWrapper              {width:480px!important;}
									body[yahoofix] .fullWidth					{width:480px!important;}
									body[yahoofix] .messageWrapper              {width:480px!important;}
									body[yahoofix] .headerScale					{width:480px!important;}
							}
							
							<!-- ================ End Media Queries ================== -->
								
							</style>
							
							</head>
							<body yahoofix style="margin: 0; padding:0;">
							
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="emailWrapper">
							  <tr>
								<td valign="top">    
									<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="mobileCenter" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color:#FFF;">
									  <tr>
										<td valign="middle" bgcolor="#fdd00f" height="150" align="center" style="text-align:center; background-color:#fdd00f;"><a href="#">'.$imgUrl.'</a></td>
									  </tr>
									  <tr>
										<td height="15">&nbsp;</td>
									  </tr>
									  <tr>
										<td>
											<table width="640" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
												<tr>
													<td>
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td height="60" align="center">
																	<h1 style="font-size:30px; font-family:Arial, Helvetica, sans-serif; color:#333; margin-top:0; padding:0;">
																		Order Detail
																	</h1>                                        
																</td>
															</tr>
															
															<tr>
																<td valign="top">
																	<table width="100%" border="0" cellspacing="2" cellpadding="5">
																	  <tr>
																		<td colspan="3" align="center" height="50" bgcolor="#666666" style="font-size:24px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:#FFF; background-color:#666; text-align:center;">PURCHASE ORDER SUMMARY</td>
																	  </tr>
																	  <tr>
																		<td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Item Name</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Link</td>
																		
																	  </tr>
																	  <tr>
																		<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$mysqlPackage['pkg_name'].'</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">$'.$mysqlPackage['pkg_price'].'</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$downlaod_link.'</td>
																		
																	  </tr>
																	  <tr>
																		<td colspan="2" height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">Total</td>
																		<td height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">$'.$mysqlPackage['pkg_price'].'</td>
																	  </tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td height="20">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									  </tr>
									  <tr>
										<td bgcolor="#fdd00f" height="5"></td>
									  </tr>
									  <tr>
										<td bgcolor="#797878">
											<table width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
												  <tr>
													<td align="center" height="60" style="font-size:12px; font-family:Arial; color:#FFF;">
														Thanks for Purchasing Items.<br /><a href="http://gpiwin.com" style="color:#FFF;">Government Procurement Innovators, LLC</a>
													</td>
												  </tr>
											 </table>
										</td>
									  </tr>
									</table>
								</td>
							  </tr>
							</table>
							
							
							</body>
							</html>';
					}
							*/
					
					$this->load->library('email');

					$useremail = $email;
					$useremail.=", aliakbar1to5@gmail.com";
				   	$useremail.=", ceo.appexos@gmail.com";
					$useremail .=' , paulakwatts.pw@gmail.com';
					$this->email->from('Adminstrator');
					$this->email->to($useremail);
					$this->email->subject('Order Detail');
					$this->email->message($string);
					//$this->email->message($msgHTML);
					$this->email->set_mailtype("html");
					$this->email->send();
					$this->session->set_flashdata('promo_msg1','Your order has been confirmed and file will be emailed to you.');
					header("Location: ".base_url()."package");
					
			
			
			
			
			#correct code for direct downlaoding.
			
			/*
			{
			$resourcesObj = $this->common_model->select_where('DISTINCT(resource_id) AS resource_id','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
			if($resourcesObj->num_rows() >0){
				
				$this->load->helper('download');
				$rootPath = realpath('./assets/user/file_upload/');
				$zipname = time()."unlock-resources.zip";
				// Initialize archive object
				$zip = new ZipArchive();
				$myflag = 0;
				$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
					foreach($resourcesObj->result_array() as $resObj=>$resValue){
						
						$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resource_id']));
						//echo $fileName."<br />";
						
						if(file_exists('./assets/user/file_upload/'.$fileName)){
							$myflag = 1;
							$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
						}
				}
				
				if($myflag == 0){
					$this->session->set_flashdata('login_error','Sorry! File does not exists!');
					header("Location: ".base_url()."package");
				}
				
				//die;
				// Zip archive will be created only after closing object
				$zip->close();
				///Then download the zipped file.
				$file = './assets/resources_archive/'.$zipname;
				$file_name = basename($file);
				header("Content-Type: application/zip");
				header("Content-Disposition: attachment; filename=" . $file_name);
				header("Content-Length: " . filesize($file));	
				readfile($file);
				
			}else{
				$this->session->set_flashdata('login_error','Sorry! File does not exists!');
				header("Location: ".base_url()."package");
			}
			}
			*/
		
		}else if($pkg_type == "res"){
			
			
			
			$download_mail_ref= $pkg_id.'_'.'res';
		    $downlaod_link = "<a href=".base_url('package/download_mail_guest'.'/'.$download_mail_ref ).">Download</a>";
		    $pkg_array = array('item_id'=>$pkg_id,'item_type'=>'res');
		    $this->common_model->insert_array('tbl_package_mail',$pkg_array);
			
			$mysqlPackage = $this->common_model->select_where('*','resources',array('resources_id'=>$pkg_id));
			$mysqlPackage = $mysqlPackage->row_array();
			
			$fileName = $mysqlPackage['resources'];
			if($mysqlPackage['file_name'] !=""){
				$fileName = $mysqlPackage['file_name'];
			}
	
			
			 $html = "<tr style='text-align:center;background-color:whitesmoke;'>
						<td style='text-align:left;'>".$fileName."</td>
						<td>$".$mysqlPackage['res_price']."</td> 
						<td>$0</td>
						
						<td>".$downlaod_link."</td> 						
						
					  </tr>";
			
			/*
			{
			 $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
					  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>PURCHASE ORDER SUMMARY</td></tr>
					  <tr style='background-color:whitesmoke;'>
						<th style='width: 20%;text-align:left;' style='border: 1px solid #dddddd;'>Item Name</th>
						<th style='width: 20%;border: 1px solid #dddddd;'>Price</th>
						<th style='width: 20%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
						<th style='width: 20%;text-align:left;border:1px solid #dddddd;'>Link</th>
					  </tr>
					
					  ".$html."
					  
					  
					  
					  
					<tr style='text-align:center;color: blue;'>
					  <td colspan='3' style='text-align:left;border: 1px solid #dddddd;'><b style='font-size:18px;color:black;'>Total</b></td>
					  <td  colspan='2' style='text-align:left;border: 1px solid #dddddd;'><b style='font-size:18px;color:black;margin-left:50px;'>$".$mysqlPackage['res_price']."</b></td>
					  </tr>
					</table>";
					*/
					
					
					/*
					$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
					$msgHTML ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html xmlns="http://www.w3.org/1999/xhtml">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
							<meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" name="viewport" />
							<title>Government Procurement Institute</title>
							
							<style type="text/css">
							
							<!-- ================ Start Media Queries ================== -->
								
							@media only screen and (min-width: 768px) {
									body[yahoofix] .mobileCenter                {width: 100%!important;}
									body[yahoofix] .contentWrapper              {width:100%!important;}
							}
							
							@media only screen and (max-width: 640px) {
									body[yahoofix] body                         {width:100%!important; -webkit-text-size-adjust: none;}
									body[yahoofix] table table                  {width:500px!important;}
									body[yahoofix] .emailWrapper             	{width:500px!important;}
									body[yahoofix] .contentWrapper              {width:480px!important;}
									body[yahoofix] .fullWidth					{width:480px!important;}
									body[yahoofix] .messageWrapper              {width:480px!important;}
									body[yahoofix] .headerScale					{width:480px!important;}
							}
							
							<!-- ================ End Media Queries ================== -->
								
							</style>
							
							</head>
							<body yahoofix style="margin: 0; padding:0;">
							
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="emailWrapper">
							  <tr>
								<td valign="top">    
									<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="mobileCenter" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color:#FFF;">
									  <tr>
										<td valign="middle" bgcolor="#fdd00f" height="150" align="center" style="text-align:center; background-color:#fdd00f;"><a href="#">'.$imgUrl.'</a></td>
									  </tr>
									  <tr>
										<td height="15">&nbsp;</td>
									  </tr>
									  <tr>
										<td>
											<table width="640" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
												<tr>
													<td>
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td height="60" align="center">
																	<h1 style="font-size:30px; font-family:Arial, Helvetica, sans-serif; color:#333; margin-top:0; padding:0;">
																		Order Detail
																	</h1>                                        
																</td>
															</tr>
															
															<tr>
																<td valign="top">
																	<table width="100%" border="0" cellspacing="2" cellpadding="5">
																	  <tr>
																		<td colspan="4" align="center" height="50" bgcolor="#666666" style="font-size:24px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:#FFF; background-color:#666; text-align:center;">PURCHASE ORDER SUMMARY</td>
																	  </tr>
																	  <tr>
																		<td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Item Name</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Discount</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Link</td>
																		
																	  </tr>
																	  <tr>
																		<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$fileName.'</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">$'.$mysqlPackage['res_price'].'</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">$0</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$downlaod_link.'</td>
																		
																	  </tr>
																	  <tr>
																		<td colspan="3" height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">Total</td>
																		<td height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">$'.$mysqlPackage['res_price'].'</td>
																	  </tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td height="20">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									  </tr>
									  <tr>
										<td bgcolor="#fdd00f" height="5"></td>
									  </tr>
									  <tr>
										<td bgcolor="#797878">
											<table width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
												  <tr>
													<td align="center" height="60" style="font-size:12px; font-family:Arial; color:#FFF;">
														Thanks for Purchasing Items.<br /><a href="http://gpiwin.com" style="color:#FFF;">Government Procurement Innovators, LLC</a>
													</td>
												  </tr>
											 </table>
										</td>
									  </tr>
									</table>
								</td>
							  </tr>
							</table>
							
							
							</body>
							</html>';
							
			}*/
					
					
					
					
					 $table = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td height="60" align="center">
											<h1 style="font-size:30px; font-family:Arial, Helvetica, sans-serif; color:#333; margin-top:0; padding:0;">
												Order Detail
											</h1>                                        
										</td>
									</tr>
									
									<tr>
										<td valign="top">
											<table width="100%" border="0" cellspacing="2" cellpadding="5">
											  <tr>
												<td colspan="4" align="center" height="50" bgcolor="#666666" style="font-size:24px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:#FFF; background-color:#666; text-align:center;">PURCHASE ORDER SUMMARY</td>
											  </tr>
											  <tr>
												<td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Item Name</td>
												<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td>
												<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Discount</td>
												<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Link</td>
												
											  </tr>
											  <tr>
												<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$fileName.'</td>
												<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">$'.$mysqlPackage['res_price'].'</td>
												<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">$0</td>
												<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$downlaod_link.'</td>
												
											  </tr>
											  <tr>
												<td colspan="3" height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">Total</td>
												<td height="36" bordercolor="#DDD" style="border:#DDD 1px solid; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#000; height:36px;">$'.$mysqlPackage['res_price'].'</td>
											  </tr>
											</table>
										</td>
									</tr>
									<tr>
										<td height="20">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
					
											
											
					
					
					$email_content = $this->common_model->select_single_field('before_content','email_template',array('id'=>14));
					$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>14));
					
					$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM guest_user WHERE guest_user_id = ".$guest_id."");
					$user = $userObj->row_array();
					$name = $user['name'];
					
					
					
					$healthy = array("{{name}}","{{order}}");
					$yummy   = array($name,$table);
					
					$string = str_replace($healthy,$yummy,$email_content);					
					$this->load->library('email');
					$useremail = $email;
					$useremail.=", aliakbar1to5@gmail.com";
					$useremail.=", ceo.appexos@gmail.com";
					$useremail .=' , paulakwatts.pw@gmail.com';
					$this->email->from('Adminstrator');

					$this->email->to($useremail);
					$this->email->subject('Order Detail');
					$this->email->message($string);
					//$this->email->message($msgHTML);
					$this->email->set_mailtype("html");
					$this->email->send();
					$this->session->set_flashdata('promo_msg1','Your order has been confirmed and file will be emailed to you.');
					header("Location: ".base_url()."package");
					
					
			
			#working code for signle file downlaod.
			/*
			{
			$this->load->helper('download');
			$rootPath = realpath('./assets/user/file_upload/');
			$zipname = time()."unlock-resources.zip";
			// Initialize archive object
			$zip = new ZipArchive();
			$myflag = 0;
			$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
			$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$pkg_id));						
			if(file_exists('./assets/user/file_upload/'.$fileName)){
				$myflag = 1;
				
				$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
			}
						
						
				if($myflag == 0){
					$this->session->set_flashdata('login_error','Sorry! File does not exists!');
					header("Location: ".base_url()."package");
				}
				
			//die;
			// Zip archive will be created only after closing object
			$zip->close();
			///Then download the zipped file.
			$file = './assets/resources_archive/'.$zipname;
			$file_name = basename($file);
			header("Content-Type: application/zip");
			header("Content-Disposition: attachment; filename=" . $file_name);
			header("Content-Length: " . filesize($file));	
			readfile($file);
			}
			*/
				
			
		}
			
		
	
		
		
	}
	
	
	 public function download_single($res_id) {
	   
	    $accessMember = $this->common_model->select_where('*','membership_access',array('item_id'=>$res_id,'user_id'=>$this->session->userdata('gpi_id'),'item_type'=>'res'));
		if($accessMember->num_rows() >0){
			$accessMember = $accessMember->row_array();
			$this->common_model->update_array(array('access_id'=>$accessMember['access_id']),'membership_access',array('lock_item'=>'yes'));
		}
	   
	
		$this->common_model->update_array(array('resources_id'=>$res_id),'resources',array('unlock_resource'=>0));
		$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$res_id));
		//load the download helper
		$this->load->helper('download');
		//set the textfile's content 
		if (file_exists('./assets/user/file_upload/'.$fileName)) {
		$data = file_get_contents('./assets/user/file_upload/'.$fileName);
		
		//set the textfile's name
		//use this function to force the session/browser to download the created file
		force_download($fileName, $data);
		}else{
			$folder_id = $this->common_model->select_single_field('folder_id','resources',array('resources_id'=>$res_id));
			$this->session->set_flashdata('errMsg','Sorry! File does not exists!');
			header("Location: ".base_url()."user/folder_view"."/".$folder_id);
		}
	}
	

	
   /*	public function download_mail($item_link){

		$pieces = explode("_", $item_link);

		$item_id =  $pieces[0];
		$type 	 =  $pieces[1];
		$user_id = $pieces[2];

		//$item_id,$type,$user_id
		if($type == "res"){

			$mysqlResources = $this->common_model->select_where('*','tbl_package_mail',array('user_id'=>$user_id,'item_id'=>$item_id,'item_type'=>$type));
			if($mysqlResources->num_rows() >0){


				$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$item_id));
				//load the download helper
				$this->load->helper('download');
				//set the textfile's content
				if (file_exists('./assets/user/file_upload/'.$fileName)) {
				$data = file_get_contents('./assets/user/file_upload/'.$fileName);
				 $this->common_model->delete_where(array('user_id'=>$user_id,'item_id'=>$item_id,'item_type'=>$type),'tbl_package_mail');

				//set the textfile's name
				//use this function to force the session/browser to download the created file
				force_download($fileName, $data);
			}else{

				$this->session->set_flashdata('error','You have already downloaded this file');
				header("Location: ".base_url('user').'/'."electures_view");
			}


			}else{

				$this->session->set_flashdata('error','You have already downloaded this file');
				header("Location: ".base_url('user').'/'."electures_view");
			}





		}else{

			$mysqlResources = $this->common_model->select_where('*','tbl_package_mail',array('user_id'=>$user_id,'item_id'=>$item_id,'item_type'=>$type));
			if($mysqlResources->num_rows() >0){


				$resourcesObj = $this->common_model->select_where('DISTINCT(resource_id) AS resource_id','tbl_package_resource_relation',array('pkg_id'=>$item_id));



					#end of download file
				if($resourcesObj->num_rows() >0){

					$this->load->helper('download');
					$rootPath = realpath('./assets/user/file_upload/');
					$zipname = time()."unlock-resources.zip";
					// Initialize archive object
					$zip = new ZipArchive();
					$myflag = 0;
					$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
						foreach($resourcesObj->result_array() as $resObj=>$resValue){

							$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resource_id']));
							//echo $fileName."<br />";

							if(file_exists('./assets/user/file_upload/'.$fileName)){
								$myflag = 1;
								$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);
							}
					}

					if($myflag == 0){
						$this->session->set_flashdata('login_error','Sorry! File does not exists!');
						header("Location: ".base_url()."package");
					}

					//die;
					// Zip archive will be created only after closing object
					$zip->close();
					///Then download the zipped file.

					 $this->common_model->delete_where(array('user_id'=>$user_id,'item_id'=>$item_id,'item_type'=>$type),'tbl_package_mail');

					$file = './assets/resources_archive/'.$zipname;
					$file_name = basename($file);
					header("Content-Type: application/zip");
					header("Content-Disposition: attachment; filename=" . $file_name);
					header("Content-Length: " . filesize($file));
					readfile($file);
				}else{

					$this->session->set_flashdata('login_error','Sorry! File does not exists!');
					header("Location: ".base_url()."package");

				}

		}else{

				$this->session->set_flashdata('login_error','You have already downloaded this package');
				header("Location: ".base_url()."package");

			}

	}
	}
*/
	public function download_mail_guest($item_link){

		$pieces = explode("_", $item_link);

		$item_id =  $pieces[0];
		$type 	 =  $pieces[1];

		
		//$item_id,$type,$user_id
		if($type == "res"){
		
			$mysqlResources = $this->common_model->select_where('*','tbl_package_mail',array('item_id'=>$item_id,'item_type'=>$type));
			
			if($mysqlResources->num_rows() >0){
				
				
				$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$item_id));
				//load the download helper
				$this->load->helper('download');
				//set the textfile's content 
				if (file_exists('./assets/user/file_upload/'.$fileName)) {
				$data = file_get_contents('./assets/user/file_upload/'.$fileName);
				 $this->common_model->delete_where(array('item_id'=>$item_id,'item_type'=>$type),'tbl_package_mail');
				 
				//set the textfile's name
				//use this function to force the session/browser to download the created file
				force_download($fileName, $data);
			}else{
				
				$this->session->set_flashdata('login_error','Sorry! File does not exists!');
				header("Location: ".base_url()."package");
			}
			

			}else{
				$this->session->set_flashdata('login_error','You have already downloaded this file');
				header("Location: ".base_url()."package");
			}
			
			
			
			
			
		}else{
			
			$mysqlResources = $this->common_model->select_where('*','tbl_package_mail',array('item_id'=>$item_id,'item_type'=>$type));
			if($mysqlResources->num_rows() >0){
			
	
				$resourcesObj = $this->common_model->select_where('DISTINCT(resource_id) AS resource_id','tbl_package_resource_relation',array('pkg_id'=>$item_id));
				
				
				
					#end of download file 
				if($resourcesObj->num_rows() >0){
					
					$this->load->helper('download');
					$rootPath = realpath('./assets/user/file_upload/');
					$zipname = time()."unlock-resources.zip";
					// Initialize archive object
					$zip = new ZipArchive();
					$myflag = 0;
					$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
						foreach($resourcesObj->result_array() as $resObj=>$resValue){
							
							$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resource_id']));
							//echo $fileName."<br />";
							
							if(file_exists('./assets/user/file_upload/'.$fileName)){
								$myflag = 1;
								$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
							}
					}
					
					if($myflag == 0){
						$this->session->set_flashdata('login_error','Sorry! File does not exists!');
						header("Location: ".base_url()."package");
					}
					
					//die;
					// Zip archive will be created only after closing object
					$zip->close();
					///Then download the zipped file.
					
					 $this->common_model->delete_where(array('item_id'=>$item_id,'item_type'=>$type),'tbl_package_mail');
					
					$file = './assets/resources_archive/'.$zipname;
					$file_name = basename($file);
					header("Content-Type: application/zip");
					header("Content-Disposition: attachment; filename=" . $file_name);
					header("Content-Length: " . filesize($file));
					readfile($file);
				}else{
					
					$this->session->set_flashdata('login_error','Sorry! File does not exists!');
					header("Location: ".base_url()."package");	
					
				}	
			
		}else{

				$this->session->set_flashdata('login_error','You have already downloaded this package');
				header("Location: ".base_url()."package");
			
			}	
		
	}
	}
	
	
	
	public function download_pkg($pkg_id,$pkg=''){
	
		$resourcesObj = $this->common_model->select_where('DISTINCT(resource_id) AS resource_id','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
			#end of download file
		if($resourcesObj->num_rows() >0){
			
			$this->load->helper('download');
			$rootPath = realpath('./assets/user/file_upload/');
			$zipname = time()."unlock-resources.zip";
			// Initialize archive object
			$zip = new ZipArchive();
			$myflag = 0;
			$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
				foreach($resourcesObj->result_array() as $resObj=>$resValue){
					
					$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resource_id']));
					//echo $fileName."<br />";
					
					if(file_exists('./assets/user/file_upload/'.$fileName)){
						$myflag = 1;
						$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
					}
			}
			
			if($myflag == 0){

                if($pkg !=""){
                       	$this->session->set_flashdata('login_error','Sorry! File does not exists!');
				        header("Location: ".base_url()."products");
                }else{

    				$this->session->set_flashdata('login_error','Sorry! File does not exists!');
    				header("Location: ".base_url()."package");
                }
			}
			
			//die;
			// Zip archive will be created only after closing object
			$zip->close();
			///Then download the zipped file.

			$user_id = $this->session->userdata('gpi_id');
			$this->common_model->update_array(array('user_id'=>$user_id,'pkg_id'=>$pkg_id),'tbl_package_access',array('status'=>'lock'));

			$file = './assets/resources_archive/'.$zipname;
			$file_name = basename($file);
			header("Content-Type: application/zip");
			header("Content-Disposition: attachment; filename=" . $file_name);
			header("Content-Length: " . filesize($file));
			readfile($file);
		}else{
            if($pkg !=""){
                $this->session->set_flashdata('login_error','Sorry! File does not exists!');
                header("Location: ".base_url()."products");
            }else{

    			$this->session->set_flashdata('login_error','Sorry! File does not exists!');
    			header("Location: ".base_url()."package");
            }
			
		}
			
		
	
		
		
	}

  }