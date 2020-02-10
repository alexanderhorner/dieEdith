<?php
require_once __DIR__ . '/randomID.php';


// Set up all variables
$response = array();
$response['request'] = 'failed';
$response['error'] = 'unknown';

if (isset($_POST['text'])) {
  $text = htmlspecialchars($_POST['text'], ENT_QUOTES, 'UTF-8');
  if (ctype_space($text)) {
    $response['error'] = 'Kommentar ist leer';
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
  $response['request'] = 'success';
  unset($response['error']);


  $content = <<<JSON
  {
    "text": "$text"
  }
  JSON;



  // prepare statement
  $statement = $pdo->prepare("INSERT INTO `posts`(`PID`, `owner`, `type`, `content`) VALUES (?, ?, ?, ?)");

  // execute statement and put response into array
  $statement->execute(array('P'.random_str(10), $UID, 'post', $content));

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
