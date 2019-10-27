$(document).ready(function() {

  //initialize Masonry
  $grid = $('.grid').masonry({
    itemSelector: '.card',
    percentPosition: true,
    transitionDuration: 0
  });

  fetchPosts("init");

});

function fetchPosts(page) {
  // fetch posts
  $.ajax({
    type: 'GET',
    url: 'fetchposts.php',
    dataType: 'html',
    timeout: 10000,
    success: function(data) {
      $(".grid").prepend(data);

      setTimeout(function() {
        $grid.masonry('reloadItems');
        $grid.masonry('layout');
      }, 50);

      // layout masonry grid when images loaded
      $grid.imagesLoaded().progress(function(instance) {
        $grid.masonry('reloadItems');
        $grid.masonry('layout');
      })

      if (page == "init") {
        //hide loading
        setTimeout(function() {
          $(".loading").remove();
        }, 40);

        setTimeout(function() {
          $grid.masonry({
            transitionDuration: '0.15'
          });
        }, 50);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      error("Neue Posts konnten nicht geladen werden (" + textStatus + ") Überprüfe deine Internetverbindung und versuche es später erneut.");
    }
  });
}
