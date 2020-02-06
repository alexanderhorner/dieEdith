<?php

class UUID
{
	public static function v4()
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

	public static function is_valid($uuid) {
		return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
	}
}


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
if (isset($_SESSION['userUUID'])) {
    $userUUID = $_SESSION['userUUID'];
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


  // prepare statement
  $statement = $pdo->prepare("INSERT INTO `articles`(`UUID`, `owner`, `title`, `jsondata`) VALUES (?, ?, ?, ?)");

  // execute statement and put response into array
  $statement->execute(array(UUID::v4(), $userUUID, $title, '{}'));

}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
