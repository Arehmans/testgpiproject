<?php

  class Events extends CI_Controller

  {

	   function __construct() {
     
     parent::__construct();
	 
	  ini_set('error_reporting', E_ALL);
	  ini_set('display_errors', 1);  //On or Off


	 $this->load->model('common_model');
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     date_default_timezone_set('America/New_York');
	 
     }
	

   }


   function delete_event($event_id){

         $this->common_model->delete_where(array('id'=>$event_id),'tbl_events');
         $this->common_model->delete_where(array('event_id'=>$event_id),'tbl_event_images');

         $this->session->set_flashdata('eventMsg','Event has been Successfully deleted.');
    	 header("Location: ".base_url()."admin/events/view_events");

   }
   
    function view_events(){

		 	$allEvents = $this->common_model->select_all('*','tbl_events');
			$data['allEvents'] = $allEvents;
			$data['main_cls'] = 'events';
			$data['class'] = "view_events";
			$data['content'] = 'admin/events/view_events';
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
	    $upload_dir = './assets/uploads/product_images/multiple_images';
        $upload_dir_thumb = './assets/uploads/product_images/multiple_images/thumbs';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}

        if (!file_exists($upload_dir_thumb)) {
			mkdir($upload_dir_thumb, 0777, true);
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
			for($i=0; $i <count($image_array);$i++){
				@image_resize($upload_dir.'/'.$image_array[$i], $upload_dir_thumb.'/'.$image_array[$i], 400, 370, 0);
			}
			
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
	
	

	public  function add_events(){

		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('event_name', 'Event Name', 'required');
		  $this->form_validation->set_rules('speaker', 'Speaker', 'required');
          $this->form_validation->set_rules('location', 'Location', 'required');
		  $this->form_validation->set_rules('editor1', 'Description', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){
				 $data['class'] = "events";
				$data['main_cls'] = 'events';
				$data['class'] = "add_events";
			  	 $data['content'] = 'admin/events/add_events';
		   		 $this->load->view('admin/layout/layout',$data);
			}else
			{
				
				
				
				
                 $insert_array = array();
                 $imagesArray = array();
                if($_FILES['userfile'] !=""){
                    $multi_images = $this->uplaod_multiple_images();
                    $imagesArray[] = $multi_images;
                }


				
				
				

				$insert_array['event_name'] = $this->input->post('event_name');
				$insert_array['event_speaker'] = $this->input->post('speaker');
				$insert_array['event_location'] = $this->input->post('location');
				$insert_array['event_description'] = $this->input->post('editor1');
                $insert_array['event_date'] = strtotime($this->input->post('datetimepicker'));

			    $event_id = 	$this->common_model->insert_array('tbl_events',$insert_array);
				
				
				$image_order = explode(",",$this->input->post('image_array_order'));
				
				$imagesArray = $imagesArray[0];
                $inserting_array = array();
				
				for($i=0;$i<count($image_order);$i++){
						$imageArr = $imagesArray[$image_order[$i]];	
                       $inserting_array[]=array('event_id'=>$event_id,'image_name'=>$imageArr,'image_order'=>$image_order[$i]);
                }

			
               /* for($i=0;$i<count($imagesArray[0]);$i++){
                       $inserting_array[]=array('event_id'=>$event_id,'image_name'=>$imagesArray[0][$i],'image_order'=>$image_order[$i]);
                }*/

                 $this->db->insert_batch('tbl_event_images',$inserting_array);

				$this->session->set_flashdata('eventMsg','Event has been Successfully Inserted...');
				header("Location: ".base_url()."admin/events/view_events");

		 }

	  }

      function deleteImage($event_id){

             $id =  $this->common_model->select_single_field('event_id','tbl_event_images',array('id'=>$event_id));
             $this->common_model->delete_where(array('id'=>$event_id),'tbl_event_images');
             $this->session->set_flashdata('eventMsg','Image has been deleted sucessfully.');
			 header("Location: ".base_url()."admin/events/update_event/".$id);

      }

	  

	  public  function update_event($event_id){

		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('event_name', 'Event Name', 'required');
		  $this->form_validation->set_rules('speaker', 'Speaker', 'required');
          $this->form_validation->set_rules('location', 'Location', 'required');
		  $this->form_validation->set_rules('editor1', 'Description', 'required');
		  $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');

		  if ($this->form_validation->run() == FALSE){

			 $data['event_id'] = $event_id;
			 $mysql_event = $this->common_model->select_where('*',' tbl_events',array('id'=>$event_id));
			 $data['main_cls'] = 'events';
				$data['class'] = "update_event";
			 $data['mysql_event'] = $mysql_event->row_array();

			 $data['content'] = 'admin/events/update_events';
			 $this->load->view('admin/layout/layout',$data);

			}else
			{

				$imagesName = explode(",",$this->input->post('already_images_name'));
				
				$imageIds= explode(",",$this->input->post('already_images_id'));
				
				$mysql_images_all = $this->common_model->select_where("*","tbl_event_images",array("event_id"=>$event_id));
				
				if($mysql_images_all->num_rows() >0){
					$q=0;
					foreach($mysql_images_all->result_array() as $newRes){
						$this->common_model->update_array(array('id'=>$newRes['id']),'tbl_event_images',array('image_name'=>$imagesName[$q]));	
					$q++;	
					}	
				}
				
				
				$insert_array['event_name'] = $this->input->post('event_name');
				$insert_array['event_speaker'] = $this->input->post('speaker');
				$insert_array['event_location'] = $this->input->post('location');
				$insert_array['event_description'] = $this->input->post('editor1');
                $insert_array['event_date'] = strtotime($this->input->post('datetimepicker'));
            	$upload_multi_dir = './assets/uploads/product_images/multiple_images';



               $uploading_images = array();

                if($_FILES['userfile']['size'][0] !=0){

                  /*  $event_images = $this->common_model->select_single_field('event_images','tbl_events',array('id'=>$event_id));

                    $images =  json_decode($event_images);

            		if(!empty($images)){
            			foreach($images as $Image){

            				 if (file_exists($upload_multi_dir.$Image)) {
            						@unlink($upload_multi_dir.$Image);
            						$insert_array['event_images'] = "";
            				 }
            			}
    	        	}*/


        		   	$multi_images = $this->uplaod_multiple_images();
        			//$uploading_images[] = $multi_images;
					$imagesArray[] = $multi_images;
				}
				
				$this->common_model->update_array(array('id'=>$event_id),'tbl_events',$insert_array);

                   if(!empty($imagesArray) >0){
					   
					    $image_order = explode(",",$this->input->post('image_array_order'));
				
						$imagesArray = $imagesArray[0];
						$inserting_array = array();
                        /*for($i=0;$i<count($uploading_images[0]);$i++){
                               $inserting_array[]=array('event_id'=>$event_id, 'image_name'=>$uploading_images[0][$i]);
                        }*/
						
						for($i=0;$i<count($image_order);$i++){
							$imageArr = $imagesArray[$image_order[$i]];	
							$inserting_array[]=array('event_id'=>$event_id,'image_name'=>$imageArr,'image_order'=>$image_order[$i]);
                		}
						
                         $this->db->insert_batch('tbl_event_images',$inserting_array);
                   }


				$this->session->set_flashdata('eventMsg','Event has been updated successfully!...');
				header("Location: ".base_url()."admin/events/view_events");

		 }

	  }



	  



	}


  

  
