<?php

  class faith_questions extends CI_Controller

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
				 $data['class'] = "add_questions_faith";
			  	 $data['content'] = 'admin/faith_questions_insert';

		   		 $this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 
			   $data=array(

		    		'questions_name'=>$this->input->post('questions_name'),	

					

		  		);
		 	    $this->gpi_model->insert($data,'faith_questions');	
			 
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
                 
				$this->gpi_model->insert($data2,'faith_answer');
				$i++;
				}
				 $this->session->set_flashdata('add_que','Question Successfully Inserted...');
		     header("Location: ".base_url()."admin/faith_questions/questions_view");

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
				 $data['class'] = "add_answers_faith";

			  	 $data['content'] = 'admin/answers_insert';

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
                 
				$this->gpi_model->insert($data,'answer');
				$i++;
				}
				header("Location: ".base_url()."admin/questions/answers_view");



			 }

	  }



	  function questions_view()

	  {

		        $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',8);
                 $per_page =$pages->pages;
				$qry = "select * from `faith_questions` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/faith_questions/questions_view'; 
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
				 $data['class'] = "questions_view_faith";

		    $data['content'] = 'admin/faith_questions_view';

		    $this->load->view('admin/layout/layout',$data);

	  }

       function answers_view()

	  {

		 		$this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',11);
                 $per_page =$pages->pages;
				$qry = "select * from `faith_answer` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/faith_questions/faith_answer'; 
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
				 $data['class'] = "answers_view_faith";
				
$data['class'] = "manage_assessments";
		    $data['content'] = 'admin/faith_answers_view';

		    $this->load->view('admin/layout/layout',$data);

	  }

	  function delete_questions($id)

	  {

		  $table="faith_questions";

		  $primary="questions_id";

	      $this->db->delete($table, array($primary => $id));
          $this->db->delete('faith_answer', array('questions_id' => $id));
		  $this->session->set_flashdata('delete_que','Question Successfully Deleted...');
	      header("Location: ".base_url()."admin/faith_questions/questions_view");

	  }

	  

	  function delete_answers($id)

	  {

		  $table="faith_answer";

		  $primary="answer_id";

	      $this->db->delete($table, array($primary => $id));
          $this->session->set_flashdata('delete_ans','Answer Successfully Deleted...');
	      header("Location: ".base_url()."admin/faith_questions/answers_view");

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
				 $data['class'] = "update_questions_faith";
				  
				  
				$data['content'] = 'admin/faith_questions_update';

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

		 $this->gpi_model->update($data1,"faith_questions",$this->input->post('vid'),"questions_id");  
          $i=0;
		 foreach($answer_id as $answer_id)
		 {
			 
		   $data=array(
	           'answer_name'=>$answer_name1[$i],

				'answer_point'=>$answer_point1[$i],		
				);

		 $ansupdate= $this->gpi_model->update($data,"faith_answer",$answer_id,"answer_id");
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
                 
				$this->gpi_model->insert($data2,'faith_answer');
				$j++;
			    }
				
			
	     	}
			 $this->session->set_flashdata('update_que','Question Successfully Updated...');
			  header("Location: ".base_url()."admin/faith_questions/questions_view");
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
				 $data['class'] = "update_answer_faith";

				$data['content'] = 'admin/faith_answers_update';

				$this->load->view('admin/layout/layout',$data);

			}

	 

			else

			{ 

			  $data=array(

				'questions_id'=>$this->input->post('questions_id'),	

				'answer_name'=>$this->input->post('answer_name'),

				'answer_point'=>$this->input->post('answer_point'),		

				

				);

				  $this->gpi_model->update($data,"faith_answer",$this->input->post('vid'),"answer_id");  
                  $this->session->set_flashdata('update_ans','Answer Successfully Updated...');
				  header("Location: ".base_url()."admin/faith_questions/answers_view");

		     }

	  	}

		

  
}

	  

  

  

  

