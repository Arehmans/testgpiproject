<?php


  class Schedule_front extends CI_Controller
  {
	  function __construct() {
	 	 parent::__construct();
	  }


	public function destroy(){
		session_destroy();
		unset($_SESSION['mySessionId']);
		$this->session->unset_userdata('mySessionId');	
		$session = $this->session->userdata('mySessionId');
		echo "session_destory".$session;
	}
	
	
	
	public function guest_payment(){
		//	print_r($_POST);
			//print_r($this->session->all_userdata());die;
		
			$sessoinID = $this->input->post('sessoinID');
		
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
			 $insertedId =  $this->db->insert_id();
			 $this->session->set_userdata('guest_id',$insertedId);
			 $guest_id = $this->session->userdata('guest_id');
			  $partner_code = $this->input->post('partner_code');
			  if($partner_code !=""){
			  	$this->session->set_userdata('patner_id',$partner_code);
			  }
			
			
			$mysqlSessionObj =  $this->common_model->select_where('*','tbl_temp_cart_items',array('session_id'=>$sessoinID));
			
			if($mysqlSessionObj->num_rows() >0){
				$greaterValue = "";
				foreach($mysqlSessionObj->result_array() as $sessionPriceObj){
					if($sessionPriceObj['item_price'] >0){
						$greaterValue =1;	
					}
				}
				
				if($greaterValue == ""){
					
					 header("Location: ".base_url()."schedule_front/notify_payment"."/".$sessoinID);
					 break;
					
				}
				
				//echo $sessoinID;die;
				
				foreach($mysqlSessionObj->result_array() as $sessionObj){
					/*if($sessionObj['item_price'] < 1){
						
					}*/
					
					$config['business']				=  	$this->config->item('merchant_email');
					$config['cpp_header_image'] 	= 	''; //Image header url [750 pixels wide by 90 pixels high]
					$config['return'] 				= 	''.site_url().'schedule_front/notify_payment/'.$sessoinID;
					$config['cancel_return'] 		=   site_url();
					$config['notify_url'] 			=   site_url(); //IPN Post
					$config['production']			=  	$this->config->item('production_mode');
					$config["invoice"]				= 	random_string('numeric',8); //The invoice id
					$this->load->library('paypal',$config);
					$itemName = $sessionObj['item_name'];
					$price 	  = $sessionObj['item_price'];
					if($sessionObj['item_type'] == 'schedule'){
						$myRunTimeArr = explode("_",$sessionObj['item_name']);
						$itemName = $myRunTimeArr[1]." (".$myRunTimeArr[0].")";
					}
				
					$this->paypal->add($itemName,$price,$sessionObj['item_qty']); //First item
					$this->paypal->pay(); //Proccess the payment
					
				}	
			}
		 }
	}
	
	public function notify_payment($sessoinID){
		
		$guest_id = $this->session->userdata('guest_id');
		$html = "";
		$mysql_guest = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name,email FROM guest_user WHERE guest_user_id = ".$guest_id."");
		$mysql_guest = $mysql_guest->row_array();
		$name = $mysql_guest['name'];
		$email = $mysql_guest['email'];
		
		$mysql_tempCarts = $this->common_model->select_where('*','tbl_temp_cart_items',array('session_id'=>$sessoinID));
		
		//print_r($mysql_tempCarts->result_array());die;
		if($mysql_tempCarts->num_rows() >0){
			
			foreach($mysql_tempCarts->result_array() as $tempObj){
				
			
				
				 if($tempObj['item_type'] == "res"){
						 $download_mail_ref=$tempObj['item_id']."_".'res';
						 $downlaod_link = "<a href=".base_url('package/download_mail_guest'.'/'.$download_mail_ref).">Download</a>";
						  $pkg_array = array('item_id'=>$tempObj['item_id'],'item_type'=>'res');
						  $res_ids = $this->common_model->insert_array('tbl_package_mail',$pkg_array);
						 // echo "$res_ids".$res_ids."<br />";
					  	  $price = $this->common_model->select_single_field('res_price','resources',array('resources_id'=>$tempObj['item_id'])); 
													  
					 $html .= '<tr>
							<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$tempObj['item_name'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">Resource</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_price'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_price'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$downlaod_link.'</td>
						  </tr>';

				  }else if($tempObj['item_type'] == "pkg"){	

					$download_mail_ref=$tempObj['item_id']."_".'pkg';
					$downlaod_link = "<a href=".base_url('package/download_mail_guest'.'/'.$download_mail_ref).">Download</a>";
					$pkg_array = array('item_id'=>$tempObj['item_id'],'item_type'=>'pkg');
					$pgk_dis = $this->common_model->insert_array('tbl_package_mail',$pkg_array);
					//echo "$pgk_dis".$pgk_dis."<br />";
					
					
								  
					 $html .= '<tr>
							<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$tempObj['item_name'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">Package</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_qty'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_price'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$downlaod_link.'</td>
						  </tr>';
					
					
					
					
				}else if($tempObj['item_type'] == 'class'){
					
					    $partner_code = $this->session->userdata('patner_id');
						
					  	if($partner_code !=""){		
						
						/*adding partners to partner table for reports.*/
								$parnter_id = $this->common_model->select_single_field('id','tbl_partners',array('partner_code'=>$partner_code));
								
								$insertedArr['name'] = $name;
								$insertedArr['email']= $email;
								$insertedArr['partner_id']   = $parnter_id;
								$insertedArr['partner_code'] = $partner_code;
								$insertedArr['class_id']	 = $tempObj['class_id'];
								$insertedArr['ticket_id']	 = $tempObj['item_id'];
								$insertedArr['qty'] 		 = $tempObj['item_qty'];
								$totalPP = $tempObj['item_qty'] * $tempObj['item_price'];
								$insertedArr['price'] = $totalPP;
								$cc_id = $this->common_model->insert_array('tbl_class_partner',$insertedArr);
							//	echo "partner_id".$cc_id."<br />";
							}
						/*end adding partners to partner table for reports.*/
						$qtydb=$this->gpi_model->getrecordbyidrow("tickets","ticket_id",$tempObj['item_id']);
						//	$this->input->post("quantity1");
							$remainqty=$qtydb->ticket_qty-$tempObj['item_qty'];
						$cls_level = $this->common_model->select_single_field('level_id','classes',array('class_id'=>$tempObj['class_id']));
						 $data=array('ticket_qty'=>$remainqty );
								 $flag=0;
								 if($this->session->userdata('gpi_id') != "") {
								   $flag=1;
								 }
						 $data1=array(
									 'class_id' =>$tempObj['class_id'],
									 'ticket_id' =>$tempObj['item_id'],
									 'qty' =>$tempObj['item_qty'],
									 'ticket_date' =>$qtydb->date_id,
									 'user_id' =>$guest_id,
									 'level_id'=>$cls_level,
									 'flag' =>$flag
									 );		 
									 
						 $this->gpi_model->update($data,"tickets",$tempObj['item_id'],"ticket_id");
					//	 echo $this->db->last_query()."<br />";
						 $ticketSell = $this->gpi_model->insert($data1,"ticket_sell");
					//	 echo "ticketSell".$ticketSell."<br />";
						 
						  $html .= '<tr>
							<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$tempObj['item_name'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.ucfirst($tempObj['item_type']).'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_qty'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_price'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">N/A</td>
						  </tr>';
						 
						 
					
				 }	else if($tempObj['item_type'] == "schedule"){
					
					
						$shedultArr['sch_name']		 =  $tempObj['item_name'];
						$shedultArr['sch_id']	 	 =  $tempObj['item_id'];
						$shedultArr['sch_price']	 =  $tempObj['item_price'];
						$shedultArr['sch_discount']	 =  0;
						$shedultArr['user_id']	 	 =  $guest_id;
						$shedultArr['guest_user']	 =  1;
						$shedultArr['sch_notes']	 =  $tempObj['notes'];
						$shedultArr['date_varify_start']	 =  $tempObj['date_varify_start'];
						$shedultArr['date_varify_end']	     =  $tempObj['date_varify_end'];
						$itemFull = explode("_",$tempObj['item_name']);
						$mysqlOPtionId  = $this->common_model->select_single_field('id','tbl_service',array('ser_name'=>$itemFull[1]));
						$shedultArr['option_id'] = $mysqlOPtionId;
						$sch_id  = $this->common_model->insert_array('tbl_schedule_cart',$shedultArr);
						$item_name = explode("_",$shedultArr['sch_name']);
						$itemName = $item_name[1]." ( ".$item_name[0]." ) ";			
						$this->common_model->insert_array('tbl_reminder',array('item_name'=>$itemName,'sch_date'=>strtotime($item_name[0]),'user_id'=>$guest_id,'sch_id'=>$tempObj['item_id'],'email'=>$email,'item_price'=>$tempObj['item_price'],'remember_time'=>'1day,4hours','notes'=>$tempObj['notes']));
						
						$mysql_recurring = $this->common_model->select_where('*','tbl_schedule_recurring',array('sch_id'=>$tempObj['item_id']));
						$mysql_batch_recurring = array();
						
						if($mysql_recurring->num_rows() >0){
							foreach($mysql_recurring->result_array() as $recurrObj){
									$mysql_batch_recurring[] = array('item_name'=>$itemName,'sch_date'=>$recurrObj['recurring_date'],'user_id'=>$guest_id,'sch_id'=>$tempObj['item_id'],'email'=>$email,'item_price'=>$tempObj['item_price'],'remember_time'=>'1day,4hours','notes'=>$tempObj['notes']);
							}	
						}
						if(!empty($mysql_batch_recurring)){
							$this->db->insert_batch('tbl_reminder', $mysql_batch_recurring); 	
						}
						
						
						//'.ucfirst($tempObj['item_type']).'		    
					 $html .= '<tr>
							<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$itemFull[1].' ('.$itemFull[0].')'.'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">Meeting</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_qty'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tempObj['item_price'].'</td>
							<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">N/A</td>
						  </tr>';

						
						/*  $emailUser = $this->common_model->select_single_field('email','guest_user',array('user_id'=>$guest_id));
				$this->common_model->insert_array('tbl_reminder',array('item_name'=>$itemFull[1].'('.$itemFull[0].')','sch_date'=>strtotime($date),'end_date'=>strtotime($end_DATE_TIME),'user_id'=>$guest_id,'sch_id'=>$mysqlObj['sch_id'],'email'=>$emailUser,'item_price'=>$mysql_adminAppointment['event_price'],'remember_time'=>$remember_time));*/
				
				 }
			}
			
			/*$table = '<table width="640" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                	<tr>
                    	<td>
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                            	<tr>
                                	<td height="60" align="center">
                                    	<h1 style="font-size:30px; font-family:Arial, Helvetica, sans-serif; color:#333; margin-top:0; padding:0;">
                                        	GPI Registration
                                        </h1>                                        
                                    </td>
                                </tr>
                                <tr>
                                	<td valign="top" height="80" align="center" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#333;">
                                    	Following is your GPI Registration details!
                                    </td>
                                </tr>
                                <tr>
                                	<td valign="top">
                                    	<table width="100%" border="0" cellspacing="2" cellpadding="5">
                                          <tr>
                                            <td colspan="6" align="center" height="50" bgcolor="#666666" style="font-size:24px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:#FFF; background-color:#666; text-align:center;">GPI REGISTRATION SUMMARY</td>
                                          </tr>
                                          <tr>
                                            <td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Item Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Type</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Quantity</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td>
											 <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Download Link</td>
											
                                          </tr>
                                        '.$html.'
                                        
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td height="20">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';*/
				
				
				$table = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
															
							<tr>
								<td valign="top">
									<table width="100%" border="0" cellspacing="2" cellpadding="5">
									  <tr>
										<td colspan="5" align="center" height="50" bgcolor="#666666" style="font-size:24px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:#FFF; background-color:#666; text-align:center;">PURCHASE ORDER SUMMARY</td>
									  </tr>
									  <tr>
										<td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Item Name</td>
										<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Type</td>
										<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Quantity</td>
										<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td>
										<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Download Link</td>
									  </tr>
									 '.$html.'
									</table>
								</td>
							</tr>
						</table>';
														
													
				$email_content = $this->common_model->select_single_field('before_content','email_template',array('id'=>14));
				$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>14));
				$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name,email FROM guest_user WHERE guest_user_id = ".$guest_id."");
				$user = $userObj->row_array();
				$name = $user['name'];
				$email_user = $user['email'];
				$healthy = array("{{name}}","{{order}}");
				$yummy   = array($name,$table);
				$string = str_replace($healthy,$yummy,$email_content);
				$this->load->library('email');
				$useremail = $email_user;
				$useremail .=", aliakbar1to5@gmail.com";
				$useremail.=", ceo.appexos@gmail.com";
				$useremail .=' , paulakwatts.pw@gmail.com';
				$this->email->from('Adminstrator');
				
				 $headers = "MIME-Version: 1.0" . "\r\n";
    			 $headers .= "Content-type:text/html;charset=UTF-8" . "\r\b";
				 if(@mail($useremail,'Order Detail',$string,$headers)){}
					$this->common_model->delete_where(array('session_id'=>$sessoinID),'tbl_temp_cart_items');
					$this->session->set_flashdata('promo_msg1','Your order has been confirmed. Please check your email for further details.');
					header("Location: ".base_url()."package");
		}			
	}
	
	public function login_payment(){
		
		//echo "Here";
		$login_session_id = $this->input->post('login_session_id');
		$this->session->set_userdata('login_session_id',$login_session_id);
		$login=$this->gpi_model->get_client_login($this->input->post("email"), $this->input->post('password'));
			if($login) 
			{
				$this->session->set_userdata("gpi_id", $login->user_id);
			        $data=array(
		    		'online'=>1,
		  		);
			  $this->gpi_model->update($data,"users",$login->user_id,"user_id"); 
			  
			  $mysql_tempraryPCart  =$this->common_model->select_where('*','tbl_temp_cart_items',array('session_id'=>$login_session_id));
				 if($mysql_tempraryPCart->num_rows() >0 ){
						 foreach($mysql_tempraryPCart->result_array() as $tempObj){	
						 
						
						 	if($tempObj['item_type'] == "pkg"){
						 	
								// if package is already exist in table	  
								$mysqlAccess =   $this->common_model->select_where('*','tbl_package_access',array('pkg_id'=>$tempObj['item_id'],'user_id'=>$login->user_id));
								if($mysqlAccess->num_rows() >0){
									$mysqlAccess = $mysqlAccess->row_array();
									$access_id   = $mysqlAccess['access_id']; 
									$this->common_model->update_array(array('access_id'=>$access_id),'tbl_package_access',array('status'=>'added'));
									  $cart_array = array();
									  $cart_array['item_id']    = $access_id;
									  $cart_array['item_type']  = 'pkg';
									  $cart_array['user_id'] 	= $login->user_id;
									
									  $this->common_model->insert_array('tbl_cart',$cart_array);
									//  $this->session->set_flashdata('promo_msg1','Package has been added to cart. Go to Profile section to complete the purchase.');
									//  redirect(base_url()."package/");
								}
								else{
										 
											  $access_array = array();
											  $access_array['type']  	  = 'pkg';
											  $access_array['user_id'] 	  = $login->user_id;
											  $access_array['status']	  = 'added';
											  $access_array['pkg_id']	  = $tempObj['item_id'];	
											  $access_id =  $this->common_model->insert_array('tbl_package_access',$access_array);
											  $cart_array = array();
											  $cart_array['item_id']    = $access_id;
											  $cart_array['item_type']  = 'pkg';
											  $cart_array['user_id'] 	= $login->user_id;
											  $this->common_model->insert_array('tbl_cart',$cart_array);
										}
							}else if($tempObj['item_type'] == "res"){
								
							    $access_array = array();
							    $access_array['item_type']  = 'res';
							    $access_array['user_id'] 	  = $login->user_id;
							    $access_array['lock_item']  = 'no';
							    $access_array['item_id']	  = $tempObj['item_id'];
								$access_id = "";
								$mysqlRes =    $this->common_model->select_where("*","membership_access",array("item_id"=>$tempObj['item_id'],"user_id"=>$login->user_id));
								if($mysqlRes->num_rows() >0){
									$mysqlRes = $mysqlRes->row_array();
									$access_id = $mysqlRes['access_id'];
									$this->common_model->update_array(array("access_id"=>$access_id),"membership_access",$access_array);
								}else{
									 $access_id =  $this->common_model->insert_array('membership_access',$access_array);
								}
								$cart_array = array();
								$cart_array['item_id']    = $tempObj['item_id'];
								$cart_array['item_type']  = 'res';
								$cart_array['user_id'] 	= $login->user_id;
								$cart_qrray['qty']		= 1;
								$this->common_model->insert_array('tbl_cart',$cart_array);
								  
								  // $this->session->set_flashdata('promo_msg1','Package has been added to cart. Go to Profile section to complete the purchase. ');
							}
							else if($tempObj['item_type'] == "class"){
								
								$class_id=$this->input->post("id");
								$insert_array = array();
								$insert_array['item_id'] = $tempObj['class_id'];
								$insert_array['item_type'] = 'class';
								$insert_array['user_id'] = $this->session->userdata('gpi_id');
								$insert_array['qty'] = $tempObj['item_qty'];
								$insert_array['ticket_id'] = $tempObj['item_id'];
								$this->common_model->insert_array('tbl_cart',$insert_array);
								
							}else if($tempObj['item_type'] == "schedule"){
								$insert_array_schedule['item_id'] = $tempObj['item_id'];
								$insert_array_schedule['item_type'] = 'schedule';
								$insert_array_schedule['user_id'] = $this->session->userdata('gpi_id');
								$insert_array_schedule['qty'] = 1;
								$insert_array_schedule['sch_notes'] = $tempObj['notes'];
								$insert_array_schedule['date_varify_start']  = $tempObj['date_varify_start'];
								$insert_array_schedule['date_varify_end'] 	 = $tempObj['date_varify_end'];
								//print_r($insert_array_schedule);die;
								$this->common_model->insert_array('tbl_cart',$insert_array_schedule);
								
							}
						 }
				 }
				 //	$this->common_model->delete_where(array('session_id'=>$login_session_id),'tbl_temp_cart_items');
				//   $this->session->set_flashdata('promo_msg1','Package has been added to cart. Go to Profile section to complete the purchase.');
				   redirect(base_url()."user/cart_view");
			}
			else
			{
				    $data1=$this->session->set_flashdata('login_error','Wrong Email And Password');
					redirect(base_url()."package/"); 
			}
	}
	
	
	/*function login_payment(){
				
		$login=$this->gpi_model->get_client_login($this->input->post("email"), $this->input->post('password'));
           // $id=$login->user_id;
			if($login) 
			{
				$this->session->set_userdata("gpi_id", $login->user_id);
				
			        $data=array(
		    		'online'=>1,
		  		);
			  $this->gpi_model->update($data,"users",$login->user_id,"user_id"); 
			  
			$mysqlAccess =   $this->common_model->select_where('*','tbl_package_access',array('pkg_id'=>$pkg_id,'user_id'=>$login->user_id));
			if($mysqlAccess->num_rows() >0){
				$mysqlAccess = $mysqlAccess->row_array();
				$access_id = $mysqlAccess['access_id']; 
				$this->common_model->update_array(array('access_id'=>$access_id),'tbl_package_access',array('status'=>'added'));
				
				  $cart_array = array();
				  $cart_array['item_id']    = $access_id;
				  $cart_array['item_type']  = 'pkg';
				  $cart_array['user_id'] 	= $login->user_id;
				  $this->common_model->insert_array('tbl_cart',$cart_array);
				  
				  $this->session->set_flashdata('promo_msg1','Package has been added to cart. Go to Profile section to complete the purchase.');
				  redirect(base_url()."package/");
					
				
			}else{
				
					 if($pkg_id !=""){

						  $access_array = array();
						  $access_array['type']  = 'pkg';
						  $access_array['user_id'] 	  = $login->user_id;
						  $access_array['status']	  = 'added';
						  $access_array['pkg_id']	  = $pkg_id;	
						  $access_id =  $this->common_model->insert_array('tbl_package_access',$access_array);

						  $cart_array = array();
						  $cart_array['item_id']    = $access_id;
						  $cart_array['item_type']  = 'pkg';
						  $cart_array['user_id'] 	= $login->user_id;
						  $this->common_model->insert_array('tbl_cart',$cart_array);
						  
						   $this->session->set_flashdata('promo_msg1','Package has been added to cart. Go to Profile section to complete the purchase.');
						   redirect(base_url()."package/"); 
					 }else{
						 
						  $access_array = array();
						  $access_array['item_type']  = 'res';
						  $access_array['user_id'] 	  = $login->user_id;
						  $access_array['lock_item']  = 'no';
						  $access_array['item_id']	  = $res_id;
						  
						  $access_id = "";
						$mysqlRes =    $this->common_model->select_where("*","membership_access",array("item_id"=>$res_id,"user_id"=>$login->user_id));
						if($mysqlRes->num_rows() >0){
							$mysqlRes = $mysqlRes->row_array();
							$access_id = $mysqlRes['access_id'];
							$this->common_model->update_array(array("access_id"=>$access_id),"membership_access",$access_array);
						}else{
							 $access_id =  $this->common_model->insert_array('membership_access',$access_array);
						}
						
						  $cart_array = array();
						  $cart_array['item_id']    = $res_id;
						  $cart_array['item_type']  = 'res';
						  $cart_array['user_id'] 	= $login->user_id;
						  $cart_qrray['qty']		= 1;
						  $this->common_model->insert_array('tbl_cart',$cart_array);
						  
						   $this->session->set_flashdata('promo_msg1','Package has been added to cart. Go to Profile section to complete the purchase. ');
						   redirect(base_url()."package/"); 
						 
					  }
				
					}
			
			  
			}
			else
			{
				    $data1=$this->session->set_flashdata('login_error','Wrong Email And Password');
					redirect(base_url()."package/"); 
			}
		
	}
	*/
	
	
	public  function index()
	  {
		 // $mysql_realTime = $this->common_model->select_all('*','tbl_real_time');
		  $mysql_realTime = $this->db->query("SELECT * FROM tbl_real_time");
		  //$mysql_services = $this->common_model->select_all('*','tbl_service');
		  $mysql_services = $this->db->query("SELECT * FROM tbl_service ORDER by service_order ASC");
		  $data['mysql_services'] = $mysql_services;
		  $data['mysql_realtime'] = $mysql_realTime;
		  $data['start_attr'] = 0;
		  $data['end_attr'] = 7;
		  $data['class'] = 'schedule_front';
		  $data['content'] = 'schedule/schedule_view';
		  $this->load->view('layout/layout',$data);

 	  }
	  
	  function loadCart(){
		 $data['session_id'] =  $this->input->post('session_id');
		 $cartItems = $this->common_model->select_where('*','tbl_temp_cart_items',array('session_id'=>$data['session_id']));
	   	 $data['cartItems'] = $cartItems;
		 echo $this->load->view('schedule/ajax_cart_view',$data);	  
	 }
	 
	 
	  
	  public function schedule(){

		 $chap =  $this->input->post('chap');
		 $start =  $this->input->post('start');
		 $end =  $this->input->post('end');
		 $option_id =  $this->input->post('option_id');
		 $session_id =  $this->input->post('session_id');
		 $data['chap_1'] =  $chap;
		 $data['start_attr'] =  $start;
		 $data['end_attr'] =  $end;
		 $data['option_id'] = $option_id;
		 $data['sessionID'] = $session_id;
		 $html =  $this->load->view('schedule/ajax_shedule_view',$data);
		 echo $html;
		 		
	  }

}


	  


  


  


  


