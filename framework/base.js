function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

function linkto(x) {
  var selection = window.getSelection();
  if (selection.toString().length === 0) {
    window.location = x;
  }
}

// function update_settings() {
//   windows.
//   for (var key in window.settings) {
//     console.log(key + ":" + settings[key]);
//   }
// }

$(document).ready(function() {
  //Check Cookie for darkmode
  var darkmode = getCookie("darkmode");
  if (darkmode == "true") {
    $("html").addClass("dark");
    $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", true);
    document.cookie = "darkmode=true; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  } else {
    $("html").removeClass("dark");
    $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", false);
    document.cookie = "darkmode=false; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  }

  // Darkmode Button
  $(".side-menu__list__li:contains(Dunkel)").click(function() {
    if ($("html").hasClass("dark")) {
      $("html").removeClass("dark");
      $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", false);
      document.cookie = "darkmode=false; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
    } else {
      $("html").addClass("dark");
      $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", true);

      document.cookie = "darkmode=true; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
    }
  });

  //Check Cookie for debug
  var debug = getCookie("debug");
  if (debug == "true") {
    $("html").addClass("debug");
    $(".side-menu__list__li:contains(Debug) .switch").prop("checked", true);
    document.cookie = "debug=true; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  } else {
    $("html").removeClass("debug");
    $(".side-menu__list__li:contains(Debug) .switch").prop("checked", false);
    document.cookie = "debug=false; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  }

  // debug Button
  $(".side-menu__list__li:contains(Debug)").click(function() {
    if ($("html").hasClass("debug")) {
      $("html").removeClass("debug");
      $(".side-menu__list__li:contains(Debug) .switch").prop("checked", false);
      document.cookie = "debug=false; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
      update_prefered_color_sheme('light');
    } else {
      $("html").addClass("debug");
      $(".side-menu__list__li:contains(Debug) .switch").prop("checked", true);
      document.cookie = "debug=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
      update_prefered_color_sheme('dark');
    }
  });

  //Hamburger Button
  $(".hamburger").click(function() {
    if ($(this).hasClass("is-active")) {
      $(this).removeClass("is-active");
      $(".side-menu, html").removeClass("side-menu--shown");
    } else {
      $(this).addClass("is-active");
      $(".side-menu, html").addClass("side-menu--shown");
    }
  });

  // Home Button
  $(".header__title, .header__logo").click(function() {
    window.location = '/';
  });

  // Side-Menu Hover
  $(".side-menu__list__li").mouseover(function() {
    $(".side-menu__list__li").not(this).addClass("side-menu__list__li--not-hovered");
  });
  $(".side-menu__list__li").mouseleave(function() {
    $(".side-menu__list__li").not(this).removeClass("side-menu__list__li--not-hovered");
  });
});

$(window).on('load', function() {
  setTimeout(
    function() {
      $("html").removeClass("preload");
    }, 500);
});
