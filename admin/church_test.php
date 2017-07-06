<?php

 class church_test extends CI_Controller

  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }

	  function church_test_view()

	  {

		    $data['content'] = 'admin/church_test_view';

		    $this->load->view('admin/layout/layout',$data);

	  }
      function delete_readiness_test($id)

	  {

		  $table="users";

		  $primary="user_id";

	      $this->db->delete($table, array($primary => $id));

	      header("Location: ".base_url()."admin/church_test/church_test_view");

	  }
	  
	  function view_more($id)

      { 
	            $data['id'] = $id;

				$data['content'] = 'admin/church_test_more.php';

				$this->load->view('admin/layout/layout',$data);

	  }
	  
	  function update_users_test($id)

      {  

	       $this->load->library('form_validation');

	       $this->form_validation->set_rules('first_name', 'First Name', 'required');

		   $this->form_validation->set_rules('email', 'Email', 'required');

		   $this->form_validation->set_rules('score', 'Score', 'required');
		   
		   $this->form_validation->set_rules('level_id', 'Select Level', 'required');

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{

			  	$data['id'] = $id;

				$data['content'] = 'admin/church_test_update.php';

				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

		            $data=array(

		    		'first_name'=>$this->input->post('first_name'),

					'email'=>$this->input->post('email'),

					'score'=>$this->input->post('score'),

			//		'username'=>$this->input->post('username'),

					'level_id'=>$this->input->post('level_id'),

		  		    );

		       	$this->gpi_model->update($data,"users",$this->input->post('vid'),"user_id");

		      	header("Location: ".base_url()."admin/church_test/church_test_view");

		     }

	  	}
		 
		
  }

	  

  

  

  

