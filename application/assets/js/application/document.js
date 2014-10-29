$(document).ready(function() {
    $('#dueDate').datepicker();
    $('#dateReceived').datepicker();
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

    $('#empTSD').change(function() {
        var value = $(this).val();
        $('#empId').val(value);
    });

    $('#empTSSD').change(function() {
        var value = $(this).val();
        $('#empId').val(value);
    });

    $('#empFASD').change(function() {
        var value = $(this).val();
        $('#empId').val(value);
    });

    $('#ard').change(function() {
        var value = $(this).val();
        if (value === '1') {
            $('#empTSSD').val($('#empTSSD option:first').val()); //set values back to ''
            $('#empFASD').val($('#empFASD option:first').val()); //set values back to ''

            $('#tsd_emp').removeClass('hide');
            $('#tssd_emp').addClass('hide');
            $('#fasd_emp').addClass('hide');

            $('#ardId').val($('#ardTSD').val());
            $('#empId').val($('#empTSD').val());
        }
        else if (value === '2') {
            $('#empTSD').val($('#empTSD option:first').val()); //set values back to ''
            $('#empFASD').val($('#empFASD option:first').val()); //set values back to ''

            $('#tssd_emp').removeClass('hide');
            $('#tsd_emp').addClass('hide');
            $('#fasd_emp').addClass('hide');

            $('#ardId').val($('#ardTSSD').val());
            $('#empId').val($('#empTSSD').val());
        }
        else {
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

    loadDocuments('All');

    function loadDocuments(change) {
        $('#documentsTable').dataTable().fnDestroy();

        $.post(base_url + "admin/document/getAllDocuments", {change: change}, function(response, status) {
            var result = JSON.parse(response);

            $.each(result, function(i, field) {
                var dateReceived = format_mysqldate(field['dateReceived']);
                var dueDate = format_mysqldate(field['dueDate']);

                $('#documentsTable tbody').append('<tr><td>' + field['from'] + '</td><td>' + field['status'] + '</td><td>' + field['subject'] + '</td><td>' + dateReceived + '</td><td>' + dueDate +
                        '</td><td><div class="visible-md visible-lg visible-sm visible-xs btn-group">' +
                        '<a href="' + base_url + 'admin/document/view/' + field['id'] + '" class="btn btn-primary btn-xs" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>' +
                        '<a href="' + base_url + 'admin/document/edit/' + field['id'] + '" class="btn btn-success btn-xs" title="Edit Document" data-toggle="tooltip"><i class="glyphicon glyphicon-pencil"></i></a>' +
                        '</div></td></tr>');
            });
            $('#documentsTable').dataTable({
                "aoColumns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {"bSortable": false}
                ],
                "order": [[3, "desc"]],
                "bDestroy": true
            });
        });
    }
});