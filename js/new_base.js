$(document).ready(function() {
  $("body").removeClass("preload");
});

$(window).on('scroll', function (event) {
    var scroll = $(this).scrollTop();
    if (scroll > 100) {
      $('header h1').addClass('mini');
    } else {
      $('header h1').removeClass('mini');
    }
});