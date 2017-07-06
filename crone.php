<?php
class Crone extends CI_Controller

  {
	 
	
	function youtubegallary()
	{
		$data=array('qty'=>12);		               
		$this->gpi_model->update($data,"ticket_sell",34,"ticket_sell_id");
	} 
}    
  
?>