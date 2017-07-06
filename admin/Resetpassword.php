<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resetpassword extends CI_Controller {
		
		public function index($verify_reg_code)
		{
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('password', 'Password', 'required|matches[passconf]');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
				
				if($this->form_validation->run() == FALSE)
					{
						  
					  //$data['content'] = 'resetpassword_view';
		              $this->load->view('admin/resetpassword_view');
					
					 
					}
			else
			
					{	
				
					
					$query=$this->gpi_model->get_user_by_verify_reg_code("users",$verify_reg_code);
					$user_id=$query->user_id;
					
					$data=array(
								'password'=>$this->input->post('password'),
				               );
			     	$this->gpi_model->update($data, "users",$user_id,"user_id");
					//echo $this->db->last_query();
					$this->session->set_flashdata("msg2","Password Successfully Changed!");
					redirect(base_url()."admin/login");
					
					
					}
	
	     }
		 
		 

}