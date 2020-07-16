(function ($, window, document, undefined) {

  'use strict';

  $(function () {
    $(document).scroll(function () {
      var $nav = $(".fixed-top");
      $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
    });
  });

})(jQuery, window, document);
