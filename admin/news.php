<?php

  class news extends CI_Controller

  {

	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }

	public  function add_news()

	

	  {

		   $this->load->library('form_validation');
           $this->form_validation->set_rules('news_name', 'News Title', 'required');
		   $this->form_validation->set_rules('news_date', 'Select News Date', 'required');
	       $this->form_validation->set_rules('editor1', 'News', 'required');

		   

		   

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{
				
			
				 
				 
				 $data['class'] = "add_news";
				 $data['main_cls'] = 'content_publisher';
				 
				 
			  	 $data['content'] = 'admin/news_insert';

		   		 $this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 
			
				

		  	    $data=array(

		    		'news_name'=>$this->input->post('news_name'),
					'news_date'=>date('Y-m-d',strtotime($this->input->post('news_date'))),
                    'news'=>$this->input->post('editor1'),
					'status'=>$this->input->post('status'),
		  		);
				
				
				$data['slider_news'] = 0;
				if(isset($_POST['chkNews'])){
					$data['slider_news'] = 1;
				}
				
				

		      $this->gpi_model->insert($data,'news');
            $this->session->set_flashdata('insert_msg','News Inserted Successfully.....');
		    header("Location: ".base_url()."admin/news/news_view");

		 }

	  }

	  

	  

	  function news_view()

	  {

		 		$this->load->library('pagination');
			    $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',20);
                 $per_page =$pages->pages;
				$qry = "select * from `news` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/news/news_view'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
			$data['class'] = "content_publishers";
			
			 $data['main_cls'] = 'content_publishers';
			 $data['class'] = "news_view";
			 
			 
			
		    $data['content'] = 'admin/news_view';

		    $this->load->view('admin/layout/layout',$data);

	  }

	  

	  function delete_news($id)

	  {

		  $table="news";

		  $primary="news_id";

	      $this->db->delete($table, array($primary => $id));
         $this->session->set_flashdata('delete_msg','News Successfully Deleted...');
	      header("Location: ".base_url()."admin/news/news_view");

	  }

	  

	  function update_news($id)

      {  

	       $this->load->library('form_validation');

	       $this->form_validation->set_rules('news_name', 'News Title', 'required');
		   $this->form_validation->set_rules('news_date', 'Select News Date', 'required');
	       $this->form_validation->set_rules('editor1', 'News', 'required');

		 

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   

		   if ($this->form_validation->run() == FALSE)

			{

			  	$data['id'] = $id;
$data['class'] = "content_publishers";
				$data['content'] = 'admin/news_update';
				 $data['main_cls'] = 'content_publishers';
			 	$data['class'] = "news_update";
			 
				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

		           $data=array(

		    		'news_name'=>$this->input->post('news_name'),
					'news_date'=>date('Y-m-d',strtotime($this->input->post('news_date'))),
                    'news'=>$this->input->post('editor1'),
					 'status'=>$this->input->post('status'),

		  		);
				
				$data['slider_news'] = 0;
				if(isset($_POST['chkNews'])){
					$data['slider_news'] = 1;
				}

				  $this->gpi_model->update($data,"news",$this->input->post('vid'),"news_id");  

			   
             $this->session->set_flashdata('update_msg','News Successfully Updated...');
		      header("Location: ".base_url()."admin/news/news_view");

			}

	  	}

	 }

	  

  

  

  

