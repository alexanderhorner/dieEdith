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
    $stmntposts = $pdo->prepare("SELECT PID as ID, postedon as 'time', owner, 'post' as 'type', text, NULL as 'title'
    FROM posts 
    UNION 
    SELECT AID as ID, publishedon as 'time', owner, 'article' as 'type', previewdata as 'text', title
    FROM articles 
    WHERE status = 'public' 
    ORDER BY time desc
    LIMIT ?, ?");

    // execute statement
    $stmntposts->bindParam(1, $startAtPost, PDO::PARAM_INT);
    $stmntposts->bindParam(2, $numberOfPosts, PDO::PARAM_INT);
    $stmntposts->execute();

    // fetch
    while ($row = $stmntposts->fetch()) {

        // put data in variable
        $ID = $row['ID'];
        $owner = $row['owner'];
        $unixTimeStamp = strtotime($row['time']);
        $unixTimeStampMs = $unixTimeStamp * 1000 - 3600000;
        $type = $row['type'];
        $text = $row['text'];
        $text_sanitized = htmlspecialchars($text);
        $text_short = mb_substr($text, 0, 256, 'UTF-8');
        $text_short_sanitized = htmlspecialchars($text_short);
        $title = $row['title'];
        $title_sanitized = htmlspecialchars($title);
        $titleLink = rawurlencode($title);
        $titleLink_sanitized = htmlspecialchars($titleLink);


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


        if ($type == "article") {

          // generate response
            $responseString .= <<<HTML
            <div data-PID="$ID" data-postedOn="$unixTimeStamp" onclick="linkto('Artikel/$titleLink_sanitized')" class="card card--article">
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
              <h3>$title_sanitized</h3>
              <span class="card__text">$text_short_sanitized... <a href="Artikel/$titleLink_sanitized">Weiter lesen</a></span>
            </div>\n
            HTML;
        } elseif ($type == "post") {
            $responseString .= <<<HTML
            <div data-PID="$ID" data-postedOn="$unixTimeStamp" class="card card--post">
              <div class="card__info" onclick="linkto('/profil/$username')">
                <img class="card__info__picture" src="user/$owner/pb-small.jpg" alt="profile picture">
                <div class="card__info__textbox">
                  <div class="card__info__textbox__name">$fullname</div>
                  <div data-timeago="$unixTimeStampMs" class="$randomRequestIdentifier card__info__textbox__time"></div>
                </div>
              </div>\n
            HTML;
            $responseString .= '<span class="card__text">'.$text_sanitized.'</span>'."\n";
            $responseString .= '</div>'."\n";
        }
    }
}

$response['content'] = $responseString;

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
