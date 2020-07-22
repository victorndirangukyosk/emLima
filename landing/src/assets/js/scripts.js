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
      e.preventDefault();

      const loginButton = $('#login-button');
      const email = $('#login-email').val();
      const password = $('#login-password').val();

      if(email.length > 0 && password.length > 0) {
        loginButton.text('PLEASE WAIT');
        loginButton.toggleClass('disabled');
        $.ajax({
          url: 'index.php?path=account/login/login',
          type: 'post',
          data: { email: email, password: password },
          dataType: 'json',
          success: function (json) {
            if (json['status']) {
              if (json['temppassword'] == '1') {
                location = $('.base_url').attr('href') + '/changepass';
                console.log($('.base_url'));
              }
              else {
                location = $('.base_url').attr('href');
              }
            } else {
              iziToast.error({
                position: 'topRight',
                message: 'Incorrect email or password'
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

    $('#register-phone').keyup(function(e) {
      if (/\D/g.test(this.value)) {
        // Filter out non-digits from input value
        this.value = this.value.replace(/\D/g, '');
      }
    });

    var autocomplete;
    autocomplete = new google.maps.places.Autocomplete((document.getElementById('register-location')), {
        types: ['geocode'],
        componentRestrictions: {
          country: 'KE'
      }
    });
	
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var near_place = autocomplete.getPlace();
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

      if(formIsValid) {
        if(passwordConfirmation !== password) {
          iziToast.warning({
            position: 'topRight',
            message: 'Passwords do no match'
          });
        } else {
          if(grecaptcha.getResponse() == '') {
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
                    message: 'We couldn\'t create your account'
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

      if(otp.length > 3) {
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
  });

})(jQuery, window, document);
