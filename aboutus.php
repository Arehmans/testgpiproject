<?php

  class aboutus extends CI_Controller

  {
//this is my coomit

	  function aboutus_view($id)

	  {
           $data['aboutus_id']=$id;
		    $data['content'] = 'aboutus_view';

		    $this->load->view('layout/layout',$data);

	  }//this is my coomitdddd
       function aboutus_view_leadership()

	  {

		    $data['content'] = 'aboutus_leadership_view';

		    $this->load->view('layout/layout',$data);

	  }
	   function aboutus_view_sponsers()

	  {

		    $data['content'] = 'aboutus_sponsers_view';

		    $this->load->view('layout/layout',$data);

	  }
	   function aboutus_view_testimonies()

	  {

		    $data['content'] = 'aboutus_testimonies_view';

		    $this->load->view('layout/layout',$data);

	  }
	   function aboutus_view_service()

	  {

		    $data['content'] = 'service_view';

		    $this->load->view('layout/layout',$data);

	  }
	  
      
	  
	 }

	  

  

  

  

