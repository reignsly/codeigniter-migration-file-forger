<!DOCTYPE html>
<html>
<head>
	<title>Codeigniter Migration Forger</title>
	<!-- Latest compiled and minified CSS -->
	<link href="<?php echo base_url('assets/css/bootstrap.default.min.css'); ?>" rel="stylesheet" type="text/css" media="screen" />

	<!-- Optional theme -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

	<!-- Latest compiled and minified JavaScript -->
	<!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->

	<style>
		textarea {
		    width: 100%;
		    font-family: monospace; 
		}
	</style>
</head>
<body>
	<div class="container">
		<nav class="navbar navbar-inverse" role="navigation">
			 <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">Codeigniter Migration Forger</a>
		    </div>
		</nav>
		
		<?=form_open('',' class="form-horizontal" method="GET"');?>
		
		<?=$this->load->view('_db_form')?>

		<hr>

		<?if($result):?>
			<div class="row">
			  <div class="col-lg-12">
			  	<h4>Result</h4>
			    <textarea class="form-control" rows="35" ><?=$result?></textarea>
			  </div>
			</div>
			<br/>
		<?endif;?>

		<div class="panel panel-default">
		  <div class="panel-heading">Options</div>
		  <div class="panel-body">
		    <table class="table table-bordered table-hover">
		    	<thead>
		    		<tr>
		    			<td>Database Tables</td>
		    			<td>Settings</td>
		    		</tr>
		    	</thead>
		    	<tbody>
		    		<tr>
		    			<td>
		    				<?if($tables):?>
		    					<button onclick="check_all(event)" >Check All</button>
		    					<button onclick="uncheck_all(event)">Un-check All</button>
		    					<script>
		    					function check_all(e){
		    						 $(".cb_tables").prop("checked", true);
		    						 e.preventDefault();
		    					}
		    					function uncheck_all(e){
		    						 $(".cb_tables").prop("checked", false);
		    						 e.preventDefault();
		    					}
		    					</script>
								<?foreach ($tables as $key => $tb): ?>
									<br/>
									<input type="checkbox" class="cb_tables" name="tables[]" value="<?=$tb?>"><i class="fa fa-hand-o-right"></i>&nbsp; <?=$tb?>
								<?endforeach;?>
		    				<?else:?>
		    					No Tables Available
		    				<?endif;?>
		    			</td>
		    			<td>
		    				
		    				<div class="row">
		    					<div class="col-md-12">
									<h4>Show Result <small>on the page</small></h4>

									<p>
									<input type="checkbox" name="add_class_name" id="add_class_name" checked /> Add Class Name
									<input type="text" name="class_name" id="class_name"></p>

									<p><input type="checkbox" name="add_up" id="add_up" checked /> Add Function UP</p>
									<p><input type="checkbox" name="add_down" id="add_down" checked /> Add Function DOWN</p>

									<br/>
									<input type="submit" value="Submit" name="show_on_page" class="btn btn-primary" />
								</div>

		    				</div>
							<hr>
		    				<div class="row">
		    					
		    				</div>

		    			</td>
		    		</tr>
		    	</tbody>
		    </table>
		  </div>
		</div>
		<?=form_close();?>

	</div>
</body>
</html>