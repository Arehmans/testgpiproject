<?php	
  error_reporting(0);
	class login extends CI_Controller
	{
		function __construct() {
		
		parent::__construct();
		
		  
		}
		public function index()
		{
	   	 if($this->session->userdata('gpi_id') != "") {
			// redirect(base_url()."login/index/dashboard.html");
		   }
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('username', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		     
			if($this->form_validation->run() == FALSE)
			{
				$data['content'] = 'login';
				$this->load->view('layout/layout',$data);
			}
			else
			{
				$login=$this->gpi_model->get_client_login($this->input->post("email"), $this->input->post('password'));
           // $id=$login->user_id;
			if($login) 
			{
				$this->session->set_userdata("gpi_id", $login->user_id);
				
			        $data=array(
		    		'online'=>1,
		  		);
			 $this->gpi_model->update($data,"users",$login->user_id,"user_id");  
				if($this->input->post("payment")==1)
				{
					$this->session->set_flashdata('login_msg','User Login Successfully...');
					header("Location: ".base_url()."classes/classes_view/".$this->input->post("id"));
				  
				}
				else
				{
				  header("Location: ".base_url()."login/dashbord");
				}
			}
			else
			{	
				$this->session->set_flashdata('login_msg','Wrong Email or Password');
				redirect(base_url()."login");
			}
		  }
	   
		}
		
		public  function dashbord()
		{ 
		  if($this->session->userdata('gpi_id') == "")
		   {
			redirect(base_url().'login'); 
		   } 
		    $data['content'] = 'myprofile_view';
			$this->load->view('layout/layoutuser',$data);
		}
	
		function logout()
		{
			 $data=array(
		    		'online'=>0,
		  		);
			 $this->gpi_model->update($data,"users",$this->session->userdata('gpi_id'),"user_id"); 
			  
			$this->session->unset_userdata('gpi_id');
		    redirect(base_url().'login'); 
		}
		
		
		
		
		
		
	}

?>
