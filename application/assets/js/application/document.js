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
});