$(window).on('load', function () {
  //initialize Masonry
  $('.grid').masonry({
    itemSelector: '.card',
    percentPosition: true
  });
});
