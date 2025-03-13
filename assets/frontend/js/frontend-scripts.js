jQuery(document).ready(function ($) {
  $(".wp-slideshow-container").slick({
    autoplay: true,
    autoplaySpeed: parseInt($(".wp-slideshow-container").data("autoplay")),
    fade: $(".wp-slideshow-container").data("transition") === "fade",
    dots: true,
    arrows: true,
    prevArrow:
      '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
    nextArrow:
      '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
  });
});
