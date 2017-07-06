<?php
 error_reporting(0);
 class tickets extends CI_Controller
  {
	  
	   function __construct() {
     
     parent::__construct();
     if($this->session->userdata("admin_id") == "") {
     redirect(base_url()."admin/login");
     }
        
   }
	public  function add_tickets()
	
	  {
		  
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('class_id', 'Class Name', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');
			$this->form_validation->set_rules('date_id', 'Select Date', 'required');
		   $this->form_validation->set_rules('ticket_name', 'Tickets Name', 'required');
		   $this->form_validation->set_rules('price', 'Price', 'required');
		   $this->form_validation->set_rules('ticket_qty', 'Tickets Quantity', 'required');
		   $this->form_validation->set_rules('fee', 'Fee', 'required');
		  	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
				 $data['main_cls'] = 'manage_tickets';
				 $data['class'] = "add_tickets";
			  	 $data['content'] = 'admin/tickets_insert';
		   		 $this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{  
		  	        $data=array(
		    		'class_id'=>$this->input->post('class_id'),
					'date_id'=>$this->input->post('date_id'),
					'ticket_type'=>$this->input->post('level_id'),
					'ticket_name'=>$this->input->post('ticket_name'),
					'price'=>$this->input->post('price'),
					'ticket_qty'=>$this->input->post('ticket_qty'),
					'master_qty'=>$this->input->post('ticket_qty'),
					'fee'=>$this->input->post('fee'),
					
		  		    );
					
					
		      $this->gpi_model->insert($data,'tickets');
			  $this->session->set_flashdata('insert_msg','Ticket Successfully Inserted...');
		      header("Location: ".base_url()."admin/tickets/tickets_view");
		    } 
	  }
	  
	  function tickets_view()
	  {
			$this->load->library('pagination');
			$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',2);
            $per_page =$pages->pages;
			$qry = "select * from `tickets` ";
			if($this->input->get('tname') != "" &&  $this->input->get('tname') != "undefined") {
				$tname = $this->input->get('tname');
					if($tname==1)
					{
					  $qry.="order by `ticket_name` ASC";
					}
					else
					{
						 $qry.="order by `ticket_name` DESC";
					}
				} 
				
				if($this->input->get('tdate') != "" &&  $this->input->get('tdate') != "undefined") {
				$tdate = $this->input->get('tdate');
					if($tdate==1)
					{
					  $qry.="order by `ticket_name` ASC";
					}
					else
					{
						 $qry.="order by `ticket_name` DESC";
					}
				} 
				
				if($this->input->get('tprice') != "" &&  $this->input->get('tprice') != "undefined") {
				$tprice = $this->input->get('tprice');
					if($tprice==1)
					{
					  $qry.="order by `price` ASC";
					}
					else
					{
						 $qry.="order by `price` DESC";
					}
				} 
				if($this->input->get('tquantity') != "" &&  $this->input->get('tquantity') != "undefined") {
				$tquantity = $this->input->get('tquantity');
					if($tquantity==1)
					{
					  $qry.="order by `master_qty` ASC";
					}
					else
					{
						 $qry.="order by `master_qty` DESC";
					}
				} 	
				$offset = $this->input->get('per_page');
		
				if(!$offset)
					$offset = 0;
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['page_query_string'] = TRUE;
				$config['base_url']= base_url().'admin/tickets/tickets_view/?result=true&tname='.$tname.'&tdate='.$tdate.'&tprice='.$tprice.'&tquantity='.$tquantity.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
			$data['main_cls'] = 'manage_tickets';
			$data['class'] = "view_tickets";
		    $data['content'] = 'admin/tickets_view';
		    $this->load->view('admin/layout/layout',$data);
	  }
	   function tickets_ajax_view()
	  {
			$this->load->library('pagination');
			$pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',2);
            $per_page =$pages->pages;
			$qry = "select * from `tickets` ";
			if($this->input->get('tname') != "" &&  $this->input->get('tname') != "undefined") {
				$tname = $this->input->get('tname');
					if($tname==1)
					{
					  $qry.="order by `ticket_name` ASC";
					}
					else
					{
						 $qry.="order by `ticket_name` DESC";
					}
				} 
				
			 if($this->input->get('tdate') != "" &&  $this->input->get('tdate') != "undefined") {
				$tdate = $this->input->get('tdate');
					if($tdate==1)
					{
					  $qry.="order by `ticket_name` ASC";
					}
					else
					{
						 $qry.="order by `ticket_name` DESC";
					}
				} 
				if($this->input->get('tprice') != "" &&  $this->input->get('tprice') != "undefined") {
				$tprice = $this->input->get('tprice');
					if($tprice==1)
					{
					  $qry.="order by `price` ASC";
					}
					else
					{
						 $qry.="order by `price` DESC";
					}
				} 	
				if($this->input->get('tquantity') != "" &&  $this->input->get('tquantity') != "undefined") {
				$tquantity = $this->input->get('tquantity');
					if($tquantity==1)
					{
					  $qry.="order by `master_qty` ASC";
					}
					else
					{
						 $qry.="order by `master_qty` DESC";
					}
				} 	
				$offset = $this->input->get('per_page');
		
				if(!$offset)
					$offset = 0;
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['page_query_string'] = TRUE;
				$config['base_url']= base_url().'admin/tickets/tickets_view/?result=true&tname='.$tname.'&tdate='.$tdate.'&tprice='.$tprice.'&tquantity='.$tquantity.''; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
			 $data['class'] = "manage_tickets";
		   $this->load->view('admin/ticket_view_ajax',$data);
	  }
	  function delete_tickets($id)
	  {
		  $table="tickets";
		  $primary="ticket_id";
	      $this->db->delete($table, array($primary => $id));
		  $this->session->set_flashdata('delete_msg','Ticket Successfully Deleted...');
		   $data['class'] = "manage_tickets";
	      header("Location: ".base_url()."admin/tickets/tickets_view");
	  }
	  
	   function getsubcats($catid) {
		$getsubcats=$this->gpi_model->getrecordbyid("class_date", "class_id", $catid);
		foreach($getsubcats as $subcat) {
		?>
        	<option value="<?php echo $subcat->class_date_id; ?>"><?php echo $subcat->class_date; ?></option>
        <?php
		}
	}  
		 
		  function update_tickets($id)
        {  
	       $this->load->library('form_validation');
	       $this->form_validation->set_rules('class_id', 'Class Name', 'required');
		    $this->form_validation->set_rules('level_id', 'Select Level', 'required');
			 $this->form_validation->set_rules('date_id', 'Select Date', 'required');
		   $this->form_validation->set_rules('ticket_name', 'Tickets Name', 'required');
		   $this->form_validation->set_rules('price', 'Price', 'required');
		   $this->form_validation->set_rules('new_ticket_qty', 'Tickets Quantity', 'required');
		   $this->form_validation->set_rules('fee', 'Fee', 'required');
		  	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');  
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  	$data['id'] = $id;
				 $data['main_cls'] = 'manage_tickets';
			 	$data['class'] = "update_tickets";
				$data['content'] = 'admin/tickets_update';
				$this->load->view('admin/layout/layout',$data);
			}
	 
			else
			{ 
			         $new_value=$this->input->post('new_ticket_qty');
			
			         $old_master_qty=$this->input->post('old_master_qty');
					 $old_ticket_qty=$this->input->post('old_ticket_qty');
					 $total=$old_master_qty-$old_ticket_qty;
					 $master_value= $new_value-$total;
					  if($new_value<=$total)
					  {
						$this->session->set_flashdata('msg','Invalid Value Ticket Quantity. Value Must be more than Sold Ticket Quantity');  
						header("Location: ".base_url()."admin/tickets/update_tickets/".$id);
					  }
					  else
					{ 
		             $data=array(
		    		'class_id'=>$this->input->post('class_id'),
					'ticket_name'=>$this->input->post('ticket_name'),
					'date_id'=>$this->input->post('date_id'),
					'ticket_type'=>$this->input->post('level_id'),
					'price'=>$this->input->post('price'),
					'ticket_qty'=>$master_value,
					'master_qty'=>$new_value,
					'fee'=>$this->input->post('fee'),
		  		    );
		       	$this->gpi_model->update($data,"tickets",$this->input->post('vid'),"ticket_id");
				$this->session->set_flashdata('update_msg','Ticket Successfully Updated...');
		      	header("Location: ".base_url()."admin/tickets/tickets_view");
					 }
		     }
	  	}
		
		
       //   Ticket Sale ///////////////////////////////////////////////////////////		
	    function ticket_sell_view()
	  	{
		    $this->load->library('pagination');
			 $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',3);
             $per_page =$pages->pages;
			$qry = "select * from `ticket_sell` ";
			
			$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
			$config['total_rows'] = $this->db->query($qry)->num_rows();
			$config['per_page']= $per_page;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['uri_segment'] = 4;
			$config['base_url']= base_url().'admin/tickets/ticket_sell_view'; 
			$this->pagination->initialize($config);
			$data['paginglinks'] = $this->pagination->create_links();    
			if($data['paginglinks'] != '') {
			$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
			} else {
			$data['pagermessage'] = '';
			} 
			$qry .= " limit {$per_page} offset {$offset} ";
			
			$data['qry'] = $this->db->query($qry)->result(); 
			  $data['main_cls'] = 'manage_tickets';
			 	$data['class'] = "ticket_sell_view";
				
		    $data['content'] = 'admin/ticket_sell_view';
		    $this->load->view('admin/layout/layout',$data);
	  	}
			
	  function delete_tickets_sell($id)
	  {
		  $table="ticket_sell";
		  $primary="ticket_sell_id";
	      $this->db->delete($table, array($primary => $id));
		  $this->session->set_flashdata('delete_msg','Ticket Sales Successfully Deleted...');
		   $data['class'] = "manage_tickets";
	      header("Location: ".base_url()."admin/tickets/ticket_sell_view");
	  }
	  
//   Ticket Inventory ///////////////////////////////////////////////////////////		  
		function ticket_inventory_view()
	  		{
				$this->load->library('pagination');
				 $pages=$this->gpi_model->getrecordbyidrow('paging','paging_id',4);
                 $per_page =$pages->pages;
				$qry = "select * from `tickets` ";
				$offset = ($this->uri->segment(4) != '' ? $this->uri->segment(4):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 4;
				$config['base_url']= base_url().'admin/tickets/ticket_inventory_view'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
				 $data['main_cls'] = 'manage_tickets';
			 	$data['class'] = "ticket_inventory_view";
		        $data['content'] = 'admin/ticket_inventory_view';
		        $this->load->view('admin/layout/layout',$data);
	  		}
	}
	  
  
  
  
