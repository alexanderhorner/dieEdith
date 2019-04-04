<?php
session_start();
if (isset($_SESSION['userid'])) {
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $navinformation = "Hallo ".$firstname." ".$lastname.'! <a class="red" href="logout/">Nicht du?</a>';
} else {
    $navinformation = 'Du bist im Moment nicht angemeldet. <a class="red" href="login/">Anmelden</a>';
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <title>Schülerzeitung: Die Edith</title>
  <?php $version = "10";
  include 'config/basic-framework/head.php';?>
  <script src="homepage.js"></script>
  <link rel="stylesheet" type="text/css" href="homepage.css?v=<?php echo $version ?>">
  <link rel="subresource" href="poll.jpg">
  <link rel="subresource" href="autor.jpg">
  <link rel="prerender" href="login/">
  <link rel="prerender" href="vote/">
</head>

<body>
  <?php if (!isset($_SESSION['userid'])) : ?>
  <?php endif; ?>
  <div id="title-section" class="big">
    <span id="title" class="big">Die Edith</span>
  </div>
  <div id="wrapper">
    <div class="topic-box">
      <?php echo $navinformation?>
    </div>
    <div class="topic-box" id="vote">
      <div class="subheading"><a class="text" href="vote/">Stimme jetzt ab!</a></div>
      <?php if ($firstname == 'Steven' AND $lastname == 'Feldbusch') : ?>
        <img id="image_abstimmung" alt="steven" src="steven.jpg?v=<?php echo rand(0, 999999) ?>">
      <?php elseif ($firstname == 'Dominik' AND $lastname == 'Kudaschow') : ?>
        <img id="image_abstimmung" alt="dominik is gay" src="dodo.jpg?v=<?php echo rand(0, 999999) ?>">
      <?php else : ?>
        <img id="image_abstimmung" alt="poll" src="poll.jpg?v=<?php echo $version ?>">
      <?php endif; ?>
      <p>Strengster Lehrer? Witzigster Lehrer? Schönste Lehrerin? Stimme jetzt in 9 Kategorien für die Lehrer deiner Wahl ab! Ist natürlich komplett anonym und dauert nur ein paar Sekunden. <a href="vote/">Mehr...</a></p>
    </div>
    <!-- <div class="topic-box" id="impressum">
      <div class="subheading"><a class="text" href="vote/">Das Team</a></div>
      <img alt="autor" src="autor.jpg?v=2">
      <p>Dieses Jahr gibt es dank Herr Buckow wieder eine Schüler&shy;zeitung, doch die schreibt sich nicht von selbst. Lerne jetz Autoren, Designer, Cartoonisten und den Rest des Teams, dass für die diesjährige Ausgabe zuständig ist, kennen. <a
          href="">Mehr...</a></p>
    </div> -->
    <div class="topic-box" id="footer"><span><a href="impressum/">Impressum</a> - <a href="datenschutzerklaerung/">Datenschutzerklärung</a></span></div>
  </div>
</body>

</html>
