<?php
  class Products extends CI_Controller
  { 

	    function __construct() {
    
		   parent::__construct();

		  ini_set('error_reporting', E_ALL);
		  ini_set('display_errors', 'On');  //On or Off
		  if($this->session->userdata("gpi_id") == "") {
			 $this->load->model('common_model');
		  }


	   }
	   
	   
	   public function schedule(){

		 $chap =  $this->input->post('chap');
		 $start =  $this->input->post('start');
		 $end =  $this->input->post('end');
		 $option_id =  $this->input->post('option_id');
		 $data['chap_1'] =  $chap;
		 $data['start_attr'] =  $start;
		 $data['end_attr'] =  $end;
		 $data['option_id'] = $option_id;
		 
		 $html =  $this->load->view('user/ajax_shedule_view',$data);
		 echo $html;
		 		
	  }
	  
	     public function update_temprary_cartItems(){
		 
		   $new_item_id =  $this->input->post('new_item_id');
		   $new_item_price = $this->input->post('new_price');
		   $new_itemsArr = $this->input->post('itemsArr');
		   $new_dropdownValue = $this->input->post('new_dropdown_value');
		   $new_dropdownID = $this->input->post('new_option_id');
		   $old_item_id = $this->input->post('old_item_id');
			$old_item_price = 0;
		 	
			$mysql_itemTemp = $this->common_model->select_where('*','tbl_temp_cart_items',array('item_id'=>$new_item_id ));
			if($mysql_itemTemp->num_rows() >0){
				echo  "Appointment Already exists.";	
			}
					$mysql_item = $this->common_model->select_where('sch_price','tbl_schedule_cart',array('sch_id'=>$old_item_id));
			
			
		 	if($mysql_item->num_rows() >0){
			
				$mysql_item = $mysql_item->row_array();
				$old_item_price = $mysql_item['sch_price'];
					
			}
		  
		   $payAmount = 0;
		   $insertSchedule['lock_item'] = 'lock';
		   if($new_item_price > $old_item_price){
				$payAmount = $new_item_price - $old_item_price;  
			}

			
			$insertSchedule['updated_sch_name']   = $new_itemsArr."_".$new_dropdownValue;
			$insertSchedule['updated_sch_id']	  = $new_item_id;
			$insertSchedule['updated_sch_price']  = $payAmount;
			$insertSchedule['user_id']    = $this->session->userdata('gpi_id');
			$insertSchedule['updated_option_id']  = $new_dropdownID;
			$insertSchedule['lock_item']  = 'lock';
			$insertSchedule['status']  	  = 'inactive';
			$this->common_model->update_array(array('sch_id'=>$old_item_id),'tbl_schedule_cart',$insertSchedule);
			$this->session->set_flashdata('promo_msg1','Appointment has been added for confirmation.');
			echo "1";
		
			
	  }
	  
	  
        public function index(){

            $user_id = $this->session->userdata('gpi_id');
            $mysqlPackages = $this->common_model->join_tables_009( '*', 'tbl_package_mail', 'tbl_package', 'tbl_package_mail.item_id = tbl_package.pkg_id',array('tbl_package_mail.user_id'=>$user_id), 'tbl_package_mail.item_id' , 'DESC' );
            $mysqlResources =  $this->common_model->join_tables_009( '*', 'membership_access', 'resources', 'membership_access.item_id = resources.resources_id',array('lock_item'=>'free','user_id'=>$user_id,'item_type'=>'res'), 'membership_access.item_id' , 'DESC' );
			$mysql_schedule = $this->common_model->select_where('*','tbl_schedule_cart','user_id = '.$user_id.' group by sch_id');
			$mysql_realTime = $this->db->query("SELECT * FROM tbl_real_time");
			$data['mysql_realtime'] = $mysql_realTime;
			$mysql_services = $this->common_model->select_all('*','tbl_service');
		    $data['mysql_services'] = $mysql_services;
			$mysql_adminItems = $this->common_model->select_where("*","tbl_cart",array("user_id"=>$user_id,"item_type"=>"schedule","ticket_id"=>1));
			$data['mysql_admin_items'] = $mysql_adminItems;
			$data['mysql_schedule']    = $mysql_schedule;
            $data['mysqlPackages']    = $mysqlPackages;
            $data['mysqlResources']   = $mysqlResources;
            $data['content']          = 'product_view';
		    $this->load->view('layout/layoutuser',$data);

        }
		
		
		public function add_cart(){
				
				$item_id = $this->input->post('item_id');
				$user_id = $this->session->userdata('gpi_id');
				$insertSchedule['item_id']     = $item_id;
				$insertSchedule['item_type']   = 'schedule';
				$insertSchedule['qty']    = 1;
				$insertSchedule['user_id']  = $user_id;
				$this->common_model->insert_array("tbl_cart",$insertSchedule);
				$this->session->set_flashdata('promo_msg1','Schedule has been added to cart.');
				echo "1";
				
			
		}

  }