// Set up variables
window.initComplete = false;

setTimeout(function() {
  if (window.initComplete == false) {
    warning("Das Laden der Seite dauert länger als erwartet.")
  }
}, 10000);

$(document).ready(function() {

  //initialize Masonry
  $grid = $('.grid').masonry({
    itemSelector: '.card',
    percentPosition: true,
    transitionDuration: 0
  });

  fetchPage("init");
  infiniteScrollLoop()
});

// infinite Scroll
function infiniteScrollLoop() {

  // Check every x amount of time
  setInterval(function() {

    // only execute when initialization is complete
    if (window.initComplete == true) {

      // exit function if page ends
      if (window.nextPage == "end") {
        $('.loading').remove();
        return "End of Page!"
      }

      //get the scroll position of the bottom of the page
      var scrollBottom = $(window).scrollTop() + $(window).height();

      var gridBottom = $grid.offset().top + $grid.height();

      // get the distance between viewport bottom and page bottom
      var distanceToBottom = gridBottom - scrollBottom;

      // if bottom of screen is close enough to the page bottom
      if (distanceToBottom < 300) {
        // fetch next page
        fetchPage(window.nextPage);
      }
    }

  }, 50);
}

// Array saves all loaded Pages
window.loadedPages = [];

function fetchPage(page) {
  // Saves Page Loading state
  window.pageLoading = true;

  //Check page
  if (typeof page === 'number') {
    var loadpage = page;
  } else if (page == 'init') {
    var loadpage = 1;
  } else if (page == 'next') {
    var loadpage = Math.max.apply(Math, window.loadedPages) + 1;
  } else {
    return 'unvalid page!';
  }

  // Check if page is already loaded
  if (window.loadedPages.indexOf(loadpage) >= 0) {
    return 'already loaded!';
  } else {
    window.loadedPages.push(loadpage);
  }

  // fetch posts
  $.ajax({
    type: 'GET',
    url: 'fetchposts.php',
    dataType: 'html',
    data: 'page=' + loadpage,
    timeout: 30000,
    success: function(data) {

      // check if data is empty
      if (data != "") {

        // Append data
        $(".grid").append(data);

        // set pageLoading state to false
        window.pageLoading = false;

        // wait for appending
        setTimeout(function() {

          // relayout items after appending
          $grid.masonry('reloadItems');
          $grid.masonry('layout');

          // Save next Page
          window.nextPage = loadpage + 1;
        }, 50);

        // layout masonry grid when images loaded
        $grid.imagesLoaded().progress(function(instance) {
          $grid.masonry('reloadItems');
          $grid.masonry('layout');
        })

        if (page == "init") {


          // wait for appending of elements
          setTimeout(function() {

            // Mark init as complete
            setTimeout(function() {
              window.initComplete = true;
            }, 11);

          }, 40);

        }
      } else {
        // Save next Page
        window.nextPage = 'end';
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {

      for (var i = 0; i < window.loadedPages.length; i++) {
        if (window.loadedPages[i] === loadpage) {
          window.loadedPages.splice(i, 1);
        }
      }

      console.log(loadpage);
      error("Es gab ein Problem beim Laden der Posts (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
    }
  });
}



$(window).on('resize', function() {
  $grid.masonry('layout');
});
