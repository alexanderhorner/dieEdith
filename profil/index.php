<?php include '../framework/document-start.php';

if (isset($_GET['user'])) {
  $username = $_GET['user'];


  // Connenct to database
  include '../framework/mysqlcredentials.php';

  // Check if Article exists
  $stmntSearch = $pdo->prepare("SELECT UUID, firstname, lastname, role, description FROM user WHERE username = ?");

  // execute statement and put response into array
  $stmntSearch->execute(array($username));

  // fetch
  $row = $stmntSearch->fetch();

  //check if user exists
  if ($stmntSearch->rowCount() > 0) {
    // Set up variables
    $pageOwnerUUID = $row['UUID'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $fullname = $firstname.' '.$lastname;
    $role = $row['role'];
    $description = $row['description'];

    if (isset($_SESSION['userUUID'])) {
      if ($_SESSION['userUUID'] == $pageOwnerUUID) {
        $isOwnProfile = "true";
      } else {
        $isOwnProfile = "false";
      }
    } else {
      $isOwnProfile = "false";
    }

  } else {
    // Set up variables
    $isOwnProfile = "false";
    $pageOwnerUUID = '00000000-0000-0000-000000000000';
    $firstname = 'Unbekannter';
    $lastname = 'Nutzer';
    $fullname = $firstname.' '.$lastname;
    $role = '';
    $description = '';
  }

} else {
  // Set up variables
  $isOwnProfile = "false";
  $pageOwnerUUID = '00000000-0000-0000-000000000000';
  $firstname = 'Unbekannter';
  $lastname = 'Nutzer';
  $fullname = $firstname.' '.$lastname;
  $role = '';
  $description = '';
}

?>

<head>
  <meta charset="utf-8">
  <title>Die Edith - Profil</title>

  <?php include '../framework/head.php'?>

  <script src="/framework/timeago.js"></script>


  <link rel="stylesheet" type="text/css" href="profil.css">
  <script src="profil.js"></script>

</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

  <div class="wrapper">
    <div data-pageOwnerUUID="<?php echo $pageOwnerUUID ?>" class="profile-head">
      <img class="profile-head__profilepicture" src="/user/<?php echo $pageOwnerUUID ?>/pb-small.jpg" alt="Profilbild">
      <div class="profile-head__center">
        <div class="profile-head__name">
          <?php echo $fullname ?>
        </div>
        <div class="profile-head__role">
          <?php echo $role ?>
        </div>
        <?php
          if ($description != '') {
            echo '<div class="profile-head__description">'.$description.'</div>';
          };
        ?>
      </div>
    </div>
    <div class="background--grayish"></div>


    <div class="prompt--new-article">
      <div onclick="$('html').removeClass('prompt--new-article--shown');" class="prompt--new-article__close"><i class="material-icons">close</i></div>
      <h2>Neuen Artikel erstellen:</h2>
      <form class="prompt--new-article__form">
        <div>Titel:</div>
        <input tabindex="-1" class="prompt--new-article__form__input" maxlength="180" type="text" value="">
        <input class="prompt--new-article__form__submit" type="submit" value="Erstellen">
      </form>
    </div>

    <div class="selection__wrapper">
      <div class="selection">
        <button class="selection__button selection__button--selection selection__button--selection--posts" onclick="linkto('#beitraege')">Beiträge</button>
        <button class="selection__button selection__button--selection selection__button--selection--articles" onclick="linkto('#artikel')">Nur Artikel</button>

        <?php if ($isOwnProfile == 'true') : ?>
        <button class="selection__button selection__button--selection selection__button--selection--drafts" onclick="linkto('#entwuerfe')">Deine Entwürfe</button>
        <button class="selection__button selection__button--new-article" onclick="$('html').addClass('prompt--new-article--shown')">Neuer Artikel</button>
        <?php endif; ?>

      </div>
    </div>

    <section style="opacity: 0" class="cards">
      
      <?php if ($isOwnProfile == 'true') : ?>
        <div tabindex="-1" data-postedOn="9999999999" class="card card--new-post">
          <form>
            <textarea rows="4" class="card--new-post__textarea" required placeholder="Was gibt's neues?" maxlength="280"></textarea>
            <input class="card--new-post__submit" type="submit" value="Posten">
          </form>
        </div>
      <?php endif; ?>

      <?php

      // Fetch all posts
      $statement = $pdo->prepare("SELECT posted_on, type, content FROM posts WHERE owner = ?");
      $statement->execute(array($pageOwnerUUID));

      // Print out all posts
      while($row = $statement->fetch()) {
        $content_decoded = json_decode($row['content'], true);
        $unixTimeStamp = strtotime($row['posted_on']);
        $unixTimeStampMs = $unixTimeStamp * 1000 - 3600000;

        if ($row['type'] == 'post') {
          echo <<<HTML
          <div data-postedOn="$unixTimeStamp" class="card card--post">
            <div class="post__text">
              <span>{$content_decoded['text']}</span>
            </div>
            <div data-timeago="$unixTimeStampMs" class="card__time"></div>
          </div>
          HTML;
        } else {

          echo <<<HTML
          <div data-postedOn="$unixTimeStamp"  onclick="linkto('/Artikel/{$content_decoded['name']}')" class="card card--article">
            <div class="article__info">
              <h3 class="article__title">{$content_decoded['headline']}</h3>
              <div class="article__preview">
                <span>{$content_decoded['text']}</span> <a href="#">Mehr lesen</a>
              </div>
            </div>
            <img class="article__img" src="/Artikel/{$content_decoded['name']}/pic1.jpg" alt="">
            <div data-timeago="$unixTimeStampMs" class="card__time"></div>
          </div>
          HTML;
        }
      }

      ?>



      <div class="card card--null card--null--draft">
        <span>Keine Entwürfe</span>
      </div>


    </section>

  </div>
</body>

</html>
