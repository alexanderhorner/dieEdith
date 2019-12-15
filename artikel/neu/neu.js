$(document).ready(function() {
  $('html').addClass('editMode')
  var $editor = $('html.editMode .article');

  window.appending = false;

  // when content changes
  $editor.bind("DOMSubtreeModified", function() {


    // update $content
    window.content = $editor.html().replace(/\s/g, '');

    console.log('The Content is: ' + window.content);
    if ((window.content == '' || window.content == '<br>') && window.appending == false ) {
      console.log('correct');
      window.appending = true;

      $editor.html('<p></p>');

      setTimeout(function () {
        window.appending = false;
      }, 50);
      console.log('now');

    }

    // Format text
    $("html.editMode .article *").removeAttr('style');
  });



  $(document).on('keydown', function(e) {



    // prevent unwanted deletion
    if (e.key == "Backspace" || e.key == 'delete') {
      if ($.content == '<p></p>' || $.content == '<p><br></p>') {
        e.preventDefault();
      }
    }

  });

  // prevent unwanted deletion
  $(document).on('cut', function(e) {

    if ($content == '<p></p>' || $content == '<p><br></p>') {
      e.preventDefault();
    }
  });

  // debug
  $(document).on('keydown', function(e) {
    console.warn(e.key);
  });
});
