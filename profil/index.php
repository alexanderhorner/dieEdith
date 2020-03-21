<?php 
include '../framework/document-start.php';
require_once __DIR__ . '/../framework/isTeamMember.php';

if (isset($_GET['user'])) {
	$username = $_GET['user'];


	// Connenct to database
	include '../framework/mysqlcredentials.php';

	// Check if Article exists
	$stmntSearch = $pdo->prepare("SELECT `UID`, `firstname`, `lastname`, `role`, `description` FROM user WHERE username = ?");

	// execute statement and put response into array
	$stmntSearch->execute(array($username));

	// fetch
	$row = $stmntSearch->fetch();

	//check if user exists
	if ($stmntSearch->rowCount() > 0) {
		// Set up variables
		$pageOwnerUID = $row['UID'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$fullname = $firstname.' '.$lastname;
		$role = $row['role'];
		$description = $row['description'];

		if (isset($_SESSION['UID'])) {
			if ($_SESSION['UID'] == $pageOwnerUID) {
				$isOwnProfile = true;
			} else {
				$isOwnProfile = false;
			}
		} else {
			$isOwnProfile = false;
		}

	} else {
		// Set up variables
		$isOwnProfile = false;
		$pageOwnerUID = 'U0000000000';
		$firstname = 'Unbekannter';
		$lastname = 'Nutzer';
		$fullname = $firstname.' '.$lastname;
		$role = '';
		$description = '';
	}

} else {
	// Set up variables
	$isOwnProfile = false;
	$pageOwnerUID = 'U0000000000';
	$firstname = 'Unbekannter';
	$lastname = 'Nutzer';
	$fullname = $firstname.' '.$lastname;
	$role = '';
	$description = '';
}

?>

<head>
	<meta charset="utf-8">
	<title>Die Edith - Profil</title>

	<?php include '../framework/head.php'?>

	<script src="/framework/timeago.js"></script>


	<link rel="stylesheet" type="text/css" href="profil.css">
	<script src="profil.js"></script>

</head>

<body>
	<?php include '../framework/nav-overlay.php'?>

	<div class="wrapper">
		<div data-pageOwnerUID="<?php echo $pageOwnerUID ?>" class="profile-head">
			<img class="profile-head__profilepicture" src="/user/<?php echo $pageOwnerUID ?>/pb-small.jpg" alt="Profilbild">
			<div class="profile-head__center">
				<div class="profile-head__name">
					<?php echo $fullname ?>
				</div>
				<div class="profile-head__role">
					<?php echo $role ?>
				</div>
				<?php
					if ($description != '') {
						echo '<div class="profile-head__description">'.$description.'</div>';
					};
				?>
			</div>
		</div>

		<div class="selection__wrapper">
			<div class="selection">
				<button class="selection__button selection__button--selection selection__button--selection--posts" onclick="linkto('#beitraege')">Beiträge</button>
				<button class="selection__button selection__button--selection selection__button--selection--articles" onclick="linkto('#artikel')">Nur Artikel</button>

				<?php if ($isOwnProfile == true && isTeamMember()) : ?>
				<button class="selection__button selection__button--selection selection__button--selection--drafts" onclick="linkto('#entwuerfe')">Deine Entwürfe</button>
				<button class="selection__button selection__button--new-article" onclick="newArticle()">Neuer Artikel</button>
				<?php endif; ?>

			</div>
		</div>

		<section style="opacity: 0" class="cards">
			
			<?php if ($isOwnProfile == true && isTeamMember()) : ?>
				<div tabindex="-1" data-postedOn="9999999997" class="card card--new-post">
					<form>
						<textarea rows="4" class="card--new-post__textarea" required placeholder="Was gibt's neues?" maxlength="280"></textarea>
						<input class="card--new-post__submit" type="submit" value="Posten">
					</form>
				</div>
			<?php endif; ?>

			<?php

			$ammountofPosts = 0;
			$ammountofArticles = 0;
			$ammountofDrafts = 0;

			// Fetch all posts
			$stmntFetchPosts = $pdo->prepare("SELECT PID as ID, postedon as 'time', owner, 'post' as 'type', text, NULL as 'title'
			FROM posts 
			WHERE owner = ?
			UNION 
			SELECT AID as ID, publishedon as 'time', owner, status as 'type', previewdata as 'text', title
			FROM articles 
			WHERE owner = ? AND status = 'public'
			UNION 
			SELECT AID as ID, createdon as 'time', owner, status as 'type', previewdata as 'text', title
			FROM articles 
			WHERE owner = ? AND status = 'draft'
			ORDER BY time desc");
			$stmntFetchPosts->execute(array($pageOwnerUID, $pageOwnerUID, $pageOwnerUID));

			// Print out all posts
			while($row = $stmntFetchPosts->fetch()) {
				$ID = $row['ID'];
				$owner = $row['owner'];
				$unixTimeStamp = strtotime($row['time']);
				$unixTimeStampMs = $unixTimeStamp * 1000;
				$type = $row['type'];
				$text = $row['text'];
				$text_sanitized = htmlspecialchars($text);
				$text_medium = mb_substr($text, 0, 400, 'UTF-8');
				$text_medium_sanitized = htmlspecialchars($text_medium);
				$text_sanitized = htmlspecialchars($text);
				$title = $row['title'];
				$title_sanitized = htmlspecialchars($title);
				$titleLink = rawurlencode($title);
				$titleLink_sanitized = htmlspecialchars($titleLink);

				
				if ($type == 'post') {
					$ammountofPosts += 1;

					if ($isOwnProfile == true) {
						$isOwnedClass = "card--post--isOwner";
					} else {
						$isOwnedClass = "";
					}

					echo <<<HTML
					<div data-postedOn="$unixTimeStamp" data-PID="$ID" class="card card--post $ID $isOwnedClass">
					HTML;
					if ($isOwnProfile == true) {
						echo <<<HTML
							<div onclick="deletePost('$ID')" class="card__delete">
								<i class="material-icons">delete_forever</i>
							</div>
						HTML;
					}
					echo <<<HTML
						<div class="post__text">
							<span>$text_sanitized</span>
						</div>
						<div data-timeago="$unixTimeStampMs" class="card__time"></div>
					</div>
					HTML;

				} else if ($type == 'public') {
					$ammountofPosts += 1;
					$ammountofArticles += 1;

					if (!isset($content_decoded['pic'])) {
						$picture = 'card--article--no-picture';
					} else {
						$picture = '';
					}

					echo <<<HTML
					 <div data-AID="$ID" data-postedOn="$unixTimeStamp" onclick="linkto('/artikel/$titleLink_sanitized')" class="card card--article card--article--released $picture">
						<div class="card--article__information">
							<h3 class="card--article__headline">$title_sanitized</h3>
							<span class="card--article__text">$text_medium_sanitized... <a href="/artikel/$titleLink_sanitized">Weiter lesen</a></span>
						</div>
					HTML;
					if (isset($content_decoded['pic'])) {
						echo '<img class="card--article__picture" src="/artikel/$titleLink_sanitized/thumbnail.jpg" alt="">'."\n";
					}
					echo '<div data-timeago="'.$unixTimeStampMs.'" class="card__time"></div>'."\n";
					echo "</div>\n";

				} else {
					// draft
					$ammountofDrafts += 1;

					if (!isset($content_decoded['pic'])) {
						$picture = 'card--article--no-picture';
					} else {
						$picture = '';
					}

					echo <<<HTML
					 <div data-AID="$ID" data-postedOn="$unixTimeStamp" onclick="linkto('/editor/$titleLink_sanitized')" class="card card--article card--article--draft $picture">
						<div class="card--article__information">
							<h3 class="card--article__headline">$title_sanitized</h3>
							<span class="card--article__text">$text_medium_sanitized... <a href="/editor/$titleLink_sanitized">Editieren</a></span>
						</div>
					HTML;
					if (!file_exists('/artikel('.$ID.'/thumbnail.jpg')) {
						// error_log('yee', 0);
					} else {
						// error_log('neeeow', 0);
					}
					echo '<div data-timeago="'.$unixTimeStampMs.'" class="card__time"></div>'."\n";
					echo "</div>\n";
				}

			}
			if ($ammountofPosts == 0) {
				echo <<<HTML
					<div data-postedOn="9999999995" class="card card--null card--null--posts">
						<span>Keine Beiträge</span>
					</div>
				HTML;
			}
			if ($ammountofArticles == 0) {
				echo <<<HTML
					<div data-postedOn="9999999995" class="card card--null card--null--articles">
						<span>Keine Artikel</span>
					</div>
				HTML;
			}
			if ($ammountofDrafts == 0) {
				echo <<<HTML
					<div data-postedOn="9999999995" class="card card--null card--null--draft">
						<span>Keine Entwürfe</span>
					</div>
				HTML;
			}
			?>


				 

		</section>

	</div>
</body>

</html>
