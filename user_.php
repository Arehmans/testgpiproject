<?php
  class user extends CI_Controller
  { 
	    function __construct() {
    
		   parent::__construct();
		   
		  ini_set('error_reporting', E_ALL);
		  ini_set('display_errors', 'On');  //On or Off
		 if($this->session->userdata("gpi_id") == "") {
			 $this->load->model('common_model');
			 redirect(base_url()."login");
		 }
			
	   }
   
   public function download_single($res_id) {
	   
	    $accessMember = $this->common_model->select_where('*','membership_access',array('item_id'=>$res_id,'user_id'=>$this->session->userdata('gpi_id'),'item_type'=>'res'));
		if($accessMember->num_rows() >0){
			$accessMember = $accessMember->row_array();
			$this->common_model->update_array(array('access_id'=>$accessMember['access_id']),'membership_access',array('lock_item'=>'yes'));
		}
	   
	
		$this->common_model->update_array(array('resources_id'=>$res_id),'resources',array('unlock_resource'=>0));
		$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$res_id));
		//load the download helper
		$this->load->helper('download');
		//set the textfile's content 
		if (file_exists('./assets/user/file_upload/'.$fileName)) {
		$data = file_get_contents('./assets/user/file_upload/'.$fileName);
		
		//set the textfile's name
		//use this function to force the session/browser to download the created file
		
		 
		force_download($fileName, $data);
		}else{
			$folder_id = $this->common_model->select_single_field('folder_id','resources',array('resources_id'=>$res_id));
			$this->session->set_flashdata('errMsg','Sorry! File does not exists!');
			header("Location: ".base_url()."user/folder_view"."/".$folder_id);
		}
	}
   
   
   function res_sucessPaypal($res_id){
   
		$this->common_model->update_array(array('resources_id'=>$res_id),'resources',array('unlock_resource'=>1));
		//echo $this->db->last_query();die;
		$this->session->set_flashdata('success','Resource unlock successfully!');
		$folder_id = $this->common_model->select_single_field('folder_id','resources',array('resources_id'=>$res_id));
		header("Location: ".base_url()."user/folder_view"."/".$folder_id);
		
   
   }
   
   
   
   function resourcesPay(){
		$res_id = $this->input->post('res_id');
		$res_obj = $this->common_model->select_where('*','resources',array('resources_id'=>$res_id));
		$res_array = $res_obj->row_array();
		$name = $res_array['resources'];
		$price = $res_array['res_price'];
		$qty = 1 ;
		
		
		
		$config['cpp_header_image'] 	= 'Hello'; //Image header url [750 pixels wide by 90 pixels high]
		
		
		//$config['return'] 				= 'http://localhost/gpi/user/res_sucessPaypal/'.$res_id;
		//$config['cancel_return'] 		= 'http://localhost/gpi/user/res_cancelPaypayl/'.$res_id;
		//$config['notify_url'] 			= 'http://localhost/gpi/user/res_notify_url/'.$res_id; //IPN Post 

		$config['business'] 			= 'Pwatts@gpiwin.com';
		//$config['business'] 			= 'testingbusiness8877@gmail.com';
		
		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
		$config['return'] 				= 'https://www.gpiwin.com/user/res_sucessPaypal/'.$res_id;
		$config['cancel_return'] 		= 'https://www.gpiwin.com/user/res_cancelPaypayl/'.$res_id;
		$config['notify_url'] 			= 'https://www.gpiwin.com/user/res_notify_url/'.$res_id; //IPN Post 
		$config['production'] 			= TRUE; //Its false by default and will use sandbox
		$config["invoice"]				= random_string('numeric',8); //The invoice id
		
		
		
		$this->load->library('paypal',$config);
		
		$this->paypal->add($name,$price,$qty); //First item
		$this->paypal->pay(); //Proccess the payment
		
		
   }
   
   function upgradeMembership($user_id=''){
   
		$activation = $this->input->post('activate');
		
		$this->load->model('common_model');
		$result = array();
		$result['user_id'] = $user_id;
		$memObj = $this->common_model->select_where('mem_id','users',array('user_id'=> $user_id));
		$mem_array_my = $memObj->result_array();
		$mem_array = array();
		foreach($mem_array_my as $myMemkey => $myMemVal){
			$mem_array[] = $myMemVal['mem_id'];
			
		}
	//	$mem_array = array_column($mem_array_my,'mem_id');
		
		
		$mem_array = implode(", ", $mem_array);
		

		//listing of membership on the base of that user id which is login..
		$user_level_id = $this->common_model->select_single_field('level_id','users',array('user_id'=>$user_id));
		$mem_cond = "tbl_membership_level.level_id = ".$user_level_id." AND tbl_membership.mem_id NOT IN (".$mem_array.") AND mem_default !=1";
		$allMemberships  = $this->common_model->join_two_tab_where_nolimit( '*', 'tbl_membership', 'tbl_membership_level', "tbl_membership.mem_id = tbl_membership_level.mem_id", $mem_cond,"tbl_membership.mem_id", "ASC" );
		
		if($allMemberships->num_rows() >0){
			$allMemberships = $allMemberships->result_array();	
			$result['allMemberships'] = $allMemberships;
		}
		
		$result['activation'] = $activation;

		$result['content'] = 'update_membership';
		$this->load->view('layout/layoutuser',$result); 
   }
   
   public function cancelPaypayl($user_id,$upgradeId){
		echo 'cancelPaypayl'.$upgradeId;
   }
   
   
   
   function sales(){
   
		$sales =  $this->common_model->select_where('*','tbl_order',array('user_id'=>$this->session->userdata('gpi_id')));
		$data['sales'] = $sales;
     	$data['content'] = 'sales';
		$this->load->view('layout/layoutuser',$data);
		   
   }
   
   
   public function deactivate_membership(){
   
		$user_id = $this->input->post('deactive_id');
		$this->db->query("SELECT mem_id FROM tbl_membership WHERE mem_default = 1");
		$mem_id = $this->common_model->select_single_field('mem_id','tbl_membership',array('mem_default'=>1));
		$this->common_model->update_array(array("user_id"=>$user_id),"users",array('mem_id'=>$mem_id,'activate_membership'=>0));
		$this->common_model->update_array(array("user_id"=>$user_id),"tbl_order",array('frequancy'=>0));
		
		redirect('user/myprofile_view/');
   }
   
   public function sucessPaypal($user_id='',$upgradeId='',$total_cycles=0){

		$mem_id = $this->common_model->select_single_field('mem_id','users',array('user_id'=>$this->session->userdata('gpi_id')));
		$mem_id = explode(',', $mem_id);
		$from_membership = end($mem_id);
		$from_membership = $this->common_model->select_single_field('mem_name','tbl_membership',array('mem_id'=>$from_membership));
		
		
		$this->common_model->update_array(array('user_id'=>$user_id),'tbl_order',array('frequancy'=>0));
		
		$insert_array = array();
		$insert_array['user_id']					= $user_id;
		$insert_array['transaction_id'] 			= $_POST['txn_id'];
		$insert_array['ord_amount'] 				= $_POST['payment_gross'];
		$insert_array['new_mem_name'] 				= $_POST['item_name1'];
		$insert_array['ord_qty'] 					= $_POST['quantity1'];
		$insert_array['mem_id']						= $upgradeId;
		$insert_array['frequancy'] 					= $total_cycles;
		$insert_array['dateadded'] 					= time();
		$insert_array['old_mem_name']				= $from_membership;
		
		$this->common_model->insert_array('tbl_order',$insert_array);
   
   
		$mem_id = $this->common_model->select_single_field('mem_id','users',array('user_id'=>$user_id));
		$memArray = explode(", ", $mem_id);
		
		if(!in_array($upgradeId,$memArray)){
			$updated_id = $mem_id.",".$upgradeId;
			
			$this->common_model->update_array(array('user_id'=>$user_id),'users',array('mem_id'=>$updated_id,'activate_membership'=>0));
		}
			
			
		$email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>10));
		$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>10));
		$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$user_id."");
		$user = $userObj->row_array();
		$name = $user['name'];
		$package_name = $this->common_model->select_single_field('mem_name','tbl_membership',array('mem_id'=>$upgradeId));
		
		$healthy = array("{{name}}", "{{package}}", "{{quantity}}","{{amount}}","{{transaction}}");
		$yummy   = array($name, $package_name, $_POST['quantity1'],$_POST['payment_gross'],$_POST['txn_id']);
		
		$string = str_replace($healthy,$yummy,$email_content);
		
		$this->load->library('email'); 
		
		$useremail  = $this->common_model->select_single_field('email','users',array('user_id'=>$user_id));
		$useremail .= 'asadk9630@gmail.com';
		//$useremail .= ", rabbiaanam456@gmail.com";
		 $useremail .=", ceo.appexos@gmail.com";
         $this->email->from('owner@gmail.com', $name); 
         $this->email->to($useremail);
         $this->email->subject($email_subject); 
         $this->email->message($string); 
		 $this->email->set_mailtype("html");
         $this->email->send();
		redirect('user/myprofile_view/');
   }
   
   public function notify_url($user_id,$upgradeId,$frequancy=0){
		echo 'notify'.$upgradeId;
   }
   
   
   public function paypalSubmit($user_id){
   
		$upgradeId = $this->input->post('membership');
		$total_cycels = $this->input->post('select_cycles');
		
		
		
		
		//$config['business'] 			= 'testingbusiness8877@gmail.com';
		$config['cpp_header_image'] 	= 'Hello'; //Image header url [750 pixels wide by 90 pixels high]
		
		
		//$config['return'] 				= 'http://localhost/gpi/user/sucessPaypal/'.$user_id.'/'.$upgradeId.'/'.$total_cycels;
		//$config['cancel_return'] 		= 'http://localhost/gpi/user/cancelPaypayl/'.$user_id.'/'.$upgradeId.'/'.$total_cycels;
		//$config['notify_url'] 			= 'http://localhost/gpi/user/notify_url/'.$user_id.'/'.$upgradeId.'/'.$total_cycels; //IPN Post 
	
		
		$config['business'] 			= 'Pwatts@gpiwin.com';
		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
		$config['return'] 				= 'https://www.gpiwin.com/user/sucessPaypal/'.$user_id.'/'.$upgradeId;
		$config['cancel_return'] 		= 'https://www.gpiwin.com/user/cancelPaypayl/'.$user_id.'/'.$upgradeId;
		$config['notify_url'] 			= 'https://www.gpiwin.com/user/notify_url/'.$user_id.'/'.$upgradeId; //IPN Post 
		$config['production'] 			= TRUE; //Its false by default and will use sandbox
		$config["invoice"]				= random_string('numeric',8); //The invoice id
		
		
		$this->load->library('paypal',$config);
		
		$resultRow = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$upgradeId));
		$resultRow = $resultRow->row_array();
	
		$price = "";
		if($this->input->post('select_cycles') !=""){
			$cycles = $this->input->post('select_cycles');
			$price = $resultRow['mem_price'] * $cycles;
		}else{
			$price = $resultRow['mem_price'];
		}
		
		$name = $resultRow['mem_name'];
		$qty = 1;
		
	
		$this->paypal->add($name,$price,$qty); //First item
		$this->paypal->pay(); //Proccess the payment
		
   }
   
   function getName($id){
   
	return $this->common_model->select_single_field('mem_name','tbl_membership',array('mem_id'=>$id));
  }
   
   
	  function myprofile_view()
	  { 
	       $data['obj'] = $this;
		   $data['content'] = 'myprofile_view';
		   $this->load->view('layout/layoutuser',$data);
	  }
	  function myprofile_update($id)
	  {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('first_name', 'First Name', 'required');
		   $this->form_validation->set_rules('last_name', 'Last Name', 'required');
		   $this->form_validation->set_rules('email', 'Email', 'required');
		  
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   
		   if ($this->form_validation->run() == FALSE)
		   {

			   $data['id']=$id;
			   $data['content'] = 'myprofile_update';
			   $this->load->view('layout/layoutuser',$data);
		   }
		  else
		  {
			   $config['upload_path'] = './assets/uploads/';
		       $config['allowed_types'] = 'gif|jpg|png';
			   $config['max_size']	= '10000';
	
			   $this->load->library('upload', $config);
			   if ( ! $this->upload->do_upload('profile'))
				{
				//$this->session->set_flashdata('error', $this->upload->display_errors());
				header("Location: ".base_url()."user/myprofile_update/".$id);
				
				} else {
				$profile = $this->upload->data();
				}
				
				if($_FILES["profile"]["name"]!="" and (!empty($_FILES["profile"]["name"]))) {
				
					$data=array(
					'first_name'=>$this->input->post('first_name'),
					'last_name'=>$this->input->post('last_name'),
					'email'=>$this->input->post('email'),
					'zip_code'=>$this->input->post('zip_code'),
					'organization'=>$this->input->post('organization'),
					'phone_no'=>$this->input->post('phone_no'),
					'address1'=>$this->input->post('address1'),
					'address2'=>$this->input->post('address2'),
					'profile' =>$profile['file_name'],
					
					);
              
			   $this->gpi_model->update($data,"users",$this->input->post('vid'),"user_id");  
               header("Location: ".base_url()."user/myprofile_view");
			     }
		    else
		    {
		          $data=array(
				
				'first_name'=>$this->input->post('first_name'),
				'last_name'=>$this->input->post('last_name'),
				'email'=>$this->input->post('email'),
				'zip_code'=>$this->input->post('zip_code'),
				'organization'=>$this->input->post('organization'),
				'phone_no'=>$this->input->post('phone_no'),
				'address1'=>$this->input->post('address1'),
				'address2'=>$this->input->post('address2'),
				//'profile' =>$profile['file_name'],
				
				);
              
			   $this->gpi_model->update($data,"users",$this->input->post('vid'),"user_id");  
               header("Location: ".base_url()."user/myprofile_view");
			}
		  }
			
	  }
	  
	  function itemDetail(){
		 $upgradeId = $this->input->post('membership');
		 
		 $data['id']= $upgradeId;
		
		 $membershipRecord = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$upgradeId));
		 $data['membershipRecord'] = $membershipRecord->row_array();
		 $data['content'] = 'pay_membership';
	     $this->load->view('layout/layoutuser',$data);
		
	  }
	  
	  function electures_success($activation_code){
		
			$pieces = explode("_", $activation_code);
			$mem_id =  $pieces[0];
			$video_id =  $pieces[1];
			
			$mysql_membership = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$mem_id));
				if($mysql_membership->num_rows() >0){
					$mysql_membership = $mysql_membership->row_array();
						$this->common_model->update_array(array('electures_id'=>$video_id),'videos',array('unlock_videos'=>1));
						$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
						if($resourcesObj->num_rows() >0){
							foreach($resourcesObj->result_array() as $resObj=>$resValue){
								$this->common_model->update_array(array('resources_id'=>$resValue['resouces_id']),'resources',array('unlock_resource'=>1));
							}
						}
						#start of download file
						//$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
						//if($resourcesObj->num_rows() >0){
						/*
							$this->load->helper('download');
							$rootPath = realpath('./assets/user/file_upload/');
							$zipname = time()."unlochgk-resources.zip";
							// Initialize archive object
							$zip = new ZipArchive();
							$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
								foreach($resourcesObj->result_array() as $resObj=>$resValue){
									$this->common_model->update_array(array('resources_id'=>$resValue['resouces_id']),'resources',array('unlock_resource'=>1));
									$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resouces_id']));
									$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
							}
							// Zip archive will be created only after closing object
							$zip->close();
							///Then download the zipped file.
							$file = './assets/resources_archive/'.$zipname;
							$file_name = basename($file);
							header("Content-Type: application/zip");
							header("Content-Disposition: attachment; filename=" . $file_name);
							header("Content-Length: " . filesize($file));
							readfile($file);
							//header("Location: ".base_url()."user/electures_view");
							#end of download file */
						//}else{
							header("Location: ".base_url()."user/electures_view");
						//}
				}
			
	  }
	  
	  
	  function electuresPay(){
	  
			
			$title = $this->input->post('title');
			$quantity = $this->input->post('quantity');
			$price = $this->input->post('price');
			$discount = $this->input->post('discount');
			$elec_mem_id = $this->input->post('elec_mem_id');
			$elec_video_id = $this->input->post('elec_video_id');
			$activation_code = $elec_mem_id."_".$elec_video_id;

			
			$net_price = $price -($price * $discount/100);
			$net_price = ceil($net_price);
			
			//$config['business'] 			= 'testingbusiness8877@gmail.com';
			//$config['cpp_header_image'] 	= site_url('assets/user/images/logo.png'); //Image header url [750 pixels wide by 90 pixels high]
			//$config['return'] 			= 'http://localhost/gpi/user/electures_success/'.$activation_code;
			//$config['cancel_return'] 		= 'http://localhost/gpi/user/electures_cancel/'.$activation_code;
			//$config['notify_url'] 		= 'http://localhost/gpi/user/electures_notify/'.$activation_code; //IPN Post 
			
			$config['business'] 			= 'Pwatts@gpiwin.com';
			$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
			$config['return'] 				= 'https://www.gpiwin.com/user/electures_success/'.$activation_code;
			$config['cancel_return'] 		= 'https://www.gpiwin.com/user/electures_cancel/'.$activation_code;
			$config['notify_url'] 			= 'https://www.gpiwin.com/user/electures_notify/'.$activation_code; //IPN Post 
			$config['production'] 			= TRUE; //Its false by default and will use sandbox
			$config["invoice"]				= random_string('numeric',8); //The invoice id
			
			$this->load->library('paypal',$config);
			
			$this->paypal->add($title ,$net_price,$quantity); //First item
			$this->paypal->pay(); //Proccess the payment
		
		
	  }
	  
	  
	  function videoDetail(){
		
		$mem_id   	  =  $this->input->post('mem_id');
		$video_id 	  =	 $this->input->post('video_id');
		$video_type	  =  $this->input->post('video_type');
		$data=array();
		if($video_type == 0){
			 $data['discount'] = $this->common_model->select_single_field('disc_electures',' tbl_membership',array('mem_id'=>$mem_id));
		}else{
			$data['discount'] = $this->common_model->select_single_field('disc_additional_web',' tbl_membership',array('mem_id'=>$mem_id));
		}
			$video_data = $this->common_model->select_where('video_name,title,price','videos',array('electures_id'=>$video_id));
			$data['video_record'] = $video_data->row_array();
		

				
		echo	$this->load->view('ajax_video_detail',$data,true);
		 
		 
	  }
	  
	 function unlock_videos(){
		
	
		   $mem_id = $this->input->post('selected_membership');
		   $unlock_videos   = $this->input->post('total_unlock_electures');
		   $unlock_webinars = $this->input->post('total_unlock_webinars');
		   $video_id = $this->input->post('my_video_id');
		   $user_id = $this->input->post('user_id');
		   
		   
		   $resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
			if($resourcesObj->num_rows() > 0){
				foreach($resourcesObj->result_array() as $resObj=>$resValue){
					
					   $resource_insert['user_id']   = $user_id;
					   $resource_insert['item_id']   = $resValue['resouces_id'];
					   $resource_insert['item_type'] = "res";
					   $resource_insert['lock_item'] = "free";
					   
					   $accessMember_res = $this->common_model->select_where('*','membership_access',array('user_id'=>$user_id,'item_id'=>$resValue['resouces_id'],'item_type'=> 'res'));
					   if($accessMember_res->num_rows() >0){
						  $memberRow_res =  $accessMember_res->row_array();
						  $this->common_model->update_array(array('access_id'=>$memberRow_res['access_id']),'membership_access',$resource_insert);
						   
					   }else{
							$this->common_model->insert_array('membership_access',$resource_insert);
					   }

				}
			}

		   
		   $insert_array['user_id']   = $user_id;
		   $insert_array['item_id']   = $video_id;
		   $insert_array['item_type'] = "video";
		   $insert_array['lock_item'] = "free";
		   
		   $accessMember = $this->common_model->select_where('*','membership_access',array('user_id'=>$user_id,'item_id'=>$video_id,'item_type'=> 'video'));
		   if($accessMember->num_rows() >0){
			  $memberRow =  $accessMember->row_array();
			  $this->common_model->update_array(array('access_id'=>$memberRow['access_id']),'membership_access',$insert_array);
			   
		   }else{
				$this->common_model->insert_array('membership_access',$insert_array);
		   }
		   
		   header("Location: ".base_url()."user/electures_view");	
		   
		   
		   
		   
		   
		 /*  if($unlock_webinars !=""){
			   $insert_array['webinar'] = 1;
			   $this->common_model->insert_array('video_type',$insert_array);
		   }else if($unlock_videos !=""){
			   $insert_array['webinar'] = 1;
			   $this->common_model->insert_array('video_type',$insert_array);
		   }
		   */
		   	
		   
		/*	$mysql_membership = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$mem_id));
			
			if($mysql_membership->num_rows() >0){
			
				$mysql_membership = $mysql_membership->row_array();
				
					if($unlock_webinars !=""){
							if($unlock_webinars < $mysql_membership['web_per_month']){
								$this->common_model->update_array(array('electures_id'=>$video_id),'videos',array('unlock_videos'=>1));
								$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
								
								if($resourcesObj->num_rows() > 0){
									foreach($resourcesObj->result_array() as $resObj=>$resValue){
										$this->common_model->update_array(array('resources_id'=>$resValue['resouces_id']),'resources',array('unlock_resource'=>1));
									}
								}
								header("Location: ".base_url()."user/electures_view");
						 }
					 
					 }else if($unlock_videos !=""){
						 
						if($unlock_videos < $mysql_membership['elec_per_month']){
							$this->common_model->update_array(array('electures_id'=>$video_id),'videos',array('unlock_videos'=>1));
							$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
							if($resourcesObj->num_rows() > 0){
								foreach($resourcesObj->result_array() as $resObj=>$resValue){
									$this->common_model->update_array(array('resources_id'=>$resValue['resouces_id']),'resources',array('unlock_resource'=>1));
								}
							}
							header("Location: ".base_url()."user/electures_view");
						}	 
				}
				header("Location: ".base_url()."user/electures_view");			
			}	
			*/
			
		
		  }
		    
		  function unlock_resources(){
		
			   $mem_id = $this->input->post('selected_membership');
			   $unlock_videos = $this->input->post('total_uncheck');
			   $my_folder_id = $this->input->post('my_folder_id');
			   $user_id = $this->input->post('user_id');
			   
			   
			  // $activation_link = json_encode(array('mem_id'=>$mem_id,'unlock_videos'=>$unlock_videos,'video_id'=>$video_id));
				$activation_link = $mem_id."_".$unlock_videos."_".$my_folder_id."_".$user_id;
				$this->common_model->update_array(array('resource_folder_id'=>$my_folder_id),'resource_folder',array('activation_field'=>$activation_link));
				$this->load->library('email');
				$link = site_url('user/resources_email_activation'.'/'.$activation_link);
				$string =  "<a href=".$link.">Click Here to unlock Webinars</a>";
				$body = '<html><head></head><body> Thank you for Unlocking! Please go to this address to download and unlock  files: '.$string.' </body></html>';
				
				$headers = 'From: noreply@ example.com' . "\r\n" .
				$useremail  = $this->common_model->select_single_field('email','users',array('user_id'=>$user_id));
				// To send HTML mail, the Content-type header must be set
				// $useremail = 'ceo.appexos@gmail.com';
				$useremail .= ',asadk9630@gmail.com';
				$from = 'testing@gmail.com';      
				$headersfrom='';
				$headersfrom .= 'MIME-Version: 1.0' . "\r\n";
				$headersfrom .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headersfrom .= 'From: '.$from.' '. "\r\n";
				mail($useremail,'Unlocking Resources',$body,$headersfrom);
				$this->session->set_flashdata('success', 'Unlocking email has been sent successfully!');
				header("Location: ".base_url()."user/electures_view");
	  }
	  
	  
	    function resources_email_activation($activation_code){
		
		$pieces = explode("_", $activation_code);
		$mem_id =  $pieces[0];
		$unlock_videos =  $pieces[1];
		$video_id =  $pieces[2];
		$user_id =  $pieces[3];
		
		$db_activatedCode = $this->common_model->select_single_field('activation_field','videos',array('electures_id'=>$video_id));
			if($activation_code == $db_activatedCode){
			$mysql_membership = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$mem_id));
				if($mysql_membership->num_rows() >0){
					$mysql_membership = $mysql_membership->row_array();
					
					if($unlock_videos < $mysql_membership['web_per_month']){	
						$this->common_model->update_array(array('electures_id'=>$video_id),'videos',array('unlock_videos'=>1,'activation_field'=>''));
						#start of download file
						$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
						$this->load->helper('download');
						$rootPath = realpath('./assets/user/file_upload/');
						$zipname = time()."unlock-resources.zip";
						// Initialize archive object
						$zip = new ZipArchive();
						$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
							foreach($resourcesObj->result_array() as $resObj=>$resValue){
								$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resouces_id']));
								$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
						}
						// Zip archive will be created only after closing object
						$zip->close();
						///Then download the zipped file.
						$file = './assets/resources_archive/'.$zipname;
						$file_name = basename($file);
						header("Content-Type: application/zip");
						header("Content-Disposition: attachment; filename=" . $file_name);
						header("Content-Length: " . filesize($file));
						readfile($file);
						//header("Location: ".base_url()."user/electures_view");
						#end of download file
									
						$this->session->set_flashdata('video_data',$video_id);
						$this->session->set_flashdata('success', 'Video Unlock Successfully');
						header("Location: ".base_url()."user/electures_view");
					}else{
				
						$video_record = $this->common_model->select_where('*','videos',array('electures_id'=>$video_id));
						$video_record  = $video_record->row_array();
						
						$membership_record = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$mem_id));
						$membership_record = $membership_record->row_array();
						$discount = $membership_record['disc_electures'];
						
						$name = $video_record['video_name'];
						$discount = ($video_record['price']*$discount)/100;
						$price = $video_record['price'] - $discount;
						$qty = 1;
						
						
						
						//$config['business'] 			= 'testingbusiness8877@gmail.com';
						$config['cpp_header_image'] 	= 'Hello'; //Image header url [750 pixels wide by 90 pixels high]
						
						
						//$config['return'] 				= 'http://localhost/gpi/user/sucessPaypal/'.$user_id.'/'.$upgradeId.'/'.$total_cycels;
						//$config['cancel_return'] 		= 'http://localhost/gpi/user/cancelPaypayl/'.$user_id.'/'.$upgradeId.'/'.$total_cycels;
						//$config['notify_url'] 			= 'http://localhost/gpi/user/notify_url/'.$user_id.'/'.$upgradeId.'/'.$total_cycels; //IPN Post 
					
						$config['business'] 			= 'Pwatts@gpiwin.com';
						$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
						$config['return'] 				= 'https://www.gpiwin.com/user/mysuccess/'.$activation_code;
						$config['cancel_return'] 		= 'https://www.gpiwin.com/user/cancelPaypayl/'.$activation_code;
						$config['notify_url'] 			= 'https://www.gpiwin.com/user/notify_url/'.$activation_code; //IPN Post 
						$config['production'] 			= TRUE; //Its false by default and will use sandbox
						$config["invoice"]				= random_string('numeric',8); //The invoice id
						
						$this->load->library('paypal',$config);
						
						
						$this->paypal->add($name,$price,$qty); //First item
						$this->paypal->pay(); //Proccess the payment
						
						
						
						}
					
				}
		 

		 }else{
				$this->session->set_flashdata('error', 'Sorry! video already unlocked.');
				header("Location: ".base_url()."user/electures_view");
		  }
	  }
	  
	  
	  
	  
	  
	  
	  
	  
	  function mysuccess($activation_code){
	  
		$pieces = explode("_", $activation_code);
		$mem_id =  $pieces[0];
		$unlock_videos =  $pieces[1];
		$video_id =  $pieces[2];
		$user_id =  $pieces[3];
		
		
		
		
		$db_activatedCode = $this->common_model->select_single_field('activation_field','videos',array('electures_id'=>$video_id));
			if($activation_code == $db_activatedCode){
			$mysql_membership = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$mem_id));
				if($mysql_membership->num_rows() >0){
					$mysql_membership = $mysql_membership->row_array();
					
					
						$this->common_model->update_array(array('electures_id'=>$video_id),'videos',array('unlock_videos'=>1,'activation_field'=>''));
						#start of download file
						$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
						$this->load->helper('download');
						$rootPath = realpath('./assets/user/file_upload/');
						$zipname = time()."unlochgk-resources.zip";
						// Initialize archive object
						$zip = new ZipArchive();
						$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
							foreach($resourcesObj->result_array() as $resObj=>$resValue){
								$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resouces_id']));
								$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
						}
						// Zip archive will be created only after closing object
						$zip->close();
						///Then download the zipped file.
						$file = './assets/resources_archive/'.$zipname;
						$file_name = basename($file);
						header("Content-Type: application/zip");
						header("Content-Disposition: attachment; filename=" . $file_name);
						header("Content-Length: " . filesize($file));
						readfile($file);
						//header("Location: ".base_url()."user/electures_view");
						#end of download file
									
						$this->session->set_flashdata('video_data',$video_id);
						$this->session->set_flashdata('success', 'Video Unlock Successfully');
						header("Location: ".base_url()."user/electures_view");
					
					
				}
		 
		}
	  
	  }
	  
	  function email_activation($activation_code){
		
		$pieces = explode("_", $activation_code);
		$mem_id =  $pieces[0];
		$unlock_videos =  $pieces[1];
		$video_id =  $pieces[2];
		$user_id =  $pieces[3];
		
		$db_activatedCode = $this->common_model->select_single_field('activation_field','videos',array('electures_id'=>$video_id));
			if($activation_code == $db_activatedCode){
			$mysql_membership = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$mem_id));
				if($mysql_membership->num_rows() >0){
					$mysql_membership = $mysql_membership->row_array();
					
					if($unlock_videos < $mysql_membership['web_per_month']){	
						$this->common_model->update_array(array('electures_id'=>$video_id),'videos',array('unlock_videos'=>1,'activation_field'=>''));
						#start of download file
						$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$video_id));
						$this->load->helper('download');
						$rootPath = realpath('./assets/user/file_upload/');
						$zipname = time()."unlock-resources.zip";
						// Initialize archive object
						$zip = new ZipArchive();
						$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
							foreach($resourcesObj->result_array() as $resObj=>$resValue){
								$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resouces_id']));
								$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
						}
						// Zip archive will be created only after closing object
						$zip->close();
						///Then download the zipped file.
						$file = './assets/resources_archive/'.$zipname;
						$file_name = basename($file);
						header("Content-Type: application/zip");
						header("Content-Disposition: attachment; filename=" . $file_name);
						header("Content-Length: " . filesize($file));
						readfile($file);
						//header("Location: ".base_url()."user/electures_view");
						#end of download file
									
						$this->session->set_flashdata('video_data',$video_id);
						$this->session->set_flashdata('success', 'Video Unlock Successfully');
						header("Location: ".base_url()."user/electures_view");
					}else{
				
						$video_record = $this->common_model->select_where('*','videos',array('electures_id'=>$video_id));
						$video_record  = $video_record->row_array();
						
						$membership_record = $this->common_model->select_where('*','tbl_membership',array('mem_id'=>$mem_id));
						$membership_record = $membership_record->row_array();
						$discount = $membership_record['disc_electures'];
						
						$name = $video_record['video_name'];
						$discount = ($video_record['price']*$discount)/100;
						$price = $video_record['price'] - $discount;
						$qty = 1;
						
						
						
						//$config['business'] 			= 'testingbusiness8877@gmail.com';
						$config['cpp_header_image'] 	= 'Hello'; //Image header url [750 pixels wide by 90 pixels high]
						
						
						//$config['return'] 				= 'http://localhost/gpi/user/sucessPaypal/'.$user_id.'/'.$upgradeId.'/'.$total_cycels;
						//$config['cancel_return'] 		= 'http://localhost/gpi/user/cancelPaypayl/'.$user_id.'/'.$upgradeId.'/'.$total_cycels;
						//$config['notify_url'] 			= 'http://localhost/gpi/user/notify_url/'.$user_id.'/'.$upgradeId.'/'.$total_cycels; //IPN Post 
						
						$config['business'] 			= 'Pwatts@gpiwin.com';
						$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
						$config['return'] 				= 'https://www.gpiwin.com/user/mysuccess/'.$activation_code;
						$config['cancel_return'] 		= 'https://www.gpiwin.com/user/cancelPaypayl/'.$activation_code;
						$config['notify_url'] 			= 'https://www.gpiwin.com/user/notify_url/'.$activation_code; //IPN Post 
						$config['production'] 			= TRUE; //Its false by default and will use sandbox
						$config["invoice"]				= random_string('numeric',8); //The invoice id
						
						$this->load->library('paypal',$config);
						
						
						$this->paypal->add($name,$price,$qty); //First item
						$this->paypal->pay(); //Proccess the payment
						
						
						
						}
					
				}
		 

		 }else{
				$this->session->set_flashdata('error', 'Sorry! video already unlocked.');
				header("Location: ".base_url()."user/electures_view");
		  }
	  }
	  
	  function electures_view()
	  {	  
		$myelectID = "";
		$this->load->library('pagination');
		$per_page = 10;
		$offset = $this->input->get('per_page');
		$session_user = $this->session->all_userdata();
		$new_id =  $session_user['gpi_id'];
		
		$mem_id = $this->common_model->select_single_field('GROUP_CONCAT(`mem_id`)','users',array('user_id'=>$new_id));
		$data['user_id'] = $new_id;
		$data['mem_id'] = $mem_id;
		
		if($mem_id !=0){
		
		$electID = $this->common_model->select_where("*","tbl_membership_level","mem_id IN (".$mem_id.")");
		
		
		if($electID->num_rows() >0){
			$electID_my = $electID->result_array();
		}
		
		
		$user_level_id = $this->common_model->select_single_field('level_id','users',array('user_id'=>$new_id));
		$arrayLevel = array();
		foreach($electID_my as $myKey => $myValue){
			$arrayLevel[] = $myValue['level_id'];
		}
		//$arrayLevel = array_column($electID,'level_id');
	
		if(in_array($user_level_id,$arrayLevel)){
			$myelectID = $user_level_id;
		}
		$cond = "mem_id IN (".$mem_id.") AND level_id = ".$myelectID."";
		$selectedLevels = $this->common_model->select_where('electures_id','tbl_membership_level',$cond);
		$selectedLevels = $selectedLevels->result_array();
		
		$myselectedlvlLec = array();
		for($i=0; $i<count($selectedLevels); $i++){
			$myselectedlvlLec[] = json_decode($selectedLevels[$i]['electures_id']);
		}
		$selectedlvl = "";
		$selctedLevelElec = "";
		for($i=0;$i < count($myselectedlvlLec); $i++){
			$selectedlvl = implode(", ", $myselectedlvlLec[$i]);
			$selctedLevelElec .= $selectedlvl.",";
		}
		
		$selctedLevelElec = rtrim($selctedLevelElec,',');
		

		$userlevel= $this->gpi_model->getrecordbyidrow('users','user_id', $new_id); 
		if(!$offset)
			$offset = 0;
			$this->load->model('common_model');
		if (!empty($myelectID)) {
				$videodata1=$this->db->query("SELECT * FROM (`videos`) JOIN `video_levels` ON `video_levels`.`video_id` = `videos`.`electures_id` WHERE `video_levels`.`video_id` IN (".$selctedLevelElec.") AND `videos`.`status` = 1 GROUP BY video_levels.video_id");
			
				$config['total_rows'] = $videodata1->num_rows();
				
			}else{
				$config['total_rows'] = 0;
			}
			$config['per_page']= $per_page;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['uri_segment'] = 4;	
			$config['page_query_string'] = TRUE;
			$config['base_url']= base_url().'user/electures_view?result=true'; 
			$this->pagination->initialize($config);
			$data['paginglinks'] = $this->pagination->create_links();  
			if($data['paginglinks'] != '') {
				//$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
			} else {
				$data['pagermessage'] = '';
			} 
		
			$qry = "limit  {$per_page} offset {$offset} ";
			$total_uncheck = 0;
			if($myelectID != ""){
				
				$videodata=$this->db->query("SELECT * FROM (`videos`) JOIN `video_levels` ON `video_levels`.`video_id` = `videos`.`electures_id` WHERE `video_levels`.`video_id` IN ( ".$selctedLevelElec." ) AND `videos`.`status` = 1 GROUP BY video_levels.video_id ".$qry." ");
				
				$data['videodata'] = $videodata->result();
				
				$total_webinars = $this->db->query("SELECT * FROM (`videos`) JOIN `video_levels` ON `video_levels`.`video_id` = `videos`.`electures_id` WHERE `video_levels`.`video_id` IN ( ".$selctedLevelElec." ) AND `videos`.`status` = 1  AND webinar = 1  GROUP BY video_levels.video_id ".$qry."");
					$data['total_unlock_webinars'] = "N/A";
					if($total_webinars->num_rows() >0){
						$videosIds_column1 = array();
						foreach($total_webinars->result_array() as $myResKey => $myResVal){
							$videosIds_column1[] = $myResVal['electures_id'];
						}
						
						$videosIds_column1 = implode(", ", $videosIds_column1);
						
					//	$videosIds_column1 = implode(", ", array_column($total_webinars->result_array(),'electures_id'));
						
						
						
						$total_webinars = $this->db->query("SELECT * FROM membership_access WHERE item_id IN (".$videosIds_column1.") AND user_id = ".$new_id." AND lock_item = 'free' ");
						$data['total_unlock_webinars'] = $total_webinars->num_rows();
						
					}
					
					$total_electures = $this->db->query("SELECT * FROM (`videos`) JOIN `video_levels` ON `video_levels`.`video_id` = `videos`.`electures_id` WHERE `video_levels`.`video_id` IN ( ".$selctedLevelElec." ) AND `videos`.`status` = 1  AND webinar = 0 GROUP BY video_levels.video_id ".$qry."");
					$data['total_unlock_electures'] = "N/A";
					if($total_electures->num_rows() >0){
						$videosIds_column1 = array();
						foreach($total_electures->result_array() as $totKey=>$totVal){
							$videosIds_column1[] = $totVal['electures_id'];
						}
						
						$videosIds_column1 = implode(", ", $videosIds_column1);
						
						//$videosIds_column1 = implode(", ", array_column($total_electures->result_array(),'electures_id'));
						
						$total_electures = $this->db->query("SELECT * FROM membership_access WHERE item_id IN (".$videosIds_column1.") AND user_id = ".$new_id." AND lock_item = 'free' ");
						
						$data['total_unlock_electures'] = $total_electures->num_rows();
					}
			}else{
				$data['videodata'] = "";
			}
			}

		    $data['content'] = 'electures_view';
		    $this->load->view('layout/layoutuser',$data);
	  }
	 
	 
	 
	   function electures_view_limit()
	   {	  
		$myelectID = "";
		$this->load->library('pagination');
		$per_page = 10;
		$offset = $this->input->get('per_page');
		$session_user = $this->session->all_userdata();
		$new_id =  $session_user['gpi_id'];
		
		$mem_id = $this->common_model->select_single_field('GROUP_CONCAT(`mem_id`)','users',array('user_id'=>$new_id));
		
		$data['mem_id'] = $mem_id;
		if($mem_id !=0){
		
		$electID = $this->common_model->select_where("*","tbl_membership_level","mem_id IN (".$mem_id.")");
		
		if($electID->num_rows() >0){
			$electID = $electID->result_array();
		}
		
		
		$user_level_id = $this->common_model->select_single_field('level_id','users',array('user_id'=>$new_id));
		
		$arrayLevel = array();
		
		foreach($electID as $elecKey => $elecVal){
			$arrayLevel[] = 	$elecVal['level_id'];
		}
		
	//	$arrayLevel = array_column($electID,'level_id');
	
		if(in_array($user_level_id,$arrayLevel)){
			$myelectID = $user_level_id;
		}
	
		$cond = "mem_id IN (".$mem_id.") AND level_id = ".$myelectID."";
		$selectedLevels = $this->common_model->select_where('electures_id','tbl_membership_level',$cond);
		$selectedLevels = $selectedLevels->result_array();
		
		$myselectedlvlLec = [];
		for($i=0; $i<count($selectedLevels); $i++){
			$myselectedlvlLec[] = json_decode($selectedLevels[$i]['electures_id']);
		}
		$selectedlvl = "";
		$selctedLevelElec = "";
		for($i=0;$i < count($myselectedlvlLec); $i++){
			$selectedlvl = implode(", ", $myselectedlvlLec[$i]);
			$selctedLevelElec .= $selectedlvl.",";
		}
		
		$selctedLevelElec = rtrim($selctedLevelElec,',');

		$userlevel= $this->gpi_model->getrecordbyidrow('users','user_id', $new_id); 
		if(!$offset)
			$offset = 0;
			$this->load->model('common_model');
		if (!empty($myelectID)) {
				$videodata1=$this->db->query("SELECT * FROM (`videos`) JOIN `video_levels` ON `video_levels`.`video_id` = `videos`.`electures_id` WHERE `video_levels`.`video_id` IN (".$selctedLevelElec.") AND `videos`.`status` = 1 GROUP BY video_levels.video_id");
				$config['total_rows'] = $videodata1->num_rows();
			}else{
				$config['total_rows'] = 0;
			}
			$config['per_page']= $per_page;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['uri_segment'] = 4;	
			$config['page_query_string'] = TRUE;
			$config['base_url']= base_url().'user/electures_view/?result=true'; 
			$this->pagination->initialize($config);
			$data['paginglinks'] = $this->pagination->create_links();  
			if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
			} else {
				$data['pagermessage'] = '';
			} 
		
			$qry = "limit  {$per_page} offset {$offset} ";
			
			if($myelectID != ""){
			//video_levels`.`level_id` IN (".$selectedLevels.") AND 
				$videodata=$this->db->query("SELECT * FROM (`videos`) JOIN `video_levels` ON `video_levels`.`video_id` = `videos`.`electures_id` WHERE `video_levels`.`video_id` IN ( ".$selctedLevelElec." ) AND `videos`.`status` = 1 GROUP BY video_levels.video_id ".$qry." ");
				
				$data['videodata'] = $videodata->result();
			}else{
				$data['videodata'] = "";
			}
			
			
			}
		    $data['content'] = 'electures_view';
		    $this->load->view('layout/layoutuser',$data);
	  }
	 
	 
	 

	 function upcommingclass_view()
	  {	   
		   $data['content'] = 'userupcommingclass_view';
		   $this->load->view('layout/layoutuser',$data);
	  }
	  function resources_view()
	  {	
			
		   $data['content'] = 'resources_view';
		   $this->load->view('layout/layoutuser',$data);
	  }
	  function folder_view($id)
	  {	   
	 
	       $data['id']=$id;
		   $data['content'] = 'folder_view';
		   $this->load->view('layout/layoutuser',$data);
	  }
	  function contracts_view()
	  {	   
		   $data['content'] = 'contracts_view';
		   $this->load->view('layout/layoutuser',$data);
	  }
	  
	    
	  function add_cart(){
		  $user_id = $this->session->userdata('gpi_id');
		  
		  $accessMember = $this->common_model->select_where('*','membership_access',array('user_id'=>$user_id,'item_id'=>$_GET["resourse_id"],'item_type'=> $_GET['type']));
			
			if($accessMember->num_rows() == 0){
				$inserting_array['user_id'] = $this->session->userdata('gpi_id');
				$inserting_array['item_id'] = $_GET["resourse_id"];
				$inserting_array['item_type'] = $_GET['type'];
				$inserting_array['lock_item'] = "no";
				$this->common_model->insert_array('membership_access',$inserting_array);
				
			}else{
				
				$accessMember = $accessMember->row_array();
				$updating_array['user_id']   = $this->session->userdata('gpi_id');
				$updating_array['item_type'] = $_GET['type'];
				$updating_array['lock_item'] = "no";
				$this->common_model->update_array(array('access_id'=>$accessMember['access_id']),'membership_access',$updating_array);
			}
		  
		  
		  $insert_array = array();
		  $insert_array['item_id'] = $_GET["resourse_id"];
		  $insert_array['item_type'] = $_GET['type'];
		  $insert_array['user_id'] = $this->session->userdata('gpi_id');
		  echo $this->common_model->insert_array('tbl_cart',$insert_array);
		  
	  }
	  
	  function remove_cartItems($cart_id,$type,$item_id,$user_id){
		  
		 $this->common_model->update_array(array('user_id'=>$user_id,'item_id'=>$item_id,'item_type'=>$type),'membership_access',array('lock_item'=>'yes'));
		 $this->common_model->delete_where(array('cart_id'=>$cart_id),'tbl_cart');
		 $this->session->set_flashdata('cartMsg','Item is removed from the cart');
		 redirect('user/cart_view');
		  
	  }
	  
	  function cart_view(){
		  
		  $mysqlCart = $this->common_model->select_where('*','tbl_cart',array('user_id'=>$this->session->userdata('gpi_id')));
		  $mysqlCart = $this->common_model->select_where('*','tbl_cart',array('user_id'=>$this->session->userdata('gpi_id')));
		  $mem_id = $this->common_model->select_single_field('mem_id','users',array('user_id'=>$this->session->userdata('gpi_id')));
		  $mem_id = explode(',', $mem_id);
		  $data['mem_id'] = end($mem_id);
		  
		  
		  $data['mysqlCart'] = $mysqlCart;
		  $data['content'] = 'cart_view';
		  $this->load->view('layout/layoutuser',$data);
		  
	  }
	  function clear_cart($item_id='',$user_id='',$type=''){

	 
		  $this->common_model->update_array(array('user_id'=>$user_id,'item_id'=>$item_id,'item_type'=>$type),'membership_access',array('lock_item'=>'yes'));
		  $this->common_model->delete_where(array('user_id'=>$user_id),'tbl_cart');
		  redirect('user/cart_view');
	  }
	  
	  function cart_paypal_success($user_id=''){
		
		 
		   $mysqlCarts = $this->common_model->select_where('*','tbl_cart',array('user_id'=>$user_id));
		   
		  if($mysqlCarts->num_rows() >0){
			  
			   $html = "";
			   $mem_id = $this->common_model->select_single_field('mem_id','users',array('user_id'=>$user_id));
			   $mem_id = explode(',', $mem_id);
			   $mem_id = end($mem_id);
			   $mysqlMembership = $this->common_model->select_where('disc_additional_web,disc_electures','tbl_membership',array('mem_id'=>$mem_id));
			   $mysqlMembership = $mysqlMembership->row_array();
			   $webinars_discount = $mysqlMembership['disc_additional_web'];
			   $electures_discount = $mysqlMembership['disc_electures'];
			   $qty=1;
			   $counter =1;
			   $net_price = 0;
			  foreach($mysqlCarts->result_array() as $mysqlObj){
				  $dis = 0;
				  $pp_price=0;
				  if($mysqlObj['item_type'] == "res"){
					  $item_nameObj = $this->common_model->select_where('resources,file_name','resources',array('resources_id'=>$mysqlObj['item_id']));
					  $item_nameObj = $item_nameObj->row_array();
					  $item_name = @$item_nameObj['file_name'];
					  if($item_name == ""){
						  $item_name = $item_nameObj['resources'];
					  }
					  
					  $price = $this->common_model->select_single_field('res_price','resources',array('resources_id'=>$mysqlObj['item_id'])); 
					 $pp_price = $price;
				  }else if($mysqlObj['item_type'] == "video"){
					  
					  $mysqlItem = $this->common_model->select_where('price,webinar,title','videos',array('electures_id'=>$mysqlObj['item_id']));
					  $mysqlItem = $mysqlItem->row_array();
					  $item_name = $mysqlItem['title'];
					  $webinar_status =  $mysqlItem['webinar'];
					  $item_price  =  $mysqlItem['price'];
					  $pp_price = $item_price;
					  
					 if($webinar_status == 1){
						 $discount  = $item_price * ($webinars_discount/100);
						 $price 	= $item_price  - $discount;
						 $dis = $discount;
					 } else{
						 
						$discount = $item_price * ($electures_discount/100);
						$price 	  = $item_price - $discount;
						$dis = $discount;						
					 }	  
				  }else if($mysqlObj['item_type'] == 'class'){
					  
					  $clsObj = $this->common_model->select_where('class_name,class_type','classes',array('class_id'=>$mysqlObj['item_id']));
					  $clsObj = $clsObj->row_array();
					  
					  $liveDiscount = "";
						if($clsObj['class_type'] == 0){
							$liveDiscount = $this->common_model->select_single_field('disc_live_cls','tbl_membership',array('mem_id'=>$mem_id));
						}else if($clsObj['class_type'] == 1){
							$liveDiscount = $this->common_model->select_single_field('disc_virtual_cls','tbl_membership',array('mem_id'=>$mem_id));
						}else if($clsObj['class_type'] == 2){
							$liveDiscount = $this->common_model->select_single_field('disc_speciality_cls','tbl_membership',array('mem_id'=>$mem_id));
						}
						$item_name = $clsObj['class_name'];
						
				
				if($liveDiscount !=""){
					$price = $this->common_model->select_single_field('price','tickets',array('class_id'=>$mysqlObj['item_id']));
					$price = $price * $mysqlObj['qty'];
					$pp_price = $price;
					$discount = $price *$liveDiscount/100;
					$dis = $discount;
					$price = $price-$discount;
				}
				
				  }
				  $color = "";
				  if($counter %2==0){
					 $color =  'background-color:whitesmoke';
					}
				  $html .= "<tr style='text-align:center;".$color."'>
						<td style='text-align:left;'>".$item_name."</td>
						<td>$".$pp_price."</td> 
						<td>$".$dis."</td>
						<td>$".$price."</td> 						
						
					  </tr>";
					  $net_price += $price;
					  $counter++;
			  }
			  $total_qty = $counter-1;
			  
			 $string = "";
			  $string .= "<div style='background-color:silver; width:50%;text-align:center;padding-bottom:15px;padding:10px'>";
			  $after_content = "";
			  $mysqlEmail = $this->common_model->select_where('*','email_template',array('id'=>14));
			  if($mysqlEmail->num_rows() > 0){
				 $mysqlEmail =  $mysqlEmail->row_array();
				
				$userRecord = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$user_id."");
				 $userRecord = $userRecord->row_array();
				 $name = $userRecord['name'];
				 if($mysqlEmail['cutomer_email'] == 1){
					$string .= "<h2 style='padding-top:15px;'>".$mysqlEmail['email_subject']."</h2>";
					 $healthy = array("{{name}}");
					 $yummy   = array($name);
					 $string .= str_replace($healthy,$yummy,$mysqlEmail['before_content']);
					 $after_content = "<div style='margin-top:-15px;>".$mysqlEmail['after_content']."</div>";
				 }
				  
			  }
			  
			  
			  $string .=  "<table align='center' style='width:50%;border: 1px solid;' cellpadding='10'>
					  <tr style='text-align:center;'><td colspan='4' style='border: 1px solid;background-color: grey;color:white;font-size: 17px;'>PURCHASE ORDER SUMMARY</td></tr>
					  <tr style='background-color:whitesmoke;'>
						<th style='width: 40%;text-align:left;' style='border: 1px solid #dddddd;'>Item Name</th>
						<th style='width: 20%;border: 1px solid #dddddd;'>Price</th>
						<th style='width: 20%;text-align:left;border: 1px solid #dddddd;'>Discount</th>
						<th style='width: 20%;text-align:left;border:1px solid #dddddd;'>Amount Paid</th>
					  </tr>
					
					  ".$html."
					  
					  
					<tr style='text-align:center;color: blue;'>
					  <td colspan='1' style='text-align:left;border: 1px solid #dddddd;'><b style='font-size:18px;color:black;'>Total</b></td>
					  <td colspan='2' style='border: 1px solid #dddddd;'></td>
					  <td style='text-align:center;border: 1px solid #dddddd;'><b style='font-size:18px;color:black;'>$".$net_price."</b></td>
					  </tr>
					</table>";
					
					if($after_content !=""){
							$string .= "<p style='margin-top:-15px;>".$after_content."</p>";
					}
					
					
					$string .="</div>";
					
				
					$this->load->library('email');

					$mysqlEmailSend = $this->common_model->select_single_field('cutomer_email','email_template',array('id'=>14));
					if($mysqlEmailSend == 1){
						$useremail  = $this->common_model->select_single_field('email','users',array('user_id'=>$user_id));
						//$useremail .= ' ,asadk9630@gmail.com';
					}else{
						//$useremail = 'asadk9630@gmail.com';
					}
					
					$this->email->from('Adminstrator');
					$useremail .=' , paulakwatts.pw@gmail.com';
					$this->email->to($useremail);
					$this->email->subject('Order Detail');
					$this->email->message($string); 
					$this->email->set_mailtype("html");
					$this->email->send();
					
				
			}
	  
	  
	  
		$cartItemsObj =  $this->common_model->select_where('*','tbl_cart',array('user_id'=>$user_id));
			if($cartItemsObj->num_rows() >0){
			
				foreach($cartItemsObj->result_array() as $cartObj){
					
					if($cartObj['item_type'] == "video"){
						
						$resourcesObj = $this->common_model->select_where('resouces_id','tbl_video_resoures_relation',array('video_id'=>$cartObj['item_id']));
							if($resourcesObj->num_rows() > 0){
								foreach($resourcesObj->result_array() as $resObj=>$resValue){
									
									   $resource_insert['user_id']   = $user_id;
									   $resource_insert['item_id']   = $resValue['resouces_id'];
									   $resource_insert['item_type'] = "res";
									   $resource_insert['lock_item'] = "free";
									   
									   $accessMember_res = $this->common_model->select_where('*','membership_access',array('user_id'=>$user_id,'item_id'=>$resValue['resouces_id'],'item_type'=> 'res'));
									   if($accessMember_res->num_rows() >0){
										  $memberRow_res =  $accessMember_res->row_array();
										  $this->common_model->update_array(array('access_id'=>$memberRow_res['access_id']),'membership_access',$resource_insert);
										   
									   }else{
											$this->common_model->insert_array('membership_access',$resource_insert);
									   }
								}
							}	 
						}
					
					$accessMember = $this->common_model->select_where('*','membership_access',array('user_id'=>$user_id,'item_id'=>$cartObj['item_id'],'item_type'=> $cartObj['item_type']));
					if($accessMember->num_rows() >0){
						$this->common_model->update_array(array('item_id'=>$cartObj['item_id'],'user_id'=>$user_id),'membership_access',array('lock_item'=>"free"));
					}
				}
					
				
				}
			
			
			 $this->common_model->delete_where(array('user_id'=>$user_id),'tbl_cart');
			 $this->session->set_flashdata('success', 'Cart Items Unlock Successfully!');
			//	$this->session->set_flashdata('error', 'Sorry! video already unlocked.');
			header("Location: ".base_url()."user/electures_view");
		  
	  }
	  
	  function cart_cancel_paypal($user_id=''){
		  
		  	$this->session->set_flashdata('cartMsg', 'Payment has been cancel successfully!!');
			header("Location: ".base_url()."user/cart_view");
		  
	  }
	  
	  function cart_billing($user_id){
		  
		  $mysqlCarts = $this->common_model->select_where('*','tbl_cart',array('user_id'=>$user_id));
		  if($mysqlCarts->num_rows() >0){
			   $mem_id = $this->common_model->select_single_field('mem_id','users',array('user_id'=>$user_id));
			   $mem_id = explode(',', $mem_id);
			   $mem_id = end($mem_id);
			   $mysqlMembership = $this->common_model->select_where('disc_additional_web,disc_electures','tbl_membership',array('mem_id'=>$mem_id));
			   $mysqlMembership = $mysqlMembership->row_array();
			   $webinars_discount = $mysqlMembership['disc_additional_web'];
			   $electures_discount = $mysqlMembership['disc_electures'];
			   $qty=1;
			  foreach($mysqlCarts->result_array() as $mysqlObj){
				  
				  if($mysqlObj['item_type'] == "res"){
					  $item_nameObj = $this->common_model->select_where('resources,file_name','resources',array('resources_id'=>$mysqlObj['item_id']));
					  $item_nameObj = $item_nameObj->row_array();
					  $item_name = "";
					  if($item_name == ""){
						  $item_name = $item_nameObj['resources'];
					  }else{
						  
						 $item_name = $item_nameObj['file_name'];; 
					  }
					  
					  $price = $this->common_model->select_single_field('res_price','resources',array('resources_id'=>$mysqlObj['item_id']));  
					  $discount_res = $this->common_model->select_single_field('disc_per_temp_pro','tbl_membership',array('mem_id'=>$mem_id));
					  $discount = $price * ($discount_res /100);
					  $price = $price - $discount;
					  
				  }else if($mysqlObj['item_type'] == "video"){
					  
					  $mysqlItem = $this->common_model->select_where('price,webinar,title','videos',array('electures_id'=>$mysqlObj['item_id']));
					  $mysqlItem = $mysqlItem->row_array();
					  $item_name = $mysqlItem['title'];
					  $webinar_status =  $mysqlItem['webinar'];
					  $item_price  =  $mysqlItem['price'];
					 if($webinar_status == 1){
						 
						 $discount  = $item_price * ($webinars_discount/100);
						 $price 	= $item_price  - $discount;
						 
					 } else{
						 
						$discount = $item_price * ($electures_discount/100);
						$price 	  = $item_price - $discount; 
					 }	  
				  } else if($mysqlObj['item_type'] == 'class'){
					  
					  $clsObj = $this->common_model->select_where('class_name,class_type','classes',array('class_id'=>$mysqlObj['item_id']));
					  $clsObj = $clsObj->row_array();
					  $liveDiscount = "";
						if($clsObj['class_type'] == 0){
							$liveDiscount = $this->common_model->select_single_field('disc_live_cls','tbl_membership',array('mem_id'=>$mem_id));
						}else if($clsObj['class_type'] == 1){
							$liveDiscount = $this->common_model->select_single_field('disc_virtual_cls','tbl_membership',array('mem_id'=>$mem_id));
						}else if($clsObj['class_type'] == 2){
							$liveDiscount = $this->common_model->select_single_field('disc_speciality_cls','tbl_membership',array('mem_id'=>$mem_id));
						}
						$item_name = $clsObj['class_name'];
						
				
						if($liveDiscount !=""){
							$price = $this->common_model->select_single_field('price','tickets',array('class_id'=>$mysqlObj['item_id']));
							$price = $price * $mysqlObj['qty'];
							$discount = $price *$liveDiscount/100;
							$price = $price-$discount;
						}
				  }
				  
				  
					//	$config['return'] 				= 'http://localhost/gpi/user/cart_paypal_success/'.$user_id;
					//	$config['cancel_return'] 		= 'http://localhost/gpi/user/cart_cancel_paypal/'.$user_id;
					//	$config['notify_url'] 			= 'http://localhost/gpi/user/cart_cancel_paypal/'.$user_id; //IPN Post 
					//$config['business'] 			= 'testingbusiness8877@gmail.com';
					$config['business'] 			= 'Pwatts@gpiwin.com';

					$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]

					$config['return'] 				= 'https://www.gpiwin.com/user/cart_paypal_success/'.$user_id;

					/*$config['cancel_return'] 		= 'https://gpiwin.com/';

					$config['notify_url'] 			= 'https://gpiwin.com/'; //IPN Post */

					$config['cancel_return'] 		= 'https://www.gpiwin.com/user/cart_cancel_paypal/'.$user_id;

					$config['notify_url'] 			= 'https://www.gpiwin.com/user/cart_cancel_paypal/'.$user_id; //IPN Post 



					$config['production'] 			= TRUE; //Its false by default and will use sandbox

					$config["invoice"]				= random_string('numeric',8); //The invoice id
							

					//$config["invoice"]			= 123; //The invoice id
					
					$this->load->library('paypal',$config);
					
					$this->paypal->add($item_name,$price,$qty); //First item
					$this->paypal->pay(); //Proccess the payment
					 
			  }
			  
			  
		  }
		 
	  }
	  
	  
	  
	  
	  
	  function news_view($id)
	  {	   
	       $data['id']=$id;
		   $data['content'] = 'news_view';
		   $this->load->view('layout/layoutuser',$data);
	  }
	   function news_view_more($id)
	  {	   
	       $data['id']=$id;
		   $data['content'] = 'news_view_more';
		   $this->load->view('layout/layoutuser',$data);
	  }
	  function celender_view()
	  {	   
		   $data['content'] = 'celender_view';
		   $this->load->view('layout/layoutuser',$data);
	  }
	   function chat_view() {
	 $data['content'] = 'chat_view';
	 $this->load->view('layout/layoutuser',$data);
	 }
	 
	 
	 
	 function getzip($id){
		
		//$getfiles = $this->gpi_model->getrecordbyid('resources','folder_id',$id);
		$getfiles = $this->common_model->select_where('*','resources',array('folder_id'=>$id,'unlock_resource'=>1));
		$getfiles = $getfiles->result();
		$getfoldername = $this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$id);
		
		
		$rootPath = realpath('./assets/user/file_upload/');
		$zipname = friendlyURL($getfoldername->folder_name).".zip";
		// Initialize archive object
		$zip = new ZipArchive();
		$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		foreach ($getfiles as  $file){
		
			// Add current file to archive
			$zip->addFile('./assets/user/file_upload/'.$file->resources, $file->resources);
		}

		// Zip archive will be created only after closing object
		$zip->close();
		///Then download the zipped file.
		$file = './assets/resources_archive/'.$zipname;
		$file_name = basename($file);
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=" . $file_name);
		header("Content-Length: " . filesize($file));
		readfile($file);
	 }
  }