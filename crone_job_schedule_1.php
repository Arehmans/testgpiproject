<?php

class Crone_job_schedule extends CI_Controller
  {
    function __construct() {
	
       parent::__construct();
	}
	
	function index()
	{
		
		
		
		$scheduleObj = $this->db->query("SELECT * FROM tbl_reminder");
		
		
		if($scheduleObj->num_rows() >0){
			$scheduleObj = $scheduleObj->result_array();
			
			foreach($scheduleObj as $obj){
				//$obj['remember_time']
				$abc = "2day,1day,12hours,8hours,4hours,2hours,1hour,30minute,15minute";
				
				$remember_timeArray = explode(",",$abc);
				//$db_time = "27-04-2017 5:00 AM";
				$db_time   	   = $obj['sch_date'];
				//$user_emails   = $obj['user_id'];
				//print_r($user_emails);
				$user_email = "abc@gpi.com";
				//print_r($remember_timeArray);
				if(in_array("2day",$remember_timeArray)){
						$expDate =  strtotime($db_time);
						//$todayTime1 ="25-04-2017 5:00 AM";
						$todayTime1 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime("+2 day",strtotime($todayTime1));
						//echo $todayTime."<br />";
						$db_time1 = strtotime($db_time);
						
						if($todayTime1 == $db_time1){
							
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										 
										
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										// print_r($string);
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("1day",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime2 ="26-04-2017 5:00 AM";
						$todayTime2 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime2 = strtotime("+1 day",strtotime($todayTime2));
						//echo $todayTime."<br />";
						$db_time2 = strtotime($db_time);
						
						if($todayTime2 == $db_time2){
							
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("12hours",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime3 ="26-04-2017 5:00 PM";
						$todayTime3 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime3 = strtotime("+12 hours",strtotime($todayTime3));
					//	echo $todayTime."<br />";
						$db_time3 = strtotime($db_time);
					//	echo $db_time3;die;
						if($todayTime3 == $db_time3){
							
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										 
										// print_r($string);
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("8hours",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime4 ="26-04-2017 9:00 PM";
						$todayTime4 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime4 = strtotime("+8 hours",strtotime($todayTime4));
						//echo $todayTime4."<br />";
						$db_time4 = strtotime($db_time);
						//echo $db_time4;die;
						if($todayTime4 == $db_time4){
						
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
								
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										
										// print_r($string);
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("4hours",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime5 ="27-04-2017 1:00 AM";
						$todayTime5 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime5 = strtotime("+4 hours",strtotime($todayTime5));
						//echo $todayTime5."<br />";
						$db_time5 = strtotime($db_time);
						//echo $db_time5;die;
						if($todayTime5 == $db_time5){
						
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
								
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										
										// print_r($string);
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("2hours",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime6 ="27-04-2017 3:00 AM";
						$todayTime6 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime6 = strtotime("+2 hours",strtotime($todayTime6));
						//echo $todayTime5."<br />";
						$db_time6 = strtotime($db_time);
						//echo $db_time5;die;
						if($todayTime6 == $db_time6){
						
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
								
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										
										// print_r($string);
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("1hour",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime7 ="27-04-2017 4:00 AM";
						$todayTime7 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime7 = strtotime("+1 hours",strtotime($todayTime7));
						//echo $todayTime5."<br />";
						$db_time7 = strtotime($db_time);
						//echo $db_time5;die;
						if($todayTime7 == $db_time7){
						
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
								
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										
										// print_r($string);
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("30minute",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime8 ="27-04-2017 4:30 AM";
						$todayTime8 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime8 = strtotime("+30 minutes",strtotime($todayTime8));
						//echo $todayTime5."<br />";
						$db_time8 = strtotime($db_time);
						//echo $db_time5;die;
						if($todayTime8 == $db_time8){
						
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 
							 	if(!empty($user_email)){
								
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										$price = 10;
										
										 
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										
										// print_r($string);
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				if(in_array("15minute",$remember_timeArray)){
					
						$expDate =  strtotime($db_time);
						//$todayTime9 ="27-04-2017 4:45 AM";
						$todayTime9 = date("d-m-Y H:i A");
						//$todayTime = strtotime("+2 day");
						$todayTime9 = strtotime("+15 minutes",strtotime($todayTime9));
						//echo $todayTime5."<br />";
						$db_time9 = strtotime($db_time);
						//echo $db_time5;die;
						if($todayTime9 == $db_time9){
						
							
							 $email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>16));
							 $email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>16));
							 	if(!empty($user_email)){
										 $userObj = $this->db->query("SELECT CONCAT(first_name,'', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										
										$price = 10;
										 $html = "<tr style='text-align:center;'>
											<td style='text-align:left;'>".$obj['sch_name']."</td>
											<td>".$obj['start_date']."</td>
											<td>$".$price."</td>
											<td>$0.00</td>
											<td>Paid</td>
					
										  </tr>";
					  
										 
										 
										  $string =  "<table align='center' style='width:90%;border: 1px solid;' cellpadding='10'>
											  <tr style='text-align:center;'><td colspan='5' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>Schedule Reminder</td></tr>
											  <tr style='background-color:whitesmoke;'>
												<th style='width: 25%;text-align:left;' style='border: 1px solid #dddddd;'>Schedule</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Date</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Price</th>
												<th style='width: 25%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
												<th style='width: 25%;border: 1px solid #dddddd;'>Status</th>
											  </tr>
											
											  ".$html."
																	
											</table>";
										 
										
										// print_r($string);
										 
										 $healthy = array("{{name}}","{{content}}");
										 $yummy   = array($name,$string);
										
										 $string = str_replace($healthy,$yummy,$email_content);
										 $this->load->library('email'); 
										
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $this->email->from('asadk9630@gmail.com', $name); 
										 $this->email->to($useremail);
										 $this->email->subject($email_subject); 
										 $this->email->message($string); 
										 $this->email->set_mailtype("html");
										 @$this->email->send();
									
								}
							 
						}
				}
				
			die;
			}
		}
	
	} 
	

}    

  

?>