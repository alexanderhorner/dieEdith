<?php 
header('Content-type: application/json');
require_once __DIR__ . '/validateID.php';


// Set up all response parameters
$response = array();
$response['status'] = 'failed';
$response['error'] = array();
$response['error']['category'] = 'Unknown';
$response['error']['description'] = 'An unknown error has occured';

// Check Permissions
// no restrictions

// Set up all input parameters
if (isset ($_POST['AID']) && isset($_POST['newTitle'])) {
    $AID = $_POST['AID'];
    $newTitle = $_POST['newTitle'];
} else {
    $response['error']['category'] = 'Parameter error';
    $response['error']['description'] = 'One or more parameter are missing';
    goto end;
}

// Check Parameters
if (validateID('A', $AID) === false) {
    $response['error']['category'] = 'Parameter error';
    $response['error']['description'] = 'The parameter "AID" is wrong';
    goto end;
}
if (strlen($newTitle) <= 0) {
    $response['error']['category'] = 'Parameter error';
    $response['error']['description'] = 'The parameter "newTitle" is wrong';
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
    $statement = $pdo->prepare("UPDATE articles SET title = ? WHERE AID = ?");

    // execute statement
    $statement->execute(array($newTitle, $AID));

    // check response from statement
    if($statement->errorCode() != 0) {
        $stmntError = $statement->errorInfo();
        $response['error']['category'] = 'MySQL error';
        $response['error']['description'] = $stmntError[2];
        goto end;
    } else {

        $response['status'] = 'successful';
        unset($response['error']);

        // Check how many rows were affected
        // if ($statement->rowCount() == 1) {}
    }


}



// Finish request
end:
echo json_encode($response, JSON_PRETTY_PRINT);

?>