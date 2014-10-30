//restrict datepicker's starting date for regular user
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    startDate: '0d',
    autoclose: true,
    todayHighlight: true,
    daysOfWeekDisabled: [0,6] //exclude weekends
});

//no restriction of date for admin
$('.admindatepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    daysOfWeekDisabled: [0,6] //exclude weekends
});

//defualt dates is today
var date = new Date();
if(date.getDay()==6) {
    date = new Date(date.getTime()+ 2*86400000);
}else{
    if(date.getDay()==0) {
        date = new Date(date.getTime()+86400000);
    }
}
$(".datepicker, .admindatepicker").datepicker("setDate", date);
$(".datepicker, .admindatepicker").datepicker('update');

$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });
});

$('.adminSigninForm').bootstrapValidator({
    message: 'This value is not valid',
    fields: {
        username: {
            message: 'The username is not valid',
            validators: {
                notEmpty: {
                    message: 'The username is required and cannot be empty'
                },
                regexp: {
                    regexp: /^[a-zA-Z0-9_]+$/,
                    message: 'The username can only consist of alphabetical and number'
                }
            }
        },
        password: {
            message: 'The password is not valid',
            validators: {
                notEmpty: {
                    message: 'The password is required and cannot be empty'
                }
                //stringLength: {
                //    min: 6,
                //    max: 30,
                //    message: 'The password must be more than 6 and less than 30 characters long'
                //}
            }
        }
    }
});

$('.signinForm').bootstrapValidator({
    message: 'This value is not valid',
    fields: {
        nric: {
            message: 'The nric is not valid',
            validators: {
                notEmpty: {
                    message: 'The nric is required and cannot be empty'
                },
                stringLength: {
                    min: 9,
                    max: 9,
                    message: 'The nric must be 9 characters long'
                },
                regexp: {
                    regexp: /^[a-zA-Z0-9_]+$/,
                    message: 'The nric can only consist of alphabetical and number'
                }
            }
        },
        password: {
            message: 'The password is not valid',
            validators: {
                notEmpty: {
                    message: 'The password is required and cannot be empty'
                }
                //stringLength: {
                //    min: 6,
                //    max: 30,
                //    message: 'The password must be more than 6 and less than 30 characters long'
                //}
            }
        }
    }
});

$('.ChangePassForm').bootstrapValidator({
    message: 'This value is not valid',
    fields: {
        newpassword: {
            message: 'The password is not valid',
            validators: {
                notEmpty: {
                    message: 'The password is required and cannot be empty'
                }
            }
        },
        confirmnewpassword: {
            message: 'The password is not valid',
            validators: {
                notEmpty: {
                    message: 'The password is required and cannot be empty'
                },
                identical: {
                    field: 'newpassword',
                    message: 'The password and its confirm are not the same'
                }
            }
        }
    }
});

$('.MCForm').bootstrapValidator({
    message: 'This value is not valid',
    fields: {
        nric: {
            message: 'The nric is not valid',
            validators: {
                notEmpty: {
                    message: 'The nric is required and cannot be empty'
                },
                stringLength: {
                    min: 9,
                    max: 9,
                    message: 'The nric must be 9 characters long'
                },
                regexp: {
                    regexp: /^[a-zA-Z0-9_]+$/,
                    message: 'The nric can only consist of alphabetical and number'
                }
            }
        },
        fromdate: {
            validators: {
                notEmpty: {
                    message: 'The starting date cannot be empty'
                }
            }
        },
        todate:{
            validators:{
                notEmpty: {
                    message: 'The ending date cannot be empty'
                },
                callback: {
                    message: 'The ending date must not be before the starting date',
                    callback: function(value, validator) {
                        var todate = new moment(value, 'DD-MM-YYYY', true);
                        var fromdate = new moment($('[name="fromdate"]').val(), 'DD-MM-YYYY', true);
                        if (!todate.isValid()) {
                            return false;
                        }
                        // Check if the date in our range
                        //alert(todate.diff(fromdate));
                        return (todate.isAfter(fromdate) || todate.isSame(fromdate));
                    }
                }
            }
        }
    }
});

$('.datepicker, .admindatepicker').on('changeDate', function (ev) {
    $('.MCForm').bootstrapValidator('revalidateField', 'todate');
    $('.MCForm').bootstrapValidator('revalidateField', 'fromdate');
});

//$('.editMCForm').bootstrapValidator({
//    message: 'This value is not valid',
//    fields: {
//        mc_score: {
//            message: 'The file is not valid',
//            validators: {
//                notEmpty: {
//                    message: 'You must choose a file'
//                }
//                //integer: {
//                //    message: 'The score is not an integer'
//                //}
//            }
//        }
//    }
//});


$('.TimeTableForm').bootstrapValidator({
    message: 'This value is not valid',
    fields: {
        timetablefile: {
            message: 'The file is not valid',
            validators: {
                notEmpty: {
                    message: 'Please choose a file.'
                },
                file: {
                    extension: 'xls,xlsx,csv',
                    message: 'The selected file is of wrong format!'
                }
            }
        }
    }
});

$('.ParticularForm').bootstrapValidator({
    message: 'This file is not valid',
    fields: {
        particularfile: {
            message: 'The file is not valid',
            validators: {
                notEmpty: {
                    message: 'Please choose a file.'
                },
                file: {
                    extension: 'xls,xlsx,csv',
                    message: 'The selected file is of wrong format!'
                }
            }
        }
    }
});
