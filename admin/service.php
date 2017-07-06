<?php

  class service extends CI_Controller

  {
        function __construct() {
     
       parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
	  

	public  function add_service()

	

	  {

		  $this->load->library('form_validation');

	        $this->form_validation->set_rules('service_name', 'Title', 'required');
            $this->form_validation->set_rules('editor1', 'Service', 'required');
		   

		   

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{
				 
				  $data['main_cls'] = 'content_publishers';
				 $data['class'] = "add_service";
				 
			  	 $data['content'] = 'admin/service_insert';

		   		 $this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

		  	    $data=array(

		    		'service'=>$this->input->post('editor1'),
					'service_name'=>$this->input->post('service_name'),

					

		  		);

		      $this->gpi_model->insert($data,'service');
            $this->session->set_flashdata('insert_msg','Service Inserted Successfully.....');
		    header("Location: ".base_url()."admin/service/service_view");

		 }

	  }

	  

	  

	  function service_view()

	  {

		     $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',21);
                 $per_page =$pages->pages;
				$qry = "select * from `service` ";
				if($this->input->get('servicetitle') != "" &&  $this->input->get('servicetitle') != "undefined") {
				$servicetitle = $this->input->get('servicetitle');
					if($servicetitle==1)
					{
					  $qry.="order by `service_name` ASC";
					}
					else
					{
						 $qry.="order by `service_name` DESC";
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
				$config['base_url']= base_url().'admin/service/service_view/?result=true&servicetitle='.$servicetitle.''; 
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
				 $data['class'] = "service_view";
				 
				 
		    $data['content'] = 'admin/service_view';

		    $this->load->view('admin/layout/layout',$data);

	  }

	   function service_view_ajax()

	  {

		     $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',21);
                 $per_page =$pages->pages;
				$qry = "select * from `service` ";
				if($this->input->get('servicetitle') != "" &&  $this->input->get('servicetitle') != "undefined") {
				$servicetitle = $this->input->get('servicetitle');
					if($servicetitle==1)
					{
					  $qry.="order by `service_name` ASC";
					}
					else
					{
						 $qry.="order by `service_name` DESC";
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
				$config['base_url']= base_url().'admin/service/service_view/?result=true&servicetitle='.$servicetitle.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
		   

		    $this->load->view('admin/service_view_ajax',$data);

	  }

	  function delete_service($id)

	  {

		  $table="service";

		  $primary="service_id";

	      $this->db->delete($table, array($primary => $id));
           $this->session->set_flashdata('delete_msg','Service Successfully Deleted...');
	      header("Location: ".base_url()."admin/service/service_view");

	  }

	  

	  function update_service($id)

      {  

	       $this->load->library('form_validation');

	       $this->form_validation->set_rules('editor1', 'Service', 'required');
           $this->form_validation->set_rules('service_name', 'Title', 'required');
		 

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{

			  	$data['id'] = $id;
				
				 $data['main_cls'] = 'content_publishers';
				 $data['class'] = "update_service";
				
				$data['content'] = 'admin/service_update';

				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

		           $data=array(

		    		'service'=>$this->input->post('editor1'),
                    'service_name'=>$this->input->post('service_name'),
					 	 'status'=>$this->input->post('status'),

		  		);

				  $this->gpi_model->update($data,"service",$this->input->post('vid'),"service_id");  

			     $this->session->set_flashdata('update_msg','Service Successfully Updated...');

		      header("Location: ".base_url()."admin/service/service_view");

			}

	  	}

	 }

	  

  

  

  

