<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrapValidator.min.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("assets/css/jquery.dataTables.min.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("assets/css/custom.css"); ?>">
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
				<a class="navbar-brand" href="#">DTS</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="<?php echo base_url("user"); ?>">User</a></li>
					<li><a href="<?php echo base_url("division"); ?>">Division</a></li>
					<li><a href="<?php echo base_url("document"); ?>">Documents</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo base_url("login"); ?>">Login</a></li>
					<li><a href="logout">Logout</a></li>
				</ul>
			</div>
		</div>
	</div>