<?php
 class individualassessment extends CI_Controller
  {
	 
    
	public  function add_individualassessment()
	
	  {
		  
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('first_name', 'First Name', 'required');
		   $this->form_validation->set_rules('last_name', 'Last Name', 'required');
		   $this->form_validation->set_rules('email', 'email', 'required');
		  
		  	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
				
			  	 $data['content'] = 'individualassessment_insert';
	   			 $this->load->view('layout/layoutassesment',$data);
		   		
			}
	 
			else
			{
			
				
				$config['mailtype'] = 'html';
				$this->load->library('email',$config);
				$this->load->library('table');
				
				$this->email->from('webmaster@gpiwin.com', 'GPI');
  
 		        $first_name=$this->input->post('first_name');
				$last_name=$this->input->post('last_name');
				$zip_code=$this->input->post('zip_code');
				$organization=$this->input->post('organization');
				$email=$this->input->post('email');
			 	$totalscore=$this->input->post('postscore'); 
				$password=mt_rand(1000, 9999);
				
				 
				
				
							
				if($totalscore>=0 && $totalscore<=9)
					   {
						  $level_id=1;
						  $level="1-2"; 
					   }
					   else if($totalscore>=10 && $totalscore<=27)
					   {
						   $level_id=2;
						   $level="3-4"; 
					   }
					   else if($totalscore>=28 && $totalscore<=45)
					   {
						  $level_id=3;
						  $level="5-6"; 
					   }
						else
					   {
						  $level_id=4;
						  $level="7-8"; 
					   }
				
				$data=array(
						       'first_name'=>$first_name,
							   'last_name'=>$last_name,
		   			           'email'=>$email,
					           'zip_code'=>$zip_code,
							   'organization'=>$organization,
							   'password'=>$password,
							   'score'=>$totalscore,
							   'level_id'=>$level_id,
							   'flag'=>4,
		     	        	);
							
							
				$this->gpi_model->insert($data,'users');
					   
     			$this->email->to($this->input->post('email')); 
				//$this->email->to($this->input->post('email').", michaelafleming@hotmail.com, paulakwatts.pw@gmail.com, ceo.appexos@gmail.com"); 
				
				$qa="";
				$i=0;
				
				  $answer=$this->input->post('answer_id');
				  $question=$this->input->post('question');
			      $start = '
							<html>
								<head>
									<title>GPI</title>
								</head>
								<body>
								<p align="center"><h1><b>Individual Assessment Check</b></h1></p><br/>
								<table align="center"><h1><b>Score:'.$totalscore.'</b></h1><br/>
								<h1><b>Level:'.$level.'</b></h1></table><br/>
								<table>
								<tr>
										<td><b>First Name : </b></td>
									    <td>'.$first_name.'</td>
									</tr>
									<tr>
										<td><b>Last Name : </b></td>
										<td>'.$last_name.'</td>
									</tr>
									<tr>
										<td><b>Email : </b></td>
										<td>'.$email.'</td>
									</tr>
									<tr>
										<td><b>Password : </b></td>
										<td>'.$password.'</td>
									</tr>
							        <tr>
										<td><b>Zip Code : </b></td>
									    <td>'.$zip_code.'</td>
									</tr>
									<tr>
										<td><b>Organization : </b></td>
									    <td>'.$organization.'</td>
									</tr><br />';
		     $userid = $this->db->insert_id();							
			 $mid="";
		  	 foreach($answer as $answer)
				{
				   
					$question_name=$this->gpi_model->get_questions("individual_questions",$question[$i]);
					$qa.= $question_name->questions_name.":".$answer."\n";
					//$qa.= $question_name->questions_name."\n";
					//$qa.= $answer."\n";
					
				
				    $mid.='<b>Que#'.$question_name->questions_id.':'.$question_name->questions_name.'</b><br />
							Ans)'.$answer.'<br/><br/>';
                    
					  
					
					$data2=array(
		   			'user_id'=>$userid,
					'questions' =>$question_name->questions_name,
					'answers' =>$answer,
				
		     		);
					 $this->gpi_model->insert($data2,'user_answer_individual');
					 $i++;  
					
		   		
				}
				 //print_r($data2);
				
				// exit;
			     $this->email->subject('Score');
				 $last='</table><br /><br />'.$mid.'</body></html>';
				$message=$start.$last;
				//print_r($message);
				//exit;
			    $this->email->message($message);
				$this->email->send();
				//echo $this->email->print_debugger();
				$this->session->set_flashdata('msg', "Email Successfully Sent. Your Current Level is : ".$level);
				
				redirect(base_url()."individualassessment/add_individualassessment/individual-assessment.html");
		    } 
	  }
	  
	  
			
		    
}
	   		
	 
	
	  
  
  
  
