$(document).ready(function() {
    $('#dueDate').datetimepicker({ pickTime: false });
    $('#dateReceived').datetimepicker({ pickTime: false });
	$('#deadline').datetimepicker({ pickTime: false });
    $('#attachment').bootstrapFileInput();

    function clearDocumentForm() {
        $('#addDocumentForm').data('bootstrapValidator').resetForm('resetFormData');
    }

    $('.cancelDocumentForm').on('click', function() {
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
            description: {
                validators: {
                    notEmpty: {
                        message: 'The Description is required!'
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
            attachment: {
                validators: {
                    file: {
                        extension: 'jpeg,jpg,png,gif,pdf,docx,doc',
                        type: 'image/jpeg,image/png,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        maxSize: 2048 * 1024, // 2 MB
                        message: 'The selected file is not valid!'
                    }
                }
            },
			dueDate: {
				validators: {
					notEmpty: {
                        message: 'Please set due date for document!'
                    },
                    date: {
                        format: 'MM/DD/YYYY'
                    }
				}
            },
			dateReceived: {
				validators: {
					notEmpty: {
                        message: 'Please set receive date for document!'
                    },
                    date: {
                        format: 'MM/DD/YYYY'
                    }
				}
            }
        }
    });

    $('#dueDate').on('dp.change dp.show', function(e) {
        // Validate the date when user change it
        $('#addDocumentForm').bootstrapValidator('revalidateField', 'dueDate');
    });
	
	$('#dateReceived').on('dp.change dp.show', function(e) {
        // Validate the date when user change it
        $('#addDocumentForm').bootstrapValidator('revalidateField', 'dateReceived');
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
    
    $('#assignForm').bootstrapValidator({
        container: 'tooltip',
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            deadline: {
				validators: {
					notEmpty: {
                        message: 'Please set deadline for document!'
                    },
                    date: {
                        format: 'MM/DD/YYYY'
                    }
				}
            },
            status: {
                validators: {
                    notEmpty: {
                        message: 'Select status!'
                    }
                }
            },
            note: {
                validators: {
                    notEmpty: {
                        message: 'Please fill in note!'
                    }
                }
            }
        }
    });
	
	$('#deadline').on('dp.change dp.show', function(e) {
        // Validate the date when user change it
        $('#assignForm').bootstrapValidator('revalidateField', 'deadline');
    });

    $('#toggleChat').click(function() {
        $('#toggleChat i').toggle();
        $('#chatContents').toggle();

        if ($('.chatBox').height() === 300) {
            $('.chatBox').height(35);
        }
        else {
            $('.chatBox').height(300);
        }
    });

    $('#download').click(function() {
        var path = $('#path').val();
        $.post(base_url + "admin/document/download", {path: path}, function(data) {
            if (data.status == 'ok') {

            } else {

            }
        }, "json");
    });

    var currentStatus = $('#currentStatus').val();
    $('#editStatus').val(currentStatus);

    $('#changeView').on('change', function() {
        var value = $(this).val();
        var change = '';
        if (value === '1') {
            change = 'All';
        } else if (value === '2') {
            change = 'Compiled';
        } else if (value === '3') {
            change = 'On-Going';
        } else if (value === '4') {
            change = 'Cancelled';
        }

        loadDocuments(change);
    });
    
    // set status for dropdown menu in edit document
    var status = $('#currentStatus').val();
    $('#documentStatus').val(status);
    
	//load function depending on need on page
    var load = $('#load').val();
    if(load === 'documents'){
        loadDocuments('All');
    }else if(load === 'rddetails'){
		loadRDButtons();
	}
});

function loadRDButtons(){
    var users = $('#getUserCount').val();
}

function moveItem(){
    var selected = $('#list option:selected');
    selected.appendTo('#selectedList');
	
	$('#assign').removeClass('disabled');
}

function removeItem(){
    var selected = $('#selectedList option:selected');
    selected.appendTo('#list');
	
	var num = $('#selectedList option').length;
	if(num === 0){
		$('#assign').addClass('disabled');
	}
}

function loadDocuments(change) {
    $('#documentsTable').dataTable().fnClearTable();
    $('#documentsTable').dataTable().fnDraw();
    $('#documentsTable').dataTable().fnDestroy();

    var userId = $('#userId').val();
    $.post(base_url + "admin/document/getAllDocuments", {change: change, userType: userType, userId: userId}, function(response, status) {
        var result = JSON.parse(response);

        $.each(result, function(i, field) {
			var received = field['received'];
			
            var dateReceived = format_mysqldate(field['dateReceived']);
            var deadline = field['deadline'];
            var dueDate = '';
            if (deadline === '0000-00-00') {
                dueDate = compare_mysqldate(field['due15Days'], field['dueDate']);
            } else {
                dueDate = format_mysqldate(deadline);
            }

            var links = '';
            if (userType === 'ADMIN' || userType === 'SEC') {
                links = '<a href="' + base_url + 'admin/document/view/' + field['id'] + '" class="btn btn-primary btn-xs view_button" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>';
                if (userType === 'SEC') {
                    links += '<a href="' + base_url + 'admin/document/edit/' + field['id'] + '" class="btn btn-success btn-xs edit_button" title="Edit Document" data-toggle="tooltip"><i class="glyphicon glyphicon-pencil"></i></a>';
                }
            } else if (userType === 'RD' || userType === 'ARD') {
                links = '<a href="' + base_url + 'admin/document/details/' + field['id'] + '" class="btn btn-primary btn-xs view_button" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>';
            }

			if(received === '0000-00-00' || received === null){
				$('#documentsTable tbody').append('<tr><td><strong>' + field['from'] + '</strong></td><td><strong>' + field['subject'] + '</strong></td><td><strong>' + dateReceived + '</strong></td><td><strong>' + dueDate +
                    '</strong></td><td><div class="visible-md visible-lg visible-sm visible-xs btn-group">' + links + '</div></td></tr>');
			}else{
				$('#documentsTable tbody').append('<tr><td>' + field['from'] + '</td><td>' + field['subject'] + '</td><td>' + dateReceived + '</td><td>' + dueDate +
                    '</td><td><div class="visible-md visible-lg visible-sm visible-xs btn-group">' + links + '</div></td></tr>');
			}
        });
        $('#documentsTable').dataTable({
            "aoColumns": [
                null,
                null,
                null,
                null,
                {"bSortable": false}
            ],
            "aoColumnDefs": [
                {"sWidth": "20%", "aTargets": [0]},
                {"sWidth": "50%", "aTargets": [1]},
                {"sWidth": "10%", "aTargets": [2]},
                {"sWidth": "10%", "aTargets": [3]},
                {"sWidth": "10%", "aTargets": [4]}
            ],
            "order": [[2, "asc"]],
            "bDestroy": true,
			"iDisplayLength" : 50
        });
    });
}

function checkUsers(){
	var num = $('#selectedList option').length;
	if(num >= 1){
		return true;
	}else{
		$('#assign').addClass('disabled');
	}
}