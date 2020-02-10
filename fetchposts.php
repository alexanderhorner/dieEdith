<?php

require_once __DIR__ . '/framework/randomID.php';

$randomRequestIdentifier = 'R'.random_str(10);

// Set up all variables
$response = array();
$response['request'] = 'failed';
$response['error'] = 'unknown';
$response['requestIdentifier'] = $randomRequestIdentifier;


$responseString = '';

if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}



$numberOfPosts = 15;
$startAtPost = $page * $numberOfPosts - $numberOfPosts;

// Connenct to database
include 'framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response['error'] = 'Could not connect to database.';
    goto end;
} else {

    $response['request'] = 'success';
    unset($response['error']);

    // prepare statement
    $statement = $pdo->prepare("SELECT PID, owner, postedon, type, content FROM posts ORDER BY postedon DESC LIMIT ?, ?");

    // execute statement
    $statement->bindParam(1, $startAtPost, PDO::PARAM_INT);
    $statement->bindParam(2, $numberOfPosts, PDO::PARAM_INT);
    $statement->execute();

    // fetch
    while ($row = $statement->fetch()) {

        // put data in variable
        $PID = $row['PID'];
        $owner = $row['owner'];
        $unixTimeStamp = strtotime($row['postedon']);
        $unixTimeStampMs = $unixTimeStamp * 1000 - 3600000;
        $type = $row['type'];
        $content = $row['content'];

        // Decode jason data
        $content_decoded = json_decode($content, true);


        // prepare statement
        $statement1 = $pdo->prepare("SELECT username, firstname, lastname FROM user WHERE UID = ?");

        // execute statement
        $statement1->execute(array($owner));

        // fetch
        $row = $statement1->fetch();
        if ($statement1->rowCount() > 0) {
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


        if ($type == "article") {

          // generate response
            $responseString .= <<<HTML
            <div data-PID="$PID" data-postedOn="$unixTimeStamp" onclick="linkto('Artikel/{$content_decoded['name']}')" class="card card--article">
              <div class="card__info" onclick="linkto('/profil/$username')">
                <img class="card__info__picture" src="user/$owner/pb-small.jpg" alt="profile picture">
                <div class="card__info__textbox">
                  <div class="card__info__textbox__name">$fullname</div>
                  <div data-timeago="$unixTimeStampMs" class="$randomRequestIdentifier card__info__textbox__time"></div>
                </div>
              </div>
            HTML;
            if (isset($content_decoded['pic'])) {
              $responseString .=  '<img class="card__picture" src="artikel/{$content_decoded["name"]}/pic1.jpg" alt="">';
            }
            $responseString .= <<<HTML
              <h3>{$content_decoded['headline']}</h3>
              <span class="card__text">{$content_decoded['text-medium']}... <a href="Artikel/{$content_decoded['name']}">Weiter lesen</a></span>
            </div>\n
            HTML;
        } elseif ($type == "post") {
            $responseString .= <<<HTML
            <div data-PID="$PID" data-postedOn="$unixTimeStamp" class="card card--post">
              <div class="card__info" onclick="linkto('/profil/$username')">
                <img class="card__info__picture" src="user/$owner/pb-small.jpg" alt="profile picture">
                <div class="card__info__textbox">
                  <div class="card__info__textbox__name">$fullname</div>
                  <div data-timeago="$unixTimeStampMs" class="$randomRequestIdentifier card__info__textbox__time"></div>
                </div>
              </div>\n
            HTML;
            if (isset($content_decoded['text'])) {
                $responseString .= '  <span class="card__text">'.$content_decoded['text'].'</span>'."\n";
            }
            if (isset($content_decoded['image'])) {
                $responseString .= '  <img class="card__picture" src="'.$content_decoded['image'].'" alt="Color placeholder">'."\n";
            }
            $responseString .= '</div>'."\n";
        }
    }
}

$response['content'] = $responseString;

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
