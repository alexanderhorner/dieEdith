$(document).ready(function() {
  console.log("Initialize masonry!");
  $('.grid').masonry({
    // set itemSelector so .grid-sizer is not used in layout
    itemSelector: '.grid__item',
    // use element for option
    percentPosition: true
  })
});
