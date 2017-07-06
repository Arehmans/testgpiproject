<?php
class Membership extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        $this->load->model('common_model');
        if ($this->session->userdata("admin_id") == "") {
            redirect(base_url() . "admin/login");
        }
    }
    public function add_membership()
    {
		
        $this->load->library('form_validation');
        $this->form_validation->set_rules('mem_name', 'Membership Title', 'required');
        $this->form_validation->set_rules('mem_price', 'Price', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('member_level[]', 'Level', 'required');
		$this->form_validation->set_rules('videos_0[]', 'Electures', 'required');
		$this->form_validation->set_rules('resources_0[]', 'Resources', 'required');
		
		
		//if cannot default membership then..
		
	//	if($this->input->post('default') == 0){
			$this->form_validation->set_rules('web_per_month', 'Webinars Access/ month', 'required');
			$this->form_validation->set_rules('elec_per_month', 'E-Lectures Access/ month', 'required');
			$this->form_validation->set_rules('disc_additional_web', 'Discount Percent on Webinars', 'required');
			$this->form_validation->set_rules('disc_per_temp_pro', 'Discount Percent on  Template', 'required');
			$this->form_validation->set_rules('disc_electures', 'Discount Percent on E-Lectures', 'required');
			$this->form_validation->set_rules('disc_live_cls', ' Discount Percent on  Live Classes', 'required');
			$this->form_validation->set_rules('disc_speciality_cls', ' Discount Percent on  Speciality Classes', 'required');
			$this->form_validation->set_rules('disc_virtual_cls', 'Discount Percent on  Virtual Classes', 'required');
            $this->form_validation->set_rules('disc_package', 'Discount Percent on  Packages', 'required');
	//	}
		
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
        if ($this->form_validation->run() == FALSE) {
            $membershipObj            = $this->common_model->select_all("*", "tbl_membership");
            $data['total_membership'] = $membershipObj->num_rows();
			$data['class'] = "membership";
			$data['main_cls'] = 'membership';
			$data['class'] = "add_membership";
            $data['content']          = 'admin/membership/add_membership';
            $this->load->view('admin/layout/layout', $data);
        } else {
			
		
			
            $membershipObj = $this->common_model->select_all("*", "tbl_membership");
            if ($membershipObj->num_rows()) {
                $membership_status = $this->input->post('status');
                if ($membership_status == 1) {
					if($this->input->post('default') == 1)
                    $this->db->query("UPDATE tbl_membership SET mem_default = '0'");
                }
            }
            $Insert_array                        = array();
            $insert_array['mem_name']            = $this->input->post('mem_name');
            $insert_array['mem_type']            = $this->input->post('mem_type');
            $insert_array['mem_price']           = $this->input->post('mem_price');
            $insert_array['mem_description']     = $this->input->post('description');
            $insert_array['mem_status']          = $this->input->post('status');
            $insert_array['mem_default']         = $this->input->post('default');
			$insert_array['web_per_month']       = $this->input->post('web_per_month');
			$insert_array['elec_per_month']      = $this->input->post('elec_per_month');
			$insert_array['disc_additional_web'] = $this->input->post('disc_additional_web');
			$insert_array['disc_per_temp_pro']   = $this->input->post('disc_per_temp_pro');
			$insert_array['disc_electures']      = $this->input->post('disc_electures');
			$insert_array['disc_live_cls']       = $this->input->post('disc_live_cls');
			$insert_array['disc_speciality_cls'] = $this->input->post('disc_speciality_cls');
			$insert_array['disc_virtual_cls'] 	 = $this->input->post('disc_virtual_cls');
            $insert_array['disc_package'] 	 	 = $this->input->post('disc_package');

            $membership_id                       = $this->common_model->insert_array('tbl_membership', $insert_array);
            $member_level                        = $this->input->post('member_level');
            for ($i = 0; $i < count($member_level); $i++) {
                $videos    = $this->input->post("videos_" . $i);
                $resources = $this->input->post('resources_' . $i);
				
				
                $videos    = json_encode($videos);
                $resouces  = json_encode($resources);
				
				if($videos == "false"){
					$videos = '["0"]';
				}
				if($resources == 0){
					$resouces = '["0"]';
				}
				
				//print_r($videos);
				//print_r($resouces);
                $this->common_model->insert_array('tbl_membership_level', array(
                    'mem_id' => $membership_id,
                    'level_id' => $member_level[$i],
                    'electures_id' => $videos,
                    'resouces_id' => $resouces
                )); 
            }
		
            $this->session->set_flashdata('add_que', 'Membership Successfully Inserted...');
            header("Location: " . base_url() . "admin/membership/membership_view");
        }
    }
    function membership_view()
    {
        $this->load->library('pagination');
        $per_page              = 10;
        $qry                   = "select * from `tbl_membership` ";
        $offset                = ($this->uri->segment(4) != '' ? $this->uri->segment(4) : 0);
        $config['total_rows']  = $this->db->query($qry)->num_rows();
        $config['per_page']    = $per_page;
        $config['first_link']  = 'First';
        $config['last_link']   = 'Last';
        $config['uri_segment'] = 4;
        $config['base_url']    = base_url() . 'admin/membership/membership_view';
        $this->pagination->initialize($config);
        $data['paginglinks'] = $this->pagination->create_links();
        if ($data['paginglinks'] != '') {
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $this->pagination->per_page) + 1) . ' to ' . ($this->pagination->cur_page * $this->pagination->per_page) . ' of ' . $this->pagination->total_rows;
        } else {
            $data['pagermessage'] = '';
        }
       // $qry .= " limit {$per_page} offset {$offset} ";
	   
	   $data['main_cls'] = 'membership';
		$data['class'] 	  = "view_membership";
	   
        $data['membership_record'] = $this->db->query($qry)->result_array();
        $data['myObj']             = $this;
        $data['content']           = 'admin/membership/membership_view';
        $this->load->view('admin/layout/layout', $data);
    }
    function update_membership($membership_id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('mem_name', 'Membership Title', 'required');
        $this->form_validation->set_rules('mem_price', 'Price', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('member_level[]', 'Level', 'required');
		
		$this->form_validation->set_rules('videos_0[]', 'Electures', 'required');
		$this->form_validation->set_rules('resources_0[]', 'Resources', 'required');
		

	//	if($this->input->post('default') == 0){
			$this->form_validation->set_rules('web_per_month', 'Webinars Access/ month', 'required');
			$this->form_validation->set_rules('elec_per_month', 'E-Lectures Access/ month', 'required');
			$this->form_validation->set_rules('disc_additional_web', 'Discount Percent on Webinars', 'required');
			$this->form_validation->set_rules('disc_per_temp_pro', 'Discount Percent on  Template', 'required');
			$this->form_validation->set_rules('disc_electures', 'Discount Percent on E-Lectures', 'required');
			$this->form_validation->set_rules('disc_live_cls', 'Discount Percent on  Live Classes', 'required');
			$this->form_validation->set_rules('disc_speciality_cls', 'Discount Percent on  Speciality Classes', 'required');
			$this->form_validation->set_rules('disc_virtual_cls', 'Discount Percent on  Virtual Classes', 'required');
            $this->form_validation->set_rules('disc_package', 'Discount Percent on  Packages', 'required');
	//	}
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
        if ($this->form_validation->run() == FALSE) {
            $mem_levels                     = $this->common_model->select_where("*", "tbl_membership_level", array(
                "mem_id" => $membership_id
            ));
            $mem_levels                     = $mem_levels->result_array();
            $data['whole_level_membership'] = $mem_levels;
            $data['level_id']               = $mem_levels;
			$data['main_cls'] = 'membership';
			$data['class'] = "update_membership";
			$mem_levels = array();
			foreach($mem_levels as $myMemkey => $myMemVal){
				$mem_levels[] = $myMemVal['level_id'];
			}
			
            $mem_levels                     = implode(", ", $mem_levels);
            $membershipObj                  = $this->common_model->select_all("*", "tbl_membership");
            $data['total_membership']       = $membershipObj->num_rows();
            $memberRecord                   = $this->common_model->select_where("*", "tbl_membership", array(
                "mem_id" => $membership_id
            ));
			
			
            $data['memberRecord']           = $memberRecord->row_array();
            $data['membership_id']          = $membership_id;
            $level_idObj                    = $this->common_model->select_where('level_id', 'tbl_membership_level', array(
                'mem_id' => $membership_id
            ));
			$level_idArray  = array();
			foreach($level_idObj->result_array() as $newResKey => $newResValue){
				$level_idArray[] = $newResValue['level_id'];
				
			}
          
            $data['level_idArray']          = $level_idArray;
            $data['content']                = 'admin/membership/update_membership';
            $this->load->view('admin/layout/layout', $data);
        } else {
            $membershipObj = $this->common_model->select_all("*", "tbl_membership");
            if ($membershipObj->num_rows()) {
                $membership_status = $this->input->post('status');
                if ($membership_status == 1) {
                    $this->db->query("UPDATE tbl_membership SET mem_default = '0'");
                }
            }
            $Insert_array                        = array();
            $insert_array['mem_name']            = $this->input->post('mem_name');
            $insert_array['mem_price']           = $this->input->post('mem_price');
            $insert_array['mem_type']            = $this->input->post('mem_type');
            $insert_array['mem_status']          = $this->input->post('status');
            $insert_array['mem_default']         = $this->input->post('default');
            $insert_array['mem_description']     = $this->input->post('description');
			
		/*	
			$insert_array['web_per_month']       = "";
			$insert_array['elec_per_month']      = "";
			$insert_array['disc_additional_web'] = "";
			$insert_array['disc_per_temp_pro']   = "";
			$insert_array['disc_electures']      = "";
			$insert_array['disc_live_cls']       = ""; */
			
		//	if($this->input->post('default') == 0){

				$insert_array['web_per_month']       = $this->input->post('web_per_month');
				$insert_array['elec_per_month']      = $this->input->post('elec_per_month');
				$insert_array['disc_additional_web'] = $this->input->post('disc_additional_web');
				$insert_array['disc_per_temp_pro']   = $this->input->post('disc_per_temp_pro');
				$insert_array['disc_electures']      = $this->input->post('disc_electures');
				$insert_array['disc_live_cls']       = $this->input->post('disc_live_cls');
				$insert_array['disc_speciality_cls'] = $this->input->post('disc_speciality_cls');
				$insert_array['disc_virtual_cls']    = $this->input->post('disc_virtual_cls');
                $insert_array['disc_package'] 	 	 = $this->input->post('disc_package');
			
		//	}
			
            $this->common_model->update_array(array(
                'mem_id' => $membership_id
            ), 'tbl_membership', $insert_array);
            $this->common_model->delete_where(array(
                'mem_id' => $membership_id
            ), 'tbl_membership_level');
            $member_level = $this->input->post('member_level');
            for ($i = 0; $i < count($member_level); $i++) {
                $videos    = $this->input->post("videos_" . $i);
                $resources = $this->input->post('resources_' . $i);
                $videos    = json_encode($videos);
                $resouces  = json_encode($resources);
				
				if($videos == "false"){
					$videos = '["0"]';
				}
				if($resources == 0){
					$resouces = '["0"]';
				}
				
				
                $this->common_model->insert_array('tbl_membership_level', array(
                    'mem_id' => $membership_id,
                    'level_id' => $member_level[$i],
                    'electures_id' => $videos,
                    'resouces_id' => $resouces
                ));
            }
            $this->session->set_flashdata('add_que', 'Membership updated sucessfully.');
            header("Location: " . base_url() . "admin/membership/membership_view");
        }
    }
    function answers_view()
    {
        $this->load->library('pagination');
        $pages                 = $this->gpi_model->getrecordbyidrow('paging', 'paging_id', 9);
        $per_page              = $pages->pages;
        $qry                   = "select * from `answer` ";
        $offset                = ($this->uri->segment(4) != '' ? $this->uri->segment(4) : 0);
        $config['total_rows']  = $this->db->query($qry)->num_rows();
        $config['per_page']    = $per_page;
        $config['first_link']  = 'First';
        $config['last_link']   = 'Last';
        $config['uri_segment'] = 4;
        $config['base_url']    = base_url() . 'admin/questions/answers_view';
        $this->pagination->initialize($config);
        $data['paginglinks'] = $this->pagination->create_links();
        if ($data['paginglinks'] != '') {
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $this->pagination->per_page) + 1) . ' to ' . ($this->pagination->cur_page * $this->pagination->per_page) . ' of ' . $this->pagination->total_rows;
        } else {
            $data['pagermessage'] = '';
        }
        $qry .= " limit {$per_page} offset {$offset} ";
		$data['class'] = "membership";
        $data['qry']     = $this->db->query($qry)->result();
        $data['content'] = 'admin/answers_view';
        $this->load->view('admin/layout/layout', $data);
    }
    function replaceItems()
    {
        $level_ids  = $this->input->post('level_id');
        $flag       = $this->input->post('flag');
        $myArray    = array();
        $videodata1 = $this->db->query("SELECT electures_id,title FROM (`videos`) JOIN `video_levels` ON `video_levels`.`video_id` = `videos`.`electures_id` WHERE  `video_levels`.`level_id` IN (" . $level_ids . ") AND `videos`.`status` = 1 GROUP BY video_levels.video_id");
        $dropDown   = "";
        if ($videodata1->num_rows() > 0) {
            foreach ($videodata1->result_array() as $vidKey => $vidValue) {
                if ($vidValue['title'] == "")
                    $title = "N/A";
                else
                    $title = $vidValue['title'];
                $dropDown .= "<option value='" . $vidValue['electures_id'] . "'>" . $title . "</option>";
            }
        }
       // $resources = $this->common_model->select_where("*", "resource_folder", array( "level_id" => $level_ids ));
		//$resources = $this->db->query("SELECT * FROM resource_folder WHERE level_id = ".$level_ids." GROUP BY resource_folder_id");
      //  $resources = $this->db->query("SELECT * FROM resource_folder_level WHERE level_id = ".$level_ids." GROUP BY resource_folder_id");
        $dropDown1 = "";
		
		 $resources = $this->db->query("SELECT * FROM resource_folder INNER JOIN resource_folder_level ON resource_folder_level.resource_folder_id = resource_folder.resource_folder_id WHERE resource_folder_level.level_id = ".$level_ids."  GROUP BY resource_folder_level.resource_folder_id");
		
		
        if ($resources->num_rows() > 0) {
            foreach ($resources->result_array() as $resourcesKey => $resourcesValue) {
				//$folder_name = $this->common_model->select_single_field('folder_name','resource_folder',array('resource_folder_id'=>$resourcesValue['resource_folder_id']));
               
				
			/*	if ($folder_name == "")
                    $title = "N/A";
                else
                    $title = $folder_name;
					*/
                $dropDown1 .= "<option value='" . $resourcesValue['resource_folder_id'] . "' >" . $resourcesValue['folder_name'] . "</option>";
            }
        }
		
		
	
        $myArray = array(
            $dropDown,
            $dropDown1
        );
        $myArray = json_encode($myArray);
        echo $myArray;
    }
    function appendItems()
    {
        $result  = array();
        $last_id = $this->input->post('flag');
        $last_id += 1;
        $level_array           = $this->input->post('level_array');
        $level_array           = array_unique($level_array);
        $level_array           = implode(', ', $level_array);
        $result['last_id']     = $last_id;
        $result['level_array'] = $level_array;
        echo $this->load->view('admin/membership/append_levels', $result, true);
    }
    function delete_questions($id)
    {
        $table   = "questions";
        $primary = "questions_id";
        $this->db->delete($table, array(
            $primary => $id
        ));
        $this->db->delete('answer', array(
            'questions_id' => $id
        ));
        $this->session->set_flashdata('delete_que', 'Question Successfully Deleted...');
        header("Location: " . base_url() . "admin/questions/questions_view");
    }
    function delete_membership($id)
    {
        $membershipObj = $this->common_model->select_where('*', 'users', array(
            'mem_id' => $id
        ));
        $message       = "";
        if ($membershipObj->num_rows() > 0) {
            $message = "This membership cannot be deleted as there are users registered in it.";
            $this->session->set_flashdata('delete_membership', $message);
        } else {
            $table = "tbl_membership";
            $this->common_model->delete_where(array(
                "mem_id" => $id
            ), "tbl_membership_level");
            $this->common_model->delete_where(array(
                'mem_id' => $id
            ), $table);
            $message = "Membership Successfully Deleted.";
            $this->session->set_flashdata('delete_que', $message);
        }
        header("Location: " . base_url() . "admin/membership/membership_view");
    }
    function delete_answers($id)
    {
        $table   = "answer";
        $primary = "answer_id";
        $this->db->delete($table, array(
            $primary => $id
        ));
        $this->session->set_flashdata('delete_ans', 'Answer Successfully Deleted...');
        header("Location: " . base_url() . "admin/questions/answers_view");
    }
    function update_questions($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('questions_name', 'Questions', 'required');
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
        if ($this->form_validation->run() == FALSE) {
            $data['id']      = $id;
            $data['content'] = 'admin/questions_update';
			$data['class'] = "membership";
            $this->load->view('admin/layout/layout', $data);
        } else {
            $question_name = $this->input->post('questions_name');
            $count         = $this->input->post('count');
            $deleteids     = $this->input->post('deleteids');
            $answer_id     = $this->input->post('aid');
            $answer_name1  = $this->input->post('answer_name1');
            $answer_point1 = $this->input->post('answer_point1');
            $answer_name   = $this->input->post('answer_name');
            $answer_point  = $this->input->post('answer_point');
            $deleteids     = explode(",", $deleteids);
            if (empty($deleteids[count($deleteids) - 1])) {
                unset($deleteids[count($deleteids) - 1]);
            }
            $newdiff = array_diff($deleteids, $answer_id);
            $newdiff = array_values($newdiff);
            for ($i = 0; $i < sizeof($newdiff); $i++) {
                $table   = "answer";
                $primary = "answer_id";
                $this->db->delete($table, array(
                    $primary => $newdiff[$i]
                ));
            }
            $data1 = array(
                'questions_name' => $question_name
            );
            $this->gpi_model->update($data1, "questions", $this->input->post('vid'), "questions_id");
            $i = 0;
            foreach ($answer_id as $answer_id) {
                $data      = array(
                    'answer_name' => $answer_name1[$i],
                    'answer_point' => $answer_point1[$i]
                );
                $ansupdate = $this->gpi_model->update($data, "answer", $answer_id, "answer_id");
                $i++;
            }
            $j = 0;
            if ($answer_name != "") {
                foreach ($answer_name as $answer_name) {
                    $data2 = array(
                        'questions_id' => $this->input->post('vid'),
                        'answer_name' => $answer_name,
                        'answer_point' => $answer_point[$j]
                    );
                    $this->gpi_model->insert($data2, 'answer');
                    $j++;
                }
            }
            $this->session->set_flashdata('update_que', 'Question Successfully Updated...');
            header("Location: " . base_url() . "admin/questions/questions_view");
        }
    }
    function update_answer($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('questions_id', 'Select Question', 'required');
        $this->form_validation->set_rules('answer_name', 'Answer', 'required');
        $this->form_validation->set_rules('answer_point', 'Point', 'required');
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
        if ($this->form_validation->run() == FALSE) {
            $data['id']      = $id;
			$data['class'] = "membership";
            $data['content'] = 'admin/answers_update';
            $this->load->view('admin/layout/layout', $data);
        } else {
            $data = array(
                'questions_id' => $this->input->post('questions_id'),
                'answer_name' => $this->input->post('answer_name'),
                'answer_point' => $this->input->post('answer_point')
            );
            $this->gpi_model->update($data, "answer", $this->input->post('vid'), "answer_id");
            $this->session->set_flashdata('update_ans', 'Answer Successfully Updated...');
            header("Location: " . base_url() . "admin/questions/answers_view");
        }
    }
}