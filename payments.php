<?php

class Payments extends CI_Controller{

	function __construct() {
     
       parent::__construct();
    //  if($this->session->userdata("gpi_id") == "") {
      //redirect(base_url()."login");
   //  }
	 $this->load->helper('string');

	}
	
	
	function codeExists(){
		$partner_code  = $this->input->post('partner_code');
		$mysqlPartner = $this->common_model->select_where('*','tbl_partners',array('partner_code'=>$partner_code));
		echo $mysqlPartner->num_rows();
		
	}

	public function do_purchase(){
		
		//print_r($_POST);
		
		 /*if(isset($_POST['guest_user']))
		  {*/
			// echo "hello";
			if($this->input->post("poppop_login")==1){
				$login=$this->gpi_model->get_client_login($this->input->post("email"), $this->input->post('password'));
           // $id=$login->user_id;
			if($login) 
			{
				$this->session->set_userdata("gpi_id", $login->user_id);
				$ticket_id = $this->input->post("ticket_id");
				$this->session->set_userdata("myTicket_id",$ticket_id);
				
			        $data=array(
		    		'online'=>1,
		  		);
			  $this->gpi_model->update($data,"users",$login->user_id,"user_id"); 
			}
			else
			{
				
				    $data1=$this->session->set_flashdata('login_error','Wrong Email And Password');
					redirect(base_url()."classes/classes_view/".$this->input->post("id"),$data1); 
					
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
			 
			 $partner_code = $this->input->post('partner_code');
			 
			// echo $guest_id; 
			$this->session->set_userdata("guest_id",$guest_id);
			$ticket1_id=$this->input->post("ticket_id");
			$level_id=$this->input->post("level_id");
			$class_id=$this->input->post("class_id");
			$user=$this->session->userdata('guest_id');	 
			$class_name=$_REQUEST['class_name'];
			$price=$_REQUEST['price'];
			$qty=$_REQUEST['qty'];
		//	echo 'https://www.gpiwin.com/payments/notify_payment/'.$ticket1_id.'/'.$class_id.'/'.$user.'/'.$level_id;die;
		
			
			
			$this->session->set_userdata("tqty",$qty);
			
			
			
			
			//echo "quantiry".$qty;
			
			/*echo $ticket1_id."<br />";
			echo $class_id."<br />";
			echo $user."<br />";
			echo $level_id."<br />";
			echo $partner_code."<br />";
			
			
			die;
			*/
			
		//	$config['business'] 			= 'testingbusiness8877@gmail.com';
			//$config['business'] 			= 'Pwatts@gpiwin.com';
			$config['business']				=  $this->config->item('merchant_email');
			
			$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
			
			//echo $ticket1_id.'/'.$class_id.'/'.$user.'/'.$level_id.'/'.$partner_code;die;
			$config['return'] 				= ''.site_url().'payments/notify_payment/'.$ticket1_id.'/'.$class_id.'/'.$user.'/'.$level_id.'/'.$partner_code;
			
			/*$config['cancel_return'] 		= 'https://gpiwin.com/';
			$config['notify_url'] 			= 'https://gpiwin.com/'; //IPN Post */
             $config['cancel_return'] 		= ''.site_url().'';
			$config['notify_url'] 			= ''.site_url().''; //IPN Post 

			//$config['production'] 			= TRUE; //Its false by default and will use sandbox
			$config['production']			=  $this->config->item('production_mode');
			
			
			$config["invoice"]				=  random_string('numeric',8); //The invoice id
			
			$this->load->library('paypal',$config);
			
			#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
			$this->paypal->add($class_name,$price,$qty); //First item
			$this->paypal->pay(); //Proccess the payment

		 //////////////////////////////////////////////////////////////////////////////////////
		 }
		 else
		 {
			
			$class_id=$this->input->post("id");
			$insert_array = array();
			$insert_array['item_id'] = $this->input->post("class_id");
			$insert_array['item_type'] = 'class';
			$insert_array['user_id'] = $this->session->userdata('gpi_id');
			$insert_array['qty'] = $_REQUEST['qty'];
			$insert_array['ticket_id'] = $this->input->post('ticket_id');
			
			//$insert_array['']
		
			$this->common_model->insert_array('tbl_cart',$insert_array);
			$data1=$this->session->set_flashdata('promo_msg1','Class is added to cart.');
		  
		    redirect(base_url()."classes/classes_view"."/".$this->input->post("class_id"),$data1); 
		  
		  
			/*
			 // echo "ello";
			//  exit;
			   
			  $ticket1_id=$this->input->post("ticket_id");
			  $level_id=$this->input->post("level_id");
				
			$class_id=$this->input->post("class_id");
			$user=$this->session->userdata('gpi_id'); 
			$class_name=$_REQUEST['class_name'];
			$price=$_REQUEST['price'];
			$qty=$_REQUEST['qty'];
			
			$this->session->set_userdata("tqty",$qty);
		///	echo $qty;
			//exit;
			$config['business'] 			= 'Pwatts@gpiwin.com';
			$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
			$config['return'] 				= 'https://gpiwin.com/payments/notify_payment/'.$ticket1_id.'/'.$class_id.'/'.$user.'/'.$level_id;
			$config['cancel_return'] 		= 'https://gpiwin.com/';
			$config['notify_url'] 			= 'https://gpiwin.com/'; //IPN Post
			$config['production'] 			= TRUE; //Its false by default and will use sandbox
			$config["invoice"]				= random_string('numeric',8); //The invoice id
			
			$this->load->library('paypal',$config);
			
			#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
			
			
			
			$this->paypal->add($class_name,$price,$qty); //First item
			
			
			$this->paypal->pay(); //Proccess the payment
			*/
		
	  }
	}
	public function notify_payment($t_id,$classid,$user_id,$level_id,$partner_code=''){
		
		$minusQty = $this->session->userdata('tqty');
		
		
		if($partner_code !=""){
		
		
			
			
			/*adding partners to partner table for reports.*/
			
				$parnter_id = $this->common_model->select_single_field('id','tbl_partners',array('partner_code'=>$partner_code));
				$mysqlGuest = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name,email FROM guest_user WHERE guest_user_id = ".$user_id."");
				$mysqlGuest = $mysqlGuest->row_array();
				$insertedArr['name'] = $mysqlGuest['name'];
				$insertedArr['email']= $mysqlGuest['email'];
				$insertedArr['partner_id']   = $parnter_id;
				$insertedArr['partner_code'] = $partner_code;
				$insertedArr['class_id']	 = $classid;
				$insertedArr['ticket_id']	 = $t_id;
				$insertedArr['qty'] = $minusQty;
				$price = $this->common_model->select_single_field('price','tickets',array('ticket_id'=>$t_id));
				$totalPP = $minusQty * $price;
				$insertedArr['price'] = $totalPP;
				//print_r($insertedArr);die;
				$this->common_model->insert_array('tbl_class_partner',$insertedArr);
				
			/*end adding partners to partner table for reports.*/
		
		}

		$received_data = print_r($this->input->post(),TRUE);
		$qtydb=$this->gpi_model->getrecordbyidrow("tickets","ticket_id",$t_id);
		
	//	$this->input->post("quantity1");
		$remainqty=$qtydb->ticket_qty-$minusQty;
		
		
		
	 
	 $data=array(
	           'ticket_qty'=>$remainqty,
	            );
			 $flag=0;
			 if($this->session->userdata('gpi_id') != "")
			 {
			   $flag=1;
			 }
	 $data1=array(
	             'class_id' =>$classid,
				 'ticket_id' =>$t_id,
				 'qty' =>$minusQty,
				 'ticket_date' =>$qtydb->date_id,
				 'user_id' =>$user_id,
				 'level_id'=>$level_id,
				 'flag' =>$flag
				 );		 
				 
				
     $this->gpi_model->update($data,"tickets",$t_id,"ticket_id");
	
	 $this->gpi_model->insert($data1,"ticket_sell");

	 
	 if($this->session->userdata('gpi_id') != "")
	 {
		        $classes=$this->gpi_model->getrecordbyidrow("classes",'class_id',$classid); 
				$tickets=$this->gpi_model->getrecordbyidrow("tickets",'ticket_id',$t_id); 
				$users=$this->gpi_model->getrecordbyidrow("users",'user_id',$this->session->userdata('gpi_id'));
				
				$slect_qty=$this->input->post("quantity1");
				
				$ticket_level=$this->gpi_model->getrecordbyidrow("levels","level_id",$tickets->ticket_type);
				$class_date_id=$this->gpi_model->getrecordbyidrow("class_date","class_date_id",$tickets->date_id);
				
		        $config['mailtype'] = 'html';
				$this->load->library('email',$config);
				$this->load->library('table');
				
				$this->email->from('webmaster@gpiwin.com', 'GPI');
			    $this->email->to($users->email); 
			   // $this->email->to("aliakbar1to5@gmail.com"); 
			    $this->email->subject('GPI Registration');
			    $message='<html>
								<head>
									<title>GPI Registration</title>
								</head>
								<body>
								<p align="center"><h1><b>GPI Registration</b></h1></p><br/>
								<table align="center"><br/>
								</table><br/>
								<table>
								<tr>
										<td><b>Class Name : </b></td>
									    <td>'.$classes->class_name.'</td>
									</tr>
									<tr>
										<td><b>Ticket Name : </b></td>
										<td>'.$tickets->ticket_name.'</td>
									</tr>
									<tr>
										<td><b>Level : </b></td>
										<td>'.$ticket_level->level_name.'</td>
									</tr>
									<tr>
										<td><b>Date : </b></td>
										<td>'.$class_date_id->class_date.'</td>
									</tr>
									<tr>
										<td><b>Price : </b></td>
										<td>'.$tickets->price.'</td>
									</tr>
									<tr>
										<td><b>Quantity : </b></td>
										<td>'.$slect_qty.'</td>
									</tr>
									<br /></table></body></html>';
				/*					
				//print_r($message);
				//exit;
			   // $this->email->message($message);
				//$this->email->send();
				{$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
				$message=  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
                                            <td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Class Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Ticket Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Level</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">DATE</td>
											<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td><td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Quantity</td>
                                          </tr>
                                          <tr>
                                            <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$classes->class_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->ticket_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$ticket_level->level_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$class_date_id->class_date.'</td>
											<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->price.'</td><td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$minusQty.'</td>
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
</html>'; }*/


$table = '<table width="640" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
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
                                            <td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Class Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Ticket Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Level</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">DATE</td>
											<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td><td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Quantity</td>
                                          </tr>
                                          <tr>
                                            <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$classes->class_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->ticket_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$ticket_level->level_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$class_date_id->class_date.'</td>
											<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->price.'</td><td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$minusQty.'</td>
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
				
				$this->db->query("UPDATE users set activate_membership = 1 WHERE user_id = ".$obj['user_id']." ");
				$email_content = $this->common_model->select_single_field('before_content','email_template',array('id'=>14));
				$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>14));
				$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$user_id."");
				$user = $userObj->row_array();
				$name = $user['name'];
				
				
				
				$healthy = array("{{name}}","{{order}}");
				$yummy   = array($name,$table);
				$string = str_replace($healthy,$yummy,$email_content);
				$this->load->library('email'); 
				//print_r($message);
				
				$this->email->message($string);
				$this->email->send();
				
				
				//echo $this->email->print_debugger();
				//exit; 
	 } 
	 else
	 {
			    $classes=$this->gpi_model->getrecordbyidrow("classes",'class_id',$classid); 
				$tickets=$this->gpi_model->getrecordbyidrow("tickets",'ticket_id',$t_id); 
				$users=$this->gpi_model->getrecordbyidrow("guest_user",'guest_user_id',$user_id);
				
				$slect_qty=$this->input->post("quantity1");
				
				$ticket_level=$this->gpi_model->getrecordbyidrow("levels","level_id",$tickets->ticket_type);
				$class_date_id=$this->gpi_model->getrecordbyidrow("class_date","class_date_id",$tickets->date_id);
				
		        $config['mailtype'] = 'html';
				$this->load->library('email',$config);
				$this->load->library('table');
				
				$this->email->from('webmaster@gpiwin.com', 'GPI');
			    $this->email->to($users->email);
				//$this->email->to("aliakbar1to5@gmail.com"); 
				// $this->email->to("rabbiaAnam456@gmail.com");
			    $this->email->subject('GPI Registration');
			    $message='<html>
								<head>
									<title>GPI Registration</title>
								</head>
								<body>
								<p align="center"><h1><b>GPI Registration</b></h1></p><br/>
								<table align="center"><br/>
								</table><br/>
								<table>
								<tr>
										<td><b>Class Name : </b></td>
									    <td>'.$classes->class_name.'</td>
									</tr>
									<tr>
										<td><b>Ticket Name : </b></td>
										<td>'.$tickets->ticket_name.'</td>
									</tr>
									<tr>
										<td><b>Level : </b></td>
										<td>'.$ticket_level->level_name.'</td>
									</tr>
									<tr>
										<td><b>Date : </b></td>
										<td>'.$class_date_id->class_date.'</td>
									</tr>
									<tr>
										<td><b>Price : </b></td>
										<td>'.$tickets->price.'</td>
									</tr>
									<tr>
										<td><b>Quantity : </b></td>
										<td>'.$slect_qty.'</td>
									</tr>
									<br /></table></body></html>';
									
									
									
									
				//print_r($message);
				//exit;
			//    $this->email->message($message);
			//	$this->email->send();
			
$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
$message=  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
                                            <td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Class Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Ticket Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Level</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Date</td>
											<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td><td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Quantity</td>
                                          </tr>
                                          <tr>
                                            <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$classes->class_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->ticket_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$ticket_level->level_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$class_date_id->class_date.'</td>
											<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->price.'</td><td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$minusQty.'</td>
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

$table = '<table width="640" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
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
                                            <td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Class Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Ticket Name</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Level</td>
                                            <td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Date</td>
											<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Price</td><td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Quantity</td>
                                          </tr>
                                          <tr>
                                            <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$classes->class_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->ticket_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$ticket_level->level_name.'</td>
                                            <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$class_date_id->class_date.'</td>
											<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$tickets->price.'</td><td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$minusQty.'</td>
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
				$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$user_id."");
				
				$user = $userObj->row_array();
				$name = $user['name'];
				$healthy = array("{{name}}","{{order}}");
				$yummy   = array($name,$table);
				$string = str_replace($healthy,$yummy,$email_content);
				
				
				$this->email->message($string);
				$this->email->send();
				//echo $this->email->print_debugger();
				//exit;  
	 }
	 $this->session->unset_userdata('guest_id');
	 
	   $this->session->set_flashdata('class_success_msg','You have been registered in the selected class successfully.');
	  redirect(base_url()."guest_classes/classes_view/".$classid."?tid=".$t_id."&date=".$class_date_id->class_date_id."&qty=".$this->session->userdata('tqty').""); 
	   $this->session->unset_userdata('tqty');
	 ?>    
	 <?php /*?>echo "<b>"."You have been registered in the selected class successfully."."</b>";
	 ?>
    

    <meta http-equiv="refresh" content="7;url=<?php echo base_url();?>" /><?php */?>
      

     <?php
	  
	}

	public function cancel_payment()
	{
            echo "Please Try Again";
	}
	public function returnurl()
	{
	
	}

		
}