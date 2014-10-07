$(document).ready(function() {
	$('#documentsTable').dataTable({
		"order": [[ 2, "desc" ]]
	});
	
	$('#dueDate').datepicker();
	$('#dateReceived').datepicker();
	$('#attachment').bootstrapFileInput();
	
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
			},
			attachment: {
                validators: {
                    file: {
                        extension: 'jpeg,jpg,png,gif,pdf,txt',
                        type: 'image/jpeg,image/png,image/gif,application/pdf,text/plain',
                        maxSize: 2048 * 1024,   // 2 MB
                        message: 'The selected file is not valid!'
                    }
                }
            }
			/*
			,dueDate: {
				validators: {
					date: {
                        format: 'MM/DD/YYYY h:m A',
                        message: 'The value is not a valid date'
                    }
				}
			}
			*/
		}
	});
	
	$('#dueDate').on('dp.change dp.show', function(e) {
        // Validate the date when user change it
        $('#addDocumentForm').bootstrapValidator('revalidateField', 'dueDate');
    });
	
	$('#empTSD').change(function(){
		var value = $(this).val();
		$('#empId').val(value);
	});
	
	$('#empTSSD').change(function(){
		var value = $(this).val();
		$('#empId').val(value);
	});
	
	$('#empFASD').change(function(){
		var value = $(this).val();
		$('#empId').val(value);
	});
	
	$('#ard').change(function(){
		var value = $(this).val();
		if(value==='1'){
			$('#empTSSD').val($('#empTSSD option:first').val()); //set values back to ''
			$('#empFASD').val($('#empFASD option:first').val()); //set values back to ''
			
			$('#tsd_emp').removeClass('hide');
			$('#tssd_emp').addClass('hide');
			$('#fasd_emp').addClass('hide');
			
			$('#ardId').val($('#ardTSD').val());
			$('#empId').val($('#empTSD').val());
		}
		else if(value==='2'){
			$('#empTSD').val($('#empTSD option:first').val()); //set values back to ''
			$('#empFASD').val($('#empFASD option:first').val()); //set values back to ''
			
			$('#tssd_emp').removeClass('hide');
			$('#tsd_emp').addClass('hide');
			$('#fasd_emp').addClass('hide');
			
			$('#ardId').val($('#ardTSSD').val());
			$('#empId').val($('#empTSSD').val());
		}
		else{
			$('#empTSSD').val($('#empTSSD option:first').val()); //set values back to ''
			$('#empTSD').val($('#empTSD option:first').val()); //set values back to ''
			
			$('#fasd_emp').removeClass('hide');
			$('#tsd_emp').addClass('hide');
			$('#tssd_emp').addClass('hide');
			
			$('#ardId').val($('#ardFASD').val());
			$('#empId').val($('#empFASD').val());
		}
	});
	
	$('#markReceivedForm').bootstrapValidator({
		container: 'tooltip',
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			ard: {
				validators: {
					notEmpty: {
						message: 'Select Division to assign!'
					}
				}
			},
			action: {
				validators: {
					notEmpty: {
						message: 'Select appropriate action!'
					}
				}
			},
			note: {
				validators: {
					notEmpty: {
						message: 'Fill in notes before forwarding to assigned person!'
					}
				}
			}
		}
	});
	
	$('#ardMarkReceivedForm').bootstrapValidator({
		container: 'tooltip',
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			emp: {
				validators: {
					notEmpty: {
						message: 'Select user to assign!'
					}
				}
			}
		}
	});
	
	$('#changeStatusForm').bootstrapValidator({
		container: 'tooltip',
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			status: {
				validators: {
					notEmpty: {
						message: 'Select status!'
					}
				}
			}
		}
	});
	
	$('#toggleChat').click(function(){
		$('#toggleChat i').toggle();
		$('#chatContents').toggle();
		
		
		if($('.chatBox').height()===300){
			$('.chatBox').height(35);
		}
		else{
			$('.chatBox').height(300);
		}
	});
	
	$('#download').click(function(){
		var path = $('#path').val();
		$.post(base_url + "admin/document/download", {path: path}, function(data) {
            if (data.status == 'ok') {
                
            } else {

            }
        }, "json");
	});
});