<?php

  class service extends CI_Controller

  {


	  function service_view($id)
	  {
		    $data['service_id']=$id;
		    $data['content'] = 'service_view';
		    $this->load->view('layout/layout',$data);
	  }
	   function unemployed_view()
	  {
		    $data['content'] = 'unemployed_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function parttime_employed_view()
	  {
		    $data['content'] = 'parttime_employed_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function student_mellenials_view()
	  {
		    $data['content'] = 'student_mellenials_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function fulltime_employed_view()
	  {
		    $data['content'] = 'fulltime_employed_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function self_employed_view()
	  {
		    $data['content'] = 'self_employed_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function small_bussiness_view()
	  {
		    $data['content'] = 'small_bussiness_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function sesion_bussiness_view()
	  {
		    $data['content'] = 'sesion_bussiness_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function corporate_view()
	  {
		    $data['content'] = 'corporate_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function government_view()
	  {
		    $data['content'] = 'government_view';
		    $this->load->view('layout/layout',$data);
	  }
	  function faith_based_view()
	  {
		    $data['content'] = 'faith_based_view';
		    $this->load->view('layout/layout',$data);
	  }
	  
       
	 }

	  

  

  

  

