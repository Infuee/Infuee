/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 112);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/metronic/js/pages/custom/login/login-general.js":
/*!*******************************************************************!*\
  !*** ./resources/metronic/js/pages/custom/login/login-general.js ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

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



"use strict";

// Class Definition
var KTLogin = function() {
    var _login;

    var _showForm = function(form) {
        var cls = 'login-' + form + '-on';
        var form = 'kt_login_' + form + '_form';

        _login.removeClass('login-forgot-on');
        _login.removeClass('login-signin-on');
        _login.removeClass('login-signup-on');

        _login.addClass(cls);

        KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
    }

    var _handleSignInForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('kt_login_signin_form'),
			{
				fields: {
					username: {  
						validators: {
							notEmpty: {
								message: 'Please enter email id'
							},
                            emailAddress: {
                                message: 'The value is not a valid email address'
                            }
						}
					},
					password: {
						validators: {
							notEmpty: {
								message: 'Please enter Password'
							}
						}
					}
				},
				plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#kt_login_signin_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                  	KTUtil.scrollTop();
				    $('#kt_login_signin_form').submit();
				} 
				KTUtil.scrollTop();
		    });
        });

        // Handle forgot button
        $('#kt_login_forgot').on('click', function (e) {
            e.preventDefault();
            _showForm('forgot');
        });

        // Handle signup
        $('#kt_login_signup').on('click', function (e) {
            e.preventDefault();
            _showForm('signup');
        });
    }

    var _handleSignUpForm = function(e) {
        var validation;
        var form = KTUtil.getById('kt_login_signup_form');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			form,
			{
				fields: {
					fullname: {
						validators: {
							notEmpty: {
								message: 'Username is required'
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
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            }
                        }
                    },
                    cpassword: {
                        validators: {
                            notEmpty: {
                                message: 'The password confirmation is required'
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    agree: {
                        validators: {
                            notEmpty: {
                                message: 'You must accept the terms and conditions'
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

        $('#kt_login_signup_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                    swal.fire({
		                text: "All is cool! Now you submit this form",
		                icon: "success",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				} else {
					swal.fire({
		                text: "Sorry, looks like there are some errors detected, please try again.",
		                icon: "error",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				}
		    });
        });

        // Handle cancel button
        $('#kt_login_signup_cancel').on('click', function (e) {
            e.preventDefault();

            _showForm('signin');
        });
    }

    var _handleForgotForm = function(e) {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('kt_login_forgot_form'),
			{
				fields: {
					email: {
						validators: {
							notEmpty: {
								message: 'Please enter email id'
							},
                            emailAddress: {
								message: 'The value is not a valid email address'
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
        $('#kt_login_forgot_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                	$('#kt_login_forgot_form').submit()
				} 
                KTUtil.scrollTop();
		    });
        });

        // Handle cancel button
        $('#kt_login_forgot_cancel').on('click', function (e) {
            e.preventDefault();

            _showForm('signin');
        });
    }

    var _handleResetForm = function(e) {

    	var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var form = KTUtil.getById('kt_password_form');
        validation = FormValidation.formValidation(
			form,
			{
				fields: {
					email: {
                        validators: {
							notEmpty: {
								message: 'Please enter email id'
							},
                            emailAddress: {
								message: 'The value is not a valid email address'
							}
						}
					},
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter password'
                            }
                        }
                    },
                    cpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter confirm password'
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
		);

        // Handle submit button
        $('#kt_password_submit').on('click', function (e) {
            e.preventDefault();

            if($('#kt_password_form [name="password"]').val() !== $('#kt_password_form [name="cpassword"]').val()){
            	swal.fire({
	                text: "The password and confirm password are not the same",
	                icon: "error",
	                buttonsStyling: false,
	                confirmButtonText: "Ok",
                    customClass: {
						confirmButton: "btn font-weight-bold btn-light-primary"
					}
	            }).then(function() {
					KTUtil.scrollTop();
				});
				return false;
            }

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                	$('#kt_password_form').submit()
				}
                KTUtil.scrollTop();
		    });
        });
    }

    var _handleLoginForm = function(e) {

        $('#user_login_page').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password : {
                    required: true,
                }
            },
            messages: {
                email: {
                    required: "Please enter email id",
                    email: "Please enter a valid email id"
                },
                password : {
                    required : "Please enter Password",
                }
            }
        });
        $('#user_login_submit').on('click', function (e) {
            e.preventDefault();
            if ($('#user_login_page').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#user_login_page').submit()
            }
        });
    }


    var _handleForgotPassForm = function(e) {

        $('#forgot-password-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "Email address is required",
                    email: "The value is not a valid email address"
                }
            }
        });

        $('#forgot-password-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#forgot-password-form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#forgot-password-form').submit()
            }
        });
    }

if(typeof $.validator != 'undefined'){
    $.validator.addMethod("passwordCheck", function (value, element) {
        return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(value);
    });
}   

    var _handleUserResetForm = function(e) {

        $('#kt_user__password_form').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password:{
                    required: true,
                    minlength: 5,
                    passwordCheck:true
                },
                cpassword: {
                    required: true,
                }
            },
            messages: {
                email: {
                    required: "Email address is required",
                    email: "The value is not a valid email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long",
                    passwordCheck: "Password must be a minumum of 8 characters and contain a digit, upper-case, lower-case and non-alphanumeric character e.g. !@# with no leading or trailing spaces."
                },
            }
        });

        $('#user-forgot-password-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#kt_user__password_form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#kt_user__password_form').submit()
            }
        });
    }

    var _handleUserRegisterForm = function(e) {

        $('#user-registration-from').validate({
            rules: {
                first_name:{
                    required: true,
                },
                // last_name:{
                //     required: true,  
                // },
                // month:{
                //     required: true,    
                // },
                // day :{
                //     required: true,  
                // },
                dob:{
                    required: true,  
                },
                email: {
                    required: true,
                    email: true
                },
                cemail:{
                    required: true,
                    equalTo: "#email"
                },
                country_code :{
                    required: true,
                },
                address :{
                    required: true,  
                },
                phone:{
                    required: true,    
                },
                city:{
                    required: true,  
                },
                password:{
                    required: true,
                    passwordCheck:true
                },
                cpassword:{
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                first_name:{
                    required: "Please enter name",
                },
                // last_name:{
                //     required: "Please enter last name",
                // },
                dob:{
                    required: 'Please select your date of birth',
                },
                email: {
                    required: "Email address is required",
                    email: "The value is not a valid email address"
                },
                cemail:{
                    required: "Confirm email address is required",
                    email: "The value is not a valid email address",
                    equalTo: "Please enter same email and confirm email"
                },
                country_code:{
                    required: 'Please select your country code',
                },
                address:{
                    required: 'Please enter your address',
                },
                phone:{
                    required: 'Please enter your phone',
                },
                city:{
                    required: 'Please enter your city',
                },
                password: {
                    required: "Please enter password",
                    minlength: "Your password must be at least 8 characters long",
                    passwordCheck: "Password must be a minumum of 8 characters and contain a digit, upper-case, lower-case and non-alphanumeric character e.g. !@# with no leading or trailing spaces."
                },
                cpassword:{
                    required: "Please enter password",
                    equalTo: "Please enter same password and confirm password"
                }
            },    
            errorPlacement: function (error, element) {
                error.addClass('mt-2 text-danger');
                if(element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2'));
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                }
                else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                    error.insertAfter(element.parent().parent());
                }
                else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
        });

        $('#user-registration-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#user-registration-from').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#user-registration-from').submit()
            }
        });
    }

    var _handleUserProfileForm = function(e) {
        $('#manage_profile_form').validate({
            rules: {
                first_name:{
                    required: true,

                },
                last_name:{
                    required: true,  
                },
                date_of_bith:{
                    required: true,    
                },
                country_code :{
                    required: true,
                },
                category:{
                    required: true,
                },
                address :{
                    required: true,  
                },
                phone:{
                    required: true,    
                },
                city:{
                    required: true,  
                },
                facebook:{
                    url:true
                },
                twitter:{
                    url:true
                },
                pinterest:{
                    url:true
                }
            },
            messages: {
                first_name:{
                    required: "Please enter first name",
                },
                last_name:{
                    required: "Please enter last name",
                },
                date_of_bith:{
                    required: 'Please select your date of birth'
                },
                country_code:{
                    required: 'Please select your country code',
                },
                category:{
                    required: 'Please select your category',
                },
                address:{
                    required: 'Please enter your address',
                },
                phone:{
                    required: 'Please enter your phone',
                },
                city:{
                    required: 'Please enter your city',
                },
                facebook:{
                    required: 'Please enter valid url.',
                },
                twitter:{
                    required: 'Please enter valid url.',
                },
                pinterest:{
                    required: 'Please enter valid url.',
                },
            },    
            errorPlacement: function (error, element) {
                console.log('element', element);
                if(element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2'));
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                }
                else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                    error.insertAfter(element.parent().parent());
                }
                else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
        });

        $('#manage_profile_submit').on('click', function (e) {
            e.preventDefault();
            if ($('#manage_profile_form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#manage_profile_form').submit()
            }
        });
    }

    var _handleContactUsForm = function(e) {

        $('#contact-us-form').validate({
            rules: {
                full_name:{
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                desc:{
                    required: true,
                }
            },
            messages: {
                full_name:{
                    required: "Please enter your name."
                },
                email: {
                    required: "Email address is required",
                    email: "The value is not a valid email address"
                },
                desc:{
                    required:"Please enter description."
                }
            }
        });

        $('#contact-us-form-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#contact-us-form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#contact-us-form').submit()
            }
        });
    }

    var _handleBeInfluencerForm = function(e) {

        $('#be-influencer-form').validate({
            rules: {
                i_username:{
                    required: true,
                },
                category: {
                    required: true,
                },
                desc:{
                    required: true,
                }
            },
            messages: {
                i_username:{
                    required: "Please enter your instagram username."
                },
                category: {
                    required: "Please select category.",
                },
            },    
            errorPlacement: function (error, element) {
                console.log('element', element);
                if(element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2'));
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                }
                else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                    error.insertAfter(element.parent().parent());
                }
                else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
        });

        $('#be-influencer-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#be-influencer-form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#be-influencer-form').submit()
            }
        });
    }



    var _handlePlanForm = function(e) {

        $('#plan-setting-form').validate({
            rules: {
                price:{
                    required: true,
                    digits:true
                },
            },
            messages: {
                price:{
                    required: "Please enter price.",
                    digits:"Only numeric values are allowed."
                },
            }
        });

        var inputs = $('.influencer-plans');
        inputs.filter('input').each(function() {
            $(this).rules("add", {
                required: true,
                number:true,
                messages: {
                    required: "Please enter price.",
                    number:"Only numeric values are allowed."
                }
            });
        });

        $('#plan-setting-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#plan-setting-form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#plan-setting-form').submit()
            }
        });

    }

  var _handleChangePasswordForm = function(e) {

        $('#change-password-form').validate({
            rules: {
                current_password:{
                    required: true,
                },
                new_password: {
                    required: true,
                    passwordCheck:true
                },
                confirm_password:{
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                current_password:{
                    required: "Please enter password"
                },
                new_password: {
                    required: 'Please enter new password.',
                    passwordCheck: "Password must be a minumum of 8 characters and contain a digit, upper-case, lower-case and non-alphanumeric character e.g. !@# with no leading or trailing spaces."
                },
                confirm_password: {
                    required: 'Please enter confirm password.',
                    equalTo: "New password and Confirm password doesn't match"
                },
            }
        });

        $('#change-password-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#change-password-form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#change-password-form').submit()
            }
        });
    }
    
    var _handle2FactorForm = function(e) {
        
        $('#enable-two-factor-form').validate({
            rules: {
                country_code: {
                    required: true,
                },
                phone:{
                    required: true,
                },
                password: {
                    required: true,
                }
            },
            messages: {
                country_code: {
                    required: "Please select your country code",
                },
                phone: {
                    required: "Please enter your phone number.",
                },
                password: {
                    required: "Please enter password",
                },
            },    
            errorPlacement: function (error, element) {
                console.log('element', element);
                if(element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2'));
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                }
                else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                    error.insertAfter(element.parent().parent());
                }
                else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
        });

        $(document).on('keypress', '#password', function (){
            $('#password-error-message').hide();
        });

        // Handle submit button
        $(document).on('click', '#generate_OTP', function (e) {
            e.preventDefault();
            var this_ = this;
            $('#password-error-message').hide();
            if($('#enable-two-factor-form').valid()){
                $('.loader-wrapper').toggleClass('d-flex');
                var url = HOST_URL+'/requestOTP'
                $.ajax({
                    url : url,
                    type: "POST",
                    data : { _token : $('meta[name="csrf-token"]').attr('content'), country_code : $('#country_code').val(), phone : $('#phone').val(), password : $('#password').val() },
                    success: function(data, textStatus, jqXHR)
                    {
                        $('.loader-wrapper').toggleClass('d-flex');
                        if(data.success){
                            $(this_).parent().hide();
                            $('#enable-two-factor-form .otp-container').show();
                            $('#otp-success-message p').text(data.message);
                            $('#otp-success-message').show();
                            $('#otp-error-message').hide();

                            setTimeout(function(){
                                $('#otp-success-message').hide();
                            },5000)

                        }else{
                            $('#password-error-message p').text(data.message);
                            $('#password-error-message').show();
                            $('#otp-success-message').hide();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                 
                    }
                });
            }
        });

        $(document).on('click', '#confirm_OTP', function (e) {
            e.preventDefault();
            var this_ = this;
            
            var otp = $('#otp').val();
            if(otp == ''){
                $('#otp-error-message p').text('Please enter OTP');
                $('#otp-error-message').show();
                return
            }
            $('#otp-error-message').hide();

            $('.loader-wrapper').toggleClass('d-flex');
            var url = HOST_URL+'/confirmOTP'
            $.ajax({
                url : url,
                type: "POST",
                data : { _token : $('meta[name="csrf-token"]').attr('content'), otp : otp},
                success: function(data, textStatus, jqXHR)
                {
                    $('.loader-wrapper').toggleClass('d-flex');
                    if(data.success){
                        $(this_).parent().hide();
                        $('#enable-two-factor-form .close-container').show();
                        $('#otp-confirm-success-message p').text(data.message);
                        $('#otp-confirm-success-message').show();
                        setTimeout(function(){
                            $('#otp-confirm-success-message').hide();
                        },5000)
                        $('#otp-confirm-error-message').hide();
                        $('#enable_modal').modal('toggle');
                        swal.fire({
                            text: data.message,
                            icon: 'success',
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        }).then(function() {
                            window.location.reload();
                        });
                    }else{
                        $('#otp-confirm-error-message p').text(data.message);
                        $('#otp-confirm-error-message').show();
                        setTimeout(function(){
                            $('#otp-confirm-error-message').hide();
                        },5000)
                        $('#otp-confirm-success-message').hide();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
             
                }
            });
                
        });
        $('#otp').on('keypress', function(){
            $(this).parent().find('.fv-plugins-message-container').html('');
            $('#otp-error-message').hide();
            $('#otp-confirm-error-message').hide();
        })
        
    }
    var _handleLoginOtpForm = function(e) {

        $('#user_login_otp_form').validate({
            rules: {
                otp: {
                    required: true,
                }
            },
            messages: {
                otp: {
                    required: "Please enter OTP.",
                }
            }
        });

        // Handle submit button
        $('#user_login_otp_submit').on('click', function (e) {
            e.preventDefault();
            if ($('#user_login_otp_form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#user_login_otp_form').submit()
            }
        });

        $('#otp').on('keypress', function(){
            $(this).parent().find('.fv-plugins-message-container').html('');
            $('#otp-error-message').hide();
        })
    }

    var _handleBankDetailForm = function(){
        
        $('#manage_bank_details_form').validate({
            rules: {
                account_holder:{
                    required: true,
                },
                account_number: {
                    required: true,
                    maxlength:32,
                    minlength:9
                },
                personal_id_number:{
                    required: true,
                },
                sortCode:{
                    required: true,
                    maxlength:32,
                    minlength:9
                },
                documentFront:{
                    required: true,
                },
                documentBack:{
                    required: true,
                },
            },
            messages: {
                account_holder:{
                    required: "Please enter account holder name",
                },
                account_number: {
                    required: "Please enter account number",
                    maxlength: "Please enter bank account number between 9 to 32 characters.",
                    minlength: "Please enter bank account number between 9 to 32 characters."
                },
                personal_id_number:{
                    required: "Please enter ID number",
                },
                sortCode:{
                    required: "Please enter short code [IFSC code]",
                    maxlength: "Please enter short code [IFSC code] between 9 to 32 characters.",
                    minlength: "Please enter short code [IFSC code] between 9 to 32 characters."
                },
                documentFront:{
                    required: "Please upload front side of your id",
                },
                documentBack:{
                    required: "Please upload front back of your id",
                },
            },
            submitHandler: function (form) { 
                //$('.loader-wrapper').toggleClass('d-flex');
                form.submit();
            }
        });

        $('#manage_bank_details_submit').on('click', function (e) {
            e.preventDefault();
            if ($('#manage_bank_details_form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#manage_bank_details_form').submit()
            }
        });
    }
    
    var handleOrderPlaceForm = function(){
        ccnum  = document.getElementById('ccnum');
          type   = document.getElementById('ccnum-type')
          expiry = document.getElementById('expiry');
          cvc    = document.getElementById('cvc');
          submit = document.getElementById('place_order_submit');
          result = document.getElementById('result');
          payform.cardNumberInput(ccnum);
          payform.expiryInput(expiry);
          payform.cvcInput(cvc);
          ccnum.addEventListener('input',   updateType);

        $.validator.addMethod("card", function (value, element) {
            var cardType = payform.validateCardNumber(element.value);
            return $('.radio-card:checked').length ? true : cardType &&  element.value.length >= 19;
        });

        $.validator.addMethod("expiry", function (value, element) {
            var expiry = payform.validateCardExpiry(element.value);
            return $('.radio-card:checked').length ? true : expiry;
        });
        $.validator.addMethod("cvv", function (value, element) {
            var cvv = payform.validateCardCVC(element.value);
            return $('.radio-card:checked').length ? true : cvv;
        });


        $('#place_order_form').validate({
            rules: {
                description:{
                    required: true,
                },
                first_name: {
                    required: true,
                },
                last_name:{
                    required: true,
                },
                address:{
                    required: true,
                },
                ccnum:{
                    required: function (){
                        return $('.radio-card:checked').length ? false : true;
                    },
                    card:true,
                },
                expiry:{
                    required: function (){
                        return $('.radio-card:checked').length ? false : true;
                    },
                    expiry:true,
                },
                cvc:{
                    required: function (){
                        return $('.radio-card:checked').length ? false : true;
                    },
                    cvv:true,
                },
                tnc:{
                    required: true,  
                }
            },
            messages: {
                description:{
                    required: "Please enter description",
                },
                first_name: {
                    required: "Please enter first name",
                },
                last_name:{
                    required: "Please enter last name",
                },
                address:{
                    required: "Please enter address",
                },
                ccnum:{
                    required: "Please enter card numbers",
                    card:"Please enter a valid card number."
                },
                expiry:{
                    required: "Please enter expiry details",
                    expiry: "Please enter valid card expiry"
                },
                cvc:{
                    required: "Please enter CVV",
                    cvv: "Please enter valid CVV."
                },
                tnc:{
                    required: "Please accept terms of service agreement.",  
                }

            }
        });

        $('#place_order_submit').on('click', function (e) {
            e.preventDefault();
            if ($('#place_order_form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#place_order_form').submit()
            }
        });

        $('.radio-card').on('click', function(){
            $('#selected_card').val($(this).val());
            $('#place_order_form').valid();
        });

          console.log('clickmkemke6');
          function updateType(e) {
            var cardType = payform.parseCardType(e.target.value);
            // type.innerHTML = cardType || 'invalid';
          }


          function fieldStatus(input, valid) {
            if (valid) {
              removeClass(input.parentNode, 'error');
            } else {
              addClass(input.parentNode, 'error');
            }
            return valid;
          }

          function addClass(ele, _class) {
            if (ele.className.indexOf(_class) === -1) {
              ele.className += ' ' + _class;
            }
          }

          function removeClass(ele, _class) {
            if (ele.className.indexOf(_class) !== -1) {
              ele.className = ele.className.replace(_class, '');
            }
          }
    }
    var handleCustomPlanSettingForm = function(){
        $('#custom_plan_setting').validate({
            rules: {
                plan_name:{
                    required: true,
                },
                description:{
                    required: true,
                }
            },
            messages: {
                plan_name:{
                    required: "Please enter plan name",
                },
                description:{
                    required: "Please enter description",
                }
            }
        });

        $('#custom_plan_setting_submit').on('click', function (e) {
            e.preventDefault();
            if ($('#custom_plan_setting').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#custom_plan_setting').submit()
            }
        });
    }

    var handleCreateCampaignForm = function(){

        $('#create-campaign').validate({
            ignore:[],
            rules: {
                title:{
                    required: true,
                },
                description:{
                    required: function() 
                                {
                                    CKEDITOR.instances.forlabel2.updateElement();
                                },
                },
                location:{
                    required: true,
                },
                website:{
                    url: true
                },
                image:{
                    required: true,
                }
            },
            messages: {
                title:{
                    required: "Please enter title of campaign",
                },
                description:{
                    required: "Please enter description",
                },
                location:{
                    required: 'Please select a location'
                },
                website:{
                    url: "Please enter a valid website URL",
                },
                image:{
                    required: "Please upload a campaign logo",
                },
            },
            errorPlacement: function(error, element) {
                error.addClass('mt-2 text-danger');
                if ($(element).hasClass('selectpicker')) {
                    error.appendTo(element.parent());
                } else if ($(element).hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }else if (element.attr("type") == "textarea") {
                    error.appendTo(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });

        $('#create-campaign-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#create-campaign').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#create-campaign').submit()
            }
        });

    }


    var handleEditCampaignForm = function(){

        $('#edit-campaign').validate({
            ignore:[],
            rules: {
                title:{
                    required: true,
                },
                description:{
                    required: function() 
                                {
                                    CKEDITOR.instances.forlabel2.updateElement();
                                },
                },
                location:{
                    required: true,
                },
                website:{
                    url: true
                },
                image:{
                    required: {
                        depends: function(element) {
                            return $("#check_upload_orig").is(":blank");
                        }
                    },
                }
            },
            messages: {
                title:{
                    required: "Please enter title of campaign",
                },
                description:{
                    required: "Please enter description",
                },
                location:{
                    required: 'Please select a location'
                },
                website:{
                    url: "Please enter a valid website URL",
                },
                image:{
                    required: "Please upload a campaign logo",
                },
            },
            errorPlacement: function(error, element) {
                error.addClass('mt-2 text-danger');
                if ($(element).hasClass('selectpicker')) {
                    error.appendTo(element.parent());
                } else if ($(element).hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }else if (element.attr("type") == "textarea") {
                    error.appendTo(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });

        $('#create-campaign-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#edit-campaign').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#edit-campaign').submit()
            }
        });

    }
   var handlePostUrlForm = function(){

        $('#job-submit-post-url').validate({
            rules: {
                url:{
                    url: true
                },
            //"url[3]": { required: "#facebook_url:blank" && "#instagram_url:blank"},
            // "url[2]": { required: "#instagram_url:blank" }

            },
            messages: {
                "url[1]":{
                    url: "Please enter a valid URL for instagram",
                },
                "url[2]":{
                    url: "Please enter a valid URL for facebook",
                },
                "url[3]":{
                    url: "Please enter a valid URL for youtube",
                },
                "url[4]":{
                    url: "Please enter a valid URL for tiktok",
                },
                "url[5]":{
                    url: "Please enter a valid URL for twitter",
                },
                


            },
            errorPlacement: function(error, element) {
                error.addClass('mt-2 text-danger');
                if ($(element).hasClass('selectpicker')) {
                    error.appendTo(element.parent());
                } else if ($(element).hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });

        $('#job-post-url-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#job-submit-post-url').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#job-submit-post-url').submit()
            }
        });

    
      $( "#job-submit-post-url" ).submit(function( event ) {
        
         //event.preventDefault();
         var Input=false;
          $('.mygroup').each(function () {
           if($(this).val()  !== ""){
            Input=true;
        
           }
          }); 
          console.log(Input);
          if(!Input){
            $('#post_url_error').after('<p style="color:red; text-align:center;">Please enter at least one post url.</p>');
            $('.loader-wrapper').toggleClass('d-flex');
            return false;
           }
           return true;
      }); 


    }
    var handleCreateCampaignJobForm = function(){

        $('#create-campaign-job').validate({
            ignore: [], 
            rules: {
                title:{
                    required: true,
                },
                description:{
                    required: function() 
                                {
                                    CKEDITOR.instances.forlabel2.updateElement();
                                },
                },
                caption:{
                    required: function() 
                                {
                                    CKEDITOR.instances.caption.updateElement();
                                },
                },
                "platforms[]":{
                    required: true,
                },
                category:{
                    required: true
                },
                image:{
                    required: true,
                },
                influencers:{
                    required: true,
                },
                promo_days: {
                    required: function(element) {
                        return $("#promo_days").length >= 0;
                    }    
                },
                price :{
                    required: true,  
                },
                minutes: {
                    required: function(element) {
                        return $("#seconds").val() == '';
                    }
                },
                seconds: {
                    required: function(element) {
                        return $("#minutes").val() == '';
                    }
                }
            },
            messages: {
                title:{
                    required: "Please enter job title",
                },
                description:{
                    required: "Please enter description",
                },
                caption:{
                    required: "Please enter caption",
                },
                "platforms[]":{
                    required: 'Please select social platforms'
                },
                category:{
                    required: "Please enter a category",
                },
                image:{
                    required: "Please upload job cover image",
                },
                influencers:{
                    required: "Please enter number of influencers you want to hire",  
                },
                promo_days: {
                    required: "Please enter number of days",  
                },
                price :{
                    required: "Please enter price", 
                },
                minutes :{
                    required : "Please select duration of job"
                },
                seconds :{
                    required : "Please select duration of job"
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('mt-2 text-danger');
                if ($(element).hasClass('selectpicker')) {
                    error.appendTo(element.parent());
                } else if ($(element).hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }else if (element.attr("type") == "textarea") {
                    error.appendTo(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });

        $('#create-campaign-job-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#create-campaign-job').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#create-campaign-job').submit()
            }
        });

        $('#minutes, #seconds').on('change', function(){
            if($(this).val() !== ''){
                $('label[for="seconds"]').remove();
                $('label[for="minutes"]').remove();
            }
        })


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

    var handleEditCampaignJobForm = function(){

        $('#edit-campaign-job').validate({
            rules: {
                title:{
                    required: true,
                },
                description:{
                    required: function() 
                                {
                                    CKEDITOR.instances.forlabel2.updateElement();
                                },
                },
                caption:{
                    required: function() 
                                {
                                    CKEDITOR.instances.caption.updateElement();
                                },
                },
                "platforms[]":{
                    required: true,
                },
                category:{
                    required: true
                },
                image:{
                    required: {
                        depends: function(element) {
                            return $("#check_upload_orig").is(":blank");
                        }
                    },
                },
                influencers:{
                    required: true,  
                },
                promo_days: {
                    required: function(element) {
                        return $("#promo_days").length >= 0;
                    }
                },
                price :{
                    required: true,  
                },
                minutes: {
                    required: function(element) {
                        if($("#seconds").val() == 0 && $("#minutes").val() == 0){
                            $("#seconds").val('');
                        }
                        return $("#seconds").is(':empty') || $("#seconds").val() == 0;
                    }
                },
                seconds: {
                    required: function(element) {
                        if($("#seconds").val() == 0 && $("#minutes").val() == 0){
                            $("#minutes").val('');
                        }
                        return $("#minutes").is(':empty')  || $("#minutes").val() == 0;
                    }
                }
            },
            messages: {
                title:{
                    required: "Please enter title of campaign",
                },
                description:{
                    required: "Please enter description",
                },
                caption:{
                    required: "Please enter caption",
                },
                "platforms[]":{
                    required: 'Please select social platforms'
                },
                category:{
                    required: "Please enter a category",
                },
                image:{
                    required: "Please upload a campaign logo",
                },
                influencers:{
                    required: "Please enter number of influencers you want to hire",  
                },
                promo_days: {
                    required: "Please enter number of days",  
                },
                price :{
                    required: "Please enter price", 
                },
                minutes :{
                    required : "Please select a duration of job"
                },
                seconds :{
                    required : "Please select a duration of job"
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('mt-2 text-danger');
                if ($(element).hasClass('selectpicker')) {
                    error.appendTo(element.parent());
                } else if ($(element).hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }else if (element.attr("type") == "textarea") {
                    error.appendTo(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });

        $('#create-campaign-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#edit-campaign-job').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#edit-campaign-job').submit()
            }
        });

    }

    var handleRatingsForm = function(){
        $('#ratings_form').validate({
            rules: {
                review:{
                    required: true,
                },
                start_rating:{
                    required: true,
                }
            },
            messages: {
                review:{
                    required: "Please add review",
                },
                start_rating:{
                    required: "Please add ratings",
                }
            }
        });

        $('#ratings_form_submit').on('click', function (e) {
            e.preventDefault();
            if ($('#ratings_form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#ratings_form').submit()
            }
        });
    }

    var handleVerifyForm = function(){
        $('#verify-form').validate({
            rules: {
                platform:{
                    required: true,
                },
                category:{
                    required: true,
                }
            },
            messages: {
                platform:{
                    required: "Please select atleast a social platform",
                },
                category:{
                    required: "Please select your category",
                }
            }
        });

        $('#verify-form-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#verify-form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#verify-form').submit()
            }
        });
    }

    var handleProposalFrom = function(){
        $('#job-submit-proposal').validate({
            rules: {
                cost:{
                    required: true,
                },
                cover_latter:{
                    required: true,
                }
            },
            messages: {
                cost:{
                    required: "Please enter your cost",
                },
                cover_latter:{
                    required: "Please select cover latter",
                }
            }
        });

        $('#job-submit-proposal-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#job-submit-proposal').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#job-submit-proposal').submit()
            }
        });
    }


    var handleFundWalletForm = function(){

        ccnum  = document.getElementById('ccnum');
          type   = document.getElementById('ccnum-type')
          cvc    = document.getElementById('cvc');
          submit = document.getElementById('place_order_submit');
          result = document.getElementById('result');
          payform.cardNumberInput(ccnum);
          payform.cvcInput(cvc);
          ccnum.addEventListener('input',   updateType);

        $.validator.addMethod("card", function (value, element) {
            var cardType = payform.validateCardNumber(element.value);
            return $('.radio-card:checked').length ? true : cardType &&  element.value.length >= 19;
        });

        $.validator.addMethod("cvv", function (value, element) {
            var cvv = payform.validateCardCVC(element.value);
            return $('.radio-card:checked').length ? true : cvv;
        });


        $('#fund-wallet-form').validate({
            // ignore:[],
            rules: {
                amount:{
                    required: true,
                },
                card_holder_name: {
                    required: true,
                },
                ccnum:{
                    required: function (){
                        return $('.new-card-radio:checked').length ? false : true;
                    },
                    card:true,
                },
                expiry_month:{
                    required: true
                    // function (){
                    //     return $('.new-card-radio:checked').length ? false : true;
                    // },
                },
                expiry_year:{
                    required: true
                    // function (){
                    //     return $('.new-card-radio:checked').length ? false : true;
                    // },
                },
                cvc:{
                    required: function (){
                        return $('.new-card-radio:checked').length ? false : true;
                    },
                    cvv:true,
                }
            },
            messages: {
                amount:{
                    required: "Please enter amount you want to load",
                },
                card_holder_name: {
                    required: "Please enter card holder name",
                },
                ccnum:{
                    required: "Please enter card numbers",
                    card:"Please enter a valid card number."
                },
                expiry_month:{
                    required: "Please select expiry month",
                },
                expiry_year:{
                    required: "Please select expiry year",
                },
                cvc:{
                    required: "Please enter CVV",
                    cvv: "Please enter valid CVV."
                }
            },
            errorPlacement: function (error, element) {
                error.addClass('mt-2 text-danger');
                if(element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2'));
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                }
                else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                    error.insertAfter(element.parent().parent());
                }
                else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
        });

        $('#fund-wallet-form-submit').on('click', function (e) {
            e.preventDefault();
            if ($('#fund-wallet-form').valid()) {
                $('.loader-wrapper').toggleClass('d-flex');
                $('#fund-wallet-form').submit()
            }
        });

        
          function updateType(e) {
            var cardType = payform.parseCardType(e.target.value);
            // type.innerHTML = cardType || 'invalid';
          }


          function fieldStatus(input, valid) {
            if (valid) {
              removeClass(input.parentNode, 'error');
            } else {
              addClass(input.parentNode, 'error');
            }
            return valid;
          }

          function addClass(ele, _class) {
            if (ele.className.indexOf(_class) === -1) {
              ele.className += ' ' + _class;
            }
          }

          function removeClass(ele, _class) {
            if (ele.className.indexOf(_class) !== -1) {
              ele.className = ele.className.replace(_class, '');
            }
          }
        
    }
    

    // Public Functions
    return {
        // public functions
        init: function() {
            _login = $('#kt_login');

            $('.view_password').on('click', function(){
                /*var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }*/
                var x = $(this).siblings('input');
                if (x.attr('type') == "password") {
                    $(this).find('.fa').attr('class','fa fa-eye-slash');
                    x.attr('type','text');
                } else {
                    $(this).find('.fa').attr('class','fa fa-eye');
                    x.attr('type','password');
                }
            });




	        if($('#kt_login_signin_form').length){
	            _handleSignInForm();
	            _handleSignUpForm();
	            _handleForgotForm();
	        }
	        if($('#kt_password_form').length){
	        	_handleResetForm();
	        }

            if($('#user_login_page').length){
                _handleLoginForm();  //Done 
            }

            if($('#forgot-password-form').length){
                _handleForgotPassForm();  //Done 
            }

            if($('#kt_user__password_form').length){
                _handleUserResetForm();   //Done 
            }

            if($('#user-registration-from').length){
                _handleUserRegisterForm();  //Done    
            }

            if($('#manage_profile_form').length){
                _handleUserProfileForm();  //Done        
            }

            if($('#contact-us-form').length){
                _handleContactUsForm();  //Done      
            }

            if($('#be-influencer-form').length){
                _handleBeInfluencerForm(); //Done     
            }

            if($('#plan-setting-form').length){
                _handlePlanForm(); //Done 
            }

            if($('#change-password-form').length){
                _handleChangePasswordForm(); //Done 
            }

            if($('#enable-two-factor-form').length){
                _handle2FactorForm(); //Done
            }

            if($('#user_login_otp_form').length){
                _handleLoginOtpForm(); //Done
            }

            if($('#manage_bank_details_form').length){
                _handleBankDetailForm(); //Done
            }

            if($('#place_order_form').length){
                handleOrderPlaceForm();
            }

            if($('#custom_plan_setting').length){
                handleCustomPlanSettingForm();
            }


            if($('#create-campaign').length){
                handleCreateCampaignForm();
            }

            if($('#edit-campaign').length){
                handleEditCampaignForm();
            }

            if($('#create-campaign-job').length){
                handleCreateCampaignJobForm();
            }

            if($('#edit-campaign-job').length){
                handleEditCampaignJobForm();
            }

            if( $('#verify-form').length ){
                handleVerifyForm();
            }

            if( $('#fund-wallet-form').length ){
                handleFundWalletForm();
            }

            if( $('#job-submit-proposal').length ){
                handleProposalFrom();
            }
            if( $('#job-post-url-submit').length ){
                handlePostUrlForm();
            }


            /*if($('#ratings_form').length){
                handleRatingsForm();
            }*/

            $('select').on('change', function(){
                $(this).closest('.has-danger').find('.fv-plugins-message-container .fv-help-block').hide();
            });

            var year = (new Date).getFullYear();
            var past_year = parseInt(year) - 13;
            $("#datepicker").datepicker({ 
                autoclose: true, 
                todayHighlight: true,
                startDate: "01/01/1900",
                endDate: "31/12/"+past_year,
            });


            $('#disable2fa').click(function(){
                swal.fire({
                    title : 'Are you sure?',
                    text: "Do you want to disable Two-Factor Authentication ?",
                    type: "danger",
                    buttonsStyling: false,
                    confirmButtonText: "Yes! Disable",
                    confirmButtonClass: "btn btn-danger",
                    showCancelButton: true,
                    cancelButtonText: "Cancel",
                    cancelButtonClass: "btn btn-light-primary"
                }).then(function(result) {
                    if(result.isConfirmed){
                        $.ajax({
                            url: HOST_URL+'/disable2fa', 
                            success: function(result){
                                swal.fire({
                                    text: result.message,
                                    icon: typeof result.success != 'undefined' ? 'success' : 'error',
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            })

            $('#user_login_resent_submit').on('click', function(){
                $('.loader-wrapper').toggleClass('d-flex');
                $('#user_login_otp_form').submit();
            });


            $('.remove_cart').click(function(){
                var id = $(this).data('planid');
                swal.fire({
                    title : 'Are you sure?',
                    text: "Do you want to remove this plan from cart ?",
                    type: "danger",
                    buttonsStyling: false,
                    confirmButtonText: "Yes! Remove",
                    confirmButtonClass: "btn btn-danger",
                    showCancelButton: true,
                    cancelButtonText: "Cancel",
                    cancelButtonClass: "btn btn-light-primary"
                }).then(function(result) {
                    if(result.isConfirmed){
                        $.ajax({
                            url: HOST_URL+'/removefromcart/'+id, 
                            success: function(result){
                                swal.fire({
                                    text: result.message,
                                    icon: typeof result.success != 'undefined' ? 'success' : 'error',
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            })


        }
    };
}();

function scrollTop(){
    var offset = $(document).find(".has-danger:first").offset();
    // console.log($(document).find(".has-danger:first").html());
    $('html, body').animate({
      scrollTop: offset.top-180
    }, 1000);
}

// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();

    $('#kt_login_signin_form,#kt_login_forgot_form').on("submit", function(){
        $('.loader-wrapper').toggleClass('d-flex');
    });
    setTimeout(function(){
        $('.flash-message').hide();
    }, 3000)
});

/***/ }),

/***/ 112:
/*!*************************************************************************!*\
  !*** multi ./resources/metronic/js/pages/custom/login/login-general.js ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp64\www\keenthemes\themes\metronic\theme\html_laravel\demo1\skeleton\resources\metronic\js\pages\custom\login\login-general.js */"./resources/metronic/js/pages/custom/login/login-general.js");


/***/ })

/******/ });

function alphaOnly(event) {
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8 || key == 32);
};