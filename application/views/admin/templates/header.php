<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Document Tracking System | <?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/bootstrapValidator.min.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/jquery.dataTables.min.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/bootstrap.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/datepicker.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/custom.css"); ?>">
</head>
<body>
	<div class="navbar navbar-default" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" data-target=".navbar-collapse" data-toggle="collapse" type="button">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo base_url("home"); ?>">DTS</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<?php if($userType == 'ADMIN') {?>
						<li><a href="<?php echo base_url("admin/user"); ?>">User</a></li>
						<li><a href="<?php echo base_url("admin/division"); ?>">Division</a></li>
					<?php }?>
					<li><a href="<?php echo base_url("admin/document"); ?>">Documents</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo base_url("admin/home/logout"); ?>">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>