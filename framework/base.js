function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

function linkto(x) {
  var selection = window.getSelection();
  if (selection.toString().length === 0) {
    window.location = x;
  }
}

function update_settings(settings) {
  // Send data
  $.ajax({
    type: 'POST',
    url: '/framework/update_settings.php',
    dataType: 'json',
    data: $.param(settings),
    timeout: 10000,
    success: function(data) {
      if (data.request == "failed") {
        error("Es ist ein Fehler beim aktualisieren deiner Einstellungen aufgetreten (" + data.error + "). Die Einstellungen wurden nicht in deinem Account gespeichert. Probiere es später erneut.");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      error("Es ist ein Fehler beim aktualisieren deiner Einstellungen aufgetreten (" + textStatus + "). Die Einstellungen wurden nicht in deinem Account gespeichert. Probiere es später erneut.");
    }
  });
}

// Messages
var messageCount = 1;

function error(text) {
  message(text, "error");
}

function warning(text) {
  message(text, "warning");
}

function message(message, type) {
  if (type != "warning" && type != "error") {
    return "Unknown message type."
  };
  var html = '<div class="message--' + messageCount + ' message--' + type + ' message"><div class="message__ribbon"><i class="material-icons">error_outline</i></div><div class="message__close"><i class="material-icons">close</i></div><div class="message_float_fix"></div><span>' + message + '</span></div>';
  $(".message-box").prepend(html);
  var new_message = '.message--' + messageCount;
  $(new_message).delay(10000).fadeOut(500);
  messageCount = messageCount + 1;
}

$(document).ready(function() {
  //Check Cookie for darkmode
  var prefers_color_scheme = getCookie("prefers_color_scheme");
  if (prefers_color_scheme == "dark") {
    $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", true);
    document.cookie = "prefers_color_scheme=dark; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  } else {
    $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", false);
    document.cookie = "prefers_color_scheme=light; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  }

  $(".side-menu__list__li:contains(Dunkel)").click(function() {
    setTimeout(function() {
      $(".hamburger").trigger("click");
    }, 250);
    if ($("html").hasClass("dark")) {
      $("html").removeClass("dark");
      $("html").addClass("light");
      $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", false);
      document.cookie = "prefers_color_scheme=light; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
      update_settings({
        prefers_color_scheme: 'light'
      });
    } else {
      $("html").addClass("dark");
      $("html").removeClass("light");
      $(".side-menu__list__li:contains(Dunkel) .switch").prop("checked", true);
      document.cookie = "prefers_color_scheme=dark; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
      update_settings({
        prefers_color_scheme: 'dark'
      });
    }
  });

  //Check Cookie for debug
  var debug = getCookie("debug");
  if (debug == "true") {
    $("html").addClass("debug");
    $(".side-menu__list__li:contains(Debug) .switch").prop("checked", true);
    document.cookie = "debug=true; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  } else {
    $("html").removeClass("debug");
    $(".side-menu__list__li:contains(Debug) .switch").prop("checked", false);
    document.cookie = "debug=false; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
  }

  // debug Button
  $(".side-menu__list__li:contains(Debug)").click(function() {
    setTimeout(function() {
      $(".hamburger").trigger("click");
    }, 200);
    if ($("html").hasClass("debug")) {
      $("html").removeClass("debug");
      $(".side-menu__list__li:contains(Debug) .switch").prop("checked", false);
      document.cookie = "debug=false; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    } else {
      $("html").addClass("debug");
      $(".side-menu__list__li:contains(Debug) .switch").prop("checked", true);
      document.cookie = "debug=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    }
  });

  //  Side-menu Button
  $(".hamburger").click(function() {
    if ($(this).hasClass("is-active")) {
      $(this).removeClass("is-active");
      $(".side-menu, html").removeClass("side-menu--shown");
    } else {
      $(this).addClass("is-active");
      $(".side-menu, html").addClass("side-menu--shown");
    }
  });

  // Home Button
  $(".header__title, .header__logo").click(function() {
    window.location = '/';
  });

  // Side-Menu Hover
  $(".side-menu__list__li").mouseover(function() {
    $(".side-menu__list__li").not(this).addClass("side-menu__list__li--not-hovered");
  });
  $(".side-menu__list__li").mouseleave(function() {
    $(".side-menu__list__li").not(this).removeClass("side-menu__list__li--not-hovered");
  });

  // Close error Button
  $(document).on('click', '.message', function() {
    var message = this;
    var selection = window.getSelection();
    if (selection.toString().length === 0) {
      $(message).stop();
    }
  });
});


// Login
function loginResetButton() {
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
        url: '/framework/verify-login.php',
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
            setTimeout(function () {
              location.reload();
            }, 200);


            // locate error in form
          } else if (username == "invalid") {
            $(".form__textfield--username").addClass("form__textfield--error");
            loginResetButton();
          } else if (password == "invalid") {
            $(".form__textfield--password").addClass("form__textfield--error");
            loginResetButton();
          }
        },

        // on ajax error
        error: function(jqXHR, textStatus, errorThrown) {
          loginResetButton();
          error("Es ist ein Fehler bei der Anmeldung aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und probiere es später erneut.");
        }
      });
    } else if ($('.form__textfield--username').val().length == 0 && $('.form__textfield--password').val().length != 0) {
      // when the password is filled but the username isnt
      $(".form__textfield--username").addClass("form__textfield--error");
      $(".container__login__error").text("Gebe deinen Benutzername ein.");
      loginResetButton();
    } else if ($('.form__textfield--username').val().length != 0 && $('.form__textfield--password').val().length == 0) {
      // when the username is filled but the password isnt
      $(".container__login__error").text("Gebe dein Passwort ein.");
      $(".form__textfield--password").addClass("form__textfield--error");
      loginResetButton();
    } else {
      // when both are empty
      $(".container__login__error").text("Gebe deine Benutzerdaten ein.");
      $(".form__textfield--username").addClass("form__textfield--error");
      $(".form__textfield--password").addClass("form__textfield--error");
      loginResetButton();
    }
  });

  $('.login__wrapper, .login__container__close, .login__container__close .material-icons').click(function(e) {
    if (e.target == this) {
      $('body').scrollLock('disable')
      $('.login__wrapper').fadeOut(200);
    }
  });
});
