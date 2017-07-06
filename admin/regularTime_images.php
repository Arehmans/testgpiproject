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
			$data['content'] = 'admin/schedule/realtime_view';
			$mysql_shedule = $this->common_model->select_all('*','tbl_real_time');
			$data['mysql_realtime'] = $mysql_shedule;
			$this->load->view('admin/layout/layout',$data);
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
		
		public function getTime(){
			  $id =  $this->input->post('id');
			  $mysql_event = $this->db->query("SELECT *  FROM tbl_real_time WHERE id = ".$id."");
		 	  $mysql_event =  $mysql_event->row_array();
			  echo json_encode($mysql_event);
			
		}
		
		public function delete_time(){
			
		   $start_date = $this->input->post('start_date');
		   $end_date = $this->input->post('end_date');
		   $result_s = explode(" ", $start_date, 2);
		   $result_e = explode(" ", $end_date, 2);
		   $start_time = $result_s[1];
		   $end_time = $result_e[1];
		   
		    $date_from = strtotime($start_date);
			$date_to = strtotime($end_date);
			$dateArr = $this->list_days($date_from,$date_to);
			
			
			 for($i=0; $i <count($dateArr);$i++){
			    $this->common_model->delete_where(array('date'=>$dateArr[$i],'start_time'=>$start_time,'end_time'=>$end_time),'tbl_real_time');
		   }
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
			//	$insert_array['end_date']   =  $this->input->post('ending_date');
				$this->common_model->update_array(array('id'=>$id),'tbl_real_time',$insert_array);
				
			}
	
	
	   
	   public function add_time(){
		   
		  $schedule_id =  $this->input->post('schedule_id');
		  if($schedule_id !=""){
			
			  $insert_array['start_time'] = $this->input->post('time1');
			  $insert_array['end_time']	  = $this->input->post('time2');
			  
			  $this->common_model->update_array(array('id'=>$schedule_id),'tbl_real_time',$insert_array);
			  $this->session->set_flashdata('scheduleMsg','Schedule  has updated Successfully...');
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
		   for($i=0; $i <count($dateArr);$i++){
			   
			   $inseringArr = array();
			   $inseringArr['date'] = $dateArr[$i];
			   $inseringArr['start_time'] = $start_time;
			   $inseringArr['end_time']	  =  $end_time;
			   $inseringArr['multiple_delete'] = $t;
			   $this->common_model->insert_array('tbl_real_time',$inseringArr);
			   
			   
			  
		   }
		   
		   echo "1";
		  }
		   
		   
		 
		   
		}
   
	}
	  
  
  
  
