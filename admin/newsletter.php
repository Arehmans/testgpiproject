<?php
 //error_reporting(0);
  class Newsletter extends CI_Controller
  {
	   function __construct() {

     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }

   }

	  function index()
	  {

            $data['newsletter'] = $this->common_model->select_all('*','tbl_newsletter');
$data['class'] = "settings";

$data['main_cls'] = 'settings';
$data['class'] = "newsletter_index";

		    $data['content'] = 'admin/newsletter';
		    $this->load->view('admin/layout/layout',$data);
	  }

      function deleteEmail($id){
        $this->common_model->delete_where(array('id'=>$id),'tbl_newsletter');
		$data['class'] = "settings";
		
			
		
        $this->session->set_flashdata('delete_newsletter','Email has been deleted sucessfully!');
        redirect(base_url()."admin/newsletter");
      }



  }


