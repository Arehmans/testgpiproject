<?php

class Crone_job extends CI_Controller
  {
    function __construct() {
	
       parent::__construct();
	}
	
	function index()
	{
		
		
		
		$membershipObj = $this->db->query("SELECT * FROM tbl_order WHERE frequancy <> 0");
		
		if($membershipObj->num_rows() >0){
			$membershipArray = $membershipObj->result_array();
			
			foreach($membershipArray as $obj){
				
				$frequancy = $obj['frequancy'];
				$db_time   = $obj['dateadded'];
				
				$expDate = date('Y-m-d', strtotime("+".$frequancy." months", $db_time));
				$todayDate = date('Y-m-d');
				
				$datediff = strtotime($expDate) - strtotime($todayDate);
				$daysDiff =  floor($datediff/(60*60*24));
				//echo "days".$daysDiff."<br />";
				if($daysDiff <= 7 && $daysDiff >=0){
					
					$this->db->query("UPDATE users set activate_membership = 1 WHERE user_id = ".$obj['user_id']." ");
					$email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>12));
					$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>12));
					$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$obj['user_id']."");
					$user = $userObj->row_array();
					$name = $user['name'];
					
					
					$healthy = array("{{name}}","{{days}}");
					$yummy   = array($name,$daysDiff);
					
					$string = str_replace($healthy,$yummy,$email_content);
					$this->load->library('email'); 
					
					
					/*$msgHtml =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
										<td valign="middle" bgcolor="#fdd00f" height="150" align="center" style="text-align:center; background-color:#fdd00f;"><a href="#"><img src="img/logo.png" border="0" width="321" /></a></td>
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
																		'.$email_subject.'
																	</h1>                                        
																</td>
															</tr>
															'.$string.'															
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
														<a href="http://gpiwin.com" style="color:#FFF;">Government Procurement Innovators, LLC</a>
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
							</html>
							';*/
					
					
					
					
					
					
					
					
					
					$useremail  = $this->common_model->select_single_field('email','users',array('user_id'=>$user_id));
					$useremail .= ', aliakbar1to5@gmail.com';
					//$useremail .= ", rabbiaanam456@gmail.com";
					 $useremail .=", ceo.appexos@gmail.com";
					 $this->email->from('asadk9630@gmail.com', $name); 
					 $this->email->to($useremail);
					 $this->email->subject($email_subject); 
					// $this->email->message($string)l
					$this->email->message($string); 
					 $this->email->set_mailtype("html");
					 @$this->email->send();
				}else if($daysDiff < 0){
				
					$user_id = $obj['user_id'];
					$mem_id = $this->common_model->select_single_field('mem_id','tbl_membership',array('mem_default'=>1));
					$this->common_model->update_array(array("user_id"=>$user_id),"users",array('mem_id'=>$mem_id,'activate_membership'=>0));
					$this->common_model->update_array(array("user_id"=>$user_id),"tbl_order",array('frequancy'=>0));
					
					$email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>13));
					$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>13));
					$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$user_id."");
					$user = $userObj->row_array();
					$name = $user['name'];
					$healthy = array("{{name}}","{{days}}");
					$yummy   = array($name,$daysDiff);
					
					$string = str_replace($healthy,$yummy,$email_content);
					
					$this->load->library('email');
					 
					/*$imgUrl = "";
					$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
					$msgHtml =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
																		'.$email_subject.'
																	</h1>                                        
																</td>
															</tr>
															'.$string.'															
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
														<a href="http://gpiwin.com" style="color:#FFF;">Government Procurement Innovators, LLC</a>
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
							</html>
							';*/
					
					
					
					
					$useremail = 'asadk9630@gmail.com';
					$useremail .= ", rabbiaanam456@gmail.com";
					$useremail .=", ceo.appexos@gmail.com";
					$this->email->from('owner@gmail.com', $name); 
					$this->email->to($useremail);
					$this->email->subject($email_subject); 
					$this->email->message($string); 
					//$this->email->message($msgHtml); 
					$this->email->set_mailtype("html");
					@$this->email->send();
		
					
					
				}
			}
		}
	
	} 
	

}    

  

?>