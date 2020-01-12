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

function updateSettings(settings) {
  // Send data
  $.ajax({
    type: 'POST',
    url: '/framework/updateSettings.php',
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

function switchHeaderProfileMenu() {
  if ($("html").hasClass('header__profile-menu--shown')) {
    $("html").removeClass('header__profile-menu--shown');
  } else {
    $("html").addClass('header__profile-menu--shown');
  }
}

// close profile menu when a click somwhere else is detected
$(document).click(function(e) {
  if ($(e.target).closest(".header__profile-menu, .header").length > 0) {
  } else {
    $('html').removeClass('header__profile-menu--shown');
  }
});

// Messages
var messageCount = 2;

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
  var html = '<div class="message--' + messageCount + ' message--' + type + ' message"><div class="message__ribbon"><i class="material-icons">error_outline</i></div><div class="message__close"><i class="material-icons">close</i></div><div class="message__float-fix"></div><span>' + message + '</span></div>';
  $(".message-box").prepend(html);
  if (type == 'error') {
    console.error(message);
  } else {
    console.warn(message);
  }
  var new_message = '.message--' + messageCount;
  $(new_message).delay(10000).fadeOut(500);
  messageCount = messageCount + 1;
}

// Feauture Detext Media Query prefered color scheme
function supportsPreferedColorScheme() {
  if ($('.feature-detect--prefers-color-scheme--1').height() == 4 || $('.feature-detect--prefers-color-scheme--2').height() == 6) {
    return true;
  } else {
    return false;
  }
}


window.AutoTheme = false;

// set Auto Theme
$(document).ready(function() {

  // log Support
  if (supportsPreferedColorScheme()) {
    console.log('Supports Media Query prefered-color-scheme.');
  } else {
    console.log('Doesnt support Media Query prefered-color-scheme. Using sunrise/sunset calculations.')
  }


  // Interval checks every X time
  setInterval(function() {

    // Check if Theme is Auto
    if (window.AutoTheme == true) {

      // check Media Query support
      if (supportsPreferedColorScheme()) {

        if ($('.feature-detect--prefers-color-scheme--1').height() == 4) {
          // prefers light
          if ($('html').hasClass('dark')) {
            $('html').addClass('light');
            $('html').removeClass('dark');
            document.cookie = "last_color_scheme=light; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
          }

        } else if ($('.feature-detect--prefers-color-scheme--2').height() == 6) {
          // prefers dark
          if ($('html').hasClass('light')) {
            $('html').addClass('dark');
            $('html').removeClass('light');
            document.cookie = "last_color_scheme=dark; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
          }

        } else {
          console.log('supportsPreferedColorScheme() error');
        }

      } else {
        // using sunlight calculations
        var today = new Date();
        var obj = SunCalc.getTimes(today, 48, 11);

        if (today > obj['sunrise'] && today < obj['sunset']) {
          // sun's shining
          if ($('html').hasClass('dark')) {
            $('html').addClass('light');
            $('html').removeClass('dark');
            document.cookie = "last_color_scheme=light; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
          }

        } else {
          // its night
          if ($('html').hasClass('light')) {
            $('html').addClass('dark');
            $('html').removeClass('light');
            document.cookie = "last_color_scheme=dark; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
          }

        }

      }
    }
  }, 400);
});

// switch Color Theme
function switchTheme(theme) {

  if (theme == 'auto') {

    //Set Theme to Auto
    window.AutoTheme = true;

    document.cookie = "color_scheme=auto; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";

  } else if (theme == 'light') {
    // set theme to light
    window.AutoTheme = false;
    $('html').addClass('light');
    $('html').removeClass('dark');
    document.cookie = "color_scheme=light; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
    document.cookie = "last_color_scheme=light; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";

  } else if (theme == 'dark') {
    // Set Theme to dark
    window.AutoTheme = false;
    $('html').addClass('dark');
    $('html').removeClass('light');
    document.cookie = "color_scheme=dark; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";
    document.cookie = "last_color_scheme=dark; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/";

  } else {
    return 'Theme not recognised!';
  }
}

$(document).ready(function() {

  // Check Cookie for darkmode
  var color_scheme = getCookie("color_scheme");
  if (color_scheme == "auto") {
    // auto
    $(".side-menu__theme-selection__option--auto").addClass("side-menu__theme-selection__option--selected");
    switchTheme('auto');

  } else if (color_scheme == "light") {
    // light
    $(".side-menu__theme-selection__option--light").addClass("side-menu__theme-selection__option--selected");
    switchTheme('light');

  } else if (color_scheme == "dark") {
    // dark
    $(".side-menu__theme-selection__option--dark").addClass("side-menu__theme-selection__option--selected");
    switchTheme('dark');

  }

  // color scheme Button logic
  $(".side-menu__theme-selection__option").click(function() {
    if ($(this).hasClass("side-menu__theme-selection__option--auto")) {
      // auto
      $(".side-menu__theme-selection__option--auto").addClass("side-menu__theme-selection__option--selected");
      $(".side-menu__theme-selection__option").not(this).removeClass("side-menu__theme-selection__option--selected");
      switchTheme('auto');
      updateSettings({
        color_scheme: 'auto'
      })

    } else if ($(this).hasClass("side-menu__theme-selection__option--light")) {
      // light
      $(".side-menu__theme-selection__option--light").addClass("side-menu__theme-selection__option--selected");
      $(".side-menu__theme-selection__option").not(this).removeClass("side-menu__theme-selection__option--selected");
      switchTheme('light');
      updateSettings({
        color_scheme: 'light'
      })

    } else if ($(this).hasClass("side-menu__theme-selection__option--dark")) {
      // dark
      $(".side-menu__theme-selection__option--dark").addClass("side-menu__theme-selection__option--selected");
      $(".side-menu__theme-selection__option").not(this).removeClass("side-menu__theme-selection__option--selected");
      switchTheme('dark');
      updateSettings({
        color_scheme: 'dark'
      })

    }

  })

  //  Side-menu Button
  $(".header__nav-items__side-menu-btn").click(function() {
    if ($('html').hasClass("side-menu--shown")) {
      $('html').removeClass("side-menu--shown");
    } else {
      $('html').addClass("side-menu--shown");
    }
  });

  // close side-menu when a click somwhere else is detected
  $(document).click(function(e) {
    if ($(e.target).closest(".side-menu, .header").length > 0) {
    } else {
      $('html').removeClass('side-menu--shown');
    }
  });

  // Side-Menu Hover
  $(".side-menu__list__li, .side-menu hr").mouseover(function() {
    $(".side-menu__list__li").not(this).addClass("side-menu--not-hovered");
  });
  $(".side-menu__list__li, .side-menu hr").mouseleave(function() {
    $(".side-menu__list__li").not(this).removeClass("side-menu--not-hovered");
  });

  // Close message
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

window.isLoginShown = false;

function showLogin() {
  if (window.isLoginShown == false) {
    $('body').scrollLock('enable');
    $('.login__wrapper').fadeIn(200);
    window.isLoginShown = true;
  }

  if ($('html').hasClass("side-menu--shown")) {
    $('html').removeClass("side-menu--shown");
  }
}

var hideLogin = function() {
  if (window.isLoginShown == true) {
    $('body').scrollLock('disable')
    $('.login__wrapper').fadeOut(200);
    window.isLoginShown = false;
  }
}

$(document).on('keypress', function(e) {
  if (e.key === "Escape") {
    if (window.isLoginShown == true) {
      e.preventDefault();
    }
    hideLogin();
  }
});

$(document).ready(function() {
  // Check Hash For Login and open it
  if (window.location.hash === "#login") {
    showLogin()
  }

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
          var error = data.username;
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
            setTimeout(function() {
              window.location = window.location.href.split("#")[0];
            }, 200);


            // locate error in form
          } else if (error == '1') {} else if (username == "invalid") {
            $(".form__textfield--username").addClass("form__textfield--error");
            $(".form__textfield--username").focus();
            loginResetButton();
          } else if (password == "invalid") {
            $(".form__textfield--password").addClass("form__textfield--error");
            $(".form__textfield--password").focus();
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
      $(".form__textfield--username").focus();
      loginResetButton();
    } else if ($('.form__textfield--username').val().length != 0 && $('.form__textfield--password').val().length == 0) {
      // when the username is filled but the password isnt
      $(".container__login__error").text("Gebe dein Passwort ein.");
      $(".form__textfield--password").addClass("form__textfield--error");
      $(".form__textfield--password").focus();
      loginResetButton();
    } else {
      // when both are empty
      $(".container__login__error").text("Gebe deine Benutzerdaten ein.");
      $(".form__textfield--username").addClass("form__textfield--error");
      $(".form__textfield--password").addClass("form__textfield--error");
      $(".form__textfield--username").focus();
      loginResetButton();
    }
  });

  $('.login__wrapper, .login__container__close, .login__container__close .material-icons').click(function(e) {
    if (e.target == this) {
      hideLogin()
    }
  });
});
