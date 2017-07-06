<?php
 //error_reporting(0);
  class resources extends CI_Controller
  {
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }


	public function dropable_update(){
		
		
		$draggableId = $this->input->post('draggableId');	
		$dropableId = $this->input->post('dropableId');	
		//echo $draggableId."___".$dropableId;
		$this->common_model->update_array(array('resources_id'=>$draggableId),'resources',array('folder_id'=>$dropableId));
		
		echo "1";
		
		//resources //folder_id
	}
	
	
	public function dropable_sub_folder(){
		$draggableId = $this->input->post('draggableId');	
		$droppableId = $this->input->post('droppableId');
		$this->common_model->update_array(array('folder_id'=>$draggableId),'resources',array('folder_id'=>$droppableId));
		$this->common_model->delete_where(array('resource_folder_id'=>$draggableId),'resource_folder');
		$this->common_model->delete_where(array('resource_folder_id'=>$draggableId),'resource_folder_level');
		echo "1";
			
	}
	
	function dropable_main_folder(){
		
		$draggableId = $this->input->post('draggableId');	
		$droppableId = $this->input->post('droppableId');
		$parent_idRow =$this->common_model->select_where("resource_folder_id","resource_folder",array("parent_id"=>$draggableId));
		
		$mysql_dropableArray = $this->common_model->select_where("*","resource_folder",array("resource_folder_id"=>$droppableId));
		$mysql_dropableArray = $mysql_dropableArray->row_array();
		
		$mysql_dropable_level_Array = $this->common_model->select_where("*","resource_folder_level",array("resource_folder_id"=>$droppableId));
		$mysql_dropable_level_Array1 = array();
		if($mysql_dropable_level_Array->num_rows() >0){
			$mysql_dropable_level_Array1 = $mysql_dropable_level_Array->result_array();
		}
		$level_array = array();

		if($parent_idRow->num_rows() >0){
				$this->common_model->update_array(array('parent_id'=>$draggableId),'resource_folder',array('parent_id'=>$droppableId,'type'=>'sub','level_id'=>$mysql_dropableArray['level_id'],'public_folder'=>$mysql_dropableArray['public_folder'],'user_id'=>$mysql_dropableArray['user_id'],'unlock_folders'=>$mysql_dropableArray['unlock_folders']));
				
			$mysqlTotal=$this->common_model->select_where("*","resource_folder",array("parent_id"=>$droppableId));
			if($mysqlTotal->num_rows() >0){
				foreach($mysqlTotal->result_array() as $totalObj){
				$this->common_model->delete_where(array('resource_folder_id'=>$totalObj['resource_folder_id']),'resource_folder_level');
					if(!empty($mysql_dropable_level_Array1)){
						foreach($mysql_dropable_level_Array1 as $each_level){
							$level_array[] = array('resource_folder_id'=>$totalObj['resource_folder_id'],'level_id'=>$each_level['level_id']);
						}
						$this->db->insert_batch('resource_folder_level', $level_array); 	
					}
				}	
			}				
		}
		$this->common_model->update_array(array('resource_folder_id'=>$draggableId),'resource_folder',array('parent_id'=>$droppableId,'type'=>'sub','level_id'=>$mysql_dropableArray['level_id'],'public_folder'=>$mysql_dropableArray['public_folder'],'user_id'=>$mysql_dropableArray['user_id'],'unlock_folders'=>$mysql_dropableArray['unlock_folders']));
		
		$mysqlTotal=$this->common_model->select_where("*","resource_folder",array("parent_id"=>$droppableId));
		
			if($mysqlTotal->num_rows() >0){
				foreach($mysqlTotal->result_array() as $totalObj){
				$this->common_model->delete_where(array('resource_folder_id'=>$totalObj['resource_folder_id']),'resource_folder_level');
				if(!empty($mysql_dropable_level_Array1)){
					foreach($mysql_dropable_level_Array1 as $each_level){
						$level_array[] = array('resource_folder_id'=>$totalObj['resource_folder_id'],'level_id'=>$each_level['level_id']);
					}
					$this->db->insert_batch('resource_folder_level', $level_array); 
				}
				  }
			}
		echo "1";
	}


   
	public  function add_resources($fid,$sub_folder="")
	
	  {
		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('description', 'Description', 'required');
		  
		  if(!isset($_POST['chkpublic'])){
			   $this->form_validation->set_rules('res_price','Price','required');
		   }
		   
		  if (empty($_FILES['resources']['name']))
   			{
     		 $this->form_validation->set_rules('resources', 'Resources', 'required');
   			}
		   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
				 
				$data['main_cls'] = 'digital_media';
				$data['class'] = "resources_view";
			
				//$data['main_cls'] = 'settings';
				//$data['class'] = "add_resources_setting";
				$data['res_id'] = $fid; 
				$data['sub_folder_id'] = $sub_folder;
			
			  	 $data['content'] = 'admin/resources_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		       $config['upload_path'] = './assets/user/file_upload';
		     //  $config['allowed_types'] = 'docx|pdf|jpg|png|jpeg|xml|pages|xls|doc|pptx|ppt|PDF|webm|mkv|flv|vob|ogg|ojb|gif|avi|mov|wmv|rm|rmvb|asf|mp4|m4v|m4p|mpeg|3gp|3g2|mxf|nsv|zip|rar|xlsx';
               $config['allowed_types'] = '*';
			   $config['max_size']	= '100000';

			   $this->load->library('upload', $config);
			   if ( ! $this->upload->do_upload('resources'))
				{
				//$this->session->set_flashdata('error', $this->upload->display_errors());
				redirect(base_url()."admin/resources/add_resources/".$fid);
				
				} else {
					$resources = $this->upload->data();
				}
				
				 
				 $uploadfile=$resources['file_name'];
				 
				 $file_explode=explode(".", $uploadfile);
				  
				 $file_compare=strtolower($file_explode[1]);
				  
			    if($file_compare == "pdf")
				{
					$image="pdf.png";
					$file_compare1=1;
				}
			    elseif($file_compare == "docx"|| $file_compare =="doc")
				{
					$image="docx.png";
					$file_compare1=2;
				}
				elseif($file_compare=="xlsx"|| $file_compare =="xls")
				{
					$image="excel.png";
					$file_compare1=4;
				}
				 elseif($file_compare=="pptx"|| $file_compare =="ppt")
				{
					$image="ppt.png";
					$file_compare1=5;
				}
				elseif($file_compare=="zip"||  $file_compare =="rar")
				{
					$image="gi.png";
					$file_compare1=6;
				}
				else
				{
					$image="no_image.png";
					$file_compare1=7;
				}
			
			
		  	   
				 $level=$this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$fid);
				 $priceValue = 0;
				 $publicFlag = 1;
				 $public_file = 1;
				 if(!isset($_POST['chkpublic'])){
					 
					$priceValue =  $this->input->post('res_price');
					$publicFlag = 0;
					
				 }
				 
				  if(!isset($_POST['chkpublic_1'])){
					$public_file = 0;
				 }
				 
					$data=array(
		    		
						'folder_id'=>$fid,
						'type'=>$file_compare1,
						'description'=>$this->input->post('description'),										
						'res_price'=> $priceValue,
						'public'=>$publicFlag,
						'public_file'=>$public_file,
						'resources' =>$uploadfile,
						'image'=>$image,
						'file_name'=>$this->input->post('file_name'),
		  		);
				if($sub_folder !=""){
					$data['folder_id'] = $sub_folder;	
				}
		        $this->gpi_model->insert($data,'resources');				 
				 if($sub_folder !=""){
					  header("Location: ".base_url()."admin/resources/show_sub_folder/".$fid."/".$sub_folder);
				 }else{
					 header("Location: ".base_url()."admin/resources/resources_view/".$fid);	 
				}
				 
				 
				 $zip = new ZipArchive();
				$getfiles = $this->gpi_model->getrecordbyid('resources','folder_id',$fid);
				$getfoldername = $this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$fid);
				$zipname = friendlyURL($getfoldername->folder_name).".zip";
				$createzip = fopen('./assets/resources_archive/'.$zipname, 'w') or die("Can't create file");
				$zip->open('./assets/resources_archive/'.$zipname);
				
				foreach($getfiles as $res) {
					$zip->addFile('./assets/user/file_upload/'.$res->resources, $res->resources);
				}
				$data2=array(
				 'zip_file'=>$zipname
				);
				
				 $this->gpi_model->update($data2,"resource_folder",$fid,"resource_folder_id"); 
				$zip->close();
				
				///Then download the zipped file.
				/*header('Content-Type: application/zip');
				header('Content-disposition: attachment; filename='.$zipname);
				header('Content-Length: ' . filesize($zipname));
				readfile('./assets/resources_archive/'.$zipname);*/
			
			}
			
	  }
	  
	   function add_folder($page_id="")
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('folder_name', 'Folder Name', 'required');
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');


		   if ($this->form_validation->run() == FALSE)

			{

			 
				//$data['main_cls'] = 'digital_media';
				//$data['class'] = "add_folder";
				
				
				$data['main_cls'] = 'digital_media';
				$data['class'] = "folder_view_setting";
				
				
				$data['content'] = 'admin/folder_insert';

				$this->load->view('admin/layout/layout',$data);

			}

			else

			{ 
			
			
			

                  $users=$this->input->post('user_id');
  					 $user_id=",";
   						foreach($users as $user_in) {
  						  $user_id.=$user_in.","; 
  						 }
				 
		           $data=array(
		    		'folder_name'=>$this->input->post('folder_name'),
					'public_folder'=>$this->input->post('public_folder'),
					'user_id'=>$user_id,
					'datetime'=>strtotime($this->input->post('datetimepicker')),
					'date_time_actual'=>$this->input->post('datetimepicker'),
		  		);
				
				 
                   $this->gpi_model->insert($data,'resource_folder');
				   $lastid = $this->db->insert_id();
				    $level_ids = $this->input->post('level_id');
				
					$level_array = array();
					
					if(!empty($level_ids)){
						foreach($level_ids as $each_level){
							$level_array[] = array('resource_folder_id'=>$lastid,'level_id'=>$each_level);
						}
						$this->db->insert_batch('resource_folder_level', $level_array); 
					}
					
					$this->session->set_flashdata('update_msg','Folder Successfully Inserted...');
					if($page_id == ""){
						header("Location: ".base_url()."admin/resources/folder_view");
					}else{
						header("Location: ".base_url()."admin/resources/folder_file_view/");
					}
			}

	  	}
		
		
		 function add_sub_folder($folder_id,$subfolder_id="")
     	 {  
		
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('folder_name', 'Folder Name', 'required');
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   if ($this->form_validation->run() == FALSE)
			{
	
				$data['res_id'] = $folder_id;
				$data['sub_folderid'] = $subfolder_id;
				$data['main_cls'] = 'digital_media';
				$data['class'] = "folder_view_setting";
				$data['content'] = 'admin/sub-folder/sub_folder_insert';
				$this->load->view('admin/layout/layout',$data);
			}else{ 
			
			$resourcRecord = $this->common_model->select_where("*","resource_folder",array("resource_folder_id"=>$folder_id));
			$resourcRecord = $resourcRecord->row_array();
			
			$resourcRecordLevel1 = array();
			$resourcRecordLevel = $this->common_model->select_where("*","resource_folder_level",array("resource_folder_id"=>$folder_id));
			if($resourcRecordLevel->num_rows() >0){
				$resourcRecordLevel1 = $resourcRecordLevel->result_array();
			}
			
			
				 
		           $data=array(
						"folder_name"=>$this->input->post('folder_name'),
						"public_folder"=>$resourcRecord['public_folder'],
						"level_id"=>$resourcRecord['level_id'],
						"user_id"=>$resourcRecord['user_id'],
						"unlock_folders"=>$resourcRecord['unlock_folders'],
						"type"=>"sub",
						"parent_id"=>$folder_id,
						'datetime'=>strtotime($this->input->post('datetimepicker')),
						'date_time_actual'=>$this->input->post('datetimepicker'),
		  		);
				
				
				$this->common_model->insert_array("resource_folder",$data);
				 
                  //  $this->gpi_model->insert($data,'resource_folder');
				    $lastid = $this->db->insert_id();
				    $level_ids = $this->input->post('level_id');
					$level_array = array();
					
				/*	if(!empty($level_ids)){
						foreach($level_ids as $each_level){
							$level_array[] = array('resource_folder_id'=>$lastid,'level_id'=>$each_level);
						}						
					}
					
					*/
					if(!empty($resourcRecordLevel1)){
						
						foreach($resourcRecordLevel1 as $resLevel){
								$level_array[] = array('resource_folder_id'=>$lastid,'level_id'=>$resLevel['level_id']);	
						}
						$this->db->insert_batch('resource_folder_level', $level_array); 
					}
					
					$this->session->set_flashdata('add_product','Sub-folder Successfully Inserted...');
					header("Location: ".base_url()."admin/resources/resources_view/".$folder_id);
			}

	  	}
		
		function show_sub_folder($main_folder,$sub_folder_id)
	 	 {
				$data['fid']		   =	$main_folder; 
				$data['sub_folder_id'] =	$sub_folder_id; 
				$data['main_cls'] = 'digital_media';
				$data['class'] = "resources_view";
				$data['content'] = 'admin/sub-folder/sub_folder_view';
				$this->load->view('admin/layout/layout',$data);
	  }
		
		
		
		
		
	  function resources_view($fid)
	  {
		 $data['fid']=$fid; 
			
		$mysqlPackage = 	$this->db->query("SELECT * FROM tbl_package INNER JOIN tbl_package_levels ON tbl_package.pkg_id = tbl_package_levels.pkg_id WHERE tbl_package_levels.resource_id = ".$fid." GROUP BY tbl_package_levels.pkg_id");

			//get sub-folders.
		//	$mysql_subfolders = 	$this->db->query("SELECT * FROM resource_folder JOIN resource_folder_level ON resource_folder_level.resource_folder_id =resource_folder.resource_folder_id WHERE resource_folder.parent_id= ".$fid." ");

			$mysql_subfolders = 	$this->common_model->select_where("*","resource_folder",array("parent_id"=>$fid,"type"=>"sub"));
			//echo $this->db->last_query();die;
			$data['mysql_subfolders'] = $mysql_subfolders;
			$data['mysqlPackage'] = $mysqlPackage;
			$data['main_cls'] = 'digital_media';
			$data['class'] = "resources_view";
		    $data['content'] = 'admin/resources_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  function folder_file_view()
	  {
		      $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',24);
                 $per_page =$pages->pages;
			   // $per_page =10;
				$qry = "select * from `resource_folder` WHERE parent_id=0";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/resources/folder_file_view'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				$data['main_cls'] = 'digital_media';
				$data['class'] = "folder_file_view";
				
		    $data['content'] = 'admin/folder_file_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	  function folder_view()
	  {
		     $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',23);
                 $per_page =$pages->pages;
			   // $per_page =10;
				$qry = "select * from `resource_folder`";
				
				if($this->input->get('foldername') != "" &&  $this->input->get('foldername') != "undefined") {
				$foldername = $this->input->get('foldername');
					if($foldername==1)
					{
					  $qry.="order by `folder_name` ASC";
					}
					else
					{
						 $qry.="order by `folder_name` DESC";
					}
				} 
				
				$offset = $this->input->get('per_page');
		
				if(!$offset)
					$offset = 0;
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['page_query_string'] = TRUE;
				$config['base_url']= base_url().'admin/resources/folder_view/?result=true&foldername='.$foldername.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				//$data['main_cls'] = 'digital_media';
				$data['class'] = "folder_view_setting";
				$data['main_cls'] = 'digital_media';
				
				
				
		    	$data['content'] = 'admin/folder_view';
			
		    $this->load->view('admin/layout/layout',$data);
	  }
	  function folder_view_ajax()
	  {
		     $this->load->library('pagination');
				$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',23);
                 $per_page =$pages->pages;
			   // $per_page =10;
				$qry = "select * from `resource_folder`";
				
				if($this->input->get('foldername') != "" &&  $this->input->get('foldername') != "undefined") {
				$foldername = $this->input->get('foldername');
					if($foldername==1)
					{
					  $qry.="order by `folder_name` ASC";
					}
					else
					{
						 $qry.="order by `folder_name` DESC";
					}
				} 
				
				$offset = $this->input->get('per_page');
		
				if(!$offset)
					$offset = 0;
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['page_query_string'] = TRUE;
				$config['base_url']= base_url().'admin/resources/folder_view/?result=true&foldername='.$foldername.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				
		 //   $data['content'] = 'admin/folder_view';
			$data['class'] = "media";
		    $this->load->view('admin/folder_view_ajax',$data);
	  }
	  function delete_resources($id,$fid)
	  {
		  $table="resources";
		  $primary="resources_id";
	      $this->db->delete($table, array($primary => $id));
		  
				
		  $this->session->set_flashdata('delete_msg','Resources File Successfully Deleted...');
	      header("Location: ".base_url()."admin/resources/resources_view/".$fid);
		  
		  //zip _create **********
		   $zip = new ZipArchive();
				$getfiles = $this->gpi_model->getrecordbyid('resources','folder_id',$fid);
				$getfoldername = $this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$fid);
				$zipname = friendlyURL($getfoldername->folder_name).".zip";
				$createzip = fopen('./assets/resources_archive/'.$zipname, 'w') or die("Can't create file");
				$zip->open('./assets/resources_archive/'.$zipname);
				
				foreach($getfiles as $res) {
					$zip->addFile('./assets/user/file_upload/'.$res->resources, $res->resources);
				}
				$data2=array(
				 'zip_file'=>$zipname
				);
				
				 $this->gpi_model->update($data2,"resource_folder",$fid,"resource_folder_id"); 
				$zip->close();
	  }
	  function delete_folder($id)
	  {
		  $table="resource_folder";
		  $primary="resource_folder_id";
	      $this->db->delete($table, array($primary => $id));
		  $this->db->delete('resources', array('folder_id' => $id));
		  $this->session->set_flashdata('delete_msg','Resources Folder Successfully Deleted...');
	      header("Location: ".base_url()."admin/resources/folder_view");
	  }
	  
	  function update_resources($id,$fid)
      {  
	   						
	      $this->load->library('form_validation');
		  
		   if(!isset($_POST['chkpublic'])){
			   $this->form_validation->set_rules('res_price','Price','required');
		   }
		   
		  $this->form_validation->set_rules('description', 'Description', 'required');
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				$data['fid'] = $fid;
				$data['main_cls'] = 'digital_media';
				$data['class'] = "resources_update";
				$data['content'] = 'admin/resources_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
		
			   $config['upload_path'] = './assets/user/file_upload';
		       //$config['allowed_types'] = 'docx|pdf|jpg|png|jpeg|xml|pages|xls|doc|pptx|ppt|webm|mkv|flv|vob|ogg|ojb|gif|avi|mov|wmv|rm|rmvb|asf|mp4|m4v|m4p|mpeg|3gp|3g2|mxf|nsv|zip|rar|xlsx|doc';
               $config['allowed_types'] = '*';
			   $config['max_size']	= '100000';
	
			   $this->load->library('upload', $config);
			   if ( ! $this->upload->do_upload('resources'))
				{
					
				//$this->session->set_flashdata('error', $this->upload->display_errors());
				//header("Location: ".base_url()."admin/resources/update_resources/".$id);
				
				} else {
				$resources = $this->upload->data();
				}
		     
				if($_FILES["resources"]["name"]!="" and (!empty($_FILES["resources"]["name"])))
				{
			
				 $uploadfile=$resources['file_name'];
				 
				 $file_explode=explode(".", $uploadfile);
				  
				 $file_compare=$file_explode[1];
				  
			    if($file_compare == "pdf")
				{
					$file_compare1=1;
				}
			    else if($file_compare=="docx" || 'doc')
				{
					$file_compare1=2;
				}
				elseif($file_compare=='xlsx' || 'xls')
				{
					$file_compare1=4;
				}
				 elseif($file_compare=='pptx' || 'ppt')
				{
					$file_compare1=5;
				}
				elseif($file_compare=='zip' || 'rar')
				{
					$file_compare1=6;
				}
				else
				{
					$file_compare1=7;
				}
				
				 $priceValue1 = 0;
				 $publicFlag = 1;
				 $public_file = 1;
				 
				 if(!isset($_POST['chkpublic'])){
					$priceValue1 =  $this->input->post('res_price');
					$publicFlag = 0;
				 }
				 
				  if(!isset($_POST['chkpublic_1'])){
					$public_file = 0;
				 }
				 
				   $level=$this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$fid);
					
					$data=array(
		    		
				    'folder_id'=>$fid,
					'type'=>$file_compare1,	
					'public' => $publicFlag,
					'public_file' => $public_file,
					'res_price'=>$priceValue1,
					'description'=>$this->input->post('description'),
					'resources' =>$uploadfile,
		   		    'file_name'=>$this->input->post('file_name'),
		 
		  		);
				
				 $this->gpi_model->update($data,"resources",$this->input->post('vid'),"resources_id");
				 
				
				 
				 
			     header("Location: ".base_url()."admin/resources/resources_view/".$fid);
			   
				}
			   else
			   {
				   
				   $level=$this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$fid);
				
				
					 $priceValue = 0;
					 $publicFlag = 1;
					 if(!isset($_POST['chkpublic'])){
						 
						$priceValue =  $this->input->post('res_price');
						$publicFlag = 0;
						$this->common_model->update_array(array('item_id'=>$this->input->post('vid'),'item_type'=>'res'),'membership_access',array('lock_item'=>'yes'));
						
					 }
					 $public_file = 1;
					 
					   if(!isset($_POST['chkpublic_1'])){
							$public_file = 0;
						 }
					 
					 $data=array(
					 
						'folder_id'=>$fid,
						'public'=>$publicFlag,
						'public_file' => $public_file,
						'res_price'=> $priceValue,
						'description'=>$this->input->post('description'),										
						'file_name'=>$this->input->post('file_name'),
					);
				
				
				    $this->gpi_model->update($data,"resources",$this->input->post('vid'),"resources_id");  
				    header("Location: ".base_url()."admin/resources/resources_view/".$fid);
				
				    //zip _create **********
					$zip = new ZipArchive();
					$getfiles = $this->gpi_model->getrecordbyid('resources','folder_id',$fid);
					$getfoldername = $this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$fid);
					$zipname = friendlyURL($getfoldername->folder_name).".zip";
					$createzip = fopen('./assets/resources_archive/'.$zipname, 'w') or die("Can't create file");
					$zip->open('./assets/resources_archive/'.$zipname);
					
					foreach($getfiles as $res) {
						$zip->addFile('./assets/user/file_upload/'.$res->resources, $res->resources);
					}
					$data2=array(
					 'zip_file'=>$zipname
					);
					
					 $this->gpi_model->update($data2,"resource_folder",$fid,"resource_folder_id"); 
					$zip->close();
			   }
	  	}
		
	 }
	 
	 
      function update_folder($id)
      {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('folder_name', 'Folder Name', 'required');
		   
		  
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');


		   if ($this->form_validation->run() == FALSE)

			{

			  	$data['id'] = $id;
				$selectedLevels = $this->common_model->select_where('level_id','resource_folder_level',array('resource_folder_id'=>$id));
				$myLevels = array();
				if($selectedLevels->num_rows() > 0){
					$selectedLevels = $selectedLevels->result_array();
					foreach($selectedLevels as $myArr){
						$myLevels[] = $myArr['level_id'];
						
					}
				}
				$data['myLevels'] = $myLevels;

				$data['main_cls'] = 'digital_media';
				$data['class'] = "folder_view_setting";
				
				

				$data['content'] = 'admin/folder_update';

				$this->load->view('admin/layout/layout',$data);

			}

			else

			{ 
			
			     $users=$this->input->post('user_id');
  					 $user_id=",";
   						foreach($users as $user_in) {
  						  $user_id.=$user_in.","; 
  						 }
				   
		           $data=array(

		    		'folder_name'=>$this->input->post('folder_name'),
					'public_folder'=>$this->input->post('public_folder'),
					'user_id'=>$user_id,
					'datetime'=>strtotime($this->input->post('datetimepicker')),
					'date_time_actual'=>$this->input->post('datetimepicker'),
					
		  		   );
				   
				  
				   
				   
				   $this->common_model->delete_where(array('resource_folder_id'=>$id),'resource_folder_level');
				   
				    $level_ids = $this->input->post('level_id');
					$level_array = array();
					
					if(!empty($level_ids)){
						foreach($level_ids as $each_level){
							$level_array[] = array('resource_folder_id'=>$id,'level_id'=>$each_level);
						}
						$this->db->insert_batch('resource_folder_level', $level_array); 
					}
				
				  $this->gpi_model->update($data,"resource_folder",$this->input->post('vid'),"resource_folder_id");
				  
				 $this->common_model->update_array(array('parent_id'=>$id),'resource_folder',array("public_folder"=>$this->input->post('public_folder'),"user_id"=>$user_id));
				 
				$mysql_childs =  $this->common_model->select_where("*","resource_folder",array("parent_id"=>$id));
				if($mysql_childs->num_rows() >0){
					foreach($mysql_childs->result_array() as $childObj){
							  $this->common_model->delete_where(array('resource_folder_id'=>$childObj['resource_folder_id']),'resource_folder_level');
							  if(!empty($level_ids)){
								foreach($level_ids as $each_level){
									$level_array[] = array('resource_folder_id'=>$childObj['resource_folder_id'],'level_id'=>$each_level);
								}
								$this->db->insert_batch('resource_folder_level', $level_array); 
					}
							  
					}	
				}
				  
				    
                 // $this->gpi_model->update($data1,"resources",$this->input->post('vid'),"folder_id"); 
				  
			     $this->session->set_flashdata('update_msg','Folder Successfully Updated...');

		      header("Location: ".base_url()."admin/resources/folder_view");

			}

	  	}
	   function getzip($fid) {
		$zip = new ZipArchive();
		$getfiles = $this->gpi_model->getrecordbyid('resources','folder_id',$fid);
		$getfoldername = $this->gpi_model->getrecordbyidrow('resource_folder','resource_folder_id',$fid);
		$zipname = friendlyURL($getfoldername->folder_name).".zip";
		$createzip = fopen('./assets/resources_archive/'.$zipname, 'w') or die("Can't create file");
		$zip->open('./assets/resources_archive/'.$zipname);
		
		foreach($getfiles as $res) {
			$zip->addFile('./assets/user/file_upload/'.$res->resources, $res->resources);
		}
		
		$zip->close();
		
		///Then download the zipped file.
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile('./assets/resources_archive/'.$zipname);
		
		//unlink('./assets/resources_archive/'.$zipname);
	 }
	 
	 
	   public function delete_package($pkg_id,$folder_id=''){
		   
		  
			
			  $this->common_model->delete_where(array('pkg_id'=>$pkg_id),'tbl_package');
			  $this->common_model->delete_where(array('pkg_id'=>$pkg_id),'tbl_package_levels');
			  $this->common_model->delete_where(array('pkg_id'=>$pkg_id),'tbl_package_resource_relation');
			  $mysqlaccess =  $this->db->query("SELECT GROUP_CONCAT(`access_id`) as access_id FROM  tbl_package_access WHERE pkg_id = ".$pkg_id."");
			  if($mysqlaccess->num_rows() >0){
			  	$mysqlaccess =  $mysqlaccess->row_array();
				$access_id = $mysqlaccess['access_id'];
				
				if(!empty($access_id)){
					$this->db->query("DELETE FROM tbl_cart WHERE item_id IN (".$access_id.") AND item_type = 'pkg'");
				}
				$this->common_model->delete_where(array('pkg_id'=>$pkg_id),'tbl_package_access');
			  }
			  
			    $this->session->set_flashdata('add_product','Package has been deleted Successfully!');
				header("Location: ".base_url()."admin/resources/resources_view"."/".$folder_id);
			  
	   }
	   
	   public  function update_package($pkg_id,$folder_id){

		
		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('pkg_name', 'Package Name', 'required');
		  //$this->form_validation->set_rules('pkg_description', ' Package Description', 'required');
		 
		  if(@$_POST['chkpublic'] ==""){
			  
			  $this->form_validation->set_rules('level', 'Level', 'required');	
		  
		  }
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
			 $data['pkg_id'] = $pkg_id;
			 $data['folder_id'] = $folder_id;
			 $mysql_package = $this->common_model->select_where('*','tbl_package',array('pkg_id'=> $pkg_id));
			 $data['mysql_package'] = $mysql_package->row_array();
			$data['main_cls'] = 'digital_media';
			$data['class'] = "edit_package";
			 $data['content'] = 'admin/edit_package';
			 $this->load->view('admin/layout/layout',$data);
			 
			}else{
				
			
				$Insert_array = array();
				$insert_array['pkg_name'] = $this->input->post('pkg_name');     
				
				
				$insert_array['pkg_price'] = $this->input->post('pkg_price');
				$insert_array['pkg_public'] = 0;
				if(@$_POST['chkpublic'] !=""){
					$insert_array['pkg_public'] = 1;
				} 
				
				$insert_array['pkg_description'] = $this->input->post('pkg_description');
				$this->common_model->update_array(array('pkg_id'=>$pkg_id),'tbl_package',$insert_array);
				$this->common_model->delete_where(array('pkg_id'=>$pkg_id),'tbl_package_levels');
				
				if($insert_array['pkg_public'] == 0){
					
					$levels = $this->input->post('level');
					$resources = $this->input->post('resources');
					
					for($i=0; $i< count($levels); $i++){
							$myArray = array();
							
							for($j=0; $j < count($resources);$j++){
								$myArray[] = array('pkg_id'=>$pkg_id,'level_id'=>$levels[$i],'resource_id'=>$resources[$j]);
							}
							$this->db->insert_batch('tbl_package_levels',$myArray);
					}
					
				}
				$this->session->set_flashdata('add_product','Package has been updated Successfully!');
				header("Location: ".base_url()."admin/resources/resources_view"."/".$folder_id);

}

	  }

	   public function download_pkg($pkg_id , $folder_id){
		
	
		
		$resourcesObj = $this->common_model->select_where('DISTINCT(resource_id) AS resource_id','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
		
		if($resourcesObj->num_rows() >0){
			
			$this->load->helper('download');
			$rootPath = realpath('./assets/user/file_upload/');
			$zipname = time()."unlock-resources.zip";
			// Initialize archive object
			$zip = new ZipArchive();
			$myflag = 0;
			$zip->open('./assets/resources_archive/'.$zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
				foreach($resourcesObj->result_array() as $resObj=>$resValue){
					
					$fileName = $this->common_model->select_single_field('resources','resources',array('resources_id'=>$resValue['resource_id']));
					//echo $fileName."<br />";
					
					if(file_exists('./assets/user/file_upload/'.$fileName)){
						$myflag = 1;
						$zip->addFile('./assets/user/file_upload/'.$fileName, $fileName);	
					}
			}
			
			if($myflag == 0){
				$this->session->set_flashdata('errMsg','Sorry! File does not exists!');
				header("Location: ".base_url()."admin/resources/resources_view"."/".$folder_id);
			}
			
			//die;
			// Zip archive will be created only after closing object
			$zip->close();
			///Then download the zipped file.
			$file = './assets/resources_archive/'.$zipname;
			$file_name = basename($file);
			header("Content-Type: application/zip");
			header("Content-Disposition: attachment; filename=" . $file_name);
			header("Content-Length: " . filesize($file));
			readfile($file);
		}
			#end of download file 
		
	}
	   
	  		
  }
  
  
