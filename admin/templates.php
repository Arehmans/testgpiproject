<?php
 error_reporting(0);
 class templates extends CI_Controller
  {
	  
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
   //   Templates Listing ///////////////////////////////////////////////////////////		  
		function index(){
				$this->load->library('pagination');
				 $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',4);
                 $per_page =$pages->pages;
				$qry = "select * from `email_template` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/email_template/index'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				$data['class'] = "settings";
				
				$data['main_cls'] = 'settings';
		 		 $data['class'] = "template_index";
				
		        $data['content'] = 'admin/email_template/index';
		        $this->load->view('admin/layout/layout',$data);
	  		}
	
	
	  
	  	 
		function update($id){  
	       	$this->load->library('form_validation');
	       	$this->form_validation->set_rules('email_subject', 'Email Subject', 'required');
		  //  $this->form_validation->set_rules('email_content', 'Email Content', 'required');
			
			$this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');  
		   
		   	if ($this->form_validation->run() == FALSE){
				$data['main_cls'] = 'settings';
		 		 $data['class'] = "template_update";
				 
				 
		   		$q=$this->gpi_model->get_by_where('email_template',array('id'=>$id));				
		   		$admins=$this->gpi_model->get_by_where('users',array('expert'=>1));
		   		if(count($admins) == 1)
		   			$admins = array($admins);
		   		if($q){
			  		$data['q'] = $q;
					$data['content'] = 'admin/email_template/edit';
					$data['admins'] = $admins;
					$this->load->view('admin/layout/layout',$data);
				}else{
					redirect(site_url('admin/404'));
				}
			}else{ 
				$admin_recipients = '';
				$if_admin_recipients = $this->input->post('admin_recipient');
				
				if($if_admin_recipients == 1 && $this->input->post('admins')){
					if(count($this->input->post('admins')))
						$admin_recipients = serialize($this->input->post('admins'));
					
				}
				
				$customer_email =0;
				
				if(isset($_POST['customer_recipient'])){
					
					$customer_email = 1;
				}
				
			    $data=array(
		    			'email_subject'=>$this->input->post('email_subject'),
						'admin_recipients'=>$admin_recipients,
						'cutomer_email'=> $customer_email,
						'modified_date'=> date('Y-m-d h:i:s'),
				);
				
				if($this->input->post('email_content') == ""){
					$data['email_content'] = "";
					
				}else{
					$data['email_content'] = $this->input->post('email_content');
				}
				
				if($this->input->post('before_email') !=""){
					$data['before_content'] = $this->input->post('before_email');
					
				}
				if($this->input->post('after_email') !=""){
					$data['after_content'] = $this->input->post('after_email');
				}
				
				
				
	       		$this->gpi_model->update($data,"email_template",$this->input->post('id'),"id");
				$this->session->set_flashdata('update_msg','Ticket Successfully Updated...');
	      		redirect(site_url('admin/email-templates'));
				
		     }
	  	}
		
		
       
	  
}