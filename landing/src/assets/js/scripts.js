(function ($, window, document, undefined) {

  'use strict';

  $(function () {
    $(document).scroll(function () {
      var $nav = $('.fixed-top');
      $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
    });

    $('.nav-link, .navbar-brand').click(function () {
      var sectionTo = $(this).attr('href');
      $('html, body').animate({
        scrollTop: $(sectionTo).offset().top
      }, 700);
    });

    ScrollOut({
      /* options */
    });    
  });

})(jQuery, window, document);
