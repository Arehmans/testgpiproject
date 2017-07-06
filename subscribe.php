<?php
 class subscribe extends CI_Controller
  {
	  
	 function __construct() {
     
       parent::__construct();
       if($this->session->userdata("gpi_id") == "") {
        redirect(base_url()."login");
     }
        
   }
	  function subscribe_user()
	  {
		    $data['content'] = 'subscribe_view';
		    $this->load->view('layout/layout',$data);
			
			
			   if($this->input->post('subscribe')!="")
				 {		  
					$data2=array('subscribe'=>1);
					$this->gpi_model->update($data2,'users',$this->session->userdata('gpi_id'),"user_id");		  
					
					 redirect(base_url().'subscribe/subscribe_user');

				 }
				  if($this->input->post('unsubscribe')!="")
				 {		  
				    $data2=array('subscribe'=>0);
					$this->gpi_model->update($data2,'users',$this->session->userdata('gpi_id'),"user_id");		  
					 redirect(base_url().'subscribe/subscribe_user');

	  
				 }
	  
	  }
      
  }

	  

  

  

  

