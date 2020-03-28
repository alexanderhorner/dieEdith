$(document).ready(function() {
	//initialize Isotope
	$grid = $('.grid').isotope({
		itemSelector: '.card',
		transitionDuration: 0,
		layoutMode: 'masonry',
		masonry: {
			percentPosition: true
		}
	});

	homescreen = new infiniteScroll;
});

class infiniteScroll {

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

		// // debug test to check if page load halting code works (so it waits for one page to be loaded to load the next)
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
					if (data.content == "") {
						self.endOfPage = true;
					} else {
						var $items = $(data.content);
						
						// insert data
						$grid.isotope('insert', $items)
							.ready(function() {
								$grid.isotope('reloadItems');
								$grid.isotope('layout');

								// Render time
								var selector = '.' + data.requestIdentifier;
								var nodes = document.querySelectorAll(selector);
								timeago.render(nodes, 'de');

								// set pageLoading state to false
								self.pageLoading = false;
							})

						// layout isotope grid when images loaded
						// $grid.imagesLoaded().progress(function(instance) {
						// 	$grid.isotope('reloadItems');
						// 	$grid.isotope('layout');
						// })
					}
				} else {
					self.pageLoading = false;
					error("Es gab ein Problem beim Laden der Posts (" + data.error + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				this.pageLoading = false;
				error("Es gab ein Problem beim Laden der Posts (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		});
	}
	
}

$(window).on('resize', function() {
	$grid.isotope('layout');
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