<?php
include '../../framework/document-start.php';

date_default_timezone_set('Europe/Berlin');

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
} else {
    die();
}
?>

<head>
  <title>Die Edith</title>

  <!-- include? VVV -->
  <?php include '../../framework/head.php'?>

  <script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/balloon/ckeditor.js"></script>
  <script src="neu.js"></script>
  <link rel="stylesheet" type="text/css" href="/artikel/artikel.css">
  <link rel="stylesheet" type="text/css" href="neu.css">
</head>

<body>
  <?php include '../../framework/nav-overlay.php'?>

  <div class="wrapper">

    <h1>Solardorf Herrneied</h1>

    <div class="article__info">
      <img class="article__info__picture" src="/user/<?php echo $userid ?>/pb-small.jpg" alt="profile picture">
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
    <div class="article">
      <p>In einem kleinen Dorf, nahe Parsberg wird etwas für die Energiewende getan: der Elektroautobesitzer Martin Selch, der den Solarstammtisch Herrnried ins Leben gerufen hat, möchte die Leute wachrütteln. Für seinen Verdienst, ihr
        Umweltbewusstsein zu schärfen, hat er auch schon Preise erhalten.</p>
      <p>Martin Selch ist sich sicher: Energietechnisch ist vieles im Umbruch und es verändert sich auch einiges zum Positiven, beispielsweise erzeugt Herrn-ried 150 Prozent mehr Strom, als dort benötigt wird. Dennoch muss deutlich mehr für die
        Energiewende getan werden, um von umweltschädigenden Energien wegzukommen. Sei es die 10-H Regelung (Windrad muss so weit von einem Ort weg sein wie die 10-fache Höhe des Windrads) zu beenden oder mehr Photovoltaik-Anlagen auf Freiflächen
        zu bauen.</p>
    </div>
  </div>
</body>

</html>
