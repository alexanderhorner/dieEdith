<?php include '../framework/document-start.php';

if (isset($_GET['user'])) {
  $username = $_GET['user'];


  // Connenct to database
  include '../framework/mysqlcredentials.php';

  // Check if Article exists
  $stmntSearch = $pdo->prepare("SELECT `UID`, `firstname`, `lastname`, `role`, `description` FROM user WHERE username = ?");

  // execute statement and put response into array
  $stmntSearch->execute(array($username));

  // fetch
  $row = $stmntSearch->fetch();

  //check if user exists
  if ($stmntSearch->rowCount() > 0) {
    // Set up variables
    $pageOwnerUID = $row['UID'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $fullname = $firstname.' '.$lastname;
    $role = $row['role'];
    $description = $row['description'];

    if (isset($_SESSION['UID'])) {
      if ($_SESSION['UID'] == $pageOwnerUID) {
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
    $pageOwnerUID = 'U0000000000';
    $firstname = 'Unbekannter';
    $lastname = 'Nutzer';
    $fullname = $firstname.' '.$lastname;
    $role = '';
    $description = '';
  }

} else {
  // Set up variables
  $isOwnProfile = "false";
  $pageOwnerUID = 'U0000000000';
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
    <div data-pageOwnerUID="<?php echo $pageOwnerUID ?>" class="profile-head">
      <img class="profile-head__profilepicture" src="/user/<?php echo $pageOwnerUID ?>/pb-small.jpg" alt="Profilbild">
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
        <div tabindex="-1" data-postedOn="9999999997" class="card card--new-post">
          <form>
            <textarea rows="4" class="card--new-post__textarea" required placeholder="Was gibt's neues?" maxlength="280"></textarea>
            <input class="card--new-post__submit" type="submit" value="Posten">
          </form>
        </div>
      <?php endif; ?>

      <?php

      $ammountofPosts = 0;
      $ammountofArticles = 0;
      $ammountofDrafts = 0;

      // Fetch all posts
      $stmntFetchPosts = $pdo->prepare("SELECT `PID`, `postedon`, `type`, `content` FROM posts WHERE owner = ?");
      $stmntFetchPosts->execute(array($pageOwnerUID));

      // Print out all posts
      while($row = $stmntFetchPosts->fetch()) {
        $type = $row['type']; 
        $PID = $row['PID']; 
        $content_decoded = json_decode($row['content'], true);
        $unixTimeStamp = strtotime($row['postedon']);
        $unixTimeStampMs = $unixTimeStamp * 1000 - 3600000;

        $ammountofPosts += 1;
        if ($type == 'post') {
          echo <<<HTML
          <div data-postedOn="$unixTimeStamp" class="card card--post">
            <div class="post__text">
              <span>{$content_decoded['text']}</span>
            </div>
            <div data-timeago="$unixTimeStampMs" class="card__time"></div>
          </div>
          HTML;

        } else if ($type == 'article') {
          $ammountofArticles += 1;

          echo <<<HTML
           <div data-PID="$PID" data-postedOn="$unixTimeStamp" onclick="linkto('/artikel/{$content_decoded['name']}')" class="card card--article card--article--released">
            <div class="card--article__information">
              <h3 class="card--article__headline">{$content_decoded['headline']}</h3>
              <span class="card--article__text">{$content_decoded['text-long']}... <a href="/artikel/{$content_decoded['name']}">Weiter lesen</a></span>
            </div>
          HTML;
          if (isset($content_decoded['pic'])) {
            echo '<img class="card--article__picture" src="/artikel/Solardorf-HERRNRIED/pic1.jpg" alt="">'."\n";
          }
          echo "</div>\n";

        } else {
          // draft

          $ammountofDrafts += 1;

          echo <<<HTML
           <div data-PID="$PID" data-postedOn="$unixTimeStamp" onclick="linkto('/editor/{$content_decoded['name']}')" class="card card--article card--draft">
            <div class="card--article__information">
              <h3 class="card--article__headline">{$content_decoded['headline']}</h3>
              <span class="card--article__text">{$content_decoded['text-long']}... <a href="/editor/{$content_decoded['name']}">Editieren</a></span>
            </div>
          HTML;
          if (isset($content_decoded['pic'])) {
            echo '<img class="card--article__picture" src="/artikel/Solardorf-HERRNRIED/pic1.jpg" alt="">'."\n";
          }
          echo "</div>\n";
        }

      }
      if ($ammountofPosts == 0) {
        echo <<<HTML
          <div data-postedOn="9999999995" class="card card--null card--null--posts">
            <span>Keine Beiträge</span>
          </div>
        HTML;
      }
      if ($ammountofArticles == 0) {
        echo <<<HTML
          <div data-postedOn="9999999995" class="card card--null card--null--articles">
            <span>Keine Artikel</span>
          </div>
        HTML;
      }
      if ($ammountofDrafts == 0) {
        echo <<<HTML
          <div data-postedOn="9999999995" class="card card--null card--null--draft">
            <span>Keine Entwürfe</span>
          </div>
        HTML;
      }
      ?>


         

    </section>

  </div>
</body>

</html>
