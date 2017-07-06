<?php
 class Sales extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
   
   public function index(){
   
		$sales =  $this->common_model->select_all('*','tbl_order');
		$data['sales'] = $sales;
		$data['class'] = 'membership_sales';
		$data['content'] = 'admin/sales/sales';
	    $this->load->view('admin/layout/layout',$data);
		
   }
	
	 
	}
	  
  
  
  
