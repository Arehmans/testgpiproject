<?php

  class paging extends CI_Controller

  {
        function __construct() {
     
       parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
	  

	public  function add_aboutus()

	

	  {

		  $this->load->library('form_validation');

	       $this->form_validation->set_rules('aboutus_name', 'Title', 'required');
            $this->form_validation->set_rules('aboutus', 'About Us', 'required');
		   

		   

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{
				$data['class'] = "settings";
			  	 $data['content'] = 'admin/aboutus_insert';

		   		 $this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

		  	    $data=array(

		    		'aboutus_name'=>$this->input->post('aboutus_name'),
					'aboutus'=>$this->input->post('aboutus'),

					

		  		);

		      $this->gpi_model->insert($data,'aboutus');
           $this->session->set_flashdata('insert_msg','Inserted Successfully.....');
		    header("Location: ".base_url()."admin/aboutus/aboutus_view");

		 }

	  }

	  

	  

	  function paging_view()

	  {
        $this->load->library('pagination');
	   $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',22);
       $per_page =$pages->pages;
       $qry = "select * from `paging` ";
      if($this->input->get('pagetitle') != "" &&  $this->input->get('pagetitle') != "undefined") {
				$pagetitle = $this->input->get('pagetitle');
					if($pagetitle==1)
					{
					  $qry.="order by `paging_name` ASC";
					}
					else
					{
						 $qry.="order by `paging_name` DESC";
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
				$config['base_url']= base_url().'admin/paging/paging_view/?result=true&pagetitle='.$pagetitle.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
		 
$data['class'] = "settings";

				$data['main_cls'] = 'settings';
				$data['class'] = "paging_view";


		    $data['content'] = 'admin/paging_view';

		    $this->load->view('admin/layout/layout',$data);

	  }

	   function paging_view_ajax()

	  {
        $this->load->library('pagination');
	   $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',22);
       $per_page =$pages->pages;
       $qry = "select * from `paging` ";
      if($this->input->get('pagetitle') != "" &&  $this->input->get('pagetitle') != "undefined") {
				$pagetitle = $this->input->get('pagetitle');
					if($pagetitle==1)
					{
					  $qry.="order by `paging_name` ASC";
					}
					else
					{
						 $qry.="order by `paging_name` DESC";
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
				$config['base_url']= base_url().'admin/paging/paging_view/?result=true&pagetitle='.$pagetitle.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
		 

		 

		    $this->load->view('admin/paging_view_ajax',$data);

	  }


	  function delete_paging($id)

	  {

		  $table="paging";

		  $primary="paging_id";

	      $this->db->delete($table, array($primary => $id));
            $this->session->set_flashdata('delete_msg','Successfully Deleted...');
	      header("Location: ".base_url()."admin/aboutus/aboutus_view");

	  }

	  

	  function update_paging($id)

      {  

	       $this->load->library('form_validation');

	      // $this->form_validation->set_rules('aboutus_name', 'Title', 'required');
           $this->form_validation->set_rules('pages', 'Pages', 'required|numeric');
		 

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{

			  	$data['id'] = $id;
$data['class'] = "settings";

				$data['main_cls'] = 'settings';
				$data['class'] = "update_paging";


				$data['content'] = 'admin/paging_update';

				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

		           $data=array(

		    		//'aboutus_name'=>$this->input->post('aboutus_name'),
                    'pages'=>$this->input->post('pages'),
					

		  		);

				  $this->gpi_model->update($data,"paging",$this->input->post('vid'),"paging_id");  

			   
               $this->session->set_flashdata('update_msg','Successfully Updated...');
		      header("Location: ".base_url()."admin/paging/paging_view");

			}

	  	}

	 }

	  

  

  

  

