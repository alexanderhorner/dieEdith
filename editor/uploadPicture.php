<?php 

header('Content-type: application/json');

require_once __DIR__ . '/../framework/isTeamMember.php';
require_once __DIR__ . '/../framework/validateID.php';

// Set up all response parameters
$response = array();
$response['success'] = 0;
// $response['file'] = array();
// $response['file']['url'] = '';

// Check Permissions
session_start();
if (isset($_SESSION['UID'])) {
	$UID = $_SESSION['UID'];
} else {
	error_log("User isn't logged in", 0);
	goto end;
}
if (isTeamMember() == false) {
	error_log("The logged in user isn't part of a permitted group", 0);
	goto end;
} 

// Set up all input parameters
if (!isset($_POST['AID'])) {
	error_log("AID isn't set", 0);
	goto end;
} else {
	$AID = $_POST['AID'];
}
if (!isset($_FILES['image'])) {
	error_log("No file attached", 0);
	goto end;
} else {
	$file = $_FILES['image'];
}

// Check Parameters
if (validateID('A', $AID) == false) {
	error_log('The parameter "AID" is wrong', 0);
	error_log('AID: '.$AID, 0);
  goto end;
}

// Request
// save directory
$saveDirectory = '../artikel/bilder/'.$AID.'/';

// create folder
mkdir($saveDirectory);

// check how many files are in that folder
$files = new FilesystemIterator($saveDirectory, FilesystemIterator::SKIP_DOTS);
$filecount = iterator_count($files);
$filename = 'pic'.$filecount.'.jpg';
$target_file = $saveDirectory.'/'.$filename;

move_uploaded_file($file['tmp_name'], $target_file);
$response['success'] = 1;
$response['file'] = array();
$response['file']['url'] = '/artikel/bilder/'.$AID.'/'.$filename;

// Finish request
end:
echo json_encode($response, JSON_PRETTY_PRINT);

?>