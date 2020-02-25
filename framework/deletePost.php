<?php 
header('Content-type: application/json');

require_once __DIR__ . '/validateID.php';

// Set up all response parameters
$response = array();
$response['status'] = 'failed';
$response['error'] = array();
$response['error']['category'] = 'Unknown';
$response['error']['description'] = 'An unknown error has occured';

// Set up all input parameters
// $UID = UID
if (isset($_POST['PID'])) {
  $PID = $_POST['PID'];
} else {
  $response['error']['category'] = 'Parameter error';
  $response['error']['description'] = 'One or more parameter are missing';
  goto end;
}

// Check Parameters
if (validateID('P', $PID) == false) {
  $response['error']['category'] = 'Parameter error';
  $response['error']['description'] = 'The parameter "PID" is wrong';
  goto end;
}

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

// Request
// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response['error']['category'] = 'MySQL error';
    $response['error']['description'] = 'Could not connect to database';
    goto end;
} else {

    // Delete from database
    // prepare statement
    $statement = $pdo->prepare("DELETE FROM `posts` WHERE `PID` = ? AND `owner` = ?");

    // execute statement
    $statement->execute(array($PID, $UID));

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
          $response['error']['description'] = 'Failed to delete entry from database. Either no permissions OR wrong Post ID';
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