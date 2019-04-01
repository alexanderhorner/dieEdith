$(document).ready(function() {

  //category selection
  $(".category-box").click(function(event) {

    // fetch real target
    var target = $(event.target);
    if ($(target).is('span')) {
      target = $(target).parent().parent();
    } else if ($(target).is('.category-box__centering')) {
      target = $(target).parent();
    }

    // Reset Stlyes and window.(and show vote sec)
    window.teachername = "";
    window.teacherid = "";
    $("td").removeClass("selected");
    $("#submit").css('opacity', '0');
    setTimeout(function() {
      $("#submit").css('display', 'none');
    }, 200);

    $("table tr").removeClass("hidden");
    $("#vote-section").css('display', 'block');
    setTimeout(function() {
      $("#vote-section").css('opacity', '1');
    }, 10);
    $(".category-box").removeClass("selected");
    $(".category-box__vote").css("color", "#85C7F2");

    // Stlye real target
    $(target).find(".category-box__vote").css("color", "#636363");
    $(target).addClass("selected");

    // Save id (category) of real target
    window.category = target.attr('id');

    if (window.category == "attraktivstefrau") {
      $("table tr:contains('Herr')").addClass("hidden");
    }
    if (window.category == "attraktivstermann") {
      $("table tr:contains('Frau')").addClass("hidden");
      $("table tr:contains('Dr.')").addClass("hidden");
    }
    // Sroll to input
    $([document.documentElement, document.body]).animate({
      scrollTop: $("input").offset().top - 75
    }, 500);
  });


  //name selection
  $(".fullname").click(function(event) {
    // reset styles
    $("td").removeClass("selected");

    var target = $(event.target);
    //style target
    $(target).addClass("selected");

    //save id and name of target
    window.teacherid = $(target).closest('tr').children('td:nth-child(1)').text();
    window.teachername = $(target).text();
    //show button
    $("#submit").css('display', 'block');
    setTimeout(function() {
      $("#submit").css('opacity', '1');
    }, 10);
  });
});
