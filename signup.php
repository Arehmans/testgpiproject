<?php
 class signup extends CI_Controller
  {
	  
		function __construct() {
		
		parent::__construct();
		
	  $config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'email-smtp.us-east-1.amazonaws.com',
			'smtp_port' => 465,
			'smtp_user' => 'AKIAIOTTT4Q6QYPJBJAA',
			'smtp_pass' => 'AlAqsyuNaljTAJ4FZ5l69V2IiT35H2BfvNbAoCrKbtoP', 
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE,
			'crlf' => '\r\n',
			'smtp_crypto'  => 'ssl',
			'newline' => '\r\n'
		);
		
		$this->load->library('email', $config);
		}
	public  function add_signup()
	
	  {
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('email', 'Email', 'required');
		   $this->form_validation->set_rules('password', 'Password', 'required');
		 
		  	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  //	 $data['content'] = 'admin/tickets_insert';
		   		 $this->load->view('signup_insert');
			}
	 
			else
			{  
		  	        $data=array(
		    		'email'=>$this->input->post('email'),
					'password'=>$this->input->post('password'),
					
					
		  		    );
		      $this->gpi_model->insert($data,'login');
			  $this->load->library('email');

               $this->email->from('tp.amit123@gmail.com', 'amit');
               $this->email->to($this->input->post('email')); 
               //$this->email->cc('another@another-example.com'); 
               //$this->email->bcc('them@their-example.com'); 
               $this->email->subject('Account Activation');
               $this->email->message('Please Activate Your Account By Clicking On This Link. ');	
               $this->email->send();
		     // header("Location: ".base_url()."admin/tickets/tickets_view");
		    } 
	  }
	  
	  function signup_view()
	  {
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('first_name', 'First Name', 'required');
		   $this->form_validation->set_rules('last_name', 'Last Name', 'required');
		   $this->form_validation->set_rules('username', 'User Name', 'required');
		   $this->form_validation->set_rules('email', 'Email', 'required');
		   $this->form_validation->set_rules('phone_no', 'Contact No', 'required');
		   $this->form_validation->set_rules('password', 'Password', 'required|matches[confpassword]|alpha_numeric|callback_passwordstrength');
		   $this->form_validation->set_rules('confpassword', 'Confirm Password', 'required');
		 
		  	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
			    $data['content'] = 'signup';
		        $this->load->view('layout/layout', $data);
			}
	 
			else
			{  
			   $email=$this->gpi_model->getemail($this->input->post("email"));
			   foreach($email as $email)
			   {
				   
			    
			   }
			
			   if($email) 
			 {
				$this->session->set_userdata("gpi_id", $email->user_id);
				//header("Location: ".base_url()."login/dashbord");
				
			
			        $verify_reg_code = uniqid();
		  	        $data=array(
					'first_name'=>$this->input->post('first_name'),
					'last_name'=>$this->input->post('last_name'),
					'email'=>$this->input->post('email'),
					'username'=>$this->input->post('username'),
					'phone_no'=>$this->input->post('phone_no'),
		    		'zip_code'=>$this->input->post('zip_code'),
					'organization'=>$this->input->post('organization'),
				    'password'=>$this->input->post('password'),
					'verify_reg_id'=>$verify_reg_code,
					
		  		    );
			
		        //$this->gpi_model->insert($data,'users');
				 $this->gpi_model->update($data,"users",$email,"user_id");
				echo "demo..... ";
			  }
		     // header("Location: ".base_url()."admin/tickets/tickets_view");
		    } 
		   
	  }
	  
	 
	  
	  
	  	
	}
	  
  
  
  
