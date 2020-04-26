// delete article
function deleteArticle(aid) {
	$('html').addClass('prompt--delete-post--shown');
	$('body').scrollLock('enable');
	$('.prompt--delete-article .prompt__btn-container__btn').prop("disabled", true);

	window.promptFunction = function() {
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
					window.location = "../profil/myprofile#entwuerfe";
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('.prompt--delete-article .prompt__btn-container__btn').prop("disabled", false);
				error("Es ist ein Fehler beim Löschen des Artikels aufgetreten (" + textStatus + "). Überprüfe deine Internetverbindung und versuche es später erneut.");
			}
		});
	}
}