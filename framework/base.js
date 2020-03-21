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

function closePrompt(prompt) {
	if (prompt == 'all') {

		// delete post
		$("html").removeClass('prompt--delete-post--shown');

		// new article
		$("html").removeClass('prompt--new-article--shown');
		setTimeout(function() {
			$('.prompt--new-article__text-field').val('');
		}, 200)

		// login
		$("html").removeClass('prompt--login--shown');
		// prompt functions
		window.promptFunction = function() {return 'function unset'};

		// scroll lock
		$('body').scrollLock('disable');
		
	} else {
	}
}

window.promptFunction = function() {return 'function unset'};

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
				error("Es ist ein Fehler beim aktualisieren deiner Einstellungen aufgetreten (" + data.error + "). Die Einstellungen wurden nicht in deinem Account gespeichert. Versuche es später erneut.");
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			error("Es ist ein Fehler beim aktualisieren deiner Einstellungen aufgetreten (" + textStatus + "). Die Einstellungen wurden nicht in deinem Account gespeichert. Versuche es später erneut.");
		}
	});
}

function switchHeaderProfileMenu() {
	if ($("html").hasClass('header__profile-menu--shown')) {
		$("html").removeClass('header__profile-menu--shown');
	} else {
		$("html").addClass('header__profile-menu--shown');
		if ($('html').hasClass('side-menu--shown')) {
			$('html').removeClass('side-menu--shown');
		}
	}
}

// close profile menu when a click somwhere else is detected
$(document).click(function(e) {
	if ($(e.target).closest(".header__profile-menu, .header").length > 0) {
	} else {
		$('html').removeClass('header__profile-menu--shown');
	}
});

// close all prompts
$(document).ready(function() {
	$(".prompt-bg").click(function() {
		closePrompt('all');
	});
});

// Messages
$(document).ready(function() {
	// initilize isotope
	$msgBoxIsotope = $('.message-box__isotope-container').isotope({
		itemSelector: '.message',
		layoutMode: 'vertical'
	})
});

function error(text) {
	message(text, "error");
}

function warning(text) {
	message(text, "warning");
}

var messageCount = 2;
function message(message, type) {
	if (type != "warning" && type != "error") {
		return "Unknown message type."
	};

	messageCount = messageCount + 1;

	var newMessageClass = '".message--' + messageCount + '"';

	var $message = $('<div class="message--' + messageCount + ' message--' + type + ' message"><div class="message__ribbon"><i class="material-icons">error_outline</i></div><div class="message__close"><i class="material-icons">close</i></div><div class="message__float-fix"></div><span>' + message + '</span></div>');

	$msgBoxIsotope.prepend($message).isotope('prepended', $message);
	console.warn(message);
	
	setTimeout(function(){
		$msgBoxIsotope.isotope('remove', $message).isotope('layout');
		
	}, 10000)

	$(document).on('click', '.message', function() {
		var message = this;
		var selection = window.getSelection();
		if (selection.toString().length === 0) {
			$msgBoxIsotope.isotope('remove', message).isotope('layout');
		}
	});
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
		console.info('Supports Media Query prefered-color-scheme.');
	} else {
		console.info('Doesnt support Media Query prefered-color-scheme. Using sunrise/sunset calculations.')
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
		if ($('html').hasClass('header__profile-menu--shown')) {
			$('html').removeClass('header__profile-menu--shown');
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

});

// Escape Key
$(document).on('keypress', function(e) {
	if (e.key === "Escape") {

		// login
		if ($('html').hasClass('prompt--login--shown')) {
			e.preventDefault();
			closePrompt('all');
		}

		// Side-menu
		if ($('html').hasClass('side-menu--shown')) {
			e.preventDefault();
			$('html').removeClass('side-menu--shown');
		}

		// header profile-menu
		if ($('html').hasClass('header__profile-menu--shown')) {
			e.preventDefault();
			$('html').removeClass('header__profile-menu--shown');
		}

		// delete post
		if ($('html').hasClass('prompt--delete-post--shown')) {
			e.preventDefault();
			closePrompt('all');
		}

		// new article
		if ($('html').hasClass('prompt--new-article--shown')) {
			e.preventDefault();
			closePrompt('all');
		}
	}
});


$(document).ready(function() {
	// Check Hash For Login and open it
	if (window.location.hash === "#login") {
		$('html').addClass('prompt--login--shown');
	}
	$('.prompt--login__login__form').submit(function(event) {
		event.preventDefault();

		// Reset stlyes
		$(".prompt--login__login__form__textfield").removeClass("prompt--login__login__form__textfield--error");

		// Disable Button
		$(".prompt--login__login__form__submit").prop("disabled", true);
		$(".prompt--login__login__form__submit").attr("value", "Checke...");

		// check if fields are filled
		if ($('.prompt--login__login__form__textfield--username').val().length != 0 && $('.prompt--login__login__form__textfield--password').val().length != 0) {

			// Verify data
			$.ajax({
				type: 'POST',
				url: '/framework/verify-login.php',
				dataType: 'json',
				timeout: 3000,
				data: $('.prompt--login__login__form').serialize(),

				// on ajax success
				success: function(data) {

					// put data in variables
					var error = data.username;
					var username = data.username;
					var password = data.password;
					var errormessage = data.errormessage;

					// Set errormessage
					$(".prompt--login__login__error").text(errormessage);


					// check if all data is valid
					if (username == "valid" && password == "valid") {

						// Dye errormessage green
						$(".prompt--login__login__error").css("color", "#30d158");

						// rederict logic
						setTimeout(function() {
							window.location = window.location.href.split("#")[0];
						}, 200);


						// locate error in form
					} else if (error == '1') {} else if (username == "invalid") {
						$(".prompt--login__login__form__textfield--username").addClass("prompt--login__login__form__textfield--error");
						$(".prompt--login__login__form__textfield--username").focus();

						// reset login button
						$(".prompt--login__login__form__submit").prop("disabled", false);
						$(".prompt--login__login__form__submit").attr("value", "Absenden");;
					} else if (password == "invalid") {
						$(".prompt--login__login__form__textfield--password").addClass("prompt--login__login__form__textfield--error");
						$(".prompt--login__login__form__textfield--password").focus();
						
						// reset login button
						$(".prompt--login__login__form__submit").prop("disabled", false);
						$(".prompt--login__login__form__submit").attr("value", "Absenden");;;
					}
				},

				// on ajax error
				error: function(jqXHR, textStatus, errorThrown) {
					// reset login button
					$(".prompt--login__login__form__submit").prop("disabled", false);
					$(".prompt--login__login__form__submit").attr("value", "Absenden");;
					error("Es ist ein Fehler bei der Anmeldung aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
				}
			});
		} else if ($('.prompt--login__login__form__textfield--username').val().length == 0 && $('.prompt--login__login__form__textfield--password').val().length != 0) {
			// when the password is filled but the username isnt
			$(".prompt--login__login__form__textfield--username").addClass("prompt--login__login__form__textfield--error");
			$(".prompt--login__login__error").text("Gebe deinen Benutzername ein.");
			$(".prompt--login__login__form__textfield--username").focus();
			// reset login button
			$(".prompt--login__login__form__submit").prop("disabled", false);
			$(".prompt--login__login__form__submit").attr("value", "Absenden");;
		} else if ($('.prompt--login__login__form__textfield--username').val().length != 0 && $('.prompt--login__login__form__textfield--password').val().length == 0) {
			// when the username is filled but the password isnt
			$(".prompt--login__login__error").text("Gebe dein Passwort ein.");
			$(".prompt--login__login__form__textfield--password").addClass("prompt--login__login__form__textfield--error");
			$(".prompt--login__login__form__textfield--password").focus();
			// reset login button
			$(".prompt--login__login__form__submit").prop("disabled", false);
			$(".prompt--login__login__form__submit").attr("value", "Absenden");;
		} else {
			// when both are empty
			$(".prompt--login__login__error").text("Gebe deine Benutzerdaten ein.");
			$(".prompt--login__login__form__textfield--username").addClass("prompt--login__login__form__textfield--error");
			$(".prompt--login__login__form__textfield--password").addClass("prompt--login__login__form__textfield--error");
			$(".prompt--login__login__form__textfield--username").focus();
			// reset login button
			$(".prompt--login__login__form__submit").prop("disabled", false);
			$(".prompt--login__login__form__submit").attr("value", "Absenden");;
		}
	});

	$('.prompt--login__close, .prompt--login__close .material-icons').click(function(e) {
		if (e.target == this) {
			closePrompt('all');
		}
	});
});

function newArticle() {

	// show prompt
	$('html').addClass('prompt--new-article--shown');
	$('body').scrollLock('enable');

	// set Function
	window.promptFunction = function() {
		// Submit article
		// Disable Button
		$(".prompt--new-article .prompt__btn-container__btn--confirm").prop("disabled", true);
		$(".prompt--new-article .prompt__btn-container__btn--confirm").text("Laden");

		// get title value
		var titleVal = encodeURIComponent($('.prompt--new-article__text-field').val())

		// Send data
		$.ajax({
			type: 'POST',
			url: '/framework/newArticle.php',
			dataType: 'json',
			data: 'title=' + titleVal,
			timeout: 10000,
			success: function(data) {
				if (data.status != "successful") {
					error("Es ist ein Fehler beim erstellen aufgetreten (" + data.error.category + ": " + data.error.description + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
					// Enable Button
					$(".prompt--new-article .prompt__btn-container__btn--confirm").prop("disabled", false);
					$(".prompt--new-article .prompt__btn-container__btn--confirm").text("Erstellen");
				} else {
					// Enable Button
					$(".prompt--new-article .prompt__btn-container__btn--confirm").prop("disabled", false);
					$(".prompt--new-article .prompt__btn-container__btn--confirm").text("Erstellen");
					closePrompt('all');
					window.location = '/editor/' + titleVal;
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				error("Es ist ein Fehler beim posten aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");

				// Enable Button
				$(".prompt--new-article .prompt__btn-container__btn--confirm").prop("disabled", false);
				$(".prompt--new-article .prompt__btn-container__btn--confirm").text("Erstellen");
			}
		});
	}
}

// delete artilce
function deleteArticle(aid, isCard) {
	$('html').addClass('prompt--delete-post--shown');
	$('body').scrollLock('enable');
	$('')
	window.promptFunction = function() {
		$.ajax({
			type: 'POST',
			url: '/framework/deleteArticle.php',
			dataType: 'json',
			data: 'aid=' + aid,
			timeout: 10000,
			success: function(data) {
				if (data.status != "successful") {
					error("Es ist ein Fehler beim Löschen des Artikels aufgetreten (" + data.error['category'] + ": " + data.error['description'] + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
				} else {
					$('.grid').masonry({
						transitionDuration: '0.4s'
					});
					var postClass = "." + aid
					$(postClass).remove()

					closePrompt('all');
					

					$grid.masonry('reloadItems');
					$grid.masonry('layout');
					setTimeout(function() {
						$('.grid').masonry({
							transitionDuration: 0
						});
					}, 450);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				error("Es ist ein Fehler beim Löschen des Artikels aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		});
	}
}