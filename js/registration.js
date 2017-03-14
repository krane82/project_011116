$( document ).ready(function() {
    function adjustIframeHeight() {
        var $body   = $('body'),
            $iframe = $body.data('iframe.fv');
        if ($iframe) {
            // Adjust the height of iframe
            $iframe.height($body.height());
        }
    }

    //Init Step view
    $("#wizard").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onStepChanged: function(e, currentIndex, priorIndex) {
            // You don't need to care about it
            // It is for the specific demo
            adjustIframeHeight();
        },
        // Triggered when clicking the Previous/Next buttons
        onStepChanging: function(e, currentIndex, newIndex) {
            var fv         = $('#wizard').data('formValidation'), // FormValidation instance
                // The current step container
                $container = $('#wizard').find('section[data-step="' + currentIndex +'"]');

            // Validate the container
            fv.validateContainer($container);

            var isValidStep = fv.isValidContainer($container);
            if (isValidStep === false || isValidStep === null) {
                // Do not jump to the next step
                return false;
            }

            return true;
        },
        // Triggered when clicking the Finish button
        onFinishing: function(e, currentIndex) {
            var fv         = $('#wizard').data('formValidation'),
                $container = $('#wizard').find('section[data-step="' + currentIndex +'"]');

            // Validate the last step container
            fv.validateContainer($container);

            var isValidStep = fv.isValidContainer($container);
            if (isValidStep === false || isValidStep === null) {
                return false;
            }

            return true;
        },
        onFinished: function (event, currentIndex)
        {
            $('#new-client-form').submit();
        }
    })
    .formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        // This option will not ignore invisible fields which belong to inactive panels
        excluded: ':disabled',
        fields: {
            campaign_name: {
                validators: {
                    notEmpty: {
                        message: 'The Company Name is required'
                    },
                    stringLength: {
                        min: 6,
                        max: 100,
                        message: 'The Company Name must be more than 6 and less than 100 characters long'
                    },
                }
            },
            address: {
                validators: {
                    notEmpty: {
                        message: 'The Address is required'
                    },
                    stringLength: {
                        min: 6,
                        max: 255,
                        message: 'The Address must be more than 6 and less than 255 characters long'
                    },
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: 'The City is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 255,
                        message: 'The City must be more than 2 and less than 255 characters long'
                    },
                }
            },
            state: {
                validators: {
                    notEmpty: {
                        message: 'The State is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 255,
                        message: 'The State must be more than 2 and less than 255 characters long'
                    },
                }
            },
            abn: {
                validators: {
                    notEmpty: {
                        message: 'The ABN is required'
                    },
                    stringLength: {
                        min: 11,
                        max: 11,
                        message: 'The State must be 11 characters long'
                    },
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    },
                    different: {
                        field: 'username',
                        message: 'The password cannot be the same as username'
                    }
                }
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'The confirm password is required'
                    },
                    identical: {
                        field: 'password',
                        message: 'The confirm password must be the same as original one'
                    }
                }
            },
            name_on_card: {
                validators: {
                    notEmpty: {
                        message: 'The Name on Card is required'
                    },
                    stringLength: {
                        min: 3,
                        max: 100,
                        message: 'The Name on Card must be more than 3 and less than 100 characters long'
                    },
                }
            },
            credit_card_number: {
                validators: {
                    notEmpty: {
                        message: 'The Name on Card is required'
                    },
                    stringLength: {
                        min: 16,
                        max: 16,
                        message: 'The Credit Card Number must be 16 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The Credit Card Number can only consist of numbers'
                    }
                }
            },
            expires_mm: {
                validators: {
                    notEmpty: {
                        message: 'The Expires is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 2,
                        message: 'The Expires Number must be 2 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The Expires can only consist of numbers'
                    }
                }
            },
            expires_yy: {
                validators: {
                    notEmpty: {
                        message: 'The Expires is required'
                    },
                    stringLength: {
                        min: 2,
                        max: 2,
                        message: 'The Expires Number must be 2 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The Expires can only consist of numbers'
                    }
                }
            },
            cvc: {
                validators: {
                    notEmpty: {
                        message: 'The CVC is required'
                    },
                    stringLength: {
                        min: 3,
                        max: 3,
                        message: 'The CVC must be 3 characters long'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'The CVC can only consist of numbers'
                    }
                }
            },
        }
    });
});