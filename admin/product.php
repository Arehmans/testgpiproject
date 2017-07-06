<?php

  class Product extends CI_Controller

  {

	   function __construct() {
     
     parent::__construct();
	 
	  ini_set('error_reporting', E_ALL);
	  ini_set('display_errors', 1);  //On or Off
	  
	  
	 $this->load->model('common_model');
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
	 
     }
	
        
   }
   
    function view_product(){

		 	$allProducts = $this->common_model->select_all('*','tbl_product');
			$data['allProducts'] = $allProducts;
			$data['content'] = 'admin/product/view_products';

			$this->load->view('admin/layout/layout',$data); 
	  }
   
   
   
   function upload_images(){
		$fileName = "";
		$upload_dir = './uploads/product_images/';
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
   
   function uplaod_multiple_images(){
	    $upload_dir = './uploads/product_images/multiple_images';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		$this->load->library('upload');
		$image_array = array();
		for($i=0;$i<count($_FILES['userfile']['name']);$i++){
			$image_array[] = time().$_FILES['userfile']['name'][$i];
		}
		$this->upload->initialize(array(
			"file_name"     => $image_array,
			"upload_path"=>$upload_dir,
			"allowed_types"=>"*"
		));
		
		if($this->upload->do_multi_upload("userfile")){
			return $image_array;
		}else{
			return "";
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
	
	

	public  function add_product(){
		
		
	
		  $this->load->library('form_validation');
		  $prdCat = $this->input->post('prd_category');
		  $prdType = $this->input->post('prd_type');
		  
		  if($prdCat == "single"){
			  	$this->form_validation->set_rules('prd_name', ' Product Name', 'required'); 

		  } else if($prdCat == "package"){
			  
			   $this->form_validation->set_rules('pkg_name', ' Package Name', 'required');
			  
		  }
		  
		  
		  if($prdType == "digital"){
				$this->form_validation->set_rules('level', 'Level', 'required');
				
		  }else if($prdType == "physical"){
				$this->form_validation->set_rules('file', 'Main Image', 'callback_image_upload');
		  }
		  
		  $this->form_validation->set_rules('prd_price', ' Product Price', 'required');
		  $this->form_validation->set_rules('prd_type', 'Product Type', 'required');
		  $this->form_validation->set_rules('prd_description', 'Product Description', 'required');
		  
		  $this->form_validation->set_rules('prd_category', 'Product Category', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
		  
			  	 $data['content'] = 'admin/product/add_products';
		   		 $this->load->view('admin/layout/layout',$data);
			}else
			{ 
			
	
				$Insert_array = array();
				
				
				 if($_POST['prd_category'] == "single"){
					 
					 $insert_array['prd_name'] = $this->input->post('prd_name');

				  } else if($_POST['prd_category'] == "package"){
						 $insert_array['prd_name'] = $this->input->post('pkg_name');
					  
				  }
		  				
				
				  if($_POST['prd_category'] == "single"){
					   $insert_array['prd_name'] = $this->input->post('prd_name');

				  } else if($_POST['prd_category'] == "package"){
					   $insert_array['prd_name'] = $this->input->post('prd_name');
					   
					  
				  }
				  
				    if($_POST['prd_type'] == "digital"){
						
						$insert_array['prd_level'] = json_encode($this->input->post('level'));
							
				    }else if($_POST['prd_type'] == "physical"){
					  
						if($_FILES['file'] !=""){
							$Image = $this->upload_images();
							$insert_array['prd_main_image'] =  $Image;
						}
					
						if($_FILES['userfile'] !=""){
							$multi_images = $this->uplaod_multiple_images();
							$insert_array['prd_multiple_images'] = json_encode($multi_images);
						}
				    }
				
			
				
				$insert_array['generate_code'] = $this->input->post('gen_code');
				$insert_array['prd_price'] = $this->input->post('prd_price');
				$insert_array['prd_type'] = $this->input->post('prd_type');
				$insert_array['prd_description'] = $this->input->post('prd_description');
				$insert_array['prd_category'] = $this->input->post('prd_category');
				
				$taxes_bit = 0;
				if(isset($_POST['chopping_cost'])){
					$taxes_bit = 1;
				}
				
				$insert_array['taxes_shipping_card'] = $taxes_bit;
				$insert_array['prd_category'] = $this->input->post('prd_category');
				$this->common_model->insert_array('tbl_product',$insert_array); 
				$this->session->set_flashdata('add_product','Product has been Successfully Inserted...');
				header("Location: ".base_url()."admin/product/view_product");

		 }

	  }

	  

	  public  function update_product($prd_id){

		  $this->load->library('form_validation');
		  $prdCat = $this->input->post('prd_category');
		  $prdType = $this->input->post('prd_type');
		  
		  if($prdCat == "single"){
			  	$this->form_validation->set_rules('prd_name', ' Product Name', 'required'); 

		  } else if($prdCat == "package"){
			   $this->form_validation->set_rules('pkg_name', ' Package Name', 'required');
		  }
		  
		  
		  if($prdType == "digital"){
				$this->form_validation->set_rules('level', 'Level', 'required');	
		  }
		  
		  $this->form_validation->set_rules('prd_price', ' Product Price', 'required');
		  $this->form_validation->set_rules('prd_type', 'Product Type', 'required');
		  $this->form_validation->set_rules('prd_description', 'Product Description', 'required');
		  
		  $this->form_validation->set_rules('prd_category', 'Product Category', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
			  
			  $data['prd_id'] = $prd_id;
			 $mysql_product = $this->common_model->select_where('*',' tbl_product',array('prd_id'=>$prd_id));
			 $data['mysql_product'] = $mysql_product->row_array();
			
			 $data['content'] = 'admin/product/edit_products';
			 $this->load->view('admin/layout/layout',$data);
			 
			}else
			{ 
		
		
			
				$Insert_array = array();
				
				 if($_POST['prd_category'] == "single"){
					 
					 $insert_array['prd_name'] = $this->input->post('prd_name');

				  } else if($_POST['prd_category'] == "package"){
						 $insert_array['prd_name'] = $this->input->post('pkg_name');
					  
				  }
		  				
				
				  if($_POST['prd_category'] == "single"){
					   $insert_array['prd_name'] = $this->input->post('prd_name');

				  } else if($_POST['prd_category'] == "package"){
					   $insert_array['prd_name'] = $this->input->post('prd_name');
					   
					  
				  }
				  
				    if($_POST['prd_type'] == "digital"){
						
						$insert_array['prd_level'] = json_encode($this->input->post('level'));
						
						$rowRec = $this->common_model->select_where('prd_main_image,prd_multiple_images','tbl_product',array('prd_id'=>$prd_id));
						$rowRec = $rowRec->row_array();
						$upload_dir = './uploads/product_images/';
						
						
						$upload_multi_dir = './uploads/product_images/multiple_images/';
						
						$upload_dir.= $rowRec['prd_main_image'];
						
						 if (file_exists($upload_dir)) {
							unlink($upload_dir);
							$insert_array['prd_main_image'] = "";
							
						 }
						 
						 
						 $images =  json_decode($rowRec['prd_multiple_images']);
						
						if(!empty($images)){ 
							foreach($images as $Image){
								
								 if (file_exists($upload_multi_dir.$Image)) {
										@unlink($upload_multi_dir.$Image);
										$insert_array['prd_multiple_images'] = "";
								 }
							}
						}
		
				    }else if($_POST['prd_type'] == "physical"){
						$insert_array['prd_level'] =  "";
						  if (!empty($_FILES['file']['name'])) {
							$Image = $this->upload_images();
							$insert_array['prd_main_image'] =  $Image;
						}
					
						if($_FILES['userfile']['size'][0] !=0){
							$multi_images = $this->uplaod_multiple_images();
							$insert_array['prd_multiple_images'] = json_encode($multi_images);
						}
				    }
				
			
				
				$insert_array['generate_code'] = $this->input->post('gen_code');
				$insert_array['prd_price'] = $this->input->post('prd_price');
				$insert_array['prd_type'] = $this->input->post('prd_type');
				$insert_array['prd_description'] = $this->input->post('prd_description');
				$insert_array['prd_category'] = $this->input->post('prd_category');
				
				$taxes_bit = 0;
				if(isset($_POST['chopping_cost'])){
					$taxes_bit = 1;
				}
				
				$insert_array['taxes_shipping_card'] = $taxes_bit;
				$insert_array['prd_category'] = $this->input->post('prd_category');
			
				$this->common_model->update_array(array('prd_id'=>$prd_id),'tbl_product',$insert_array);
				
				
				$this->session->set_flashdata('add_product','Product has been updated successfully!...');
				header("Location: ".base_url()."admin/product/view_product");

		 }

	  }

	  

	  
	  


	}
  

  

  
