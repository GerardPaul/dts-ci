	<div class="navbar navbar-inverse navbar-fixed-bottom" id="footer">
		<div class="container">
			<p class="navbar-text">Copyright &copy; 
				<script>
					document.write(new Date().getFullYear());
				</script>
			</p>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/jquery-2.1.1.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrap.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrapValidator.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/jquery.dataTables.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrap-datepicker.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrap.file-input.js"); ?>"></script>
	
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/application/document.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/application/user.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/application/division.js"); ?>"></script>
	
	<script>
		$(document).ready(function() {
			$('.dataTables_wrapper .dataTables_filter input').addClass('form-control, input-sm').attr('display','inline');
			$('.dataTables_wrapper .dataTables_length select').addClass('form-control, input-sm').attr('display','inline');
			
		});
	</script>
</body>
</html>