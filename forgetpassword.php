<?php 
class forgetpassword extends CI_Controller {
  
    public  function forgetpassword_view()
	{
		
		$this->load->library('form_validation');
				
				$this->form_validation->set_rules('email', 'email', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
				
				if($this->form_validation->run() == FALSE)
				{
					
					$data['content'] = 'forgetpassword_view';
		            $this->load->view('layout/layout',$data);
				}
			else
			
			{	
			
		
			
			$email= $this->input->post('email');
			$login1=$this->gpi_model->getuserforget($email);
			if($login1){
				$user = $login1;
				$query=$this->gpi_model->get_useruniqid_by_email("users",$email);
				$verify_reg_code=$query->verify_reg_code;
		
			    $config['mailtype'] = 'html';
				$this->load->library('email',$config);
				$this->load->library('table');
				$this->email->from('webmaster@gpiwin.com', 'GPI');
				/*$this->email->to($this->input->post('email'));
				$this->email->subject('GPI Reset Password Verification');
				$this->email->message('Hi.  Please click on the provided link to reset the password of your account  '.base_url()."Resetpassword/index/".$verify_reg_code);
				$this->email->send();*/
				$this->email->to($this->input->post('email'));
				$where = array('id'=>4); // Event 1 = Readiness Assessment Submitted 
				$email_template = $this->gpi_model->get_by_where('email_template',$where);
				$link= base_url()."Resetpassword/index/".$verify_reg_code;
				$content = 'Hi.  Please click on the provided link to reset the password of your account  '.$link; 
				if($email_template){
					$recipients = $email_template->admin_recipients !='' ? unserialize($email_template->admin_recipients) : array();
					if($recipients){
						$admin_recipients = $this->gpi_model->get_by_whereIn('users',array('user_id'=>$recipients));
						$cc = array();
						foreach ($admin_recipients as $key => $u) {
							$cc[] = $u->email;
						}
						//$cc[] = "aliakbar1to5@gmail.com";
						$cc[] = "paulakwatts.pw@gmail.com";
						if($cc)
							$this->email->cc($cc);
					}
					/* Replacement Pattern */
					$make_patterns = array(
						'name' => full_name($user) ,
						'email' => $user->email,
						'link' => $link
						);
					
					$content = $email_template->email_content;
					foreach ($make_patterns as $key => $value) {
						
			            $content = str_replace('{{'.$key.'}}', $value, $content);
						
			        }
						
					$this->email->subject($email_template->email_subject);
					

					/* Replacement Pattern */
				}else{
					$this->email->subject('GPI Reset Password Verification');
					$this->email->cc("paulakwatts.pw@gmail.com"); 
					//$this->email->cc("aliakbar1to5@gmail.com, ceo.appexos@gmail.com"); 
				}
				
				
				/* set CC to Admins */
			    $this->email->message($content);
				if($_SERVER['HTTP_HOST'] == 'localhost'){
					echo $content;
					exit;
				}
				
				$this->email->send();
					
				
				
				/*$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
				
					
					$msgHtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
																		Forgot Password
																	</h1>                                        
																</td>
															</tr>
															<tr>
																<td valign="top" height="80" align="center" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#333;">
																	'.$content.'
																</td>
															</tr>
															<tr>
																<td valign="top">
																	
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
														<a href="http://gpiwin.com" style="color:#FFF;">Government Procurement Innovators, LLC</a><br />
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
					 $this->email->message($msgHtml);

					if($_SERVER['HTTP_HOST'] == 'localhost'){
						echo $msgHtml;
						exit;
					}
					$this->email->send();*/
			//	echo $verify_reg_code;
		//		echo $email;
			  //  echo $this->email->print_debugger();
			
				$this->session->set_flashdata("msg1","Check your email to Reset your Password");
				redirect(base_url()."login");
			}else{
				$this->session->set_flashdata("invalid_user","Email ID is Not Authorised..");
			    redirect(base_url()."forgetpassword/forgetpassword_view");
			}
		
	   }
	}
		//$this->load->view('welcome_messege');
	
}