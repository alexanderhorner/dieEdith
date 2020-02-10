<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \EditorJS\EditorJS;

// Set up all variables
$response = array();
$response['request'] = 'failed';
$response['error'] = 'unknown';
$response['requestJSON'] = 'failed';
$response['errorJSON'] = 'unknown';
$response['requestHTML'] = 'failed';
$response['errorHTML'] = 'unknown';
$response['requestPost'] = 'failed';
$response['errorPost'] = 'unknown';


session_start();
if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
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
	$AID = $_POST['AID'];
	$data = $_POST['data'];


	// prepare statement
	$stmntSearch = $pdo->prepare("SELECT `title`, `owner`, `linkedpost` FROM articles WHERE AID = ?");

	// execute statement and put response into array
	$stmntSearch->execute(array($AID));

	// fetch
	$row = $stmntSearch->fetch();

	//put in variables
	$articleOwner = $row['owner'];
	$articleTitle = $row['title'];
	$PID = $row['linkedpost'];

	
	//check if article is already in database
	if ($stmntSearch->rowCount() == 0) {

		// article doesnt exist
		$response['request'] = 'failed';
		$response['error'] = 'Article doesnt exist';


	} else {

		// Check if loggedIn user has rights
		if ($articleOwner == $UID) {

			//****
			//jsondata
			// prepare statement
			$stmntUpdate = $pdo->prepare("UPDATE articles SET jsondata = ? WHERE AID = ?");

			// execute statement and put response into array
			$stmntUpdate->execute(array($data, $AID));

			if ($stmntUpdate->rowCount() > 0) {
				$response['requestJSON'] = 'success';
			  	unset($response['errorJSON']);
			} else {
				$response['errorJSON'] = 'Mysql request failed';
			}


			//****
			//htmldata
			function renderblocks($blocks)
			{
			$return = "";

			foreach ($blocks as $block) {

				// paragraph
				if ($block['type'] == 'paragraph') {
				$return .= '<p>'.$block['data']['text']."</p>\n";
				}
				
				// header
				else if ($block['type'] == 'header') {
				$return .= '<h'.$block['data']['level'].'>'.$block['data']['text'].'</h'.$block['data']['level'].">\n";
				}

				// list
				else if ($block['type'] == 'list') {
				$listtag = ($block['data']['style'] == 'ordered') ? 'ol' : 'ul';
				$return .= '<'.$listtag.">\n";
				foreach ($block['data']['items'] as $itemtext) {
					$return .= "\t<li>".$itemtext."</li>\n";
				}
				$return .= '</'.$listtag.">\n";
				}

			}

			return $return;
			}


			$configuration = <<<JSON
			{
			"tools": {
				"header": {
				"text": {
					"type": "string",
					"allowedTags": ""
				},
				"level": {
					"type": "int",
					"canBeOnly": [2, 3, 4]
				}
				},
				"paragraph": {
				"text": {
					"type": "string",
					"allowedTags": "i,b,u,a[href],mark"
				}
				},
				"list": {
				"style": {
					"type": "string",
					"canBeOnly": ["ordered", "unordered"]
				},
				"items": {
					"type": "array",
					"data": {
					"-": {
						"type": "string",
						"allowedTags": "i,b,u"
					}
					}
				}
				},
				"quote": {
				"text": {
					"type": "string",
					"allowedTags": "i,b,u"
				},
				"caption": {
					"type": "string"
				},
				"alignment": {
					"type": "string",
					"canBeOnly": ["left"]
				}
				}
			}
			}
			JSON;


			try {
			// Initialize Editor backend and validate structure
			$editor = new EditorJS( $data, $configuration );
			
			// Get sanitized blocks (according to the rules from configuration)
			$blocks = $editor->getBlocks();
			
			} catch (\EditorJSException $e) {
			// process exception
			$response['HTMLerror'] = 'Conversion failed';
			goto end;
			}

			$html = renderblocks($blocks);
			

			// prepare statement
			$stmntUpdateHTML = $pdo->prepare("UPDATE `articles` SET `htmldata` = ? WHERE AID = ?");

			// execute statement and put response into array
			$stmntUpdateHTML->execute(array($html, $AID));

			if ($stmntUpdateHTML->rowCount() > 0) {
				$response['requestHTML'] = 'success';
				unset($response['errorHTML']);
			} else {
				$response['requestHTML'] = 'success';
				$response['errorHTML'] = 'No changes were made';
			}
			
			//****
			//post
			function renderPreview($blocks)
			{
			$return = "";
			foreach ($blocks as $block) {

				// paragraph
				if ($block['type'] == 'paragraph') {
				$return .= $block['data']['text'].' ';
				}
				
				// header
				else if ($block['type'] == 'header') {
				$return .= $block['data']['text']." ";
				}

				// list
				else if ($block['type'] == 'list') {
				foreach ($block['data']['items'] as $itemtext) {
					$return .= $itemtext.", ";
				}

				}

			}

			return $return;
			}


			// prepare statement
			$stmntSelectPost = $pdo->prepare("SELECT `content` FROM posts WHERE PID = ?");

			// execute statement and put response into array
			$stmntSelectPost->execute(array($PID));

			// fetch
			$row = $stmntSelectPost->fetch();
			$jsoncontent = $row['content'];
			$content = json_decode($jsoncontent, true);
			
			if ($stmntSelectPost->rowCount() > 0) {

				// catch preview
				$previewdatamedium = json_encode(substr(renderPreview($blocks), 0, 280));
				$previewdatalong = json_encode(substr(renderPreview($blocks), 0, 480));

				// build content
				$content = <<<JSON
				{
				"headline": "{$content['headline']}",
				"name": "{$content['name']}",
				"text-medium": $previewdatamedium,
				"text-long": $previewdatalong
				}
				JSON;

				// prepare statement
				$stmntUpdatePost = $pdo->prepare("UPDATE `posts` SET `content` = ?, `postedon` = CURRENT_TIMESTAMP() WHERE PID = ?");

				// execute statement and put response into array
				$stmntUpdatePost->execute(array($content, $PID));

				if ($stmntUpdatePost->rowCount() > 0) {
					$response['requestPost'] = 'success';
					unset($response['errorPost']);
				} else {
					$response['requestPost'] = 'success';
					$response['errorPost'] = 'No changes were made';
				}

			} else {
				$response['requestPost'] = 'error';
				$response['errorPost'] = 'Linked post was not found';
			}


		} else {
			$response['request'] = 'failed';
	    	$response['error'] = 'No Permission';
		}


	}

}

end:
if ($response['requestJSON'] == 'success' && $response['requestHTML'] == 'success' && $response['requestPost'] == 'success') {
	unset($response['error']);
	$response['request'] = 'success';
} else {
	$response['error'] = 'Mysql request(s) failed';
}
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
?>