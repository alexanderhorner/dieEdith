$("body").keydown(function(e) {
  var zKey = 90;
  if ((e.ctrlKey || e.metaKey) && e.keyCode == zKey) {
    e.preventDefault();
    return false;
  }
});

function uploadData(saveData) {
  // Send data
  $.ajax({
    type: 'POST',
    url: 'uploadData.php',
    dataType: 'json',
    data: 'articleUUID=' + encodeURI($('.main-title').attr('data-UUID')) + '&data=' + encodeURI(JSON.stringify(saveData)),
    timeout: 30000,
    success: function(data) {
      saveState('saved');
      if (data.request == "failed" && data.error == "Session expired") {
        window.saved = false;
        saveState('unsaved');
        error("Der Artikel konnte nicht gespeichert werden, da du im Hintergrund abgemeldet wurdest. Öffne einen neuen Tab und logge dich ein und versuche dann nochmal zu speichern");
      } else if (data.request == "failed" && data.error == "No Permission") {
        error("Der Artikel konnte nicht gespeichert werden, da du keine Rechte dafür hast. Frage den Besitzer oder versuche es später erneut.");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      window.saved = false;
      saveState('unsaved');
      error("Der Artikel konnte nicht gespeichert werden (" + textStatus + "). Überprüfe deine Internetverbindung und probiere es später erneut.");
    }
  });
}

window.saveLockTimer = 1000;
window.saved = true;

setInterval(function() {

  if (window.saveLockTimer > 0) {
    window.saveLockTimer -= 50;
  } else if (window.saved == false) {
    saveData();
  }

}, 50);

function saveData() {
  window.saved = true;
  // Save
  saveState('loading');
  editor.save().then((outputData) => {
    uploadData(outputData);
  }).catch((error1) => {
    console.log('Saving failed: ', error1)
    saveState('unsaved');
    error('Der Artikel konnte nicht gespeichert werden (JavaScript-Fehler). Probiere es später erneut.')
    window.saved = false;
  });
}

function saveState(state) {
  if (state == 'saved') {
    $('.save-state, .save-state--saved').css('display', 'inline-block');
    $('.save-state:not(.save-state--saved)').css('display', 'none');
  } else if (state == 'loading') {
    $('.save-state, .save-state--loading').css('display', 'inline-block');
    $('.save-state:not(.save-state--loading)').css('display', 'none');
  } else if (state == 'unsaved') {
    $('.save-state, .save-state--unsaved').css('display', 'inline-block');
    $('.save-state:not(.save-state--unsaved)').css('display', 'none');
  } else {
    return 'Unvalid save state';
  }
}

$(window).on('load', function() {
  MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

  var observer = new MutationObserver(function(mutations, observer) {

    window.saveLockTimer = 1000;
    window.saved = false;
    saveState('unsaved');
    document.title = $('.main-title').text() + ' - Die Edith';

  });

  observer.observe(document, {
    childList: false,
    attributes: false,
    characterData: true,
    subtree: true,
    attributeOldValue: false,
    characterDataOldValue: false,
  });
});

$(document).ready(function() {
  $('.save-state').click(function() {
    saveState('loading');
    saveData();
  });
});
