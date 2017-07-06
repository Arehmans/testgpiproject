<?php
  class contracts extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
	  
	public  function add_contracts()
	
	  {
		  $this->load->library('form_validation');
	     /*  $this->form_validation->set_rules('category_name', 'Category Name', 'required');
		   if (empty($_FILES['category_image']['name']))
			{
    		$this->form_validation->set_rules('category_image', 'Category Image', 'required');
			}
		   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');*/
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  	 $data['content'] = 'admin/contracts_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		       /*if(isset($_POST['submit']))
			   {
				if($_FILES["category_image"]["name"]!="" and !empty($_FILES["category_image"]["name"]))
				{
				$filename=uniqid();
               // $othid=uniqid();
    			$path_info = pathinfo($_FILES["category_image"]["name"]);
    			$fileExtension = $path_info['extension'];

    			$config['upload_path'] = './assets/uploads/';
    			
    			$config['file_name'] = $filename.".".$fileExtension;
   				$config['allowed_types'] = 'jpg|png|jpeg';
				//$config['filesize'] = $filename.".".$fileExtension;
    			$this->load->library('upload', $config);
    			$this->upload->initialize($config);
    			$this->upload->do_upload('category_image');
				}
		  	    $data=array(
		    		'category_name'=>$this->input->post('category_name'),
					'category_image' =>$filename.".".$fileExtension,
		   		   // 'category_image'=>$this->input->post('category_image'),
		 
		  		);
		      $this->hopinhop_model->insert($data,'category');*/
		     // header("Location: ".base_url()."admin/dashboard/dashboard_view");
		     //}
		 }
	  }
	  
	  
	  function contracts_view()
	  {
		 
		    $data['content'] = 'admin/contracts_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  
	  function delete_contracts($id)
	  {
		  $table="category";
		  $primary="category_id";
	      $this->db->delete($table, array($primary => $id));
	      header("Location: ".base_url()."admin/catagories/catagory_view");
	  }
	  
	  function update_contracts($id)
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('category_name', 'Category Name', 'required');
		 /*  if (empty($_FILES['category_image']['name']))
			{
    		$this->form_validation->set_rules('category_image', 'Category Image', 'required');
			}*/
		   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				$data['content'] = 'admin/category_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		       if(isset($_POST['submit']))
			   {
				if($_FILES["category_image"]["name"]!="" and (!empty($_FILES["category_image"]["name"])))
				{
				$filename=uniqid();
               // $othid=uniqid();
    			$path_info = pathinfo($_FILES["category_image"]["name"]);
    			$fileExtension = $path_info['extension'];

    			$config['upload_path'] = './assets/uploads/';
    			
    			$config['file_name'] = $filename.".".$fileExtension;
   				$config['allowed_types'] = 'jpg|png|jpeg';
				//$config['filesize'] = $filename.".".$fileExtension;
    			$this->load->library('upload', $config);
    			$this->upload->initialize($config);
    			$this->upload->do_upload('category_image');
				
		  	        $data=array(
		    		'category_name'=>$this->input->post('category_name'),
					'category_image' =>$filename.".".$fileExtension,
		   		   // 'category_image'=>$this->input->post('category_image'),
		 
		  		);
			   $this->hopinhop_model->update($data,"category",$this->input->post('vid'),"category_id");
				}
			   else
			   {
				    $data=array(
		    		'category_name'=>$this->input->post('category_name'),
					//'category_image' =>$filename.".".$fileExtension,
				   );
				  $this->hopinhop_model->update($data,"category",$this->input->post('vid'),"category_id");  
			   }
		      header("Location: ".base_url()."admin/catagories/catagory_view");
		     }
	  	}
	 }
	  
  }
  
  
