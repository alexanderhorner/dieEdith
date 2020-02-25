<?php
 

include '../framework/document-start.php';


if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
} else {
    $UID = 'U0000000000';
    $firstname = 'Unbekannter';
    $lastname = 'Nutzer';
}



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
    $publishedon = $row['publishedon'];


    if ($articleState == 'public') {
      $articleData = $row['htmldata'];
    } else {
      echo 'Dieser Artikel wurde noch nicht veröffentlicht.';
      die();
    }
  } else {
    echo 'Artikel wurde nicht gefunden!';
    die();
  }

} else {
  echo 'Kein Artikel spezifiziert!';
  die();
}


?>

<head>
  <title>
    <?php echo $documentTitle ?> - Die Edith
  </title>

  <?php include '../framework/head.php'?>

  <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="/artikel/artikel.css">

</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

  <div class="wrapper">

    <h1 data-AID="<?php echo $AID ?>" class="main-title">
      <?php echo htmlspecialchars($articleName, ENT_QUOTES, 'UTF-8'); ?>
    </h1>

    <div class="article__info">
      <img class="article__info__picture" src="/user/<?php echo $UID ?>/pb-small.jpg" alt="profile picture">
      <div class="article__info__textbox">
        <div class="article__info__textbox__name">
          <?php echo $firstname." ".$lastname; ?>
        </div>
        <div class="article__info__textbox__time">
          Unveröffentlichter Entwurf
        </div>
      </div>
    </div>

    <p class="clear"></p>


    <article class="article"><?php echo $articleData ?></article>
</body>

</html>
