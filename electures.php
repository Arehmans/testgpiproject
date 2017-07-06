<?php
class electures extends CI_Controller
{
	
	function __construct() {
		parent::__construct();
		
		ini_set('max_input_time',5000000);
		ini_set('memory_limit', '5000000M');
		ini_set('post_max_size', '5000000M');
		ini_set('upload_max_filesize', '5000000M');
		ini_set('max_execution_time', 10000000);
		if($this->session->userdata("admin_id") == "") {
			redirect(base_url()."admin/login");
		}
		
	}
	public function index(){
		// echo 'hiiii';
	}
	
	
	public  function add_electures(){
		
		if(!isset($_POST['submit']))
		{
			
			$data['content'] = 'admin/electures_insert';
			$this->load->view('admin/layout/layout',$data);  	
			
		}
		else
		{
			$data1=array(

				'level_id'=>$this->input->post('level_id'),
				'status'=>$this->input->post('status'),
				'show_at_home'=>$this->input->post('show_at_home'),
				'video_name'=>$this->input->post('title')
				);
			
			$this->gpi_model->update($data1,'electures',$this->input->post('videoid'),"video_id");
			$chk=$this->gpi_model->insert($dat3,"videos");
	
			$data['content'] = 'admin/electures_insert';
			$this->load->view('admin/layout/layout',$data); 	  
		}
		
	}
	function electures_view()

	{
		
		
		
		$this->load->library('pagination');
		$per_page = 2;
		
		$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',25);

		//$per_page =$pages->pages;
		
		$offset = $this->input->get('per_page');
		
		if(!$offset)
			$offset = 0;
					
		if(!isset($_POST['submit']))
		{
			$this->load->model('common_model');
			$videodata1= $this->common_model->select_all('*','videos');			
			$config['total_rows'] = $videodata1->num_rows();
			
			$config['per_page']= $per_page;
			
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['uri_segment'] = 4;
			
			$config['page_query_string'] = TRUE;
			 
			$config['base_url']= base_url().'admin/electures/electures_view/?result=true'; 
			$this->pagination->initialize($config);
			$data['paginglinks'] = $this->pagination->create_links();  
			if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
		    $qry ='';
			$qry .= "limit  {$per_page} offset {$offset} ";
			$videodata= $this->db->query("SELECT * FROM videos ".$qry."");
//echo $this->db->last_query();die;
			if($videodata->num_rows() >0)
			$videodata = $videodata->result();
//print_r($videodata);
			$data['videodata'] = $videodata;
			$data['content'] = 'admin/electures_view';
			$this->load->view('admin/layout/layout',$data);

			
		}
		else
		{
			if($this->input->post('publis')!="")
			{		  
				$data2=array('features'=>0);
				$this->gpi_model->update($data2,'electures',1,"features");		  
				$data1=array(
					
					'level_id'=>$this->input->post('level_id'),
					'status'=>$this->input->post('status'),
					'video_name'=>$this->input->post('title'),
					'show_at_home'=>$this->input->post('show_at_home'),
					'features'=>$this->input->post('feature')
					);
				$this->gpi_model->update($data1,'electures',$this->input->post('videoid'),"video_id");
				$this->session->set_flashdata('vedio_msg','Video is Successfully Unpublished..');
				$data['content'] = 'admin/electures_view';
				$this->load->view('admin/layout/layout',$data);

			}
			if($this->input->post('unpublis')!="")
			{		  
				$data1=array(
					
					'level_id'=>$this->input->post('level_id'),
					'status'=>$this->input->post('status'),
					'video_name'=>$this->input->post('title'),
					'show_at_home'=>$this->input->post('show_at_home'),
					'features'=>0
					);
				$this->gpi_model->update($data1,'electures',$this->input->post('videoid'),"video_id");
				$this->session->set_flashdata('vedio_msg','Video is Successfully Published..');
				$data['content'] = 'admin/electures_view';
				$this->load->view('admin/layout/layout',$data);
				
			}
			
		}

		
	}
	function new_player(){
		$data['content'] = 'admin/new_player';
		$this->load->view('admin/layout/layout',$data);
	}
	public function add_video(){
		$data['content'] = 'admin/add_video';
		$this->load->view('admin/layout/layout',$data);
	}
	function edit_electure($id){
		$video_data = $this->gpi_model->get_by_where('videos', array('electures_id' => $id));
    // echo "<pre>";
    // print_r($video_data);
    // die;
		$data['v_data'] = $video_data;
		$data['content'] = 'admin/edit_video';

		$this->load->view('admin/layout/layout',$data);
	}
	function delete_electures($id)
	{

		$table="electures";

		$primary="electures_id";
		$this->load->model('common_model');
                $video_name = $this->common_model->select_single_field('video_name','videos',array('electures_id'=>$id));
                $target_dir = "./assets/uploads/testing/".$video_name;
                if (file_exists($filename)) {
                     unlink($target_dir);  
                }
                $this->db->delete('video_levels ',array('video_id'=>$id));
                $this->db->delete('videos',array('electures_id'=>$id));
				$this->db->delete('tbl_video_resoures_relation',array('video_id'=> $id));
				
				
		
		$this->session->set_flashdata('success','Video has been deleted successfully !'); 
		header("Location: ".base_url()."admin/electures/electures_view");

	}
	
	function add_video_recoureses(){
	
		$video_id = $this->input->post('video_id');
		$folder_id = $this->input->post('resources');
		$resouces_ids = $this->input->post('checkboxs'); 
		
		if(empty($resouces_ids)){
			
			$this->session->set_flashdata('danger','Please Choose Supporting Material.');		
			redirect('admin/electures/electures_view/');
			
		}else{
		
			$mysqlVid = $this->common_model->select_where('*','tbl_video_resoures_relation',array('video_id'=>$video_id));
			if($mysqlVid->num_rows() > 0){
				$this->common_model->delete_where(array('video_id'=>$video_id),'tbl_video_resoures_relation');
			}

			
			for($i=0; $i < count($folder_id);$i++){
				for($j=0; $j< count($resouces_ids); $j++){
					$data['video_id'] 		  = $video_id;
					$data['folder_id']		  = $folder_id[$i];
					$data['resouces_id']	  = $resouces_ids[$j];
					$this->common_model->insert_array('tbl_video_resoures_relation',$data);
				}
			}	

			$this->session->set_flashdata('success','Supporting Material added successfully!');		
			redirect('admin/electures/electures_view/');
		}
		
	}

	function folders(){
		$video_id = $this->input->post('video_id');
		$video_levels = $this->common_model->select_where('GROUP_CONCAT(level_id) as level_id','video_levels', array('video_id'=>$video_id));
		$video_levels = $video_levels->row_array();
		$video_levels = $video_levels['level_id'];
		$dropDown = "<input type='hidden' id='video_id' name='video_id' value=".$video_id." />"; 
		$dropDown .= "<label>Choose Resource Folder</label>";
		$dropDown .= "<select name='resources[]' onchange='show_resources_detail(this)' id='resources_dropdown' class='selectpicker' multiple='multiple'>";
	  
		$mysql_resources = $this->common_model->select_where('*','resource_folder',"level_id IN (".$video_levels.")");
		if($mysql_resources->num_rows() > 0){
			$mysql_resources = $mysql_resources->result_array();
			foreach($mysql_resources as $resObj){
				$dropDown .= "<option value='".$resObj['resource_folder_id']."'>".$resObj['folder_name']."</option>";
			}
		}
		$dropDown .="</select>";
		echo json_encode($dropDown);
		
		
	}
	
	function resouces_details(){
		
		$resouces_id = $this->input->post('resources_id');
		$resources_array = $this->input->post('resources_array');
		$resources_array = explode(',',$resources_array);
		$mysqlDetail = $this->db->query("SELECT `resources`.`resources_id`, `resources`.`resources`, `resources`.`type`, `resources`.`file_name` FROM (`resources`) JOIN `resource_folder` ON `resource_folder`.`resource_folder_id` = `resources`.`folder_id` WHERE `resource_folder`.`resource_folder_id` IN (".$resouces_id.") OR parent_id IN (".$resouces_id.")");
		
		
		
		
		
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
					<td>".$name."_".$details['resources_id']."</td>
					<td>".$file_type."</td>
					
					<td><input type='checkbox' name='checkboxs[]' $checked class='checkbox_resources' data-resouces = '".$details['resources_id']."' value='".$details['resources_id']."' /></td>
				</tr>";
			}
		
		
		}
		
		echo json_encode($html);
	}

	function update_electures($id)

	{  

		$this->load->library('form_validation');
		$this->form_validation->set_rules('level_id', 'Select Level', 'required');
		$this->form_validation->set_rules('youtube_url', 'Youtube URL', 'required');
		$this->form_validation->set_rules('vedio_name', 'Video Title', 'required');
		$this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');



		if ($this->form_validation->run() == FALSE)

		{

			$data['id'] = $id;

			$data['content'] = 'admin/electures_update';

			$this->load->view('admin/layout/layout',$data);

		}



		else

		{ 
			$config['upload_path'] = './assets/uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '10000';

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('thumb'))
			{
				//$this->session->set_flashdata('error', $this->upload->display_errors());
				header("Location: ".base_url()."admin/electures/add_electures");

			} else {
				$image = $this->upload->data();
			}
			if($_FILES["thumb"]["name"]!="" and (!empty($_FILES["thumb"]["name"]))) {
				$data=array(

					'level_id'=>$this->input->post('level_id'),
					'vedio_name' =>$this->input->post('vedio_name'),
					'youtube_url' =>$this->input->post('youtube_url'),
					'thumb' =>$image['file_name'],
					);

				$this->gpi_model->update($data,"electures",$this->input->post('vid'),"electures_id");  

				header("Location: ".base_url()."admin/electures/electures_view");
			}
			else
			{
				$data=array(
					'level_id'=>$this->input->post('level_id'),
					'vedio_name' =>$this->input->post('vedio_name'),
					'youtube_url' =>$this->input->post('youtube_url'),

					);
				$this->gpi_model->update($data,"electures",$this->input->post('vid'),"electures_id");  

				header("Location: ".base_url()."admin/electures/electures_view");
			}



		}

	}
	function form_values_live_old(){
		$data= $this->input->post();
		// echo "<pre>";
		// print_r($data);
		// print_r($_FILES);
		// die;
		if(count($_FILES)){
			$files = $_FILES['file'];
			$files_array = array();
					//print_r("No of fiels :"+count($files));
			foreach($files as $file_key => $files_val){
						//print_r("files recieved");
				$count = count($files_val);
				foreach($files_val as $key => $val){
					//print_r("file recieved :: \n");
					$files_array[$key]['name']  =  $val;
					$files_array[$key]['type']  =  $files['type'][$key];
					$files_array[$key]['tmp_name']  =  $files['tmp_name'][$key];
					$files_array[$key]['error']  =  $files['error'][$key];
					$files_array[$key]['size']  =  $files['size'][$key];
				}
				break;

			}
			$target_dir = "./assets/uploads/eLectures/";

			foreach($files_array as $key => $file){
				

				/*if (file_exists($target_dir.$file['name'])) 
				{*/
					$ran=rand(400000,1000000000);
					$file['name']=str_replace(' ', '-', $ran.$file['name']);
					$files_array[$key]['name']=$ran.$files_array[$key]['name'];   
				//} 
//print_r($file);die;
				if(@move_uploaded_file($file["tmp_name"],$target_dir.$file['name']))
				{
					//echo 'data sent';
					// $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
					// $dat["video_name"] = $file['name'];
					// $file_md5_name_tmp = md5(time().$file['name']);
					// $dat["video_name"] = $file_md5_name_tmp.'.'.$extension;
					
					
					$dat["title"] = $data['title'];
					$dat["video_name"] = $file['name'];
					// echo $dat["video_name"]; die;
					$levels = $data["video_level"];
					if(isset($data["video_show_on_home_up"])){
						$dat["show_at_home"] = $data["video_show_on_home_up"];
						$dat['video_id'] = $ran;
 		//$dat['video_name'] = $data['title'];
						//$dat2['video_id'] = $dat['video_id'];
					}else{
						$dat["show_at_home"] = 0;
					}
					if(isset($data["video_feature"])){
						$dat["features"] = $data["video_feature"];
					}else{
						$dat["features"] = 0;
					}
					if (isset($data["video_publish_up"])){
						$dat["status"] = $data["video_publish_up"];
						$dat2["status"] = $data['video_publish_up'];
					}else {
						$dat["status"] = 0;
						$dat2["status"] = 0;
					}

					$chk=$this->gpi_model->insert($dat,"videos");
					foreach($levels as $key => $value) {
						// echo "<pre>";
						// print_r($value);
						// die;
						$dat2['video_id'] = $chk;
						$dat2['level_id'] = $value;
						$chk2 = $this->gpi_model->insert($dat2, "video_levels");
						
						if($dat["features"] == 1){
							$lev_vids = array();
							$lev_vids = $this->gpi_model->get_videos_by_level($value);
							if(is_array($lev_vids)){
								foreach($lev_vids as $vidVal){
									$dat3 = array();
									$dat3['features'] = 0;
									$this->gpi_model->update($dat3,'videos',$vidVal->electures_id,"electures_id");

								}
							}
						
						$dat3 = array();
						$dat3['features'] = 1;
						$this->gpi_model->update($dat3,'videos',$dat2['video_id'],"electures_id");
						}	
					}
					//echo 'here';die;
					if($chk){
						$this->session->set_flashdata('success','Video have been uploaded successfully.');
						$resp = array('status'=>true,'msg'=>'Video Uploaded.');
						echo json_encode($resp);
					}
					else{
						
						$resp = array('status'=>false,'msg'=>'Error in Uploading');
						echo json_encode($resp);
					}



				}
				else 
				{

					echo 'failed to upload';
						 		//print_r($file["tmp_name"]);
						 		//print_r($file["name"]);
						 		//print_r($target_dir.$file['name']);
				}


						// print_r($file['name']);

			}
		}
		else {
			echo "Nooo file recieved";
		}
		die;
	}
	
	function form_values(){
		
		$data= $this->input->post();
		$c=0;
		if(count($_FILES)){
		
		
		$allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4","flv", "wma","avi");
			//$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$extension = $_FILES['file']['type'];
			$extension = $extension[$c];
			$file_name = "";
			$time="";
		
			if ((($_FILES["file"]["type"] == "video/mp4")
			|| ($_FILES["file"]["type"] == "application/octet-stream")
			|| ($_FILES["file"]["type"] == "audio/mp3")
			|| ($_FILES["file"]["type"] == "audio/wma")
			|| ($_FILES["file"]["type"] == "image/pjpeg")
			|| ($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg"))
			
			&& in_array($extension, $allowedExts))
			
			  {
				  echo "HERE";die;
			  if ($_FILES["file"]["error"] > 0)
				{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}
			  else
				{
				
				
				$time = time();
				$file_name = $time.$_FILES["file"]["name"];
				
				  //$target_dir = APPPATH . '.uploads/';
				  $target_dir = "./assets/uploads/eLectures/";
				  @move_uploaded_file($_FILES["file"]["tmp_name"],$target_dir . $file_name);
				  
				 
				}
			  }
			  
					$dat["title"] = $data['title'];
					$dat["video_name"] = $file_name;
					
					$priceVal = $this->input->post('price');
					$public =0;
					if(isset($data['chkpublic'])){
						$priceVal=0;
						$public = 1;
						
					}
					
					$dat["price"] = $priceVal;
					$dat["public"] = $public;
					$levels = $data["video_level"];
					if(isset($data["video_show_on_home_up"])){
						$dat["show_at_home"] = $data["video_show_on_home_up"];
						$dat['video_id'] = $time;
 		
					}else{
						$dat["show_at_home"] = 0;
					}
					if(isset($data["video_feature"])){
						$dat["features"] = $data["video_feature"];
					}else{
						$dat["features"] = 0;
					}
					if (isset($data["video_publish_up"])){
						$dat["status"] = $data["video_publish_up"];
						$dat2["status"] = $data['video_publish_up'];
					}else {
						$dat["status"] = 0;
						$dat2["status"] = 0;
					}
					
					if(isset($data['webinar_up'])){
						$dat["webinar"] = 0;
					}else{
						$dat["webinar"] = 0;
					}
					$chk=$this->gpi_model->insert($dat,"videos");
					foreach($levels as $key => $value) {
						
						$dat2['video_id'] = $chk;
						$dat2['level_id'] = $value;
						$chk2 = $this->gpi_model->insert($dat2, "video_levels");
						
						if($dat["features"] == 1){
							$lev_vids = array();
							$lev_vids = $this->gpi_model->get_videos_by_level($value);
							if(is_array($lev_vids)){
								foreach($lev_vids as $vidVal){
									$dat3 = array();
									$dat3['features'] = 0;
									$this->gpi_model->update($dat3,'videos',$vidVal->electures_id,"electures_id");

								}
							}
						
						$dat3 = array();
						$dat3['features'] = 1;
						$this->gpi_model->update($dat3,'videos',$dat2['video_id'],"electures_id");
						}	
					}
					if($chk){
						$this->session->set_flashdata('success','Video have been uploaded successfully.');
						$resp = array('status'=>true,'msg'=>'Video Uploaded.');
						echo json_encode($resp);
					}
					else{
						
						$resp = array('status'=>false,'msg'=>'Error in Uploading');
						echo json_encode($resp);
					}
			$c++;
		}
		else {
			echo "Nooo file recieved";
		}
		die;
	}
	
	
	
	function form_values2(){
		$data= $this->input->post();
		$vid_id =  $this->input->post('videoid');
		// echo count($_FILES);
		
		
		// echo $data["video_show_on_home_up"].' '. $data["video_feature"]. ' ' . $data["video_publish_up"];
					
					
		// die;
		
		
		$chk = 0;
		
		// if(count($_FILES)){
		// 	$files = $_FILES['file'];
		// 	$files_array = array();

		// 	foreach($files as $file_key => $files_val){

		// 		$count = count($files_val);
		// 		foreach($files_val as $key => $val){

		// 			$files_array[$key]['name']  =  $val;
		// 			$files_array[$key]['type']  =  $files['type'][$key];
		// 			$files_array[$key]['tmp_name']  =  $files['tmp_name'][$key];
		// 			$files_array[$key]['error']  =  $files['error'][$key];
		// 			$files_array[$key]['size']  =  $files['size'][$key];
		// 		}
		// 		break;

		// 	}
		// 	$target_dir = "./assets/uploads/testing/";

		// 	foreach($files_array as $key => $file){
		// 		if (file_exists($target_dir.$file['name'])) 
		// 		{
		// 			$ran=rand(400000,1000000000);
		// 			$file['name']=str_replace(' ', '-', $ran.$file['name']);
		// 			$files_array[$key]['name']=$files_array[$key]['name'];   
		// 		} 
		// 		// echo $file['name'];
		// 		// die;

		// 		if(move_uploaded_file($file["tmp_name"],$target_dir.$file['name']))
		// 		{
		// 			$dat['title'] = '';
		// 			$dat["show_at_home"] = 0;
		// 			$dat["features"] = 0;
		// 			$dat["status"] = 0;
		// 			$dat["video_name"] = $file['name'];
					
		// 			$this->gpi_model->update($dat,'videos',$vid_id,"electures_id");
					
		// 		}else{
		// 			echo 'failed to upload';
		// 		}
		// 	}    
		// }
		
		
		if($chk > 0){
			$vid_id = $chk;
		}
		
		$levels = $data["video_level"];
		if(isset($data["video_show_on_home_up"])){
			$dat['title'] = $data['title'];
			$dat["show_at_home"] = $data["video_show_on_home_up"];

		}else{
			$dat["show_at_home"] = 0;
		}
		if(isset($data["video_feature"])){
			$dat["features"] = $data["video_feature"];
		}else{
			$dat["features"] = 0;
		}
		if (isset($data["video_publish_up"])){
			$dat["status"] = $data["video_publish_up"];
			$dat2["status"] = $data['video_publish_up'];
		}else {
			$dat["status"] = 0;
			$dat2["status"] = 0;
		}
		
		if(isset($data['webinar_up'])){
			$dat["webinar"] = $data['webinar_up'];
		}else{
			$dat["webinar"] = 0;
		}
		
		$dat3 = array();
		$dat3["features"] = $dat["features"];
		$dat3["status"]   = $dat["status"];
		
		$priceVal = $this->input->post('price');
					$public =0;
					if(isset($data['chkpublic'])){
						$priceVal=0;
						$public = 1;
						
					}
		
		$dat3["price"]    = $priceVal;
		$dat3["public"]    = $public;
		$dat3["show_at_home"] = $dat["show_at_home"];
		//$dat3["webinar"] = $dat["webinar"];
		$dat3['title'] = $data['title'];
		$this->gpi_model->update($dat3,'videos',$vid_id,"electures_id");
		
		
		
		$this->db->delete('video_levels', array('video_id' => $vid_id));
		
		
		foreach($levels as $key => $value) {
			$data5 = array();
			$dat5['video_id'] = $vid_id;
			$dat5['level_id'] = $value;
			
			$this->gpi_model->insert($dat5,'video_levels');

			if($dat["features"] == 1){
				$lev_vids = array();
				$lev_vids = $this->gpi_model->get_videos_by_level($value);
				if(is_array($lev_vids)){
					foreach($lev_vids as $vidVal){
						$dat3 = array();
						$dat3['features'] = 0;
						$this->gpi_model->update($dat3,'videos',$vid_id,"electures_id");

					}
				}
			
			$dat3 = array();
			$dat3['features'] = 1;
			$this->gpi_model->update($dat3,'videos',$vid_id,"electures_id");
			}
			

			// $this->db->delete('videos', array('id' => $dat2['video_id']));
			
			// $chk2 = $this->gpi_model->insert($dat2, "video_levels");
			// $this->gpi_model->update($dat2,'video_levels',$dat2['video_id'],"video_id");
		}
		
		
		
		if($vid_id){
			$this->session->set_flashdata('success','Video have been updated successfull.');
			$resp = array('status'=>true,'msg'=>'Video Uploaded.');
			echo json_encode($resp);
		}
		else{
			$resp = array('status'=>false,'msg'=>'Error in Uploading');
			echo json_encode($resp);
		}
		die;
	}

}





