<?php
  class Timeline extends CI_Controller
  { 
		 var $user_id;
	    function __construct() {
    
		   parent::__construct();
		  $this->user_id =  $this->session->userdata("gpi_id");
		  ini_set('error_reporting', E_ALL);
		  ini_set('display_errors', 'On');  //On or Off
		  $this->load->helper('smiley');
	  	  $this->load->library('table');
		 
		  if($this->session->userdata("gpi_id") == "") {
			 $this->load->model('common_model');
		  }

	   }
	   
	   
	    function delete_comment(){
			$comment_id = 	$this->input->post('comment_id');
			$status = $this->input->post('status');  
			$this->common_model->update_array(array('id'=>$comment_id),'post_comments',array('comment_status'=>$status));
			echo $this->db->last_query();
			echo '1';
	  }
	  
	  
	  public function update_comment(){
		  
		$comment_id = 	$this->input->post('comment_id');
		$comment = $this->input->post('comment');
		$comment =parse_smileys($this->input->post('comment'), site_url().'assets/smilys/images/smileys/');
		$smileys_text = $this->input->post('comment');
		$this->common_model->update_array(array('id'=>$comment_id),'post_comments',array('comment'=>$comment,'smileys_text'=>$smileys_text));
		//echo $this->db->last_query();
		
		echo $comment;
		  
	  }
	  
	  
	    public function deleting_comment(){
		  
			$comment_id = 	$this->input->post('comment_id');
			$this->common_model->delete_where(array('id'=>$comment_id),'post_comments');
			echo "1";
	  }
	  
	  
	  
        public function index(){
			
			
			 //$user_info = $this->common_model->select_where("profile,CONCAT_WS(' ', first_name, last_name) AS name",'users',array('user_id'=>$this->user_id));
			 $user_info = $this->db->query("SELECT profile,CONCAT_WS(' ', first_name, last_name) AS name FROM users WHERE user_id = ".$this->user_id."");
          	 $data['user_record'] = $user_info->row_array();
			 
			
			 
			
			 $mysql_share = $this->db->query("SELECT DISTINCT(`timeline_id`) AS timeline_id FROM timeline_share WHERE active_status = 1 AND share_id = ".$this->user_id." || share_id = 1");
			 $timeline_id = array();
		 
			 if($mysql_share->num_rows() >0){
				 foreach($mysql_share->result_array() as $share){
				 	$timeline_id[] = $share['timeline_id'];	
				 }
				 $timeline_id =  implode(",",$timeline_id);
				 $mysql_rows =  $this->common_model->select_where_ASC_DESC('*','tbl_timeline','id IN ('.$timeline_id.')','id','DESC');
				 $data['mysql_timeline'] = $mysql_rows;
				 
			 }
			
			
			$data['total_records'] = $mysql_share->num_rows();
         	 $data['content']          = 'timeline_view';
		     $this->load->view('layout/layoutuser',$data);

        }
		
		
		function allComments(){
			
			
			$post_id = $this->input->post('post_id');
			$mysqlObj =  $this->common_model->select_where('*','post_comments',array('post_id'=>$post_id));
			 $html = "";
			 if($mysqlObj->num_rows() >0){
					$comment = 1;
					foreach($mysqlObj->result_array() as $resObj){
						
						   $mysqlUSER = $this->db->query("SELECT CONCAT(first_name,' ',last_name) AS name,profile FROM users WHERE user_id = ".$resObj['user_id'].""); 	  
						$html.='<div  class="media comment_class_'.$comment.'" style="padding:10px !important;">';
						$mysqlUSER = $mysqlUSER->row_array();
                        $profileImage  = $mysqlUSER['profile'];
						$fileURL = ""; 
						$file = './assets/uploads'.'/'.$profileImage;
						$fileURL = "";
						if (file_exists($file) && $profileImage !="") {
							 $url = site_url("assets/uploads"."/".$profileImage);
							 $fileURL = '<img class="_s0 _44ma img" src="'.$url.'" alt="No image" style="width:40px;height:40px" />';
						}else{
							$url = site_url('assets/uploads/noimage.jpg');
							$fileURL = '<img src="'.$url.'" style="width:40px;height:40px;margin-top:2px;" />';	
						}
						
						$Image = "";
						if($resObj['image'] !=""){
							$Image = '<img   height="100px" style="padding-bottom:5px;" src="'.site_url('assets/uploads/comment_images'.'/'.$resObj['image']).'" /><div class="clear"></div>';
						}
						
						
						$block = 'unblock';
					$color = 'red';
					if($resObj['comment_status'] == 'active'){
						$block = "block";	
						$color = 'black';
					}
					
					 if($block == 'unblock') {
                          if($resObj['user_id'] == $this->user_id){ 
							 $html.=$fileURL;
						}
						
					}else{
						 $html.=$fileURL;
					}
					if($this->user_id == $resObj['user_id']) {
						$html.='<div  style="float:right">
                        <a href="javascript:void(0)" onclick="update_comment('.$resObj['id'].')"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="javascript:void(0)" onclick="deleting_comment('.$resObj['id'].','.$comment.')"><span class="glyphicon glyphicon-trash"></span></a>      
                    </div>';
					 } 
						
						
						$com = addslashes("comment_class_".$comment);
					    $com =  "'".$com."'";
					if($block == 'unblock') { 
                       if($resObj['user_id'] == $this->user_id){ 

						$html .= '<h5 class="media-heading font_regular" style="position:absolute;margin-top:-35px;left:66px;"> <a href="#" class="text_blck"> '.$mysqlUSER['name'].'</a> </h5>';
						$paraId = 'para_'.$comment;
						$color = "color:$color";
						$html.="<p  style=".$color." class='$paraId'>".$resObj['comment']."</p>";
						$para_block  = "";
						 if($resObj['disapprove_reason'] != "") {
							  $para_block ='<p style="color:red;">Your comment is blocked. Reason From GPI Team:</p>';
							  $para_block .= '<p style="color:red"  class="para_block_'.$resObj['id'].'">'.$resObj['disapprove_reason'].'</p>';
						  }
						  $html.=$para_block;
						  
					   }
					  }else{
						  
						$html .= '<h5 class="media-heading font_regular" style="position:absolute;margin-top:-35px;left:66px;"> <a href="#" class="text_blck"> '.$mysqlUSER['name'].'</a> </h5>';
						$paraId = 'para_'.$comment;
						$color = "color:$color";
						$html.="<p  style=".$color." class='$paraId'>".$resObj['comment']."</p>";
						$para_block  = "";
						 if($resObj['disapprove_reason'] != "") {
							  $para_block ='<p style="color:red;">Your comment is blocked. Reason From GPI Team:</p>';
							  $para_block .= '<p style="color:red"  class="para_block_'.$resObj['id'].'">'.$resObj['disapprove_reason'].'</p>';
						  }
						  $html.=$para_block;
						  
						}
						
						$html.='<input   type="text" value="'.$resObj['smileys_text'].'" style="width:90%;display:none;margin-bottom:5px" class="form-control comment_'.$resObj['id'].'" onkeypress="update_function(event,'.$comment.',this)" />';
						$html.='<a href="javascript:void(0)" class="cancel_'.$resObj['id'].'" onclick="cancel_edit_fun('.$resObj['id'].')" style="float:right;margin-top:-20px;display:none;">Cencel</a>';
						
						$html.=$Image;
						
						
						 
						
						$html.='</div>';	
						$comment++;
					}
			 }
			 echo $html;	
		}
		
		
		
		 function update_postText(){
		  $timeline_id = $this->input->post('id');
		  $text =  parse_smileys($this->input->post('comment'), site_url().'assets/smilys/images/smileys/');
		  $this->db->query("Update tbl_timeline SET post_text = '".$text."',smileys_text = '".$this->input->post('comment')."' WHERE id = ".$timeline_id."");
		  echo $text;
		  
		}
		
		
		
		function postComment(){
		 
		 $result['comment'] = $str = parse_smileys($this->input->post('comment'), site_url().'assets/smilys/images/smileys/');
		 $result['smileys_text'] = $this->input->post('comment');
		 $result['post_id'] = $this->input->post('post_id');
		 $image_url = $this->input->post('image_url');
		 $result['user_id'] = $this->session->userdata("gpi_id");
		 $loop_id = $this->input->post('loop_id');
		 $result['image'] = $this->input->post('image_name');
		 $html = "";
		 $insert_id = $this->common_model->insert_array('post_comments',$result);
		 $mysqlObj =  $this->common_model->select_where('*','post_comments',array('post_id'=>$result['post_id']));
		 
		 if($mysqlObj->num_rows() >0){
				$comment = 1;
				foreach($mysqlObj->result_array() as $resObj){
					//$profileImage = $this->common_model->select_single_field('profile','users',array('user_id'=>$resObj['user_id']));
					
					 $mysqlUSER = $this->db->query("SELECT CONCAT(first_name,' ',last_name) AS name,profile FROM users WHERE user_id = ".$resObj['user_id']."");
						 
					  $mysqlUSER = $mysqlUSER->row_array();
					  $profileImage  = $mysqlUSER['profile'];
					
					$fileURL = ""; 
					$file = './assets/uploads'.'/'.$profileImage;
					$fileURL = "";
					if (file_exists($file) && $profileImage !="") {
						 $url = site_url("assets/uploads"."/".$profileImage);
						 $fileURL = '<img class="_s0 _44ma img" src="'.$url.'" alt="No image" style="width:40px;height:40px" />';
					}else{
						$url = site_url('assets/uploads/noimage.jpg');
						$fileURL = '<img src="'.$url.'" style="width:40px;height:40px;margin-top:2px;" />';	
					}
					
					$html.='<div  class="media comment_class_'.$comment.'" style="padding:10px !important;">';
					
				$Image = "";
				if($resObj['image'] !=""){
					$Image = '<img   height="100px" style="padding-bottom:5px;" src="'.site_url('assets/uploads/comment_images'.'/'.$resObj['image']).'" /><div class="clear"></div>';
				}
				
				$block = 'unblock';
					$color = 'red';
					if($resObj['comment_status'] == 'active'){
						$block = "block";	
						$color = 'black';
					}
					
					 if($block == 'unblock') {
                         if($resObj['user_id'] == $this->user_id){ 
							 $html.=$fileURL;
						}
						
					}else{
						 $html.=$fileURL;
					}
					 if($this->user_id == $resObj['user_id']) {
						 $html.='<div  style="float:right">
							<a href="javascript:void(0)" onclick="update_comment('.$resObj['id'].')"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="javascript:void(0)" onclick="deleting_comment('.$resObj['id'].','.$comment.')"><span class="glyphicon glyphicon-trash"></span></a>      
						</div>';
					 }
					 
					 $com = addslashes("comment_class_".$comment);
					 $com =  "'".$com."'";
					// $com = stripcslashes($com);
					
					/* $html.='<button type="button" style="margin:10px;font-size:12px;" class="close btn_delete" data-action="'.$block.'"  onclick="delete_comment('.$resObj['id'].','.$com.','.$comment.',this)"><span class="sp_'.$comment.'">'.ucfirst($block).'</span></button>';
					*/
					if($block == 'unblock') { 
                        if($resObj['user_id'] == $this->user_id){  
					
					$html.= '<h5 class="media-heading font_regular" style="position:absolute;margin-top:-36px;margin-left:50px;"><a href="#" class="text_blck">'.$mysqlUSER['name'].'</a> </h5>';
					$paraId = 'para_'.$comment;
					$color = "color:$color";
					$html.="<p style=".$color." class='$paraId'>".$resObj['comment']."</p>";
					$para_block  = "";
						 if($resObj['disapprove_reason'] != "") {
							  $para_block ='<p style="color:red;">Your comment is blocked. Reason From GPI Team:</p>';
							  $para_block .= '<p style="color:red"  class="para_block_'.$resObj['id'].'">'.$resObj['disapprove_reason'].'</p>';
						  }
						  $html.=$para_block;
					  }}else{
						  
						  $html.= '<h5 class="media-heading font_regular" style="position:absolute;margin-top:-36px;margin-left:50px;"><a href="#" class="text_blck">'.$mysqlUSER['name'].'</a> </h5>';
					$paraId = 'para_'.$comment;
					$color = "color:$color";
					$html.="<p style=".$color." class='$paraId'>".$resObj['comment']."</p>";
					$para_block  = "";
						 if($resObj['disapprove_reason'] != "") {
							  $para_block ='<p style="color:red;">Your comment is blocked. Reason From GPI Team:</p>';
							  $para_block .= '<p style="color:red"  class="para_block_'.$resObj['id'].'">'.$resObj['disapprove_reason'].'</p>';
						  }
						  $html.=$para_block;
						  
					  }
						
						
					$html.='<input   type="text" value="'.$resObj['smileys_text'].'" style="width:90%;display:none;margin-bottom:5px" class="form-control comment_'.$resObj['id'].'" onkeypress="update_function(event,'.$comment.',this)" />';
					$html.='<a href="javascript:void(0)" class="cancel_'.$resObj['id'].'" onclick="cancel_edit_fun('.$resObj['id'].')" style="float:right;margin-top:-20px;display:none;">Cencel</a>';
					$html.=$Image;
					$html.='</div>';
					$comment++;
				}
		 }
		 echo $html;
	 }
	 
	   function comment_image(){

			$upload_dir = './assets/uploads/comment_images';
			
			$time = time();
			$file_name = preg_replace( "/[^a-z0-9\._]+/", "", strtolower($_FILES['comment_images']['name']) );
			
			if(!move_uploaded_file($_FILES['comment_images']['tmp_name'], $upload_dir.'/' .$time. $file_name)){
				
			}else{
				echo $time.$file_name;	
			}

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
	 
	 
		
		 function sharepost(){
			
			$user_id = $this->session->userdata("gpi_id");
			$users = array();
			$group = $this->input->post('group');
			$user = $this->input->post('user');
			$users = $this->input->post('chkusers');
			$timeline_id = $this->input->post('timeline_id');
			
			//print_r($_POST);die;
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
		
			if(empty($users)){
				$this->session->set_flashdata('timeline_error','Please choose some users to share post.');		
				redirect('timeline'); 	
			}
			
			$share_array = array();
			foreach($usersArr as $user){
				$share_array[] = array('user_id'=>$user_id,'share_id'=>$user,'active_status'=>1,'timeline_id'=>$timeline_id,'share_type'=>$share_type,'group_id'=>$group_ids);
			}
			
			$this->db->insert_batch('timeline_share',$share_array);
			
			$this->session->set_flashdata('timeline_post','Post has been shared.');		
			redirect('timeline');
			
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
				$mysql_users = $this->common_model->select_where('*','users',array('user_id <>'=>$this->user_id,'user_id <>'=>1));
				
				
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
		
		
		function add_timeline(){
			
			/* if(@mail("aliakbar1to5@gmail.com","dubsdf","amc")){
				 echo "YYY";die;
				}*/
			
			$imagesArray = array();
			$video_name = "";
			
			 if($_FILES['userfile']['name'][0] !=""){
                    //$multi_images = $this->uplaod_multiple_images();
					
					
					extract($_POST);
					$error=array();
					
					$upload_dir = './assets/uploads/timeline_images';
					$upload_dir_thumb = './assets/uploads/timeline_images/thumbs';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir, 0777, true);
					}
					$image_array = array();
					foreach($_FILES["userfile"]["tmp_name"] as $key=>$tmp_name)
							{
								$time = time();
								$fileName = preg_replace('/\s+/', '_',$_FILES["userfile"]["name"][$key]);
								
								$file_name=$time.$fileName;
								$file_tmp=$_FILES["userfile"]["tmp_name"][$key];
								
								$ext=pathinfo($file_name,PATHINFO_EXTENSION);
								$image_array[] = $file_name;
								move_uploaded_file($_FILES["userfile"]["tmp_name"][$key],$upload_dir."/".$file_name);
								@image_resize($upload_dir.'/'.$file_name, $upload_dir_thumb.'/'.$file_name, 200, 200, 1);
								
						   } 
						
					
                    $imagesArray[] = $image_array;
             }
				
			
			
		
			
			
				
			/* if($_FILES['file']['name'] !=""){
				
			 	$video_name = $this->upload_video();
			 }*/
			 
			 
			
			
			
			//print_r($multipFile[0]);die;
			 $emailAddress = array();
			 $other_file = "";
			// if($_FILES['document']['name'] !=""){
				//$other_file = 	$this->file_uplaod(); 
			 //}
			 $postText = parse_smileys($this->input->post('text'), site_url().'assets/smilys/images/smileys/');
			 $insert_array = array();
			 $user_id = $this->session->userdata("gpi_id");
			 $insert_array['user_id']  	 = $user_id;
			 
			 $insert_array['post_text']  = parse_smileys($this->input->post('text'), site_url().'assets/smilys/images/smileys/');
			 $insert_array['smileys_text'] =  $this->input->post('text');
			 $insert_array['dateadded'] =  time();
			 $timeline_id =  $this->common_model->insert_array('tbl_timeline',$insert_array);
			 $this->common_model->insert_array('timeline_share',array('user_id'=>$user_id,'share_id'=>$user_id,'active_status'=>1,'post_status'=>'active','timeline_id'=>$timeline_id));
			 
			 $level_id = $this->common_model->select_single_field('level_id','users',array('user_id'=>$user_id));
			 $usersArr = array();
		//	$group_ids="";
			 $mysql_users = $this->common_model->select_where('user_id,email','users',array('level_id'=>$level_id));
			 
					if($mysql_users->num_rows() > 0){
						
						foreach($mysql_users->result_array() as $mysqlUser){
							$emailAddress[] =$mysqlUser['email'];
							$usersArr[] = $mysqlUser['user_id'];	
						}
					}
			// $emailAddress[]='rabbiaAnam456@gmail.com';
			 
			 
			 $share_array = array();
			foreach($usersArr as $user){
				$share_array[] = array('user_id'=>$user_id,'share_id'=>$user,'active_status'=>1,'timeline_id'=>$timeline_id,'share_type'=>'groups','group_id'=>$level_id);
			}
			
			$this->db->insert_batch('timeline_share',$share_array);
			 
			
			 if(!empty($imagesArray[0])){
				 for($i=0;$i<count($imagesArray[0]);$i++){
					 $inserting_array[]=array('timeline_id'=>$timeline_id, 'file_name'=>$imagesArray[0][$i],'upload_type'=>'image');
				 }
				 $this->db->insert_batch('tbl_timeline_uploaded',$inserting_array);
			 }
			 /*if($video_name !=""){
				 $this->common_model->insert_array('tbl_timeline_uploaded',array('timeline_id'=>$timeline_id, 'file_name'=>$video_name,'upload_type'=>'video'));
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
		
			$email_content = $this->common_model->select_single_field('email_content','email_template',array('id'=>15));
			$email_subject = $this->common_model->select_single_field('email_subject','email_template',array('id'=>15));
			
		
			
			
			
			/*$this->load->library('email'); 
			
			$useremail  = $this->common_model->select_single_field('email','users',array('user_id'=>$user_id));
			$useremail .= 'aliakbar1to5@gmail.com';
			//$useremail .= ", rabbiaanam456@gmail.com";
			// $useremail .=", ceo.appexos@gmail.com";
			 $this->email->from('owner@gmail.com', $name); 
			 $this->email->to($useremail);
			 $this->email->subject($email_subject); 
			 $this->email->message($string); 
			 //print_r($string);
			 $this->email->set_mailtype("html");
			 if($this->email->send()){
				echo "email send...";	 
			 }else{
				echo "email not sent";	 
			}*/
			 
			 $emailAdd = implode(", ",$emailAddress);
			 // $emailAdd = "'".$emailAdd."'";
			 
			 
			/*$textHtml =   '<table width="555" border="1">
							  <tr>
								 <td width="545" height="30" valign="bottom" bgcolor="#D6D6D6">
								 <div align="center;float:left;" style="padding:4px;">
									<strong style="font-size:20px;margin-left:145px;"><img src="'.site_url('assets/admin/images/logo1.png').'" width="56" height="25" style=float:left;margin-left:10px"; />Email Subject</strong> 
								</div></td>
							  </tr>
							  <tr>
								<td height="auto" bgcolor="#FFFFFF">'.$string.'</td>
							  </tr>
							  <tr>
							  <td style="text-align:center;" bgcolor="#D6D6D6">Copyright © 2015 All Rights Reserved.</td>
							  </tr>
							</table>';*/
							
							$date = date("F j, Y, g:i a");
							
							//<img src="'.site_url('assets/images/new_logo.jpg').'" style="text-align:center;align:center;margin-left:120px;" height="100px"  valign="middle" />
							
							/*In order to check notifications, please login to your account from here <a href="http://www.gpiwin.com/login">http://www.gpiwin.com/login</a><br />
								</p>
								Thanks,<br />
								GPI Team<br />
								<a href="http://www.gpiwin.com/login">http://www.gpiwin.com/login</a> </div>
								<h4 style="float:right;">&nbsp;</h4>*/
								
								
								/*$textHtml = '<div style="width:500px;">
								<img src='.site_url("assets/images/new_logo.jpg").' style="text-align:center;align:center;margin-left:120px;" height="100px"  valign="middle" />
								<div>
								<div>
								<div>
								<div>
								<h4 style="float:left;">'.$email_subject.'</h4>
								<div class="clear"></div>
								
								<h4 style="float:right;">'.$date.'</h4>
								</div>
								<div class="clear"></div>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
								'.$string.'
								
								
								</div>
								</div>
								</div>';*/
							
							
							
							
							
							 
								
								
								 /* $message = '';
								  $this->load->library('email');
								  $this->email->set_newline("\r\n");
								  $this->email->from('xxx@gmail.com'); // change it to yours
								  $this->email->to('aliakbar1to5@gmail.com');// change it to yours
								  $this->email->subject('Resume from JobsBuddy for your Job posting');
								  $this->email->message($message);
								  if($this->email->send())
								 {
								  echo 'Email sent.';
								 }
								 else
								{
								 show_error($this->email->print_debugger());
								}
							
							
						
							die;
							*/
							
							//print_r($textHtml);die;
			 
			
			/* $headersfrom='';
			 $headersfrom .= 'MIME-Version: 1.0' . "\r\n";
			 $headersfrom .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			 $headersfrom .= "From: default@gpi".
			 $headersfrom .= "Cc: admin@gpi.com";*/
			 
			 //Load email library 
			 
			// $j=0;
			 $emails = "";
			
			  
			   /*if(@mail("aliakbar1to5@gmail.com, rabbiaAnam456@gmail.com",$email_subject,$textHtml,$headersfrom)){
				  echo "send"; 
				}else{
					echo "not send.";  
				}*/
				
				
		        $config['mailtype'] = 'html';
				
				$this->load->library('email',$config);
				$this->load->library('table');
				
				for($i=0; $i <count($emailAddress);$i++){
					$emails.= "'".$emailAddress[$i]."', ";
				}
				
				$emails =  substr(trim($emails), 0, -1);
				
				
				
				$list  = array($emails);

				
				//  $this->email->set_newline("\r\n");
				 // $this->email->from('aliakbar1to5@gmail.com'); // change it to yours
				 // $this->email->to('rabbiaAnam456@gmail.com, ceo.appexos@gmail.com');// change it to yours
				//  $this->email->to($list);// change it to yours
				 // $this->email->subject('GPI Connect');
				 // $this->email->message($textHtml);
				
			//	  if($this->email->send())
				// {
				//  echo 'Email sent.';
				// }
				// else
				//{
				// show_error($this->email->print_debugger());
			//	}
			  
			  
			 /* $emailAddress = array();
			  $emailAddress[] = "aliakbar1to5@gmail.com";
			  $emailAddress[] = "ceo.appexos@gmail.com";
			  $usersArr = array();
			  $usersArr[]= 677;
			  $usersArr[] = 642;*/
			  
				  for($i=0; $i <count($emailAddress);$i++){
					  
					/*  $this->email->set_newline("\r\n");
					  $this->email->from('aliakbar1to5@gmail.com'); // change it to yours
					  $this->email->to($emailAddress[$i]);// change it to yours
					  $this->email->subject('GPI Connect');
					  $this->email->message($textHtml);
					
					  if($this->email->send()){
						  echo "send";
					  }else{
							echo "not send";	  
					 }*/
					 
					 
					 
					$userObj = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id = ".$usersArr[$i]."");
					
					$user = $userObj->row_array();
					$name = $user['name'];
					$healthy = array("{{name}}", "{{content}}");
					$yummy   = array($name, $postText);
					$string = str_replace($healthy,$yummy,$email_content);
					
				  	/*$imgUrl = '<img src="'.site_url('assets/images/logo_email.png').'" border="0" width="321" />';
					$msgHtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html xmlns="http://www.w3.org/1999/xhtml">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
							<meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" name="viewport" />
							<title>Government Procurement Institute</title>
							
							<style type="text/css">
							
							<!-- ================ Start Media Queries ================== -->
								
							@media only screen and (min-width: 768px) {
									body[yahoofix] .mobileCenter                {width: 100%!important;}
									body[yahoofix] .contentWrapper              {width:100%!important;}
							}
							
							@media only screen and (max-width: 640px) {
									body[yahoofix] body                         {width:100%!important; -webkit-text-size-adjust: none;}
									body[yahoofix] table table                  {width:500px!important;}
									body[yahoofix] .emailWrapper             	{width:500px!important;}
									body[yahoofix] .contentWrapper              {width:480px!important;}
									body[yahoofix] .fullWidth					{width:480px!important;}
									body[yahoofix] .messageWrapper              {width:480px!important;}
									body[yahoofix] .headerScale					{width:480px!important;}
							}
							
							<!-- ================ End Media Queries ================== -->
								
							</style>
							
							</head>
							<body yahoofix style="margin: 0; padding:0;">
							
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="emailWrapper">
							  <tr>
								<td valign="top">    
									<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="mobileCenter" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color:#FFF;">
									  <tr>
										<td valign="middle" bgcolor="#fdd00f" height="150" align="center" style="text-align:center; background-color:#fdd00f;"><a href="#">'.$imgUrl.'</a></td>
									  </tr>
									  <tr>
										<td height="15">&nbsp;</td>
									  </tr>
									  <tr>
										<td>
											<table width="640" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
												<tr>
													<td>
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td height="60" align="center">
																	<h1 style="font-size:30px; font-family:Arial, Helvetica, sans-serif; color:#333; margin-top:0; padding:0;">
																		GPI CONNACT
																	</h1>                                        
																</td>
															</tr>
															<tr>
																<td valign="top" height="80" align="center" style="font-size:18px; font-family:Arial, Helvetica, sans-serif; color:#333;">
																	'.$string.'
																</td>
															</tr>
															
															<tr>
																<td height="20">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									  </tr>
									  <tr>
										<td bgcolor="#fdd00f" height="5"></td>
									  </tr>
									  
									   <tr>
										<td bgcolor="#797878">
											<table width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
												  <tr>
													<td align="center" height="60" style="font-size:12px; font-family:Arial; color:#FFF;">
														<a href="http://gpiwin.com" style="color:#FFF;">Government Procurement Innovators, LLC</a>
													</td>
												  </tr>
											 </table>
										</td>
									  </tr>
									  
									</table>
								</td>
							  </tr>
							</table>
							</body>
							</html>';*/
							
							//echo $msgHtml;die;
					/*  $this->email->set_newline("\r\n");
					  $this->email->from('aliakbar1to5@gmail.com'); // change it to yours
					  $this->email->to($emailAddress[$i]);// change it to yours
					  $this->email->subject('GPI Connect');
					  $this->email->message($textHtml); */
					
							$this->email->from("owner@gpi.com");
							$this->email->to($emailAddress[$i]);
							$this->email->subject('GPI Connect');
							$this->email->message($string); 
							$this->email->set_mailtype("html");
							$this->email->send();	
										  
				   }
				  
				  
				  $this->session->set_flashdata('timeline_post','Post has been added sucessfully!');		
			  redirect('timeline'); 
			  
			
			
			 
			 for($i=0; $i <count($emailAddress);$i++){
				$emails.= $emailAddress[$i].", ";
				
				
				
				   if($j % 10 == 0){
							$emails = substr(trim($emails), 0, -1);
							$emails = "'".$emails."'";
							// if(@mail($emailAddress[$i],$email_subject,$textHtml,$headersfrom)){
								 if(@mail($emails,$email_subject,$textHtml,$headersfrom)){
								echo "send";
								$j=0;	 
							 }else{
								echo "not";	 
							 }
							
							} 
						
					 $j++;
					 
					 }

			
			 
			die;
			
			
		}
		
	/*	function upload_video(){
					
			$time = time();
			$file_name = $time.$_FILES['file']['name'];
			$target_dir = "./assets/uploads/timeline_videos/";
			if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}
			
			  if(@copy($_FILES["file"]["tmp_name"],$target_dir.$file_name)){
				  return $file_name;	  
			  }else{
				  echo "Not uploded..";
			  } 
		}
				
			*/	
		
		
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
				
		   }*/
		   
		   
		   function uplaod_multiple_images(){
				extract($_POST);
				$error=array();
				
				$upload_dir = './assets/uploads/timeline_images';
				$upload_dir_thumb = './assets/uploads/timeline_images/thumbs';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
				$image_array = array();
				foreach($_FILES["userfile"]["tmp_name"] as $key=>$tmp_name)
						{
							$time = time();
							$fileName = preg_replace('/\s+/', '_',$_FILES["userfile"]["name"][$key]);
							$file_name=$time.$fileName;
							$file_tmp=$_FILES["userfile"]["tmp_name"][$key];
							$ext=pathinfo($file_name,PATHINFO_EXTENSION);
							$image_array[] = $file_name;
							move_uploaded_file($file_tmp=$_FILES["userfile"]["tmp_name"][$key],$upload_dir."/".$file_name);
							@image_resize($upload_dir.'/'.$file_name, $upload_dir_thumb.'/'.$file_name, 200, 200, 1);
            		   }  
					   
					   return $image_array; 
		   }
   
   

  }