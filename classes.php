
<?php
  error_reporting(0);
  class classes extends CI_Controller
  {
	  function __construct() {
		
		parent::__construct();
		
		  $this->load->helper('cookie');
		}			
  function classes_view($id)
	  {
		  /*if(isset($_POST['promo_submit']))
		  {
			 
		   echo "hello";
		   $qty=$this->input->post("qty");
		   echo $qty;
		  }
		  
		  
		 /* if(isset($_POST['guest_user']))
		  {
			 
		   
			 $name=$this->input->post("first_name");
			 $last=$this->input->post("last_name");
			 $email=$this->input->post("email");
			 $data=array(	"first_name"=>$name,
			    	        "last_name"=>$last,
							"email" =>$email,
			  );
			
			 $guest=$this->gpi_model->insert($data,"guest_user");
			 $guest_id = $this->db->insert_id();
			// echo $guest_id;
			$this->session->set_userdata("guest_id",$guest_id);
			$last_id=$this->session->userdata('guest_id');
			echo $last_id;		 
		  }*/
		  
		   $this->session->unset_userdata('guest_id');
				$data['cls_id']=$id;
			    $data['content'] = 'classes_view';
			    $this->load->view('layout/layout',$data);
				$this->session->sess_expiration = '1800'; 
		
	  }
	     
	function guest($id)
	{ 
	$cookie = array(
			'name'   => 'name',
			'value'  => $this->input->post('first_name'),
			'expire' => time()+86500,
			'domain' => '.gpiwin.appexos.com',
			'path'   => '/',
			'prefix' => 'gpi_',
		);
			
		set_cookie($cookie);
	           $data['cls_id']=$id;
			
				$data['content'] = 'classes_view';
			   $this->load->view('layout/layout',$data);
	}
	function get()
 {
  echo $this->input->cookie('demo',true);
 }

	function fblogin()
	{ 
	     $this->load->view('fblogin');
	}
	function getcity($catid) {
		$getsubcats=$this->hopinhop_model->getrecordbyid("cities", "state_id", $catid);
		foreach($getsubcats as $subcat) {
		?>
        	<option value="<?php echo $subcat->id; ?>"><?php echo $subcat->name; ?></option>
        <?php
		}
	}
	  
	  function upcommingclass_view($id)
	  {
		  $data['id']=$id;
		  $data['content'] = 'upcommingclass_view';
		  $this->load->view('layout/layout',$data);
		  //$this->load->view('upcommingclass_view');  
	  }
	 function upcommingclass_more()
	  {
		        $this->load->library('pagination');
                $per_page = 10;
				//$qry = "select * from `classes` order by class_id DESC";
				$qry = "SELECT * FROM classes WHERE status = 1 ORDER BY class_order ASC";
				//echo $this->db->last_query();die;
				
				$offset = ($this->uri->segment(3) != '' ? $this->uri->segment(3):0);
				$config['total_rows'] = $this->db->query($qry)->num_rows();
				$config['per_page']= $per_page;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['uri_segment'] = 3;
				$config['base_url']= base_url().'classes/upcommingclass_more/'; 
				$this->pagination->initialize($config);
				$data['paginglinks'] = $this->pagination->create_links();    
				if($data['paginglinks'] != '') {
				$data['pagermessage'] = 'Showing '.((($this->pagination->cur_page-1)*$this->pagination->per_page)+1).' to '.($this->pagination->cur_page*$this->pagination->per_page).' of '.$this->pagination->total_rows;
				} else {
				$data['pagermessage'] = '';
				} 
				$qry .= " limit {$per_page} offset {$offset} ";
				
				$data['qry'] = $this->db->query($qry)->result(); 
		    $data['content'] = 'classes_home_more';
		    $this->load->view('layout/layout',$data);
	  } 
	
	 function getselectdate($catid) 
	 {
		 
		  $qry= $this->gpi_model->getrecordbyidrow('class_date','class_date_id',$catid);
         echo  date('l, F d, Y',strtotime($qry->class_date));?> from <?php echo date('h:i A ',strtotime($qry->fromtime))?> to <?php echo date('h:i A ',strtotime($qry->totime))?> (EDT)
		 <?php        
         
			 // }
	}
	   function gettabledata($catid) {
		//$getsubcats=$this->gpi_model->getrecordbyid('tickets','class_id',$this->input->post('date_id'));
		//foreach($getsubcats as $subcat) {
			 $daterespone= $this->gpi_model->getrecordbyidrow('class_date','class_date_id',$catid)
					
			?>
            
            <table class="table table-striped" id="table_id_1">

        			<thead>

                      <tr>

                        <th colspan="7">Ticket Information</th>

                      </tr>

                    </thead>

        			<tbody>

                      <tr> 

                        <th>Ticket Type</th>
                        <th>Ticket Name</th>
                        <th>Remaining</th>

                      

                        <th>Price</th>

                        <th>Fee</th>

                        <th>Quantity</th>
                        <th>Booking Now</th>

                      </tr>

					<?php
					$qry1= $this->gpi_model->getrecordbyid('tickets','date_id',$catid);

                    foreach($qry1 as $res1) {
					  $level=$this->gpi_model->get_levels("levels",$res1->ticket_type); 
					  $classes=$this->gpi_model->getrecordbyidrow("classes",'class_id',$res1->class_id); 

				   ?>
                       
                    
                      
                 
                        <tr>
                                
                        <th><?php echo $level->level_name;?></th>
                         <td><?php echo $res1->ticket_name;?></td>
                        <td><?php echo $res1->ticket_qty;?> Tickets</td>
    
                     
                        
                        <td>$<?php echo $res1->price;?></td>
                        
                        <td>$<?php echo $res1->fee?></td>
                        
                        <td>
                         <form class="paypal promocode pay" action="<?php echo base_url(); ?>payments/do_purchase/" method="post" id="paypal_form">
                          <input type="hidden" id="dat" value="<?php echo $date->class_date;?>" class="class_date" name="dat"/>
                        	<input type="hidden" name="ticket_id" class="ticket_id" id="ticket_id" value="<?php echo $res1->ticket_id;?>" />
                
                <input type="hidden" name="class_id" id="class_id" class="class_id" value="<?php echo $classes->class_id;?>" /> 
                <input type="hidden" name="class_name" class="class_name" id="class_name" value="<?php echo $classes->class_name; ?>" />               <input type="hidden" id="pric" class="pric" value="<?php echo $res1->price;?>" name="price"/>
                <input type="hidden" name="level_id" class="level_id" id="level_id" value="<?php echo $level->level_id; ?>" />
                <input type="hidden" name="tranction_id" value="<?php echo uniqid(); ?>" / >
                                <select name="qty" id="qty" class="qty">
                                 
                                     <?php for($i=0;$i<=$res1->ticket_qty;$i++ ){ ?>
                                      <?php if($i==1){
													  $selected="selected='selected'";

													} else {

													  $selected="";

													} ?>
                                    <option value="<?php echo $i;?>"  <?php echo $selected;?>><?php echo $i?>  </option>
                                   <?php  } ?> 
                                </select>
                           
                        </td>
       
                          
                    
                  
              <?php 
                if($this->session->userdata("guest_id")=="" && $this->session->userdata("gpi_id")=="")  {
                ?>	
                 <?php  if($classes->promotional_class==1) { ?> 
                 <td><input type="submit" value="Pay Now" name="paynow" onclick="data()" data-toggle="modal" data-target="#promo_guest_id"   class="btn btn-primary guest_class promo_control " /></td>
                <?php } else { ?>   
                <td><input type="submit" value="Pay Now" name="paynow" onclick="data()" data-toggle="modal" data-target="#guest_id"   class="btn btn-primary class_control promo_control" /></td>
                <?php
				}
				} 
			   	else if($this->session->userdata("guest_id")=="" && $this->session->userdata("gpi_id")!=""){ ?>
                 <?php if($classes->promotional_class==1) { ?>
                <td><input type="submit" value="Pay Now" name="paynow"  data-toggle="modal" data-target="#promontional_class_model"   class="btn btn-primary promo_control" /></td> <?php } else { ?> 
                <td><input type="submit" value="Pay Now" name="paynow" onclick="data()" class="btn btn-primary paynow  class_control"  /></td>
                <?php } }
			   else if($this->session->userdata("guest_id")!="" && $this->session->userdata("gpi_id")==""){ ?>
               <?php if($classes->promotional_class==1) { ?>
                <td><input type="submit" value="Pay Now" name="paynow"  data-toggle="modal" data-target="#promontional_class_model"   class="btn btn-primary promo_control" /></td> <?php } else { ?>
                <td><input type="submit" value="Pay Now" name="paynow" onclick="data()" class="btn btn-primary paynow class_control " /></td>
				<?php } }
				else {?>
                 <?php if($classes->promotional_class==1) { ?>
                <td><input type="submit" value="Pay Now" name="paynow"  data-toggle="modal" data-target="#promontional_class_model"   class="btn btn-primary promo_control" /></td> <?php } else { ?>
                 <td><input type="submit" value="Pay Now" name="paynow"  class="btn btn-primary paynow class_control" /></td> <?php } } ?>
              	  
                      </tr>
                    </form>
                   
				<?php
                }
				?>
                
 
                </tbody>
                </table>
                <?php
				}
		}
  
?>  
  