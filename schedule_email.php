<?php
  class Schedule_email extends CI_Controller
  { 

	    function __construct() {
    
		   parent::__construct();

		  ini_set('error_reporting', E_ALL);
		  ini_set('display_errors', 'On');  //On or Off
		  
	   }
	  	public function add_schedule_item($sch_id="",$user_id=""){
		$mysql_records =    $this->common_model->select_where('*','tbl_schedule',array('id'=>$sch_id));
		if($mysql_records->num_rows() >0){
			$mysqlUser = $this->common_model->select_where("*","users",array("user_id"=>$user_id));
			if($mysqlUser->num_rows() >0){
				$mysql_records = $mysql_records->row_array();
				$cartArray['item_id'] = $mysql_records['id'];
				$cartArray['item_type']= 'schedule';
				$cartArray['qty'] = 1;
				$cartArray['user_id'] = $user_id;
				$cartArray['ticket_id'] = 1;
				if($mysql_records['event_status'] == 1){
					$inser_id = $this->common_model->insert_array('tbl_cart',$cartArray);
					$this->session->set_flashdata('msg','Meeting has been added to your cart, please Login to proceed with payments.');
					redirect('login');
				}else{
					$sch_name = $mysql_records['start_date']."_".$mysql_records['sch_name'];
					$this->common_model->insert_array('tbl_schedule_cart',array('sch_name'=>$sch_name,"sch_id"=>$mysql_records['id']."_admin","sch_price"=>0,"sch_discount"=>0,"option_id"=>$mysql_records['option_id'],"user_id"=>$user_id,"lock_item"=>"free","status"=>"active"));
					$this->common_model->update_array(array('sch_id'=>$mysql_records['id']),'tbl_real_time',array('type'=>1));
					$this->session->set_flashdata('msg','Your order has been confirmed. Please check your email for further details.');	
					redirect('login');	
				}
			}else{
				
					$mysql_records = $mysql_records->row_array();
					$sch_name = $mysql_records['start_date']." ( ".$mysql_records['sch_name'] ." )";
					$mysql_OBJ = $this->common_model->select_where("*","schedule_guest_email_content",array("user_id"=>$user_id));
					if($mysql_OBJ->num_rows() >0){
						$mysql_OBJ = $mysql_OBJ->row_array();
						$this->common_model->insert_array('tbl_reminder',array('item_name'=>$sch_name,'sch_date'=>strtotime($mysql_records['start_date']),'user_id'=>$user_id,'sch_id'=>$mysql_records['id'],'email'=>$mysql_OBJ['email'],'item_price'=>$mysql_records['event_price'],'remember_time'=>'1day,4hours','notes'=>$mysql_records['note']));
					}
					
					if($mysql_records['event_status'] == 1){
						$config['return'] 				= ''.site_url().'schedule_email/cart_paypal_success/'.$user_id.'/'.$mysql_records['id'];
						$config['cancel_return'] 		= ''.site_url().'schedule_email/cart_cancel_paypal/'.$user_id;
						$config['notify_url'] 			= ''.site_url().'schedule_email/cart_cancel_paypal/'.$user_id; //IPN Post
						$config['business']				=  $this->config->item('merchant_email');
						$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
						$config['production']			=  $this->config->item('production_mode');
						$config["invoice"]				= random_string('numeric',8); //The invoice id
						$this->load->library('paypal',$config);
						$this->paypal->add($sch_name,$mysql_records['event_price'],1); //First item
						$this->paypal->pay(); //Proccess the payment
					}else{
						
						$this->common_model->update_array(array('sch_id'=>$mysql_records['id']),'tbl_real_time',array('type'=>1));
						$this->session->set_flashdata('msg','Your order has been confirmed. Please check your email for further details.');	
						redirect('login');	
					}
			}
		}
 	} 
	
	public function cart_paypal_success($user_id,$sch_id=''){
		
		$headers = array("From: from@example.com",
				"Reply-To: replyto@example.com",
				"Content-type: text/html; charset=iso-8859-1",
				"X-Mailer: PHP/" . PHP_VERSION
			);
			$headers = implode("\r\n", $headers);
		
		$this->common_model->update_array(array('sch_id'=>$sch_id),'tbl_real_time',array('type'=>1));
		
		//echo $this->db->last_query()."<br />";
		$mysql_email = $this->common_model->select_where("*","schedule_guest_email_content",array("user_id"=>$user_id));
		$this->common_model->update_array(array('user_id'=>$user_id),'tbl_schedule_cart',array('status'=>'active'));
		if($mysql_email->num_rows() >0){
			$mysql_email = $mysql_email->row_array();
			$email_content = $mysql_email['email_content'];
			$email =  $mysql_email['email'];
			
			$s = @mail("'".$email."'", "Meeting Appointment", $email_content, $headers);
			if($s){
			//	$this->common_model->delete_where(array('user_id'=>$user_id),'schedule_guest_email_content');
				$this->session->set_flashdata('msg','Your order has been confirmed. Please check your email for further details.');	
				redirect('login');	
			}else{
				$this->session->set_flashdata('login_msg','Your order has been confirmed.Sorry Email cannot sent!');	
				redirect('login');	
			}
		}else{
			$this->session->set_flashdata('login_msg','Your order has been already confirmed!');	
			redirect('login');	
		}
	}
	
	public function cart_cancel_paypal($user_id){
		$this->session->set_flashdata('msg','Meeting has been added to your cart, please Login to proceed with payments.');
		redirect('login');
	}
	
	public function reject_item($sch_id = "",$user_id=""){
		$mysql_schedule = $this->common_model->select_where("*","tbl_schedule",array("id"=>$sch_id));	
		
		if($mysql_schedule->num_rows() >0){
			$mysql_schedule = $mysql_schedule->row_array();
			$rec_email = $mysql_schedule['receiver_emails'];
			$receiver_emails = explode(",",$rec_email);
			$pos = array_search($user_id, $receiver_emails);
			unset($receiver_emails[$pos]);
			$update_string = implode(",",$receiver_emails);
			$this->common_model->update_array(array('id'=>$sch_id),'tbl_schedule',array('receiver_emails'=>$update_string));
			$this->session->set_flashdata('msg','Appointment has been rejected sucessfully!');
			redirect('login');
				
		}
	}
	
	  
  }