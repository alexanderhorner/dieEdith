<?php
// Set up all variables
$response = array();
$response['updated_settings'] = 0;
$response['request'] = 'failed';
$response['error'] = 'unknown';

session_start();
if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
} else {
    $response['request'] = 'success';
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
  
  // color_scheme
  if (isset($_POST['color_scheme'])) {
    $color_scheme = $_POST['color_scheme'];

    // prepare statement
    $statement = $pdo->prepare("UPDATE user SET color_scheme = ? WHERE UID = ?");

    // execute statement and put response into array
    $statement->execute(array($color_scheme, $UID));

    // response
    if ($statement->rowCount() == 1) {
        $response['updated_settings'] = $response['updated_settings'] + 1;
        $response['color_scheme'] = 'success';
    } else {
        $response['color_scheme'] = 'No settings were changed!';
    }
  }

  // description
  if (isset($_POST['description'])) {
    $color_scheme = $_POST['description'];

    // prepare statement
    $statement = $pdo->prepare("UPDATE user SET description = ? WHERE UID = ?");

    // execute statement and put response into array
    $statement->execute(array($color_scheme, $UID));

    // response
    if ($statement->rowCount() == 1) {
        $response['updated_settings'] = $response['updated_settings'] + 1;
        $response['description'] = 'success';
    } else {
        $response['description'] = 'No settings were changed!';
    }
  }

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
