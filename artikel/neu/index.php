<?php
date_default_timezone_set('Europe/Berlin');
session_start();
if (isset($_SESSION['userGUID'])) {
    $userGUID = $_SESSION['userGUID'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
} else {
  echo "Not logged in!";
  die();
}

include '../../framework/document-start.php';
?>

<head>
  <title>Die Edith</title>

  <!-- include? VVV -->
  <?php include '../../framework/head.php'?>

  <script src="https://cdn.ckeditor.com/ckeditor5/15.0.0/classic/ckeditor.js"></script>
  <script src="neu.js"></script>
  <link rel="stylesheet" type="text/css" href="/artikel/artikel.css">
  <link rel="stylesheet" type="text/css" href="neu.css">
</head>

<body>
  <?php include '../../framework/nav-overlay.php'?>

  <div class="wrapper">

    <h1 contenteditable="true" class="main-title">Unbenanntes Textdokument</h1>

    <div class="article__info">
      <img class="article__info__picture" src="/user/<?php echo $userGUID ?>/pb-small.jpg" alt="profile picture">
      <div class="article__info__textbox">
        <div class="article__info__textbox__name">
          <?php echo $firstname." ".$lastname; ?>
        </div>
        <div class="article__info__textbox__time">
          <?php echo date('d. F') ?>
        </div>
      </div>
    </div>
    <p class="clear"></p>

    <article id="editor">
      <p>Über unsere Waschbecken und Duschen gelangt das Mikroplastik ins Meer. Dort zieht es Gifte an und wird von Tieren gefressen. Eine große Gefahr für die Umwelt!</p>
      <p>Seit ein paar Monaten wasche ich meine Haare mit Roggenmehl und gebrauche somit kein umweltunfreundliches Shampoo mehr. Ich wasche sie ca. alle 4 - 7 mal die Woche und bin sehr zufrieden.</p>
      <figure>
        <img src="../Nachhaltiges-Shampoo/pic1.jpg" alt="Roggenmehl Shampoo">
        <figcaption>So sieht das Roggenmehl Shampoo aus.</figcaption>
      </figure>
      <p>So geht’s:</p>
      <ol>
        <li>ca. 2-3 EL Roggenmehl in ein Schälchen geben</li>
        <li>Wasser hinzu geben</li>
        <li>ruhen lassen (am besten 1-2 Stunden)</li>
        <li>unter der Dusche auf der nassen Kopfhaut verteilen und einmassieren (wie normales Shampoo)</li>
        <li>2-3 Minuten einwirken lassen und gründlich auswaschen</li>
        <li>die trockenen Haare kämmen und so restliche Mehlschuppen (wenn vorhanden) entfernen</li>
        <li>an gesunden und schönen Haaren erfreuen (:</li>
      </ol>
      <p>Mit der Menge könnt und müsst ihr variieren, je nach Haarlänge und ob ihr nur den Ansatz oder eure ganzen Haare waschen wollt. Ich mache es immer in die ganzen Haare, aber der Ansatz würde völlig genügen.</p>
      <p>Außerdem gibt es auch eine Apfelessig – Spülung, die ihr ausprobieren könnt, falls euch die Haare danach zu trocken sind. Ich persönlich habe das noch nicht versucht, da ich so vollkommen zufrieden bin... schaut dafür dann einfach mal auf YouTube nach!</p>
      <p>Auch wird empfohlen einen kurzen Entzug von Shampoo zu machen, bevor man mit Roggenmehl startet, praktisch eine Zeit lang nur mit Wasser zu waschen. Ich allerdings habe es einfach sofort ausprobiert und es war super... Probiert es doch einfach mal aus, wichtig ist nur, dass ihr Roggenmehl nehmt und kein anderes, denn das verklebt die Haare.</p>
    </div>
  </article>
</body>

</html>
