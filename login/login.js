function resetButton() {
  // Change button to normal
  $(".form__submit").prop("disabled", false);
  $(".form__submit").attr("value", "Absenden");
}

$(document).ready(function() {

  // Submit Login
  $('.container__login__form').submit(function(event) {
    event.preventDefault();

    // Reset stlyes
    $(".form__textfield").removeClass("form__textfield--error");

    // Disable Button
    $(".form__submit").prop("disabled", true);
    $(".form__submit").attr("value", "Checke...");

    // check if fields are filled
    if ($('.form__textfield--username').val().length != 0 && $('.form__textfield--password').val().length != 0) {

      // Verify data
      $.ajax({
        type: 'POST',
        url: 'verify-login.php',
        dataType: 'json',
        timeout: 3000,
        data: $('.container__login__form').serialize(),

        // on ajax success
        success: function(data) {

          // put data in variables
          var username = data.username;
          var password = data.password;
          var errormessage = data.errormessage;

          // Set errormessage
          $(".container__login__error").text(errormessage);


          // check if all data is valid
          if (username == "valid" && password == "valid") {

            // Dye errormessage green
            $(".container__login__error").css("color", "#30d158");

            // rederict logic
            if (typeof realreferer != 'undefined') {
              window.location = realreferer;
            } else if (document.referrer.indexOf(location.protocol + "//" + location.host) === 0) {
              window.location = document.referrer;
            } else {
              window.location = "../";
            }

            // locate error in form
          } else if (username == "invalid") {
            $(".form__textfield--username").addClass("form__textfield--error");
            resetButton();
          } else if (password == "invalid") {
            $(".form__textfield--password").addClass("form__textfield--error");
            resetButton();
          }
        },

        // on ajax error
        error: function(jqXHR, textStatus, errorThrown){
          resetButton();
          error("Es ist ein Fehler bei der Anmeldung aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und probiere es später erneut.");
        }
      });
    } else if ($('.form__textfield--username').val().length == 0 && $('.form__textfield--password').val().length != 0) {
      // when the password is filled but the username isnt
      $(".form__textfield--username").addClass("form__textfield--error");
      $(".container__login__error").text("Gebe deinen Benutzername ein.");
      resetButton();
    } else if ($('.form__textfield--username').val().length != 0 && $('.form__textfield--password').val().length == 0) {
      // when the username is filled but the password isnt
      $(".container__login__error").text("Gebe dein Passwort ein.");
      $(".form__textfield--password").addClass("form__textfield--error");
      resetButton();
    } else {
      // when both are empty
      $(".container__login__error").text("Gebe deine Benutzerdaten ein.");
      $(".form__textfield--username").addClass("form__textfield--error");
      $(".form__textfield--password").addClass("form__textfield--error");
      resetButton();
    }
  });

  $(".form__textfield").focus(function() {
    scrollTop: $(this).offset().top
    $([document.documentElement, document.body]).animate({
        scrollTop: $(".container__login__form").offset().top - 75
    }, 200);
  });
});
