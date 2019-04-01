$(document).ready(function() {

  $('#submit').click(function() {
    //Check if all atributes are given
    if (window.teacherid != ""
      & window.category != "") {

      // Scroll back to top
      $('body,html').animate({
        scrollTop: 0
      }, 500);

      // Reset styles
      $('#vote-section').css('opacity', '0');

      setTimeout(function() {
        $('#vote-section').css('display', 'none');
      }, 500);

      $('#submit').css('opacity', '0');

      setTimeout(function() {
        $('#submit').css('display', 'none');
      }, 200);

      $(".category-box").removeClass("selected");
      $(".category-box__vote").css("color", "#85C7F2");
      $("td").removeClass("selected");

      // clear input field
      setTimeout(function() {
        $("input").val('');
        $('input').trigger("keyup");
      }, 510);

      // log all saved values
      console.log("You voted for \n" + window.teachername + " (id: " + window.teacherid + ") \nin the category \n" + window.category);

      //id of category
      var obj = "#" + window.category

      // update "you voted for"-text
      var text1 = "Du hast für " + window.teachername + " gestimmt.";
      $(obj).find(".category-box__vote").text(text1);

      //pass vote to processing php file
      $.ajax({
        type: 'POST',
        url: 'transmit-vote.php',
        dataType: 'json',
        data: {
          category: window.category,
          teacherid: window.teacherid
        }
      });
    }
  });
});
