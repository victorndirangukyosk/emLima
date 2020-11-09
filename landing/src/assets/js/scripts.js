(function ($, window, document, undefined) {

    'use strict';
    
    $(function () {
        // Change navbar style on scroll
        $(document).scroll(function () {
            var $nav = $('.fixed-top');
            $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
        });

        // Nav links scroll animation
        $('.nav-link, .navbar-brand').click(function () {
            var sectionTo = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(sectionTo).offset().top
            }, 700);
        });

        // Landing page scroll animations
        ScrollOut();

        // Hero section animation
        gsap.to('.anim-from-top', {
            opacity: 1,
            y: 0,
            duration: .5,
            ease: 'elastic'
        });

        gsap.to('.anim-from-bottom', {
            y: 0,
            duration: .5
        });

        // Customer Login
        $(document).delegate('#login-button', 'click', function (e) {
            getLocationOnly();
            e.preventDefault();

                    const loginButton = $('#login-button');
                    var login_latitude = $('#lat').val();
                    var login_longitude = $('#lng').val();
                    const email = $('#login-email').val();
                    const password = $('#login-password').val();
                    if (email.length > 0 && password.length > 0) {
                loginButton.text('PLEASE WAIT');
                loginButton.toggleClass('disabled');
                $.ajax({
                    url: 'index.php?path=account/login/login',
                    type: 'post',
                    data: {email: email, password: password, login_latitude:login_latitude, login_longitude:login_longitude, login_mode:'web'},
                    dataType: 'json',
                    success: function (json) {
                        if (json['status']) {
                            if (json['redirect'] != null) {
                                window.location.href = json['redirect'];
                            } else if (json['temppassword'] == '1') {
                                location = $('.base_url').attr('href') + '/changepass';
                                console.log($('.base_url'));
                            } else {
                                location = $('.base_url').attr('href');
                            }
                        } else {
                            iziToast.error({
                                position: 'topRight',
                                message: json['error_warning']
                            });
                            loginButton.text('LOGIN');
                            loginButton.toggleClass('disabled');
                        }
                    }
                });
            } else {
                iziToast.warning({
                    position: 'topRight',
                    message: 'Please enter your email and password'
                });
            }
        });

        // Customer Registration

        $('#register-phone').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                // Filter out non-digits from input value
                this.value = this.value.replace(/\D/g, '');
            }
        });

        $(document).delegate('#register-button', 'click', function (e) {
            e.preventDefault();

            const firstName = $('#register-first-name').val();
                    const lastName = $('#register-last-name').val();
                    const email = $('#register-email').val();
                    const phone = $('#register-phone').val();
                    const companyName = $('#register-company-name').val();
                    const companyAddress = $('#register-company-address').val();
                    const businessType = $('#register-business-type').val();
                    const buildingName = $('#register-building-name').val();
                    const addressLine = $('#register-address-line').val();
                    const location = $('#register-location').val();
                    const password = $('#register-password').val();
                    const passwordConfirmation = $('#register-password-confirm').val();
                    const registrationView = $('#registration-view');
                    const otpView = $('#otp-view');
                    const registerButton = $('#register-button');
                    const registerForm = $('#register-form')[0];
                    const formIsValid = registerForm.reportValidity();
                    if (formIsValid) {
                if (passwordConfirmation !== password) {
                    iziToast.warning({
                        position: 'topRight',
                        message: 'Passwords do no match'
                    });
                } else {
                    if (grecaptcha.getResponse() == '') {
                        iziToast.warning({
                            position: 'topRight',
                            message: 'Please complete captcha'
                        });
                    } else {
                        registerButton.text('PLEASE WAIT');
                        registerButton.toggleClass('disabled');

                        $.ajax({
                            url: 'index.php?path=account/register/register_send_otp',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                firstname: firstName,
                                lastname: lastName,
                                email: email,
                                telephone: phone,
                                company_name: companyName,
                                company_address: companyAddress,
                                customer_group_id: businessType,
                                house_building: buildingName,
                                address: addressLine,
                                location: location,
                                password: password,
                                confirm: passwordConfirmation
                            },
                            success: function (json) {
                                registerButton.text('SIGN UP');
                                registerButton.toggleClass('disabled');

                                if (json['status']) {
                                    iziToast.success({
                                        position: 'topRight',
                                        message: json['success_message']
                                    });

                                    registrationView.hide();
                                    otpView.show();
                                } else {
                                    iziToast.warning({
                                        position: 'topRight',
                                        title: 'Oops',
                                        message: json['error_warning']
                                    });
                                }
                            }
                        });
                    }
                }
            }
        });

        // OTP Verification
        $(document).delegate('#otp-verify-button', 'click', function (e) {
            e.preventDefault();

            const firstName = $('#register-first-name').val();
                    const lastName = $('#register-last-name').val();
                    const email = $('#register-email').val();
                    const phone = $('#register-phone').val();
                    const companyName = $('#register-company-name').val();
                    const companyAddress = $('#register-company-address').val();
                    const businessType = $('#register-business-type').val();
                    const buildingName = $('#register-building-name').val();
                    const addressLine = $('#register-address-line').val();
                    const location = $('#register-location').val();
                    const password = $('#register-password').val();
                    const passwordConfirmation = $('#register-password-confirm').val();
                    const otp = $('#otp-value').val();
                    const verifyButton = $('#otp-verify-button');
                    if (otp.length > 3) {
                verifyButton.text('PLEASE WAIT');
                verifyButton.toggleClass('disabled');

                $.ajax({
                    url: 'index.php?path=account/register/register_verify_otp',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        firstname: firstName,
                        lastname: lastName,
                        email: email,
                        telephone: phone,
                        company_name: companyName,
                        company_address: companyAddress,
                        customer_group_id: businessType,
                        house_building: buildingName,
                        address: addressLine,
                        location: location,
                        password: password,
                        confirm: passwordConfirmation,
                        signup_otp: otp
                    },
                    success: function (json) {
                        verifyButton.text('VERIFY');
                        verifyButton.toggleClass('disabled');
                        $('#otp-value').val('');

                        if (json['status']) {
                            iziToast.success({
                                timeout: false,
                                position: 'topRight',
                                message: json['success_message']
                            });

                            setTimeout(function () {
                                window.location = $('.base_url').attr('href');
                            }, 3000)
                        } else {
                            iziToast.warning({
                                position: 'topRight',
                                message: 'We couldn\'t verify your account. Please check the OTP'
                            });
                        }
                    }
                });
            } else {
                iziToast.error({
                    position: 'topRight',
                    message: 'Please enter a valid OTP'
                });
            }
        });

        $(document).delegate('#careers-submit-button', 'click', function (e) {
            e.preventDefault();
                    const firstName = $('#careers-first-name').val();
                    const lastName = $('#careers-last-name').val();
                    const role = $('#careers-designation').val();
                    const yourself = $('#careers-about-yourself').val();
                    const job_id = $('#careers-job-id').val();
                    const cover = $('#careers-cover-letter').val();
                    // const cv_path = $('#careers-cv-path').val();
                    const registerForm = $('#careers-form')[0];
                    const formIsValid = registerForm.reportValidity();
                    const registerButton = $('#careers-submit-button');
                    if (formIsValid) {
                if (grecaptcha.getResponse() == '') {
                    iziToast.warning({
                        position: 'topRight',
                        message: 'Please complete captcha'
                    });
                } else {
                    registerButton.text('PLEASE WAIT');
                    registerButton.toggleClass('disabled');
                    $.ajax({
                        url: 'index.php?path=common/home/savecareers',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            firstname: firstName,
                            lastname: lastName,
                            role: role,
                            yourself: yourself,
                            job_id: job_id,
                            cover: cover,
                            //cv_path: cv_pathk
                        },
                        success: function (json) {
                            registerButton.toggleClass('disabled');
                            if (json['status']) {
                                iziToast.success({
                                    position: 'topRight',
                                    message: json['success_message']
                                });
                                $('#careers-form')[0].reset();
                                registerButton.text('SUBMIT');

                            } else {
                                iziToast.warning({
                                    position: 'topRight',
                                    title: 'Oops',
                                    message: json['error_warning']
                                });
                            }
                        }
                    });
                }
            }
        });

        $(document).delegate('#partner-registration-button', 'click', function (e) {
            e.preventDefault();
                    const firstName = $('#partner-first-name').val();
                    const lastName = $('#partner-last-name').val();
                    const designation = $('#partner-designation').val();
                    const company = $('#partner-company-name').val();
                    const email = $('#partner-email').val();
                    const phone = $('#partner-phone').val();
                    const description = $('#partner-description').val();
                    const registerForm = $('#partner-registration-form')[0];
                    const formIsValid = registerForm.reportValidity();
                    const registerButton = $('#partner-registration-button');
                    if (formIsValid) {
                if (grecaptcha.getResponse() == '') {
                    iziToast.warning({
                        position: 'topRight',
                        message: 'Please complete captcha'
                    });
                } else {
                    registerButton.text('PLEASE WAIT');
                    registerButton.toggleClass('disabled');
                    $.ajax({
                        url: 'index.php?path=common/home/savepartner',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            firstname: firstName,
                            lastname: lastName,
                            designation: designation,
                            company: company,
                            email: email,
                            phone: phone,
                            description: description
                        },
                        success: function (json) {
                            registerButton.toggleClass('disabled');
                            if (json['status']) {
                                iziToast.success({
                                    position: 'topRight',
                                    message: json['success_message']
                                });
                                $('#partner-registration-form')[0].reset();
                            } else {
                                iziToast.warning({
                                    position: 'topRight',
                                    title: 'Oops',
                                    message: json['error_warning']
                                });
                            }
                        }
                    });
                }
            }
        });

        $('#forgot-password-btn').click(function (e) {
            e.preventDefault();

            $('#login-view').hide();
            $('#forgot-password-view').show();

        });

        $('#password-reset-button').click(function (e) {
            e.preventDefault();

            const email = $('#reset-password-email').val();
                    const resetButton = $('#password-reset-button');
                    if (email.length == 0) {
                iziToast.warning({
                    position: 'topRight',
                    message: 'Please enter your account email'
                });
            } else {
                resetButton.text('PLEASE WAIT');
                resetButton.toggleClass('disabled');

                $.ajax({
                    url: 'index.php?path=account/forgotten',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        email: email
                    },
                    success: function (json) {
                        resetButton.text('RESET PASSWORD');
                        resetButton.toggleClass('disabled');
                        $('#reset-password-email').val('');

                        if (json['status']) {
                            iziToast.success({
                                timeout: false,
                                position: 'topRight',
                                message: json['text_message']
                            });
                        } else {
                            iziToast.warning({
                                position: 'topRight',
                                message: 'We couldn\'t find an account with that email address'
                            });
                        }
                    }
                });
            }
        });

        $('#farmer-register-button').click(function (e) {
            e.preventDefault();

            const firstName = $('#farmer-first-name').val();
                    const lastName = $('#farmer-last-name').val();
                    const email = $('#farmer-email').val();
                    const phone = $('#farmer-phone').val();
                    const farmerType = $('#farmer-type').val();
                    const farmLocation = $('#farmer-location').val();
                    const produceDescription = $('farmer-produce-grown').val();
                    const registerButton = $('#farmer-register-button');
                    if (grecaptcha.getResponse() == '') {
                iziToast.warning({
                    position: 'topRight',
                    message: 'Please complete captcha'
                });
            } else {
                if ($('#farmer-registration-form')[0].reportValidity()) {
                    iziToast.success({
                        position: 'topRight',
                        message: 'Thanks for registering. We\'ll get in touch'
                    });

                    $('#farmer-registration-form')[0].reset();
                    // registerButton.text('PLEASE WAIT');
                    // registerButton.toggleClass('disabled');

                    // $.ajax({
                    //   url: 'index.php?path=account/farmerregister/register',
                    //   type: 'POST',
                    //   dataType: 'json',
                    //   data: { 
                    //     name: firstName + ' ' + lastName,
                    //     email: email,
                    //     telephone: phone
                    //   },
                    //   success: function (json) {
                    //     registerButton.text('REGISTER');
                    //     registerButton.toggleClass('disabled');

                    //     if (json['status']) {
                    //       iziToast.success({
                    //         position: 'topRight',
                    //         message: json['success_message']
                    //       });

                    //       $('#farmer-registration-form')[0].reset();

                    //     } else {
                    //       iziToast.warning({
                    //         position: 'topRight',
                    //         title: 'Oops',
                    //         message: 'We couldn\'t register you. Please try again'
                    //       });
                    //     }
                    //   }
                    // });
                }
            }
        });

        $("#careers-job-category, #careers-job-type, #careers-job-location").change(function () {

            var url = 'index.php?path=common/home/careers';


            var filter_category = $('#careers-job-category').val();

            if (filter_category && filter_category != "All Job Category") {
                url += '&filter_category=' + encodeURIComponent(filter_category);
            }

            var filter_type = $('#careers-job-type').val();

            if (filter_type && filter_type != "All Job Type") {
                url += '&filter_type=' + encodeURIComponent(filter_type);
            }

            var filter_location = $('#careers-job-location').val();

            if (filter_location && filter_location != "All Job Location") {
                url += '&filter_location=' + encodeURIComponent(filter_location);
            }
            location = url;

        });
    });
    
    
    var lat = $("#lat");
    var lng = $("#lng");

         function getLocationOnly() {
             
             if (navigator.geolocation) {    

                    console.log(1);                                

                    navigator.geolocation.getCurrentPosition(

                        showPositionOnly, 

                        // Error function

                        null,//showError, 

                        // Options. See MDN for details.

                        {

                        enableHighAccuracy: true,

                        timeout: 5000,

                        maximumAge: 0

                        });                    

                } else { 

                    console.log(2);

                   console.log("Geolocation is not supported by this browser.");

                   /*lat.innerHTML =   0;

                   lng.innerHTML = 0;*/

                }
         }
         
         function showPositionOnly(position) {
             console.log('showPositionOnly');
             console.log(position);
             $("#lat").val(position.coords.latitude);
             $("#lng").val(position.coords.longitude);
             console.log(position.coords.latitude);
             console.log(position.coords.longitude);
         }

})(jQuery, window, document);
