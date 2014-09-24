$(document).ready(function() {
	$('#usersTable').dataTable();
	
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
});