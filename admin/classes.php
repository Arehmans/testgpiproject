<?php
class classes extends CI_Controller
{
    function __construct()
    {
        // $this->load->library("pagination");
        parent::__construct();
        if ($this->session->userdata("admin_id") == "") {
            redirect(base_url() . "admin/login");
        }
    }
    public function add_classes()
    {
		
		
        $this->load->library('form_validation');
        $this->form_validation->set_rules('class_name', 'Class Name', 'required');
	
        $this->form_validation->set_rules('level_id', 'Select Level', 'required');
		$cls_type = $this->input->post('class_type');
		
		if($cls_type == 0 || $cls_type == 2){
			
				$this->form_validation->set_rules('address', 'Address', 'required');
			    $this->form_validation->set_rules('country_id', 'Country ', 'required');
				$this->form_validation->set_rules('state_id', 'State', 'required');
				$this->form_validation->set_rules('city_id', 'City', 'required');
				$this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
		}
		
		
		
      
        $this->form_validation->set_rules('class_date[]', 'Date', 'required');
        $this->form_validation->set_rules('fromtime[]', 'From Time', 'required');
        $this->form_validation->set_rules('totime[]', 'To Time', 'required');
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
        if ($this->form_validation->run() == FALSE) {
            $data['content'] = 'admin/classes_insert';
			$data['class'] = 'manage_classes';
            $this->load->view('admin/layout/layout', $data);
        } else {
			$cls_address = "";
			if($cls_type == 0 || $cls_type == 2){
				$address = $this->input->post('address');
				$cls_address   =  $address;
			}
		
            $data = array(
                'class_name' => $this->input->post('class_name'),
				'description' => $this->input->post('description'),
                'level_id' => $this->input->post('level_id'),
                'address' => $cls_address,
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
				'class_type' => $this->input->post('class_type'),
                'city_id' => $this->input->post('city_id'),
                'zip_code' => $this->input->post('zip_code'),
                'promotional_class' => $this->input->post('promotional_class'),
                'promotional_class_code' => $this->input->post('promotional_code')
            );
            $this->gpi_model->insert($data, 'classes');
            $classid    = $this->db->insert_id();
            $class_date = $this->input->post('class_date');
            $fromtime   = $this->input->post('fromtime');
            $totime     = $this->input->post('totime');
            $i          = 0;
            foreach ($class_date as $class) {
                $data2 = array(
                    'class_id' => $classid,
                    'class_date' => date('Y-m-d', strtotime($class)),
                    'fromtime' => date('H:i ', strtotime($fromtime[$i])),
                    'totime' => date('H:i ', strtotime($totime[$i]))
                );
                $this->gpi_model->insert($data2, 'class_date');
                $i++;
            }
            $this->session->set_flashdata('insert_msg', 'Class Successfully Inserted...');
            header("Location: " . base_url() . "admin/classes/classes_view");
            // }
        }
    }
	
	
	function update_status(){
		
		
		$class_id = $this->input->post('class_id');
		$status = $this->input->post('resource_status');
		
		$active_status = "";
		if($status == 1){
			$active_status = 0;	
		}else{
			$active_status = 1;
		}

		
	
		$this->common_model->update_array(array('class_id'=>$class_id),'classes',array('status'=>$active_status));
		$this->session->set_flashdata('insert_msg', 'Status has been  successfully updated!');
        header("Location: " . base_url() . "admin/classes/classes_view");
		
		
	}
	
	
	function set_order(){
		
		$classes_ids = $_POST['data'];
		$classes_ids = substr(trim($classes_ids), 0, -1); 
		$idsArray = explode(",",$classes_ids);
		$updateArray = array();
		$j=1;
		for($i=0; $i <count($idsArray);$i++){
			
			$updateArray[] = array(
				'class_id'=>$idsArray[$i],
				'class_order' => $j
				);
			$j++;
		}
		
		$this->db->update_batch('classes',$updateArray, 'class_id'); 
	}
    function classes_view()
    {
        $this->load->library('pagination');
        $pages    = $this->gpi_model->getrecordbyidrow('paging', 'paging_id', 1);
		$data['class'] = 'manage_classes';
        $per_page = $pages->pages;
		
        $qry      = "select * from `classes` ";
		 
		
       /* if ($this->input->get('classname') != "" && $this->input->get('classname') != "undefined") {
            $classname = $this->input->get('classname');
            if ($classname == 1) {
                $qry .= "order by `class_name` ASC";
            } else {
                $qry .= "order by `class_name` DESC";
            }
        }
        if ($this->input->get('clevel') != "" && $this->input->get('clevel') != "undefined") {
            $clevel = $this->input->get('clevel');
            if ($clevel == 1) {
                $qry .= "order by `level_id` ASC";
            } else {
                $qry .= "order by `level_id` DESC";
            }
        }
        if ($this->input->get('caddress') != "" && $this->input->get('caddress') != "undefined") {
            $caddress = $this->input->get('caddress');
            if ($caddress == 1) {
                $qry .= "order by `address` ASC";
            } else {
                $qry .= "order by `address` DESC";
            }
        }*/
		
		$qry .= "ORDER BY class_order ASC";
		
        $offset = $this->input->get('per_page');
        if (!$offset)
            $offset = 0;
        $config['total_rows']        = $this->db->query($qry)->num_rows();
        $config['per_page']          = $per_page;
        $config['first_link']        = 'First';
        $config['last_link']         = 'Last';
        $config['uri_segment']       = 4;
        $config['page_query_string'] = TRUE;
        $config['base_url']          = base_url() . 'admin/classes/classes_view?classname=' . @$classname . '&clevel=' . @$clevel . '&caddress=' . @$caddress . '';
        $this->pagination->initialize($config);
        $data['paginglinks'] = $this->pagination->create_links();
        if ($data['paginglinks'] != '') {
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $this->pagination->per_page) + 1) . ' to ' . ($this->pagination->cur_page * $this->pagination->per_page) . ' of ' . $this->pagination->total_rows;
        } else {
            $data['pagermessage'] = '';
        }
        $qry .= " limit {$per_page} offset {$offset} ";
		
        $data['qry']     = $this->db->query($qry)->result();
		//echo $this->db->last_query();die;
        //$this->load->view("example1", $data);
        $data['content'] = 'admin/classes_view';
        $this->load->view('admin/layout/layout', $data);
    }
	
	
    function classes_ajax()
    {
        $this->load->library('pagination');
        $pages    = $this->gpi_model->getrecordbyidrow('paging', 'paging_id', 1);
        $per_page = $pages->pages;
        $qry      = "select * from `classes` ";
        if ($this->input->get('classname') != "" && $this->input->get('classname') != "undefined") {
            $classname = $this->input->get('classname');
            if ($classname == 1) {
                $qry .= "order by `class_name` ASC";
            } else {
                $qry .= "order by `class_name` DESC";
            }
        }
        if ($this->input->get('clevel') != "" && $this->input->get('clevel') != "undefined") {
            $clevel = $this->input->get('clevel');
            if ($clevel == 1) {
                $qry .= "order by `level_id` ASC";
            } else {
                $qry .= "order by `level_id` DESC";
            }
        }
        if ($this->input->get('caddress') != "" && $this->input->get('caddress') != "undefined") {
            $caddress = $this->input->get('caddress');
            if ($caddress == 1) {
                $qry .= "order by `address` ASC";
            } else {
                $qry .= "order by `address` DESC";
            }
        }
        $offset = $this->input->get('per_page');
        if (!$offset)
            $offset = 0;
        $config['total_rows']        = $this->db->query($qry)->num_rows();
        $config['per_page']          = $per_page;
        $config['first_link']        = 'First';
        $config['last_link']         = 'Last';
        $config['uri_segment']       = 4;
        $config['page_query_string'] = TRUE;
        $config['base_url']          = base_url() . 'admin/classes/classes_view?classname=' . $classname . '&clevel=' . $clevel . '&caddress=' . $caddress . '';
        $this->pagination->initialize($config);
        $data['paginglinks'] = $this->pagination->create_links();
        if ($data['paginglinks'] != '') {
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $this->pagination->per_page) + 1) . ' to ' . ($this->pagination->cur_page * $this->pagination->per_page) . ' of ' . $this->pagination->total_rows;
        } else {
            $data['pagermessage'] = '';
        }
        $qry .= " limit {$per_page} offset {$offset} ";
        $data['qry'] = $this->db->query($qry)->result();
        // $data['content'] = 'admin/organization_ajax_view';
        $this->load->view('admin/classes_ajax.php', $data);
    }
    function getstate($catid)
    {
        $getsubcats = $this->gpi_model->getrecordbyid("states", "country_id", $catid);
        foreach ($getsubcats as $subcat) {
?>



            <option value="<?php
            echo $subcat->id;
?>"><?php
            echo $subcat->name;
?></option>



        <?php
        }
    }
    function getcity($catid)
    {
        $getsubcats = $this->gpi_model->getrecordbyid("cities", "state_id", $catid);
        foreach ($getsubcats as $subcat) {
?>

            

            <option value="<?php
            echo $subcat->id;
			?>"><?php
						echo $subcat->name;
			?></option>



        <?php
        }
    }
    function delete_classes($id)
    {
        $table   = "classes";
        $primary = "class_id";
        $this->db->delete($table, array(
            $primary => $id
        ));
        $this->db->delete('class_date', array(
            'class_id' => $id
        ));
        $this->db->delete('tickets', array(
            'class_id' => $id
        ));
        $this->db->delete('ticket_sell', array(
            'class_id' => $id
        ));
        $this->session->set_flashdata('delete_msg', 'Class Successfully Deleted...');
        header("Location: " . base_url() . "admin/classes/classes_view");
    }
	
	
	function detail($class_id){
		$mysqlObj = 	$this->db->query("SELECT * FROM classes JOIN levels ON classes.level_id = levels.level_id WHERE class_id = ".$class_id."");
		
		$mysqlCls = $this->common_model->select_where("*","class_date",array("class_id"=>$class_id));
		$qry = "select * from `ticket_sell` WHERE class_id = ".$class_id." ";
		
		
		$data['mysqlObj'] = $mysqlObj->row_array();
		$data['mysqlCls'] = $mysqlCls;
		$data['Obj'] = $this;
		$data['qry'] = $this->db->query($qry); 
		$data['content'] = 'admin/class_detail';
        $this->load->view('admin/layout/layout', $data);
		
		
	}
	
	
    function update_classes($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('class_name', 'Class Name', 'required');
		
        $this->form_validation->set_rules('level_id', 'Select Level', 'required');
		
		$cls_type = $this->input->post('class_type');
		
		if($cls_type == 0 || $cls_type == 2){
			$this->form_validation->set_rules('address', 'Address', 'required');
			$this->form_validation->set_rules('country_id', 'Country ', 'required');
        	$this->form_validation->set_rules('state_id', 'State', 'required');
       	 	//  $this->form_validation->set_rules('city_id', 'City', 'required');
       	    $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
		}
		
        
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
        if ($this->form_validation->run() == FALSE) {
            $data['id']      = $id;
			$data['class'] = 'manage_classes';
            $data['content'] = 'admin/classes_update';
            $this->load->view('admin/layout/layout', $data);
        } else {
			
			$cls_address = "";
			if($cls_type == 0 || $cls_type == 2){
				$address = $this->input->post('address');
				$cls_address   =  $address;
			}
		
            $data = array(
                'class_name' => $this->input->post('class_name'),
				'description' => $this->input->post('description'),
                'level_id' => $this->input->post('level_id'),
                'address' => $cls_address,
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'city_id' => $this->input->post('city_id'),
                'zip_code' => $this->input->post('zip_code'),
				'class_type' => $this->input->post('class_type')
            );
            $this->gpi_model->update($data, "classes", $this->input->post('vid'), "class_id");
            $count       = $this->input->post('count');
            $answer_id   = $this->input->post('aid');
            $class_date1 = $this->input->post('class_date1');
            $fromtime1   = $this->input->post('fromtime1');
            $totime1     = $this->input->post('totime1');
            $class_date  = $this->input->post('class_date');
            $fromtime    = $this->input->post('fromtime');
            $totime      = $this->input->post('totime');
            $this->gpi_model->update($data, "classes", $this->input->post('vid'), "class_id");
            $i = 0;
            foreach ($answer_id as $answer_id) {
                $data      = array(
                    'class_date' => date('Y-m-d', strtotime($class_date1[$i])),
                    'fromtime' => date('H:i ', strtotime($fromtime1[$i])),
                    'totime' => date('H:i ', strtotime($totime1[$i]))
                );
                $ansupdate = $this->gpi_model->update($data, "class_date", $answer_id, "class_date_id");
                $i++;
            }
            $j = 0;
            if ($class_date != "") {
                foreach ($class_date as $class) {
                    $data2 = array(
                        'class_id' => $this->input->post('vid'),
                        'class_date' => date('Y-m-d', strtotime($class)),
                        'fromtime' => date('H:i ', strtotime($fromtime[$j])),
                        'totime' => date('H:i ', strtotime($totime[$j]))
                    );
                    $this->gpi_model->insert($data2, 'class_date');
                    $j++;
                }
            }
            $this->session->set_flashdata('update_msg', 'Class Successfully Updated...');
            header("Location: " . base_url() . "admin/classes/classes_view");
        }
    }
    // }
    //   class date *************************************************************************************** 
    public function add_classes_date()
    {
        $this->load->library('form_validation');
       
        if ($this->form_validation->run() == FALSE) {
            $data['content'] = 'admin/classes_date_insert';
            $this->load->view('admin/layout/layout', $data);
        } else {
          
        }
    }
    public function deleteupdaterow()
    {
        if ($this->input->post('class_date_id') != "") {
            $this->db->delete('class_date', array(
                'class_date_id' => $this->input->post('class_date_id')
            ));
            echo $this->input->post('class_date_id');
        }
    }
}