<?php	

	class login extends CI_Controller

	{

		function __construct() {

		

		parent::__construct();

		}

		public function index()

		{

			 if($this->session->userdata('admin_id') != "") {

			 // redirect(base_url()."login/index/dashboard.html");

		   }

			$this->load->library('form_validation');

			//$this->form_validation->set_rules('username', 'Name', 'required');

			$this->form_validation->set_rules('email', 'Email', 'required|xss_clean');

			$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');

			$this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		

			if($this->form_validation->run() == FALSE)

			{

				

				$this->load->view('admin/login');

			}

			else

			{

				$login=$this->gpi_model->get_admin_login($this->db->escape($this->input->post("email")), $this->db->escape($this->input->post('password')));

           // $id=$login->user_id;

			if($login) 

			{ 

				$this->session->set_userdata("admin_id", $login->user_id);

				 $data=array(

		    		'online'=>1,

		  		);

				

				$data_updatedate=array("last_login_datetime" => date('Y-m-d H:i:s'));

				

				$updatelastlogin=$this->gpi_model->update($data_updatedate, "users", $login->user_id, "user_id"); 

				

			   $this->gpi_model->update($data,"users",$login->user_id,"user_id");  

				//header("Location: ".base_url()."admin/login/dashbord");

				redirect(base_url()."admin/login/dashbord");

			}

			else

			{	

				$this->session->set_flashdata('msg','Wrong  Email Or Password');

				redirect(base_url()."admin/login");

			}

		}

			

		}

		

		public  function dashbord()

		{ 

		  if($this->session->userdata('admin_id') == "") {

			redirect(base_url().'admin/login');

			 

		  } 

		    redirect(base_url()."admin/tickets/tickets_view/");

		   

		

		}

		function logout()

		{

			 $data=array(

		    		'online'=>0,

		  		);

			 $this->gpi_model->update($data,"users",$this->session->userdata('admin_id'),"user_id"); 

			$this->session->unset_userdata('admin_id');

			

		   redirect(base_url()."admin/login");

		}

	

		

		

}



?>

