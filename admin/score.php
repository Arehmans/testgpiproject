<?php
 class score extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
	public  function add_readiness()
	
	  {
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('from_score', 'From Score', 'required');
		   $this->form_validation->set_rules('to_score', 'To Score', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
				
				$data['main_cls'] = 'settings';
		 		 $data['class'] = "add_readiness";
				
			  	 $data['content'] = 'admin/readiness_score_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{  
		  	        $data=array(
		    		'from_score'=>$this->input->post('from_score'),
					'to_score'=>$this->input->post('to_score'),
					'level_id'=>$this->input->post('level_id'),
		  		    );
		      $this->gpi_model->insert($data,'readiness_score');
			  $this->session->set_flashdata('add','Score Successfully Inserted...'); 
		      header("Location: ".base_url()."admin/score/readiness_score");
		    } 
	  }
	  public  function add_organization()
	
	  {
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('from_score', 'From Score', 'required');
		   $this->form_validation->set_rules('to_score', 'To Score', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
				$data['class'] = "settings";
			  	 $data['content'] = 'admin/organization_score_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{  
		  	        $data=array(
		    		'from_score'=>$this->input->post('from_score'),
					'to_score'=>$this->input->post('to_score'),
					'level_id'=>$this->input->post('level_id'),
		  		    );
		      $this->gpi_model->insert($data,'organization_score');
			  $this->session->set_flashdata('add','Score Successfully Inserted...'); 
		      header("Location: ".base_url()."admin/score/organization_score");
		    } 
	  }
	  public  function add_faith()
	
	  {
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('from_score', 'From Score', 'required');
		   $this->form_validation->set_rules('to_score', 'To Score', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
				$data['class'] = "settings";
			  	 $data['content'] = 'admin/faith_score_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{  
		  	        $data=array(
		    		'from_score'=>$this->input->post('from_score'),
					'to_score'=>$this->input->post('to_score'),
					'level_id'=>$this->input->post('level_id'),
		  		    );
		      $this->gpi_model->insert($data,'faith_score');
			  $data['class'] = "settings";
			  $this->session->set_flashdata('add','Score Successfully Inserted...'); 
		      header("Location: ".base_url()."admin/score/faith_score");
		    } 
	  }
	  function readiness_score()
	  {
		 
		  $data['main_cls'] = 'settings';
		  $data['class'] = "readiness_score";

		  
		    $data['content'] = 'admin/readiness_score_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  function organization_score()
	  {
		  $data['class'] = "settings";
		    $data['content'] = 'admin/organization_score_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  function faith_score()
	  {
		  $data['class'] = "settings";
		    $data['content'] = 'admin/faith_score_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  function delete_readiness($id)
	  {
		  $table="readiness_score";
		  $primary="readiness_score_id";
	      $this->db->delete($table, array($primary => $id));
		   $this->session->set_flashdata('delete','Score Successfully Deleted...');
	        header("Location: ".base_url()."admin/score/readiness_score");
	  }
	   function delete_organization($id)
	  {
		  $table="organization_score";
		  $primary="organization_score_id";
	      $this->db->delete($table, array($primary => $id));
		   $this->session->set_flashdata('delete','Score Successfully Deleted...');
	        header("Location: ".base_url()."admin/score/organization_score");
	  }
	   function delete_faith($id)
	  {
		  $table="faith_score";
		  $primary="faith_score_id";
	      $this->db->delete($table, array($primary => $id));
		   $this->session->set_flashdata('delete','Score Successfully Deleted...');
	        header("Location: ".base_url()."admin/score/faith_score");
	  }
	  function update_readiness($id)
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('from_score', 'From Score', 'required');
		   $this->form_validation->set_rules('to_score', 'To Score', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');	 
		  
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				
				$data['main_cls'] = 'settings';
		 		 $data['class'] = "update_readiness";
				
				$data['content'] = 'admin/readiness_score_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		            $data=array(
		    		'from_score'=>$this->input->post('from_score'),
					'to_score'=>$this->input->post('to_score'),
					'level_id'=>$this->input->post('level_id'),
					
		  		    );
		       	$this->gpi_model->update($data,"readiness_score",$id,"readiness_score_id");
				 $this->session->set_flashdata('update','Score Successfully Updated...');
		      	  header("Location: ".base_url()."admin/score/readiness_score");
		     }
	  	}
		function update_organization($id)
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('from_score', 'From Score', 'required');
		   $this->form_validation->set_rules('to_score', 'To Score', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');	 
		  
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				$data['class'] = "settings";
				$data['content'] = 'admin/organization_score_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		            $data=array(
		    		'from_score'=>$this->input->post('from_score'),
					'to_score'=>$this->input->post('to_score'),
					'level_id'=>$this->input->post('level_id'),
					
		  		    );
		       	$this->gpi_model->update($data,"organization_score",$id,"organization_score_id");
				 $this->session->set_flashdata('update','Score Successfully Updated...');
		      	  header("Location: ".base_url()."admin/score/organization_score");
		     }
	  	}
		function update_faith($id)
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('from_score', 'From Score', 'required');
		   $this->form_validation->set_rules('to_score', 'To Score', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');	 
		  
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				$data['class'] = "settings";
				$data['content'] = 'admin/faith_score_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		            $data=array(
		    		'from_score'=>$this->input->post('from_score'),
					'to_score'=>$this->input->post('to_score'),
					'level_id'=>$this->input->post('level_id'),
					
		  		    );
		       	$this->gpi_model->update($data,"faith_score",$id,"faith_score_id");
				 $this->session->set_flashdata('update','Score Successfully Updated...');
		      	  header("Location: ".base_url()."admin/score/faith_score");
		     }
	  	}
	}
	  
  
  
  
