function htmlEntities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function hashSort() {

  var hash = window.location.hash.substring(1);

  if (hash == 'artikel') {

    $('.selection__button--selection').removeClass('selection__button--selection--active');
    $('.selection__button--selection--articles').addClass('selection__button--selection--active');
    $grid.isotope({
      filter: '.card--article--released, .card--null--articles'
    })

  } else if (hash == 'entwuerfe') {

    $('.selection__button--selection').removeClass('selection__button--selection--active');
    $('.selection__button--selection--drafts').addClass('selection__button--selection--active');
    $grid.isotope({
      filter: '.card--article--draft, .card--null--draft'
    })

  } else {
    // hash == 'beitraege'
    $('.selection__button--selection').removeClass('selection__button--selection--active');
    $('.selection__button--selection--posts').addClass('selection__button--selection--active');
    $grid.isotope({
      filter: '.card--article--released, .card--post, .card--null--posts, .card--new-post'
    })
  }

  if (hash == 'neuerArtikel') {
    $('html').addClass('prompt--new-article--shown')
  }
}

window.prependedPostCount = 0;

$(document).ready(function() {

  // close prompt
  $('.background--grayish').click(function() {
    $('html').removeClass('prompt--new-article--shown');
  })
  $(document).on('keypress', function(e) {
    if (e.key === "Escape") {
      if ($('html').hasClass('prompt--new-article--shown')) {
        e.preventDefault();
        $('html').removeClass('prompt--new-article--shown');
      }
    }
  });

  // prevent new lines
  $('.card--new-post__textarea').on('input paste', function() {
    $('.card--new-post__textarea').val($('.card--new-post__textarea').val().replace(/\n/g, ""));
  });


  if ($(".card__time").length) {
    const nodes = document.querySelectorAll('.card__time');
    timeago.render(nodes, 'de');
  }

  $grid = $('.cards').isotope({
    getSortData: {
      postedOn: '[data-postedOn] parseInt'
    },
    sortBy: 'postedOn',
    sortAscending: false,
    transitionDuration: 0,
    filter: '.card--article, .card--post, .card--null--posts, .card--new-post'
  })

  setTimeout(function() {
    $grid.isotope({
      transitionDuration: '0.4s'
    })
    $grid.css('opacity', '1');
  }, 70);

  // Set height of cards
  var minHeight = $(window).height() - $('.cards').offset().top;
  $('.cards').css('min-height', minHeight);
  $(window).resize(function() {
    var minHeight = $(window).height() - $('.cards').offset().top;
    $('.cards').css('min-height', minHeight);
  })

  hashSort();

  setTimeout(function() {
    $grid.isotope({
      // options
      itemSelector: '.card',
      layoutMode: 'vertical'
    });
  }, 50);


  //if theres a hash
  setTimeout(function() {
    if (window.location.hash) {
      hashSort()
    }
  }, 60);

  // on hash change
  $(window).on('hashchange', function(e) {
    hashSort();
  });


  // Submit article
  $('.prompt--new-article__form').submit(function(event) {
    event.preventDefault();

    // Disable Button
    $(".prompt--new-article__form__submit").prop("disabled", true);
    $(".prompt--new-article__form__submit").attr("value", "Warte");

    // Send data
    $.ajax({
      type: 'POST',
      url: '/framework/newArticle.php',
      dataType: 'json',
      data: 'title=' + encodeURIComponent($('.prompt--new-article__form__input').val()),
      timeout: 10000,
      success: function(data) {
        if (data.request == "failed") {
          error("Es ist ein Fehler beim erstellen aufgetreten (" + data.error + "). Überprüfe deine Internetverbindung und probiere es später erneut.");
        } else {
          window.location = '/editor/' + encodeURIComponent($('.prompt--new-article__form__input').val());
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        error("Es ist ein Fehler beim posten aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und probiere es später erneut.");

        // Enable Button
        $(".prompt--new-article__form__submit").prop("disabled", false);
        $(".prompt--new-article__form__submit").attr("value", "Erstellen");
      }
    });

  });


  // Submit post
  $('.card--new-post form').submit(function(event) {
    event.preventDefault();

    $('.card--null--posts').remove();

    // Disable Button
    $(".card--new-post__submit").prop("disabled", true);
    $(".card--new-post__submit").attr("value", "Postet...");

    // Send data
    $.ajax({
      type: 'POST',
      url: '/framework/newPost.php',
      dataType: 'json',
      data: 'text=' + encodeURIComponent($('.card--new-post__textarea').val()),
      timeout: 10000,
      success: function(data) {
        if (data.status != "successful") {
          // Enable Button
          $(".card--new-post__submit").prop("disabled", false);
          $(".card--new-post__submit").attr("value", "Posten");

          error("Es ist ein Fehler beim posten aufgetreten (" + data.error['category'] + ': ' + data.error['description'] + "). Probiere es später erneut.");
        } else {

          // prepend
          var today = new Date();
          var date = today.getDate() + '.' + (today.getMonth() + 1) + '.' + today.getFullYear();

          var $items = $('<div id="prependedPost' + window.prependedPostCount + '" data-postedOn="' + Math.floor(Date.now() / 1000 + 3600) + '" class="card card--post"><div class="post__text"><span>' + htmlEntities($('.card--new-post__textarea').val()) + '</span></div><div data-timeago="' + Math.floor(Date.now()) + '" class="card__time"></div></div>');
          $grid.prepend($items).isotope('insert', $items);

          // render prepended Post's time
          var prependedPost = '#prependedPost' + window.prependedPostCount + ' .card__time';
          timeago.render(document.querySelectorAll(prependedPost), 'de');

          window.prependedPostCount = window.prependedPostCount + 1;

          //clear textarea
          $('.card--new-post__textarea').val('');

          // Enable Button
          $(".card--new-post__submit").prop("disabled", false);
          $(".card--new-post__submit").attr("value", "Posten");
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        error("Es ist ein Fehler beim posten aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und probiere es später erneut.");

        // Enable Button
        $(".card--new-post__submit").prop("disabled", false);
        $(".card--new-post__submit").attr("value", "Posten");
      }
    });

  });

});
