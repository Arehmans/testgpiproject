<?php

 class readiness_test extends CI_Controller

  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }

	  function readiness_test_view()

	  {
           		$this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',12);
                 $per_page =$pages->pages;
				$qry = "select * from `users` where flag=1 ";
				
				if($this->input->get('flag') != "" &&  $this->input->get('flag') != "undefined") {
				$flag = $this->input->get('flag');
					$qry.="and `level_id`='".$flag."'";
				} 
				if($this->input->get('rfname') != "" &&  $this->input->get('rfname') != "undefined") {
				$rfname = $this->input->get('rfname');
					if($rfname==1)
					{
					  $qry.="order by `first_name` ASC";
					}
					else
					{
						 $qry.="order by `first_name` DESC";
					}
				}
				
				if($this->input->get('rlastname') != "" &&  $this->input->get('rlastname') != "undefined") {
				$rlastname = $this->input->get('rlastname');
					if($rlastname==1)
					{
					  $qry.="order by `last_name` ASC";
					}
					else
					{
						 $qry.="order by `last_name` DESC";
					}
				}  
				 if($this->input->get('rcontactno') != "" &&  $this->input->get('rcontactno') != "undefined") {
				$rcontactno = $this->input->get('rcontactno');
					if($rcontactno==1)
					{
					  $qry.="order by `phone_no` ASC";
					}
					else
					{
						 $qry.="order by `phone_no` DESC";
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
				$config['base_url']= base_url().'admin/readiness_test/readiness_test_view/?result=true&flag='.$flag.'&rfname='.$rfname.'&rlastname='.$rlastname.'&rcontactno='.$rcontactno.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				$data['qry'] = $this->db->query($qry)->result();
				
				 $data['main_cls'] = 'submited_assessments';
				 $data['class'] = "readiness_test_view";
				
		    	$data['content'] = 'admin/readiness_test_view';

		    $this->load->view('admin/layout/layout',$data);

	  }
	   function readiness_ajax()

	  {
		         $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',12);
                 $per_page =$pages->pages;
				$qry = "select * from `users` where flag=1 ";
				
				
				if($this->input->get('flag') != "" &&  $this->input->get('flag') != "undefined") {
				$flag = $this->input->get('flag');
					$qry.="and `level_id`='".$flag."'";
				} 
				
				if($this->input->get('rfname') != "" &&  $this->input->get('rfname') != "undefined") {
				$rfname = $this->input->get('rfname');
					if($rfname==1)
					{
					  $qry.="order by `first_name` ASC";
					}
					else
					{
						 $qry.="order by `first_name` DESC";
					}
				}
				if($this->input->get('rlastname') != "" &&  $this->input->get('rlastname') != "undefined") {
				$rlastname = $this->input->get('rlastname');
					if($rlastname==1)
					{
					  $qry.="order by `last_name` ASC";
					}
					else
					{
						 $qry.="order by `last_name` DESC";
					}
				} 
				if($this->input->get('rcontactno') != "" &&  $this->input->get('rcontactno') != "undefined") {
				$rcontactno = $this->input->get('rcontactno');
					if($rcontactno==1)
					{
					  $qry.="order by `phone_no` ASC";
					}
					else
					{
						 $qry.="order by `phone_no` DESC";
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
				$config['base_url']= base_url().'admin/readiness_test/readiness_test_view/?flag='.$flag.'&rfname='.$rfname.'&rlastname='.$rlastname.'&rcontactno='.$rcontactno.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				$data['qry'] = $this->db->query($qry)->result();
		   // $data['content'] = 'admin/organization_ajax_view';
$data['class'] = "submited_assessments";
		    $this->load->view('admin/readiness_ajax_view',$data);
	  }
      function delete_readiness_test($id)

	  {

		  $table="users";

		  $primary="user_id";

	      $this->db->delete($table, array($primary => $id));
         $this->session->set_flashdata('delete_test','Readiness Test Successfully Deleted...');
	      header("Location: ".base_url()."admin/readiness_test/readiness_test_view");

	  }
	  
	  function view_more($id)

      { 
	            $data['id'] = $id;
$data['class'] = "submited_assessments";
				$data['content'] = 'admin/readiness_test_more.php';

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
$data['class'] = "submited_assessments";
				$data['content'] = 'admin/readiness_test_update.php';

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
                $this->session->set_flashdata('update_test','Readiness Test Successfully Updated...');
		      	header("Location: ".base_url()."admin/readiness_test/readiness_test_view");

		     }

	  	}
		 
		
  }

	  

  

  

  

