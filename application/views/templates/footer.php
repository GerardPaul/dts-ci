	<div class="footer">
		<div class="container">
			<p class="text-muted">Copyright &copy; 
				<script>
					document.write(new Date().getFullYear());
				</script>
			</p>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-2.1.1.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrapValidator.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.dataTables.min.js"); ?>"></script>
	
	<script>
		$(document).ready(function() {
			$('#userTable').dataTable();
			$('#divisionTable').dataTable();
			
			$('#addDivisionForm').bootstrapValidator({
				message: 'This value is not valid',
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				fields: {
					divisionName: {
						validators: {
							notEmpty: {
								message: 'The Division Name is required!'
							}
						}
					},
					description: {
						validators: {
							notEmpty: {
								message: 'The Description is required!'
							}
						}
					}
				}
			});
		});
	</script>
</body>
</html>