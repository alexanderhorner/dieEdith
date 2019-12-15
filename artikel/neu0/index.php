<?php
date_default_timezone_set('Europe/Berlin');

include '../../framework/document-start.php';


if (isset($_SESSION['userGUID'])) {
    $userGUID = $_SESSION['userGUID'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
} else {
  echo "Not logged in!";
  die();
}
?>

<head>
  <title>Die Edith</title>

  <!-- include? VVV -->
  <?php include '../../framework/head.php'?>

  <script src="rangy-core.js"></script>
  <script src="rangy-classapplier.js"></script>
  <script src="undo.js"></script>
  <script src="medium.min.js"></script>
  <link rel="stylesheet" href="medium.css">

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

    <article class="article" id="editor" contenteditable="true">
    </article>
</body>

</html>
