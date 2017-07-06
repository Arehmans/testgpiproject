<?php
 class booking extends CI_Controller
  {
	public  function add_booking()
	
	  {
		   $this->load->library('form_validation');
	       $this->form_validation->set_rules('email', 'Email', 'required');
		   $this->form_validation->set_rules('password', 'Password', 'required');
		 
		  	   
		   $this->form_validation->set_error_delimiters('<div style="color: red;">', '</div>');
		   
		   if ($this->form_validation->run() == FALSE)
			{
			  //	 $data['content'] = 'admin/tickets_insert';
		   		 $this->load->view('signup_insert');
			}
	 
			else
			{  
		  	        $data=array(
		    		'email'=>$this->input->post('email'),
					'password'=>$this->input->post('password'),
					
					
		  		    );
		      $this->gpi_model->insert($data,'login');
		     // header("Location: ".base_url()."admin/tickets/tickets_view");
		    } 
	  }
	  
	
	  
	  function delete_tickets($id)
	  {
		  $table="tickets";
		  $primary="ticket_id";
	      $this->db->delete($table, array($primary => $id));
	      header("Location: ".base_url()."admin/tickets/tickets_view");
	  }
	  
	  function update_tickets()
      {  
		   
		   if(isset($_POST['book']))
		  {
			echo 'hello';
		            /* $data=array(
		    		'ticket_qty'=>$this->input->post($book),
					
		  		    );
		       	$this->gpi_model->update($data,"tickets",$this->input->post('vid'),"ticket_id");
		      	//header("Location: ".base_url()."admin/tickets/tickets_view");*/
		     }
	  	}
	}
	  
  
  
  
