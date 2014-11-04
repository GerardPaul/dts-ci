	<div id="footer">
		<div class="container">
			<p class="navbar-text">Copyright &copy; 
				<script>
					document.write(new Date().getFullYear());
				</script>
                                <a href="http://gpplworx.com" target="_blank">Gerard Paul P. Labitad</a>
			</p>
		</div>
	</div>
        <script type="text/javascript">
            var base_url = "<?php echo base_url(); ?>";
        </script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrap-datetimepicker.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/jquery.dataTables.min.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrap.file-input.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("application/assets/js/bootstrapValidator.min.js"); ?>"></script>
	
        <?php $javascript = $this->router->fetch_class(); ?>
	<script type="text/javascript" src="<?php echo base_url()."application/assets/js/application/".$javascript.".js"; ?>"></script>
	
	<script>
            $(document).ready(function() {
                $('.dataTables_wrapper .dataTables_filter input').addClass('form-control, input-sm').attr('display','inline');
                $('.dataTables_wrapper .dataTables_length select').addClass('form-control, input-sm').attr('display','inline');

                $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
            });
	</script>
</body>
</html>