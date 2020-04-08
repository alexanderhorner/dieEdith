function uploadArticleData(saveData) {
	// Send data
	$.ajax({
		type: 'POST',
		url: 'uploadArticleData.php',
		dataType: 'json',
		data: 'AID=' + encodeURI($('.main-title').attr('data-aid')) + '&data=' + encodeURIComponent(JSON.stringify(saveData)),
		timeout: 10000,
		success: function(data) {
			if (data.request == "success") {
				saveState('saved');
			} else if (data.request == "failed" && data.error == "Session expired") {
				window.saved = false;
				window.saveLockTimer = 6000;
				saveState('unsaved');
				error("Der Artikel konnte nicht gespeichert werden, da du im Hintergrund abgemeldet wurdest. Öffne einen neuen Tab und logge dich ein und versuche dann nochmal zu speichern");
			} else if (data.request == "failed" && data.error == "No Permission") {
				window.saved = false;
				window.saveLockTimer = 6000;
				saveState('unsaved');
				error("Der Artikel konnte nicht gespeichert werden, da du keine Rechte dafür hast. Frage den Besitzer oder versuche es später erneut.");
			} else if (data.request == "failed" && data.error == "Mysql request(s) failed") {
				window.saved = false;
				window.saveLockTimer = 6000;
				saveState('unsaved');
				error('Der Artikel konnte nicht gespeichert werden, da die gesendeten Daten ein falsches Format haben ("Mysql request(s) failed"). Frage einen Admin oder versuche es später erneut.');
			} else {
				window.saved = false;
				window.saveLockTimer = 6000;
				saveState('unsaved');
				error("Der Artikel konnte nicht gespeichert werden (" + data.error + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			window.saved = false;
			saveState('unsaved');
			window.saveLockTimer = 6000;
			error("Der Artikel konnte nicht gespeichert werden (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
		}
	});
}

function publishArticle() {
	
	saveData();

	// Send data
	$.ajax({
		type: 'POST',
		url: '/framework/publishArticle.php',
		dataType: 'json',
		data: 'AID=' + encodeURI($('.main-title').attr('data-aid')),
		timeout: 10000,
		success: function(data) {
			if (data.request == "failed" && data.error == "Article doesnt exist") {
				error("Der Artikel konnte nicht veröffentlicht werden, da er nicht gefunden wurde.");
			} else if (data.request == "failed" && data.error == "No Permission") {
				error("Der Artikel konnte nicht veröffentlicht werden, da du keine Rechte dafür hast. Frage den Besitzer oder versuche es später erneut.");
			} else if (data.request == "failed" && data.error == "unknown MYSQL error") {
				error('Der Artikel konnte nicht veröffentlicht werden, da ein unbekannter Fehler auftrat.');
			} else if (data.request == "failed" && data.error == "Already public") {
				error('Der Artikel ist schon öffentlich');
			} else if (data.request == "failed") {
				error("Der Artikel konnte nicht veröffentlicht werden (" + data.error + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			error("Der Artikel konnte nicht veröffentlicht werden (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
		}
	});
}


window.saved = true;

setInterval(function() {

	if (window.saveLockTimer > 0) {
		window.saveLockTimer -= 50;
	} else if (window.saved == false) {
		saveData();
	}

}, 50);

$(window).on("beforeunload", function () {
	saveData();
})

function saveData() {
	window.saved = true;
	// Save
	saveState('loading');
	editor.save().then((outputData) => {
		uploadArticleData(outputData);
	}).catch((error1) => {
		console.log('Saving failed: ', error1)
		saveState('unsaved');
		window.saveLockTimer = 6000;
		error('Der Artikel konnte nicht gespeichert werden (JavaScript-Fehler). Versuche es später erneut.')
		window.saved = false;
	});
}

function saveState(state) {
	if (state == 'saved') {
		$('.save-state, .save-state--saved').css('display', '');
		$('.save-state:not(.save-state--saved)').css('display', 'none');
	} else if (state == 'loading') {
		$('.save-state, .save-state--loading').css('display', '');
		$('.save-state:not(.save-state--loading)').css('display', 'none');
	} else if (state == 'unsaved') {
		$('.save-state, .save-state--unsaved').css('display', '');
		$('.save-state:not(.save-state--unsaved)').css('display', 'none');
	} else {
		return 'Unvalid save state';
	}
}

$(window).on('load', function() {
editor.isReady
	.then(() => {
		console.log('Editor.js is ready to work!')
		
		MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

		var observer = new MutationObserver(function(mutations, observer) {
			
			window.saveLockTimer = 1500;
			window.saved = false;
			saveState('unsaved');
			$("div:has(> div:has(> .image-tool__tune))").remove();
			

		});

		observer.observe($(".article").get(0), {
			childList: true,
			attributes: false,
			characterData: true,
			subtree: true,
			attributeOldValue: false,
			characterDataOldValue: false,
		});
	})
});


$(document).ready(function() {

	// save button
	$('.save-state').click(function() {
		saveState('loading');
		saveData();
	});

	// periodically search for 
	setInterval(function() {
		
	}, 100);
});

