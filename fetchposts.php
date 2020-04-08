<?php
date_default_timezone_set('Europe/Berlin');

require_once __DIR__ . '/framework/randomID.php';

$randomRequestIdentifier = 'R'.random_str(10);

// Set up all variables
$response = array();
$response['request'] = 'failed';
$response['error'] = 'unknown';
$response['requestIdentifier'] = $randomRequestIdentifier;


$responseString = '';


if (isset($_POST["alreadyLoadedPosts"])) {
	$alreadyLoadedPosts = json_decode($_POST["alreadyLoadedPosts"], true);
} else {
	$response['error'] = 'Parameter \'alreadyLoadedPosts\' wrong';
	goto end;
}

if (isset($_POST["ammount"])) {
	$ammountOfPosts = $_POST["ammount"];
} else {
	$response['error'] = 'Parameter \'ammount\' wrong';
	goto end;
}

session_start();
if (isset($_SESSION['UID'])) {
	$UID = $_SESSION['UID'];
} 

// Connenct to database
include 'framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
	$response['error'] = 'Could not connect to database.';
	goto end;
} else {

	$response['request'] = 'success';
	unset($response['error']);

	// prepare IN condition: Insert parameter for each
	// $alreadyLoadedPosts = []; // DEBUG to return infinite posts
	$INparam = '';
	$integerKey = 0;
	if (sizeof($alreadyLoadedPosts) === 0) {
		$INparam = '""';
	} else {
		foreach ($alreadyLoadedPosts as $i => $post) {
			$INparam .= ":post$integerKey, ";
			$integerKey += 1;
		}
		$INparam = substr($INparam, 0, -2);
	}

	// prepare statement
	$stmntposts = $pdo->prepare("SELECT PID as ID, postedon as 'time', owner, 'post' as 'type', text, NULL as 'title'
	FROM posts 
	WHERE status = 'public' AND PID NOT IN($INparam)
	UNION ALL 
	SELECT AID as ID, publishedon as 'time', owner, 'article' as 'type', previewdata as 'text', title
	FROM articles 
	WHERE status = 'public' AND AID NOT IN($INparam)
	ORDER BY time desc
	LIMIT :ammountOfPosts");

	// bind a parameter for each posts in array
	foreach ($alreadyLoadedPosts as $i => $post) {
		$postVariable = 'post'.$i;
		$$postVariable = $post;
		$stmntposts->bindParam(":post$i", $$postVariable);
	} 

	$stmntposts->bindParam(':ammountOfPosts', $ammountOfPosts, PDO::PARAM_INT);

	$stmntposts->execute();

	// // debug code for printin out mysql parameters
	// ob_start();
	// $stmntposts->debugDumpParams();
	// $r = ob_get_contents();
	// ob_end_clean();
	// error_log($r);
	// error_log("--------------------------------------\n\n\n\n");

	// fetch
	while ($row = $stmntposts->fetch()) {

		// put data in variable
		$ID = $row['ID'];
		$owner = $row['owner'];
		$unixTimeStamp = strtotime($row['time']);
		$unixTimeStampMs = $unixTimeStamp * 1000;
		$type = $row['type'];
		$text = $row['text'];
		$text_sanitized = htmlspecialchars($text);
		$text_short = mb_substr($text, 0, 256, 'UTF-8');
		$text_short_sanitized = htmlspecialchars($text_short);
		$title = $row['title'];
		$title_sanitized = htmlspecialchars($title);
		$titleLink = rawurlencode($title);
		$titleLink_sanitized = htmlspecialchars($titleLink);


		// get name of owner
		$stmntowner = $pdo->prepare("SELECT username, firstname, lastname FROM user WHERE UID = ?");

		// execute statement
		$stmntowner->execute(array($owner));

		// fetch
		$row = $stmntowner->fetch();
		if ($stmntowner->rowCount() > 0) {
				$username = $row['username'];
				$firstname= $row['firstname'];
				$lastname = $row['lastname'];
				$fullname = $firstname." ".$lastname;
		} else {
				$username = 'unknown.user';
				$firstname= $row['Unknown'];
				$lastname = $row['User'];
				$fullname = $firstname." ".$lastname;
		}


		if ($type == "article") {
				$responseString .= <<<HTML
				<div data-pid="$ID" data-titlelink="$titleLink_sanitized" data-username="$username" data-postedOn="$unixTimeStamp" class="card card--article">
					<div class="card__info">
						<img class="card__info__picture" src="user/$owner/pb-small.jpg" alt="profile picture">
						<div class="card__info__textbox">
							<div class="card__info__textbox__name">$fullname</div>
							<div data-timeago="$unixTimeStampMs" class="$randomRequestIdentifier card__info__textbox__time"></div>
						</div>
					</div>
				HTML;
				if (file_exists('artikel/bilder/'.$ID.'/thumbnail.jpg')) {
					$responseString .= '<img class="card__picture" src="/artikel/bilder/'.$ID.'/thumbnail.jpg" alt="">'."\n";
				}
				$responseString .= <<<HTML
					<h3>$title_sanitized</h3>
					<span class="card__text">$text_short_sanitized... <a href="artikel/$titleLink_sanitized">Weiter lesen</a></span>
				</div>\n
				HTML;


		} elseif ($type == "post") {
			if (isset($UID)) {
				if ($owner == $UID) {
					$isOwnerClass = " card--post--isOwner";
					$isOwnerDiv = <<<HTML
					<div onclick="deletePost('$ID')" class="card__delete">
						<i class="material-icons">delete_forever</i>
					</div>
					HTML;
				} else {
				$isOwnerClass = "";
				$isOwnerDiv = "";
				}
			} else {
				$isOwnerClass = "";
				$isOwnerDiv = "";
			}

			$responseString .= <<<HTML
			<div data-pid="$ID" data-postedOn="$unixTimeStamp" class="$ID card card--post$isOwnerClass">
				<div class="card__info" onclick="linkTo('/profil/$username')">
					<img class="card__info__picture" src="user/$owner/pb-small.jpg" alt="profile picture">
					<div class="card__info__textbox">
						<div class="card__info__textbox__name">$fullname</div>
						<div data-timeago="$unixTimeStampMs" class="$randomRequestIdentifier card__info__textbox__time"></div>
					</div>
				</div>
				$isOwnerDiv
				<span class="card__text">$text_sanitized</span>
			</div>\n
			HTML;
		}
	}
}

$response['content'] = $responseString;

end:
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
