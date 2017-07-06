<?php

class Crone_job_schedule extends CI_Controller
  {
    function __construct() {
	
       parent::__construct();
	}
	
	function index()
	{		
		$scheduleObj = $this->db->query("SELECT * FROM tbl_reminder");
		//date_default_timezone_set('Asia/Karachi');
		
		
		if($scheduleObj->num_rows() >0){
			$scheduleObj = $scheduleObj->result_array();
			
			foreach($scheduleObj as $obj){
				
				//$abc = $obj['remember_time']; comment
				$abc = "2day,1day,12hours,8hours,4hours,2hours,1hour,30minute,15minute";
				$remember_timeArray = explode(",",$abc);
				//print_r($remember_timeArray);
			//	$db_time   	   = $obj['sch_date']; comment.
				$db_time = strtotime("25-05-2017 5:00 PM");
				$user_email   = $obj['email'];
				//print_r($user_emails);
				//$user_email = "abc@gpi.com";
				//print_r($remember_timeArray);
				
				
				if(in_array("2day",$remember_timeArray)){
						$expDate =  $db_time;
						
						
						$todayTime1 = date("d-m-Y H:i"); 
						
					//	$todayTime1 ="23-05-2017 5:00 PM";
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +2 day')));
						//echo date("d m Y",$todayTime1);die;
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						
						if($todayTime1 == $db_time1){
							
							
						
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
									
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp;  <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp;<span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
									
								</div>
							</div>
					</div>';
						
						
						echo $html;die;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
										// $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										 //$this->email->to($useremail);
										 //$this->email->subject($email_subject); 
										// $this->email->message($html); 
										 //$this->email->set_mailtype("html");
										 
										 $s = @mail($useremail, "Meeting Appointment Reminder", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("1day",$remember_timeArray)){
						$expDate =  $db_time;
						//$todayTime1 ="24-05-2017 5:00 AM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +1 day')));
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
					//	echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										  $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										 
										 $s = @mail($useremail, "Meeting Appointment Reminder", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("12hours",$remember_timeArray)){
						$expDate =  $db_time;
						//$todayTime1 ="24-05-2017 5:00 PM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +12 hours')));
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
						//echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										  $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										
										 $s = @mail($useremail, "Meeting Appointment Reminder", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("8hours",$remember_timeArray)){
						$expDate =  $db_time;
						//$todayTime1 ="24-05-2017 9:00 PM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +8 hours')));
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
						//echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										
										 $s = @mail($useremail, "Meeting Appointment Reminder", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("4hours",$remember_timeArray)){
						$expDate =  $db_time;
						//$todayTime1 ="25-05-2017 1:00 AM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +4 hours')));
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
						//echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										 $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										
										 $s = @mail($useremail, "Meeting Appointment Reminder", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("2hours",$remember_timeArray)){
						$expDate =  $db_time;
						//$todayTime1 ="25-05-2017 3:00 AM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +2 hours')));
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
						//echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										  $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										
										 $s = @mail($useremail, "Meeting Appointment Reminder", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("1hour",$remember_timeArray)){
						$expDate =  $db_time;
						//$todayTime1 ="25-05-2017 4:00 AM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +1 hours')));
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
						//echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										  $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										 
										 $s = @mail($useremail, "Meeting Appointment Reminder", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("30minute",$remember_timeArray)){
						$expDate =  $db_time;
						//$todayTime1 ="25-05-2017 4:30 AM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +30 minutes')));
					//	echo $stop_date;
						
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo "30_min".$todayTime1."___".$db_time;
						
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
						//echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										  $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										
										 $s = @mail($useremail, "Meeting Appointment", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
										 
										
									
								}
							 
						}
				}
				
				if(in_array("15minute",$remember_timeArray)){
						$expDate =  $db_time;
						
						//$todayTime1 ="25-05-2017 4:45 AM";
						//$todayTime1 = '2009-09-30 20:24:00';
						$todayTime1 = date("d-m-Y H:i");
						//$todayTime = strtotime("+2 day");
						$todayTime1 = strtotime(date('Y-m-d H:i', strtotime($todayTime1 . ' +15 minutes')));
					//	echo $stop_date;
					
						//$todayTime1 = strtotime($todayTime1 . ' +2 day');
						//echo $todayTime."<br />";
						$db_time1 = $db_time;
						//echo $db_time1."___".$db_time;die;
						echo $todayTime1."__".$db_time1."<br />";
						if($todayTime1 == $db_time1){
							 	if(!empty($user_email)){
									
										 $userObj = $this->db->query("SELECT CONCAT(first_name,' ', last_name) AS name FROM users WHERE email = '".$user_email."'");
										 $name = "Guest";
										 if($userObj->num_rows() >0){
										 	$user = $userObj->row_array();
										 	$name = $user['name'];
										 }
										 
										 
										$stDay = date('D',$db_time1);
										$stMon = date('M',$db_time1);
										$stYear = date('Y',$db_time1);
										$stHour= date("g:i A",$db_time1);
										$dateStartTime = date("d",$db_time1);
										$sss_dateTime = $stDay." ".$stMon.", ".$stYear;
										
										$admin_name = $name;
										
										
										$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>   &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' @'.$stHour.' EST<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$user_email.'</span><br />
								<div style="margin-top:20px;">
							   
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;">'.$obj['notes'].'</p>
								</div>
							</div>
					</div>';
					//	echo $html;
										 $headers = array("From: from@example.com",
												"Reply-To: replyto@example.com",
												"Content-type: text/html; charset=iso-8859-1",
												"X-Mailer: PHP/" . PHP_VERSION
											);
											$headers = implode("\r\n", $headers);
										
										 
										
									//	 $this->load->library('email'); 
										 $useremail  = $user_email;
										 $useremail .= ', aliakbar1to5@gmail.com';
										  $useremail .= ', ceo.appexos@gmail.com';
										 
										// $this->email->from('asadk9630@gmail.com', $name); 
										// $this->email->to($useremail);
										// $this->email->subject($email_subject); 
										// $this->email->message($html); 
										// $this->email->set_mailtype("html");
										
										 $s = @mail($useremail, "Meeting Appointment", $html, $headers);
										if($s){
											echo "Sent.".$useremail;	
										}else{
										 //	echo "Sorry Email cannot sent!.";	
										}
								}
						}
				}	
			}
		}
	
	} 
	

}    

  

?>