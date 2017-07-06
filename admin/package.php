<?php

  class Package extends CI_Controller

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
   
    function view_package(){

		 	$allpkg = $this->db->query("SELECT * FROM tbl_package");
			$data['allpackage'] = $allpkg;
			$data['main_cls'] = 'packages';
			$data['class'] = "view_package";
			$data['content'] = 'admin/package/view_package';
			$this->load->view('admin/layout/layout',$data); 
	  }
	  
	  
	  
	  function showResources(){
		  
		$level_ids  = $this->input->post('level_id');
		$foldersIds = $this->input->post('folders_id');

		$level_ids = implode("," , $level_ids[0]);
		// class='selectpicker'  multiple='multiple'
	
		$dropDown = "<select name='resources[]' id='myResources' class='selectpicker'  multiple='multiple'>";
		$mysql_resources = $this->common_model->select_where('*','resource_folder',"level_id IN (".$level_ids.")");
		
		
		if($mysql_resources->num_rows() > 0){
			$mysql_resources = $mysql_resources->result_array();
			foreach($mysql_resources as $resObj){
				$selected = '';
				if(!empty($foldersIds[0])){
					/*print_r($resObj['resource_folder_id']);
					print_r($foldersIds[0]);*/
					if(in_array($resObj['resource_folder_id'],$foldersIds[0])){
						$selected = "selected='selected'";
					}
				}
				$dropDown .= "<option  ".$selected." value='".$resObj['resource_folder_id']."'>".$resObj['folder_name']."</option>";
			}
		}
		
		$dropDown .="</select>";
		echo $dropDown;
	  }
   
   
   
   function delete_resources_records(){
		$pkg_id = $this->input->post('pkg_id');   
		$this->common_model->delete_where(array('pkg_id'=>$pkg_id),'tbl_package_resource_relation');
		echo "1";
	}
   
   
   
   function selectedFolders(){
	   
	   $pkg_id = $this->input->post('pkg_id');
	  $mysqlObj =  $this->db->query('SELECT DISTINCT(level_id) as level_id FROM tbl_package_resource_relation WHERE pkg_id = '.$pkg_id.'');	   
	  if($mysqlObj->num_rows() >0){
		$mysqlArray = array();
		
		foreach($mysqlObj->result_array() as $mysqlArr){
			$mysqlArray[] = $mysqlArr['level_id'];
		}	  
	   }else{
		   $mysqlArray = 0;
		}
	   echo json_encode($mysqlArray);
	}
   
   function folders(){

		$pkg_id 		= $this->input->post('pkg_id');
		$package_levels = $this->input->post('level_id');
		
		
		$resource_array = $this->input->post('resource_array');
		
		$myarray = array();
		$package_relation = $this->common_model->select_where('*','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
		$foldersIds = "";
		$res_ids = "";
		if($package_relation->num_rows() >0){
			$package_relation = $package_relation->result_array();
			$foldersIds = array();
			foreach($package_relation as $vidKey=>$vidValue){
				$foldersIds[] = $vidValue['folder_id'];
				$res_ids[]	  = $vidValue['resource_id'];
			}
			
			$foldersIds = array_unique($foldersIds);
			
		} 
		
		
		
	//	$package_levels1 = $this->db->query("SELECT DISTINCT(resource_id) as resource_id FROM tbl_package_levels WHERE pkg_id = ".$pkg_id."");
		
	//	$package_levels = $this->db->query("SELECT GROUP_CONCAT(level_id) as level_id FROM tbl_package_levels WHERE pkg_id = ".$pkg_id."");
	//	$package_levels = $this->db->query("SELECT GROUP_CONCAT(level_id) as level_id FROM tbl_package_levels WHERE pkg_id = ".$pkg_id."");
	//	$package_levels = array();
	//	foreach($package_levels1->result_array() as $pkgObj){
	//		$package_levels[] = $pkgObj['resource_id'];
	//	}
	//	$package_levels = implode(",",$package_levels);
	
	
		
		//$package_levels = $package_levels['level_id'];
		$dropDown = "<input type='hidden' id='pkg_id' name='pkg_id' value=".$pkg_id." />"; 
		$dropDown .= "<label>Choose Resource Folder</label>";
		$dropDown .= "<select name='resources[]' onchange='show_resources_detail_pkg(this)' id='resources_dropdown' class='selectpicker myresource' multiple='multiple'>";
	  
	//	$mysql_resources = $this->common_model->select_all('*','resource_folder');
		//if(!empty($package_levels)){
		
			$mysql_resources = $this->common_model->select_where('*','resource_folder',"level_id IN (".$package_levels.")");
		//}
		
			if($mysql_resources->num_rows() > 0){
				$mysql_resources = $mysql_resources->result_array();
				foreach($mysql_resources as $resObj){
					
					$selected = '';
					
					if(!empty($foldersIds) >0){
						
						if(in_array($resObj['resource_folder_id'],$foldersIds)){
							$selected = "selected='selected'";
						}
						
					}
					
					//if($foldersIds !=""){
					//	if(in_array($resObj['resource_folder_id'],$foldersIds)){
						if(!empty($resource_array)){
							
							if(in_array($resObj['resource_folder_id'],$resource_array[0])){
								$selected = "selected='selected'";
						}
					//	}
					
					
					}
					$dropDown .= "<option  ".$selected." value='".$resObj['resource_folder_id']."'>".$resObj['folder_name']."</option>";
				}
			}
			
			$dropDown .="</select>";
			
			
			
			$myarray[] = $dropDown;
			$html = "";
			if($foldersIds == ""){
				$html = "";
				
			}else{
				//print_r("empty".$foldersIds);die;
				$resouces_id = implode(",",$foldersIds);
				$resources_array = $res_ids;
				$mysqlDetail = $this->db->query("SELECT `resources`.`resources_id`, `resources`.`resources`, `resources`.`type`, `resources`.`file_name` FROM (`resources`) JOIN `resource_folder` ON `resource_folder`.`resource_folder_id` = `resources`.`folder_id` WHERE `resource_folder`.`resource_folder_id` IN (".$resouces_id.")");
				
				if($mysqlDetail->num_rows() > 0){
					foreach($mysqlDetail->result_array() as $details){
					$name = "";
					
					if($details['file_name'] != ""){
						$name = substr($details['file_name'], 0, 45);
					}else{
						$name = substr($details['resources'], 0, 45);
					}
					
					$type = $details['type'];
					$file_type = "";
					
					 if($type == 1){
							$file_type="pdf";
					} elseif($type == 2){
					
							$file_type="docx";
					} elseif($type== 4){
					
							$file_type="xlsx";
					} elseif($type== 5){
							$file_type="pptx";
	
					}elseif($type==6){
							$file_type="zip";
					}else{
							$file_type="img";
					}
					$checked="";
					if(in_array($details['resources_id'],$resources_array)){
						$checked='checked="checked"';
					}
					
						$html .= "<tr>
							<td>".$name."</td>
							<td>".$file_type."</td>
							
							<td><input type='checkbox' name='checkboxs[]' $checked class='checkbox_resources' data-resouces = '".$details['resources_id']."' value='".$details['resources_id']."' /></td>
						</tr>";
					}
				
				
				
				$myarray[] = $html;
				
				}
			}
			
			echo json_encode($myarray);
		
		
	}
	
	
	function resouces_details(){
		$resouces_id = $this->input->post('resources_id');
		$resources_array = $this->input->post('resources_array');
		$resources_array = explode(',',$resources_array);
		$mysqlDetail = $this->db->query("SELECT `resources`.`resources_id`, `resources`.`resources`, `resources`.`type`, `resources`.`file_name` FROM (`resources`) JOIN `resource_folder` ON `resource_folder`.`resource_folder_id` = `resources`.`folder_id` WHERE `resource_folder`.`resource_folder_id` IN (".$resouces_id.")");
		$html = "";
		if($mysqlDetail->num_rows() > 0){
			foreach($mysqlDetail->result_array() as $details){
			$name = "";
			
			if($details['file_name'] != ""){
				$name = substr($details['file_name'], 0, 45);
			}else{
				$name = substr($details['resources'], 0, 45);
			}
			
			$type = $details['type'];
			$file_type = "";
			
			 if($type == 1){
					$file_type="pdf";
			} elseif($type == 2){
			
					$file_type="docx";
			} elseif($type== 4){
			
					$file_type="xlsx";
			} elseif($type== 5){
					$file_type="pptx";

			}elseif($type==6){
					$file_type="zip";
			}else{
					$file_type="img";
			}
			$checked="";
			if(in_array($details['resources_id'],$resources_array)){
				$checked='checked="checked"';
			}
			
				$html .= "<tr>
					<td>".$name."</td>
					<td>".$file_type."</td>
					
					<td><input type='checkbox' name='checkboxs[]' $checked class='checkbox_resources' data-resouces = '".$details['resources_id']."' value='".$details['resources_id']."' /></td>
				</tr>";
			}
		
		
		}
		
		echo json_encode($html);
	}
	

	function add_package_recoureses(){
		
		$pkg_id 		= $this->input->post('mypkg_id');
		$folder_id 		= $this->input->post('resources');
		$resouces_ids   = $this->input->post('checkboxs'); 
		$level_ids 		= $this->input->post('level');
		
		
		if(empty($resouces_ids)){
			
			$data['main_cls'] = 'packages';
			$data['class'] = "add_package_recoureses";
			
			
			$this->session->set_flashdata('danger','Please Choose Supporting Material.');		
			redirect('admin/electures/electures_view/');
			
		}else{
		
			$mysqlVid = $this->common_model->select_where('*','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
			if($mysqlVid->num_rows() > 0){
				$this->common_model->delete_where(array('pkg_id'=>$pkg_id),'tbl_package_resource_relation');
			}

			for($lev=0; $lev < count($level_ids);$lev++){
				
				for($i=0; $i < count($folder_id);$i++){
					for($j=0; $j< count($resouces_ids); $j++){
						$data['pkg_id'] 		  = $pkg_id;
						$data['folder_id']		  = $folder_id[$i];
						$data['resource_id']	  = $resouces_ids[$j];
						$data['level_id']		  = $level_ids[$lev];
						$this->common_model->insert_array('tbl_package_resource_relation',$data);
					}
				}

			}

			$this->session->set_flashdata('add_product','Documents have been added to the package');
			redirect('admin/package/view_package/');
		}
		
	}
	
   
   
  

	public  function add_package(){
		  
		  $this->form_validation->set_rules('pkg_name', 'Package Name', 'required');
		  //$this->form_validation->set_rules('pkg_description', ' Package Description', 'required');
		  if(@$_POST['chkpublic'] == ""){ 
		  		$this->form_validation->set_rules('level', 'Level', 'required');	
		  }
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
			  	 $data['main_cls'] = 'packages';
				 $data['class'] = "add_package";
			  	 $data['content'] = 'admin/package/add_package';
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
				$pkg_id = $this->common_model->insert_array('tbl_package',$insert_array);
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
				
				$this->session->set_flashdata('add_product','Package has been added Successfully!');
				header("Location: ".base_url()."admin/package/view_package");

		 }

	  }

	  public  function update_package($pkg_id){

		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('pkg_name', 'Package Name', 'required');
		  //$this->form_validation->set_rules('pkg_description', ' Package Description', 'required');
		 
		  if(@$_POST['chkpublic'] ==""){
			  
			  $this->form_validation->set_rules('level', 'Level', 'required');	
		  
		  }
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
			 $data['pkg_id'] = $pkg_id;
			 $mysql_package = $this->common_model->select_where('*','tbl_package',array('pkg_id'=> $pkg_id));
			 $data['mysql_package'] = $mysql_package->row_array();

			 $data['main_cls'] = 'packages';
			 $data['class'] = "update_package";
			 $data['content'] = 'admin/package/edit_package';
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
				header("Location: ".base_url()."admin/package/view_package");

}

	  }
	  
	    public function delete_package($pkg_id){
			
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
			  header("Location: ".base_url()."admin/package/view_package");		  
	  	}
		
	


public function filesPopup(){
		
		$pkg_id = $_GET["pkg_id"];
		$resourceArray = $this->common_model->select_where('DISTINCT(resource_id) as resource_id','tbl_package_resource_relation',array('pkg_id'=>$pkg_id));
		$my_files = "";
		if($resourceArray->num_rows() > 0){
			$my_files .='<ul class="list-group">';
			foreach($resourceArray->result_array() as $resObj){
				$filename = $this->common_model->select_single_field('resources','resources',array('resources_id'=> $resObj['resource_id']));
                 $my_files .='<li class="list-group-item">'.$filename.'</li>';	
			}
			$my_files .='</ul>';
		}
		echo $my_files;
		
	}


	}
  

  

  
