<?php
 class Schedule extends CI_Controller
  {
	  	 function __construct() {
     
		 parent::__construct();
		 if($this->session->userdata("admin_id") == "") {
			 redirect(base_url()."admin/login");
		 }
			
	   }
   
   		 public function index(){
	   
	 /* $html =   '<div class="col-lg-12" style="width:60%;border:1px solid #ccc;height:auto;height:260px">
				<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
					<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
                   <div style="border-bottom:1px solid;width:100%;text-align:center;;border-bottom:1px solid;width:100%;text-align:center;background-color:black;color:white;font-family: cursive;"><span>Mar</span>
                  </div> 
                   <h2 style="text-align:center;margin-top: 2px;margin-bottom: 26px;background-color:white;font-family: sans-serif;">18</h2>
				   <div style="margin-top: -24px;border-top:1px solid;width:100%;text-align:center;;background-color:white;"><span>Sat</span>
                  </div>
                    </div>
				</div>
				<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
					<div style="padding:15px;height:250px">
                    <h1><b>Meeting</b></h1>
					<a href="#" style="position:absolute;margin-top:-23px;">View on Google Calender</a><br />
					<b style="color:#ccc">When</b>  &nbsp; &nbsp;Sat 18,2017 5pm - 6pm (CST)<br />
					<b style="color:#ccc">Where</b> &nbsp; 3039 College Awe Regina SK, Canada<br />
					<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">Ddi Emplisca jhon@hotmail.com</span><br />
					<div style="margin-top:20px;">
                    <input type="button" style="background-color:#090;color:#FFF;padding:7px;width:90px;font-weight:800" value ="Accept" />
					<input type="button" style="padding:7px;width:90px;font-size:bold;font-weight:800" value ="Reject" />
                    </div>
                    </div>
				</div>
			
				<div class="col-lg-3" style="width:25%;float:left;height:250px;">
               		 <div style="padding:15px;">
                        <h1>Agenda</h1>
                        <p style="position:absolute;margin-top:-23px;color:#ccc;">Sat Mar 18,2017</p>
            
                        <b>5pm</b> &nbsp;&nbsp;&nbsp;
                        <b>Meeting</b>
                      
                    </div>
				</div>
		</div>';*/
		


	    $data['main_cls'] = 'schedule';
		$data['class'] = "schedule_view";
		$data['inactive_notifications'] = $this->common_model->select_where('*','tbl_schedule_cart',array('status'=>'inactive'));
		$data['content'] = 'admin/schedule/schedule_view';
		$mysql_shedule = $this->common_model->select_all('*','tbl_schedule');
		
		$mysql_allusers = $this->db->query("SELECT user_id,CONCAT(first_name, ' ',last_name) AS name,email FROM users");
		//$mysql_allusers = $this->common_model->select_all("user_id,CONCAT(first_name,' ',second_name) AS name,email","users");
		$data['mysql_allusers'] = $mysql_allusers->result_array();
		$mysql_services = $this->common_model->select_all("*","tbl_service");
		$data['mysql_services'] = $mysql_services;
		$data['mysql_shedule'] = $mysql_shedule;
	    $this->load->view('admin/layout/layout',$data);
   }
   
   
      	 function deleteFile(){
		   
		  $id = $this->input->post('file_id');
		  $file_name = $this->common_model->select_single_field('file_name','schedule_fiels',array('id'=>$id));
		  $upload_dir = './assets/uploads/schedule_files'.'/'.$file_name;
			if (file_exists($upload_dir)) {
				@unlink($upload_dir);
          }
		   $this->common_model->delete_where(array('id'=>$id),'schedule_fiels');
	  }
	 
    	 function uploadScheduleImages(){
			$upload_dir = './assets/uploads/schedule_files';
			$nameArr = array();
			
			foreach($_FILES as $index => $file)
			{
				// for easy access
				$fileName     = $file['name'];
				// for easy access
				$fileTempName = $file['tmp_name'];
				// check if there is an error for particular entry in array
				if(!empty($file['error'][$index]))
				{
					// some error occurred with the file in index $index
					// yield an error here
					return false;
				}
		 
				// check whether file has temporary path and whether it indeed is an uploaded file
				if(!empty($fileTempName) && is_uploaded_file($fileTempName))
				{
					$time = time();
					
					// move the file from the temporary directory to somewhere of your choosing
					$move = "/assets/uploads/schedule_files/".$file['name'];
				
					$fileName = preg_replace('/\s+/', '_',$file['name']);
					$fileName = $time.$fileName;
					$nameArr[] = $fileName;
					@move_uploaded_file($file['tmp_name'], "./assets/uploads/schedule_files".'/'.$fileName);
					
				}
			}
			
				echo json_encode($nameArr);
	 
	 }
  
  		 public function deleteShedule(){
	   $event_id =  $this->input->post('id');
	   
	   $file = $this->common_model->select_single_field('file','tbl_schedule',array('id'=>$event_id));
	   
	   $upload_dir =  getcwd().'/assets/uploads/schedule_files'.'/'.$file;
	   @unlink($upload_dir);
	   $this->common_model->delete_where(array('id'=>$event_id),'tbl_schedule');
	   $this->session->set_flashdata('scheduleMsg','Schedule  has been Successfully deleted...');
	   echo "1";
	   
  }
  
  		 public function update_date(){
		$id =  $this->input->post('id');
		
		$insert_array['start_date'] =  $this->input->post('staring_date');
		$insert_array['end_date']   =  $this->input->post('ending_date');
		
		$this->common_model->update_array(array('id'=>$id),'tbl_schedule',$insert_array);
		
	}
  
	 	 public function getShedule(){
		  
		 
		 $event_id =  $this->input->post('id');		
		 
		// $this->db->query("SELECT * FROM tbl_schedule JOIN schedule_fiels ON tbl_schedule.id  = schedule_fiels.schedule_id WHERE id=".$event_id."");
		 
		 $mysql_event = $this->db->query("SELECT *  FROM tbl_schedule WHERE id = ".$event_id."");		 
		 $mysql_event =  $mysql_event->row_array();
		 $mysqlImages =  $this->common_model->select_where("*",'schedule_fiels',array('schedule_id'=>$event_id));
		 $fileArray = array();
		
		 if($mysqlImages->num_rows() >0){
			foreach($mysqlImages->result_array() as $filesObj){
				$fileArray[] = array("image"=>$filesObj['file_name'],"id"=>$filesObj['id']);
			}
		 }
		 
		$mysql_event['images'] = $fileArray;
		 echo json_encode($mysql_event);
	  }
  	  
		 public function total_images(){
		$event_id = $this->input->post('event_id');  
		$mysql_total = $this->common_model->select_where('*','schedule_fiels',array('schedule_id'=>$event_id));
		echo $mysql_total->num_rows();
	  }

	  	 public function upload_file(){
		  
		    $upload_dir = './assets/uploads/schedule_files/';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			$fileName = time().$_FILES['file']['name'];
			$config['upload_path']   = $upload_dir;
			$config['allowed_types'] = '*';
			$config['file_name']     = $fileName;
			$config['overwrite']     = false;
			$config['max_size']  = '5120';
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')){
				echo  $this->upload->display_errors();
			}  else{
				echo $fileName;
			}
		  
	  }

	     function list_days($date_from,$date_to){
			$arr_days = array();
			$arr_days[] = date('o-m-d',$date_from);
			$day_passed = ($date_to - $date_from); //seconds
			$day_passed = ($day_passed/86400); //days
		
			$counter = 1;
			$day_to_display = $date_from;
			while($counter < $day_passed){
				$day_to_display += 86400;
				$arr_days[] = date('o-m-d',$day_to_display);
				$counter++;
			}
		
			return $arr_days;
		}
   
  		 public  function add_schedule(){
			 
			 
			
		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('name', 'Title', 'required');
		  $this->form_validation->set_rules('note', 'Notes', 'required');
		  $event_status = $this->input->post('event_status');
		  
		 
		  
          $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div><br />');
		  $data['main_cls'] = 'schedule';
		  $data['class'] = "add_schedule";
		  if ($this->form_validation->run() == FALSE){
			  	 echo validation_errors();
			}else
			{
				   $start_date = $this->input->post('start_date');
				   $end_date = $this->input->post('end_date');
				   $package_opt = $this->input->post('package_opt');
				   $custom_price = $this->input->post('price_opt');
				   $option_name = "";
				   $option_price = "";
				   if($package_opt == "custom"){
						$option_name = "Custom";  
						$option_price = $custom_price;
				   }else{
				   		$optionObj = $this->common_model->select_where('*','tbl_service',array('id'=>$package_opt));
				   		$optionObj = $optionObj->row_array();
				   		$option_name = $optionObj['ser_name'];
						$option_price = $optionObj['ser_name'];
				   }
				  
				   $event_id = $this->input->post('event_id');
				   $reciver_email = array();
				   
				   $tagsinput = $this->input->post('tagsinput');
				   if(!empty($tagsinput)){
					   $rece_email = explode(",",$tagsinput);
					   for($sm=0; $sm <count($rece_email);$sm++){
							if (!filter_var($rece_email[$sm], FILTER_VALIDATE_EMAIL) === false) {
								$reciver_email[] = $rece_email[$sm];
							} 
					   }
				   }
				  
				    if((empty($start_date)) || (empty($end_date))){
						echo "Date/Time Field is required.";  
						return false; 
				    }
				   
				   
				   $result_s = explode(" ", $start_date, 2);
				   $result_e = explode(" ", $end_date, 2);
				   $start_time = $result_s[1];
				   $end_time = $result_e[1];
				   
				   $ss_timeDb = $start_time;
				   $ee_timeDb = $end_time;
				   
				    $date_from = strtotime($start_date);
					$date_to = strtotime($end_date);
					$dateArr = $this->list_days($date_from,$date_to);
					
					 $i=0;
					 $topArray = array();
					 $maxArray = array();
					 $valueArray = array();
					 $idsArray   = array();
					 $dateArray = array();
					 $innserArray = array();
					 $innerValueArr = array();
					 $innerIdsArr   = array();
						
							
					$chap = 0;
					$myArr = array();
					$chap_1 = $package_opt;
					$t = time();
					 for($i=0; $i <count($dateArr);$i++){
						 $eachAllDateSingle = "";
						   $mysqlAlldates = $this->db->query("SELECT * FROM tbl_real_time");
						   if($mysqlAlldates->num_rows() >0){
							  // echo $start_date."<br />";
							  // echo $end_date."<br />";die;
							   
							  
							   $fDateTime = strtotime($dateArr[$i]." ". $start_time);
							  
							   $eDateTime = strtotime($dateArr[$i]." ". $end_time);
								foreach($mysqlAlldates->result_array() as $mysqlObj){
									
									$dbStartDate = strtotime($mysqlObj['date']." ". $mysqlObj['start_time']);	
									$dbENDDate 	 = strtotime($mysqlObj['date']." ". $mysqlObj['end_time']);
									
									$existFlag=0;
									$s_time ="";
									$e_time = "";
									$_date = "";
									$mysql_eventsstDate = "";
									$mysql_eventsendDate = "";
									if($event_id !=""){
										
										$mysql_eventsDate = $this->common_model->select_where('*','tbl_schedule',array('id'=>$event_id));
										$mysql_eventsDate = $mysql_eventsDate->row_array();
										$mysql_eventsstDate = strtotime($mysql_eventsDate['start_date']);
										$mysql_eventsendDate = strtotime($mysql_eventsDate['end_date']);
									}
									
									if($event_id !=""){
										if(($fDateTime >= $dbStartDate && $fDateTime <= $dbENDDate) && (!($fDateTime >= $mysql_eventsstDate && $fDateTime <= $mysql_eventsendDate))) {
											$existFlag++;
											$s_time =  date("Y-m-d",$fDateTime);
											$_date = $s_time;
										} 
										
										
										if((($eDateTime >= $dbStartDate) && ($eDateTime <= $dbENDDate)) && (!($eDateTime >= $mysql_eventsstDate && $eDateTime <= $mysql_eventsendDate))) {
											$existFlag++;
											$e_time =  date("Y-m-d",$eDateTime);
											$_date = $e_time;
										} 	
									}else{
										if(($fDateTime >= $dbStartDate && $fDateTime <= $dbENDDate)) {
											$existFlag++;
											$s_time =  date("Y-m-d",$fDateTime);
											$_date = $s_time;
										} 	
										
										if(($eDateTime >= $dbStartDate) && ($eDateTime <= $dbENDDate)) {
											$existFlag++;
											$e_time =  date("Y-m-d",$eDateTime);
											$_date = $e_time;
										} 
										
										
									}
									
									if($existFlag !=""){
										 $eachAllDateSingle =  $_date;;
										 $dateArray[] = $_date;
										
									}
								}	   
							}
			  
							if($eachAllDateSingle == ""){
							   $inseringArr = array();
							   $inseringArr['date'] = $dateArr[$i];
							   $inseringArr['start_time'] = $start_time;
							   $inseringArr['end_time']	  =  $end_time;
							   $inseringArr['multiple_delete'] = $t;
							   
							   if($package_opt == "custom"){
									$inseringArr['option_type'] = "custom"; 
								}
							   
							   $inser_id = $this->common_model->insert_array('tbl_real_time',$inseringArr);
							   $dd = $dateArr[$i]." ". $start_time;
							   $startTime = strtotime($dd);
							   $chap_1 = 30;
								$end_date = $dateArr[$i]." ". $end_time;
								
							 	while(strtotime($dd) < strtotime($end_date)){
									
									$chap_time  = date('h:i A',strtotime($dd));
									$cartItem =  date("Y-m-d h:i A",strtotime($dd));
									$dd =  date("Y-m-d h:i:s A",strtotime("+".$chap." minutes", $startTime));
									
									if(strtotime($dd) > strtotime($end_time)){
										$myArr = array();
										
										$chap =0;
										break;
									}
									
									if(!in_array($chap_time,$myArr)){
										$myArr[] = $chap_time;
										$innserArray[] = $chap_time;
										$innerValueArr[] = $cartItem;
										$innerIdsArr[] = 	$inser_id;
										//$innerIdsArr[] = 	1;
									}
								
									
									
									 $chap = $chap+$chap_1;
									 $i++;	
									 
									 
									 $insertingArrTemp['item_id']		  = "admin_".$package_opt.$inser_id;
									 $insertingArrTemp['type']    		  = "admin";
									 $insertingArrTemp['item_name']		  = $dd."_".$option_name;
									 $insertingArrTemp['item_type'] 	  = "schedule";
									 $insertingArrTemp['item_qty'] 		  = 1;
									 $insertingArrTemp['item_price'] 	  = $option_price;
									 $insertingArrTemp['item_discount']   = 0;
									 $insertingArrTemp['item_discount']   = 0;
									 $insertingArrTemp['type']   		  = 'admin';
									 
									 
									 
									 $inser_id = $this->common_model->insert_array('tbl_temp_cart_items',$insertingArrTemp);
								}
							}
					  }
					  if(!empty($dateArray)){
					   	$exisigDates =  implode(", ",$dateArray);
						
						if(!empty($event_id)){
							
						}
					   	echo "Sorry! Appointment already exist in these dates (".$exisigDates." )<br />";
						exit;	
						
					  }
					  
					  
				$name = $this->input->post('name');
				$note = $this->input->post('note');
				$insert_array['start_date'] = $start_date;
				$insert_array['end_date']   = $end_date;
				
				$insert_array['start_time']   =  $ss_timeDb;
				$insert_array['end_time']     = $ee_timeDb;
				$insert_array['sch_name']   = $this->input->post('name');
				$insert_array['dateadded']  = time();
				$insert_array['note'] 		= $this->input->post('note');
				//$insert_array['duration'] 		= $this->input->post('duration');
				
				
				$insert_array['color'] = $this->input->post('color');
				$event_type = $this->input->post('event_type');
				$recurring_opt = 0;
				$price_opt = "";
				$insert_array['event_type'] = $event_type;
				if($event_type == 1){
					$recurring_opt = $this->input->post('recurring_opt');
				}
				
				$insert_array['reminder']  = 0;
				if(isset($_POST['reminder'])){
					$insert_array['reminder'] = 1;	
					
					$reminders = $this->input->post('reminder_opt');
					$reminders = implode(",",$reminders);
					$insert_array['remember_time'] = $reminders;
					
				}
				
				
			
				$event_status = $this->input->post('event_status');
				$insert_array['event_status'] = $event_status;
				if($event_status == 1){
					
					$price_opt = $this->input->post('price_opt');
					$insert_array['option_id'] = $package_opt;
					
				
					if(!empty($custom_price)){
						$price_opt = $custom_price;	
					}else{
						$option_price = $this->common_model->select_single_field('ser_price','tbl_service',array('ser_name'=>$option_name));
						$price_opt  = $option_price;
					}
					
				}
				
				$custom_price = $this->input->post('price_opt');
				$insert_array['custom_price'] = $custom_price;
				$insert_array['event_type'] = $event_type;
				$insert_array['recuring_time'] = $recurring_opt;
				$insert_array['event_status'] = $event_status;
				$insert_array['event_price'] = $price_opt;
				
				if(isset($_POST['reminder']) && ($_POST['reminder'] !="")){
					$insert_array['reminder'] = $_POST['reminder'];
				}
				
				if(isset($_POST['invitation']) && ($_POST['invitation'] !="")){
					$insert_array['invitation'] = $_POST['invitation'];
				}
				
			
				
				if($event_id !=""){
					$this->common_model->update_array(array('id'=>$event_id),'tbl_schedule',$insert_array);
					$this->session->set_flashdata('scheduleMsg','Schedule has updated Successfully...');
						
				}else{
				
					$event_id = 	$this->common_model->insert_array('tbl_schedule',$insert_array);
					$this->session->set_flashdata('scheduleMsg','Schedule  has been Successfully Inserted...');
					
				}

				$doc_file =  $this->input->post('doc_file');
				$delete_file = $this->input->post('delete_file');
				
				//print_r($delete_file);
				 if(!empty($doc_file[0])){
					// $this->common_model->delete_where(array('schedule_id'=>$event_id),'schedule_fiels');
					for($i=0; $i <count($doc_file); $i++){
						$fileArr = explode(",",$doc_file[$i]);
						for($j=0; $j <count($fileArr);$j++){
							if($delete_file[0] != ""){
								if(!in_array($j,$delete_file)){
									$upload_dir = './assets/uploads/schedule_files'.'/'.$fileArr[$j];
									if (file_exists($upload_dir)) {
										$this->common_model->insert_array("schedule_fiels",array("schedule_id"=>$event_id,"file_name"=>$fileArr[$j]));
									}
								}
							}else{
								$upload_dir = './assets/uploads/schedule_files'.'/'.$fileArr[$j];
								if (file_exists($upload_dir)) {
									$this->common_model->insert_array("schedule_fiels",array("schedule_id"=>$event_id,"file_name"=>$fileArr[$j]));	
								}
							}
						}	
						
					}
				}
				
			$adminId = $this->session->userdata("admin_id");
			$mysql_adminRec = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) as name,email FROM users WHERE user_id = ".$adminId."");
			$mysql_adminRec = $mysql_adminRec->row_array();
			$admin_name = $mysql_adminRec['name'];
			$admin_email = $mysql_adminRec['email'];
			
			
			//$sss_date = "2017-03-28 12:00 AM";
			//$eee_date = "2017-03-28 2:00 AM";
			
			$sss_date = $this->input->post('start_date');
			$eee_date = $this->input->post('end_date');
			
			$dateStartTime = explode("-",$sss_date);
			$dateStartTime = explode(" ",$dateStartTime[2]);
			$dateStartTime = $dateStartTime[0]; //28
			
			
			
			
			
			$dateEndTime = explode("-",$eee_date);
			$dateEndTime = explode(" ",$dateEndTime[2]);
			$dateEndTime = $dateEndTime[0]; //28
			
			
			
			$sss_dateTime = explode(" ",$sss_date);
			$sss_dateTime = $sss_dateTime[1]." ".$sss_dateTime[2];
			
			$eee_dateTime = explode(" ",$eee_date);
			$eee_dateTime = $eee_dateTime[1]." ".$eee_dateTime[2];
			
			
			$sss_date = strtotime($sss_date);
			$eee_date = strtotime($eee_date);
			
			$stDay = date('D',$sss_date);
			$enDay = date('D',$eee_date);
			
			
			$stMon = date('M',$sss_date);
			$enMon = date('M',$eee_date);
			
			$stYear = date('Y',$sss_date);
			$enYear = date('Y',$eee_date);
			
			
			//$reciver_email = "aliakbar1to5@gmail.com,receiver@local.com";
			
			
			 /*<input type="button" style="background-color:#090;color:#FFF;padding:7px;width:90px;font-weight:800" value ="Accept" />
			 <input type="button" style="padding:7px;width:90px;font-size:bold;font-weight:800" value ="Reject" />
		*/
			//echo $reciver_email;die;
			
			//  <div style="border-top:1px solid;width:50%;margin-left:24%;margin-top: -27%;text-align:center;background-color:white;"><span>Sat</span>
             //     </div>
						 
			 $headers = array("From: from@example.com",
				"Reply-To: replyto@example.com",
				"Content-type: text/html; charset=iso-8859-1",
				"X-Mailer: PHP/" . PHP_VERSION
			);
			$headers = implode("\r\n", $headers);
			
			
			
		//	print_r($reciver_email);
			if(count($reciver_email) >0)
			{
				
				$upateIds= array();
				for($i=0; $i <count($reciver_email); $i++){
					
							$userSenderId = time();
							$mysql_userRecord = $this->common_model->select_where('user_id','users',array('email'=>$reciver_email[$i]));
							if($mysql_userRecord->num_rows() >0){
								$mysql_adminRec_single = $mysql_userRecord->row_array();
								$userSenderId = $mysql_adminRec_single['user_id'];
							}
							$upateIds[] = $userSenderId;
							$html =   '<div class="col-lg-12" style="width:80%;border:1px solid #ccc;height:auto;height:260px">
							<div class="col-lg-2" style="background-color:#fdda1a;width:15%;float:left;height:260px;">
								<div style="margin-top: 40px;height: 75px;width: 65px;margin-left: 25px;border-top: 1px solid;border-left: 1px solid;border-right: 1px solid;border-left\: b;border-bottom: 1px solid;text-align:center;">
							   <div style="border-bottom:1px solid;width:100%;text-align:center;border-bottom:1px solid;margin-left: 12%;margin-top:33%;padding-top:0%;width: 76%;text-align:center;background-color:black;color:white;font-family:cursive;"><span>'.$stMon.'</span>
							  </div> 
							   <h3 style="text-align:center;margin-top: 0px;margin-bottom:26px;width:75%;height:40px;padding-top: 10px;margin-left:12%;background-color:white;font-family:sans-serif;">'.$dateStartTime.'</h3> </div>
							</div>
							<div class="col-lg-7" style="background-color:#FFF;width:55%;height:260px;float:left;border-right:1px solid #ccc">
								<div style="padding:15px;height:250px">
								<h1><b>Meeting</b></h1>
								
								<b style="color:#ccc">When</b>  &nbsp; &nbsp;'.$stDay.' '.$dateStartTime.','.$stYear.' '.$sss_dateTime.' - '.$eee_dateTime.' To &nbsp; '.$enDay.' '.$dateEndTime.','.$enYear.' '.$sss_dateTime.' - '.$eee_dateTime.'  (CST)<br />
								
								<b style="color:#ccc;">Who</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_name.'</span><br />
								<b style="color:#ccc;">Email</b> &nbsp; &nbsp; <span style="margin-left:5px;">'.$admin_email.'</span><br />
								<div style="margin-top:20px;">
							   
								<a href="'.site_url('schedule_email/add_schedule_item'.'/'.$event_id.'/'.$userSenderId).'"  style="background-color:#090;color:#FFF;padding:7px;width:90px;font-weight:800">Accept</a>
								<a href="'.site_url('schedule_email/reject_item'.'/'.$event_id.'/'.$userSenderId).'" style="padding:7px;width:90px;font-size:bold;font-weight:800;background-color:ghostwhite;">Reject</a>
								
								</div>
								</div>
							</div>
						
							<div class="col-lg-3" style="width:25%;float:left;height:250px;">
								 <div style="padding:15px;">
									<h1>Agenda</h1><br/><br />
									<p style="position:absolute;margin-top:-23px;color:#ccc;">'.$insert_array['note'].'</p>
								</div>
							</div>
					</div>';
					
					//echo $html;die;
					
					$s = @mail("'".$reciver_email[$i]."'", "Meeting Appointment", $html, $headers);
					if($s){
					//	echo "Sent."."'".$reciver_email[$i]."'";	
					}else{
					//	echo "Sorry Email cannot sent!.";	
					}
				}
				$ids = implode(",",$upateIds);
				$this->common_model->update_array(array('id'=>$event_id),'tbl_schedule',array('receiver_emails'=>$ids));
			}
			
				
		echo "1";

		 }

	  }
   
   
   	/*public function add_schedule_item($sch_id="",$user_id=""){
		$mysql_records =    $this->common_model->select_where('*','tbl_schedule',array('id'=>$sch_id));
		if($mysql_records->num_rows() >0){
			$mysqlUser = $this->common_model->select_where("*","users",array("user_id"=>$user_id));
			if($mysqlUser->num_rows() >0){
				$mysql_records = $mysql_records->row_array();
				$cartArray['item_id'] = $mysql_records['id'];
				$cartArray['item_type']= 'schedule';
				$cartArray['qty'] = 1;
				$cartArray['user_id'] = $user_id;
				$cartArray['ticket_id'] = 1;
				if($mysql_records['event_status'] == 1){
					$inser_id = $this->common_model->insert_array('tbl_cart',$cartArray);
					echo "Items is added to your cart!";
				}else{
					$sch_name = $mysql_records['start_date']."_".$mysql_records['sch_name'];
					$this->common_model->insert_array('tbl_schedule_cart',array('sch_name'=>$sch_name,"sch_id"=>$mysql_records['id']."_admin","sch_price"=>0,"sch_discount"=>0,"option_id"=>$mysql_records['option_id'],"user_id"=>$user_id,"lock_item"=>"free","status"=>"active"));
					
					echo "Your appointment has been  fixed!";
					//echo "The appointment is free we will directly show them into this appointment only the calender!";	
				}
			}else{
				
					$mysql_records = $mysql_records->row_array();
					$sch_name = $mysql_records['start_date']." ( ".$mysql_records['sch_name'] ." )";
					if($mysql_records['event_status'] == 1){
						$config['return'] 				= ''.site_url().'schedule/cart_paypal_success/'.$user_id;
						$config['cancel_return'] 		= ''.site_url().'schedule/cart_cancel_paypal/'.$user_id;
						$config['notify_url'] 			= ''.site_url().'schedule/cart_cancel_paypal/'.$user_id; //IPN Post
						$config['business']				=  $this->config->item('merchant_email');
						$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
						$config['production']			=  $this->config->item('production_mode');
						$config["invoice"]				= random_string('numeric',8); //The invoice id
						$this->load->library('paypal',$config);
						$this->paypal->add($sch_name,$mysql_records['event_price'],1); //First item
						$this->paypal->pay(); //Proccess the payment
					}else{
						echo "Your appointment has been  fixed!";
					}
			}
		}
		   
 	}
   */
	
	 
	}
	  
  
  
  
