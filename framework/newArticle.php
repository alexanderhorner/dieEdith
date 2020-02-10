<?php

require_once __DIR__ . '/randomID.php';

// Set up all variables
$response = array();
$response['request'] = 'failed';
$response['error'] = 'unknown';

if (isset($_POST['title'])) {
  $title = $_POST['title'];
  if (ctype_space($title)) {
    $response['error'] = 'Titel ist leer';
    goto end;
  }
}

session_start();
if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
} else {
    $response['request'] = 'failed';
    $response['error'] = "User isn't logged in";
    goto end;
}

// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response['error'] = 'Could not connect to database.';
    goto end;
} else {

  $PID = 'P'.random_str(10);

  // prepare statement
  $stmntNewArticle = $pdo->prepare("INSERT INTO `articles`(`AID`, `owner`, `title`, `jsondata`, `linkedpost`) VALUES (?, ?, ?, ?, ?)");

  // execute statement and put response into array
  $stmntNewArticle->execute(array("A".random_str(10), $UID, $title, '{}', $PID));

    if ($stmntNewArticle->rowCount() > 0) {
      
      function encodeURIComponent($str) {
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        return strtr(rawurlencode($str), $revert);
    }

      $name = encodeURIComponent($title);
      $content = <<<JSON
      {
        "headline": "$title",
        "name": "$name",
        "text-medium": "",
        "text-long": ""
      }
      JSON;

      // prepare statement
      $statement = $pdo->prepare("INSERT INTO `posts`(`PID`, `owner`, `type`, `content`) VALUES (?, ?, ?, ?)");

      // execute statement and put response into array
      $statement->execute(array($PID, $UID, 'draft', $content));
      
      if ($stmntNewArticle->rowCount() > 0) {
        $response['request'] = 'success';
        unset($response['error']);
      } else {
        $response['error'] = 'Article could not be posted.';
      }
    } else {
      $response['error'] = 'Article could not be created.';
    }

		

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
