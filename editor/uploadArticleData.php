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
$response['requestPreview'] = 'failed';
$response['errorPreview'] = 'unknown';


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

	$GLOBALS['AID'] = $AID;

	// prepare statement
	$stmntSearch = $pdo->prepare("SELECT title, `owner` FROM articles WHERE AID = ?");

	// execute statement and put response into array
	$stmntSearch->execute(array($AID));

	// fetch
	$row = $stmntSearch->fetch();

	//put in variables
	$articleOwner = $row['owner'];
	$articleTitle = $row['title'];

	
	//check if article is already in database
	if ($stmntSearch->rowCount() == 0) {

		// article doesnt exist
		$response['request'] = 'failed';
		$response['error'] = 'Article doesnt exist';
		

	} else {

		// Check if loggedIn user has rights
		if ($articleOwner == $UID || $UID == 'UoaWWOeSsGk') {

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

			$image_counter = 0;

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

				// image 
				else if ($block['type'] == 'image' && isset($block['data']['file']['url'])) {
					$image_counter =+ 1;
					if ($image_counter == 1) {
						$urlToPicture = '..'.$block['data']['file']['url'];
						$urlToDestination = dirname($urlToPicture).'/thumbnail'.'.'.pathinfo($urlToPicture, PATHINFO_EXTENSION);;
						error_log($urlToPicture);
						error_log($urlToDestination);
						copy($urlToPicture, $urlToDestination);
					}
					$return .= "<figure>\n";
					$return .= "\t".'<img src="'.$block['data']['file']['url'].'" alt="'.$block['data']['caption'].'">'."\n";
					$return .= "<figcaption>".$block['data']['caption']."</figcaption>\n";
					$return .= "</figure>\n";
				}
			}

			if ($image_counter == 0) {
				$urlToArticle = '../artikel/bilder/'.$GLOBALS['AID'].'/';
				error_log($urlToArticle);
				$thumbnail = glob($urlToArticle.'thumbnail.*');
				error_log(json_encode($thumbnail, JSON_PRETTY_PRINT));
				if (!empty($thumbnail)) {
					error_log('unlink');
					unlink($thumbnail[0]);
				} else {
					error_log('empty');
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
				"image": {
					"caption": {
						"type": "string"
					},
					"file": {
						"type": "array",
						"data": {
							"url": {
								"type": "string",
								"required": false
							}
						}
					},
					"stretched": {
						"type": "bool",
						"canBeOnly": [false]
					},
					"withBackground": {
						"type": "bool",
						"canBeOnly": [false]
					},
					"withBorder": {
						"type": "bool",
						"canBeOnly": [false]
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
			$stmntUpdateHTML = $pdo->prepare("UPDATE articles SET htmldata = ? WHERE AID = ?");

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
			// prviewdata
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

			return trim($return);
			}

			$previewConfig = <<<JSON
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
						"allowedTags": ""
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
								"allowedTags": ""
							}
						}
					}
				},
				"image": {
					"caption": {
						"type": "string"
					},
					"file": {
						"type": "array",
						"data": {
							"url": {
								"type": "string",
								"required": false
							}
						}
					},
					"stretched": {
						"type": "bool",
						"canBeOnly": [false]
					},
					"withBackground": {
						"type": "bool",
						"canBeOnly": [false]
					},
					"withBorder": {
						"type": "bool",
						"canBeOnly": [false]
					}
				}
			}
			}
			JSON;


			try {
			// Initialize Editor backend and validate structure
			$previeweditor = new EditorJS( $data, $previewConfig );
			
			// Get sanitized blocks (according to the rules from configuration)
			$previewblocks = $previeweditor->getBlocks();
			
			} catch (\EditorJSException $e) {
			// process exception
			$response['HTMLerror'] = 'Conversion failed';
			goto end;
			}
			 
			$previewdata = mb_substr(renderpreview($previewblocks), 0, 1024, 'UTF-8');
			
			// prepare statement
			$stmntUpdatePreview = $pdo->prepare("UPDATE articles SET previewdata = ? WHERE AID = ?");

			// execute statement and put response into array
			$stmntUpdatePreview->execute(array($previewdata, $AID));

			if ($stmntUpdatePreview->rowCount() > 0) {
				$response['requestPreview'] = 'success';
				unset($response['errorPreview']);
			} else {
				$response['requestPreview'] = 'success';
				$response['errorPreview'] = 'No changes were made';
			}
			


		} else {
			$response['request'] = 'failed';
			$response['error'] = 'No Permission';
		}


	}

}

end:
if ($response['requestJSON'] == 'success' && $response['requestHTML'] == 'success' && $response['requestPreview'] == 'success') {
	unset($response['error']);
	$response['request'] = 'success';
} else {
	$response['error'] .= ' / Mysql request(s) failed';
}
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
?>