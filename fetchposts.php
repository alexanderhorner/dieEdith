<?php
header('Content-Type: text/plain');
function humanTiming($time)
{
    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array(
        31536000 => 'Jahre',
        2592000 => 'Monate',
        604800 => 'Woche',
        86400 => 'Tage',
        3600 => 'Stunde',
        60 => 'Minute',
        1 => 'Sekunde'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) {
            continue;
        }
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'n':'');
    }
}

// Set up all variables
$response = '';
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
  $page = 1;
}

$numberOfPosts = 10;
$startAtPost = $page * $numberOfPosts - $numberOfPosts;

// Connenct to database
include 'framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response .= 'Could not connect to database.';
    goto end;
} else {

    // prepare statement
    $statement = $pdo->prepare("SELECT id, owner, posted_on, type, content FROM posts ORDER BY posted_on DESC LIMIT ?, ?");

    // execute statement
    $statement->bindParam(1, $startAtPost, PDO::PARAM_INT);
    $statement->bindParam(2, $numberOfPosts, PDO::PARAM_INT);
    $statement->execute();

    // fetch
    while ($row = $statement->fetch()) {
        // put data in variable
        $id = $row['id'];
        $owner = $row['owner'];
        $posted_on = strtotime($row['posted_on']);
        $type = $row['type'];
        $content = $row['content'];

        $posted_on_human = humanTiming($posted_on);

        $content_decoded = json_decode($content, true);

        // prepare statement
        $statement1 = $pdo->prepare("SELECT firstname, lastname FROM user WHERE id = ?");

        // execute statement
        $statement1->execute(array($owner));

        // fetch
        $row = $statement1->fetch();
        if ($statement1->rowCount() > 0) {
            $firstname= $row['firstname'];
            $lastname = $row['lastname'];
            $fullname = $firstname." ".$lastname;
        } else {
            $firstname= $row['Unknown'];
            $lastname = $row['User'];
            $fullname = $firstname." ".$lastname;
        }


        if ($type == "article") {
            $response .= <<<EOT
<div onclick="linkto('artikel/{$content_decoded['name']}')" class="card card--article">
  <div class="card__info">
    <img class="card__info__picture" src="user/$owner/pb-small.jpg" alt="profile picture">
    <div class="card__info__textbox">
      <div class="card__info__textbox__name">$fullname</div>
      <div class="card__info__textbox__time">vor $posted_on_human</div>
    </div>
  </div>
  <img class="card__picture" src="artikel/{$content_decoded['name']}/pic1.png" alt="">
  <h3>{$content_decoded['headline']}</h3>
  <span class="card__text">{$content_decoded['text']}<a href="artikel/{$content_decoded['name']}"><wbr>... Weiter lesen</a></span>
</div>\n
EOT;
        } elseif ($type == "post") {
            $response .= <<<EOT
<div class="card card--post">
  <div class="card__info">
    <img class="card__info__picture" src="user/$owner/pb-small.jpg" alt="profile picture">
    <div class="card__info__textbox">
      <div class="card__info__textbox__name">$fullname</div>
      <div class="card__info__textbox__time">vor $posted_on_human</div>
    </div>
  </div>\n
EOT;
            if (isset($content_decoded['text'])) {
                $response .= '  <span class="card__text">'.$content_decoded['text'].'</span>'."\n";
            }
            if (isset($content_decoded['image'])) {
                $response .= '  <img class="card__picture" src="'.$content_decoded['image'].'" alt="Color placeholder">'."\n";
            }
            $response .= '</div>'."\n";
        }
    }
}


end:

echo $response;
