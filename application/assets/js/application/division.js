$(document).ready(function() {
	$('#divisionsTable').dataTable();
	
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
});