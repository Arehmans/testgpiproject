<?php 
class forgetpassword extends CI_Controller {
  
    public  function forgetpassword_view()
	{
		
		$this->load->library('form_validation');
				
				$this->form_validation->set_rules('email', 'email', 'required');
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
				
				if($this->form_validation->run() == FALSE)
				{
					
					//$data['content'] = 'forgetpassword_view';
		            $this->load->view('admin/forgetpassword_view');
				}
			else
			
			{	
			
			$email= $this->input->post('email');
			$login1=$this->gpi_model->getadminforget($email);
			if($login1){
				
			$query=$this->gpi_model->get_useruniqid_by_email("users",$email);
			$verify_reg_code=$query->verify_reg_code;
	
		    $this->load->library('email');
			$this->email->from('webmaster@gpiwin.com', 'GPI');
			$this->email->to($this->input->post('email'));
			$this->email->subject('GPI Reset Password Verification');
			//$this->email->message('Hi.  Please click on the provided link to reset the password of your account  '.base_url()."admin/Resetpassword/index/".$verify_reg_code);
			$message = 'Hi.  Please click on the provided link to reset the password of your account  '.base_url()."admin/Resetpassword/index/".$verify_reg_code;
			
			$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
				
					
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
																		GPI Reset Password Verification
																	</h1>                                        
																</td>
															</tr>
															<tr>
																<td valign="top" height="80" align="center" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#333;">
																	'.$message.'
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
							$this->email->send();
			
			
			
			
			//$this->email->send(); 
		//	echo $verify_reg_code;
	//		echo $email;
		  //  echo $this->email->print_debugger();
		
			$this->session->set_flashdata("msg1","Check your email to Reset your Password");
			redirect(base_url()."admin/login");
			}
			else
			{
				$this->session->set_flashdata("invalid_user","Email ID is Not Authorised..");
			    redirect(base_url()."admin/forgetpassword/forgetpassword_view");
			}
		   
	   }
	}
		//$this->load->view('welcome_messege');
	
}