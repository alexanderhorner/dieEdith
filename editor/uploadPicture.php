<?php 

// Set up all response parameters
$response = array();
$response['success'] = 0;
$response['file'] = array();
$response['file']['url'] = '';

// Set up all input parameters
$AID = $_POST['AID'];
$file = $_FILES['image'];

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
$response['file']['url'] = '/artikel/bilder/'.$AID.'/'.$filename;

// Finish request
end:
echo json_encode($response, JSON_PRETTY_PRINT);


?>