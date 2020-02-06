<?php
use \EditorJS\EditorJS;


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

if (isset($_POST['data'])) {
  $data = $_POST['data'];
	if (isset($_POST['articleUUID'])) {
	  $articleUUID = $_POST['articleUUID'];
	}
}

session_start();
if (isset($_SESSION['userUUID'])) {
    $userUUID = $_SESSION['userUUID'];
} else {
    $response['request'] = 'failed';
    $response['error'] = "Session expired";
    goto end;
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
        "allowedTags": "i,b,u,a[href]"
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
        "canBeOnly": ["left", "center"]
      }
    },
    "table": {
      "header": {
        "type": "array",
        "data": {
          "description": {
            "type": "string"
          },
          "author": {
            "type": "string"
          }
        }
      },
      "rows": {
        "type": "array",
        "data": {
          "-": {
            "type": "array",
            "data": {
              "-": {
                "type": "string"
              }
            }
          }
        }
      }
    }
  }
}
JSON;



// Get sanitized blocks (according to the rules from configuration)
try {
    // Initialize Editor backend and validate structure
    $editor = new EditorJS( $data, $configuration );

    // Get sanitized blocks (according to the rules from configuration)
    $blocks = $editor->getBlocks();

} catch (\EditorJSException $e) {
  printf($e);
}


// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    $response['error'] = 'Could not connect to database.';
    goto end;
} else {

  // prepare statement
  $statement = $pdo->prepare("INSERT INTO `articles`(`htmldata`) VALUES (?) WHERE UUID");

  // execute statement and put response into array
  $statement->execute(array($blocks));

	if ($stmntUpdate->rowCount() > 0) {
		$response['request'] = 'success';
		unset($response['error']);
	} else {
		$response['error'] = 'Mysql request failed';
	}
}

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
