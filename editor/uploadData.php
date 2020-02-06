<?php


function uuid()
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		// 32 bits for "time_low"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		// 16 bits for "time_mid"
		mt_rand(0, 0xffff),
		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand(0, 0x0fff) | 0x4000,
		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand(0, 0x3fff) | 0x8000,
		// 48 bits for "node"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}


// Set up all variables
$response = array();
$response['request'] = 'failed';
$response['error'] = 'unknown';


session_start();
if (isset($_SESSION['userUUID'])) {
    $userUUID = $_SESSION['userUUID'];
} else {
    $response['request'] = 'failed';
    $response['error'] = "Session expired";
    goto end;
}

// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response['error'] = 'Could not connect to database.';
    goto end;
} else {

  // Get variables
  $articleUUID = $_POST['articleUUID'];
  $data = $_POST['data'];


  // prepare statement
  $stmntSearch = $pdo->prepare("SELECT UUID FROM articles WHERE UUID = ?");

  // execute statement and put response into array
  $stmntSearch->execute(array($articleUUID));

  // fetch
  $row = $stmntSearch->fetch();

  //check if article is already in database
  if ($stmntSearch->rowCount() == 0) {

		// article doesnt exist
    $response['request'] = 'failed';
    $response['error'] = 'Article doesnt exist';

    // $articleUUID = uuid();
		//
    // // new article
    // $response['request'] = 'success';
    // unset($response['error']);
    // $response['uploadType'] = 'new';
		//
    // // prepare statement
    // $stmntInsert = $pdo->prepare("INSERT INTO articles (UUID, name, data, owner) VALUES (?, ?, ?, ?)");
		//
    // // execute statement and put response into array
    // $stmntInsert->execute(array($articleUUID, $name, $data, $userUUID));

  } else {

		// Check if loggedIn user has rights
		// prepare statement
	  $stmntPerm = $pdo->prepare("SELECT owner FROM articles WHERE UUID = ?");

	  // execute statement and put response into array
	  $stmntPerm->execute(array($articleUUID));

	  // fetch
	  $row = $stmntPerm->fetch();
		$owner = $row['owner'];

		if ($owner == $userUUID) {

	    // Update existing article

	    // prepare statement
	    $stmntUpdate = $pdo->prepare("UPDATE articles SET jsondata = ? WHERE UUID = ?");

	    // execute statement and put response into array
	    $stmntUpdate->execute(array($data, $articleUUID));

			if ($stmntUpdate->rowCount() > 0) {
				$response['request'] = 'success';
			  unset($response['error']);
			} else {
				$response['error'] = 'Mysql request failed';
			}

		} else {
			$response['request'] = 'failed';
	    $response['error'] = 'No Permission';
		}


	}

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
