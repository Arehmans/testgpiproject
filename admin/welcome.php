<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class welcome extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata("admin_id") == "") {
            redirect(base_url() . "admin/login");
        }
    }
    public function view_servey()
    {
        $this->load->library('pagination');
        $pages                 = $this->gpi_model->getrecordbyidrow('paging', 'paging_id', 15);
        $per_page              = $pages->pages;
        $qry                   = "select * from `survey_1` ";
        $offset                = ($this->uri->segment(4) != '' ? $this->uri->segment(4) : 0);
        $config['total_rows']  = $this->db->query($qry)->num_rows();
        $config['per_page']    = $per_page;
        $config['first_link']  = 'First';
        $config['last_link']   = 'Last';
        $config['uri_segment'] = 4;
        $config['base_url']    = base_url() . 'admin/welcome/view_servey';
        $this->pagination->initialize($config);
        $data['paginglinks'] = $this->pagination->create_links();
        if ($data['paginglinks'] != '') {
            $data['pagermessage'] = 'Showing ' . ((($this->pagination->cur_page - 1) * $this->pagination->per_page) + 1) . ' to ' . ($this->pagination->cur_page * $this->pagination->per_page) . ' of ' . $this->pagination->total_rows;
        } else {
            $data['pagermessage'] = '';
        }
        $qry .= " limit {$per_page} offset {$offset} ";
        $data['serveys'] = $this->db->query($qry)->result();
        $data['content'] = 'admin/servey_view';
        $this->load->view('admin/layout/layout', $data);
    }
    public function view_servey_details($id)
    {
        $data['id']      = $id;
        $data['content'] = 'admin/servey_details';
        $this->load->view('admin/layout/layout', $data);
    }
    public function view_servey_2($id)
    {
        $data['id']      = $id;
        $data['content'] = 'admin/servey_2_view';
        $this->load->view('admin/layout/layout', $data);
    }
    function delete_servery($id)
    {
        $table   = "survey_1";
        $primary = "survey_id";
        $this->db->delete($table, array(
            $primary => $id
        ));
        $this->session->set_flashdata('delete_survey', 'Survey Successfully Deleted...');
        header("Location: " . base_url() . "admin/welcome/view_servey");
    }
    public function view_servey3()
    {
        $data['content'] = 'admin/servey_3_view';
        $this->load->view('admin/layout/layout', $data);
    }
    function delete_survey_3($id)
    {
        $table   = "survey_3";
        $primary = "survey_id";
        $this->db->delete($table, array(
            $primary => $id
        ));
        $this->session->set_flashdata('delete_survey1', 'Survey Successfully Deleted...');
        header("Location: " . base_url() . "admin/welcome/view_servey");
    }
    public function view_survey_details_3($id)
    {
        $data['id']      = $id;
        $data['content'] = 'admin/servey_details_3';
        $this->load->view('admin/layout/layout', $data);
    }
    function gettabledata($catid)
    {
        $getsubcats = $this->gpi_model->getrecordbyid("survey_1", "level_id", $catid);
        //foreach($getsubcats as $subcat) {
?>

		



		<table class="table table-striped table-bordered">

			<thead>



				<tr>



					<th>Name</th>

					<th>Email</th>

					<th>Action</th>

				</tr>

			</thead>

			<?php
        foreach ($getsubcats as $servey) {
            // $username=$this->gpi_model->get_user('users',$servey->user_id);
?>

				<tbody>

					<tr>

						<td><?php
            echo $servey->fname;
?></td>



						<td><?php
            echo $servey->email;
?></td>

						<?php
            if ($servey->level_id == 1) {
?>

						<td><a class="delete" href="<?php
                echo base_url();
?>admin/welcome/delete_servery/<?php
                echo $servey->survey_id;
?>"><span class="glyphicon glyphicon-trash" style="float:right;"></span></a>



							<a href="<?php
                echo base_url();
?>admin/welcome/view_servey_details/<?php
                echo $servey->survey_id;
?>"><span style="float:left;"></span>View</a></td>



							<?php
            } else if ($servey->level_id == 2) {
?>

							<td><a class="delete" href="<?php
                echo base_url();
?>admin/welcome/delete_servery/<?php
                echo $servey->survey_id;
?>"><span class="glyphicon glyphicon-trash" style="float:right;"></span></a>



								<a href="<?php
                echo base_url();
?>admin/welcome/view_servey_2/<?php
                echo $servey->survey_id;
?>"><span style="float:left;"></span>View</a></td>

								<?php
            } else if ($servey->level_id == 0) {
?>

								<td><a class="delete" href="<?php
                echo base_url();
?>admin/welcome/delete_servery/<?php
                echo $servey->survey_id;
?>"><span class="glyphicon glyphicon-trash" style="float:right;"></span></a>



									<a href="<?php
                echo base_url();
?>admin/welcome/view_survey_details_3/<?php
                echo $servey->survey_id;
?>"><span style="float:left;"></span>View</a></td>

									<?php
            }
?>



								</tr>			

							</tbody>

						</table>



						<?php
        }
    }
    function gettabledata3($catid)
    {
        $getsubcats = $this->gpi_model->getrecordbyid("survey_3", "level_id", $catid);
        //foreach($getsubcats as $subcat) {
?>

					<table class="table table-striped table-bordered">

						<thead>



							<tr>

								<th>User Name</th>

								<th>Name</th>

								<th>Email</th>

								<th>Action</th>

							</tr>

						</thead>

						<?php
        foreach ($getsubcats as $servey) {
            $username = $this->gpi_model->get_user('users', $servey->user_id);
?>







							<tbody>

								<tr>

									<td><?php
            echo $username->username;
?></td>

									<td><?php
            echo $username->first_name . "&nbsp;" . $username->last_name;
?></td>

									<td><?php
            echo $username->email;
?></td>





									<td><a class="delete" href="<?php
            echo base_url();
?>admin/welcome/delete_survey_3/<?php
            echo $servey->survey_id;
?>"><span class="glyphicon glyphicon-trash" style="float:right;"></span></a>



										<a href="<?php
            echo base_url();
?>admin/welcome/view_survey_details_3/<?php
            echo $servey->survey_id;
?>"><span style="float:left;"></span>View</a></td>







									</tr>			

								</tbody>

							</table>



							<?php
        }
    }
    public function change_show_at_home()
    {
        $resp = array(
            'status' => false,
            'msg' => 'Error'
        );
        $val  = $this->input->post('val');
        $vid  = $this->input->post('vid');
        if ($vid > 0) {
            $out = $this->gpi_model->update(array(
                'show_at_home' => $val
            ), 'videos', $vid, 'electures_id');
            if ($out == true)
                $resp = array(
                    'status' => true,
                    'msg' => 'Status Updated'
                );
        }
        echo json_encode($resp);
        exit;
    }
    public function change_publish()
    {
        //$resp  = array('status'=>false,'msg'=>'Unpublished');
        $resp      = array(
            'status' => false,
            'msg' => 'Error'
        );
        $published = $this->input->post('if_published');
        if ($published == 0 || $published == 1) {
            $data['video_id'] = $this->input->post('e_id');   
            $data['status']   = $this->input->post('if_published');
            if ($data['video_id'] > 0) {
                $out = $this->gpi_model->update($data, 'videos', $data['video_id'], 'electures_id');
                if ($out == true)
                    $resp = array(
                        'status' => true,
                        'msg' => ($data['status'] == 0) ? 'Lecture Unpublished' : 'Lecuture Published'
                    );
            }
        }
        echo json_encode($resp);
        exit;
    }
    public function change_featured()
    {
        $resp = array(
            'status' => false,
            'msg' => 'Error'
        );
        $val  = $this->input->post('val');
        $vid  = $this->input->post('vid');
        // echo $val;
        if ($vid > 0) {
            $out = $this->gpi_model->update(array(
                'features' => $val
            ), 'videos', $vid, 'electures_id');
            if ($out == true)
                $resp = array(
                    'status' => true,
                    'msg' => 'Status Updated'
                );
        }
        echo json_encode($resp);
        exit;
    }
   
  
  public function change_webinar()
    {
        $resp = array(
            'status' => false,
            'msg' => 'Error'
        );
        $val  = $this->input->post('val');
        $vid  = $this->input->post('vid');
        if ($vid > 0) {
            $out = $this->gpi_model->update(array(
                'webinar' => $val
            ), 'videos', $vid, 'electures_id');
            if ($out == true)
                $resp = array(
                    'status' => true,
                    'msg' => 'Status Updated'
                );
        }
        echo json_encode($resp);
        exit;
    }
   
   

   /*	public function index()
    
    {
    
    if($this->session->userdata('userid') == "")
    
    {
    
    header("Location: ".base_url()."admin/welcome/login");
    
    }
    
    $data['content'] = 'admin/news_view';
    
    $this->load->view('admin/layout/layout',$data);
    
    }
    
    
    
    
    
    
    
    public function users_profile()
    
    {
    
    
    
    $data['content'] = 'users_profile';
    
    $this->load->view('layout/layout',$data);
    
    }
    
    
    
    public function edit_user($id)
    
    {
    
    $this->load->library('form_validation');
    
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    
    $this->form_validation->set_rules('email', 'Email ID', 'required|valid_email');
    
    //	$this->form_validation->set_rules('password', 'Password', 'required');
    
    
    
    //	$this->form_validation->set_rules('contactno', 'Contact No', 'required|numeric');
    
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
    
    
    
    
    
    if ($this->form_validation->run() == FALSE)
    
    {
    
    
    
    $data['id'] = $id;
    
    $data['content'] = 'edit_user';
    
    $this->load->view('layout/layout',$data);
    
    }
    
    else
    
    
    
    {
    
    
    
    if($_FILES["photo"]["name"]!="" and (!empty($_FILES["photo"]["name"]))) 
    
    {
    
    $filename=uniqid();
    
    
    
    $path_info = pathinfo($_FILES["photo"]["name"]);
    
    $fileExtension = $path_info['extension'];
    
    
    
    $config['upload_path'] = './FileUpload/Userprofilepic/';
    
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    
    $config['file_name'] = $filename.".".$fileExtension;
    
    
    
    $this->load->library('upload', $config);
    
    $this->upload->initialize($config);
    
    $this->upload->do_upload('photo');
    
    
    
    
    
    
    
    $data=array(
    
    'first_name'=>$this->input->post('first_name'),
    
    'last_name'=>$this->input->post('last_name'),
    
    'email'=>$this->input->post('email'),
    
    'password'=>md5($this->input->post('password')),
    
    'telephone_no'=>$this->input->post('p1').' '.$this->input->post('p2').' '.$this->input->post('p3'),
    
    'dob'=>$this->input->post('date').'/'.$this->input->post('month').'/'.$this->input->post('year'),
    
    'photo'=>$this->input->post('photo'),
    
    'photo'=>$filename.".".$fileExtension
    
    
    
    
    
    );
    
    echo	$this->dbquery->update($data, "user",$id, "user_id");
    
    
    
    redirect(base_url()."welcome/users_profile");
    
    
    
    }
    
    else{
    
    $data2=array(
    
    'first_name'=>$this->input->post('first_name'),
    
    'last_name'=>$this->input->post('last_name'),
    
    'email'=>$this->input->post('email'),
    
    'password'=>md5($this->input->post('password')),
    
    'telephone_no'=>$this->input->post('p1').' '.$this->input->post('p2').' '.$this->input->post('p3'),
    
    'dob'=>$this->input->post('date').'/'.$this->input->post('month').'/'.$this->input->post('year')
    
    );
    
    }
    
    $this->dbquery->update($data2, "user",$id, "user_id");
    
    redirect(base_url()."welcome/users_profile");
    
    
    
    }
    
    }
    
    
    
    public function delete_user($id)
    
    {
    
    
    
    $table = "user";
    
    $primary = "user_id";
    
    $this->dbquery->delete($table,$primary,$id);
    
    redirect(base_url()."welcome/users_profile");
    
    }
    
    
    
    
    
    
    
    //
    
    public function logout()
    
    {
    
    $this->session->unset_userdata('userid');
    
    redirect(base_url()."admin/welcome");
    
    
    
    }
    
    public function elearning()
    
    {
    
    
    
    $data['content'] = 'elearning';
    
    $this->load->view('layout/layout',$data);
    
    }
    
    public function search()
    
    {
    
    
    
    $data['content'] = 'search';
    
    $this->load->view('layout/layout',$data);
    
    }
    
    public function add_certification()
    
    {
    
    
    
    $data['content'] = 'add_certification';
    
    $this->load->view('layout/layout',$data);
    
    }
    
    
    
    
    
    function login()
    
    
    
    
    
    
    
    {
    
    $this->load->library('form_validation');
    
    
    
    $this->form_validation->set_rules('email', 'email', 'required');
    
    $this->form_validation->set_rules('password', 'Password', 'required');
    
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
    
    
    
    if($this->form_validation->run() == FALSE)
    
    {
    
    $data['content'] = 'admin/login';
    
    $this->load->view('admin/layout/login',$data);
    
    }
    
    else
    
    
    
    {
    
    $login=$this->gpi_model->getlogin($this->input->post("email"), $this->input->post('password'));
    
    
    
    if($login) 
    
    {
    
    
    
    $this->session->set_userdata("userid", $login->user_id);
    
    redirect(base_url()."admin/welcome");
    
    
    
    }
    
    else
    
    {	
    
    $this->session->set_flashdata('msg','Wrong Username And Password');
    
    //					$this->session->set_userdata("error_msg", 'email and password incorrect');
    
    redirect(base_url()."admin/welcome/login");
    
    }
    
    }
    
    }
    
    
    
    
    
    
    
    public function forgotpassword()
    
    { 	
    
    $this->load->library('form_validation');
    
    
    
    $this->form_validation->set_rules('email', 'email', 'required');
    
    $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
    
    
    
    if($this->form_validation->run() == FALSE)
    
    {
    
    $data['content'] = 'forgotpassword';
    
    $this->load->view('layout/login',$data);
    
    
    
    
    
    }
    
    else
    
    
    
    {	
    
    $email= $this->input->post('email');
    
    $query=$this->dbquery->get_useruniqid_by_email("user",$email);
    
    
    
    $verify_reg_code=$query->verify_reg_code;
    
    $verify_reg_code;
    
    
    
    $this->load->library('email');
    
    $this->email->from('tp.ansh5@gmail.com', 'CertManager');
    
    $this->email->to($this->input->post('email'));
    
    $this->email->subject('Certificate Reset Password Verification');
    
    $this->email->message('Hi.  Please follow the Link mentioned below to activate your account  '.base_url()."resetpassword/reset_password/".$verify_reg_code);
    
    $this->email->send();
    
    //	echo $this->email->print_debugger();
    
    $this->session->set_flashdata("msg","Check your email to activate your account");
    
    
    
    
    
    }
    
    
    
    }*/
}















































