<?php

 class users extends CI_Controller

  {
	   function __construct() {
     
     parent::__construct();
	 
	// ini_set('error_reporting', E_ALL);
	 // ini_set('display_errors', 'On');  //On or Off
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }

	public  function add_users()

	

	  {

		   $this->load->library('form_validation');

	       $this->form_validation->set_rules('first_name', 'First Name', 'required');

		   $this->form_validation->set_rules('last_name', 'Last Name', 'required');

		   $this->form_validation->set_rules('phone_no', 'Phone No', 'required');

		   $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_checkifemailexist');

		   $this->form_validation->set_rules('level_id', 'Select Level', 'required');

		   $this->form_validation->set_rules('password', 'Password', 'required');
		   
			$this->form_validation->set_rules('membership_name', 'Membership', 'required');
		  	   

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{

				$membershipObj = $this->common_model->select_all('*','tbl_membership');
				
				$data['membershipObj'] = $membershipObj;
				$data['class'] = 'users';
			
			  	 $data['content'] = 'admin/users_insert';

		   		 $this->load->view('admin/layout/layout',$data);

			}

	         

			else
            {
				
			    $config['upload_path'] = './assets/uploads/';
		        $config['allowed_types'] = 'gif|jpg|png';
			    $config['max_size']	= '10000';
	
			    $this->load->library('upload', $config);
			    $this->upload->do_upload('profile');
			   /*if ( ! $this->upload->do_upload('profile'))
				{
				//$this->session->set_flashdata('error', $this->upload->display_errors());
				header("Location: ".base_url()."admin/users/add_users/");
				
				} else {*/
				$profile = $this->upload->data();
				//}
                   $email=$this->input->post('email');
				   $password=$this->input->post('password');
		  	        $data=array(

		    		'first_name'=>$this->input->post('first_name'),

					'last_name'=>$this->input->post('last_name'),

					'phone_no'=>$this->input->post('phone_no'),

					'mem_id'=>$this->input->post('membership_name'),
					
					'email'=>$email,

					'password'=>$password,
					
					'zip_code'=>$this->input->post('zip_code'),
					
					'organization'=>$this->input->post('organization'),
					
					'level_id'=>$this->input->post('level_id'),
					
					'score'=>$this->input->post('score'),
					
					'address1'=>$this->input->post('address1'),
					
					'address2'=>$this->input->post('address2'),
					
					'expert'=>$this->input->post('expert'),
					
					'profile' =>$profile['file_name'],
					
					'user_status'=>$this->input->post('user_status'),

		  		    );

				$this->gpi_model->insert($data,'users');
			 
			 
				$config['mailtype'] = 'html';
				$this->load->library('email',$config);
				$this->load->library('table');
				
				$this->email->from('webmaster@gpiwin.com', 'GPI');
			   $this->email->to($email); 
			  $this->email->subject('GPI User');
			  $message='<html>
				<head>
					<title>Wel Come to GPI</title>
				</head>
				<body>
				<p align="center"><h1><b>Wel Come to GPI</b></h1></p><br/>
				<table align="center"><br/>
				</table><br/>
				<table>
				<tr>
						<td><b>Email ID : </b></td>
						<td>'.$email.'</td>
					</tr>
					<tr>
						<td><b>Password : </b></td>
						<td>'.$password.'</td>
					</tr>
					<br /></table></body></html>';
					
					
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
																		Wel Come to GPI
																	</h1>                                        
																</td>
															</tr>
															
															<tr>
																<td valign="top">
																	<table width="100%" border="0" cellspacing="2" cellpadding="5">
																	  <tr>
																		<td colspan="2" align="center" height="50" bgcolor="#666666" style="font-size:24px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:#FFF; background-color:#666; text-align:center;">ACCOUNT DETAILS</td>
																	  </tr>
																	  <tr>
																		<td width="25%" bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Email</td>
																		<td bgcolor="#f5f5f5" height="36" bordercolor="#DDD" align="center" style="background-color:#f5f5f5; border:#DDD 1px solid; text-align:center; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000; height:36px;">Password</td>
																		
																	  </tr>
																	  <tr>
																		<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">'.$email.'</td>
																		<td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#555; text-align:center;">'.$password.'</td>
																		
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
							</html>';
					
					
				//print_r($message);
				//exit;
				
			
			    $this->email->message($msgHtml);
				$this->email->send();
				//echo $this->email->print_debugger();
				//exit;
			
              $this->session->set_flashdata('insert_msg','User Created Successfully.....');
		      header("Location: ".base_url()."admin/users/users_view");

		    } 

	  }

	  function checkifemailexist($str) {
		
		$checkemailexist=$this->gpi_model->emailexist("users", "email", $str);
		if($checkemailexist) {
			$this->form_validation->set_message('checkifemailexist', 'Email already exists Please try another one.');
			return false;
		} else {
			return true;
		}
	}

	  function users_view()
	  {
		 	     $this->load->library('pagination');
				 $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',17);
                 $per_page =$pages->pages;
				 $qry = "select * from `users`";
				$data['class'] = 'users';
				
				if($this->input->get('ustatus') != "" &&  $this->input->get('ustatus') != "undefined") {
				$ustatus = $this->input->get('ustatus');
					if($ustatus==1)
					{
					  $qry.="order by `user_status` DESC ";
					}
					else
					{
						  $qry.="order by `user_status` ASC";
					}
				} 
				if($this->input->get('utype') != "" &&  $this->input->get('utype') != "undefined") {
				$utype = $this->input->get('utype');
					if($utype==1)
					{
					  $qry.="order by `expert` DESC";
					}
					else
					{
						  $qry.="order by `expert` ASC";
					}
				} 
				if($this->input->get('ufname') != "" &&  $this->input->get('ufname') != "undefined") {
				$ufname = $this->input->get('ufname');
					if($ufname==1)
					{
					  $qry.="order by `first_name` ASC";
					}
					else
					{
						 $qry.="order by `first_name` DESC";
					}
				} 
				if($this->input->get('ucontact') != "" &&  $this->input->get('ucontact') != "undefined") {
				$ucontact = $this->input->get('ucontact');
					if($ucontact==1)
					{
					  $qry.="order by `phone_no` ASC ";
					}
					else
					{
						  $qry.="order by `phone_no` DESC";
					}
				} 
				if($this->input->get('uemail') != "" &&  $this->input->get('uemail') != "undefined") {
				$uemail = $this->input->get('uemail');
					if($uemail==1)
					{
					  $qry.="order by `email` ASC";
					}
					else
					{
						 $qry.="order by `email` DESC";
					}
				} 
				$offset = $this->input->get('per_page');
		
				if(!$offset)
					$offset = 0;
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['page_query_string'] = TRUE;
				$config['base_url']= base_url().'admin/users/users_view/?result=true&ufname='.$ufname.'&uemail='.$uemail.'&ustatus='.$ustatus.'&utype='.$utype.'&ucontact='.$ucontact.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				//$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result();
				
		    $data['content'] = 'admin/users_view';

		    $this->load->view('admin/layout/layout',$data); 

	  }
	  
	  function user_ajax()

	  {
		         $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',17);
                 $per_page =$pages->pages;
				$qry = "select * from `users`";
				
				
				if($this->input->get('ustatus') != "" &&  $this->input->get('ustatus') != "undefined") {
				$ustatus = $this->input->get('ustatus');
					if($ustatus==1)
					{
					  $qry.="order by `user_status` DESC ";
					}
					else
					{
						  $qry.="order by `user_status` ASC";
					}
				} 
				if($this->input->get('utype') != "" &&  $this->input->get('utype') != "undefined") {
				$utype = $this->input->get('utype');
					if($utype==1)
					{
					  $qry.="order by `expert` DESC";
					}
					else
					{
						  $qry.="order by `expert` ASC";
					}
				}
				if($this->input->get('ucontact') != "" &&  $this->input->get('ucontact') != "undefined") {
				$ucontact = $this->input->get('ucontact');
					if($ucontact==1)
					{
					  $qry.="order by `phone_no`ASC ";
					}
					else
					{
						  $qry.="order by `phone_no` DESC";
					}
				} 
				if($this->input->get('ufname') != "" &&  $this->input->get('ufname') != "undefined") {
				$ufname = $this->input->get('ufname');
					if($ufname==1)
					{
					  $qry.="order by `first_name` ASC";
					}
					else
					{
						 $qry.="order by `first_name` DESC";
					}
				} 
				
				if($this->input->get('uemail') != "" &&  $this->input->get('uemail') != "undefined") {
				$uemail = $this->input->get('uemail');
					if($uemail==1)
					{
					  $qry.="order by `email` ASC";
					}
					else
					{
						 $qry.="order by `email` DESC";
					}
				} 
				
				$offset = $this->input->get('per_page');
		
				if(!$offset)
					$offset = 0;
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['page_query_string'] = TRUE;
				$config['base_url']= base_url().'admin/users/users_view/?result=true&ufname='.$ufname.'&uemail='.$uemail.'&ustatus='.$ustatus.'&utype='.$utype.'&ucontact='.$ucontact.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result();
		   // $data['content'] = 'admin/organization_ajax_view';

		    $this->load->view('admin/user_ajax',$data);
	  }
	  function delete_users($id)

	  {

		  $table="users";

		  $primary="user_id";

	      $this->db->delete($table, array($primary => $id));
        $this->session->set_flashdata('delete_msg','User Successfully Deleted...');
	      header("Location: ".base_url()."admin/users/users_view");

	  }

	  

	  function update_users($id)

      {  

	       $this->load->library('form_validation');

	       $this->form_validation->set_rules('first_name', 'First Name', 'required');

		   $this->form_validation->set_rules('last_name', 'Last Name', 'required');

		   $this->form_validation->set_rules('phone_no', 'Phone No', 'required');

		   $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		   
		   
			$this->form_validation->set_rules('membership_name', 'Membership', 'required');

	       $this->form_validation->set_rules('level_id', 'Select Level', 'required');

		   $this->form_validation->set_rules('password', 'Password', 'required');

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{
				$data['class'] = 'users';

			  	$data['id'] = $id;
				
				$membershipObj = $this->common_model->select_all('*','tbl_membership');
				
				$data['membershipObj'] = $membershipObj;
			
				

				$data['content'] = 'admin/users_update';

				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 
			$memberId =  $this->input->post('membership_name');
			$memberId = implode(", " , $memberId);
			
			  $config['upload_path'] = './assets/uploads/';
		       $config['allowed_types'] = 'gif|jpg|png';
			   $config['max_size']	= '10000';
	
			   $this->load->library('upload', $config);
			   if ( ! $this->upload->do_upload('profile'))
				{
				//$this->session->set_flashdata('error', $this->upload->display_errors());
				header("Location: ".base_url()."admin/users/add_users/");
				
				} else {
				$profile = $this->upload->data();
				}
                if($_FILES["profile"]["name"]!="" and (!empty($_FILES["profile"]["name"]))) {
					
		            $data=array(

		    		'first_name'=>$this->input->post('first_name'),

					'last_name'=>$this->input->post('last_name'),

					'phone_no'=>$this->input->post('phone_no'),

					'email'=>$this->input->post('email'),
					
					'mem_id'=>$memberId,

					'password'=>$this->input->post('password'),
					
					'zip_code'=>$this->input->post('zip_code'),
					
					'organization'=>$this->input->post('organization'),
					
					'level_id'=>$this->input->post('level_id'),
					
					'score'=>$this->input->post('score'),
					
					'address1'=>$this->input->post('address1'),
					
					'address2'=>$this->input->post('address2'),
					
					'expert'=>$this->input->post('expert'),
					
					'profile' =>$profile['file_name'],
					'user_status'=>$this->input->post('user_status'),

		  		    );
					

		       	$this->gpi_model->update($data,"users",$this->input->post('vid'),"user_id");
                $this->session->set_flashdata('update_msg','User Successfully Updated...');
		      	header("Location: ".base_url()."admin/users/users_view");
                }
		    else
		    {
				 $data=array(

		    		'first_name'=>$this->input->post('first_name'),

					'last_name'=>$this->input->post('last_name'),

					'phone_no'=>$this->input->post('phone_no'),

					'email'=>$this->input->post('email'),
					
					'mem_id'=>$memberId,

					'password'=>$this->input->post('password'),
					
					'zip_code'=>$this->input->post('zip_code'),
					
					'organization'=>$this->input->post('organization'),
					
					'level_id'=>$this->input->post('level_id'),
					
					'score'=>$this->input->post('score'),
					
					'address1'=>$this->input->post('address1'),
					
					'address2'=>$this->input->post('address2'),
					
					'expert'=>$this->input->post('expert'),
					
				//	'profile' =>$profile['file_name'],
				'user_status'=>$this->input->post('user_status'),

		  		    );

		       	$this->gpi_model->update($data,"users",$this->input->post('vid'),"user_id");
                $this->session->set_flashdata('update_msg','User Successfully Updated...');
		      	header("Location: ".base_url()."admin/users/users_view");
			  }
		     }

	  	}
       
	}

	  

  

  

  

