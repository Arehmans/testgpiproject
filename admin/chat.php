<?php

class Chat extends CI_Controller {
	
	public function __construct()
	    {
		parent::__construct();
		 if($this->session->userdata("admin_id") == "") {
         redirect(base_url()."login");
     }
		 
		}
     function chat_view($id)  
		    {
			  	   //$data['content'] = 'chat_view';
				     $data['id']=$id;
	               $this->load->view('admin/chat_view',$data);
				    $qry= $this->gpi_model->new_chat_updatenotification('chat','from_msg','to_msg',$id,$this->session->userdata('admin_id'));
                    foreach($qry as $res)
                    {
			        $data=array(
		    		'notification'=>0,
		  		); 
			 $this->gpi_model->update($data,"chat",$res->chat_id,"chat_id");  
					}
			}
		
	 	function chat_user_view()  
		{
			 
			  $this->load->view('admin/chat_user_view');	    
		}
		
	function show_chat_user()
	{
		if($this->session->userdata('admin_id')!=1){
		     $userlevel= $this->gpi_model->getrecordbyidrow('users','user_id', $this->session->userdata('admin_id')); 
		      $user= $this->gpi_model->get_chat_user('users',$userlevel->level_id,0);
				foreach($user as $user)
				{
					
               
			 $qry= $this->gpi_model->new_chat_updatenotification_red('chat','from_msg','to_msg',$user->user_id,$this->session->userdata('admin_id'));
                          if(!empty($qry))
						  {
									if($qry->notification==1){ ?>
                                      <li class="notifyuser"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
                                    <?php }
									else if($user->online==1){
									  ?>
				<li class="active"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
									<?php  }else{  ?>
                <li ><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>                      
										<?php 
                                      }
						  }
						  else
						  {
							  if($user->online==1){
									  ?>
				<li class="active"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
									<?php  }else{  ?>
                <li ><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>                      
										<?php 
                                      }
						  }
       		 }
		}
		else 
		{ ?>
			<div> <a href="#expert_user" data-toggle="collapse">Subject Matter Expert (SME)</a>
             <div id="expert_user">
            <?php
			 $user= $this->gpi_model->get_admin_chat_user('users',1);
				foreach($user as $user)
				{?>
                <?php  if($user->user_id==1){ } else {
				$qry= $this->gpi_model->new_chat_updatenotification_red('chat','from_msg','to_msg',$user->user_id,$this->session->userdata('admin_id'));
                               if(!empty($qry))
							   {
									if($qry->notification==1){ ?>
                                      <li class="notifyuser"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
                                    <?php }
									else if($user->online==1){
									  ?>
				<li class="active"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
									<?php  }else{  ?>
                <li ><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>                      
										<?php 
                                      }
							   }
							   else
							   {
								   if($user->online==1){
									  ?>
				<li class="active"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
									<?php  }else{  ?>
                <li ><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>                      
										<?php 
                                      }
							   }
                  }
				}
			?></div>
            </div>
            <div> <a href="#user" data-toggle="collapse">User </a>
            <div id="user"> <?php
			 $user= $this->gpi_model->get_admin_chat_user('users',0);
				foreach($user as $user)
				{?>
                <?php  if($user->user_id==1){ } else { 
				$qry= $this->gpi_model->new_chat_updatenotification_red('chat','from_msg','to_msg',$user->user_id,$this->session->userdata('admin_id'));
                           if(!empty($qry))
						   {
									if($qry->notification==1){ ?>
                                      <li class="notifyuser"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
                                    <?php }
									else if($user->online==1){
									  ?>
				<li class="active"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
									<?php  }else{  ?>
                <li ><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>                      
										<?php 
                                      }
						   }else
						   {
							   if($user->online==1){
									  ?>
				<li class="active"><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>
									<?php  }else{  ?>
                <li ><a href="<?php echo base_url();?>admin/chat/chat_view/<?php echo $user->user_id;?>"><?php if($user->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$user->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle"> <?php echo $user->last_name;?></a></li>                      
										<?php 
                                      }
						   }
                  }
				}
			?></div>
            </div>
            <?php	
	     
		}
        
     } 
	public function send_message()
		{
			$id=$this->input->post('hid');
			if($this->session->userdata('admin_id')==1)
			{
			$status=2;
			}else
			{
				$status=1;
			}
			$data=array(		
			'message_content'=>$this->input->post('message'),
			'from_msg'=>$this->session->userdata('admin_id'),
			'to_msg'=>$this->input->post('hid'),
			'notification'=>1,
			'status'=>$status,	
			);
			$this->gpi_model->insert($data,'chat');
			?>
              
              
              <div class="chatlists" id="chatlists">
			 			<?php
		  	$msage=$this->gpi_model->new_getmessage('chat','from_msg','to_msg',$this->input->post('hid'),$this->session->userdata('admin_id'));
			foreach($msage as $msage)
			{?>
                      <?php
                             $qry= $this->gpi_model->getrecordbyidrow('users','user_id', $this->session->userdata('admin_id'));
							 $qry1= $this->gpi_model->getrecordbyidrow('users','user_id',$this->input->post('hid'));
					if($this->session->userdata('admin_id')!=1){
								 
					if($msage->status==1){ 		 
					   ?>
                      
                    <div class="media">
                        <div class="media-body text-right">
                        	<div class="bubble bubble--alt"><?php echo  $msage->message_content?></div>
                            <div class="clear"></div>
                            <small><?php echo $qry->first_name;?></small>
                        </div>
                    	<div class="media-right"><?php if($qry->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                    </div>
				 <?php } else if($msage->status==0 || $msage->status==2){ ?>
                 <div class="media">
                    	<div class="media-left"><?php if($qry1->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry1->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                        <div class="media-body">
                        	<div class="bubble"><?php echo  $msage->message_content?> </div>
                            <div class="clear"></div>
                            <small><?php echo $qry1->first_name;?></small>
                        </div>
                    </div>
                  <?php  } } else if($msage->status==2){ 		 
					   ?>
                      
                    <div class="media">
                        <div class="media-body text-right">
                        	<div class="bubble bubble--alt"><?php echo  $msage->message_content?></div>
                            <div class="clear"></div>
                            <small><?php echo $qry->first_name;?></small>
                        </div>
                    	<div class="media-right"><?php if($qry->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                    </div>
				 <?php }  else if($msage->status==1 || $msage->status==0){  ?>
                 <div class="media">
                    	<div class="media-left"><?php if($qry1->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry1->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                        <div class="media-body">
                        	<div class="bubble"><?php echo  $msage->message_content?> </div>
                            <div class="clear"></div>
                            <small><?php echo $qry1->first_name;?></small>
                        </div>
                    </div>
                  <?php  }  ?>
				<?php
			 	
			}
			?>
            </div>
            <?php
		}
	  
	  function show_message()
	   {
		   if($this->input->post('hid')){
			$qry= $this->gpi_model->new_chat_updatenotification('chat','from_msg','to_msg',$this->input->post('hid'),$this->session->userdata('admin_id'));
			
                    foreach($qry as $res)
                    {
			        $data=array(
		    		'notification'=>0,
		  		); 
			 $this->gpi_model->update($data,"chat",$res->chat_id,"chat_id");  
					}
		   }   
			
		  	$msage=$this->gpi_model->new_getmessage('chat','from_msg','to_msg',$this->input->post('hid'),$this->session->userdata('admin_id'));
			
			
			foreach($msage as $msage)
			{?>
                      <?php
                             $qry= $this->gpi_model->getrecordbyidrow('users','user_id', $this->session->userdata('admin_id'));
							 $qry1= $this->gpi_model->getrecordbyidrow('users','user_id',$this->input->post('hid'));
					if($this->session->userdata('admin_id')!=1){
								 
					if($msage->status==1){ 		 
					   ?>
                      
                    <div class="media">
                        <div class="media-body text-right">
                        	<div class="bubble bubble--alt"><?php echo  $msage->message_content?></div>
                            <div class="clear"></div>
                            <small><?php echo $qry->first_name;?></small>
                        </div>
                    	<div class="media-right"><?php if($qry->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                    </div>
				 <?php } else if($msage->status==0 || $msage->status==2){ ?>
                 <div class="media">
                    	<div class="media-left"><?php if($qry1->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry1->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                        <div class="media-body">
                        	<div class="bubble"><?php echo  $msage->message_content?> </div>
                            <div class="clear"></div>
                            <small><?php echo $qry1->first_name;?></small>
                        </div>
                    </div>
                  <?php  } } else if($msage->status==2){ 		 
					   ?>
                      
                    <div class="media">
                        <div class="media-body text-right">
                        	<div class="bubble bubble--alt"><?php echo  $msage->message_content?></div>
                            <div class="clear"></div>
                            <small><?php echo $qry->first_name;?></small>
                        </div>
                    	<div class="media-right"><?php if($qry->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                    </div>
				 <?php }  else if($msage->status==1 || $msage->status==0){  ?>
                 <div class="media">
                    	<div class="media-left"><?php if($qry1->profile!=""){ ?><img src="<?php echo base_url()."assets/uploads/".$qry1->profile; ?>" <?php } else { ?><img src="<?php echo base_url()."assets/uploads/noimage.jpg"?>" <?php } ?>  class="img-circle" width="50"></div>
                        <div class="media-body">
                        	<div class="bubble"><?php echo  $msage->message_content?> </div>
                            <div class="clear"></div>
                            <small><?php echo $qry1->first_name;?></small>
                        </div>
                    </div>
                  <?php  }  ?>
				<?php
			 	
			}
			
			 
	  }
	  
	  
	 
}