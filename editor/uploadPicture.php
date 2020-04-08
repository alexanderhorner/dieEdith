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
	goto end;
}
if (isTeamMember() == false) {
	goto end;
} 

// Set up all input parameters
if (!isset($_POST['AID'])) {
	goto end;
} else {
	$AID = $_POST['AID'];
}
if (!isset($_FILES['image'])) {
	goto end;
} else {
	$file = $_FILES['image'];
}

// Check Parameters
if (validateID('A', $AID) == false) {
  goto end;
}

// Request
// save directory
$saveDirectory = '../artikel/bilder/'.$AID.'/';

// create folder
if (!file_exists($saveDirectory)) {
	mkdir($saveDirectory);
}


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