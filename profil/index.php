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
      }
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

    <div class="selection">
      <button onclick="linkto('#beitraege')">Beiträge</button>
      <button onclick="linkto('#artikel')">Nur Artikel</button>

      <?php if ($isOwnProfile == 'true') : ?>
      <button onclick="linkto('#entwuerfe')">Deine Entwürfe</button>
      <?php endif; ?>

    </div>

    <section class="cards">

      <div class="card card--new-post">
        <form>
          <textarea class="card--new-post__textarea" required rows="4" cols="80" placeholder="Was gibt's neues?" maxlength="280"></textarea>
          <input class="card--new-post__submit" type="submit" value="Posten">
        </form>
      </div>

      <?php

      // Fetch all posts
      $statement = $pdo->prepare("SELECT posted_on, type, content FROM posts WHERE owner = ?");
      $statement->execute(array($pageOwnerUUID));

      // Print out all posts
      while($row = $statement->fetch()) {

        $content_decoded = json_decode($row['content'], true);
        $time = date('m.d.Y', strtotime($row['posted_on']));
        $ammountOfPosts = 0;
        $ammountOfArticles = 0;

        if ($row['type'] == 'post') {
          $ammountOfPosts =+ 1;
          echo <<<HTML
          <div class="card card--post">
            <div class="post__text">
              <span>{$content_decoded['text']}</span>
            </div>
            <div class="card__time">$time</div>
          </div>
          HTML;
        } else {

          $ammountOfPosts =+ 1;
          $ammountOfArticles =+ 1;

          // Check if alternate date exists
          if (isset($content_decoded['alternativeDate'])) {
            $card__info__textbox__time = $content_decoded['alternativeDate'];
          } else {
            $card__info__textbox__time = $row['posted_on'];
          }

          echo <<<HTML
          <div onclick="linkto('/Artikel/{$content_decoded['name']}')" class="card card--article">
            <div class="card__time">$card__info__textbox__time</div>
            <div class="article__info">
              <h3 class="article__title">{$content_decoded['headline']}</h3>
              <div class="article__preview">
                <span>{$content_decoded['text']}</span> <a href="#">Mehr lesen</a>
              </div>
            </div>
            <img class="article__img" src="/Artikel/{$content_decoded['name']}/pic1.jpg" alt="">
          </div>
          HTML;
        }
      }

      if ($ammountOfPosts == 0) {
        echo <<<HTML
        <div class="card card--null card--null--posts">
          <span>Keine Beiträge</span>
        </div>
        HTML;
      }
      if ($ammountOfArticles == 0) {
        echo <<<HTML
        <div class="card card--null card--null--article">
          <span>Keine Artikel</span>
        </div>
        HTML;
      }

      ?>



      <div class="card card--null card--null--draft">
        <span>Keine Entwürfe</span>
      </div>


    </section>

  </div>
</body>

</html>
