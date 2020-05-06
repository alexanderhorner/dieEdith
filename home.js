$(document).ready(function() {
	//initialize Isotope
	$grid = $('.grid').isotope({
		itemSelector: '.card',
		transitionDuration: 0,
		layoutMode: 'masonry',
		masonry: {
			percentPosition: true
		},
		getSortData: {
			postedOn: '[data-postedon] parseInt'
		},
		sortBy: 'postedOn',
		sortAscending: false
	});

	var infiniteScroll = new InfiniteScroll;
	var cardMenu = new CardMenu;
});

class InfiniteScroll {

  constructor() {
		this.endOfPage = false;
		this.pageLoading = false;

		this.loadedPosts = {
			elements: function() {
				return $('.grid > .card');
			},
			ammount: function() {
				return this.elements().length;
			},
			idArray: function() {
				var idReturnArray = new Array();
				this.elements().each(function(i, $obj) {
					idReturnArray.push($($obj).data('pid'));
				});
				return idReturnArray;
			}
		} 

		var self = this;

    // Check every x amount of time
		setInterval(function() {
			// exit function if page ends
			if (self.endOfPage == true) {
				$('.loading').remove();
				return "End of Page!"
			}

			// only execute when loading is complete
			if (self.pageLoading === false) {

				//get the scroll position of the bottom of the page
				var screenBottomPos = $(window).scrollTop() + $(window).height();
				var gridBottomPos = $grid.offset().top + $grid.height();
				var distanceToBottom = gridBottomPos - screenBottomPos;
				
				// if bottom of screen is close enough to the page bottom
				if (distanceToBottom < 300) {
					// fetch next posts
					self.loadPosts(self.loadedPosts.idArray());
				}
			}
		}, 50);
	}

	loadPosts(alreadyLoadedPosts, ammount = 15) {
		var self = this;

		self.pageLoading = true;

		// debug test to check if page load halting code works (so it waits for one page to be loaded to load the next)
		// window.PosLast = window.PosNow;
		// window.PosNow = $(window).scrollTop();
		// console.log(window.PosNow - window.PosLast);
		// window.loadedLast = window.loadedNow;
		// window.loadedNow = this.loadedPosts.ammount();
		// console.log(window.loadedNow - window.loadedLast);

		$.ajax({
			type: 'POST',
			url: 'fetchposts.php',
			dataType: 'json',
			data: 'ammount=' + ammount + '&alreadyLoadedPosts=' + encodeURIComponent(JSON.stringify(alreadyLoadedPosts)),
			timeout: 30000,
			success: function(data) {
				// check if request was succesfull
				if (data.request != "failed") {

					// check if data is empty
					if (data.content.posts == "") {
						self.endOfPage = true;
					} else {
						var $items = $(data.content.posts);
						
						// insert data
						$grid.isotope('insert', $items)
							.ready(function() {

								// Render time
								var selector = '.' + data.requestIdentifier;
								var nodes = document.querySelectorAll(selector);
								timeago.render(nodes, 'de');

								// set pageLoading state to false
								self.pageLoading = false;
							})

						// layout isotope grid when images loaded
						$grid.imagesLoaded().progress(function(instance) {
							$grid.isotope('layout');
						})

						$("body").append(data.content.cornerMenus);
					}
				} else {
					self.pageLoading = false;
					error("Es gab ein Problem beim Laden der Posts (" + data.error + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				self.pageLoading = false;
				error("Es gab ein Problem beim Laden der Posts (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		});
	}
	
}

// browser debug for infinite scroll
// var n = 1;
// $(document).on('keypress', function(e) {
// 	if (e.key === "m") {
// 		window.postLast = window.postNow;
// 		window.postNow = homescreen.loadedPosts.ammount();
// 		var deltaPost = window.postNow - window.postLast;
// 		console.log('Difference in posts: ' + deltaPost);
// 		console.log("KEY PRESS " + n);
// 		n += 1;
// 		$("html, body").animate({ scrollTop: $(document).height() }, 0);
// 	}
// });

class CardMenu {
	
	constructor() {

		this.$openedMenu = "";
		this.$openedMenuButton = "";

		var self = this;

		// open menu
		$grid.on('click', '.card__corner-menu', function(event) {
			self.$openedMenuButton = $(event.currentTarget);
			
			self.$openedMenu = $("." + $(self.$openedMenuButton).parent().data("id") + ".card__corner-menu__container-wrapper");
			
			self.renderMenu(self.$openedMenu)
		});

		// close menu
		$('html').click(function() {
			$(".card__corner-menu__container-wrapper").removeClass("card__corner-menu__container-wrapper--active")
		});
		$('html').on('click', '.card__corner-menu, .card__corner-menu__container-wrapper--active', function(event) {
				event.stopPropagation();
		});

		this.btnPos = {
			x: function() {
				return self.$openedMenuButton.offset().left;
			},
			y: function() {
				return self.$openedMenuButton.offset().top;
			}
		}

	}

	renderMenu(idOfPost) {
		var self = this;
		
		// open active menu
		self.$openedMenu.addClass("card__corner-menu__container-wrapper--active");

		// render Position
		this.updatePosition(idOfPost)

		
	}

	updatePosition(idOfPost) {
		var self = this;

		self.$openedMenu.css("left", Math.round(self.btnPos.x()));
		self.$openedMenu.css("top", Math.round(self.btnPos.y()));
	}

}

$(window).on('resize', function() {
	$grid.isotope('layout');
});

// card click
$(document).on('click', '.card.card--article', function(event) {

	// targets
	var $target = $(event.target);
	var $currentTarget = $(event.currentTarget);
	
	if ($target.parents('.card__info').length || $target.is('.card__info')) { // if card-info
		var username = $currentTarget.data('username');
		linkTo('/profil/' + username);
	} else if ($target.parents('.card__corner-menu').length || $target.is('.card__corner-menu')) { // if card__delete
		// Nothing, class cards Contructor handles it
	} else { // if card
		var titleLink = $currentTarget.data('titlelink');
		linkTo('/artikel/' + titleLink);
	}

});


// delete Post
function deletePost(pid) {
	$('html').addClass('prompt--delete-post--shown');
	$('body').scrollLock('enable');
	$('')
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
					$grid.isotope({
						transitionDuration: '0.4s'
					});
					var $post = $("." + pid);
					
					$grid.isotope('remove', $post).isotope('layout');

					closePrompt('all');
					
					setTimeout(function() {
						$grid.isotope({
							transitionDuration: 0
						});
					}, 450);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				error("Es ist ein Fehler beim Löschen des Posts aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		});
	}
}

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
					$grid.isotope({
						transitionDuration: '0.4s'
					});
					var $post = $("." + aid);
					
					$grid.isotope('remove', $post).isotope('layout');

					closePrompt('all');
					
					setTimeout(function() {
						$grid.isotope({
							transitionDuration: 0
						});
					}, 450);
					

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