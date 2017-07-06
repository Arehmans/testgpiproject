<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class welcome extends CI_Controller {
  	public function test(){
  		print_r(unserialize('a:51:{s:8:"mc_gross";s:6:"149.00";s:7:"invoice";s:8:"73588673";s:22:"protection_eligibility";s:8:"Eligible";s:14:"address_status";s:9:"confirmed";s:12:"item_number1";s:0:"";s:8:"payer_id";s:13:"SEWPZT44ZGPZ6";s:3:"tax";s:4:"0.00";s:14:"address_street";s:14:"1 Main Terrace";s:12:"payment_date";s:25:"00:33:16 May 11, 2016 PDT";s:14:"payment_status";s:7:"Pending";s:7:"charset";s:12:"windows-1252";s:11:"address_zip";s:7:"W12 4LQ";s:11:"mc_shipping";s:4:"0.00";s:11:"mc_handling";s:4:"0.00";s:10:"first_name";s:8:"personal";s:20:"address_country_code";s:2:"GB";s:12:"address_name";s:13:"personal test";s:14:"notify_version";s:3:"3.8";s:6:"custom";s:0:"";s:12:"payer_status";s:8:"verified";s:8:"business";s:23:"business.test@lokun.com";s:15:"address_country";s:14:"United Kingdom";s:14:"num_cart_items";s:1:"1";s:12:"mc_handling1";s:4:"0.00";s:12:"address_city";s:13:"Wolverhampton";s:11:"verify_sign";s:56:"AbsKutEMGIIVA.bc5f.QGWeXdrdDAEh4aMve5hkijPaHkiqpxIGeca77";s:11:"payer_email";s:23:"personal.test@lokun.com";s:12:"mc_shipping1";s:4:"0.00";s:4:"tax1";s:4:"0.00";s:6:"txn_id";s:17:"0RA24588DX774835N";s:12:"payment_type";s:7:"instant";s:9:"last_name";s:4:"test";s:13:"address_state";s:13:"West Midlands";s:10:"item_name1";s:48:"New Birth Birmingham Business Ready - Levels 1-2";s:14:"receiver_email";s:23:"business.test@lokun.com";s:9:"quantity1";s:1:"1";s:11:"receiver_id";s:13:"PNHWNC4KN6TCJ";s:14:"pending_reason";s:14:"multi_currency";s:8:"txn_type";s:4:"cart";s:10:"mc_gross_1";s:6:"149.00";s:11:"mc_currency";s:3:"USD";s:17:"residence_country";s:2:"GB";s:8:"test_ipn";s:1:"1";s:19:"transaction_subject";s:0:"";s:13:"payment_gross";s:6:"149.00";s:12:"ipn_track_id";s:12:"2d21f16200ae";s:3:"tid";s:2:"32";s:7:"classid";s:2:"83";s:7:"user_id";i:617;s:8:"level_id";s:1:"8";s:13:"if_guset_user";s:1:"1";}'));
  	}
    public  function welcome_view()
	{
		
		//$data['content'] = 'welcome_messege';
		//$this->load->view('layout/layout',$data);	
		$this->load->model('common_model');
		
		//$mysqlVideo= $this->common_model->select_where_limit_order("*","videos",array("status"=>1,'show_at_home'=>1),0,5,"electures_id","ASC");
		
		//$mysqlVideo = $this->db->query("SELECT * FROM videos WHERE status = 1 AND show_at_home = 1 AND video_order !=''  ORDER BY video_order ASC limit 0,5");		
			$mysqlVideo = $this->db->query("select * from videos WHERE status = 1 AND show_at_home = 1
order by if(video_order = '' or video_order is null,1,0),video_order limit 0,5");

//		print_r($this->db->last_query());die;
		$result['mysqlVideo'] = $mysqlVideo;
		
		
		$mysqlPkg =  $this->common_model->select_where('*','tbl_package',array('pkg_public'=>1));
		$result['mysqlPkg'] = $mysqlPkg;
		
		$this->load->view('welcome_messege',$result);
	}	
	
	public function video_view(){			
	$this->load->library('pagination');				
	//$videodata1= $this->common_model->select_where('*','videos',array("status"=>1,'show_at_home'=>1));
	
	$videodata1 = $this->db->query("select * from videos WHERE status = 1 AND show_at_home = 1
order by if(video_order = '' or video_order is null,1,0),video_order");
			
	//echo $this->db->last_query();die;	
	$config['total_rows'] = $videodata1->num_rows();			
	$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',25);		
	$per_page =$pages->pages;				
	$offset = $this->input->get('per_page');				
	if(!$offset)			
	$offset = 0;											
	$config['per_page']= $per_page;						
	$config['first_link'] = 'First';			
	$config['last_link'] = 'Last';			
	$config['uri_segment'] = 4;				
	$config['page_query_string'] = TRUE; 			
	$config['base_url']= base_url().'welcome/video_view/?result=true'; 			
	$this->pagination->initialize($config);			
	$data['paginglinks'] = $this->pagination->create_links();  			
	if($data['paginglinks'] != '') {				
	$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;				
	} else {				
	$data['pagermessage'] = '';				} 								
	//$qry = "Where show_at_home = 1 AND status = 1 limit  {$per_page} offset {$offset} ";						
	//$videodata= $this->db->query("SELECT * FROM videos ".$qry."");
	
	$videodata = $this->db->query("select * from videos Where show_at_home = 1 AND status = 1
order by if(video_order = '' or video_order is null,1,0),video_order limit  {$per_page} offset {$offset} ");	
	
			
	if($videodata->num_rows() >0)			
	$videodata = $videodata->result();			 
	$data['videodata'] = $videodata;		    
	$data['content'] = 'videos_view';		    
	$this->load->view('layout/layout',$data);			
	}
	
	public  function aboutus_view()
	{
		$data['content'] = 'aboutus';
	    $this->load->view('layout/layout',$data);
		
	}
	public  function announcement_view($id)
	{
		$data['id']=$id;
		$data['content'] = 'announcement_view';
	    $this->load->view('layout/layout',$data);

	}

    function popup_details() {

        $email_address = $this->input->post('email_address');
        $zip_code      = $this->input->post('zip_code');

       $this->common_model->insert_array('tbl_newsletter',array('email'=>$email_address,'zipcode'=>$zip_code));

       /* $table =  "<table style='border: 1px solid #ccc;border-collapse:collapse;width: 50%;'>
        <tr style='border:1px solid #ccc;'>
            <th style='padding:8px;'>Email</th>
            <th style='padding:8px;'>Zipcode</th>

        </tr>
        <tr style='background-color: #f2f2f2'>
            <td style='padding:8px;text-align:center;'>". $email_address ."</td>
            <td style='padding:8px;text-align:center;'>". $zip_code ."</td>
        </tr></table>";

    	$this->load->library('email');





		$useremail = 'aliakbar1to5@gmail.com';
	  	$useremail .=", ceo.appexos@gmail.com";
        $this->email->from('owner@gpi.com', "Administrator");
        $this->email->to($useremail);
        $this->email->subject("Newsletter signup");
        $this->email->message($table);
    	$this->email->set_mailtype("html");
        $this->email->send();*/
      
		
		
		$this->session->set_flashdata('message','yes');
		redirect('welcome');
    }


    	public  function event_view($id,$event_name='')
    	{
    	    $data['event_name'] = $event_name;
    		$data['id']=$id;
    		$data['content'] = 'events_view';
    	    $this->load->view('layout/layout',$data);

    	}

         function events_more()
	  {
		      $this->load->library('pagination');

                $per_page =10;
				$qry = "select * from `tbl_events` order by id DESC";
				$offset = ($this->uri->segment(3) != '' ? $this->uri->segment(3):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 3;
				$config['base_url']= base_url().'welcome/events_more/';
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				}
				$qry .= " limit {$per_page} offset {$offset} ";

				$data['qry'] = $this->db->query($qry)->result();
		    $data['content'] = 'events_more';
		    $this->load->view('layout/layout',$data);
	  }




	public  function news_view($id)
	{
		$data['id']=$id;
		$data['content'] = 'news_home_more';
	    $this->load->view('layout/layout',$data);
		
	}
	
	public function index(){
		
		$qry=$this->gpi_model->getrecordbyidrow('user_counter','counter_id',1);	
		
		$cureent_count=$qry->counts;
		$new_count=$cureent_count+1;
				
		$data=array(
			'counts'=>$new_count,				   
        	);
		$this->gpi_model->update($data,"user_counter",1,"counter_id"); 					
		//$mysqlVideo= $this->common_model->select_where_limit_order("*","videos",array("status"=>1,'show_at_home'=>1),0,5,"electures_id","ASC");		
		//$mysqlVideo = $this->db->query("SELECT * FROM videos WHERE status = 1 AND show_at_home = 1 AND video_order !=''  ORDER BY video_order ASC limit 0,5");		
		$mysqlVideo = $this->db->query("select * from videos WHERE status = 1 AND show_at_home = 1
order by if(video_order = '' or video_order is null,1,0),video_order limit 0,5");
		$result['mysqlVideo'] = $mysqlVideo;
	
		$mysqlPkg =  $this->common_model->select_where('*','tbl_package',array('pkg_public'=>1));
		$result['mysqlPkg'] = $mysqlPkg;
		
		
		$this->load->view('welcome_messege',$result);
	}

	public function load_states(){
		$resp  = array('status'=>false);
		$country = $this->input->post('country');
		if($country){
			$province_list = province_list($country);
			if($province_list)
				$resp = array('status'=>true,'states' => $province_list);
		}
		echo json_encode($resp);
		exit;
	}
	
	public function users_profile(){
		
		$data['content'] = 'users_profile';
		$this->load->view('layout/layout',$data);
	}
	
	
	
	
	public function logout()
	{
		$this->session->unset_userdata('gpi_id');
		redirect(base_url()."welcome");
		
	}
	
	function contactus_view()

	  {

		    $data['content'] = 'contactus_view';

		    $this->load->view('layout/layout',$data);

	  }
	
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
