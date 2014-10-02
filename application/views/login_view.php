<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?> | Document Tracking System</title>
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/bootstrapValidator.min.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/jquery.dataTables.min.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/bootstrap.css"); ?>">
	<link rel="stylesheet" href="<?php echo base_url("application/assets/css/custom.css"); ?>">
	
	<style>
		body {
			padding-top: 50px;
			padding-bottom: 40px;
			background-color: #eee;
		}
	</style>
</head>
<body>
	<div class="container">
		<form class="form-signin" role="form" action="<?php echo base_url("login/auth"); ?>" method="post">
			<div class="login-header">
				<i class="glyphicon glyphicon-user"></i>
				Document Tracking System
			</div>
			<?php if(isset($message)){ 
				echo <<<HTML
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button class="close" type="button" data-dismiss="alert">
							<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
						</button>
						{$message}
					</div>
HTML;
			} ?>
			<input class="form-control" type="text" autofocus="" required="" placeholder="Username" name="username">
			<input class="form-control" type="password" autofocus="" required="" placeholder="Password" name="password">
			<button class="btn btn-primary btn-block btn-lg" type="submit">Sign In</button>
		</form>
	</div>
	
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/jquery-2.1.1.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrap.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrapValidator.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/jquery.dataTables.min.js"); ?>"></script>
	
	<script>
		$(document).ready(function() {
			
		});
	</script>
</body>
</html>