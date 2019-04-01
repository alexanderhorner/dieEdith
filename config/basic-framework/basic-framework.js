$(window).scroll(function() {
  var ScrollPosition = $(window).scrollTop();
  if (ScrollPosition > 100) {
    if ($("#title-section, #title, #nav_information").hasClass("minimized") === false) {
      $("#title-section, #title, #nav_information").addClass("minimized");
      $("#title-section, #title, #nav_information").removeClass("big");
    };

  };
  if (ScrollPosition <= 100) {
    if ($("#title-section, #title, #nav_information").hasClass("big") === false) {
      $("#title-section, #title, #nav_information", ).addClass("big");
      $("#title-section, #title, #nav_information").removeClass("minimized");
    };
  };
});
