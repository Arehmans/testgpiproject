
<?php
error_reporting(0);
  class Church extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
     public  function add_church_questions()
	
	  {
		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('questions_name', 'Questions', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		  if ($this->form_validation->run() == FALSE)
			{
			  	 $data['content'] = 'admin/church_questions_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{
				 $data=array(

		    		'questions_name'=>$this->input->post('questions_name'),	

					

		  		);
		 	   $this->gpi_model->insert($data,'church_questions');	
			 
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
                 
				$this->gpi_model->insert($data2,'church_answer');
				$i++;
				}
			
		    // header("Location: ".base_url()."admin/church/church_questions_view");
			echo "<script>window.location.href = '".base_url()."admin/church/church_questions_view'</script>";
		 }
	  }
	  
	  public  function add_church_answers()
	
	  {
		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('questions_id', 'Select Question', 'required');
		  $this->form_validation->set_rules('answer_name', 'Answer', 'required');
	      $this->form_validation->set_rules('answer_point', 'Point', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		  if ($this->form_validation->run() == FALSE)
			{
			  	 $data['content'] = 'admin/church_answers_insert';
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
                 
				$this->gpi_model->insert($data,'church_answer');
				$i++;
				}
				
			   // header("Location: ".base_url()."admin/church/church_questions_view");
			    echo "<script>window.location.href = '".base_url()."admin/church/church_answers_view'</script>";
				//echo "<script>window.location.href='.base_url().admin/church/church_questions_view'
				//header("Location: ".base_url()."admin/church/church_questions_view");

			 }
	  }

	  function church_questions_view()
	  {
		 
		    $data['content'] = 'admin/church_questions_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
       function church_answers_view()
	  {
		 
		    $data['content'] = 'admin/church_answers_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  function delete_church_question($id)
	  {
		  $table="church_questions";
		  $primary="questions_id";
	      $this->db->delete($table, array($primary => $id));
		  $this->db->delete('church_answer', array('questions_id' => $id));
	     echo "<script>window.location.href = '".base_url()."admin/church/church_questions_view'</script>";
	  }
	  
	  function delete_church_answers($id)
	  {
		  $table="church_answer";
		  $primary="answer_id";
	      $this->db->delete($table, array($primary => $id));
	       echo "<script>window.location.href = '".base_url()."admin/church/church_answers_view'</script>";
	  }
	  
	  function update_church_questions($id)
      {  
		   $this->load->library('form_validation');
		   $this->form_validation->set_rules('questions_name', 'Questions', 'required');
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				$data['content'] = 'admin/church_questions_update';
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

		 $this->gpi_model->update($data1,"church_questions",$this->input->post('vid'),"questions_id");  
          $i=0;
		 foreach($answer_id as $answer_id)
		 {
			 
		   $data=array(
	           'answer_name'=>$answer_name1[$i],

				'answer_point'=>$answer_point1[$i],		
				);

		 $ansupdate= $this->gpi_model->update($data,"church_answer",$answer_id,"answer_id");
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
                 
				$this->gpi_model->insert($data2,'church_answer');
				$j++;
			    }
				
			
	     	}
			  echo "<script>window.location.href = '".base_url()."admin/church/church_questions_view'</script>";
			}
	  	}
		
		
	  function update_church_answer($id)
       {  
		   $this->load->library('form_validation');
		   $this->form_validation->set_rules('questions_id', 'Select Question', 'required');
		   $this->form_validation->set_rules('answer_name', 'Answer', 'required');
	       $this->form_validation->set_rules('answer_point', 'Point', 'required');
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				$data['content'] = 'admin/church_answers_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
			  $data=array(
				'questions_id'=>$this->input->post('questions_id'),	
				'answer_name'=>$this->input->post('answer_name'),
				'answer_point'=>$this->input->post('answer_point'),		
				
				);
				  $this->gpi_model->update($data,"church_answer",$this->input->post('vid'),"answer_id");  
				    echo "<script>window.location.href = '".base_url()."admin/church/church_answers_view'</script>";
		     }
	  	}
  }
  
?>	