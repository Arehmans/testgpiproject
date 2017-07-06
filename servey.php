<?php


  class Servey extends CI_Controller


  {


	  function __construct() {


  


  parent::__construct();


  


  }


	public  function servey1()


	


	  {


		   $this->load->library('form_validation');


		   //$this->form_validation->set_rules('fname', 'Name', 'required');


		   //$this->form_validation->set_rules('email', 'Email', 'required');


		   $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');


	     //  $this->form_validation->set_rules('topic_discussed', 'Topic Discussed', 'required');


		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');


 		     if  ($this->form_validation->run() == FALSE)


			    {


			  	  $data['content'] = 'servey_1';


		   		  $this->load->view('layout/layout',$data);


			    }


			     else


			     { 


		  	       


				   


				    $i=0;


					$a=array();


				     for($i;$i<=9;$i++)


				   {	


				    /*$qname=array($this->input->post('hid_'.$i));*/			


				     $a[]=array($this->input->post('hid_'.$i)=>$this->input->post('question_'.$i));


				  }


				//print_r($this->input->post('question_1'));


				$today_session=json_encode($a);


				  $j=0;


					$question=array();


				     for($j;$j<=28;$j++)


				   {	


				    /*$qname=array($this->input->post('hid_'.$i));*/			


				     $question[]=array($this->input->post('que_'.$j)=>$this->input->post('ans_'.$j));


				  }


				//print_r($this->input->post('question_1'));


				$question_session=json_encode($question);


							//	'fname'=>$this->input->post('fname'),


							//	'email'=>$this->input->post('email'),

				 $data=array(


								'zip_code'=>$this->input->post('zip_code'),


								'age'=>$this->input->post('age'),


								'today_session'=>$today_session,


								'topic_discussed'=>$this->input->post('topic_discussed'),


								'experience_suggstions'=>$this->input->post('experience_suggstions'),


								'questions'=>$question_session,


								'level_id'=>1,


							);


						$this->gpi_model->insert($data, "survey_1");


					    $this->session->set_flashdata('msg', "Survey Level 1-2 Submitted Successfully.");


				


				        redirect(base_url()."servey/servey1");


		        }


	       }


		   


		   public  function servey2()


	


	     {


		   $this->load->library('form_validation');


		//   $this->form_validation->set_rules('fname', 'Name', 'required');


		 //  $this->form_validation->set_rules('email', 'Email', 'required');


		   $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');


	     //  $this->form_validation->set_rules('topic_discussed', 'Topic Discussed', 'required');


		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');


 		     if  ($this->form_validation->run() == FALSE)


			    {


			  	  $data['content'] = 'servey_2';


		   		  $this->load->view('layout/layout',$data);


			    }


			     else


			     { 


		  	       


				   


					


				    $i=0;


					$a=array();


				     for($i;$i<=9;$i++)


				   {	


				    /*$qname=array($this->input->post('hid_'.$i));*/			


				     $a[]=array($this->input->post('hid_'.$i)=>$this->input->post('question_'.$i));


				  }


				//print_r($this->input->post('question_1'));


				$today_session=json_encode($a);

/*'fname'=>$this->input->post('fname'),


								'email'=>$this->input->post('email'),*/
				 $data=array(


				 


								


								'zip_code'=>$this->input->post('zip_code'),


								'age'=>$this->input->post('age'),


								'today_session'=>$today_session,


								'topic_discussed'=>$this->input->post('topic_discussed'),


								'experience_suggstions'=>$this->input->post('experience_suggstions'),


								'level_id'=>2,


							);


						$this->gpi_model->insert($data, "survey_1");


						$this->session->set_flashdata('msg', " Survey Level 3-4 Submitted Successfully .");


				


				        redirect(base_url()."servey/servey2");


						


		        }


	       }


		


		 


	 public function servey3()


	


	      {


		   $this->load->library('form_validation');


		  //  $this->form_validation->set_rules('fname', 'Name', 'required');


		  // $this->form_validation->set_rules('email', 'Email', 'required');


		   $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');


	     //  $this->form_validation->set_rules('topic_discussed', 'Topic Discussed', 'required');


		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');


 		     if  ($this->form_validation->run() == FALSE)


			    {


			  	  $data['content'] = 'servey_3';


		   		  $this->load->view('layout/layout',$data);


			    }


			     else


			     { 


		  	      


				   $today_session=array();


				   $i=0;


				    for($i;$i<=9;$i++)


			      	{	


				/*$qname=array($this->input->post('hid_'.$i));*/


				      $b[]=array($this->input->post('hid_'.$i)=>$this->input->post('question_'.$i));			


				    }


				//print_r($this->input->post('question_1'));


				$today_session=json_encode($b);


				


				


				$service_servey=array();


				$j=0;


				for($j;$j<=14;$j++)


				{	


				/*$qname=array($this->input->post('hid_'.$i));*/	


				 $c[]=array($this->input->post('hide_'.$j)=>$this->input->post('questions_'.$j));			


				// $service_servey[$this->input->post('hide_'.$j)]=array($this->input->post('questions_'.$j));


				 


				}


				//print_r($this->input->post('question_1'));


				$service_servey=json_encode($c);


				


				$areas_of_expertise=array();


				$k=0;


			


				for($k;$k<=13;$k++)


				{	


				/*$qname=array($this->input->post('hid_'.$i));*/	


				 $d[]=array($this->input->post('hides_'.$k)=>$this->input->post('questiones_'.$k));			


				 //$areas_of_expertise[$this->input->post('hides_'.$k)]=array($this->input->post('questiones_'.$k));


				}


				//print_r($this->input->post('question_1'));


				


				


				


				$areas_of_expertise=json_encode($d);
				/*'fname'=>$this->input->post('fname'),


								        'email'=>$this->input->post('email'),

*/
				 $data=array(


										


								        'zip_code'=>$this->input->post('zip_code'),


								        'age'=>$this->input->post('age'),


										'today_session'=>$today_session,


										'service_servey'=>$service_servey,


										'areas_of_expertise'=>$areas_of_expertise,


										'topic_discussed'=>$this->input->post('topic_discussed'),


										'experience_suggstions'=>$this->input->post('experience_suggstions'),


										//'level_id'=>$level


								


									);


									//$this->session->set_flashdata('success', 'Data Successfully Added');


									$this->gpi_model->insert($data, "survey_1");


									$this->session->set_flashdata('msg', "Survey Orientation Evaluation Submitted Successfully.");


				


				                    redirect(base_url()."servey/servey3");


		         }


		  }


	  public  function servey4()


	 


	     {


		   $this->load->library('form_validation');


	       $this->form_validation->set_rules('fname', 'Name', 'required');


		   $this->form_validation->set_rules('email', 'Email', 'required');


		   $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');


		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');


 		     if  ($this->form_validation->run() == FALSE)


			    {


			  	  $data['content'] = 'servey_4';


		   		  $this->load->view('layout/layout',$data);


			    }


			     else


			     { 


		  	       


				 


					


				    $i=0;


					$a=array();


				     for($i;$i<=10;$i++)


				   {	


				    /*$qname=array($this->input->post('hid_'.$i));*/			


				     $a[]=array($this->input->post('hid_'.$i)=>$this->input->post('question_'.$i));


				  }


				//print_r($this->input->post('question_1'));


				$today_session=json_encode($a);


				 $data=array(


				 


								'fname'=>$this->input->post('fname'),


								'email'=>$this->input->post('email'),


								'zip_code'=>$this->input->post('zip_code'),


								'age'=>$this->input->post('age'),


								'today_session'=>$today_session,


								'topic_discussed'=>$this->input->post('topic_discussed'),


								'experience_suggstions'=>$this->input->post('experience_suggstions'),


								'level_id'=>1,


							);


						$this->gpi_model->insert($data, "survey_1");


						$this->session->set_flashdata('msg', "Survey Level 1-2 Successfully Submit.");


				


				        redirect(base_url()."servey/servey4");


		        }


	       }


	


	  }


	  


  


  


  


