<?php
  class news extends CI_Controller
  {
  function news_view()
	  {
		      $this->load->library('pagination');
			
                $per_page =10;
				$qry = "select * from `news` order by news_id DESC";
				$offset = ($this->uri->segment(3) != '' ? $this->uri->segment(3):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 3;
				$config['base_url']= base_url().'news/news_view/'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
		    $data['content'] = 'news_more';
		    $this->load->view('layout/layout',$data);
	  }
  }
	  