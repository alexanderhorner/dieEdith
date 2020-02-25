<?php 
header('Content-type: application/json');

require_once __DIR__ . '/randomID.php';
require_once __DIR__ . '/isTeamMember.php';

// Set up all response parameters
$response = array();
$response['status'] = 'failed';
$response['error'] = array();
$response['error']['category'] = 'Unknown';
$response['error']['description'] = 'An unknown error has occured';

// Check Permissions
session_start();
if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
} else {
    $response['request'] = 'failed';
    $response['error']['category'] = "No permission";
    $response['error']['descriptopn'] = "User isn't logged in";
    goto end;
}
if (isTeamMember() == false) {
  $response['request'] = 'failed';
  $response['error']['category'] = "No permission";
  $response['error']['descriptopn'] = "The logged in user isn't part of a permitted group";
  goto end;
} 

// Set up all input parameters
// $UID = UID
if (isset($_POST['text'])) {
  $text = $_POST['text'];
} else {
  $response['error']['category'] = 'Parameter error';
  $response['error']['description'] = 'One or more parameter are missing';
  goto end;
}

// Check Parameters
if (strlen($text) <= 0) {
  $response['error']['category'] = 'Parameter error';
  $response['error']['description'] = 'The parameter "text" is wrong';
  goto end;
}


// Request
// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response['error']['category'] = 'MySQL error';
    $response['error']['description'] = 'Could not connect to database';
    goto end;
} else {

    // Update database
    // prepare statement
    $statement = $pdo->prepare("INSERT INTO `posts`(`PID`, `owner`, `text`) VALUES (?, ?, ?)");

    // execute statement
    $statement->execute(array('P'.random_str(), $UID, $text));

    // check response from statement
    if($statement->errorCode() != 0) {
        $stmntError = $statement->errorInfo();
        $response['error']['category'] = 'MySQL error';
        $response['error']['description'] = $stmntError[2];
        goto end;
    } else {

        // Check how many rows were affected
        if ($statement->rowCount() <= 0) {
          $response['error']['category'] = 'MySQL error';
          $response['error']['description'] = 'Failed to save into database';
          goto end;
        } else {
          $response['status'] = 'successful';
          unset($response['error']);
        }
    }


}

// Finish request
end:
echo json_encode($response, JSON_PRETTY_PRINT);

?>