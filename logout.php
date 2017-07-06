<?php

class Logout extends CI_Controller {

   
   function __construct() {
		
		parent::__construct();
    }

    function index() {
        $this->load->library('session');
        $this->session->sess_destroy();
        header('Location: ' . base_url(), true);
    }

}