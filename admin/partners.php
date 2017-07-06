<?php

class Partners extends CI_Controller
{
    function __construct()
    {
        
        parent::__construct();
       
	   
        // ini_set('error_reporting', E_ALL);
        // ini_set('display_errors', 'On');  //On or Off
        if ($this->session->userdata("admin_id") == "") {
            redirect(base_url() . "admin/login");
        }
        
    }
    
	
	function partner_exists() {
		$id = $this->input->post('parter_id');
		$str = $this->input->post('partner_code');
		if($id =="")
			$mysqlPartner = $this->common_model->select_where('*','tbl_partners',array('partner_code'=>$str));
		else
			$mysqlPartner = $this->common_model->select_where('*','tbl_partners',array('partner_code'=>$str,'id <>'=>$id));
		if($mysqlPartner->num_rows() >0) {
			$this->form_validation->set_message('partner_exists', 'Partner Code Must be uniqe.');
			return false;
		} else {
			return true;
		}	
	}
	
	
    function add_partner()
    {
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('organization_name', 'Organization', 'required|min_length[3]');
        $this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
        $this->form_validation->set_rules('contact_no', 'Contact No', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('city_id', 'City', 'required');
	    $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
		$this->form_validation->set_rules('percent', 'Percent', 'required');
	//	$this->form_validation->set_rules('partner_code', 'Partner Code', 'required|callback_partner_exists');
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		
        if ($this->form_validation->run() == FALSE) {
            $data['content'] = 'admin/partners/add_partner';
			$data['main_cls'] = 'partners';
			$data['class'] = "add_partner";
            $this->load->view('admin/layout/layout', $data);
            
        }  else{
			
			$organization_name = $this->input->post('organization_name');
			$subString =  substr($organization_name, 0, 3);
			$subString =  strtoupper($subString);
			$randomNo = rand ( 10 , 99 );
			$partnerCode = $subString.$randomNo;
			
			
			
			   
            $data     = array(
                'organization_name' => $this->input->post('organization_name'),
                'contact_name' => $this->input->post('contact_name'),
                'contact_no' => $this->input->post('contact_no'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'city_id' => $this->input->post('city_id'),
                'state_id' => $this->input->post('state_id'),
                'zip_code' => $this->input->post('zip_code'),
                'percent' => $this->input->post('percent'),
                'partner_code' => $partnerCode
            );
			
			$data['status'] = 0;
			if(isset($_POST['status']))
			$data['status'] = 1;
            
            $this->gpi_model->insert($data, 'tbl_partners');
            
            
            $config['mailtype'] = 'html';
            $this->load->library('email', $config);
            $this->load->library('table');
            
            $this->email->from('webmaster@gpiwin.com', 'GPI');
            $this->email->to('rabbiaAnam456@gmail.com');
            $this->email->subject('GPI Partner');
            $message = '<html>
                <head>
                    <title>WelCome to GPI</title>
                </head>
                <body>
                <p align="center"><h1><b>Wel Come to GPI</b></h1></p><br/>
                <table align="center"><br/>
                </table><br/>
                <table>
                <tr>
                        <td><b>Email  : </b></td>
                        <td>rabbiaAnam456@gmail.com</td>
                    </tr>
                    <tr>
                        <td><b>PARTNER CODE : </b></td>
                        <td>' . $this->input->post('partner_code') . '</td>
                    </tr>
                    <br /></table></body></html>';
					
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata('insert_msg', 'Partner Created Successfully.....');
            header("Location: " . base_url() . "admin/partners/view_partners");
            
        }
        
    }
    
	
    function view_partners()
    {
        $this->load->library('pagination');
        $pages    = $this->gpi_model->getrecordbyidrow('paging', 'paging_id', 17);
        $per_page = $pages->pages;
        $qry      = "select * from `tbl_partners`";
        if ($this->input->get('ustatus') != "" && $this->input->get('ustatus') != "undefined") {
            $ustatus = $this->input->get('ustatus');
            if ($ustatus == 1) {
                $qry .= "order by `user_status` DESC ";
            } else {
                $qry .= "order by `user_status` ASC";
            }
        }
        if ($this->input->get('utype') != "" && $this->input->get('utype') != "undefined") {
            $utype = $this->input->get('utype');
            if ($utype == 1) {
                $qry .= "order by `expert` DESC";
            } else {
                $qry .= "order by `expert` ASC";
            }
        }
        if ($this->input->get('ufname') != "" && $this->input->get('ufname') != "undefined") {
            $ufname = $this->input->get('ufname');
            if ($ufname == 1) {
                $qry .= "order by `first_name` ASC";
            } else {
                $qry .= "order by `first_name` DESC";
            }
        }
        if ($this->input->get('ucontact') != "" && $this->input->get('ucontact') != "undefined") {
            $ucontact = $this->input->get('ucontact');
            if ($ucontact == 1) {
                $qry .= "order by `phone_no` ASC ";
            } else {
                $qry .= "order by `phone_no` DESC";
            }
        }
        if ($this->input->get('uemail') != "" && $this->input->get('uemail') != "undefined") {
            $uemail = $this->input->get('uemail');
            if ($uemail == 1) {
                $qry .= "order by `email` ASC";
            } else {
                $qry .= "order by `email` DESC";
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
        $config['base_url']          = base_url() . 'admin/users/view_partners/?result=true&organization_name=' . @$ufname . '&contact_name=' . @$uemail . '&contact_no=' . @$ustatus . '&email=' . @$utype . '&percent=' . @$ucontact . '';
        $this->pagination->initialize($config);
        $data['paginglinks'] = $this->pagination->create_links();
        if ($data['paginglinks'] != '') {
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $this->pagination->per_page) + 1) . ' to ' . ($this->pagination->cur_page * $this->pagination->per_page) . ' of ' . $this->pagination->total_rows;
        } else {
            $data['pagermessage'] = '';
        }
        $qry .= " limit {$per_page} offset {$offset} ";
        $data['qry'] = $this->db->query($qry);
		$data['main_cls'] = 'partners';
		$data['class'] = "view_partner";
	
        
        $data['content'] = 'admin/partners/view_partners';
        $this->load->view('admin/layout/layout', $data);
        
    }
   
    
    function delete_partner($id)
    {
        
        $table = "tbl_partners";
        $primary = "id";
		
		$mysqlRelation = $this->common_model->select_where('*','tbl_class_partner',array('partner_id'=>$id));
        if($mysqlRelation->num_rows() == 0){
			$this->db->delete($table, array(
				$primary => $id
			));
			$this->session->set_flashdata('delete_msg', 'Partner Successfully Deleted...');
		}else{
			$this->session->set_flashdata('errorMsg', 'This Partner cannot be deleted as it has Sales records associated with it.');
		}
        header("Location: " . base_url() . "admin/partners/view_partners");
        
    }
    
    
     function update_partner($id)
     {
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('organization_name', 'Organization', 'required|min_length[3]');
        $this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
        $this->form_validation->set_rules('contact_no', 'Contact No', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('city_id', 'City', 'required');
	    $this->form_validation->set_rules('zip_code', 'Zip Code', 'required');
		$this->form_validation->set_rules('percent', 'Percent', 'required');
		
		//$this->form_validation->set_rules('partner_code', 'Partner Code', 'required|callback_partner_exists['.$id.']');
        $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		
        if ($this->form_validation->run() == FALSE) {
			
			$mysqlPartner = $this->common_model->select_where('*','tbl_partners',array('id'=>$id));
			$data['mysqlPartner'] = $mysqlPartner->row_array();
            $data['content'] = 'admin/partners/edit_partner';
			$data['main_cls'] = 'partners';
			$data['class'] = "update_partner";
            $this->load->view('admin/layout/layout', $data);
            
        }  else{   
		
			$organization_name = $this->input->post('organization_name');
			$subString =  substr($organization_name, 0, 3);
			$subString =  strtoupper($subString);
			$randomNo = rand ( 10 , 99 );
			$partnerCode = $subString.$randomNo;
		
            $data     = array(
                'organization_name' => $this->input->post('organization_name'),
                'contact_name' => $this->input->post('contact_name'),
                'contact_no' => $this->input->post('contact_no'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'city_id' => $this->input->post('city_id'),
                'state_id' => $this->input->post('state_id'),
                'zip_code' => $this->input->post('zip_code'),
                'percent' => $this->input->post('percent'),
                'partner_code' => $partnerCode
            );
			
			$data['status'] = 0;
			if(isset($_POST['status']))
			$data['status'] = 1;
			
		
			$this->common_model->update_array(array('id'=>$id),'tbl_partners',$data); 
            $config['mailtype'] = 'html';
            $this->load->library('email', $config);
            $this->load->library('table');
            $this->email->from('webmaster@gpiwin.com', 'GPI');
            $this->email->to('rabbiaAnam456@gmail.com');
            $this->email->subject('GPI Partner');
            $message = '<html>
                <head>
                    <title>WelCome to GPI</title>
                </head>
                <body>
                <p align="center"><h1><b>Wel Come to GPI</b></h1></p><br/>
                <table align="center"><br/>
                </table><br/>
                <table>
                <tr>
                        <td><b>Email  : </b></td>
                        <td>rabbiaAnam456@gmail.com</td>
                    </tr>
                    <tr>
                        <td><b>PARTNER CODE : </b></td>
                        <td>' . $this->input->post('partner_code') . '</td>
                    </tr>
                    <br /></table></body></html>';
					
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata('insert_msg', 'Partner Updated Successfully.....');
            header("Location: " . base_url() . "admin/partners/view_partners");
            
        }
        
    }
    
	
	
	function partner_sales(){
		
		$mysqlRows = $this->db->query("SELECT tbl_class_partner.*,classes.class_name,tickets.ticket_name FROM tbl_class_partner JOIN classes ON classes.class_id = tbl_class_partner.class_id JOIN tickets ON  tbl_class_partner.ticket_id = tickets.ticket_id");
		
		
		
		
		$data['mysqlRows'] = $mysqlRows;
		$data['main_cls']  = 'partners';
		$data['class'] 	   = "partner_sale_history";
		$data['content'] = 'admin/partners/parnter_sale';
		$this->load->view('admin/layout/layout',$data);
		
	}
}
