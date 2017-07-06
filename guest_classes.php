<?php

 error_reporting(0);

  class guest_classes extends CI_Controller

  {

	public  $user= "";

	   function __construct() {

     

     parent::__construct();

     parse_str($_SERVER['QUERY_SRTING'],$_REQUEST);

     $this->load->library('facebook',array("appId"=>'976570699081727',"secret"=>'91c390a9dc1b4213dc523d6f8074582d'));   

	  $user=$this->facebook->getUser();

   }

   function fblogin()

	{ 

	

	  //$user=$this->facebook->getUser();

	  //   $this->load->view('fblogin');

	 // exit;

	  if($user)

	  {

		  try{

			  echo "i m here";

			$user_profile= $this->facebook->api('/me');

			echo "</br>";

			print_r($user_profile);

		  }

		  catch(FacebookApiException $e)

		  {

			  print_r(e);

		  }

	  }

	  

	  if($user)

	  {

		   $logout= $this->facebook->getLogoutUrl();

		  echo "<a href='$logout'>Logout</a>"; 

	  }

	  else

	  {

		    $login= $this->facebook->getLoginUrl();

		  echo "<a href='$login'>Login</a>"; 

	  }

	  

	}

     public function classes_view($id)

	  {

		 // $this->session->unset_userdata('guest_id');

		  if(isset($_POST['guest_user']))

		  {

			 

		   

			 $name=$this->input->post("first_name");

			 $last=$this->input->post("last_name");

			 $email=$this->input->post("email");

			 $data=array(	"first_name"=>$name,

			    	        "last_name"=>$last,

							"email" =>$email,

			  );

			

			 $guest=$this->gpi_model->insert($data,"guest_user");

			 $guest_id = $this->db->insert_id();

			// echo $guest_id; 

			 $this->session->set_userdata("guest_id",$guest_id);

			

			$this->session->set_flashdata('guest_msg','Login as Guest Successfully...');

			header("Location: ".base_url()."classes/classes_view/".$this->input->post("gid"));

			//$last_id=$this->session->userdata('guest_id');

			//echo $last_id;		 

		  }

		   if(isset($_POST['calander_login']))

		   {

			   

			   	$login=$this->gpi_model->get_client_login($this->input->post("email"), $this->input->post('password'));

           // $id=$login->user_id;

			if($login) 

			{

				$this->session->set_userdata("gpi_id", $login->user_id);

				

			        $data=array(

		    		'online'=>1,

		  		);

			  $this->gpi_model->update($data,"users",$login->user_id,"user_id"); 

			  $username=$this->session->userdata('gpi_id');

			 	$getclass=$this->gpi_model->get_calander_classes($id,$username);

           // $id=$login->user_id;

			  if($getclass) {

				   $data1=$this->session->set_flashdata('login_error','Class is already added to Your Calander.');

					redirect(base_url()."classes/classes_view/".$id,$data1);  

			  }

			  else

			  {

			   $data2=array(	"user_id"=>$username,

			    	        "class_id"=>$id,						

			  );

			

			 $guest=$this->gpi_model->insert($data2,"class_calander");

			 $data3=$this->session->set_flashdata('login_msg','Class is Successfully Add to Your Calander.');

					redirect(base_url()."classes/classes_view/".$id,$data3);  

			  }

			}

			else

			{

				

				    $data1=$this->session->set_flashdata('login_error','Wrong Email And Password');

					redirect(base_url()."classes/classes_view/".$this->input->post("id"),$data1); 

					

			}

		   }

				$data['cls_id']=$id;

			    $data['content'] = 'classes_view';

			    $this->load->view('layout/layout',$data);

				

		

	  }

	  

	  function addtocalander($id)

	  {

		        $username=$this->session->userdata('gpi_id');

			 	$getclass=$this->gpi_model->get_calander_classes($id,$username);

           // $id=$login->user_id;

			  if($getclass) {

				   $data1=$this->session->set_flashdata('login_error','Class is already added to Your Calander.');

					redirect(base_url()."classes/classes_view/".$id,$data1);  

			  }

			  else

			  {

			   $data=array(	"user_id"=>$username,

			    	        "class_id"=>$id,						

			  );

			

			 $guest=$this->gpi_model->insert($data,"class_calander");

			 $data1=$this->session->set_flashdata('login_msg','Class is Successfully Add to Your Calander.');

					redirect(base_url()."classes/classes_view/".$id,$data1);  

			  }

	           

	  }

	  

	  public function promo_class($id)

	  {

				 
		 /* if(isset($_POST['promo_submit']))

		  {*/

			 

		  		

		//$received_data = print_r($this->input->post(),TRUE);

		if($this->input->post("poppop_login")==1){

				$login=$this->gpi_model->get_client_login($this->input->post("email"), $this->input->post('password'));

           // $id=$login->user_id;

			if($login) 

			{

				$this->session->set_userdata("gpi_id", $login->user_id);

				

			        $data=array(

		    		'online'=>1,

		  		);

			  $this->gpi_model->update($data,"users",$login->user_id,"user_id"); 

			}

			else

			{

				

				    $data1=$this->session->set_flashdata('login_error','Wrong Email And Password');
					redirect(base_url()."classes/classes_view/".$this->input->post("id"),$data1); 

					

			}

		}

		

		if(isset($_POST['promo_submit_log'])){

			

				$login=$this->gpi_model->get_client_login($this->input->post("lp_email"), $this->input->post("lp_password"));

           // $id=$login->user_id;

			if($login) 

			{

				$this->session->set_userdata("gpi_id", $login->user_id);

				

			        $data=array(

		    		'online'=>1,

		  		);

			  $this->gpi_model->update($data,"users",$login->user_id,"user_id"); 

			}

			else

			{

				

				    $data1=$this->session->set_flashdata('login_error','Wrong Email And Password');

					redirect(base_url()."classes/classes_view/".$this->input->post("pro_cls"),$data1); 

					

			}

		}

	 

	 if($this->session->userdata('gpi_id') != "")

	 {

		 $tick_id=$this->input->post('p_ticket_id');

		 $u_id=$this->session->userdata("gpi_id");

		

	

	 

		$qtydb=$this->gpi_model->getrecordbyidrow("tickets","ticket_id",$tick_id);

		

		

	    $classid=$this->input->post('p_class_id');

		//echo $classid;

		//exit;

		$remainqty=$qtydb->ticket_qty-$this->input->post("p_qty");

	//	echo $remainqty;

	     

	 $data=array(

	           'ticket_qty'=>$remainqty,

	            );

			//print_r($data);

			//echo "<br>";	

	 $data1_1=array(

	             'class_id' =>$this->input->post('p_class_id'),

				 'ticket_id' =>$this->input->post('p_ticket_id'),

				 'qty' =>$this->input->post("p_qty"),

				 'ticket_date' =>$qtydb->date_id,

				 'user_id' =>$u_id,

				 'level_id'=>$this->input->post('p_level_id'),

				 'flag' =>1,

				 );	

				//print_r($data1_1);

				//exit; 	 

     $this->gpi_model->update($data,"tickets",$tick_id,"ticket_id");

	 $this->gpi_model->insert($data1_1,"ticket_sell");

	 

	 

		        $classes=$this->gpi_model->getrecordbyidrow("classes",'class_id',$classid); 

				$tickets=$this->gpi_model->getrecordbyidrow("tickets",'ticket_id',$tick_id); 

				$users=$this->gpi_model->getrecordbyidrow("users",'user_id',$this->session->userdata('gpi_id'));

				

				$slect_qty=$this->input->post("p_qty");

				

				$ticket_level=$this->gpi_model->getrecordbyidrow("levels","level_id",$tickets->ticket_type);

				$class_date_id=$this->gpi_model->getrecordbyidrow("class_date","class_date_id",$tickets->date_id);

				

		        $config['mailtype'] = 'html';

				$this->load->library('email',$config);

				$this->load->library('table');

				

				$this->email->from('webmaster@gpiwin.com', 'GPI');

			    $this->email->to($users->email); 

			    $this->email->subject('GPI Registration');

			    $message='<html>

								<head>

									<title>GPI Registration</title>

								</head>

								<body>

								<p align="center"><h1><b>GPI Promotional Class Registration</b></h1></p><br/>

								<table align="center"><br/>

								</table><br/>

								<table>

								<tr>

										<td><b>Class Name : </b></td>

									    <td>'.$classes->class_name.'</td>

									</tr>

									<tr>

										<td><b>Ticket Name : </b></td>

										<td>'.$tickets->ticket_name.'</td>

									</tr>

									<tr>

										<td><b>Level : </b></td>

										<td>'.$ticket_level->level_name.'</td>

									</tr>

									<tr>

										<td><b>Date : </b></td>

										<td>'.$class_date_id->class_date.'</td>

									</tr>

									<tr>

										<td><b>Price : </b></td>

										<td>'.$tickets->price.'</td>

									</tr>

									<tr>

										<td><b>Quantity : </b></td>

										<td>'.$slect_qty.'</td>

									</tr>

									<br /></table></body></html>';

				//print_r($message);

				//exit;

			    $this->email->message($message);

				$this->email->send();

				$this->session->set_flashdata('promo_msg1','You have been registered in the selected class successfully.'); 

				//$this->session->unset_userdata('gpi_id');  

				header("Location: ".base_url()."classes/classes_view/".$this->input->post("pro_cls"));

				//$this->session->set_flashdata('promo_msg','Promontional Class Registration Successfully...');

			   

				//echo $this->email->print_debugger();

				//exit; 

	 }

	 else

	 {

		 // $tick_id=$this->input->post('p_ticket_id');

		     $name=$this->input->post("first_name");
			 $last=$this->input->post("last_name");

			 $email=$this->input->post("email");

			 $data=array(	"first_name"=>$name,

			    	        "last_name"=>$last,

							"email" =>$email,

			  );

			

			 $guest=$this->gpi_model->insert($data,"guest_user");

			 $guest_id = $this->db->insert_id();

			// echo $guest_id; 

			 $this->session->set_userdata("guest_id",$guest_id);

			 

		   $u_id=$this->session->userdata("guest_id");

		    $tick_id=$this->input->post('p_ticket_id');

	 

		

		$qtydb=$this->gpi_model->getrecordbyidrow("tickets","ticket_id",$tick_id);

	    $classid=$this->input->post('p_class_id');

		

		$remainqty=$qtydb->ticket_qty-$this->input->post("p_qty");

	 

	 $data=array(

	           'ticket_qty'=>$remainqty,

	            );

			

	 $data1_1=array(

	            'class_id' =>$this->input->post('p_class_id'),

				 'ticket_id' =>$this->input->post('p_ticket_id'),

				 'qty' =>$this->input->post("p_qty"),

				 'ticket_date' =>$qtydb->date_id,

				 'user_id' =>$u_id,

				 'level_id'=>$this->input->post('p_level_id'),

				 'flag' =>0,

				 );		 

     $this->gpi_model->update($data,"tickets",$tick_id,"ticket_id");

	 $this->gpi_model->insert($data1_1,"ticket_sell");

	 

	 

			    $classes=$this->gpi_model->getrecordbyidrow("classes",'class_id',$classid); 

				$tickets=$this->gpi_model->getrecordbyidrow("tickets",'ticket_id',$tick_id); 

				$users=$this->gpi_model->getrecordbyidrow("guest_user",'guest_user_id',$this->session->userdata('guest_id'));

				

				$slect_qty=$this->input->post("p_qty");

				

				$ticket_level=$this->gpi_model->getrecordbyidrow("levels","level_id",$tickets->ticket_type);

				$class_date_id=$this->gpi_model->getrecordbyidrow("class_date","class_date_id",$tickets->date_id);

				

		        $config['mailtype'] = 'html';

				$this->load->library('email',$config);

				$this->load->library('table');

				

				$this->email->from('no-reply@gpiwin.com', 'GPI');

			    $this->email->to($users->email); 

			    $this->email->subject('GPI Registration');

			    $message='<html>

								<head>

									<title>GPI Registration</title>

								</head>

								<body>

								<p align="center"><h1><b>GPI Promotional Class Registration</b></h1></p><br/>

								<table align="center"><br/>

								</table><br/>

								<table>

								<tr>

										<td><b>Class Name : </b></td>

									    <td>'.$classes->class_name.'</td>

									</tr>

									<tr>

										<td><b>Ticket Name : </b></td>

										<td>'.$tickets->ticket_name.'</td>

									</tr>

									<tr>

										<td><b>Level : </b></td>

										<td>'.$ticket_level->level_name.'</td>

									</tr>

									<tr>

										<td><b>Date : </b></td>

										<td>'.$class_date_id->class_date.'</td>

									</tr>

									<tr>

										<td><b>Price : </b></td>

										<td>'.$tickets->price.'</td>

									</tr>

									<tr>

										<td><b>Quantity : </b></td>

										<td>'.$slect_qty.'</td>

									</tr>

									<br /></table></body></html>';

				//print_r($message);

				//exit;

			    $this->email->message($message);

				$this->email->send();

				 $this->session->unset_userdata('guest_id');  

				$this->session->set_flashdata('promo_msg1','You have been registered in the selected class successfully.'); 

				header("Location: ".base_url()."classes/classes_view/".$this->input->post("pro_cls"));

				 

				//echo $this->email->print_debugger();

				//exit;  

	 			//}

	

		  }

		  

		  

				$data['cls_id']=$id;

			    $data['content'] = 'classes_view';

			    $this->load->view('layout/layout',$data);

	  }

  }