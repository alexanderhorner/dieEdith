<?php
include '../framework/document-start.php';

if (isset($_GET['article'])) {
  $article = $_GET['article'];
  $articleName = $article;
  $documentTitle = $article;

  // Connenct to database
  include '../framework/mysqlcredentials.php';

  // Check if Article exists
  $stmntSearch = $pdo->prepare("SELECT AID FROM articles WHERE title = ?");

  // execute statement and put response into array
  $stmntSearch->execute(array($article));

  // fetch
  $row = $stmntSearch->fetch();

  //check if article is already in database
  if ($stmntSearch->rowCount() > 0) {

    $AID = $row['AID'];

    // Download Data
    // Check if Article exists
    $stmntDownload = $pdo->prepare("SELECT `htmldata`, `owner`, `status`, `publishedon` FROM articles WHERE AID = ?");

    // execute statement and put response into array
    $stmntDownload->execute(array($AID));

    // fetch
    $row = $stmntDownload->fetch();
    $articleState = $row['status'];
    $owner = $row['owner'];
    $publishedon = $row['publishedon'];


    if ($articleState == 'public') {
      $articleData = $row['htmldata'];

      // get name of owner
      $stmntowner = $pdo->prepare("SELECT username, firstname, lastname FROM user WHERE UID = ?");

      // execute statement
      $stmntowner->execute(array($owner));

      // fetch
      $row = $stmntowner->fetch();
      if ($stmntowner->rowCount() > 0) {
          $username = $row['username'];
          $firstname= $row['firstname'];
          $lastname = $row['lastname'];
          $fullname = $firstname." ".$lastname;
      } else {
          $username = 'unknown.user';
          $firstname= $row['Unknown'];
          $lastname = $row['User'];
          $fullname = $firstname." ".$lastname;
      }

    } else {
      header('Location: /');
      die();
    }
  } else {
    header('Location: /');
    die();
  }

} else {
  header('Location: /');
  die();
}


?>

<head>
  <title>
    <?php echo $documentTitle ?> - Die Edith
  </title>

  <?php include '../framework/head.php'?>

  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <script src="/framework/timeago.js"></script>

  <link rel="stylesheet" type="text/css" href="/artikel/artikel.css">

</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

  <div class="wrapper">

    <h1 data-AID="<?php echo $AID ?>" class="main-title">
      <?php echo htmlspecialchars($articleName, ENT_QUOTES, 'UTF-8'); ?>
    </h1>

    <div class="article__info">
      <img class="article__info__picture" src="/user/<?php echo $owner ?>/pb-small.jpg" alt="profile picture">
      <div class="article__info__textbox">
        <div class="article__info__textbox__name">
          <?php echo $fullname; ?>
        </div>
        <div class="article__info__textbox__time" data-timeago="<?php echo $publishedon ?>"></div>
      </div>
    </div>
    <script>
      var nodes = document.querySelectorAll('.article__info__textbox__time');
      timeago.render(nodes, 'de');
    </script>

    <p class="clear"></p>


    <article class="article"><?php echo $articleData ?></article>
</body>

</html>
