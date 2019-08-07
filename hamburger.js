$(document).ready(function() {
  //Hamburger Button
  $(".hamburger").click(function() {
    if ($(this).hasClass("is-active")) {
      $(this).removeClass("is-active");
      $("html").removeClass("dark");
    } else {
      $(this).addClass("is-active");
      $("html").addClass("dark");
    }
  })
});
