<?php

  class Products extends CI_Controller

  {

	   function __construct() {
     
     parent::__construct();
	 
	  ini_set('error_reporting', E_ALL);
	  ini_set('display_errors', 1);  //On or Off
	  
	  $this->load->library('form_validation');
	 $this->load->model('common_model');
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
	 
     }
	
        
   }
   
    function view_products(){

		 	$allProducts = $this->db->query("SELECT * FROM tbl_products INNER JOIN tbl_products_level ON tbl_products.prd_id = tbl_products_level.prd_id GROUP BY tbl_products_level.prd_id");
			$data['allProducts'] = $allProducts;
			$data['content'] = 'admin/products/view_products';

			$this->load->view('admin/layout/layout',$data); 
	  }
   
   
   
   function upload_images(){
		$fileName = "";
		$upload_dir = './assets/uploads/package_images/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		$fileName = time().$_FILES['file']['name'];
        $config['upload_path']   = $upload_dir;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['file_name']     = $fileName;
        $config['overwrite']     = false;
        $config['max_size']  = '5120';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')){
            $this->form_validation->set_message('image_upload', $this->upload->display_errors());
            return "";
        }  
        else{
            $this->upload_data['file'] =  $this->upload->data();
            return $fileName;
        }   
   
   }
   
   
   
	function image_upload(){
		
	  if (empty($_FILES['file']['name'])) {
			$this->form_validation->set_message('image_upload', "No file selected");
			return false;
	  }else{
			return true;
	  }
	}
	
	

	public  function add_products(){
		  
		//  $this->form_validation->set_rules('prd_category', 'Product Category', 'required');
		  $this->form_validation->set_rules('prd_name', ' Product Name', 'required'); 
		  $this->form_validation->set_rules('file', 'Image', 'callback_image_upload');
		  $this->form_validation->set_rules('prd_price', ' Product Price', 'required');
		  $this->form_validation->set_rules('prd_description', 'Product Description', 'required');
		  $this->form_validation->set_rules('level', 'Level', 'required');	
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
			  	 $data['content'] = 'admin/products/add_products';
		   		 $this->load->view('admin/layout/layout',$data);
		 }else{ 
			
				$Insert_array = array();
				$insert_array['prd_name'] = $this->input->post('prd_name');     
				if($_FILES['file']['name'] !=""){
					$Image = $this->upload_images();
					$insert_array['prd_image'] =  $Image;
				}
				$insert_array['prd_price'] = $this->input->post('prd_price');
				$insert_array['prd_description'] = $this->input->post('prd_description');
				//$insert_array['prd_category'] = $this->input->post('prd_category');
				$insert_array['show_at_home'] = 0;
				if(isset($_POST['show_home'])){
					$insert_array['show_at_home']	  = 1;
				}
				$prd_id = $this->common_model->insert_array('tbl_products',$insert_array);
				
				$levels = $this->input->post('level');
				
				$myArray = array();
				for($i=0; $i< count($levels); $i++){
					$myArray[] = array('prd_id'=>$prd_id,'level_id'=>$levels[$i]);
				}
				
				
				$this->db->insert_batch('tbl_products_level',$myArray);
				 
				
				//tbl_products_level
				
				$this->session->set_flashdata('add_product','Product has been added Successfully!');
				header("Location: ".base_url()."admin/products/view_products");

		 }

	  }

	  
	  
	  public function delete_products($prd_id){
		  $this->common_model->delete_where(array('prd_id'=>$prd_id),'tbl_products');
		  $this->common_model->delete_where(array('prd_id'=>$prd_id),'tbl_products_level');
		  $this->session->set_flashdata('add_product','Product has been deleted Successfully!');
		  header("Location: ".base_url()."admin/products/view_products");

		  
	  }
	  

	  public  function update_product($prd_id){

		  $this->load->library('form_validation');
		//  $this->form_validation->set_rules('prd_category', 'Product Category', 'required');
		  $this->form_validation->set_rules('prd_name', ' Product Name', 'required'); 
		  $this->form_validation->set_rules('level', 'Level', 'required');	
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
			  
			  $data['prd_id'] = $prd_id;
			
			 $mysql_product = $this->common_model->select_where('*','tbl_products',array('prd_id'=> $prd_id));
			 $data['mysql_product'] = $mysql_product->row_array();
			
			 $data['content'] = 'admin/products/edit_products';
			 $this->load->view('admin/layout/layout',$data);
			 
			}else{
				
				$Insert_array = array();
				$insert_array['prd_name'] = $this->input->post('prd_name');     
				if($_FILES['file']['name'] !=""){
					$Image = $this->upload_images();
					$insert_array['prd_image'] =  $Image;
				}
				
				$insert_array['show_at_home'] = 0;
				if(isset($_POST['show_home'])){
					$insert_array['show_at_home']	  = 1;
				}
				
				$insert_array['prd_price'] = $this->input->post('prd_price');
				$insert_array['prd_description'] = $this->input->post('prd_description');
			//	$insert_array['prd_category'] = $this->input->post('prd_category');
				$this->common_model->update_array(array('prd_id'=>$prd_id),'tbl_products',$insert_array);
				$this->common_model->delete_where(array('prd_id'=>$prd_id),'tbl_products_level');
				$levels = $this->input->post('level');
				$myArray = array();
				for($i=0; $i< count($levels); $i++){
					$myArray[] = array('prd_id'=>$prd_id,'level_id'=>$levels[$i]);
				}	
				$this->db->insert_batch('tbl_products_level',$myArray);
				$this->session->set_flashdata('add_product','Product has been updated Successfully!');
				header("Location: ".base_url()."admin/products/view_products");
}

	  }
	  
	  
	  

	  
	  


	}
  

  

  
