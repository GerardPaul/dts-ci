	<div class="navbar navbar-default navbar-fixed-bottom" id="footer">
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
	
	<script>
		$(document).ready(function() {
			$('#dataTable').dataTable({
				"order": [[ 0, "desc" ]]
			});
			$('.dataTables_wrapper .dataTables_filter input').addClass('form-control, input-sm').attr('display','inline');
			$('.dataTables_wrapper .dataTables_length select').addClass('form-control, input-sm').attr('display','inline');
			
			$('#dueDate').datepicker();
			$('#attachment').bootstrapFileInput();
			
			function clearUserForm(){
				$('#addUserForm').data('bootstrapValidator').resetForm('resetFormData');
			}
			
			$('.cancelUserForm').on('click',function(){
				clearUserForm();
			});
			
			$('#addUserForm').bootstrapValidator({
				container: 'tooltip',
				message: 'This value is not valid',
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				fields: {
					firstname: {
						validators: {
							notEmpty: {
								message: 'The First Name is required!'
							}
						}
					},
					lastname: {
						validators: {
							notEmpty: {
								message: 'The Last Name is required!'
							}
						}
					},
					email: {
						validators: {
							notEmpty: {
								message: 'The E-mail is required!'
							},
		                    emailAddress: {
								message: 'The value is not a valid E-mail address!'
							}
						}
					},
					username: {
						validators: {
							notEmpty: {
								message: 'The Username is required!'
							}
						}
					},
					password: {
						validators: {
							identical: {
								field: 'cpassword',
								message: 'The password doesn\'t match!'
							},
							notEmpty: {
								message: 'The password is required!'
							},
							stringLength: {
								max: 50,
								message: 'The password must be less than 50 characters!'
							},
							stringLength: {
								min: 6,
								message: 'The password must be greater than 6 characters!'
							}
						}
					},
					cpassword: {
						validators: {
							identical: {
								field: 'password',
								message: 'The password doesn\'t match!'
							},
							notEmpty: {
								message: 'The password is required!'
							},
							stringLength: {
								max: 50,
								message: 'The password must be less than 50 characters!'
							},
							stringLength: {
								min: 6,
								message: 'The password must be greater than 6 characters!'
							}
						}
					},
					userType: {
						validators: {
							notEmpty: {
								message: 'The User Type is required!'
							}
						}
					},
					division: {
						validators: {
							notEmpty: {
								message: 'The Division is required!'
							}
						}
					}
				}
			});
			
			function clearDivisionForm(){
				$('#addDivisionForm').data('bootstrapValidator').resetForm('resetFormData');
			}
			
			$('.cancelDivisionForm').on('click',function(){
				clearDivisionForm();
			});
			
			$('#addDivisionForm').bootstrapValidator({
				container: 'tooltip',
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
			
			function clearDocumentForm(){
				$('#addDocumentForm').data('bootstrapValidator').resetForm('resetFormData');
			}
			
			$('.cancelDocumentForm').on('click',function(){
				clearDocumentForm();
			});
			
			$('#addDocumentForm').bootstrapValidator({
				container: 'tooltip',
				message: 'This value is not valid',
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				fields: {
					referenceNumber: {
						validators: {
							notEmpty: {
								message: 'The Reference number is required!'
							}
						}
					},
					subject: {
						validators: {
							notEmpty: {
								message: 'The Subject is required!'
							}
						}
					},
					from: {
						validators: {
							notEmpty: {
								message: 'This field is required!'
							}
						}
					},
					status: {
						validators: {
							notEmpty: {
								message: 'The Status is required!'
							}
						}
					}
				}
			});
		});
	</script>
</body>
</html>