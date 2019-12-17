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

  <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">

  <!-- Editor.js -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
  <!-- Editor.js Plugins -->
  <!-- Header --><script src="editorHeader.js"></script>
  <!-- List --><script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
  <!-- picture --><script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script>

  <script type="module" src="neu.js"></script>
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

    <article id="editorjs" class="article"></article>
</body>

</html>
