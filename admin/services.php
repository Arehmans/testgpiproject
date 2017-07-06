<?php

  class Services extends CI_Controller

  {

	   function __construct() {
     
     parent::__construct();
	 
	  ini_set('error_reporting', E_ALL);
	  ini_set('display_errors', 1);  //On or Off


	 $this->load->model('common_model');
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     date_default_timezone_set('America/New_York');
	 
     }
	

   }


   function delete_service($service_id){

         $this->common_model->delete_where(array('id'=>$service_id),'tbl_service');

         $this->session->set_flashdata('eventMsg','Service has been Successfully deleted.');
    	 header("Location: ".base_url()."admin/services/view_service");

   }
   
    function view_service(){
		

		 //	$allServices = $this->common_model->select_all('*','tbl_service');
			$allServices = $this->db->query("SELECT * FROM tbl_service ORDER BY service_order ASC");
			$data['allServices'] = $allServices;
			$data['main_cls'] = 'schedule';
			$data['class'] = "view_appointment";
			$data['content'] = 'admin/services/view_service';
			$this->load->view('admin/layout/layout',$data); 
	  }
   
   
   
  

	public  function add_service(){

		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('ser_name', 'Service Name', 'required');
		  $this->form_validation->set_rules('ser_duration', 'Service Duration', 'required');
          $this->form_validation->set_rules('ser_price', 'Service Price', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
				 $data['main_cls'] = 'schedule';
				 $data['class'] = "add_appointment";
			  	 $data['content'] = 'admin/services/add_service';
		   		 $this->load->view('admin/layout/layout',$data);
			}else
			{
                 $insert_array = array();
                
				$insert_array['ser_name'] = $this->input->post('ser_name');
				$insert_array['ser_duration'] = $this->input->post('ser_duration');
				$insert_array['ser_price'] = $this->input->post('ser_price');
				$insert_array['access'] = 0;
				if(isset($_POST['access'])){
					$insert_array['access'] = $_POST['access'];	
				}
			   $this->common_model->insert_array('tbl_service',$insert_array);
				$this->session->set_flashdata('eventMsg','Service has been Successfully Inserted...');
				header("Location: ".base_url()."admin/services/view_service");

		 }

	  }

     

	public  function update_service($service_id){

		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('ser_name', 'Service Name', 'required');
		  $this->form_validation->set_rules('ser_duration', 'Service Duration', 'required');
          $this->form_validation->set_rules('ser_price', 'Service Price', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
			  
			  $data['service_id'] = $service_id;
			  $mysql_service = $this->common_model->select_where('*',' tbl_service',array('id'=>$service_id));
			  $data['mysql_service'] = $mysql_service->row_array();
			  $data['main_cls'] = 'schedule';
			  $data['class'] = "update_appointment";
			  $data['content'] = 'admin/services/update_service';
			  $this->load->view('admin/layout/layout',$data);
			}else
			{
                $insert_array = array();
				$insert_array['ser_name'] = $this->input->post('ser_name');
				$insert_array['ser_duration'] = $this->input->post('ser_duration');
				$insert_array['ser_price'] = $this->input->post('ser_price');
				$insert_array['access'] = 0;
				$service_id = $this->input->post('service_id');
				if(isset($_POST['access'])){
					$insert_array['access'] = $_POST['access'];	
				}
			   
			    $this->common_model->update_array(array('id'=>$service_id),'tbl_service',$insert_array);	
				$this->session->set_flashdata('eventMsg','Service has been Successfully Updated...');
				header("Location: ".base_url()."admin/services/view_service");

		 }

	  }


function set_order(){
		
		$classes_ids = $_POST['data'];
		$classes_ids = substr(trim($classes_ids), 0, -1); 
		$idsArray    = explode(",",$classes_ids);
		$updateArray = array();
		$j=1;
		for($i=0; $i <count($idsArray);$i++){
			
			$updateArray[] = array(
				'id'=>$idsArray[$i],
				'service_order' => $j
				);
			$j++;
		}
		
		$this->db->update_batch('tbl_service',$updateArray, 'id'); 
		echo $this->db->last_query();
	}


	  



	}


  

  
