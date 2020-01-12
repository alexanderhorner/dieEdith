<?php
date_default_timezone_set('Europe/Berlin');

include '../framework/document-start.php';


if (isset($_SESSION['userUUID'])) {
    $userUUID = $_SESSION['userUUID'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
} else {
  echo "Not logged in!";
  die();
}



if (isset($_GET['article'])) {
  $article = htmlentities(mb_convert_encoding($_GET['article'], 'UTF-8', 'UTF-8'), ENT_QUOTES, 'UTF-8');
  $articleName = $article;
  $documentTitle = $article;

  // Connenct to database
  include '../framework/mysqlcredentials.php';

  // Check if Article exists
  $stmntSearch = $pdo->prepare("SELECT UUID FROM articles WHERE name = ?");

  // execute statement and put response into array
  $stmntSearch->execute(array($article));

  // fetch
  $row = $stmntSearch->fetch();

  //check if article is already in database
  if ($stmntSearch->rowCount() > 0) {

    $articleUUID = $row['UUID'];

    // Download Data
    // Check if Article exists
    $stmntDownload = $pdo->prepare("SELECT data, owner FROM articles WHERE UUID = ?");

    // execute statement and put response into array
    $stmntDownload->execute(array($articleUUID));

    // fetch
    $row = $stmntDownload->fetch();
    $owner = $row['owner'];
    if ($owner == $userUUID) {
      $articleData = $row['data'];
    } else {
      echo 'Du hast keine Rechte zu diesem Artikel! Frage den Besitzer oder einen Admin.';
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

  <!-- Editor.js -->
  <script src="/framework/editorjs/editor.js"></script>
  <!-- Editor.js Plugins -->
  <!-- Header -->
  <script src="/framework/editorjs/editorjsHeader.js"></script>
  <!-- List -->
  <script src="/framework/editorjs/editorjslist.js"></script>
  <!-- picture -->
  <script src="/framework/editorjs/editorjssimpleimages.js"></script>
  <!-- inline-code -->
  <script src="/framework/editorjs/editorjsinline-code.js"></script>
  <!-- marker -->
  <script src="/framework/editorjs/editorjsmarker.js"></script>

  <link rel="stylesheet" type="text/css" href="/artikel/artikel.css">
  <link rel="stylesheet" type="text/css" href="editor.css">
  <script src="editor.js"></script>

</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

  <div class="wrapper">

    <h1 data-UUID="<?php echo $articleUUID ?>" contenteditable="true" class="main-title">
      <?php echo $articleName ?>
    </h1>

    <div class="article__info">
      <img class="article__info__picture" src="/user/<?php echo $userUUID ?>/pb-small.jpg" alt="profile picture">
      <div class="article__info__textbox">
        <div class="article__info__textbox__name">
          <?php echo $firstname." ".$lastname; ?>
        </div>
        <div class="article__info__textbox__time">
          <?php echo date('d. F') ?>
        </div>
      </div>
    </div>

    <!-- Save-State -->
    <!-- Saved -->
    <div class="save-state save-state--saved">
      <div class="save-state__icon">
        <i class="material-icons">check_circle_outline</i>
      </div>
      <span class="save-state__text">Gespeichert</span>
    </div>

    <!-- Loading -->
    <div class="save-state save-state--loading">
      <div class="save-state__icon">
        <div class="loadingio-spinner-rolling-rqt9h9gqbtl">
          <div class="ldio-7m1gexeqndq">
            <div></div>
          </div>
        </div>
      </div>
      <span class="save-state__text">Speichern...</span>
    </div>

    <!-- Unsaved -->
    <div class="save-state save-state--unsaved">
      <div class="save-state__icon">
        <i class="material-icons save-state__icon--unsaved">error_outline</i>
      </div>
      <span class="save-state__text">Ungespeicherte Änderungen</span>
    </div>

    <p class="clear"></p>

    <article id="editorjs" class="article"></article>
    <script type="text/javascript">
    const editor = new EditorJS({

      placeholder: 'Schreibe los...',

      data: <?php echo $articleData ?>,

      tools: {
        header: {
          class: Header,
          config: {
            placeholder: 'Überschrift hinzufügen...'
          }
        },
        image: SimpleImage,
        list: List,
        Marker: {
          class: Marker,
          shortcut: 'CMD+SHIFT+M',
        },
        inlineCode: {
          class: InlineCode,
          shortcut: 'CMD+SHIFT+C',
        }
      }
    });
    </script>
</body>

</html>
