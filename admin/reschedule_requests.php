<?php
 class Reschedule_requests extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
    	 redirect(base_url()."admin/login");
     }
        
   }
   
	   public function index(){
		   
			$data['class'] = "reschedule_requests";
			$data['inactive_notifications'] = $this->common_model->select_where('*','tbl_schedule_cart',array('status'=>'inactive'));
			$data['content'] = 'admin/reschedule_requests/requests';
			$this->load->view('admin/layout/layout',$data);
	   }
	   
	    public function accept_notification(){
	   $item_id  =  $this->input->post('item_id');
	   $status   =  $this->input->post('status');
	   
	   if($status == "accept"){
		  $single_detail =   $this->common_model->select_where('*','tbl_schedule_cart',array('id'=>$item_id));
		  
		//  print_r($single_detail->row_array());die;
		  $single_detail =   $single_detail->row_array();
		  $item_name = explode("_",$single_detail['updated_sch_name']);
		  $itemName = $item_name[1]." ( ".$item_name[0]." ) ";
		  $item_name_old = explode("_",$single_detail['sch_name']);
		  $itemName_old = $item_name_old[1]." ( ".$item_name_old[0]." ) ";
		  $emailUser = $this->common_model->select_single_field('email','users',array('user_id'=>$single_detail['user_id']));
	      $itemPrice = $single_detail['updated_sch_price'];
		  if($single_detail['updated_sch_price'] == 0){
				$itemPrice = 	$single_detail['sch_price'];
		  }	
						
			$old_timestamp =  strtotime($item_name_old[0]);
			$mysql_numRow = $this->common_model->select_where("*",'tbl_reminder',array('sch_date'=>$old_timestamp));
			if($mysql_numRow->num_rows() >0){
			  	 $mysql_batch_recurring = array('item_name'=>$itemName,'sch_date'=>strtotime($item_name[0]),'user_id'=>$single_detail['user_id'],'sch_id'=>$single_detail['updated_sch_id'],'email'=>$emailUser,'item_price'=>$itemPrice,'remember_time'=>'1day,4hours');
		   		 $this->common_model->update_array(array('sch_date'=>$old_timestamp),'tbl_reminder',$mysql_batch_recurring);
			}
		    
		
		  if($single_detail['updated_sch_price'] <=0){
			  $udateArray['sch_name'] = $single_detail['updated_sch_name'];
			  $udateArray['sch_id'] = $single_detail['updated_sch_id'];
			  $udateArray['option_id'] = $single_detail['updated_option_id'];
			  $udateArray['sch_notes'] = $single_detail['update_sch_notes'];
			  
			  $udateArray['date_varify_start'] = $single_detail['update_varify_start_date'];
			  $udateArray['date_varify_end'] = $single_detail['update_varify_end_date'];
			  
			  $udateArray['updated_sch_name'] = "";
			  $udateArray['update_sch_notes'] = "";
			  $udateArray['updated_sch_id'] = "";
			  $udateArray['updated_option_id'] = "";
			  $udateArray['updated_sch_price'] = "";
			  $udateArray['update_varify_start_date'] = "";
			  $udateArray['update_varify_end_date']   = "";
			  
			  $udateArray['status'] = "active";
			  $udateArray['lock_item'] = "free";
			  $this->common_model->update_array(array('id'=>$item_id),'tbl_schedule_cart',$udateArray);
		  }else{
			  $this->common_model->update_array(array('id'=>$item_id),'tbl_schedule_cart',array('status'=>'active'));
		  }
			$this->session->set_flashdata('scheduleMsg','Meeting Request Schedule has been Accepted.');
	  	 	echo "1";
			
			      
		}else{
			$udateRec['updated_sch_id']= "";
			$udateRec['update_sch_notes']= "";
			$udateRec['updated_option_id']= "";
			$udateRec['updated_sch_price']= "";
			$udateRec['updated_sch_name']= "";
			$udateRec['status']= "active";
			$udateRec['lock_item']= "free";
			$udateRec['update_varify_start_date'] = "";
			$udateRec['update_varify_end_date']   = "";
			$this->common_model->update_array(array('id'=>$item_id),'tbl_schedule_cart',$udateRec);
			$this->session->set_flashdata('scheduleMsg','Meeting Request Schedule has been Rejected!');
	  	 	echo "1";
		}
		
		
	}
   
 }
	  
  
  
  
