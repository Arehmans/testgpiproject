<?php

class Home extends CI_Controller {

    function __construct() {      
	  parent::__construct();
        $this->load->library('authentication');
    }

    function index() {
        //outsiders keep out
        $this->authentication->sentry();
        $this->load->view('view_home.php');
    }

}