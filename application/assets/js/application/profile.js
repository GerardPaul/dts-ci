$(document).ready(function () {
    function clearUserForm() {
        $('#profileForm').data('bootstrapValidator').resetForm('resetFormData');
    }

    var userId = $('#userId').val();

    $('.cancelUserForm').on('click', function () {
        clearUserForm();
    });

    $('#profileForm').bootstrapValidator({
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
                    },
                    remote: {
                        message: 'This e-mail has already registered!',
                        url: base_url + 'profile/checkEmail',
                        data: {
                            type: userId
                        }
                    }
                }
            },
            username: {
                validators: {
                    notEmpty: {
                        message: 'The Username is required!'
                    },
                    remote: {
                        message: 'This username has already in use!',
                        url: base_url + 'profile/checkUsername',
                        data: {
                            type: userId
                        }
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
            }
        }
    });
});