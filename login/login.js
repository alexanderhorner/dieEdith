$(document).ready(function() {
  $('#loginform').submit(function(e) {
    $("#username_input").removeClass("error");
    $("#password_input").removeClass("error");
    $("#form__button").prop("disabled", true);
    $("#form__button").attr("value", "Warte...");
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: 'verify-login.php',
      dataType: 'json',
      data: $('form').serialize(),
      success: function(data) {

        $("#form__button").prop("disabled", false);
        $("#form__button").attr("value", "Anmelden");

        var username = data.username;
        var password = data.password;
        var errormessage = data.errormessage;

        $("#errormessage").text(errormessage);

        if (username == "valid" && password == "valid") {
          $("#errormessage").css("color", "rgb(47, 237, 107)");

          if (typeof realreferer != 'undefined') {
            window.location = realreferer;
          } else if (document.referrer.indexOf(location.protocol + "//" + location.host) === 0) {
            window.location = document.referrer;
          } else {
            window.location = "../";
          }

        } else if (username == "invalid") {
          $("#username_input").addClass("error");
        } else if (password == "invalid") {
          $("#password_input").addClass("error");
        }
      }
    });
  });
});
