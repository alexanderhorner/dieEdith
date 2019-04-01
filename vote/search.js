$(document).ready(function() {

  //Filter
  $("input").on("paste change keyup", function() {
    var value = $(this).val().toLowerCase();
    $("table tr:not(.hidden)").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

});
