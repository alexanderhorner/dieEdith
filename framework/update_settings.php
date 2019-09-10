<?php
session_start();
if (isset($_SESSION['userid'])) {
  $userid = $_SESSION['userid'];
} else {
  die();
}
// Set up all variables
$response = array();
$response['updated-settings'] = 0;

// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
} else {

  // prefers_color_scheme_dark
  if (isset($_POST['prefers_color_scheme_dark'])) {
    $prefers_color_scheme_dark = $_POST['prefers_color_scheme_dark'];

    // prepare statement
    $statement = $pdo->prepare("UPDATE user SET prefers_color_scheme_dark = ? WHERE id = ?");

    // execute statement and put response into array
    $statement->execute(array($prefers_color_scheme_dark, $userid));

    // response
    if ($statement->rowCount() == 1) {
      $response['updated-settings'] = $response['updated-setting'] + 1;
      $response['prefers_color_scheme_dark-success'] = 'success';
    }
  }

  
}

// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
?>
