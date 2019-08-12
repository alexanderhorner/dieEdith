function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

$(document).ready(function() {

  //Check Cookie for darkmode
  var darkmode = getCookie("darkmode");
  if (darkmode == "true") {
    console.log("darkmode = true");
    $("html").addClass("dark");
  }

  //Hamburger Button
  $(".hamburger").click(function() {
    if ($(this).hasClass("is-active")) {
      $(this).removeClass("is-active");
      $(".side-menu").addClass("side-menu--retracted");
    } else {
      $(this).addClass("is-active");
      $(".side-menu").removeClass("side-menu--retracted");
    }
  });

  $("li:contains(Darkmode)").click(function() {
    if ($("html").hasClass("dark")) {
      $("html").removeClass("dark");
      document.cookie = "darkmode=false; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    } else {
      $("html").addClass("dark");
      document.cookie = "darkmode=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    }
  });

  $(window).scroll(function() {
    if ($(window).scrollTop() > 15) {
      $(".header").removeClass("header--border");
    } else {
      $(".header").addClass("header--border");
    }
  });
});

$(document).ready(function() {
  $(window).trigger("scroll");
});

$(window).on('load', function() {
  $("html").removeClass("preload");
});
