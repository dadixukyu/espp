// Gallery FancyBox Js
function fancybox() {
  $('[data-fancybox]').fancybox({
    protect: true,
    loop: true,
    buttons: [
      "fullScreen",
      "thumbs",
      "share",
      "slideShow",
      "close"
    ],
    image: {
      preload: false
    },
  });
}
fancybox();

// Load More and Explore More Button JS
function updateSliceShow() {
  var windowWidth = $(window).width();
  var $defaultShow, $sliceShow;

  if (windowWidth < 768) {
    $defaultShow = 2;
    $sliceShow = 1;
  } else if (windowWidth < 992) {
    $defaultShow = 4;
    $sliceShow = 2;
  } else if (windowWidth < 1200) {
    $defaultShow = 6;
    $sliceShow = 3;
  } else {
    $defaultShow = 16;
    $sliceShow = 16;
  }

  return [$sliceShow, $defaultShow];
}

function load_more($sectionName = "", $locationCol, $btnParentClass, $btnId, $defaultShow = 16, $sliceShow = 16) {
  $($locationCol).css("display", "none");
  $($sectionName + " " + $btnParentClass).css("display", "none");

  $($locationCol).slice(0, $defaultShow).fadeIn();
  if ($($locationCol + ":hidden").length != 0) {
    $($sectionName + " " + $btnParentClass).css("display", "flex");

    $($btnId).off("click").on("click", function (e) {
      e.preventDefault();

      $($locationCol + ":hidden").slice(0, $sliceShow).slideDown(500);
      if ($($locationCol + ":hidden").length == 0) {
        $($sectionName + " " + $btnParentClass).css("display", "none");
      }
    });
  }
}

$(document).ready(function () {
  var sliceDefault, sliceShow;

  [sliceShow, sliceDefault] = updateSliceShow();

  $(window).on("resize", function () {
    [sliceShow, sliceDefault] = updateSliceShow();

    load_more(".d2c_gallery_wrapper", ".d2c_image_wrapper", ".d2c_team_btn", "#d2c_team_more", sliceDefault, sliceShow);
  });

  load_more(".d2c_gallery_wrapper", ".d2c_image_wrapper", ".d2c_team_btn", "#d2c_team_more", sliceDefault, sliceShow);
});




/* 
  Template Name: {{10 gallery section bootstrap Free}}
  Template URL: {{https://designtocodes.com/product/10-responsive-bootstrap-gallery-section}}
  Description: {{Don't miss out on the opportunity to level up your website with our 10 Responsive Bootstrap Gallery Section. Download now and watch your website come to life!}}
  Author: DesignToCodes
  Author URL: https://www.designtocodes.com/
  Text Domain: {{ Gallery Section UI Kits }} 
*/