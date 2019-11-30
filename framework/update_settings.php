<?php
// Set up all variables
$response = array();
$response['updated_settings'] = 0;
$response['request'] = 'failed';
$response['error'] = 'unknown';

session_start();
if (isset($_SESSION['userGUID'])) {
    $userGUID = $_SESSION['userGUID'];
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

  // prefers_color_scheme
    if (isset($_POST['prefers_color_scheme'])) {
        $prefers_color_scheme = $_POST['prefers_color_scheme'];

        // prepare statement
        $statement = $pdo->prepare("UPDATE user SET prefers_color_scheme = ? WHERE GUID = ?");

        // execute statement and put response into array
        $statement->execute(array($prefers_color_scheme, $userGUID));

        // response
        if ($statement->rowCount() == 1) {
            $response['updated_settings'] = $response['updated_settings'] + 1;
            $response['request'] = 'success';
            unset($response['error']);
            $response['prefers_color_scheme-success'] = 'success';
        } else {
            $response['request'] = 'success';
            $response['error'] = 'No settings were changed!';
        }
    }

    // color_scheme
      if (isset($_POST['color_scheme'])) {
          $color_scheme = $_POST['color_scheme'];

          // prepare statement
          $statement = $pdo->prepare("UPDATE user SET color_scheme = ? WHERE GUID = ?");

          // execute statement and put response into array
          $statement->execute(array($color_scheme, $userGUID));

          // response
          if ($statement->rowCount() == 1) {
              $response['updated_settings'] = $response['updated_settings'] + 1;
              $response['request'] = 'success';
              unset($response['error']);
              $response['color_scheme-success'] = 'success';
          } else {
              $response['request'] = 'success';
              $response['error'] = 'No settings were changed!';
          }
      }

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
