<link rel="stylesheet" href="<?php echo site_url() ?>assets/admin/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo site_url() ?>assets/admin/asset_2/css/font-awesome.css" />

<?php
$user_id =   @$this->session->userdata('gpi_id');
$total_items=0;

?>


<div class="clear pad-20"></div>
<div class="container">
  <div class="row">



    <div class="col-lg-12 text-center" style="margin-top:10px;margin-left:105px;">
  	  <?php if($this->session->flashdata('promo_msg1') != "") { ?>
     	 <div class="alert alert-success" role="alert" style="margin-top:20px;">
			 <?php echo $this->session->flashdata('promo_msg1');?>
         </div>
      <?php } ?>

      <?php if($this->session->flashdata('login_error') != "") { ?>
     	 <div class="alert alert-danger" role="alert" style="margin-top:20px;">
			 <?php echo $this->session->flashdata('login_error');?>
         </div>
      <?php } ?>


         <?php  if(($mysqlResources->num_rows() ==0) && ($mysqlPackages->num_rows() == 0)) { ?>
     	 <div class="alert alert-danger" role="alert" style="margin-top:20px;">
			 <?php echo 'No Items have been added to show here.';?>
         </div>
      <?php } ?>



  </div>

    <div class="col-lg-12" style="margin-top:60px;margin-left:90px">
      <h2 class="text-center text_blck mt-10">Products</h2>
      <div class="container">
        <!--<h2>Purchase Packages</h2>-->



        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>File Icon</th>
              <th>Title of File</th>
              <th>File Type</th>

              <th style="padding-left:80px;">Action</th>
            </tr>
          </thead>
          <tbody>


              <?php if($mysqlResources->num_rows() > 0) {
                  $total_items =1;
              			foreach($mysqlResources->result_array() as $resource_item){

                         ?>
                        <tr>

						<?php $fileType = ""; ?>                        
						 <td><?php if($resource_item['type']==1){$fileType = "pdf"; ?>
                                
                                <i class="fa fa-file-pdf-o fa-2x text-primary"></i>

                                <?php } elseif ($resource_item['type'] ==2){$fileType = "word"; ?>

                                <i class="fa fa-file-word-o fa-2x text_red"></i>

                               <?php } elseif ($resource_item['type'] ==3){ $fileType = "word";?>
								
                                 <i class="fa fa-file-video-o fa-2x text_blue">
                                
                              
                                
                                 <?php } elseif ($resource_item['type'] ==4){ $fileType = "excel"; ?>
								
                                 <i class="fa fa-file-excel-o fa-2x text-warning">
                                
                               
                                 <?php } elseif ($resource_item['type'] ==5){ $fileType = "powerpoint"; ?>
								
                                <i class="fa fa-file-powerpoint-o fa-2x text-success">
                                 
                             
                                <?php } elseif ($resource_item['type'] ==6){ $fileType = "zip"; ?>
								
                                <i class="fa fa-file-zip-o fa-2x text-success">
                                 
                                 
                                <?php } else if($resource_item['type'] == 7){ $fileType = "image";  ?>
								 
								 <i class="fa fa-file-image-o" style="font-size:25px" aria-hidden="true"></i>
								<?php } ?>
								
								
                                </td>
                        <?php 
						$fileName  = $resource_item['resources'];
						if($resource_item['file_name'] !="") {
						 	$fileName = $resource_item['file_name'];
						}
							?>
                        
                            <td style="color:#147db3"><?php echo  $fileName; ?></td>
                            <td><?php echo  $fileType; ?></td>

                            <td style="width:50px;">
                        	<img src="<?php echo site_url('assets/images/Download.png') ?>"  onclick="javascript:window.location.href='<?php echo site_url('user/download_single'.'/'.$resource_item['resources_id'].'/'.'res'); ?>'" style="cursor:pointer;margin-top:-33px;margin-bottom:-33px;margin-left: 15px;"  width="180px;" />
						</td>

                        </tr>
                        
                 <?php        }
               }if( $mysqlPackages->num_rows() >0){
                   $total_items = 1;
                    foreach($mysqlPackages->result_array() as $mysqlPkg){
                ?>
                   <tr>
                    <td><i class="fa fa-file-zip-o fa-2x text-success"> </i></td>
                    <td><?php echo $mysqlPkg['pkg_name'] ?></td>
                    <td>Zip</td>

                    <td style="width:50px;">
                        	<img src="<?php echo site_url('assets/images/Download.png') ?>"  onclick="javascript:window.location.href='<?php echo site_url('user/download_package_access'.'/'.$mysqlPkg['pkg_id']); ?>'" style="cursor:pointer;margin-top:-33px;margin-bottom:-33px;margin-left: 15px;"  width="180px;" />
                    </tr>
             <?php
                    }
                 }  if($total_items == 0) {  ?>
                      <td colspan="5" style="text-align:center;">No records available</td>
               <?php } ?>

              
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>




