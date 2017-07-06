<?php

  class organization_questions extends CI_Controller

  {

	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }

	public  function add_questions()

	

	  {

		  $this->load->library('form_validation');

		  $this->form_validation->set_rules('questions_name', 'Questions', 'required');

		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE)

			{
				 
				  $data['main_cls'] = 'manage_assessments';
				 $data['class'] = "add_questions";
				 
			  	 $data['content'] = 'admin/organization_questions_insert';

		   		 $this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 
			   $data=array(

		    		'questions_name'=>$this->input->post('questions_name'),	

					

		  		);
		 	    $this->gpi_model->insert($data,'organization_questions');	
			 
		        $questionid = $this->db->insert_id();
				
				$ans=$this->input->post('answer_name');
                $ans_point=$this->input->post('answer_point');
				$i=0;
				foreach($ans as $ans)
				{
				$data2=array(

				'questions_id'=>$questionid,	

				'answer_name'=>$ans,

				'answer_point'=>$ans_point[$i],		
               
				

				);
                 
				$this->gpi_model->insert($data2,'organization_answer');
				$i++;
				}
		     header("Location: ".base_url()."admin/organization_questions/questions_view");

		 }

	  }

	  

	  public  function add_answers()

	

	  {

		  $this->load->library('form_validation');

		  $this->form_validation->set_rules('questions_id', 'Select Question', 'required');

		  $this->form_validation->set_rules('answer_name', 'Answer', 'required');

	      $this->form_validation->set_rules('answer_point', 'Point', 'required');

		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE)

			{
				 
				  $data['main_cls'] = 'manage_assessments';
				 $data['class'] = "add_answers";
				 
			  	 $data['content'] = 'admin/organization_answers_insert';

		   		 $this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 
			$que=$this->input->post('questions_id');
			$ans=$this->input->post('answer_name');
            $ans_point=$this->input->post('answer_point');
				$i=0;
				foreach($ans as $ans)
				{
				$data=array(

				'questions_id'=>$que,	

				'answer_name'=>$ans,

				'answer_point'=>$ans_point[$i],		
               
				

				);
                 
				$this->gpi_model->insert($data,'organization_answer');
				$i++;
				}
				header("Location: ".base_url()."admin/organization_questions/answers_view");



			 }

	  }



	  function questions_view()

	  {

		        $this->load->library('pagination');
			   $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',6);
                 $per_page =$pages->pages;
				$qry = "select * from `organization_questions` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/organization_questions/questions_view'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
			
			
		     	$data['main_cls'] = 'manage_assessments';
				 $data['class'] = "questions_view";
			
			$data['content'] = 'admin/organization_questions_view';
			
			
			

		    $this->load->view('admin/layout/layout',$data);

	  }

       function answers_view()

	  {

		        $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',10);
                 $per_page =$pages->pages;
				$qry = "select * from `organization_answer` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/organization_questions/answers_view'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
			
			
			$data['main_cls'] = 'manage_assessments';
				 $data['class'] = "answers_view";
			
			
		    $data['content'] = 'admin/organization_answers_view';

		    $this->load->view('admin/layout/layout',$data);

	  }

	  function delete_questions($id)

	  {

		  $table="organization_questions";

		  $primary="questions_id";

	      $this->db->delete($table, array($primary => $id));
          $this->db->delete('answer', array('questions_id' => $id));
	      header("Location: ".base_url()."admin/organization_questions/questions_view");

	  }

	  

	  function delete_answers($id)

	  {

		  $table="organization_answer";

		  $primary="answer_id";

	      $this->db->delete($table, array($primary => $id));

	      header("Location: ".base_url()."admin/organization_questions/answers_view");

	  }

	  

	  function update_questions($id)

      {  


		   $this->load->library('form_validation');

		   $this->form_validation->set_rules('questions_name', 'Questions', 'required');

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   if ($this->form_validation->run() == FALSE)

			{

			  	$data['id'] = $id;
				 
				 
			$data['main_cls'] = 'manage_assessments';
				 $data['class'] = "update_questions";
				 
				$data['content'] = 'admin/organization_questions_update';

				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 
   	      $question_name=$this->input->post('questions_name');
		  $count=$this->input->post('count');
		  $answer_id=$this->input->post('aid');
       	  $answer_name1=$this->input->post('answer_name1');
		  $answer_point1=$this->input->post('answer_point1');
		   $answer_name=$this->input->post('answer_name');
		  $answer_point=$this->input->post('answer_point');
          
		    $data1=array(
	           'questions_name'=>$question_name,);

		 $this->gpi_model->update($data1,"organization_questions",$this->input->post('vid'),"questions_id");  
          $i=0;
		 foreach($answer_id as $answer_id)
		 {
			 
		   $data=array(
	           'answer_name'=>$answer_name1[$i],

				'answer_point'=>$answer_point1[$i],		
				);

		 $ansupdate= $this->gpi_model->update($data,"organization_answer",$answer_id,"answer_id");
		 $i++;
		 }
		 
		 $j=0;
		 if($answer_name!="")
		 {
			foreach($answer_name as $answer_name)
				{
					$data2=array(

				'questions_id'=>$this->input->post('vid'),	

				'answer_name'=>$answer_name,

				'answer_point'=>$answer_point[$j],		
               
				

				);
                 
				$this->gpi_model->insert($data2,'organization_answer');
				$j++;
			    }
				
			
	     	}
			  header("Location: ".base_url()."admin/organization_questions/questions_view");
			}
		
	  }
		

	  function update_answer($id)

       {  

		   $this->load->library('form_validation');

		   $this->form_validation->set_rules('questions_id', 'Select Question', 'required');

		   $this->form_validation->set_rules('answer_name', 'Answer', 'required');

	       $this->form_validation->set_rules('answer_point', 'Point', 'required');

		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		   if ($this->form_validation->run() == FALSE)

			{

			  	$data['id'] = $id;

				 $data['main_cls'] = 'manage_assessments';
				 $data['class'] = "update_answer";

				$data['content'] = 'admin/organization_answers_update';

				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

			  $data=array(

				'questions_id'=>$this->input->post('questions_id'),	

				'answer_name'=>$this->input->post('answer_name'),

				'answer_point'=>$this->input->post('answer_point'),		

				

				);

				  $this->gpi_model->update($data,"organization_answer",$this->input->post('vid'),"answer_id");  

				  header("Location: ".base_url()."admin/organization_questions/answers_view");

		     }

	  	}

		

  
}

	  

  

  

  

