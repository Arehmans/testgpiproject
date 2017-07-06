<?php
 class levels extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
	public  function add_levels()
	
	  {
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('level_name', 'Level Name', 'required');
			   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  	 $data['content'] = 'admin/levels_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{  
		  	        $data=array(
		    		'level_name'=>$this->input->post('level_name'),
					
		  		    );
		      $this->gpi_model->insert($data,'levels');
			  $this->session->set_flashdata('add','Level Successfully Inserted...'); 
		      header("Location: ".base_url()."admin/levels/levels_view");
		    } 
	  }
	  
	  function levels_view()
	  {
		      $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',16);
                 $per_page =$pages->pages;
				$qry = "select * from `levels`";
				
				if($this->input->get('level') != "" &&  $this->input->get('level') != "undefined") {
				$level = $this->input->get('level');
					if($level==1)
					{
					  $qry.="order by `level_name` ASC";
					}
					else
					{
						 $qry.="order by `level_name` DESC";
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
				$config['base_url']= base_url().'admin/levels/levels_view/?result=true&levels='.$levels.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				
		    $data['content'] = 'admin/levels_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	   function levels_ajax($levels="")
	  {
		      $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',16);
                 $per_page =$pages->pages;
				$qry = "select * from `levels`";
				
				if($this->input->get('level') != "" &&  $this->input->get('level') != "undefined") {
				$level = $this->input->get('level');
					if($level==1)
					{
					  $qry.="order by `level_name` ASC";
					}
					else
					{
						 $qry.="order by `level_name` DESC";
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
				$config['base_url']= base_url().'admin/levels/levels_view/?result=true&levels='.$levels.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				
		   $this->load->view('admin/level_ajax',$data);
	  }
	  function delete_levels($id)
	  {
		  $table="levels";
		  $primary="level_id";
	      $this->db->delete($table, array($primary => $id));
		   $this->session->set_flashdata('delete','Level Successfully Deleted...');
	        header("Location: ".base_url()."admin/levels/levels_view");
	  }
	  
	  function update_levels($id)
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('level_name', 'Level Name', 'required');
		  
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				$data['content'] = 'admin/levels_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		            $data=array(
		    		'level_name'=>$this->input->post('level_name'),
					
		  		    );
		       	$this->gpi_model->update($data,"levels",$this->input->post('vid'),"level_id");
				 $this->session->set_flashdata('update','Level Successfully Updated...');
		      	 header("Location: ".base_url()."admin/levels/levels_view");
		     }
	  	}
	}
	  
  
  
  
