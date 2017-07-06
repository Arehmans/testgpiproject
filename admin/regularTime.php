<?php
 class RegularTime extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
    	 redirect(base_url()."admin/login");
     }
        
   }
   
    function delete_multiple(){
		$multiple_delete = $this->input->post('delete_group');  
		$this->common_model->delete_where(array('multiple_delete'=>$multiple_delete),'tbl_real_time');
		$this->session->set_flashdata('scheduleMsg','Schedule  has been Successfully deleted...');
		redirect('admin/regularTime');
	   
	}
	   public function index(){
			$data['main_cls'] = 'schedule';
			$data['class'] = "open_time";
			$mysql_shedule = $this->db->query("SELECT * FROM tbl_real_time ORDER BY id ASC");
			//echo "<pre>";
			//print_r($mysql_shedule->result_array());
			$myarray = array();
			
			//print_r($mysql_shedule->result_array());
			if($mysql_shedule->num_rows() >0){
					foreach($mysql_shedule->result_array() as $myobj){
						$myobj['start_date_unix'];
						$start_date  = date("Y-m-d g:i a",strtotime("+1 day",$myobj['start_date_unix']));
						$end_date = date("Y-m-d g:i a",strtotime("+1 day",$myobj['end_date_unix']));
						$myarray[]	 = array("id"=>$myobj['id'],'date'=>$myobj['id'],'start_time'=>$myobj['start_time'],'end_time'=>$myobj['end_time'],'multiple_delete'=>$myobj['multiple_delete'],'option_type'=>$myobj['option_type'],'start_date_unix'=>date("Y-m-d",$myobj['start_date_unix']),'end_date_unix'=>date("Y-m-d",$myobj['end_date_unix']),'sch_id'=>$myobj['sch_id'],'type'=>$myobj['type'],'next_s_date'=>$start_date,'next_e_date'=>$end_date);
					}
			}
			//echo "<pre>";
			//print_r($myarray);
			//die;
			$data['mysql_realtime'] = $myarray;
			$data['content'] = 'admin/schedule/realtime_view';
			$this->load->view('admin/layout/layout',$data);
	   }
	   
	 
	   
	   function list_days($date_from,$date_to){
		   
			$arr_days = array();
			//o-m-d
			$arr_days[] = date('F j, Y',$date_from);
			$day_passed = ($date_to - $date_from); //seconds
			$day_passed = ($day_passed/86400); //days
		
			$counter = 1;
			$day_to_display = $date_from;
			while($counter < $day_passed){
				$day_to_display += 86400;
				$arr_days[] = date('F j, Y',$day_to_display); //o-m-d
				$counter++;
			}
			return $arr_days;
		}
		
		public function getTime(){
			  $id =  $this->input->post('id');
			  $mysql_event = $this->db->query("SELECT *  FROM tbl_real_time WHERE id = ".$id."");
		 	  $mysql_event =  $mysql_event->row_array();
			  echo json_encode($mysql_event);
			
		}
		
		public function delete_time(){
			
		   $start_date = $this->input->post('start_date');
		   $end_date = $this->input->post('end_date');
		   
		  // print_r($start_date);
		  // echo $end_date;die;
		   
		   $result_s = explode(" ", $start_date, 2);
		   $result_e = explode(" ", $end_date, 2);
		   $start_time = $result_s[1];
		   $end_time = $result_e[1];
		   
		    $date_from = strtotime($start_date);
			$date_to = strtotime($end_date);
			$dateArr = $this->list_days($date_from,$date_to);
			$dateArr[] = date("F j, Y",$date_to);
			$s = "";
			 for($i=0; $i <count($dateArr);$i++){
			//	 if(($start_time !="")&& ($end_time !="")){
					 //,'start_time'=>$start_time,'end_time'=>$end_time
			   		$s =  $this->common_model->delete_where(array('date'=>$dateArr[$i]),'tbl_real_time');
				 /*}else{
					 $s =  $this->common_model->delete_where(array('date'=>$dateArr[$i]),'tbl_real_time');
				 }*/
		    }
				$this->session->set_flashdata('scheduleMsg','Schedule  has deleted Successfully...');
		   		echo "1";
		   


			
		}
		
		 public function deleteTime(){
		   $schedule_id =  $this->input->post('schedule_id');
		   $this->common_model->delete_where(array('id'=>$schedule_id),'tbl_real_time');
		   $this->session->set_flashdata('scheduleMsg','Schedule  has been Successfully deleted...');
		   echo "1";
		   
	  }
		
		
			public function update_date(){
				
				$id =  $this->input->post('id');
				$insert_array['date'] =  $this->input->post('staring_date');
				$mysql_row = $this->common_model->select_where('start_time,end_time','tbl_real_time',array('id'=>$id));
				$mysql_row = $mysql_row->row_array();
				$start_time = $mysql_row['start_time'];
				$end_time = $mysql_row['end_time'];
				$insert_array['start_date_unix'] = strtotime($insert_array['date']." ".$start_time);
				$insert_array['end_date_unix'] = strtotime($insert_array['date']." ".$end_time);
				
				
				 $fDateTime = $insert_array['start_date_unix'];
			 	 $eDateTime = $insert_array['end_date_unix'];
			  
			  
			  	$mysqlAlldatesStart = $this->db->query("SELECT * FROM tbl_real_time WHERE ".$fDateTime." BETWEEN start_date_unix AND end_date_unix");
				$mysqlAlldatesEnd = $this->db->query("SELECT * FROM tbl_real_time WHERE ".$eDateTime." BETWEEN start_date_unix AND end_date_unix");
				
				if(($mysqlAlldatesStart->num_rows() >0) || ($mysqlAlldatesEnd->num_rows() >0)){
					$already = date("F j, Y",$fDateTime);
					echo "Schedule  already exits in this date( ".$already." )";
					$this->session->set_flashdata('scheduleError','Schedule  already exits in this date( '.$already.' )');
				}else{
					$this->common_model->update_array(array('id'=>$id),'tbl_real_time',$insert_array);
					$this->session->set_flashdata('scheduleMsg','Schedule  has been Updated Successfully!');
					echo 1;
				}
				
				
			}
	
	
	   
	   public function add_time(){
		 
		
		  $schedule_id =  $this->input->post('schedule_id');
		  if($schedule_id !=""){
		
			  $insert_array['start_time'] = $this->input->post('time1');
			  $insert_array['end_time']	  = $this->input->post('time2');
			  $date = $this->common_model->select_single_field("date",'tbl_real_time',array('id'=>$schedule_id));
			  
			  $fDateTime = strtotime($date." ".$insert_array['start_time']);
			  $eDateTime = strtotime($date." ".$insert_array['end_time']);
			  
			  $mysqlAlldatesStart = $this->db->query("SELECT * FROM tbl_real_time WHERE ".$fDateTime." BETWEEN start_date_unix AND end_date_unix AND id !=".$schedule_id."");
			  $mysqlAlldatesEnd = $this->db->query("SELECT * FROM tbl_real_time WHERE ".$eDateTime." BETWEEN start_date_unix AND end_date_unix AND id !=".$schedule_id."");
			  if(($mysqlAlldatesStart->num_rows() >0) || ($mysqlAlldatesEnd->num_rows() >0)){
					$already = date("F j, Y",$fDateTime);
					$this->session->set_flashdata('scheduleError','Schedule  already exits in this date( '.$already.' )');
			  }else{
					
					 $insert_array['start_date_unix'] = strtotime($date." ".$insert_array['start_time']);
					 $insert_array['end_date_unix'] = strtotime($date." ".$insert_array['end_time']);
					 $this->common_model->update_array(array('id'=>$schedule_id),'tbl_real_time',$insert_array);
					 $this->session->set_flashdata('scheduleMsg','Schedule  has updated Successfully...');
					
			  }
			  
			  echo "1";
			  
		  }else{
		   
		   $start_date = $this->input->post('start_date');
		   $end_date = $this->input->post('end_date');
		   $result_s = explode(" ", $start_date, 2);
		   $result_e = explode(" ", $end_date, 2);
		   $start_time = $result_s[1];
		   $end_time = $result_e[1];
		   $date_from = strtotime($start_date);
		   $date_to = strtotime($end_date);
		   $dateArr = $this->list_days($date_from,$date_to);
		   $s_date = strtotime($start_date);
		   $e_date = strtotime($end_date);
		   $st_day = date('d',$s_date);
		   $en_day = date('d',$e_date);
		   
		   $t =   time();
		   $existingDates = array();
		   for($i=0; $i <count($dateArr);$i++){
			   
			   $inseringArr = array();
			   $inseringArr['date'] = $dateArr[$i];
			   $inseringArr['start_time'] = $start_time;
			   $inseringArr['end_time']	  =  $end_time;
			   $inseringArr['multiple_delete'] = $t;
			   $fDateTime = strtotime($dateArr[$i]." ".$start_time);
			   $eDateTime = strtotime($dateArr[$i]." ".$end_time);
			   $inseringArr['start_date_unix'] = strtotime($dateArr[$i]." ".$start_time);
			   $inseringArr['end_date_unix'] = strtotime($dateArr[$i]." ".$end_time);
			   
			  // $mysqlExisting  = $this->db->query("SELECT * FROM tbl_real_time Where start_date_unix >= ".$fDateTime." AND end_date_unix <= ".$eDateTime." ");
			   
			   	$mysqlAlldatesStart = $this->db->query("SELECT * FROM tbl_real_time WHERE ".$fDateTime." BETWEEN start_date_unix AND end_date_unix");
				$mysqlAlldatesEnd = $this->db->query("SELECT * FROM tbl_real_time WHERE ".$eDateTime." BETWEEN start_date_unix AND end_date_unix");
					if(($mysqlAlldatesStart->num_rows() >0) || ($mysqlAlldatesEnd->num_rows() >0)){
						$existingDates[] = $dateArr[$i];
					}else{
						$this->common_model->insert_array('tbl_real_time',$inseringArr);
					}
		   }
		   
		   if(!empty($existingDates)){
			   $already = implode(",",$existingDates);
			   $this->session->set_flashdata('scheduleError','Schedule  already exits in following dates( '.$already.' )');
			}else{
		    	$this->session->set_flashdata('scheduleMsg','Schedule  has added Successfully...');
			}
		   
		   echo "1";
		  }
		}
	}
	  
  
  
  
