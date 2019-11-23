// Set up variables
window.initComplete = false;

setTimeout(function () {
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

      // get the last Anchor loaded on the page
      var lastPageAnchor = $('.page-anchor:last-of-type');

      //get the scroll position of the bottom of the page
      var scrollBottom = $(window).scrollTop() + $(window).height();

      // get the position og the last loaded Anchor
      var lastPageAnchorPos = lastPageAnchor.offset().top;

      // get the data-page Attr of the last loaded page and save it
      var lastPageAnchorAttr = lastPageAnchor.attr('data-page');
      if (lastPageAnchorAttr == "end") {
        return "End of Page!"
      }
      window.nextPageToFetch = parseInt(lastPageAnchorAttr);

      // get the distance between viewport bottom and ll Anchor
      var distanceToAnchor = lastPageAnchorPos - scrollBottom;

      // if bottom of screen is close enough to the ll Anchor
      if (distanceToAnchor < 300) {
        // fetch next page
        fetchPage(window.nextPageToFetch);
      }
    }

  }, 50);
}

// Array saves all loaded Pages
var loadedPages = [];

function fetchPage(page) {

  // Saves Page Loading state
  window.PageLoading = true;

  //Check page
  if (typeof page === 'number') {
    var loadpage = page;
  } else if (page == 'init') {
    var loadpage = 1;
  } else if (page == 'next') {
    var loadpage = Math.max.apply(Math, loadedPages) + 1;
  } else {
    return 'unvalid page!';
  }

  // Check if page is already loaded
  if (loadedPages.includes(page)) {
    return 'already loaded!';
  } else {
    loadedPages.push(loadpage);
    window.loadedPages = loadedPages;
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

        // set PageLoading state to false
        window.PageLoading = false;

        // wait for appending
        setTimeout(function() {

          // relayout items after appending
          $grid.masonry('reloadItems');
          $grid.masonry('layout');

          // get bottomPos of grid
          var gridBottomPos = $grid.offset().top + $grid.height();

          // create and append Anchor
          var dataPage = loadpage + 1;
          var anchorHTML = '<a data-page="' + dataPage + '" class="page-anchor" href="#" style="display: block; position: absolute; top: ' + gridBottomPos + 'px"></a>'
          $("body").append(anchorHTML);
        }, 50);

        // layout masonry grid when images loaded
        $grid.imagesLoaded().progress(function(instance) {
          $grid.masonry('reloadItems');
          $grid.masonry('layout');
        })

        if (page == "init") {


          // wait for appending of elements
          setTimeout(function() {

            // remove loading screem
            $(".loading").remove();
            setTimeout(function() {
              // Mark init as complete
              window.initComplete = true;
            }, 11);

          }, 40);

        }
      } else {

        // get bottomPos of grid
        var gridBottomPos = $grid.offset().top + $grid.height();

        // create and append Anchor
        var anchorHTML = '<a data-page="end" class="page-anchor" href="#" style="display: block; position: absolute; top: ' + gridBottomPos + 'px"></a>'
        $("body").append(anchorHTML);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      error("Es gab ein Problem beim Laden der Posts (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
    }
  });
}
