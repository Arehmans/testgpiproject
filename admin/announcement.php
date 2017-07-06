<?php
  class announcement extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
	public  function add_announcement()
	
	  {
		  $this->load->library('form_validation');
	       $this->form_validation->set_rules('announcement_name', 'Announcement Heading', 'required');
		   $this->form_validation->set_rules('editor1', 'Announcement', 'required');
		   $this->form_validation->set_rules('announcement_date', 'announcement_date', 'required');
		   
		   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
				 $data['class'] = "content_publishers";
				 
				 $data['main_cls'] = 'content_publishers';
				 $data['class'] = "add_announcement";
				 
				 
			  	 $data['content'] = 'admin/announcement_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
			    $config['mailtype'] = 'html';
				$this->load->library('email',$config);
				$this->load->library('table');
				
				$this->email->from('webmaster@gpiwin.com', 'GPI');
				
				$announcement_name=$this->input->post('announcement_name');
				$announcement=$this->input->post('editor1');
				$announcement_date=date('Y-m-d',strtotime($this->input->post('announcement_date')));
				$announce_format= date('F d,Y',strtotime($announcement_date));
				
		  	    $data=array(
		    		'announcement_name'=>$announcement_name,
					'announcement'=>$announcement,
					'announcement_date'=>$announcement_date,
					'status'=>$this->input->post('status'),					
		  		);
				
				$data['announcement_slider'] = 0;
				if(isset($_POST['chkSlider'])){
					$data['announcement_slider'] = 1;
				}

				
				
				
		      $this->gpi_model->insert($data,'announcement');
			 // $this->email->to($this->input->post('email').", michaelafleming@hotmail.com, paulakwatts.pw@gmail.com, ceo.appexos@gmail.com");
			 $qry= $this->gpi_model->getrecordbyid('users','subscribe',1);
			  foreach($qry as $res)
                    {
						 $email_subscribe=$res->email;
					
					
			  $this->email->to($this->input->post('email').", ".$email_subscribe.""); 
			  $this->email->subject('Announcement');
			
				$message='<html>
								<head>
									<title>GPI</title>
								</head>
								<body>
								<p align="center"><h1><b>GPI Announcements News</b></h1></p><br/>
								<table align="center"><h1><b>'.$announcement_name.'</b></h1><br/>
								</table><br/>
								<table>
								<p><a href="#">'.$announce_format.'</a></p>			
								<p>'.$announcement.'
										</p></table></body></html>';
				//print_r($message);
				//exit;
			   // $this->email->message($message);
			
				
				
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
																		GPI Announcements News
																	</h1>                                        
																</td>
																
																 <tr>
																	<td valign="top" height="80" align="center" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#333;">
																		'.$announce_format.'
																	</td>
																</tr>
																
															</tr>
															<tr>
																<td valign="top" height="80" align="center" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#333;">
																	'.$announcement.'
																</td>
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
							
								
						$this->email->message($msgHtml);
						$this->email->send();
						
				//echo $this->email->print_debugger();
				//exit;
			}
			$this->session->set_flashdata('insert_msg','Announcement Inserted Successfully.....');
		    header("Location: ".base_url()."admin/announcement/announcement_view");
		 }
	  }
	  
	  
	  function announcement_view()
	  {
		      $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',19);
                 $per_page =$pages->pages;
				$qry = "select * from `announcement` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/announcement/announcement_view'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				
				
				 $data['main_cls'] = 'content_publishers';
				 $data['class'] = "announcement_view";
				 
				
		    $data['content'] = 'admin/announcement_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  
	  function delete_announcement($id)
	  {
		  $table="announcement";
		  $primary="announcement_id";
	      $this->db->delete($table, array($primary => $id));
		   $this->session->set_flashdata('delete_msg','Announcement Successfully Deleted...'); 
	      header("Location: ".base_url()."admin/announcement/announcement_view");
	  }
	  
	  function update_announcement($id)
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('announcement_name', 'Announcement Heading', 'required');
		   $this->form_validation->set_rules('editor1', 'Announcement', 'required');
		    $this->form_validation->set_rules('announcement_date', 'announcement_date', 'required');
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				 $data['main_cls'] = 'content_publishers';
				 $data['class'] = "update_announcement";
				
				$data['content'] = 'admin/announcement_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		           $data=array(
		    		'announcement_name'=>$this->input->post('announcement_name'),
					'announcement'=>$this->input->post('editor1'),
					'announcement_date'=>date('Y-m-d',strtotime($this->input->post('announcement_date'))),
					'status'=>$this->input->post('status'),
					
		  		);
				
				$data['announcement_slider'] = 0;
				if(isset($_POST['chkSlider'])){
					$data['announcement_slider'] = 1;
				}
				
				  $this->gpi_model->update($data,"announcement",$this->input->post('vid'),"announcement_id");  
			     $this->session->set_flashdata('update_msg','Announcement Successfully Updated...'); 
		      header("Location: ".base_url()."admin/announcement/announcement_view");
			}
	  	}
	 }
	  
  
  
  
