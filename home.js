$(document).ready(function() {
  //initialize Masonry
  $('.grid').masonry({
    itemSelector: '.grid__item',
    percentPosition: true
  })

  //Hamburger Button
  $(".hamburger").click(function() {
    if ($(this).hasClass("is-active")) {
      $(this).removeClass("is-active")
    } else {
      $(this).addClass("is-active")
    }
  })
});