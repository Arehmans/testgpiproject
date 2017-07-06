<?php

  class Timeline extends CI_Controller

  {

	function __construct() {
     
     parent::__construct();
	 
	  ini_set('error_reporting', E_ALL);
	  ini_set('display_errors', 'On');  //On or Off
	  $this->load->helper('smiley');
	  $this->load->library('table');


	 $this->load->model('common_model');
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     date_default_timezone_set('America/New_York');
	 
     }
	

   }
   
   
   function outputJSON($msg, $status = 'error'){
    header('Content-Type: application/json');
    die(json_encode(array(
        'data' => $msg,
        'status' => $status
    )));
}

	 function postComment(){
		 
		 $result['comment'] = $str = parse_smileys($this->input->post('comment'), site_url().'assets/smilys/images/smileys/');
		 $result['post_id'] = $this->input->post('post_id');
		 $image_url = $this->input->post('image_url');
		 $result['user_id'] = $this->session->userdata("admin_id");
		 $loop_id = $this->input->post('loop_id');
		 $upload_dir = './assets/uploads/comment_images';
		 $result['image'] = $this->input->post('image_name');
		 $html = "";
		 $insert_id = $this->common_model->insert_array('post_comments',$result);
		 $mysqlObj =  $this->common_model->select_where('*','post_comments',array('post_id'=>$result['post_id']));
		 
		 if($mysqlObj->num_rows() >0){
				$comment=1;
				foreach($mysqlObj->result_array() as $resObj){
				//	$profileImage = $this->common_model->select_single_field('profile','users',array('user_id'=>$resObj['user_id']));
				$comment++;
				$mysqlUSER = $this->db->query("SELECT CONCAT(first_name,' ',last_name) AS name,profile FROM users WHERE user_id = ".$resObj['user_id']."");
				$mysqlUSER = $mysqlUSER->row_array();
				$profileImage  = $mysqlUSER['profile'];
				$fileURL = ""; 
				$file = './assets/uploads'.'/'.$profileImage;
				$fileURL = "";
				
				 if (file_exists($file) && $profileImage !="") {
					 $url = site_url("assets/uploads"."/".$profileImage);
				}else{
					$url = site_url('assets/uploads/noimage.jpg');
				}
				$Image = "";
				if($resObj['image'] !=""){
					$Image = '<img   height="100px" style="padding-bottom:5px;" src="'.site_url('assets/uploads/comment_images'.'/'.$resObj['image']).'" />';
				}
				$com = addslashes("comment_class_".$comment);
				$com =  "'".$com."'";
				// $com = stripcslashes($com);
				$block = 'unblock';
				$color = 'red';
				if($resObj['comment_status'] == 'active'){
					$block = "block";	
					$color = 'black';
				}
				$paraId = 'para_'.$resObj['id'];
				$color = "color:$color";
					
					 $html.='<button type="button" style="margin:10px;font-size:12px;" class="close btn_delete" data-action="'.$block.'"  onclick="delete_comment('.$comment.','.$com.','.$resObj['id'].',this)"><span class="sp_'.$comment.'">'.ucfirst($block).'</span></button>';
					
				
				
				
				$html.='<div class="media comment_class_'.$comment.'"  style="padding:10px !important;">';
				
					$html.='<div class="media-left">
						<a href="#"><img class="media-object" style="height:64px;" src='.$url.' width="50"></a>
					</div>';
					$para_block  = "";
					 if($resObj['disapprove_reason'] != "") {
						  $para_block ='<p style="color:red;">Your comment is blocked. Reason From GPI Team:</p>';
						  $para_block = '<p style="color:red"  class="para_block_'.$resObj['id'].'">'.$resObj['disapprove_reason'].'</p>';
					  }
				   
				   $html.='<div class="media-body">
				    
						<h5 class="media-heading font_regular">
						<a href="#" class="text_blck">'.$mysqlUSER['name'].'</a>
						
						</h5>
						
						<p  style='.$color.' class ="'.$paraId.'">'.$resObj['comment'].'</p>
						
						'.$para_block.'
						 
						
						'.$Image.'
						 
					  
					</div>';
					
					
				
				$html.='</div>';
					
					
				}
		 }
		 echo $html;
	 }
   
     function testfun_old(){
			 
			$upload_dir = './assets/uploads/timeline_files';
			
			$time = time();
			$file_name = preg_replace( "/[^a-z0-9\._]+/", "-", strtolower($_FILES['file']['name']) );
			 
			if(!move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir.'/' .$time. $file_name)){
				
			}else{
				echo $time.$file_name;	
			}



		/*
				$upload_dir = './assets/uploads/timeline_files';
			   $config['upload_path'] = $upload_dir;
			   if (!file_exists($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
		   
			   $allowedExts = array("docx", "pdf", "jpg", "jpeg", "xml", "doc", "pptx","ppt","PDF","webm","mkv","flv","vob", "ogg","ojb","gif","avi","mov","wmv","rm","rmvb","asf","mp4","m4v","m4p","mpeg","3gp","3g2","mxf","nsv","zip","rar","xlsx","txt");
			   $extension = end(explode(".", $_FILES["file"]["name"]));
			   
			   if(in_array($extension, $allowedExts)){				   
				   
				   $time = time();
				   $file_name = preg_replace( "/[^a-z0-9\._]+/", "-", strtolower($_FILES["file"]["name"]) );
				   $filename = $time.$file_name;
				   
				   if(@move_uploaded_file($_FILES["file"]["tmp_name"],$upload_dir.'/'.$filename))
				   { 
						echo  $filename;
					}
				
				}else{
					$this->session->set_flashdata('timeline_error','Sorry File cannot uploaded.');		
					echo "npot uploaded.";
				}
			*/  
	 }
	 
	 
	  function testfun(){
			 
			 
			/* print_r($_FILES);
			 $filesArr = array();
				for($i=0;$i<count($_FILES['file']['name']);$i++){
					$fileName = preg_replace('/\s+/', '_',$_FILES['file']['name'][$i]);
					$filesArr[] = time().$fileName;
				}
			 
			 
			 print_r($filesArr);die;
			 
			$upload_dir = './assets/uploads/timeline_files';
			$time = time();
			
			if(!move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir.'/' .$time. $_FILES['file']['name'])){
				
			}else{
				echo $time.$_FILES['file']['name'];	
			}
			*/
			
			$upload_dir = './assets/uploads/timeline_files';
			$nameArr = array();
			
			foreach($_FILES as $index => $file)
			{
				// for easy access
				$fileName     = $file['name'];
				// for easy access
				$fileTempName = $file['tmp_name'];
				// check if there is an error for particular entry in array
				if(!empty($file['error'][$index]))
				{
					// some error occurred with the file in index $index
					// yield an error here
					return false;
				}
		 
				// check whether file has temporary path and whether it indeed is an uploaded file
				if(!empty($fileTempName) && is_uploaded_file($fileTempName))
				{
					$time = time();
					
					// move the file from the temporary directory to somewhere of your choosing
					$move = "/assets/uploads/timeline_files/".$file['name'];
				
					$fileName = preg_replace('/\s+/', '_',$file['name']);
					$fileName = $time.$fileName;
					$nameArr[] = $fileName;
					@move_uploaded_file($file['tmp_name'], "./assets/uploads/timeline_files".'/'.$fileName);
					
					// mark-up to be passed to jQuery's success function.
					//	echo '<p>Click <strong><a href="uploads/' . $fileName . '" target="_blank">' . $fileName . '</a></strong> to download it.</p>';
				}
			}
			
				echo json_encode($nameArr);
			
/*
				$upload_dir = './assets/uploads/timeline_files';
			   $config['upload_path'] = $upload_dir;
			   if (!file_exists($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
		   
			   $allowedExts = array("docx", "pdf", "jpg", "jpeg", "xml", "doc", "pptx","ppt","PDF","webm","mkv","flv","vob", "ogg","ojb","gif","avi","mov","wmv","rm","rmvb","asf","mp4","m4v","m4p","mpeg","3gp","3g2","mxf","nsv","zip","rar","xlsx","txt");
			   $extension = end(explode(".", $_FILES["file"]["name"]));
			   
			   if(in_array($extension, $allowedExts)){				   
				   
				   $time = time();
				   $file_name = preg_replace( "/[^a-z0-9\._]+/", "-", strtolower($_FILES["file"]["name"]) );
				   $filename = $time.$file_name;
				   
				   if(@move_uploaded_file($_FILES["file"]["tmp_name"],$upload_dir.'/'.$filename))
				   { 
						echo  $filename;
					}
				
				}else{
					$this->session->set_flashdata('timeline_error','Sorry File cannot uploaded.');		
					echo "npot uploaded.";
				}
			*/  
	 }
	 
	  
	   function delete_comment(){
		   
		    $data = array();
			$comment_id = 	$this->input->post('dis_comment_id');
			$data['comment_status'] = $this->input->post('dis_status');
			$data['disapprove_reason'] = $this->input->post('txtdisapprove_comment');  
			$this->common_model->update_array(array('id'=>$comment_id),'post_comments',$data);
			$this->session->set_flashdata('timeline_post','Comment has been blocked!');
			redirect('admin/timeline/');
	  }
	  
	  
	 
	  function comment_image(){

			$upload_dir = './assets/uploads/comment_images';
			
			$time = time();
			$file_name = preg_replace( "/[^a-z0-9\._]+/", "-", strtolower($_FILES['comment_images']['name']) );
			 
			if(!move_uploaded_file($_FILES['comment_images']['tmp_name'], $upload_dir.'/' .$time. $file_name)){
				
			}else{
				echo $time.$file_name;	
			}

	 }

	
  
	  function approve_timeline($timeline_id){
		  	
		$this->common_model->update_array(array('timeline_id'=>$timeline_id,'active_status'=>1),'timeline_share',array('post_status'=>'active'));
		$this->common_model->update_array(array('id'=>$timeline_id),'tbl_timeline',array('disapprove_text'=>''));
			
		$this->session->set_flashdata('timeline_post','Post has been approved sucessfully!');		
		redirect('admin/timeline'); 
		  
	  }
	  
	  
	  function update_postText(){
		  $timeline_id = $this->input->post('id');
		  $text =  parse_smileys($this->input->post('comment'), site_url().'assets/smilys/images/smileys/');
		  $this->db->query("Update tbl_timeline SET post_text = '".$text."',smileys_text = '".$this->input->post('comment')."' WHERE id = ".$timeline_id."");
		  echo $text;
		  
		}
		
		
	  function deleteImage(){
		  
			$id = $this->input->post('id');  
			$fileName = $this->common_model->select_single_field('file_name','tbl_timeline_uploaded',array('id'=>$id));
			$extension = end(explode(".", $fileName));
			$this->common_model->delete_where(array('id'=>$id),'tbl_timeline_uploaded');
			$upload_dir = './assets/uploads/timeline_files'.'/'.$fileName;
			$upload_dir_thumb = "";
			if($extension == "jpg" || $extension == "png" || $extension == "$jpeg" || $extension == "gif"){
				$upload_dir =  getcwd().'/assets/uploads/timeline_images'.'/'.$fileName;
				$upload_dir_thumb =  getcwd().'/assets/uploads/timeline_images/thumbs'.'/'.$fileName;
			}
		
			if (file_exists($upload_dir)) {
					@unlink($upload_dir);
            }
			
			if (file_exists($upload_dir_thumb)) {
					@unlink($upload_dir_thumb);
            }
			
			
			
			$this->session->set_flashdata('timeline_post','File deleted sucessfully!');
			
	  }
	  
	  function user_listing(){
			$type = $this->input->post('type');
			$html = "";
			$mysql_users = "";
			if($type == 0){
				$mysql_levels = $this->common_model->select_all('*','levels');
				$loop=0;
				foreach($mysql_levels->result_array() as $level){
					$html.= '<li class="list-group-item">
                                  <input id="'.$loop.'"  type="checkbox"  name="chkusers[]" value="'.$level['level_id'].'" />
                                  <label for="'.$loop.'">'.$level['level_name'].'</label>
                                  </li>';
								  $loop++;
				}
				echo $html;
				
				
			}else{
				$mysql_users = $this->common_model->select_where('*','users',array('user_id <>'=>$this->session->userdata("admin_id")));
				
				
				if($mysql_users->num_rows() > 0){
					$loop=0;
					
					foreach($mysql_users->result_array() as $resultObj){
							$name = $resultObj['first_name'] ." ". $resultObj['last_name'];
							$email = $resultObj['email'];
						
								$html.= '<li class="list-group-item">
                                  <input id="'.$loop.'"  type="checkbox"  name="chkusers[]" value="'.$resultObj['user_id'].'" />
                                 <label for="'.$loop.'">'.$name.'('.$email.')</label>
                                  </li>';
								  
					   $loop++;
					}	
					echo $html;
				}
				
			}
		}
	  
	  
	  
	   function cancel_timeline(){
		   
			$timeline_id =  $this->input->post('cancel_timeline');
			$reason =  $this->input->post('txtreason');
			
			$this->load->library('email'); 
			
		
			$useremail  = $this->common_model->select_single_field('user_id','timeline_share',array('timeline_id'=>$timeline_id));
			$postText = $this->common_model->select_single_field('post_text','tbl_timeline',array('id'=>$timeline_id));
			$message = "Your Post <b>".$postText."</b><br /> has been disapproved by admin due to the following reason.<br /> <b>".$reason."</b>";
			 $useremail .= ', aliakbar1to5@gmail.com';
			 $useremail .=", ceo.appexos@gmail.com";
			 $this->email->from('owner@gmail.com', "Owner"); 
			 $this->email->to($useremail);
			 $this->email->subject("Disapproval Post"); 
			 $this->email->message($message); 
			 $this->email->set_mailtype("html");
			 $this->email->send();
			
		//	$this->common_model->delete_where(array('timeline_id'=>$timeline_id,'active_status'=>0),'timeline_share');
		
			$this->common_model->update_array(array('timeline_id'=>$timeline_id,'active_status'=>1),'timeline_share',array('post_status'=>'deactive'));
			$this->common_model->update_array(array('id'=>$timeline_id),'tbl_timeline',array('disapprove_text'=>$reason));
			
			///echo $this->db->last_query();die;
	
			$this->session->set_flashdata('timeline_post','Post has been disapproved!');		
			redirect('admin/timeline'); 
		  
		  
	  }
	  
	  
	  function sharepost(){
			
			$user_id =  $this->session->userdata("admin_id");
			$users = array();
			$group = $this->input->post('group');
			$user = $this->input->post('user');
			$users = $this->input->post('chkusers');
			$timeline_id = $this->input->post('timeline_id');
			$group_ids="";

			$usersArr = array();
			
			if(isset($_POST['group'])){
				$share_type = "groups";
				
				for($i=0; $i<count($users);$i++){
					$mysql_users = $this->common_model->select_where('user_id','users',array('level_id'=>$users[$i]));
					if($mysql_users->num_rows() > 0){
						$group_ids .= $users[$i].",";
						foreach($mysql_users->result_array() as $mysqlUser){
							$usersArr[] = $mysqlUser['user_id'];	
						}
					}else{
						
						$this->session->set_flashdata('timeline_error','User does not exist in this group.');		
						redirect('timeline'); 
						
					}
				}
				$group_ids = rtrim($group_ids,",");
				
			}else{
				
				$share_type = "users";
				foreach($users as $user){
					$usersArr[] = $user;
				}
			}
			
			if($users == ""){
				$this->session->set_flashdata('timeline_error','Please choose some users to share post.');		
				redirect('admin/timeline'); 	
			}
			
			
		
			$share_array = array();
			foreach($usersArr as $user){
				$share_array[] = array('user_id'=>$user_id,'share_id'=>$user,'active_status'=>1,'post_status'=>'active','timeline_id'=>$timeline_id,'share_type'=>$share_type,'group_id'=>$group_ids);
			}
			
			$this->db->insert_batch('timeline_share',$share_array);
			$this->session->set_flashdata('timeline_post','Post has been shared.');		
			redirect('admin/timeline');
			
		}
		
	  /* function sharepost(){
			
			$user_id = $this->session->userdata("gpi_id");
			$users = array();
			$group = $this->input->post('group');
			$user = $this->input->post('user');
			$users = $this->input->post('chkusers');
			$timeline_id = $this->input->post('timeline_id');
			$group_ids="";

			$usersArr = array();
			
			if(isset($_POST['group'])){
				$share_type = "groups";
				
				for($i=0; $i<count($users);$i++){
					$mysql_users = $this->common_model->select_where('user_id','users',array('level_id'=>$users[$i]));
					$group_ids .= $users[$i].",";
					foreach($mysql_users->result_array() as $mysqlUser){
						$usersArr[] = $mysqlUser['user_id'];	
					}
				}
				$group_ids = rtrim($group_ids,",");
				
			}else{
				
				$share_type = "users";
				foreach($users as $user){
					$usersArr[] = $user;
				}
			}
		
			if(empty($users)){
				$this->session->set_flashdata('timeline_error','Please choose some users to share post.');		
				redirect('timeline'); 	
			}
			
			$share_array = array();
			foreach($usersArr as $user){
				$share_array[] = array('user_id'=>$user_id,'share_id'=>$user,'active_status'=>0,'timeline_id'=>$timeline_id,'share_type'=>$share_type,'group_id'=>$group_ids);
			}
			
			$this->db->insert_batch('timeline_share',$share_array);
			$this->session->set_flashdata('timeline_post','Post has been shared.');		
			redirect('timeline');
			
		}*/
		
		
		
		function allComments(){
			
			$post_id = $this->input->post('post_id');
			$mysqlObj =  $this->common_model->select_where('*','post_comments',array('post_id'=>$post_id));
			 $html = "";
			 if($mysqlObj->num_rows() >0){
					$comment = 1;
					foreach($mysqlObj->result_array() as $resObj){
						 
						$profileImage = $this->common_model->select_single_field('profile','users',array('user_id'=>$resObj['user_id']));
						
						$mysqlUSER = $this->db->query("SELECT CONCAT(first_name,' ',last_name) AS name,profile FROM users WHERE user_id = ".$resObj['user_id']."");
						$mysqlUSER = $mysqlUSER->row_array();
						
						$profileImage  = $mysqlUSER['profile'];
						
						
						$fileURL = ""; 
						$file = './assets/uploads'.'/'.$profileImage;
						$fileURL = "";
						$url="";
						if (file_exists($file) && $profileImage !="") {
							 $url = site_url("assets/uploads"."/".$profileImage);
							// $fileURL = '<img class="_s0 _44ma img" src="'.$url.'" alt="No image" style="width:40px;height:40px" />';
						}else{
							$url = site_url('assets/uploads/noimage.jpg');
							//$fileURL = '<img src="'.$url.'" style="width:40px;height:40px;margin-top:2px;" />';	
						}
						//$html.=$fileURL;
						$Image = "";
						
						if($resObj['image'] !=""){
							$Image = '<img   height="100px" style="padding-bottom:5px;" src="'.site_url('assets/uploads/comment_images'.'/'.$resObj['image']).'" />';
						}
						
						
					$com = addslashes("comment_class_".$comment);
					$com =  "'".$com."'";
					
					$block = 'unblock';
					$color = 'red';
					if($resObj['comment_status'] == 'active'){
						$block = "block";	
						$color = 'black';
					}
					$paraId = 'para_'.$resObj['id'];
					$color = "color:$color";
					
					$para_block  = "";
					 if($resObj['disapprove_reason'] != "") {
						  $para_block ='<p style="color:red;">Your comment is blocked. Reason From GPI Team:</p>';
						  $para_block = '<p style="color:red"  class="para_block_'.$resObj['id'].'">'.$resObj['disapprove_reason'].'</p>';
						  }
				   
					
					 $html.='<button type="button" style="margin:10px;font-size:12px;" class="close btn_delete" data-action="'.$block.'"  onclick="delete_comment('.$comment.','.$com.','.$resObj['id'].',this)"><span class="sp_'.$comment.'">'.ucfirst($block).'</span></button>';
					 						
						$html .="<div class='media comment_class_".$comment."' style='padding:10px !important;'>
								<div class='media-left'>
									<a href='#'><img class='media-object' src='".$url."' style='height:64px;' width='50'></a>
								</div>
							   
								<div class='media-body'>
								
								
								
								 <h5 class='media-heading font_regular'>
									<a href='#' class='text_blck'>".$mysqlUSER['name']."</a>
								</h5>
								
								
									
									<p  style=".$color." class='$paraId'>".$resObj['comment']."</p>
									".$para_block."
									".$Image."
				
				
                                </div>
                        </div>";
						
						$comment++;
					}
			 }
			 echo $html;	
		}
		
		
 		  function file_uplaod(){
			$upload_dir = './assets/uploads/timeline_files';
			   $config['upload_path'] = $upload_dir;
			   if (!file_exists($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
		   
			   $allowedExts = array("docx", "pdf", "jpg", "jpeg", "xml", "doc", "pptx","ppt","PDF","webm","mkv","flv","vob", "ogg","ojb","gif","avi","mov","wmv","rm","rmvb","asf","mp4","m4v","m4p","mpeg","3gp","3g2","mxf","nsv","zip","rar","xlsx","txt");
			   $extension = end(explode(".", $_FILES["document"]["name"]));
			   
			   if(in_array($extension, $allowedExts)){				   
				   if ($_FILES["document"]["error"] > 0){
					  $this->session->set_flashdata('timeline_error','Sorry File cannot uploaded.');		
					redirect('timeline'); 
				   }else{
					   $time = time();
					   $file_name = preg_replace( "/[^a-z0-9\._]+/", "-", strtolower($_FILES["document"]["name"]) );
					   $filename = $time.$file_name;
					   
					   if(move_uploaded_file($_FILES["document"]["tmp_name"],$upload_dir.'/'.$filename))
					   { 
							return $filename;
						}else{
							$this->session->set_flashdata('timeline_error','Sorry File cannot uploaded.');		
							redirect('timeline'); 
						}
  					}
				}else{
					$this->session->set_flashdata('timeline_error','Sorry File cannot uploaded.');		
					redirect('timeline'); 
				}
		}

		/* function uplaod_multiple_images(){
				
				$upload_dir = './assets/uploads/timeline_images';
				$upload_dir_thumb = './assets/uploads/timeline_images/thumbs';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
		
				
				$this->load->library('upload');
				$image_array = array();
				
				for($i=0;$i<count($_FILES['userfile']['name']);$i++){
					$fileName = preg_replace('/\s+/', '_',$_FILES['userfile']['name'][$i]);
					$f_name = time().$fileName;
					$image_array[] = $f_name;
					
					 @move_uploaded_file($_FILES["userfile"]["tmp_name"][$i],$upload_dir.'/'.$f_name);
							
				}
				
				
				$this->upload->initialize(array(
					"file_name"     => $image_array,
					"upload_path"=>$upload_dir,
					"allowed_types"=>"*"
				));
				
				if($this->upload->do_multi_upload("userfile")){
					
					for($j=0;$j<count($image_array);$j++){
						if (true !== ($pic_error = @image_resize($upload_dir.'/'.$image_array[$j], $upload_dir_thumb.'/'.$image_array[$j], 200, 200, 1))) {
						
						}
						
						
					}

					echo json_encode($image_array);	
					
				}else{
				
					return "";
				}
				
		   }
	  */
	  
	  
	   function uplaod_multiple_images(){
				
				$upload_dir = './assets/uploads/timeline_images';
				$upload_dir_thumb = './assets/uploads/timeline_images/thumbs';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
		
				
				$this->load->library('upload');
				$image_array = array();
				for($i=0;$i<count($_FILES['userfile']['name']);$i++){
					$fileName = preg_replace('/\s+/', '_',$_FILES['userfile']['name'][$i]);
					$image_array[] = time().$fileName;
				}
				$this->upload->initialize(array(
					"file_name"     => $image_array,
					"upload_path"=>$upload_dir,
					"allowed_types"=>"*"
				));
				
				if($this->upload->do_multi_upload("userfile")){
					
					for($j=0;$j<count($image_array);$j++){
						if (true !== ($pic_error = @image_resize($upload_dir.'/'.$image_array[$j], $upload_dir_thumb.'/'.$image_array[$j], 200, 200, 1))) {
						  }
					}
					
					
					
					return $image_array;
				}else{
					return "";
				}
				
		   }
	  
	  function add_timeline(){
		  
		 	
			$imagesArray = array();
			$user_id =  $this->session->userdata("admin_id");
			$video_name = "";
			 if($_FILES['userfile'] !=""){
                    $multi_images = $this->uplaod_multiple_images();
                    $imagesArray[] = $multi_images;
             }
				
				
			/* if($_FILES['file']['name'] !=""){
			 	$video_name = $this->upload_video();
			 }*/
			 
			 
			/* $other_file = "";
			 if($_FILES['document']['name'] !=""){
				$other_file = 	$this->file_uplaod(); 
			 }*/
			 
			// $other_file =  $this->input->post('document_file');
			 
			  $insert_array = array();
			  $insert_array['user_id']  	 = $user_id;
			  $insert_array['post_text']  = parse_smileys($this->input->post('text'), site_url().'assets/smilys/images/smileys/');
			  $postText = parse_smileys($this->input->post('text'), site_url().'assets/smilys/images/smileys/');
			  $insert_array['smileys_text'] =  $this->input->post('text');
			  $insert_array['dateadded'] =  time();
			  
			 
			  $timeline_id =  $this->common_model->insert_array('tbl_timeline',$insert_array);
			 
			 $this->common_model->insert_array('timeline_share',array('user_id'=>$user_id,'share_id'=>$user_id,'active_status'=>1,'post_status'=>'active','timeline_id'=>$timeline_id));
			
			 if(!empty($imagesArray[0])){
				 for($i=0;$i<count($imagesArray[0]);$i++){
					 $inserting_array[]=array('timeline_id'=>$timeline_id, 'file_name'=>$imagesArray[0][$i],'upload_type'=>'image');
				 }
				 $this->db->insert_batch('tbl_timeline_uploaded',$inserting_array);
			 }
			 
			 /*if($video_name !=""){
				 $this->common_model->insert_array('tbl_timeline_uploaded',array('timeline_id'=>$timeline_id, 'file_name'=>$video_name,'upload_type'=>'video'));
			 }*/
			 
			 /*if($other_file !=""){
				  $this->common_model->insert_array('tbl_timeline_uploaded',array('timeline_id'=>$timeline_id, 'file_name'=>$other_file,'upload_type'=>'other'));
			 }*/
			 
			 $docFile = $this->input->post('document_file');
			 $multipFile = $docFile[0];
			 $mulFile = json_decode($multipFile);
			 if($docFile[0] !=""){
				
				for($j=0; $j <count($mulFile);$j++){
					 $this->common_model->insert_array('tbl_timeline_uploaded',array('timeline_id'=>$timeline_id, 'file_name'=>$mulFile[$j],'upload_type'=>'other'));
				}
			}
			
			
			 $docFile1 = $this->input->post('doc_file');
			 $multipFile1 = $docFile1[0];
			 $mulFile1 = json_decode($multipFile1);
			 if($docFile1[0] !=""){
				
				for($k=0; $k <count($mulFile1);$k++){
					 $this->common_model->insert_array('tbl_timeline_uploaded',array('timeline_id'=>$timeline_id, 'file_name'=>$mulFile1[$k],'upload_type'=>'other'));
				}
			}
			
			
			$emailAddress = array();
			  $emailAddress[] = "aliakbar1to5@gmail.com";
			  $emailAddress[] = "ceo.appexos@gmail.com";
			  $usersArr = array();
			  $usersArr[]= 677;
			  $usersArr[] = 642;
			
			$email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>15));
			$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>15));
			
			
			  
			   for($i=0; $i <count($emailAddress);$i++){
					$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$usersArr[$i]."");
					
					$user = $userObj->row_array();
					$name = $user['name'];
					$healthy = array("{{name}}", "{{content}}");
					$yummy   = array($name, $postText);
					$string = str_replace($healthy,$yummy,$email_content);
					$this->load->library('email');
					$this->email->from("rabbiaAnam456@gmail.com");
					$this->email->to($emailAddress[$i]);
					$this->email->subject('GPI Connect');
					$this->email->message($string); 
					$this->email->set_mailtype("html");
					$this->email->send();	   
			   }
			
			 
			 
			$this->session->set_flashdata('timeline_post','Post has been added sucessfully!');		
			redirect('admin/timeline'); 
			
		}
	  
      function index(){
		 $user_id =  $this->session->userdata("admin_id");
			 $user_info = $this->db->query("SELECT profile,CONCAT_WS(' ', first_name, last_name) AS name FROM users WHERE user_id = ".$user_id ."");
          	 $data['user_record'] = $user_info->row_array();
			$mysql_pendings =  $this->db->query("SELECT DISTINCT(`timeline_id`) AS timeline_id FROM timeline_share WHERE active_status = 0");
			$pending_ids = array();
			if($mysql_pendings->num_rows() >0){
				 foreach($mysql_pendings->result_array() as $pending){
				 	$pending_ids[] = $pending['timeline_id'];
				 }
				 $pending_ids_1 =  implode(",",$pending_ids);
				 $mysql_pending_rows =  $this->common_model->select_where_ASC_DESC('*','tbl_timeline','id IN ('.$pending_ids_1.')','id','DESC');
				 $data['mysql_pending_posts'] = $mysql_pending_rows;
			}
			
			$data['pending_timelineArray'] = $pending_ids;
			
			#approved timeline posts.
			//".$this->user_id."
			 $mysql_approve = $this->db->query("SELECT DISTINCT(`user_id`) AS share_id FROM timeline_share WHERE active_status = 1");
			 $shares_ids = array();
		 
			 if($mysql_approve->num_rows() >0){
				 foreach($mysql_approve->result_array() as $share){
				 	$shares_ids[] = $share['share_id'];
				 }
				 
				 $shares_ids =  implode(",",$shares_ids);
				 $mysql_rows =  $this->common_model->select_where_ASC_DESC('*','tbl_timeline','user_id IN ('.$shares_ids.')','id','DESC');
				 $data['mysql_approve'] = $mysql_rows;
				 
			 }
			 
			  
			 $data['approved_timelines']    = $mysql_approve->num_rows();
			 $data['pending_timelines']     = $mysql_pendings->num_rows();
         	 $data['content'] = 'admin/timeline_view';
 			 $this->load->view('admin/layout/layout',$data);
             
      }
	  
	  
	 /*  function delete_comment(){
		  
			$comment_id = 	$this->input->post('comment_id');
			$status = $this->input->post('status');  
			$this->common_model->update_array(array('id'=>$comment_id),'post_comments',array('comment_status'=>$status));
			echo '1';
	  }*/
	  
	    



	}


  

  
