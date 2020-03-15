<?php
 

include '../framework/document-start.php';


if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
} else {
  header("Location: /login/");
  die();
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
    $stmntDownload = $pdo->prepare("SELECT jsondata, owner FROM articles WHERE AID = ?");

    // execute statement and put response into array
    $stmntDownload->execute(array($AID));

    // fetch
    $row = $stmntDownload->fetch();
    $owner = $row['owner'];
    if ($owner == $UID) {
      $articleData = $row['jsondata'];
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

  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Editor.js -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
  <!-- Editor.js Plugins -->
  <!-- Header -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
  <!-- List -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
  <!-- picture -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
  <!-- marker -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
  <!-- link -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script>

  <link rel="stylesheet" type="text/css" href="/artikel/artikel.css">
  <link rel="stylesheet" type="text/css" href="editor.css">
  <script src="editor.js"></script>

</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

  <div class="wrapper">

    <div class="editor-information">
      <!-- Save-State -->
      <!-- Saved -->
      <div class="save-state save-state--saved">
        <div class="save-state__icon">
          <i class="material-icons">check_circle_outline</i>
        </div>
        <span class="save-state__text">Gespeichert</span>
      </div>

      <!-- Loading -->
      <div class="save-state save-state--loading" style="display: none">
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
      <div class="save-state save-state--unsaved" style="display: none">
        <div class="save-state__icon">
          <i class="material-icons save-state__icon--unsaved">error_outline</i>
        </div>
        <span class="save-state__text">Ungespeicherte Änderungen</span>
      </div>


      <!-- Publiush -->
      <button class="publish-btn" onclick="publishArticle()">Veröffentlichen</button>

    </div>

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


    <article id="editorjs" class="article"></article>
    <script type="text/javascript">
    const editor = new EditorJS({

      placeholder: 'Schreibe los...',

      data: <?php echo $articleData ?>,

      logLevel: 'ERROR',
      
      tools: {
        header: {
          class: Header,
          config: {
            placeholder: 'Überschrift hinzufügen...',
            levels: [2, 3, 4],
            defaultLevel: 3
          }
        },
        image: {
          class: ImageTool,
          config: {
            endpoints: {
              byFile: 'uploadPicture.php', // Your backend file uploader endpoint
              byUrl: 'fetchPictureByUrl.php', // Your endpoint that provides uploading by Url
            },
            captionPlaceholder: "Beschreibung des Bildes",
            additionalRequestData: {
              'AID': '<?php echo $AID ?>'
            }
          }
        },
        list: {
          class: List,
          inlineToolbar: true
        },
        marker: {
          class: Marker,
          shortcut: 'CMD+SHIFT+M',
        },
        
      }
    });
    </script>
</body>

</html>
