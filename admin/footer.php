<style>

.loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url("<?php echo site_url('assets') ?>/images/gears.gif") 50% 50% no-repeat rgb(249,249,249);
}

input[type="image"]{
outline: none !important;
}
#cke_Upload_168{
    
display:none;
}

#cke_Upload_172{
	display:none;
}


.cke_dialog_tabs a:nth-of-type(3)
{
   display:none;
} 

/*.cke_dialog_tab{
	
	display:none !important;
}*/
</style>
  <link href="<?php echo site_url() ?>assets/css/bootstrap-datetimepicker.css" rel="stylesheet">
<div class="loader"></div>




 <!-- Bootstrap core JavaScript -->

 <!-- Placed at the end of the document so the pages load faster -->

<!-- <script src="<?php echo base_url();?>assets/admin/js/jquery-1.10.2.min.js"></script>  -->
 
 <script src="<?php  echo base_url(); ?>assets/admin/asset_2/js/jquery.form.min.js"></script>

 <script src="<?php echo base_url();?>assets/admin/js/bootstrap.min.js"></script>
 
 
 <!--<script src="<?php echo base_url();?>assets/admin/js/jquery.easing.1.3.js"></script>-->



 </script>
 
   <script type="text/javascript">
$(window).load(function() {

	$(".loader").fadeOut("slow");
})
</script>
 <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript">
 	var site_url = "<?php echo base_url();?>";
 </script>
 <script src="<?php  echo base_url(); ?>assets/admin/asset_2/js/mtree.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>assets/js/nice_editor.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>assets/js/ninja-slider.js"></script>
 <script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
 

 <script type="text/javascript">
//<![CDATA[
<?php /*?> bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });<?php */?>
if($('#editor_id').length >0 ){
	bkLib.onDomLoaded(function() {
		nicEditors.editors.push(
			new nicEditor().panelInstance(
				document.getElementById('editor_id')
				)
			);
	});
}


if($('#editor_id_1').length >0 ){
	bkLib.onDomLoaded(function() {
		nicEditors.editors.push(
			new nicEditor().panelInstance(
				document.getElementById('editor_id_1')
				)
			);
	});
}


  //]]>
</script>


<script type="text/javascript">
	$('#dtable').DataTable({"lengthMenu": [[25, 50, 100], [25, 50, 100]],stateSave: true});
</script>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-36251023-1']);
	_gaq.push(['_setDomainName', 'jqueryscript.net']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<script>

	$(document).ready(function(){

		$("#changepassword").click(function(){

			$("#pass").toggle();

		});



	});
	(function($){
		$(document).ready(function(){
			$('.admin_recipient').change(function(){
				if($('.view_admins').css('display') == 'block'){
					$('.view_admins').slideUp('fast');
				}else{
					$('.view_admins').slideDown('fast');
				}
			});
		});
	})(jQuery);

</script>
<script type="text/javascript">

function myDelFun(){ 
location.href=$('#newDel').attr('href');

}
</script>
<script>

	$(function() {

		var loc = window.location.href;

		$('#blah li').each(function() {

			var link = $(this).find('a').attr('href');

			if(loc == link)

				$(this).addClass('active');

		});

	});


	$("#usertable").addClass("parent-test");

	$(".parent-test").on("click",".delete",function() {

		var r = confirm("Are you Sure ? you want to Delete ?");

		if (r == true) {

			return true;

		} else {

			return false;

		}

	});

	$(document).ready(function () {
		if($('.date-picker').length >0){
			$('.date-picker').daterangepicker({

				singleDatePicker: true,

				calender_style: "picker_4"

			}, function (start, end, label) {

				console.log(start.toISOString(), end.toISOString(), label);

			});

		}
		
	});
	$(document).ready(function(){

	});
	//$(document).ready(main);
	function toggleChevron(e) {
		$(e.target)
		.prev('.panel-heading')
		.find("i.indicator")
		.toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
	}
	$('#accordion').on('hidden.bs.collapse', toggleChevron);
	$('#accordion').on('shown.bs.collapse', toggleChevron);
	(function($){
		$(document).ready(function(){
			// $('#upload_a_video_lecture').click(function(){
			// 	$('.video_upload_form').slideToggle();
			// });
			/*
			$("#upload").submit(function(){
				
				$("#upload").ajaxForm({
					beforeSend:function(){
						$("#prog").show();

						$("#prog").attr('value', '0');
					}, 
					uploadProgress:function(event, position, total, percentComplete){
						$("#prog").attr('value', percentComplete);
						$("#percent").html(percentComplete+'%');
						
					}, 
					success:function(data){
						$("#here").html(data);
					}
				});
				return false;
			});*/
 $('#upload').ajaxForm({
   url:"<?php echo site_url('admin/electures/form_values/'); ?>",
   type:'POST',
   uploadProgress: function (event, position, total, percentComplete){ 
    $('.progress').css('display', 'block');
    $("#prog").width(percentComplete+'%');
    $("#percent").html(percentComplete+'%');
  },
  success:function(resp){
    var result = JSON.parse(resp);
    if(result.status == true){
     $("#here").html(result);
     window.location = "<?php echo site_url('/admin/electures/electures_view'); ?>";
     
   }
 },
 beforeSend:function(){
  $("#prog").show();

  $("#prog").attr('value', '0');
}
});
 $('#update').ajaxForm({
  url:"<?php echo site_url('admin/electures/form_values2'); ?>",
  type:'POST',
  uploadProgress: function (event, position, total, percentComplete){ 
    $('.progress').css('display', 'block');
    $("#prog").width(percentComplete+'%');
    $("#percent").html(percentComplete+'%');
  },
  success:function(resp){
    var result = JSON.parse(resp);
    if(result.status == true){
      $("#here").html(result);
      window.location = "<?php echo site_url('/admin/electures/electures_view'); ?>";

    }
  },
  beforeSend:function(){
    $("#prog").show();

    $("#prog").attr('value', '0');
  }
});
});
})(jQuery);
</script>

<?php /*?><script>

  $(document).ready(function () {

  $("#state_id").change(function() {

 $("#city_id").load("<?php echo base_url(); ?>admin/places/getcity/"+$(this).val());

});

 });

</script>

<?php */?>







<!-- <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>  -->

<script>

	$("#class_date").change(function() {

		$("#extradate").empty();

		var getnumbers=$("#class_date option:selected" ).attr("value");

		for(var i=1;i<=getnumbers;i++) {

			$("#extradate").append(' <div class="form-group"><label for="nam" class="col-sm-3 control-label">Select Date</label><div class="col-sm-9"><input name="date'+i+'" type="text" id="datepicker" class="form-control"   /></div>');

		}

	});
	


</script>



<script>

	$("#state_id").change(function() {



		$("#city_id").load("<?php echo base_url(); ?>admin/classes/getcity/"+$(this).val());

	});

</script>

<script>

//  $("#country_id").change(function() {



  // $("#state_id").load("<?php echo base_url(); ?>admin/classes/getstate/"+$(this).val());

   //});

</script>
<script>

	$("#class_id").change(function() {
		$("#date_id").load("<?php echo base_url(); ?>admin/tickets/getsubcats/"+$(this).val());
	});

</script>

<script>

	$("#level_id").change(function() {
		$("#table_id").load("<?php echo base_url(); ?>admin/welcome/gettabledata/"+$(this).val());
	});

</script>
<script>

	$("#level_id_3").change(function() {
		$("#table_id").load("<?php echo base_url(); ?>admin/welcome/gettabledata3/"+$(this).val());
	});

</script>

<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />

<!-- <script src="//code.jquery.com/jquery-1.8.3.js"></script> -->


<script src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>

<!-- <link rel="stylesheet" href="/resources/demos/style.css" /> -->

<script>

	$(function () {

		$( ".datepicker" ).datepicker();

	});



</script>
<script>


	$("#bt").click(function () {

		for(var i=1;i<=1;i++) {

			$("#wrapdata").append(' <div class="form-group appendupdate"><label for="nam" class="col-sm-3 control-label">Select Date</label><div class="col-sm-9"><input class="form-control datepicker"  type="text" name="class_date[]"  /></div></div><div class="form-group"><label for="nam" class="col-sm-3 control-label">Select Time</label><div class="col-sm-9"><div class="demo">Form &nbsp;<input id="timeformatExample1"  name="fromtime[]" type="text" class="time timeformatExample1 timebox" />&nbsp;&nbsp; To  &nbsp;<input id="timeformatExample2" name="totime[]" type="text" class="time timeformatExample2 timebox" /></div></div></div>');



		}
		$( ".datepicker" ).datepicker();
		$('.timeformatExample1').timepicker();
		$('.timeformatExample2').timepicker();

	});


	$(".remove_date").click(function () {
		$('#wrapdata div:last').remove();
		$('#wrapdata div:last').remove();
		$('#wrapdata div:last').remove();
		$('#wrapdata div:last').remove();
		$('#wrapdata div:last').remove();
	});
 /*
   if (maxAppend > 1)
   	{*/
   		$(".remove_date1").click(function () {

   			if($('.appendupdate').length > 1)
   			{ 


   				$.post("<?php echo base_url();?>admin/classes/deleteupdaterow/",{ class_date_id: $(".update"+$('.appendupdate').length).find('.datepicker').attr("data-vale")} );
   				$('#wrapdata div:last').remove();
   				$('#wrapdata div:last').remove();
   				$('#wrapdata div:last').remove();
   				$('#wrapdata div:last').remove();
   				$('#wrapdata div:last').remove();
   			}
	/*maxAppend--;
   }$("#wrapdata").attr("id","removed");
   $(".wrapdata").attr("id","wrapdata")	*/

 });
   	</script>

   	<script>
   	</script>

   	<script>

   		$("#question").click(function () {

   			for(var i=1;i<=1;i++) {

   				$("#que").append(' <div class="form-group"><label for="nam" class="col-sm-3 control-label">Question</label><div class="col-sm-9"><input class="form-control" type="text" name="questions_name[]" placeholder="Questions Name"/></div>');

   			}


   		});
   	</script>
   	<script>

   		$("#answer").click(function () {

   			for(var i=1;i<=1;i++) {

   				$("#ans").append(' <div class="form-group extra"><label for="nam" class="col-sm-3 control-label">Answers</label><div class="col-sm-9"><input class="form-control" type="text" name="answer_name[]" required placeholder="Answers"/></div></div><div class="form-group extra"><label for="nam" class="col-sm-3 control-label">Point</label><div class="col-sm-9"><input class="form-control" type="text" required name="answer_point[]" placeholder="Point"/></div>');



   			}


   		});


   		$(".remove_answer").click(function () {

   			$('#ans div:last').remove();
   			$('#ans div:last').remove();
   			$('#ans div:last').remove();
   			$('#ans div:last').remove();
   		});
   	</script>
   	<script>

   		$("#churchquestion").click(function () {

   			for(var i=1;i<=1;i++) {

   				$("#churchque").append(' <div class="form-group"><label for="nam" class="col-sm-3 control-label">Question</label><div class="col-sm-9"><input class="form-control" type="text" name="questions_name[]" placeholder="Questions Name"/></div>');

   			}


   		});
   	</script>
   	<script>

   		$("#churchanswer").click(function () {

   			for(var i=1;i<=1;i++) {

   				$("#churchans").append(' <div class="form-group"><label for="nam" class="col-sm-3 control-label">Answers</label><div class="col-sm-9"><input class="form-control" type="text" name="answer_name[]" placeholder="Answers"/></div></div><div class="form-group"><label for="nam" class="col-sm-3 control-label">Point</label><div class="col-sm-9"><input class="form-control" type="text" name="answer_point[]" placeholder="Point"/></div>');

   			}


   		});
   	</script>
   	 <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

   	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />

   	<!--<script src="//code.jquery.com/jquery-1.8.3.js"></script> -->

   	<script src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>

   	<!-- <link rel="stylesheet" href="/resources/demos/style.css" /> -->

   	<script>

   		$(function () {

   			$( "#datepicker" ).datepicker();

   		});



   	</script>

   	<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

   	<script type="text/javascript" src="<?php echo base_url();?>assets/admin/jquery-timepicker-master/jquery.timepicker.js"></script>


   	<script type="text/javascript" src="<?php echo base_url();?>assets/admin/jquery-timepicker-master/lib/bootstrap-datepicker.js"></script>


   	<script type="text/javascript" src="<?php echo base_url();?>assets/admin/jquery-timepicker-master/lib/site.js"></script>


    <link href="<?php echo base_url();?>assets/css/vjs-video-player.min.css" rel="stylesheet" />
<script src="<?php echo base_url();?>assets/js/vjs-video-player.min.js"></script>
    <script>
     $(function() {
      $('.timeformatExample1').timepicker({ 'timeFormat': 'h:ia' });
      $('.timeformatExample2').timepicker({ 'timeFormat': 'h:ia' });
    });
   </script>

   <script>

     var getUrlParameter = function getUrlParameter(sParam) {
      var sPageURL = decodeURIComponent(window.location.search.substring(1)),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

      for (i = 0; i < sURLVariables.length; i++) {
       sParameterName = sURLVariables[i].split('=');

       if (sParameterName[0] === sParam) {
        return sParameterName[1] === undefined ? true : sParameterName[1];
      }
    }
  };

  function removeURLParameter(url, parameter) {
		//prefer to use l.search if you have a location/link object
		var urlparts= url.split('?');   
		if (urlparts.length>=2) {

			var prefix= encodeURIComponent(parameter)+'=';
			var pars= urlparts[1].split(/[&;]/g);

			//reverse iteration as may be destructive
			for (var i= pars.length; i-- > 0;) {    
				//idiom for string.startsWith
				if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
					pars.splice(i, 1);
				}
			}

			url= urlparts[0]+'?'+pars.join('&');
			return url;
		} else {
			return url;
		}
	}
	
	$("#send_msg").click(function() {

		alert($("#message").val());

	});

</script>
<script>

	$(function () {
		$("#promotional_class").click(function () {
			if ($(this).is(":checked")) {
				$("#promo_class_divid").show();
				$(".txt").val(Math.floor(Math.random()*90000) + 10000);
			} else {
				$("#promo_class_divid").hide();
			}
		});
	});

</script>
<script>
	$("#promotional_class").click(function(){
		$(".promo").removeAttr("style");
	});
</script> 



<script>

	$(function () {
		$("#new_folder").click(function () {
			if ($(this).is(":checked")) {
				$("#folder_name_divid").show();
				
			} else {
				$("#folder_name_divid").hide();
			}
		});
	});

</script>   

<script> 

	$(function () {
		$("#public_folder").click(function () {
			if ($(this).is(":checked")) {
				$("#user_div").hide();
				$("#level_div").hide();
				

				$("#user_div").children().next().find("select").val("");
				$("#level_div").children().next().find("select").val("");
				
				
			} else {
				$("#user_div").show();
				$("#level_div").show();
				
				$("#user_div").children().next().find("select").val("");
				$("#level_div").children().next().find("select").val("");
			}
		});
	});

</script>   
<script>
	$("#level_id").change(function(){
		$(this).find("option:selected").each(function(){
			if($(this).attr("value")==""){
				$("#user_div").show();
				$("#public_folder_div").show();

				$("#user_div").children().next().find("select").val("");
				$(".pub").val("");
			}

			else{
				$("#user_div").hide();
				$("#public_folder_div").hide();

				$("#user_div").children().next().find("select").val("");
				$(".pub").val("");
			}
		});
	});


</script>  

<script>
	$("#user_id").change(function(){
		$(this).find("option:selected").each(function(){
			if($(this).attr("value")==""){
				$("#level_div").show();
				$("#public_folder_div").show();

				$("#level_div").children().next().find("select").val("");
				$('.pub').val("");
			}

			else{
				$("#level_div").hide();
				$("#public_folder_div").hide();

				$("#level_div").children().next().find("select").val("");
				$('.pub').val("");
			}
		});

	});

	$("#sorting_ve").change(function() {
		var flag=$(this).val();
		window.location = "<?php echo base_url(); ?>admin/organization_test/organization_test_view/?flag="+flag;
		history.pushState(null, null, "<?php echo base_url(); ?>admin/organization_test/organization_test_view/?flag="+$(this).val());
		
		$("#myresult").load("<?php echo base_url(); ?>admin/organization_test/organization_ajax?flag="+getUrlParameter('flag'));
	});
	

		/*	history.pushState(null, null, "<?php echo base_url(); ?>admin/readiness_test/readiness_test_view/?flag="+$(this).val());
		
		$("#myresult").load("<?php echo base_url(); ?>admin/readiness_test/readiness_ajax?flag="+getUrlParameter('flag')+"&rfname="+getUrlParameter('rfname'));
	});/
	
 /*****/
 $("#sorting_ve2").change(function() {
 	var flag=$(this).val();
 	window.location = "<?php echo base_url(); ?>admin/faith_test/faith_test_view/?flag="+flag;
 	history.pushState(null, null, "<?php echo base_url(); ?>admin/faith_test/faith_test_view/?flag="+$(this).val());

 	$("#myresult").load("<?php echo base_url(); ?>admin/faith_test/faith_ajax?flag="+getUrlParameter('flag'));
 });
</script>
<script>
	$("#sorting_ve1").change(function() {
		var flag=$(this).val();
		window.location = "<?php echo base_url(); ?>admin/readiness_test/readiness_test_view/?flag="+flag;
		if(getUrlParameter('rfname') != undefined || getUrlParameter('rlastname') != undefined || getUrlParameter('rcontactno') != undefined) {
			url = removeURLParameter(window.location.href, "flag");


			history.pushState(null, null, url+"&flag="+flag);

		} else {
			history.pushState(null, null, "<?php echo base_url(); ?>admin/readiness_test/readiness_test_view/?flag="+flag);
		}

		$("#readiness_test_table").load("<?php echo base_url(); ?>admin/readiness_test/readiness_ajax?rfname="+getUrlParameter('rfname')+"&flag="+getUrlParameter('flag')+"&rlastname="+getUrlParameter('rlastname')+"&rcontactno="+getUrlParameter('rcontactno'));

	});

	$(document).on("click", ".rfname", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  rfname=2;
		}
		else
		{
			$(this).addClass("active");
			var  rfname=1;
		}

		if(getUrlParameter('flag') != undefined || getUrlParameter('rlastname') != undefined || getUrlParameter('rcontactno') != undefined) {
			url = removeURLParameter(window.location.href, "rfname");


			history.pushState(null, null, url+"&rfname="+rfname);

		} else {
			history.pushState(null, null, "<?php echo base_url(); ?>admin/readiness_test/readiness_test_view/?rfname="+rfname);
		}

		$("#readiness_test_table").load("<?php echo base_url(); ?>admin/readiness_test/readiness_ajax?rfname="+getUrlParameter('rfname')+"&flag="+getUrlParameter('flag')+"&rlastname="+getUrlParameter('rlastname')+"&rcontactno="+getUrlParameter('rcontactno'));
	});

	$(document).on("click", ".rlastname", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  rlastname=2;
		}
		else 
		{
			$(this).addClass("active");
			var  rlastname=1;
		}

		if(getUrlParameter('flag') != undefined || getUrlParameter('rfname') != undefined || getUrlParameter('rcontactno') != undefined) {
			url = removeURLParameter(window.location.href, "rlastname");


			history.pushState(null, null, url+"&rlastname="+rlastname);

		} else {
			history.pushState(null, null, "<?php echo base_url(); ?>admin/readiness_test/readiness_test_view/?rlastname="+rlastname);
		}

		$("#readiness_test_table").load("<?php echo base_url(); ?>admin/readiness_test/readiness_ajax?rfname="+getUrlParameter('rfname')+"&flag="+getUrlParameter('flag')+"&rlastname="+getUrlParameter('rlastname')+"&rcontactno="+getUrlParameter('rcontactno'));
	});
	$(document).on("click", ".rcontactno", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  rcontactno=2;
		}
		else 
		{
			$(this).addClass("active");
			var  rcontactno=1;
		}

		if(getUrlParameter('flag') != undefined || getUrlParameter('rfname') != undefined || getUrlParameter('rlastname') != undefined) {
			url = removeURLParameter(window.location.href, "rcontactno");


			history.pushState(null, null, url+"&rcontactno="+rcontactno);

		} else {
			history.pushState(null, null, "<?php echo base_url(); ?>admin/readiness_test/readiness_test_view/?rcontactno="+rcontactno);
		}

		$("#readiness_test_table").load("<?php echo base_url(); ?>admin/readiness_test/readiness_ajax?rfname="+getUrlParameter('rfname')+"&flag="+getUrlParameter('flag')+"&rlastname="+getUrlParameter('rlastname')+"&rcontactno="+getUrlParameter('rcontactno'));
	});
</script>  
<script>

	$(document).on("click", ".classname", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  classname=2;
		}
		else
		{
			$(this).addClass("active");
			var  classname=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/classes/classes_view?classname="+classname);

		$("#classresult").load("<?php echo base_url(); ?>admin/classes/classes_ajax?classname="+getUrlParameter('classname'));
	});

	$(document).on("click", ".clevel", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  clevel=2;
		}
		else
		{
			$(this).addClass("active");
			var  clevel=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/classes/classes_view?clevel="+clevel);

		$("#classresult").load("<?php echo base_url(); ?>admin/classes/classes_ajax?clevel="+getUrlParameter('clevel'));
	});

	$(document).on("click", ".caddress", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  caddress=2;
		}
		else
		{
			$(this).addClass("active");
			var  caddress=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/classes/classes_view?caddress="+caddress);

		$("#classresult").load("<?php echo base_url(); ?>admin/classes/classes_ajax?caddress="+getUrlParameter('caddress'));
	});
</script>
<script>
	$(document).on("click", ".ufname", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  ufname=2;
		}
		else
		{
			$(this).addClass("active");
			var  ufname=1;
		}

		if(getUrlParameter('flag') != undefined) {
			url = removeURLParameter(window.location.href, "ufname");
			url = removeURLParameter(url, "ufname");

			history.pushState(null, null, url+"&ufname="+ufname);

		} else {
			history.pushState(null, null, "<?php echo base_url(); ?>admin/users/users_view/?ufname="+ufname);
		}

		$("#usertable").load("<?php echo base_url(); ?>admin/users/user_ajax?ufname="+getUrlParameter('ufname'));
	});

	$(document).on("click", ".uemail", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  uemail=2;
		}
		else
		{
			$(this).addClass("active");
			var  uemail=1;
		}

		history.pushState(null, null, "<?php echo base_url(); ?>admin/users/users_view/?uemail="+uemail);


		$("#usertable").load("<?php echo base_url(); ?>admin/users/user_ajax?uemail="+getUrlParameter('uemail'));
	});


	$(document).on("click", ".ustatus", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  ustatus=2;
		}
		else
		{
			$(this).addClass("active");
			var  ustatus=1;
		}

		history.pushState(null, null, "<?php echo base_url(); ?>admin/users/users_view/?ustatus="+ustatus);


		$("#usertable").load("<?php echo base_url(); ?>admin/users/user_ajax?ustatus="+getUrlParameter('ustatus'));
	});

	$(document).on("click", ".utype", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  utype=2;
		}
		else
		{
			$(this).addClass("active");
			var  utype=1;
		}

		history.pushState(null, null, "<?php echo base_url(); ?>admin/users/users_view/?utype="+utype);


		$("#usertable").load("<?php echo base_url(); ?>admin/users/user_ajax?utype="+getUrlParameter('utype'));
	});

	$(document).on("click", ".ucontact", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  ucontact=2;
		}
		else
		{
			$(this).addClass("active");
			var  ucontact=1;
		}

		history.pushState(null, null, "<?php echo base_url(); ?>admin/users/users_view/?ucontact="+ucontact);


		$("#usertable").load("<?php echo base_url(); ?>admin/users/user_ajax?ucontact="+getUrlParameter('ucontact'));
	});
</script>
<script>

	$(document).on("click", ".level", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  level=2;
		}
		else
		{
			$(this).addClass("active");
			var  level=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/levels/levels_view?level="+level);

		$("#leveltable").load("<?php echo base_url(); ?>admin/levels/levels_ajax?level="+getUrlParameter('level'));
	});

</script>
<script>
function show_detail(user_id){


$("#myModal").modal("show");
  }
</script>
<script>

	$(document).on("click", ".tname", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  tname=2;
		}
		else
		{
			$(this).addClass("active");
			var  tname=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/tickets/tickets_view?tname="+tname);

		$("#ticket_view_table").load("<?php echo base_url(); ?>admin/tickets/tickets_ajax_view?tname="+getUrlParameter('tname'));
	});

/* $(document).on("click", ".tdate", function() {
       if($(this).hasClass("active"))
		   {
		    $(this).removeClass("active");
			 var  tdate=2;
		   }
		   else
		   {
			 $(this).addClass("active");
			  var  tdate=1;
		   }
		   
		  
		history.pushState(null, null, "<?php echo base_url(); ?>admin/tickets/tickets_view?tdate="+tdate);
					 
		$("#ticket_view_table").load("<?php echo base_url(); ?>admin/tickets/tickets_ajax_view?tdate="+getUrlParameter('tdate'));
	});*/
 $(document).on("click", ".tprice", function() {
 	if($(this).hasClass("active"))
 	{
 		$(this).removeClass("active");
 		var  tprice=2;
 	}
 	else
 	{
 		$(this).addClass("active");
 		var  tprice=1;
 	}


 	history.pushState(null, null, "<?php echo base_url(); ?>admin/tickets/tickets_view?tprice="+tprice);

 	$("#ticket_view_table").load("<?php echo base_url(); ?>admin/tickets/tickets_ajax_view?tprice="+getUrlParameter('tprice'));
 });
 $(document).on("click", ".tquantity", function() {
 	if($(this).hasClass("active"))
 	{
 		$(this).removeClass("active");
 		var  tquantity=2;
 	}
 	else
 	{
 		$(this).addClass("active");
 		var  tquantity=1;
 	}


 	history.pushState(null, null, "<?php echo base_url(); ?>admin/tickets/tickets_view?tquantity="+tquantity);

 	$("#ticket_view_table").load("<?php echo base_url(); ?>admin/tickets/tickets_ajax_view?tquantity="+getUrlParameter('tquantity'));
 });
</script>
<script>

	$(document).on("click", ".foldername", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  foldername=2;
		}
		else
		{
			$(this).addClass("active");
			var  foldername=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/resources/folder_view/?foldername="+foldername);

		$("#foldertable").load("<?php echo base_url(); ?>admin/resources/folder_view_ajax/?foldername="+getUrlParameter('foldername'));
	});

</script>

<script>

	$(document).on("click", ".pagetitle", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  pagetitle=2;
		}
		else
		{
			$(this).addClass("active");
			var  pagetitle=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/paging/paging_view/?pagetitle="+pagetitle);

		$("#pagetable").load("<?php echo base_url(); ?>admin/paging/paging_view_ajax/?pagetitle="+getUrlParameter('pagetitle'));
	});

</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/UI-Form-master/form.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/UI-Form-master/checkbox.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/alertify.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-selece-min.js"></script>
<!-- my custom files... -->



<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/UI-Form-master/checkbox.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/alertify.core.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/alertify.default.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-select-min.css">



<script>

	$(document).on("click", ".aboutname", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  aboutname=2;
		}
		else
		{
			$(this).addClass("active");
			var  aboutname=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/aboutus/aboutus_view/?aboutname="+aboutname);

		$("#aboutustable").load("<?php echo base_url(); ?>admin/aboutus/aboutus_view_ajax/?aboutname="+getUrlParameter('aboutname'));
	});
	(function($){
		$(document).ready(function(){
			$('.selectpicker').selectpicker();
			$('.decent_checkbox').checkbox();
			$('.checkbox').checkbox();

			var checkup = $('.ui.change_show_at_home_up').checkbox();
			
			//var check2 = $('.ui.change_featured').checkbox();
			var check3up = $('.ui.change_publish_up').checkbox();
			checkup.change(function(){
			
			
				var btn = $('input',this);
				//var form = $(this).closest('form');
				//form.serialize();
				if(btn.is(':checked')){
					var val = 1;
				}else
				var val = 0;
				//console.log('unchecked');

				//console.log('change_show_at_home');
				var vid = btn.attr('data-vid');
				
			});
			
			check3up.change(function(){
				var btn = $('input',this);
				var form = $(this).closest('form');
				if(btn.is(':checked')){
					var val = 1;
				}else{
					var val = 0;
				}
				var featured = form.find('.featured').is(':checked') ? 1 : 0;
				var show_at_home = form.find('.show_at_home').is(':checked') ? 1 : 0;
				var published = form.find('.change_published').is(':checked')? 1 : 0;
				//console.log(form.serialize()+featured+show_at_home+published);
				// console.log();
				// console.log();
				//console.log('change_publish');
				var vid = btn.attr('data-vid');
				
			});



			var check = $('.ui.change_show_at_home').checkbox();
			//var check2 = $('.ui.change_featured').checkbox();
			var check3 = $('.ui.change_publish').checkbox();
			check.change(function(){
				var btn = $('input',this);
				//var form = $(this).closest('form');
				//form.serialize();
				if(btn.is(':checked')){
					var val = 1;
				}else
				var val = 0;
				console.log('unchecked');

				console.log(site_url+'change_show_at_home');
				var vid = btn.attr('data-vid');
				$.ajax({
					type: 'post',
					url: site_url+'change_show_at_home',
					data: 'vid='+vid+'&val='+val,
					success: function (formData) {
						var resp = JSON.parse(formData);
						if(resp.status==true){
							alertify.success(resp.msg);
						}else{
							alertify.error(resp.msg);
						}  
					},
					error:function(){

					}

				});
			});
			
			
			/**
			*	change featured
			**/
			var check5 = $('.ui.change_featured').checkbox();
			check5.change(function(){
				var btn = $('input',this);
				if(btn.is(':checked')){
					var val = 1;
				}else
				var val = 0;

				var vid = btn.attr('data-vid');
				$.ajax({
					type: 'post',
					url: site_url+'change_featured',
					data: 'vid='+vid+'&val='+val,
					success: function (formData) {
						var resp = JSON.parse(formData);
						if(resp.status==true){
							alertify.success(resp.msg);
						}else{
							alertify.error(resp.msg);
						}  
					},
					error:function(){

					}

				});
			});
			
			
			
			
			check3.change(function(){
				var btn = $('input',this);
				var form = $(this).closest('form');
				if(btn.is(':checked')){
					var val = 1;
				}else{
					var val = 0;
				}
				var featured = form.find('.featured').is(':checked') ? 1 : 0;
				var show_at_home = form.find('.show_at_home').is(':checked') ? 1 : 0;
				var published = form.find('.change_published').is(':checked')? 1 : 0;
				//console.log(form.serialize()+featured+show_at_home+published);
				// console.log();
				// console.log();
				//console.log('change_publish');
				var vid = btn.attr('data-vid');
				$.ajax({
					type: 'POST',
					url: site_url+'change_publish',
					data: form.serialize()+'&if_published='+published+'&if_feature='+featured+'&show_at_home='+show_at_home,
					success: function (formData) {
						var resp = JSON.parse(formData);
						if(resp.status==true){
							alertify.success(resp.msg);
						}else{
							alertify.error(resp.msg);
						}  
					},
					error:function(){

					}

				});
			});
		});
})(jQuery);  
</script>
<script>

	$(document).on("click", ".servicetitle", function() {
		if($(this).hasClass("active"))
		{
			$(this).removeClass("active");
			var  servicetitle=2;
		}
		else
		{
			$(this).addClass("active");
			var  servicetitle=1;
		}


		history.pushState(null, null, "<?php echo base_url(); ?>admin/service/service_view/?servicetitle="+servicetitle);

		$("#servicetable").load("<?php echo base_url(); ?>admin/service/service_view_ajax/?servicetitle="+getUrlParameter('servicetitle'));
	});

</script>
<!--<script type="text/javascript" src="<?php echo base_url();?>assets/admin/datepair.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/admin/jquery.datepair.js"></script> -->
   <script src="<?php echo site_url() ?>assets/js/moment-with-locales.js"></script> 
 <script src="<?php echo site_url() ?>assets/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/admin_videos.js?v=12345"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/admin_classes.js"></script>
<!-- my custom files... -->

<script src="//cdn.ckeditor.com/4.4.7/full/ckeditor.js"></script> 



  <?php /*?><script type="text/javascript" src="<?php  echo site_url(); ?>assets/ckeditor/ckeditor.js"></script><?php */?>
<?php /*?><script type="text/javascript" src="../../../ckfinder/ckfinder.js"></script><?php */?>

<script type="text/javascript" src="https://cksource.com/apps/ckfinder/3.4.0/ckfinder.js?ogufxe"></script>
<script>
		
       

</script>

<script>
$(window).load(function(e){
	

      //  CKEDITOR.replace( 'editor1' );

       var editor1 = CKEDITOR.replace( 'editor1', {
        filebrowserBrowseUrl : '<?php echo site_url() ?>assets/kfinder/ckfinder.html',
        filebrowserImageBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl 	  : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=FileUpload&type=Files',
        filebrowserFlashUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
       
    });
  //  CKFinder.setupCKEditor( editor, '../' );

  	var editor2 = CKEDITOR.replace( 'editor2', {
        filebrowserBrowseUrl : '<?php echo site_url() ?>assets/kfinder/ckfinder.html',
        filebrowserImageBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl 	  : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=FileUpload&type=Files',
        filebrowserFlashUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
       
    });


	var editor3 = CKEDITOR.replace( 'editor3', {
        filebrowserBrowseUrl : '<?php echo site_url() ?>assets/kfinder/ckfinder.html',
        filebrowserImageBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl 	  : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=FileUpload&type=Files',
        filebrowserFlashUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
       
    });
	
	
	var editor3 = CKEDITOR.replace( 'description', {
        filebrowserBrowseUrl : '<?php echo site_url() ?>assets/kfinder/ckfinder.html',
        filebrowserImageBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl 	  : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=FileUpload&type=Files',
        filebrowserFlashUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
       
    });
	
	var editor3 = CKEDITOR.replace( 'txt', {
        filebrowserBrowseUrl : '<?php echo site_url() ?>assets/kfinder/ckfinder.html',
        filebrowserImageBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl : '<?php echo site_url() ?>assets/ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl 	  : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=FileUpload&type=Files',
        filebrowserFlashUploadUrl : '<?php echo site_url() ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
       
    });
	
    // CKEDITOR.replace('txt' );       
	

//$( '.cke_dialog_tabs> a:eq(2)' ).hide();
		//CKEDITOR.replace( 'editor2' );
		//CKEDITOR.replace( 'editor3' );

});

</script>
</body>

</html>