<?php


date_default_timezone_set('Europe/Berlin');

// Set up all variables
$response = array();
$response['request'] = 'failed';
$response['error'] = 'unknown';

if (isset($_POST['AID'])) {
    $AID = $_POST['AID'];
} else {
    $response['error'] = 'Parameters missing';
    goto end;
}

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

    // prepare statement
    $stmntCeck = $pdo->prepare("SELECT `owner`, `status`, `linkedpost` FROM articles WHERE `AID` = ?");

    // execute statement and put response into array
    $stmntCeck->execute(array($AID));

    // fetch
    $article = $stmntCeck->fetch();

    // response
    if ($stmntCeck->rowCount() == 1) {
        

        // check if user has permission
        if ($article['owner'] == $UID) {

            // check if article is already public
            if ($article['status'] == 'draft') {

                // prepare statement
                $stmntUpdate = $pdo->prepare("UPDATE articles SET `status` = ?, `publishedon` = CURRENT_TIMESTAMP() WHERE AID = ?");

                // execute statement and put response into array
                $stmntUpdate->execute(array('public', $AID,));

                // response
                if ($stmntUpdate->rowCount() == 1) {
                   
                    // prepare statement
                    $stmntUpdate = $pdo->prepare("UPDATE posts SET `type` = ?, `postedon` = CURRENT_TIMESTAMP() WHERE PID = ?");

                    // execute statement and put response into array
                    $stmntUpdate->execute(array('article', $article['linkedpost']));

                    // response
                    if ($stmntUpdate->rowCount() == 1) {
                        $response['request'] = 'success';
                        unset($response['error']);
                    } else {
                        $response['error'] = 'unknown MYSQL error occured while posting the article';
                    }

                } else {
                    $response['error'] = 'unknown MYSQL error occured while publishing the article';
                }

            } else {
                $response['error'] = 'Already public';
            }

        } else {
            $response['error'] = 'No permission';
        }

    } else {
        $response['error'] = 'Article doesnt exist';
    }

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
