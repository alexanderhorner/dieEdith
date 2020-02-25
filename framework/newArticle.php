<?php

require_once __DIR__ . '/randomID.php';
require_once __DIR__ . '/isTeamMember.php';

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

if (isTeamMember() == false) {
  $response['request'] = 'failed';
  $response['error'] = "No permission";
  goto end;
} 

// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response['error'] = 'Could not connect to database.';
    goto end;
} else {

  // prepare statement
  $stmntNewArticle = $pdo->prepare("INSERT INTO `articles`(`AID`, `owner`, `title`, `jsondata`) VALUES (?, ?, ?, ?)");

  // execute statement and put response into array
  $stmntNewArticle->execute(array("A".random_str(10), $UID, $title, '{}'));

    if ($stmntNewArticle->rowCount() >= 0) {
        $response['request'] = 'success';
        unset($response['error']); 
    } else {
      $response['error'] = 'Article could not be created.';
    }

		

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
