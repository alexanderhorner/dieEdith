function hashSort() {

  var hash = window.location.hash.substring(1);

  if (hash == 'artikel') {

    $('.cards').isotope({
      filter: '.card--article, .card--null--article'
    })

  } else if (hash == 'entwuerfe') {

    $('.cards').isotope({
      filter: '.card--draft, .card--null--draft'
    })

  } else {
    // hash == 'beitraege'
    $('.cards').isotope({
      filter: '.card--article, .card--post, .card--null--posts, .card--new-post'
    })

  }
}

$(document).ready(function() {

  setTimeout(function() {
    $('.cards').isotope({
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


  // Submit post
  $('.card--new-post form').submit(function(event) {
    event.preventDefault();
    console.log('test');

    // Disable Button
    $(".card--new-post__submit").prop("disabled", true);
    $(".card--new-post__submit").attr("value", "Checke...");

    // check if fields are filled
    if ($('.card--new-post__textarea').val().length != 0) {

      // Send data
      $.ajax({
        type: 'POST',
        url: '/framework/newPost.php',
        dataType: 'json',
        data: 'text=' + encodeURIComponent($('.card--new-post__textarea').val()),
        timeout: 10000,
        success: function(data) {
          if (data.request == "failed") {
            error("Es ist ein Fehler beim posten aufgetreten (" + data.error + "). Überprüfe deine Internetverbindung und probiere es später erneut.");
          }

          // prepend
          var today = new Date();
          var date = today.getDate() + '.' + (today.getMonth()+1) + '.' + today.getFullYear();

          var $items = $('<div class="card card--post"><div class="post__text"><span>' + $('.card--new-post__textarea').val() + '</span></div><div class="card__time">' + date + '</div></div>');
          $('.cards').prepend($items)
            // add and lay out newly prepended items
            .isotope('prepend', $items);

          //clear textarea
          $('.card--new-post__textarea').val('');

          // Enable Button
          $(".card--new-post__submit").prop("disabled", false);
          $(".card--new-post__submit").attr("value", "Posten");

        },
        error: function(jqXHR, textStatus, errorThrown) {
          error("Es ist ein Fehler beim posten aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und probiere es später erneut.");

          // Enable Button
          $(".card--new-post__submit").prop("disabled", false);
          $(".card--new-post__submit").attr("value", "Posten");
        }
      });
    } else {
      // when both are empty
      $(".container__login__error").text("Gebe deine Benutzerdaten ein.");
      $(".form__textfield--username").addClass("form__textfield--error");
      $(".form__textfield--password").addClass("form__textfield--error");
      $(".form__textfield--username").focus();
      loginResetButton();
    }
  });

});
