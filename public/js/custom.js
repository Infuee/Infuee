const strongPassword = function() {
        return {
            validate: function(input) {
                const value = input.value;
                if (value === '') {
                    return {
                        valid: true,
                    };
                }
    
                // Check the password strength
                if (value.length < 8) {
                    return {
                        valid: false,
                        message: 'Passwords must contain at least eight characters including uppercase, lowercase letters, numbers and special characters.',
                    };
                }
 
                // The password doesn't contain any uppercase character
                if (value === value.toLowerCase()) {
                    return {
                        valid: false,
                        message: 'Passwords must contain at least eight characters including uppercase, lowercase letters, numbers and special characters.',
                    };
                }

                // The password doesn't contain any uppercase character
                if (value === value.toUpperCase()) {
                    return {
                        valid: false,
                        message: 'Passwords must contain at least eight characters including uppercase, lowercase letters, numbers and special characters.',
                    };
                }

                // The password doesn't contain any digit
                if (value.search(/[0-9]/) < 0) {
                    return {
                        valid: false,
                        message: 'Passwords must contain at least eight characters including uppercase, lowercase letters, numbers and special characters.',
                    };
                }
        
                var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
                if(!format.test(value)){
                    return {
                        valid: false,
                        message: 'Passwords must contain at least eight characters including uppercase, lowercase letters, numbers and special characters.',
                    };
                }

                return {
                    valid: true,
                };
            },
        };
    };



// Class Definition
var KTValidation = function() {
    
    var _handleProfileForm = function(e) {

    	var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('profile_update_form'),
			{
				fields: {
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: 'First name is required'
                            }
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: 'First name is required'
                            }
                        }
                    },
					email: {
                        validators: {
							notEmpty: {
								message: 'Email address is required'
							},
                            emailAddress: {
								message: 'The value is not a valid email address'
							}
						}
					},
                    phone: {
                        validators: {
                            notEmpty: {
                                message: 'The phone is required'
                            }
                        }
                    },
                    address: {
                        validators: {
                            notEmpty: {
                                message: 'The address confirmation is required'
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        // Handle submit button
        $('#update_profile_submit').on('click', function (e) {
            e.preventDefault();
            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                    $('.loader').show();
                	$('#profile_update_form').submit()
				}
                // KTUtil.scrollTop();
                scrollTop();
		    });
        });
    };


    var _handleUserForm = function(e) {

        var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('user_update_form'),
            {
                fields: {
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter first name.'
                            }
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter last name.'
                            }
                        }
                    },
                    address: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter address'
                            },
                        }
                    },
                    city: {
                        validators: {
                            callback: {
                                message: 'Please select a option from suggestion',
                                callback: function(input) {
                                    var address = $('#address').val();
                                    return (address == '') ? true : (input.value !== '') ;
                                }
                            }
                        }
                    },
                    // username: {
                    //     validators: {
                    //         callback: {
                    //             message: 'Please enter instagram username',
                    //             callback: function(input) {
                    //                 var type = $('#type_select').val();
                    //                 return (type == 1) ? true : (input.value !== '') ;
                    //             }
                    //         }
                    //     }
                    // },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email address is required'
                            },
                            emailAddress: {
                                message: 'The value is not a valid email address'
                            }
                        }
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: 'The phone is required'
                            }
                        }
                    },
                    dob: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter date of birth'
                            }
                        }
                    },
                    type: {
                        validators: {
                            notEmpty: {
                                message: 'Please select user type'
                            }
                        }
                    },
                    country_code: {
                        validators: {
                            notEmpty: {
                                message: 'Please select country code'
                            }
                        }
                    },
                    followers: {
                        validators: {
                            notEmpty: {
                                message: 'Followers are required'
                            },notNumber: {
                                message: 'Followers is number'
                            }
                        }
                    },
                    // profile_avatar: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: 'Image is required'
                    //         }
                    //     }
                    // },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password is required'
                            },
                            checkPassword: {
                                message: 'The password is too weak'
                            }
                        }
                    },
                    status: {
                        validators: {
                            notEmpty: {
                                message: 'Status is required'
                            }
                        }
                    },
                    category:{
                        validators: {
                            callback: {
                                message: 'Please select category',
                                callback: function(input) {
                                    var type = $('#type_select').val();
                                    return (type == 1) ? true : (input.value !== '') ;
                                }
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        ).registerValidator('checkPassword', strongPassword);;

        // Handle submit button
        $('.update_user_submit').on('click', function (e) {
            e.preventDefault();
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#user_update_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    };
    
       var _handleWebsetingform = function(e) {

        var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('manage_bank_details_form'),
            {
                fields: {
                    account_holder: {
                        validators: {
                            notEmpty: {
                                message: 'Please account holder name.'
                            }
                        }
                    },
                    personal_id_number: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter personal id number'
                            }
                        }
                    },
                    account_number: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter bank account details.'
                            }
                        }
                    },
                    sortCode: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter IFSC Code.'
                            }
                        }
                    },
                    documentFront: {
                        validators: {
                            notEmpty: {
                                message: 'Frontend photo id is required.'
                            }
                        }
                    },
                    documentBack: {
                        validators: {
                            notEmpty: {
                                message: 'Backend photo id is required.'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        ).registerValidator('checkPassword', strongPassword);;

        // Handle submit button
        $('#manage_bank_details_submit').on('click', function (e) {
            e.preventDefault();
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#manage_bank_details_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    };

    var _handleChangePassword = function(e) {

        var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var form = KTUtil.getById('change_password_form');
        validation = FormValidation.formValidation(
            form,
            {
                fields: {
                    cpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter your current password.'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter password.'
                            }
                        }
                    },
                    confirm_password: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter confirm password.'
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'The password and confirm password are not the same'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        ).registerValidator('checkPassword', strongPassword);

        // Handle submit button
        $('#change_password_submit').on('click', function (e) {
            e.preventDefault();
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#change_password_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    };

    var _handleFaqForm = function(e) {

        var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('faq_form'),
            {
                fields: {
                    cat_id: {
                        validators: {
                            notEmpty: {
                                message: 'Category is required'
                            }
                        }
                    },
                    question: {
                        validators: {
                            notEmpty: {
                                message: 'Question is required'
                            }
                        }
                    },
                    answer: {
                        validators: {
                            notEmpty: {
                                message: 'Answer is required'
                            }
                        }
                    }                    
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#update_faq_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#faq_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    };

    var _handleFaqCatForm = function(e) {

        var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('faq_cat_form'),
            {
                fields: {
                    cat_name: {
                        validators: {
                            notEmpty: {
                                message: 'Category name is required'
                            }
                        }
                    }                                    
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#faq_cat_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#faq_cat_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    };

    var _handlePlanCategoryForm = function(){
        var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('category_form'),
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Category name is required'
                            },
                            stringLength: {
                                max: 20,
                                message: 'Category name must be less than 20 characters'
                            }
                        }
                    }                                    
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#category_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#category_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    }

    var _handleRaceForm = function(){
        var validation;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('race_form'),
            {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter title'
                            },
                            stringLength: {
                                max: 20,
                                message: 'Title must be less than 20 characters'
                            }
                        }
                    }                                    
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#race_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#race_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    }

    var _handleCategoryPlanForm = function(){
        var validation;
        
        validation = FormValidation.formValidation(
            KTUtil.getById('category_plan_form'),
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter plan name.'
                            },
                            stringLength: {
                                max: 20,
                                message: 'Category name must be less than 20 characters'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter plan description.'
                            },
                            stringLength: {
                                max: 100,
                                message: 'Category name must be less than 100 characters'
                            }
                        }
                    },
                    commission: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter commission.'
                            }
                        }
                    }                                    
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#category_plan_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#category_plan_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    }

    var _handleCouponForm = function(){
        var validation;
        
        validation = FormValidation.formValidation(
            KTUtil.getById('coupon_form'),
            {
                fields: {
                    code: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter code.'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter description.'
                            }
                        }
                    },
                    expiry_date: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter expiry date.'
                            }
                        }
                    },
                    type: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter type.'
                            }
                        }
                    },discount: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter discount.'
                            }
                        }
                    }  
                    ,min_price: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter minimum price.'
                            }
                        }
                    }  
                    ,max_price: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter maximum price.'
                            }
                        }
                    }                                    
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#coupon_form_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#coupon_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    }
    
    var _handleWebSetting = function(){
        var validation;
        
        validation = FormValidation.formValidation(
            KTUtil.getById('website-setting'),
            {
                fields: {
                    twilio_accont_sid: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter Twilio Account SID.'
                            }
                        }
                    },
                    twilio_auth_token: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter Twilio Auth Token.'
                            }
                        }
                    },
                    twilio_from: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter Twilio account number.'
                            }
                        }
                    },
                    google_api_key: {
                        validators: {
                            notEmpty: {
                                message: 'Please Google API key.'
                            }
                        }
                    },
                    stripe_pk: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter stripe public key.'
                            }
                        }
                    },
                    stripe_sk: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter stripe secret key.'
                            }
                        }
                    },
                    stripe_currency: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter stripe currency.'
                            }
                        }
                    },
                    smtp_username: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter SMTP username.'
                            }
                        }
                    },
                    smtp_password: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter SMTP password.'
                            }
                        }
                    }                                     
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#website-setting-submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#website-setting').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    }

    var _handleCampaignEdit = function(){
        var validation;
        
        validation = FormValidation.formValidation(
            KTUtil.getById('campaign_form_edit'),
            {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter title.'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter description.'
                            }
                        }
                    },
                    location: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter location.'
                            }
                        }
                    },
                    website: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter website URL.'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );
    
        // Handle submit button
        $('#campaign_form_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#campaign_form_edit').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    
    }

    var _handleCampaign = function(){
        var validation;
        
        validation = FormValidation.formValidation(
            KTUtil.getById('campaign_form'),
            {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter title.'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter description.'
                            }
                        }
                    },
                    location: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter location.'
                            }
                        }
                    },
                    website: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter website URL.'
                            }
                        }
                    },
                    image: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter campaign logo.'
                            }
                        }
                    },
                    // image: {
                    //     validators: {
                    //         callback: {
                    //             message: 'Please enter campaign logo',
                    //             callback: function(input) {
                    //                 var id = $('#campaign_id').val();
                    //                 return true;
                    //             }
                    //         }
                    //     }
                    // },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );
    
        // Handle submit button
        $('#campaign_form_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#campaign_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });
    
    }

    var _handleJob = function(){
        var validation;
        
        validation = FormValidation.formValidation(
            KTUtil.getById('job_form'),
            {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter title.'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter description.'
                            }
                        }
                    },
                    caption: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter caption.'
                            }
                        }
                    },
                    "platforms[]": {
                        validators: {
                            notEmpty: {
                                message: 'Please select platforms.'
                            }
                        }
                    },
                    category: {
                        validators: {
                            notEmpty: {
                                message: 'Please select job category.'
                            }
                        }
                    },
                    image: {
                        validators: {
                            notEmpty: {
                                message: 'Please upload job cover image.'
                            }
                        }
                    },
                    image_video: {
                        validators: {
                            notEmpty: {
                                message: 'Please upload job image/video.'
                            }
                        }
                    },
                    influencers: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter number of influencers.'
                            }
                        }
                    },
                    minutes: {
                        validators: {
                            callback: {
                                message: 'Please select a duration of job',
                                callback: function(input) {
                                    var seconds = $('#seconds').val();
                                    console.log('minutes', input.value, seconds, input.value !== '' || seconds !== '' );
                                    return input.value == '' && (seconds == '' || seconds == 0 || seconds > 0) ? true : input.value !== ''  ;
                                }
                            }
                        }
                    },
                    seconds: {
                        validators: {
                            callback: {
                                message: 'Please select a duration of job',
                                callback: function(input) {
                                    var minutes = $('#minutes').val();
                                    console.log('seconds', input.value , minutes, input.value !== '' || minutes !== '' );
                                    return input.value == '' && (minutes == '' || minutes == 0 || minutes > 0) ?  true : input.value !== '' ;
                                }
                            }
                        }
                    },
                    promo_days: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter number of days.'
                            }
                        }
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter price.'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#job_form_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#job_form').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });

    }
    $(document).ready(function(){
            $("#file").change(function(event){
                $("#file_error").html("");
                $(".dropify").css("border-color","#F0F0F0");
                var file_size = $('#file')[0].files[0].size;
                var MAX_FILE_SIZE = 2 * 1024 * 1024; // 5MB
                if(file_size>MAX_FILE_SIZE) {
                    $("#file_error").html("<p style='color:#FF0000'>File size is greater than 2mb</p>");
                    $(".dropify").css("border-color","#FF0000");
                    $('input[type="submit"]').prop("disabled", true)
                    $(".placeorder_btn").prop('disabled', true)
                    return false;
                }
                $('input[type="submit"]').prop("disabled", false)
                $(".placeorder_btn").prop('disabled', false) 
                return true;
            });
    });
        
    var _handleJobEdit = function(){
        var validation;
        
        validation = FormValidation.formValidation(
            KTUtil.getById('job_form_edit'),
            {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter title.'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter description.'
                            }
                        }
                    },
                    platforms: {
                        validators: {
                            notEmpty: {
                                message: 'Please select platforms.'
                            }
                        }
                    },
                    category: {
                        validators: {
                            notEmpty: {
                                message: 'Please select job category.'
                            }
                        }
                    },
                    influencers: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter number of influencers.'
                            }
                        }
                    },
                    minutes: {
                        validators: {
                            callback: {
                                message: 'Please select a duration of job',
                                callback: function(input) {
                                    var seconds = $('#seconds').val();
                                    console.log('minutes', input.value, seconds, input.value !== '' || seconds !== '' );
                                    return input.value == '' && (seconds == '' || seconds == 0 || seconds > 0) ? true : input.value !== ''  ;
                                }
                            }
                        }
                    },
                    seconds: {
                        validators: {
                            callback: {
                                message: 'Please select a duration of job',
                                callback: function(input) {
                                    var minutes = $('#minutes').val();
                                    console.log('seconds', input.value , minutes, input.value !== '' || minutes !== '' );
                                    return input.value == '' && (minutes == '' || minutes == 0 || minutes > 0) ?  true : input.value !== '' ;
                                }
                            }
                        }
                    },
                    promo_days: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter number of days.'
                            }
                        }
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter price.'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#job_form_submit').on('click', function (e) {
            e.preventDefault();
            
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    $('.loader').show();
                    $('#job_form_edit').submit()
                }
                // KTUtil.scrollTop();
                scrollTop();
            });
        });

    }
 





    // Public Functions
    return {
        // public functions
        init: function() {
            if($('#profile_update_form').length){
                _handleProfileForm();
            }

            if($('#user_update_form').length){
                _handleUserForm();
            }

            if($('#change_password_form').length){
                _handleChangePassword();
            }

            if($('#faq_form').length){
                _handleFaqForm();
            }
            if($('#faq_cat_form').length){
                _handleFaqCatForm();
            }

            if($('#category_form').length){
                _handlePlanCategoryForm();   
            }

            if($('#category_plan_form').length){
                _handleCategoryPlanForm();   
            }
            if($('#coupon_form').length){
                _handleCouponForm();   
            }

            if($('#website-setting').length){
                _handleWebSetting();
            }

            if($('#campaign_form').length){
                _handleCampaign();
            }

            if($('#campaign_form_edit').length){
                _handleCampaignEdit();
            }

            if($('#job_form').length){
                _handleJob();
            }
            if($('#ob_form_edit').length){
                _handleJobEdit();
            }
            
            if($('#manage_bank_details_form').length){
                _handleWebsetingform();
            }

            if($('#race_form').length){
                _handleRaceForm();
            }
	    }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTValidation.init();

    $("#followers,#phone").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
                   return false;
        }
    });

    $('#dashboard_filter_type').on('change', function(){
        $('.loader').show();
        $('#dashboard_filter_from').submit();
    })    

     var editor =CKEDITOR.replace( 'editor', {
        // toolbar :
        // [
        //     { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
        //     { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        //     { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        //     { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
        //         'HiddenField' ] },
        //     '/',
        //     { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        //     { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
        //     '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        //     { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        //     { name: 'insert', items : [ 'Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
        //     '/',
        //     { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
        //     { name: 'colors', items : [ 'TextColor','BGColor' ] },
        //     { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
        //     { name: 'insert', items: [ 'Image']},
        // ],
        extraPlugins: 'sourcearea,html5video,widget,widgetselection,clipboard,lineutils',
        allowedContent : true,
    } );
     CKFinder.setupCKEditor( editor );

    CKEDITOR.on('instanceReady', function () {
        $.each(CKEDITOR.instances, function (instance) {
            CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
            CKEDITOR.instances[instance].document.on("paste", CK_jQ);
            CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
            CKEDITOR.instances[instance].document.on("blur", CK_jQ);
            CKEDITOR.instances[instance].document.on("change", CK_jQ);
        });
    });
    
    function CK_jQ() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }

    // ClassicEditor
    // .create( document.querySelector( '#editor' ), {
    // removePlugins: [ 'Blockquote', 'Image', 'List' ],
    // extraPlugins : ['sourcearea'],
    // toolbar: { items: ["heading", "|", "bold", "italic", "link", "bulletedList", "numberedList", "|", "blockQuote", "insertTable", "undo", "redo"] },
    // } )
    // .catch( error => {
    //     //console.error( error );
    // } );
    $('select').select2();
    $('#user_update_form,#category_form,#user_update_form,#faq_cat_form,#faq_form').on("submit", function(){
        $('.loader').show();
    });
    
});



(function(window, $) {
    window.LaravelDataTables = window.LaravelDataTables || {};
    window.LaravelDataTables["user-table"] = $("#user_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            "data": function(data) {
                return data;
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                delete data.search.regex;
                // $('.loader').hide();
            },
            complete: function(){
                $('.loader').hide();
            }
            
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "first_name",
            "name": "first_name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "email",
            "name": "email",
            "title": "Email",
            "orderable": true,
            "searchable": true
        }, {
            "data": "phone",
            "name": "phone",
            "title": "Phone Number",
            "orderable": true,
            "searchable": true
        },{
            "data": "created_at",
            "name": "created_at",
            "title": "Created Date",
            "orderable": false,
            "searchable": true,
            "className": "text-right"
        },{
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    if(data[1] != null){
                            status = 'Deleted';
                            class_ = 'danger';
                    }else{
                        switch (data[0]) {
                            case 1:
                                status = 'Pending';
                                class_ = 'warning';
                                break;
                            case 2:
                                status = 'Active';
                                class_ = 'success';
                                break;
                            case 3:
                                status = 'Deactivated';
                                class_ = 'danger';
                                break;
                            case 4:
                                status = 'Banned';
                                class_ = 'info';
                                break;
                        }
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {                    
                    if(data[2] != null){
                        $html = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="User" url="/admin/restore/' + data[0] +'" redirecturl="/admin/users">\
                                <span>\
                                    <i class="fa fa-redo"></i>\
                                </span>\
                            </a>';
                    }else{
                        $html = '<a href="'+ HOST_URL + '/admin/user/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="View">\
                            <span class="svg-icon svg-icon-md"><i class="fa fa-eye"></i>\
                            </span>\
                        </a>\
                        <a href="'+ HOST_URL + '/admin/edituser/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>';
                        $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="User" url="/admin/delete/' + data[0] +'" redirecturl="/admin/users">\
                                <span class="svg-icon svg-icon-md">\
                                    <i class="fa fa-trash"></i>\
                                </span>\
                            </a>';
                    }
                    // if(data[1] == 1){
                    //     $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon ban" title="Ban" data-type="User" url="/admin/banuser/' + data[0] +'" redirecturl="/admin/users">\
                    //         <span class="svg-icon svg-icon-md">\
                    //             <i class="fa fa-ban"></i>\
                    //         </span>\
                    //     </a>';
                    // }else{
                    //     $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Archive" data-type="User" url="/admin/restore/' + data[0] +'" redirecturl="/admin/users">\
                    //         <span class="svg-icon svg-icon-md">\
                    //          <i class="fa fa-history"></i>\
                    //         </span>\
                    //     </a>';
                    // }

                    return $html ;

                }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });
    //window.LaravelDataTables = window.LaravelDataTables || {};
    window.LaravelDataTables["user-table"] = $("#influencer_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            "data": function(data) {
                return data;
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                delete data.search.regex;
                // $('.loader').hide();
            },
            complete: function(){
                $('.loader').hide();
            }
            
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "first_name",
            "name": "first_name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "email",
            "name": "email",
            "title": "Email",
            "orderable": true,
            "searchable": true
        }, {
            "data": "phone",
            "name": "phone",
            "title": "Phone Number",
            "orderable": true,
            "searchable": true
        }, {
            "data": "ins_username",
            "name": "ins_username",
            "title": "Instagram Username/Followers",
            "orderable": false,
            "searchable": true
        }, {
            "data": "facebook_username",
            "name": "facebook_username",
            "title": "Facebook Username/Followers",
            "orderable": false,
            "searchable": true,
            "className": "text-right"
        },
        {
            "data": "youtube_username",
            "name": "youtube_username",
            "title": "Youtube Username/Followers",
            "orderable": false,
            "searchable": true
        }, 
        {
            "data": "tiktok_username",
            "name": "tiktok_username",
            "title": "Tiktok Username/Followers",
            "orderable": false,
            "searchable": true,
            "className": "text-right"
        },
        {
            "data": "twitter_username",
            "name": "twitter_username",
            "title": "Twitter Username/Followers",
            "orderable": false,
            "searchable": true,
            "className": "text-right"
        },{
            "data": "created_at",
            "name": "created_at",
            "title": "Created Date",
            "orderable": false,
            "searchable": true,
            "className": "text-right"
        },{
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    if(data[1] != null){
                            status = 'Deleted';
                            class_ = 'danger';
                    }else{
                        switch (data[0]) {
                            case 1:
                                status = 'Pending';
                                class_ = 'warning';
                                break;
                            case 2:
                                status = 'Active';
                                class_ = 'success';
                                break;
                            case 3:
                                status = 'Deactivated';
                                class_ = 'danger';
                                break;
                            case 4:
                                status = 'Banned';
                                class_ = 'info';
                                break;
                        }
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },{
            "data": "user_plans_count",
            "name": "user_plans_count",
            "title": "Plans",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                return data[2] == 2 ? '<a href="'+HOST_URL+'/admin/user-plans/' + data[0] +'">'+data[1]+'</a>' : ' -- ';
            }
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {                    
                    if(data[2] != null){
                        $html = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="User" url="/admin/restore/' + data[0] +'" redirecturl="/admin/users">\
                                <span>\
                                    <i class="fa fa-redo"></i>\
                                </span>\
                            </a>';
                    }else{
                        $html = '<a href="'+ HOST_URL + '/admin/influencersview/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="View">\
                            <span class="svg-icon svg-icon-md"><i class="fa fa-eye"></i>\
                            </span>\
                        </a>\
                        <a href="'+ HOST_URL + '/admin/influenceredit/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>';
                        $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="User" url="/admin/delete/' + data[0] +'" redirecturl="/admin/users">\
                                <span class="svg-icon svg-icon-md">\
                                    <i class="fa fa-trash"></i>\
                                </span>\
                            </a>';
                    }
                    // if(data[1] == 1){
                    //     $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon ban" title="Ban" data-type="User" url="/admin/banuser/' + data[0] +'" redirecturl="/admin/users">\
                    //         <span class="svg-icon svg-icon-md">\
                    //             <i class="fa fa-ban"></i>\
                    //         </span>\
                    //     </a>';
                    // }else{
                    //     $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Archive" data-type="User" url="/admin/restore/' + data[0] +'" redirecturl="/admin/users">\
                    //         <span class="svg-icon svg-icon-md">\
                    //          <i class="fa fa-history"></i>\
                    //         </span>\
                    //     </a>';
                    // }

                    return $html ;

                }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });




    window.LaravelDataTables["influence-table"] = $("#influencer_request_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }

        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "email",
            "name": "email",
            "title": "Email",
            "orderable": true,
            "searchable": true
        }, {
            "data": "phone",
            "name": "phone",
            "title": "Phone Number",
            "orderable": true,
            "searchable": true
        }, 
        {
            "data": "ins_username",
            "name": "ins_username",
            "title": "Instagram Username/Followers",
            "orderable": true,
            "searchable": true
        }, 
        {
            "data": "facebook_username",
            "name": "facebook_username",
            "title": "Facebook Username/Followers",
            "orderable": true,
            "searchable": true,
            "className": "text-right"
        },
        {
            "data": "youtube_username",
            "name": "youtube_username",
            "title": "Youtube Username/Followers",
            "orderable": true,
            "searchable": true
        }, 
        {
            "data": "tiktok_username",
            "name": "tiktok_username",
            "title": "Tiktok Username/Followers",
            "orderable": true,
            "searchable": true,
            "className": "text-right"
        },
        {
            "data": "twitter_username",
            "name": "twitter_username",
            "title": "Twitter Username/Followers",
            "orderable": true,
            "searchable": true,
            "className": "text-right"
        }, 
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": true,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    if(data[1] != null){
                            status = 'Deleted';
                            class_ = 'danger';
                    }else{
                        switch (data[0]) {
                            case 0:
                                status = 'Pending';
                                class_ = 'warning';
                                break;
                            case 1:
                                status = 'Approved';
                                class_ = 'success';
                                break;
                            case 2:
                                status = 'Rejected';
                                class_ = 'danger';
                                break;
                        }
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                // console.log('sdsdsd', data);
                    if(data[2] != null){
                        $html = '</a><a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Influencer Request" url="/admin/restorefluencer/' + data[0] +'" redirecturl="/admin/users">\
                                <span class="svg-icon svg-icon-md">\
                                    <i class="fa fa-redo"></i>\
                                </span>\
                            </a>';
                    }
                    
                    else{ 
                        $html = '<a href="'+ HOST_URL + '/admin/influencer/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="View">\
                            <span class="svg-icon svg-icon-md"><i class="fa fa-eye"></i>\
                            </span>';       
                        $html += '</a><a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Influencer Request" url="/admin/deleteinfluencer/' + data[0] +'" redirecturl="/admin/users">\
                                <span class="svg-icon svg-icon-md">\
                                    <i class="fa fa-trash"></i>\
                                </span>\
                            </a>';

                        $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon approve" title="Approve" data-type="Influencer Request" url="/admin/approveinfluencer/' + data[0] +'" redirecturl="/admin/users">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-check"></i>\
                            </span>\
                        </a><a href="javascript:;" class="btn btn-sm btn-clean btn-icon approve" title="Reject" data-type="Influencer Request" url="/admin/rejectinfluencer/' + data[0] +'" redirecturl="/admin/users">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-times"></i>\
                            </span>\
                        </a>';
                    }
                    
                    return $html ;

                }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });
    
    window.LaravelDataTables["influence-table"] = $("#faq_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "question",
            "name": "question",
            "title": "Question",
            "orderable": true,
            "searchable": true
        }, {
            "data": "answer",
            "name": "answer",
            "title": "Answer",
            "orderable": true,
            "searchable": true
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    if(data[2] != null){
                        $html = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="restore" data-type="Category" url="/admin/restore-faq/' + data[0] +'" redirecturl="/admin/users">\
                            <span class="svg-icon svg-icon-md">\
                               <i class="fa fa-redo"></i>  </span>\
                        </a>';
                    }else{
                    $html = '<a href="'+ HOST_URL + '/admin/add-edit-faq/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit details">\
                            <span class="svg-icon svg-icon-md">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a><a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Faq" url="/admin/delete-faq/' + data[0] +'" redirecturl="/admin/users">\
                            <span class="svg-icon svg-icon-md">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>';
                    }

                    return $html ;

                }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });

    window.LaravelDataTables["influence-table"] = $("#faq_cat_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "cat_name",
            "name": "cat_name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        },{
            "data": "faqs_count",
            "name": "faqs_count",
            "title": "No of Faqs",
            "orderable": true,
            "searchable": true,
            render: function(data, type) {
                return '<a href="'+HOST_URL+'/admin/faq-list/' + data[0] +'">'+data[1]+'</a>';
            }
        }, {
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    if(data[2] != null){
                        $html = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="restore" data-type="Category" url="/admin/retrieve-faq-cat/' + data[0] +'" redirecturl="/admin/users">\
                            <span class="svg-icon svg-icon-md">\
                               <i class="fa fa-redo"></i>  </span>\
                        </a>';
                    }else{

                        $html = '<a href="'+ HOST_URL + '/admin/add-faq-cat/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit details">\
                            <span class="svg-icon svg-icon-md">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>';
                        $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Category" url="/admin/delete-faq-cat/' + data[0] +'" redirecturl="/admin/users">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-trash"></i>                            </span>\
                        </a>';
                    }

                    return $html ;

                }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });

    window.LaravelDataTables["influence-table"] = $("#contact_us_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },{
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "email",
            "name": "email",
            "title": "Email",
            "orderable": true,
            "searchable": true
        },{
            "data": "description",
            "name": "description",
            "title": "Description",
            "orderable": true,
            "searchable": true
        }/*,{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    $html = '<a href="'+ HOST_URL + '/admin/add-edit-faq/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit details">\
                            <span class="svg-icon svg-icon-md">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a><a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="User" url="/admin/delete-faq/' + data[0] +'" redirecturl="/admin/users">\
                            <span class="svg-icon svg-icon-md">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>';
                    

                    return $html ;

                }
        }*/],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });



     window.LaravelDataTables["category-table"] = $("#category_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "plans_count",
            "name": "plans_count",
            "title": "No of Plans",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                return '<a href="'+HOST_URL+'/admin/plan-category/' + data[0] +'">'+data[1]+'</a>';
            }
        },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    if(data[1] != null){
                            status = 'Deleted';
                            class_ = 'danger';
                    }else{
                        switch (data[0]) {
                            case 1:
                                status = 'Active';
                                class_ = 'success';
                                break;
                            case 2:
                                status = 'Archived';
                                class_ = 'danger';
                                break;
                        }
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    if(data[2] != null){
                        $html =  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Plan Category" url="/admin/restore-category/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-redo"></i>\
                            </span>\
                        </a>';
                    }else{
                        $html = '<a href="'+ HOST_URL + '/admin/plan-category/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="View">\
                            <span class="svg-icon svg-icon-md"><i class="fa fa-eye"></i>\
                            </span>\
                        </a>\
                        <a href="'+ HOST_URL + '/admin/edit-category/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>';
                    
                        $html +=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Plan Category" url="/admin/delete-category/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-trash"></i>\
                            </span>\
                        </a>';
                    }

                    return $html ;

                }
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    }); 

     window.LaravelDataTables["category-table"] = $("#manage_category_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "ID",
            "orderable": false,
            "searchable": false
        }, 
        {
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "image",
            "name": "image",
            "title": "Category Icon",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    if(data[1] != null){
                            status = 'Deleted';
                            class_ = 'danger';
                    }else{
                        switch (data[0]) {
                            case 1:
                                status = 'Active';
                                class_ = 'success';
                                break;
                            case 2:
                                status = 'Archived';
                                class_ = 'danger';
                                break;
                        }
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    if(data[2] != null){
                        $html = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Category" url="/admin/restore-manage-category/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-redo"></i>\
                            </span>\
                        </a>';
                    }else{
                        $html = '<a href="'+ HOST_URL + '/admin/edit-manage-category/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>'
                    
                        $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Category" url="/admin/delete-manage-category/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-trash"></i>\
                            </span>\
                        </a>';
                    }
                    return $html ;

                }
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });


window.LaravelDataTables["race-table"] = $("#manage_race_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "title",
            "name": "title",
            "title": "Title",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });

    window.LaravelDataTables["user_plan_listing-table"] = $("#user_plan_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "category",
            "name": "category",
            "title": "Category",
            "orderable": true,
            "searchable": true
        }, {
            "data": "price",
            "name": "price",
            "title": "Price",
            "orderable": true,
            "searchable": true
        }
        
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });


     window.LaravelDataTables["category-package-table"] = $("#category_package_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "description",
            "name": "description",
            "title": "Description",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": true,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    switch (data) {
                        case 1:
                            status = 'Active';
                            class_ = 'success';
                            break;
                        case 2:
                            status = 'Deactivated';
                            class_ = 'danger';
                            break;
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },
        {
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    $html = '<a href="'+ HOST_URL + '/admin/plan-category/' + data[2] +'/edit-plan/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>';
                    if(data[3] != null){
                        $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Plan" url="/admin/restore-plan/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-redo"></i>\
                            </span>\
                        </a>';
                    }else{
                        $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Plan" url="/admin/delete-plan/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-trash"></i>\
                            </span>\
                        </a>';
                    }

                    return $html ;

                }
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });


    window.LaravelDataTables["orders-table"] = $("#orders_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }

        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },{
            "data": "order_id",
            "name": "order_id",
            "title": "Order Id",
            "orderable": true,
            "searchable": true
        }, {
            "data": "name",
            "name": "user.first_name",
            "title": "Name",
            "orderable": true,
            "searchable": true,
            render: function(data, type) {
                console.log(data);
                return '<a href="'+HOST_URL+'/admin/user/' + data[0] +'">'+data[1]+'</a>';
            }
        }, {
            "data": "order_items_count",
            "name": "order_items_count",
            "title": "Items",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                // console.log(data);
                return '<a href="'+HOST_URL+'/admin/order-items/' + data[0] +'">'+data[1]+'</a>';
            }
        }, {
            "data": "billing_name",
            "name": "billing_first_name",
            "title": "Billing User Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "address",
            "name": "address",
            "title": "Address",
            "orderable": true,
            "searchable": true,
            "className": "text-right"
        },{
            "data": "created_at_c",
            "name": "created_at",
            "title": "Created At",
            "orderable": true,
            "searchable": true,
            "className": "text-right"
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                // console.log('sdsdsd', data);
                $html = '<a href="'+ HOST_URL + '/admin/view-order/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="View">\
                            <span class="svg-icon svg-icon-md"><i class="fa fa-eye"></i>\
                            </span>'; 
                    return $html ;

            }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });

    window.LaravelDataTables["orders-table"] = $("#orderitems_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }

        },
        "columns": [{
            "data": "rank",
            "name": "rank",
            "title": "Sr. No.",
            "orderable": true,
            "searchable": false
        }, {
            "data": "plan_name",
            "name": "userPlan.plan.name",
            "title": "Plan Name",
            "orderable": true,
            "searchable": true
        },{
            "data": "influencer_name",
            "name": "userPlan.allUser.first_name",
            "title": "Influencer Name",
            "orderable": true,
            "searchable": true
        }, {
            "data": "cur_price",
            "name": "price",
            "title": "Plan Price",
            "orderable": true,
            "searchable": true
        },{
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": true,
            "searchable": true,
            render: function(data) {
                    var class_ = '';
                    switch (data) {
                        case 1:
                            status = 'Accepted';
                            class_ = 'success';
                            break;
                        case 0:
                            status = 'Pending';
                            class_ = 'danger';
                            break;
                        case 2:
                            status = 'Rejected';
                            class_ = 'danger';
                            break;
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });

    window.LaravelDataTables["orders-table"] = $("#transactions_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }

        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },{
            "data": "order_id",
            "name": "order_id",
            "title": "Order Id",
            "orderable": true,
            "searchable": true
        }, {
            "data": "user.first_name",
            "name": "user.first_name",
            "title": "User / Influencer",
            "orderable": true,
            "searchable": true
        }, {
            "data": "type_c",
            "name": "user.type",
            "title": "User Type",
            "orderable": false,
            "searchable": true
        }, {
            "data": "total",
            "name": "order.total",
            "title": "Total Price",
            "orderable": true,
            "searchable": true
        }, {
            "data": "discount",
            "name": "order.discount_price",
            "title": "Coupon Discount",
            "orderable": true,
            "searchable": true
        },{
            "data": "commission_c",
            "name": "commision",
            "title": "Commission",
            "orderable": false,
            "searchable": true
        },{
            "data": "influencer_paid_amount",
            "name": "amount",
            "title": "Influencer Paid Amount",
            "orderable": true,
            "searchable": true
        },{
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    switch (data) {
                        case 'Success':
                            status = 'Success';
                            class_ = 'success';
                            break;
                        case 'Failed':
                            status = 'Failed';
                            class_ = 'danger';
                            break;
                        case 'Pending':
                            status = 'Pending';
                            class_ = 'info';
                            break;
                        case '0':
                        case 0:
                            status = 'Pending';
                            class_ = 'info';
                            break;
                        case '1':
                        case 1:
                            status = 'Paid';
                            class_ = 'success';
                            break;
                        case 0:
                            status = 'Failed';
                            class_ = 'danger';
                            break;
                        case '1':
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        }, {
            "data": "transaction_no",
            "name": "transaction_no",
            "title": "Transaction No",
            "orderable": true,
            "searchable": true
        }, {
            "data": "transaction_time",
            "name": "transaction_time",
            "title": "Transaction Time",
            "orderable": true,
            "searchable": true
        },{
            "data": "type",
            "name": "type",
            "title": "Type",
            "orderable": false,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    switch (data) {
                        case 'credit':
                            status = 'Credit';
                            class_ = 'success';
                            break;
                        case 'debit':
                            status = 'Debit';
                            class_ = 'danger';
                            break;
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                // console.log('sdsdsd', data);
                $html = '<a href="'+ HOST_URL + '/admin/download-pdf/' + data +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Download Pdf">\
                            <span class="svg-icon svg-icon-md"><i class="fa fa-download"></i>\
                            </span>'; 
                    return $html ;

            }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });

    window.LaravelDataTables["orders-table"] = $("#coupons_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }

        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "code",
            "name": "code",
            "title": "Code",
            "orderable": true,
            "searchable": true
        }, {
            "data": "description",
            "name": "description",
            "title": "Description",
            "orderable": true,
            "searchable": true
        }, {
            "data": "expiry_date",
            "name": "expiry_date",
            "title": "Expiry Date",
            "orderable": true,
            "searchable": true
        }, {
            "data": "type",
            "name": "type",
            "title": "Type",
            "orderable": false,
            "searchable": true
        }, {
            "data": "discount_c",
            "name": "discount",
            "title": "Discount",
            "orderable": true,
            "searchable": true
        }, {
            "data": "min_price_c",
            "name": "min_price",
            "title": "Minimum Price",
            "orderable": true,
            "searchable": true
        }, {
            "data": "max_price_c",
            "name": "max_price",
            "title": "Max Price",
            "orderable": true,
            "searchable": true
        },{
            "data": "order_count",
            "name": "order_count",
            "title": "Used Coupons",
            "orderable": true,
            "searchable": false,
            render: function(data, type) {
            //console.log(data);
                return '<a href="'+ HOST_URL + '/admin/view-coupons-details/' + data[0] +'">'+data[1]+'</a>';
            }
        },{
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    var option = '';
                    
                    var current_date = new Date();
                    var expiry_date = new Date(data[0]);
                    if(current_date > expiry_date){
                        option = 2;
                    }else{
                        option = 1;
                    }
                    switch (option) {
                        case 1:
                            status = 'Active';
                            class_ = 'success';
                            break;
                        case 2:
                            status = 'Expired';
                            class_ = 'danger';
                            break;
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },{
            "data": "views_coupons",
            "name": "views_coupons",
            "title": "View Coupons",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
            //console.log(data);
                return '<a href="'+ HOST_URL + '/admin/view-coupons-details/' + data +'"><i class="fa fa-eye" aria-hidden="true"></i></a>';
            }
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                // console.log('sdsdsd', data);
                $html = '<a href="'+ HOST_URL + '/admin/edit-coupon/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>';
                if(data[3] != null){
                    $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Plan" url="/admin/restore-plan/' + data[0] +'">\
                        <span class="svg-icon svg-icon-md">\
                            <i class="fa fa-redo"></i>\
                        </span>\
                    </a>';
                }else{
                    $html += '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Plan" url="/admin/delete-plan/' + data[0] +'">\
                        <span class="svg-icon svg-icon-md">\
                            <i class="fa fa-trash"></i>\
                        </span>\
                    </a>';
                }

                return $html ;

            }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });

window.LaravelDataTables["orders-tables"] = $("#users_coupons_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }

        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        }, {
            "data": "billing_first_name",
            "name": "billing_first_name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        },{
            "data": "code",
            "name": "code",
            "title": "Code",
            "orderable": true,
            "searchable": true
        },{
            "data": "discount",
            "name": "discount",
            "title": "Discount",
            "orderable": true,
            "searchable": true
        }, {
            "data": "description",
            "name": "description",
            "title": "Description",
            "orderable": true,
            "searchable": true
        },{
            "data": "vieworder",
            "name": "vieworder",
            "title": "Order ID",
            "orderable": true,
            "searchable": true,
            render: function(data, type) {
            //console.log(data);
                return '<a target="_blank" href="'+ HOST_URL + '/admin/view-order/' + data +'">'+ data +'</a>';
            }
        }],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
       
    });

    
    window.LaravelDataTables["social_platforms-table"] = $("#social_platforms_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "categories",
            "name": "categories",
            "title": "Categories",
            "orderable": false,
            "searchable": true
        },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
            render: function(data, type) {
                    var status = '';
                    var class_ = '';
                    if(data[1] != null){
                            status = 'Deleted';
                            class_ = 'danger';
                    }else{
                        switch (data[0]) {
                            case 1:
                                status = 'Active';
                                class_ = 'success';
                                break;
                            case 2:
                                status = 'Archived';
                                class_ = 'danger';
                                break;
                        }
                    }

                    return '<span style="width: 137px;">\
                                        <span class="label font-weight-bold label-lg label-light-'+class_+' label-inline">'+status+'</span>\
                                    </span> ';
                }

        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    if(data[2] != null){
                        $html =  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Social Platform" url="/admin/social-platform/restore/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-redo"></i>\
                            </span>\
                        </a>';
                    }else{
                        $html = '<a href="'+ HOST_URL + '/admin/social-platform/edit/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>';
                    
                        $html +=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Social Platform" url="/admin/social-platform/delete/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-trash"></i>\
                            </span>\
                        </a>';
                    }

                    return $html ;

                }
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });


    window.LaravelDataTables["campaign-table"] = $("#campaign_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "title",
            "name": "title",
            "title": "Title",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "user_name",
            "name": "user_name",
            "title": "Name",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                return '<a href="'+HOST_URL+'/admin/user/' + data[1] +'">'+data[2]+'</a>';
            }
        },
        {
            "data": "location",
            "name": "location",
            "title": "Location",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "created_at",
            "name": "created_at",
            "title": "Created Date",
            "orderable": false,
            "searchable": true 
        },
        
        {
            "data": "jobs_count",
            "name": "jobs_count",
            "title": "Number Of Jobs",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                return '<a href="'+HOST_URL+'/admin/campaign/' + data[0] +'/jobs">'+data[1]+'</a>';
            }
        },
        {
            "data": "image",
            "name": "image",
            "title": "Campaign Logo",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
            "className": "text-right",
            render: function(data, type) {
                    if(data[2] != null){
                        $html =  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Campaign" url="/admin/campaign/restore/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-redo"></i>\
                            </span>\
                        </a>';
                    }else{

                        $html = '<a href="'+ HOST_URL + '/admin/campaign/' + data[0] +'/jobs" class="btn btn-sm btn-clean btn-icon mr-2" title="Manage Jobs">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-tasks"></i>\
                            </span>\
                        </a>';

                        $html += '<a href="'+ HOST_URL + '/admin/campaign/edit/' + data[0] +'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">\
                            <span class="svg-icon svg-icon-md">\<i class="fa fa-edit"></i>\
                            </span>\
                        </a>';
                    
                        $html +=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Campaign" url="/admin/campaign/delete/' + data[0] +'">\
                            <span class="svg-icon svg-icon-md">\
                                <i class="fa fa-trash"></i>\
                            </span>\
                        </a>';
                    }

                    return $html ;

                }
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });

    window.LaravelDataTables["jobs-table"] = $("#jobs_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "title",
            "name": "title",
            "title": "Title",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "user_name",
            "name": "user_name",
            "title": "Name",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                if(data[3]){

                return '';
            }else{
                return '<a href="'+HOST_URL+'/admin/user/' + data[1] +'">'+data[2]+'</a>';

            }
            }
        },
        {
            "data": "categories",
            "name": "categories",
            "title": "Categories",
            "orderable": false,
            "searchable": true
        },
        {
            "data": "created_at",
            "name": "created_at",
            "title": "Created Date",
            "orderable": false,
            "searchable": true
        },
        {
            "data": "duration",
            "name": "duration",
            "title": "Duration",
            "orderable": false,
            "searchable": true
        },
        {
            "data": "influencers",
            "name": "influencers",
            "title": "Influencers",
            "orderable": false,
            "searchable": true
        },
        {
            "data": "price",
            "name": "price",
            "title": "Price",
            "orderable": false,
            "searchable": true
        },
        {
            "data": "promo_days",
            "name": "promo_days",
            "title": "Promo Days",
            "orderable": true,
            "searchable": true
        },
        // {
        //     "data": "image",
        //     "name": "image",
        //     "title": "Campaign Logo",
        //     "orderable": false,
        //     "searchable": false
        // },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": false,
            "searchable": true,
        },{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });

    window.LaravelDataTables["wallet-table"] = $("#wallet_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "user_name",
            "name": "user_name",
            "title": "User Name",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "type_c",
            "name": "type_c",
            "title": "User Type",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "amount",
            "name": "amount",
            "title": "Wallet Amount",
            "orderable": true,
            "searchable": true
        }
        ,{
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": false,
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });
    
    window.LaravelDataTables["wallet_transaction-table"] = $("#wallet_transaction_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "user_name",
            "name": "user_name",
            "title": "User Name",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "type",
            "name": "type",
            "title": "Transaction Type",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "amount",
            "name": "amount",
            "title": "Transaction Amount",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "description",
            "name": "description",
            "title": "Description",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "created_at",
            "name": "created_at",
            "title": "Created Date",
            "orderable": true,
            "searchable": true
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });

    window.LaravelDataTables["wallet_transaction-table"] = $("#admin_wallet_transaction_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "user_name",
            "name": "user_name",
            "title": "User Name",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "influencer_id",
            "name": "influencer_id",
            "title": "Influencers Name",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "job_id",
            "name": "job_id",
            "title": "Job Name",
            "orderable": false,
            "searchable": true
        },
        {
            "data": "amount",
            "name": "amount",
            "title": "Transaction Amount",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "commission",
            "name": "commission",
            "title": "Commission",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "type",
            "name": "type",
            "title": "Transaction Type",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "date",
            "name": "date",
            "title": "Date",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "description",
            "name": "description",
            "title": "Description",
            "orderable": true,
            "searchable": true
        }
        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });


    window.LaravelDataTables["testimonial-table"] = $("#testimonial_data_listing").DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "",
            "type": "GET",
            beforeSend: function( xhr ) {
                $('.loader').show();
            },
            complete: function(){
                $('.loader').hide();
            },
            "data": function(data) {
                return data;
            }
        },
        "columns": [{
            "data": "DT_RowIndex",
            "name": "DT_RowIndex",
            "title": "Sr. No.",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "name",
            "name": "name",
            "title": "Name",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "description",
            "name": "description",
            "title": "Description",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "status",
            "name": "status",
            "title": "Status",
            "orderable": true,
            "searchable": true
        },
        {
            "data": "created_at",
            "name": "created_at",
            "title": "Created at",
            "orderable": false,
            "searchable": true
        },
         {
            "data": "action1",
            "name": "action1",
            "title": "Description",
            "orderable": false,
            "searchable": false,
            render: function(data, type) {
                //console.log(data);
                return '<a class="btn btn-info btn-lg viewDescription" data-toggle="modal" data-target="#myModal" href="javascript:" data-id="' + data[0] +'"><i class="fa fa-eye"></i></a>';
            }
        }, 
        {
            "data": "action",
            "name": "action",
            "title": "Action",
            "orderable": false,
            "searchable": true
        }

        ],
        "autoWidth": false,
        "select": {
            "style": "single"
        },
        "responsive": true,
        
    });
    


})(window, jQuery);



$(document).on('click', '.delete', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure?',
        text: "Do you want to delete this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Delete",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: typeof result.success != 'undefined' ? result.success : result.error ,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});


$(document).on('click', '.restore', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure?',
        text: "Do you want to restore this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Restore",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: typeof result.success != 'undefined' ? result.success : result.error ,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});

$(document).on('click', '.retrieve', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure?',
        text: "Do you want to retrieve this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Retrieve",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: typeof result.success != 'undefined' ? result.success : result.error ,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});
$(document).on('click', '.approve', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure?',
        text: "Do you want to approve this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Approve",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: typeof result.success != 'undefined' ? result.success : result.error ,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});
$(document).on('click', '.delete', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure?',
        text: "Do you want to delete this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Delete",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: typeof result.success != 'undefined' ? result.success : result.error ,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});
$(document).on('click', '.ban', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure',
        text: "want to ban this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Ban",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: result.success,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});

$(document).on('click', '.deactivate', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure',
        text: "Do you want to deactivate this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Deactivate",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: result.success,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});

$(document).on('click', '.activate', function(){
    var type = $(this).data('type');
    var url = $(this).attr('url');
    var redirecturl = $(this).attr('redirecturl');
    swal.fire({
        title : 'Are you sure',
        text: "Do you want to activate this "+type+" ?",
        type: "danger",
        buttonsStyling: false,
        confirmButtonText: "Yes! Activate",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: url, 
                success: function(result){
                    swal.fire({
                        text: result.success,
                        icon: typeof result.success != 'undefined' ? 'success' : 'error',
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        window.location.reload();
                        KTUtil.scrollTop();
                    });
                }
            });
        }
    });
});


$(document).on('change', '#profile-image-upload',function(){
    var file = this.files[0];
    $('#profile_avatar_remove').val('');
    var reader = new FileReader();
    reader.onloadend = function () {
       $('#kt_profile_avatar .image-input-wrapper').css('background-image', 'url("' + reader.result + '")');
       $('.align-items-center .symbol-60 .symbol-label').css('background-image', 'url("' + reader.result + '")');
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
    }
});

$(document).on('click', '#remove-avatar', function(){
    $('#kt_profile_avatar .image-input-wrapper').css('background-image', 'url("/media/users/default.jpg")');
    $('.align-items-center .symbol-60 .symbol-label').css('background-image', 'url("/media/users/default.jpg")');    
    $('#profile_avatar_remove').val(1);
    $(this).hide();
})

$(document).on('change', '#type_select', function(){
    var val = $(this).val();
    if(val == 1){
        $('.instagram-username').hide();
        $('.category').hide();
    }else{
        $('.instagram-username').show();
        $('.category').show();
    }
});

$(document).on('keypress', '#dob', function(){
    return false;
})

$('.view_password').on('click', function(){
    var for_ = $(this).attr('for');
    var x = document.getElementById(for_);
    if (x.type === "password") {
        $(this).find('.fa').attr('class','fa fa-eye-slash');
        x.type = "text";
    } else {
        $(this).find('.fa').attr('class','fa fa-eye');
        x.type = "password";
    }
});

function intiallizeAutocomplete(){
    var year = (new Date).getFullYear();
    var past_year = parseInt(year) - 13;
    $('.datepicker').datepicker({
        format: "yyyy/mm/dd",
        startDate: "01/01/1900",
        endDate: past_year+"/12/31",
        // endDate: moment().subtract(1, 'years').format('YYYY-MM-DD'),
    }).on('change', function(){
        $('.datepicker-dropdown').hide();
    });

    if($('#address').length){
        initiateAddress();
    }

}

$('.created_at').datepicker({
    format: "yyyy-mm-dd",
});

$('.links a').click(function(){
    var id = $(this).attr('id');
    switch(id){
        case 'today':
            var d = new Date();
            var output = getdate_by_date(d);
            $('#from').val(output);
            $('#to').val(output);

        break;

        case 'current_month':
            var date = new Date();
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);            
            var output1 = getdate_by_date(firstDay);
            var output2 = getdate_by_date(lastDay);
            $('#from').val(output1);
            $('#to').val(output2);
        break;

        case 'current_week':
            var curr = new Date;
            var firstday = new Date(curr.setDate(curr.getDate() - curr.getDay()));
            var lastday = new Date(curr.setDate(curr.getDate() - curr.getDay()+6));
            var output1 = getdate_by_date(firstday);
            var output2 = getdate_by_date(lastday);            
            $('#from').val(output1);
            $('#to').val(output2);
        break;

        case 'current_year':
            var date = new Date();
            var output1 = date.getFullYear()+'-01-01';
            var output2 = date.getFullYear()+'-12-31';
            $('#from').val(output1);
            $('#to').val(output2);
        break;
    }
    
});

function getdate_by_date(d){
    var month = d.getMonth()+1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' +
    ((''+month).length<2 ? '0' : '') + month + '-' +
    ((''+day).length<2 ? '0' : '') + day;
    return output;
}

$('#datetimepicker7').datetimepicker({
    format: "YYYY-MM-DD H:m:s",
    minDate:new Date(),
    ignoreReadonly:true
});
$('#datetimepicker7 input').on('input', function (event) {
    $('#datetimepicker7 input').removeClass('is-invalid');
    $('#datetimepicker7').parent().find('.fv-plugins-message-container').html('');
});

$('#coupon_form').find('[name="code"]').keypress(function(event){
    if (event.keyCode == 32) {
        return false;
    }
    var val = $(this).val();
    val = val.replace(/\s/g, '');
    $(this).val(val);
});

function initiateAddress(){
    console.log('callled');
      var autocomplete
      autocomplete = new google.maps.places.Autocomplete((document.getElementById('address')), {
        types: ['geocode']
      });
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        var address1 = place.formatted_address;
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        var latlng = new google.maps.LatLng(latitude, longitude);
        var geocoder = geocoder = new google.maps.Geocoder();

        if(place.address_components.length > 4){
            var city = place.address_components[place.address_components.length-4];
            var state = place.address_components[place.address_components.length-3];
            var country = place.address_components[place.address_components.length-2];
        }else{
            var city = place.address_components[place.address_components.length-3];
            var state = place.address_components[place.address_components.length-2];
            var country = place.address_components[place.address_components.length-1];
        }
        $('#city').val(city.long_name);
        $('#state').val(state.long_name);
        $('#country').val(country.long_name);
        if($('#lat').length){
            $('#lat').val(latitude);
        }
        if($('#lng').length){
            $('#lng').val(longitude);
        }
      });

}

function scrollTop(){
    var offset = $(".has-danger:first").offset();
    $('html, body').animate({
      scrollTop: offset.top-180
    }, 1000);
}

$(document).on("input", ".numeric", function() {
    var maxlength = $(this).attr('data-maxlength');
    var minlength = $(this).attr('data-minlength');
    maxlength = parseInt(maxlength);
    minlength = parseInt(minlength);
    this.value = parseInt(this.value);
    if( this.value > maxlength){
        $(this).val(maxlength);
        return false;
    }else if(this.value < minlength && this.value != ''){
        $(this).val(minlength);
        return false;
    }
    this.value = this.value.replace(/\D/g,'');
});

$(document).on('change', '.is-invalid',function(){
    $(this).parent().find('.fv-plugins-message-container').html('');
    $(this).removeClass('is-invalid');
});

// Approve or disapprove testimonial
$(document).on('click', '.testimonialStatus', function(){
    var id = $(this).attr('data-id');
    var testimonialStatus = $(this).attr('data-status');
    swal.fire({
        title : 'Are you sure?',
        text: "Do you want to " + testimonialStatus + " this Testimonial!!!",
        buttonsStyling: false,
        confirmButtonText: "Yes! ",
        confirmButtonClass: "btn btn-danger",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonClass: "btn btn-light-primary"
    }).then(function(result) {
        if(result.isConfirmed){
            $.ajax({
                url: HOST_URL + '/admin/testimonial/status/update',
                data: { id: id, testimonialStatus: testimonialStatus },
                success: function(result){
                     swal.fire({
                                    text: "Successfully Update Status!!",
                                    icon: 'success',
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                }).then(function() {
                                    location.reload();
                                });
                }
            });
        }
    });
});

// View Testinmonial Description

$(document).on('click', '.viewDescription', function(){
    var getId = $(this).attr('data-id');
    $.ajax({
        url: HOST_URL + '/admin/testimonial/description',
        data: { getId: getId },
        success: function(result){
             $('.viewdescriptions').html(result);
        }
    });
})
// $(document).click(function(){
//     var platforms = $('#platforms1').val();
//     if(platforms.length == '0'){
//         $('#fv-help-block1').text("fsdfsdf");
//     } else {
//     $('#fv-help-block1').text("");
//     }
// })

$(document).on('keydown, keypress', 'input[type="number"]', function(e){

    var key = e.charCode || e.keyCode || 0;
    return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57));
})
