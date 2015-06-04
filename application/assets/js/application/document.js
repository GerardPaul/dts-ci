$(document).ready(function () {
    $('#dueDate').datetimepicker({pickTime: false});
    $('#dateReceived').datetimepicker({pickTime: false});
    $('#fromDate').datetimepicker({pickTime: false});
    $('#toDate').datetimepicker({pickTime: false});
    $('#deadline').datetimepicker({pickTime: false});
    $('#attachment').bootstrapFileInput();

    function clearDocumentForm() {
        $('#addDocumentForm').data('bootstrapValidator').resetForm('resetFormData');
    }

    $('.cancelDocumentForm').on('click', function () {
        clearDocumentForm();
    });

    // Validation for adding document
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
                        extension: 'jpeg,jpg,png,gif,pdf,docx,doc,xls,xlsx,zip,txt,ppt,pptx,csv',
                        type: 'image/jpeg,image/png,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/x-msexcel,application/x-excel,application/vnd.ms-excel,application/excel,application/x-compressed,application/zip,text/plain',
                        maxSize: 0, // 2 MB
                        message: 'The selected file is not valid!'
                    }
                }
            },
            /*dueDate: {
                validators: {
                    notEmpty: {
                        message: 'Please set due date for document!'
                    },
                    date: {
                        format: 'MM/DD/YYYY'
                    }
                }
            },*/
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

    $('#dueDate').on('dp.change dp.show', function (e) {
        // Validate the date when user change it
        $('#addDocumentForm').bootstrapValidator('revalidateField', 'dueDate');
    });

    $('#dateReceived').on('dp.change dp.show', function (e) {
        // Validate the date when user change it
        $('#addDocumentForm').bootstrapValidator('revalidateField', 'dateReceived');
    });

    // Validation for editing document
    $('#editDocumentForm').bootstrapValidator({
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
                        extension: 'jpeg,jpg,png,gif,pdf,docx,doc,xls,xlsx,zip,txt,ppt,pptx,csv',
                        type: 'image/jpeg,image/png,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/x-msexcel,application/x-excel,application/vnd.ms-excel,application/excel,application/x-compressed,application/zip,text/plain',
                        maxSize: 0, // 2 MB
                        message: 'The selected file is not valid!'
                    }
                }
            },
            /*dueDate: {
                validators: {
                    notEmpty: {
                        message: 'Please set due date for document!'
                    },
                    date: {
                        format: 'MM/DD/YYYY'
                    }
                }
            },*/
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

    $('#dueDate').on('dp.change dp.show', function (e) {
        // Validate the date when user change it
        $('#editDocumentForm').bootstrapValidator('revalidateField', 'dueDate');
    });

    $('#dateReceived').on('dp.change dp.show', function (e) {
        // Validate the date when user change it
        $('#editDocumentForm').bootstrapValidator('revalidateField', 'dateReceived');
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
                    date: {
                        format: 'MM/DD/YYYY'
                    }
                }
            },
            action: {
                validators: {
                    notEmpty: {
                        message: 'Select action!'
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

    $('#deadline').on('dp.change dp.show', function (e) {
        // Validate the date when user change it
        $('#assignForm').bootstrapValidator('revalidateField', 'deadline');
    });

    $('#deadline_box').change(function(){
        if(this.checked){
            $('#deadline').removeAttr('disabled');
        }else{
            $('#deadline').attr('disabled','disabled');
        }
    }).change();

    $('.toggleChat').click(function () {
        $('.toggleChat i').toggle();
        $('#chatContents').toggle();

        var height = $('.chatBox').height();

        if (height === 300) {
            $('.chatBox').height(35);
        } else if (height === 35) {
            $('.chatBox').height(300);
        } else if (height === 400) {
            $('.chatBox').height(34);
        } else if (height === 34) {
            $('.chatBox').height(400);
        }
    });

    $('#download').click(function () {
        var path = $('#path').val();
        $.post(base_url + "admin/document/download", {path: path}, function (data) {
            if (data.status == 'ok') {

            } else {

            }
        }, "json");
    });

    var currentStatus = $('#currentStatus').val();
    $('#editStatus').val(currentStatus);

    $('#changeView').on('change', function () {
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
        } else if (value === '5') {
            change = 'Archived';
        }

        loadDocuments(change);
    });

    // set status for dropdown menu in edit document
    var status = $('#currentStatus').val();
    $('#documentStatus').val(status);

    //load function depending on need on page
    var load = $('#load').val();
    if (load === 'documents') {
        loadDocuments('All');
    } else if (load === 'rddetails') {
        //loadRDButtons();
    }

    $('#changeAttachmentButton').click(function () {
        $('.attachments').toggle();
        $('#changeAttachmentButton span').toggle();
    });
    
    $('#specific').click(function(){
        if(this.checked){
            $('#divisions input').attr('disabled','disabled');
            $('#select_list').css('display','block');
        }else{
            $('#divisions input').removeAttr('disabled','disabled');
            $('#select_list').css('display','none');
        }
    });
    
    $('#divisions input').click(function(){
        if($('#divisions :checkbox:checked').length > 0){
            $('#assign').removeClass('disabled');
        }else{
            $('#assign').addClass('disabled');
        }
    });
   
});

function loadRDButtons() {
    var users = $('#getUserCount').val();
}

function moveItem() {
    var selected = $('#list option:selected');
    selected.appendTo('#selectedList');

    var num = $('#list option').length;
    if(num > 0){
        $('#assign').removeClass('disabled');
    }
}

function removeItem() {
    var selected = $('#selectedList option:selected');
    var value = $('#selectedList option:selected').val();
    var position = value.substr(value.indexOf('_')+1) - 1;
    $('#list option:eq('+position+')').after(selected);

    var num = $('#selectedList option').length;
    if (num === 0) {
        $('#assign').addClass('disabled');
    }
}

function loadDocuments(change) {
    $('#documentsTable').dataTable().fnClearTable();
    $('#documentsTable').dataTable().fnDraw();
    $('#documentsTable').dataTable().fnDestroy();

    var userId = $('#userId').val();
    $.post(base_url + "admin/document/getAllDocuments", {change: change, userType: userType, method: method, userId: userId}, function (response, status) {
        var result = JSON.parse(response);

        $.each(result, function (i, field) {
            var status = field['status'];
            var stat = '';
            if (status === 'Cancelled') {
                stat = '<span class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' + status + '" data-toggle="tooltip"></i>&nbsp;</span>';
            } else if (status === 'On-Going') {
                stat = '<span class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' + status + '" data-toggle="tooltip"></i>&nbsp;</span>';
            } else if (status === 'Compiled') {
                stat = '<span class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="' + status + '" data-toggle="tooltip"></i>&nbsp;</span>';
            }
            
            var received = field['received'];

            var dateReceived = format_mysqldate(field['dateReceived']);
            var deadline = field['deadline'];
            var dueDate = '';
            if (deadline === '0000-00-00' || deadline === '1970-01-01') {
                dueDate = compare_mysqldate(field['due15Days'], field['dueDate']);
            } else {
                dueDate = format_mysqldate(deadline);
            }

            var links = '';
            if (userType === 'ADMIN' || userType === 'SEC') {
                if (userType === 'SEC') {
                    if(method === 'sec'){
                        links = '<a href="' + base_url + 'admin/document/details/' + field['id'] + '" class="btn btn-primary btn-xs view_button" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>';
                    }else{
                        links = '<a href="' + base_url + 'admin/document/view/' + field['id'] + '" class="btn btn-primary btn-xs view_button" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>';
                        links += '<a href="' + base_url + 'admin/document/edit/' + field['id'] + '" class="btn btn-success btn-xs edit_button" title="Edit Document" data-toggle="tooltip"><i class="glyphicon glyphicon-pencil"></i></a>';
                    }
                }else{
                    links = '<a href="' + base_url + 'admin/document/view/' + field['id'] + '" class="btn btn-primary btn-xs view_button" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>';
                }
            } else if (userType === 'RD' || userType === 'ARD') {
                links = '<a href="' + base_url + 'admin/document/details/' + field['id'] + '" class="btn btn-primary btn-xs view_button" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>';
            } else {
                links = '<a href="' + base_url + 'document/details/' + field['id'] + '" class="btn btn-primary btn-xs view_button" title="View Details" data-toggle="tooltip"><i class="glyphicon glyphicon-search"></i></a>';
            }

            if (received === '0000-00-00' || received === null) {
                $('#documentsTable tbody').append('<tr><td>' + field['id'] + '</td><td><strong>' + field['referenceNumber'] + '</strong></td><td><strong>' + field['from'] + '</strong></td><td><strong>' + stat + " " + field['subject'] + '</strong></td><td><strong>' + dateReceived + '</strong></td><td><strong>' + dueDate +
                        '</strong></td><td><div class="visible-md visible-lg visible-sm visible-xs btn-group">' + links + '</div></td></tr>');
            } else {
                $('#documentsTable tbody').append('<tr><td>' + field['id'] + '</td><td>' + field['referenceNumber'] + '</td><td>' + field['from'] + '</td><td>' + stat + " " + field['subject'] + '</td><td>' + dateReceived + '</td><td>' + dueDate +
                        '</td><td><div class="visible-md visible-lg visible-sm visible-xs btn-group">' + links + '</div></td></tr>');
            }
        });
        $('#documentsTable').dataTable({
            "order": [[0, "desc"]],
            "aoColumns": [
                {"bVisible": false, "iDataSort": 0},
                null,
                null,
                null,
                null,
                null,
                {"bSortable": false}
            ],
            "aoColumnDefs": [
                {"sWidth": "10%", "aTargets": [1]},
                {"sWidth": "15%", "aTargets": [2]},
                {"sWidth": "45%", "aTargets": [3]},
                {"sWidth": "10%", "aTargets": [4]},
                {"sWidth": "10%", "aTargets": [5]},
                {"sWidth": "10%", "aTargets": [6]}
            ],
            "bDestroy": true,
            "iDisplayLength": 50
        });
    });
}

function checkUsers() {
    if($('#specific').is(':checked')){
        var num = $('#selectedList option').length;
        if (num >= 1) {
            $('#selectedList option').prop('selected', true);
            return true;
        } else {
            $('#assign').addClass('disabled');
            return false;
        }
    }else{
        if($('#divisions :checkbox:checked').length > 0){
            return true;
        }else{
            return false;
        }
    }
}

function ardCheckUsers() {
    var num = $('#selectedList option').length;
    if (num >= 1) {
        $('#selectedList option').prop('selected', true);
        return true;
    } else {
        $('#assign').addClass('disabled');
        return false;
    }
}