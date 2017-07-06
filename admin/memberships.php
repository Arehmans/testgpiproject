<?php

 class memberships extends CI_Controller

  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
       $this->table  = 'memberships'; 
       $this->primary_key = 'membership_id';
   }

	public  function add_membership(){

		   

	}

	function index(){
		$this->membership_view();
	}
	function membership_view(){
		 	$data['qry'] = $this->gpi_model->get_by_where($this->table);
		 	
		 	if(!is_array($data['qry']))
		 		$data['qry'] = array($data['qry']);
			$data['content'] = 'admin/memberships/index';
			$this->load->view('admin/layout/layout',$data); 

	}
	  
	  
	  
	  

	  function update_membership($id){  


	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('name', 'Membership Name', 'required');

		   $this->form_validation->set_rules('body', 'Features', 'required');
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE){

			  	$data['id'] = $id;
			  	$data['q'] = $this->gpi_model->get_by_where('memberships',array('membership_id'=>$id));
			  	$data['levels'] = $this->gpi_model->get_by_where('levels');

				$data['content'] = 'admin/memberships/edit';

				$this->load->view('admin/layout/layout',$data);

			}else{ 
				$name = $this->input->post('name');
				$body = $this->input->post('body');
				$price = $this->input->post('price');
				$started_at = $this->input->post('started_at');
				$end_at = $this->input->post('end_at');
				$data = array(
					'name' => $name,
					'body' => $body,
					'price' => $price,
					'started_at' => $started_at,
					'end_at' => $end_at,
				);
				$resp = $this->gpi_model->edit_record( $this->table,$data, $this->primary_key,$id );
				if($resp == true){
					$this->session->set_flashdata('update_msg','Ticket Successfully Updated...');
	      			redirect(site_url('admin/memberships/'));
		    	}
			}

	  	}
       
	}

	  

  

  

  

