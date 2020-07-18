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

    // Authentication - Login
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
              if (json['temppassword'] == "1") {
                location = $('.base_url').attr('href') + "/changepass";
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
  });

})(jQuery, window, document);
