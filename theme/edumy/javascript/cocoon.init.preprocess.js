/* Slider Style 5 */
$(window).on('load', function() {
  if ($(".block_cocoon_slider_5").length) {
    var bsCarouselItems = 1;
    if ($(".bs_carousel .carousel-item").length) {
      $(".bs_carousel .carousel-item").each(function(index, element) {
        if (index == 0) {
          $(".bs_carousel_prices").addClass("pprty-price-active pprty-first-time");
        }
        $(".bs_carousel_prices .property-carousel-ticker-counter").append("<span>0" + bsCarouselItems + "</span>");
        bsCarouselItems += 1;
      });
    }
    $(".bs_carousel_prices .property-carousel-ticker-total").append("<span>0" + $(".bs_carousel .carousel-item").length + "</span>");
    if ($(".bs_carousel").length) {
      $(".bs_carousel").on("slide.bs.carousel", function(carousel) {
        $(".bs_carousel_prices").removeClass("pprty-first-time");
        $(".bs_carousel_prices").carousel(carousel.to);
      });
    }
    if ($(".bs_carousel").length) {
      $(".bs_carousel").on("slid.bs.carousel", function(carousel) {
        var tickerPos = (carousel.to) * 25;
        $(".bs_carousel_prices .property-carousel-ticker-counter > span").css("transform", "translateY(-" + tickerPos + "px)");
      });
    }
    if ($(".bs_carousel .property-carousel-control-next").length) {
      $(".bs_carousel .property-carousel-control-next").on("click", function(e) {
        $(".bs_carousel").carousel("next");
      });
    }
    if ($(".bs_carousel .property-carousel-control-prev").length) {
      $(".bs_carousel .property-carousel-control-prev").on("click", function(e) {
        $(".bs_carousel").carousel("prev");
      });
    }
  }
  $(".moremenu .more-nav .dropdownmoremenu .dropdown-menu").each(function() {
    $(this).find("a").each(function() {
      if($(this).attr('href') === "#") $(this).removeAttr("href");
    })
  })
});
if ($(".bs_carousel").length) {
  $(".bs_carousel").carousel({
    interval: 6000,
    pause: "true"
  });
}
