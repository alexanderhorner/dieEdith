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
}

window.prependedPostCount = 0;

$(document).ready(function() {

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

					error("Es ist ein Fehler beim posten aufgetreten (" + data.error['category'] + ': ' + data.error['description'] + "). Versuche es später erneut.");
				} else {

					// insert
					var today = new Date();
					var date = today.getDate() + '.' + (today.getMonth() + 1) + '.' + today.getFullYear();

					var $items = $('<div id="prependedPost' + window.prependedPostCount + '" data-pid="' + data.PID + '" data-postedOn="' + Math.floor(Date.now() / 1000 + 3600) + '" class="card card--post card--post--isOwner ' + data.PID + '"><div onclick="deletePost(\'' + data.PID + '\')" class="card__delete"><i class="material-icons">delete_forever</i></div><div class="post__text"><span>' + htmlEntities($('.card--new-post__textarea').val()) + '</span></div><div data-timeago="' + Math.floor(Date.now()) + '" class="card__time"></div></div>');
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
				error("Es ist ein Fehler beim posten aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");

				// Enable Button
				$(".card--new-post__submit").prop("disabled", false);
				$(".card--new-post__submit").attr("value", "Posten");
			}
		});

	});

});

// delete Post
function deletePost(pid) {
	$('html').addClass('prompt--delete-post--shown');
	$('body').scrollLock('enable');
	$
	window.promptFunction = function() {
		$.ajax({
			type: 'POST',
			url: '/framework/deletePost.php',
			dataType: 'json',
			data: 'PID=' + pid,
			timeout: 10000,
			success: function(data) {
				if (data.status != "successful") {
					error("Es ist ein Fehler beim Löschen des Posts aufgetreten (" + data.error['category'] + ": " + data.error['description'] + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
				} else {
					var postClass = "." + pid;
					$grid.isotope('remove', $(postClass));
					$grid.isotope('layout');

					closePrompt('all');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				error("Es ist ein Fehler beim Löschen des Posts aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		});
	}
}

$(window).on('resize', function() {
	$grid.isotope('layout');
});

// delete article
function deleteArticle(aid) {
	$('html').addClass('prompt--delete-article--shown');
	$('body').scrollLock('enable');
	

	window.promptFunction = function() {
		$('.prompt--delete-article .prompt__btn-container__btn').prop("disabled", true);
		$.ajax({
			type: 'POST',
			url: '/framework/deleteArticle.php',
			dataType: 'json',
			data: 'AID=' + aid,
			timeout: 10000,
			success: function(data) {
				if (data.status != "successful") {
					$('.prompt--delete-article .prompt__btn-container__btn').prop("disabled", false);
					error("Es ist ein Fehler beim Löschen des Artikels aufgetreten (" + data.error['category'] + ": " + data.error['description'] + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
				} else {
					$('.prompt--delete-article .prompt__btn-container__btn').prop("disabled", false);
					closePrompt('all');
					var postClass = "." + aid;
					$grid.isotope('remove', $(postClass));
					$grid.isotope('layout');
					

					closePrompt('all');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('.prompt--delete-article .prompt__btn-container__btn').prop("disabled", false);
				error("Es ist ein Fehler beim Löschen des Artikels aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		});
	}
}

// card click
$(document).on('click', '.card.card--article', function(event) {

	// targets
	var $target = $(event.target);
	var $currentTarget = $(event.currentTarget);
	
	if ($target.parents('.card__delete').length || $target.is('.card__delete')) { // if card__delete
		var aid = $currentTarget.data('aid');
		deleteArticle(aid);
	} else { // if card
		if ($currentTarget.hasClass('card--article--draft')) {
			var titleLink = $currentTarget.data('titlelink');
			linkTo('/editor/' + titleLink);
		} else {
			var titleLink = $currentTarget.data('titlelink');
			linkTo('/artikel/' + titleLink);
		}
		
	}

});

class CardMenu {
    
    constructor() {

        this.$openedMenu = "";
        this.$openedMenuButton = "";

        var self = this;

        this.btnPos = {
            x: function() {
                return self.$openedMenuButton.offset().left;
            },
            y: function() {
                return self.$openedMenuButton.offset().top;
            }
        }

        // update position on resize
        
        // openMenuListener
        $grid.on('click', '.card__corner-menu', function(event) {
            self.$openedMenuButton = $(event.currentTarget);
            self.$openedMenu = $("." + $(self.$openedMenuButton).parent().data("id") + ".card__corner-menu__container-wrapper");
            self.renderMenu();
        });

    }


    renderMenu() {
        var self = this;
        
        // open active menu
        self.$openedMenu.addClass("card__corner-menu__container-wrapper--active");

        // render Position
        this.updatePosition()


        // closeMenuListener
        $('html').click(function() {
            $(".card__corner-menu__container-wrapper").removeClass("card__corner-menu__container-wrapper--active")
            self.$openedMenuButton = '';
            self.$openedMenu = '';
        });
        $('html').on('click', '.card__corner-menu', function(event) {
            event.stopPropagation();
            $(".card__corner-menu__container-wrapper").not(self.$openedMenu).removeClass("card__corner-menu__container-wrapper--active")
        });
        $('html').on('click', '.card__corner-menu__container-wrapper--active', function(event) {
            event.stopPropagation();
        });

        $(window).on('resize', function(){
            if (self.$openedMenu == '') {
                return false
            } else { 
                self.updatePosition();
            }
        });
        
    }

    updatePosition() {
        var self = this;

        self.$openedMenu.css("left", Math.round(self.btnPos.x()));
        self.$openedMenu.css("top", Math.round(self.btnPos.y()));
    }

}