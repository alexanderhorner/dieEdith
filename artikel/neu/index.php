<?php
date_default_timezone_set('Europe/Berlin');
session_start();
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
} else {
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
    <textarea name="content" id="editor">
    Artikel
    </textarea>
  </div>
</body>

</html>
