// Set up variables
window.initComplete = false;

setTimeout(function() {
	if (window.initComplete == false) {
		warning("Das Laden der Seite dauert länger als erwartet.")
	}
}, 10000);

$(document).ready(function() {

	//initialize Isotope
	$grid = $('.grid').isotope({
		itemSelector: '.card',
		transitionDuration: 0,
		layoutMode: 'masonry',
		// layout mode options
		masonry: {
			percentPosition: true
		}
	});

	fetchPage("init");
	infiniteScrollLoop()
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

// infinite Scroll
function infiniteScrollLoop() {

	// Check every x amount of time
	setInterval(function() {

		// only execute when initialization is complete
		if (window.initComplete == true) {

			// exit function if page ends
			if (window.nextPage == "end") {
				$('.loading').remove();
				return "End of Page!"
			}

			//get the scroll position of the bottom of the page
			var scrollBottom = $(window).scrollTop() + $(window).height();

			var gridBottom = $grid.offset().top + $grid.height();

			// get the distance between viewport bottom and page bottom
			var distanceToBottom = gridBottom - scrollBottom;

			// if bottom of screen is close enough to the page bottom
			if (distanceToBottom < 300) {
				// fetch next page
				fetchPage(window.nextPage);
			}
		}

	}, 50);
}

// Array saves all loaded Pages
window.loadedPages = [];

function fetchPage(page) {
	// Saves Page Loading state
	window.pageLoading = true;

	//Check page
	if (typeof page === 'number') {
		var loadpage = page;
	} else if (page == 'init') {
		var loadpage = 1;
	} else if (page == 'next') {
		var loadpage = Math.max.apply(Math, window.loadedPages) + 1;
	} else {
		return 'unvalid page!';
	}

	// Check if page is already loaded
	if (window.loadedPages.indexOf(loadpage) >= 0) {
		return 'already loaded!';
	} else {
		window.loadedPages.push(loadpage);
	}

	// fetch posts
	$.ajax({
		type: 'GET',
		url: 'fetchposts.php',
		dataType: 'json',
		data: 'page=' + loadpage,
		timeout: 30000,
		success: function(data) {

			// check if request was succesfull
			if (data.request != "failed") {

				// check if data is empty
				if (data.content != "") {
					$items = $(data.content);
					
					// Append data
					$(".grid").append($items)
						.isotope('appended', $items)
						.ready(function() {

							// after append when dom is ready to manipulate
							// relayout items after appending
							$grid.isotope('reloadItems');
							$grid.isotope('layout');

							// Save next Page
							window.nextPage = loadpage + 1;

							// Render time
							var selector = '.' + data.requestIdentifier;
							var nodes = document.querySelectorAll(selector);
							timeago.render(nodes, 'de');

							if (page == "init") {
									// Mark init as complete
									window.initComplete = true;
							}
						})

					// set pageLoading state to false
					window.pageLoading = false;

					// layout isotope grid when images loaded
					// $grid.imagesLoaded().progress(function(instance) {
					// 	$grid.isotope('reloadItems');
					// 	$grid.isotope('layout');
					// })

				} else {
					// Save next Page
					window.nextPage = 'end';
				}
			} else {
				error("Es gab ein Problem beim Laden der Posts (" + data.error + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}

		},
		error: function(jqXHR, textStatus, errorThrown) {

			for (var i = 0; i < window.loadedPages.length; i++) {
				if (window.loadedPages[i] === loadpage) {
					window.loadedPages.splice(i, 1);
				}
			}
			error("Es gab ein Problem beim Laden der Posts (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
		}
	});
}



$(window).on('resize', function() {
	$grid.isotope('layout');
});
