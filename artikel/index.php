<?php

include '../framework/document-start.php';

function encodeURIComponent($str) {
	$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
	return strtr(rawurlencode($str), $revert);
}

if (isset($_GET['article'])) {
	$article = $_GET['article'];
	$articleName = $article;
	$documentTitle = $article;

	// Connenct to database
	include '../framework/mysqlcredentials.php';

	// Check if Article exists
	$stmntSearch = $pdo->prepare("SELECT AID FROM articles WHERE title = ?");

	// execute statement and put response into array
	$stmntSearch->execute(array($article));

	// fetch
	$row = $stmntSearch->fetch();

	//check if article is in database
	if ($stmntSearch->rowCount() > 0) {

		$AID = $row['AID'];

		// Get Data
		// Get Article
		$stmntDownload = $pdo->prepare("SELECT `htmldata`, `owner`, `status`, `publishedon` FROM articles WHERE AID = ? AND status = 'public'");
		$stmntDownload->execute(array($AID));

		// fetch
		$row = $stmntDownload->fetch();
		if ($stmntDownload->rowCount() > 0) {
			$articleState = $row['status'];
			$owner = $row['owner'];
			$publishedon = $row['publishedon'];

			$articleData = $row['htmldata'];

			// get name of owner
			$stmntowner = $pdo->prepare("SELECT username, firstname, lastname FROM user WHERE UID = ?");
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
		} else {
			// error_log('No article with that AID and status public found', 0);
			header('Location: /');
			die();
		}
	} else {
		// error_log('No article with that title found', 0);
		header('Location: /');
		die();
	}
} else {
	// error_log('No article specified in url', 0);
	header('Location: /');
	die();
}

// check if user is owner of the page
if (isset($_SESSION['UID'])) {
	if ($_SESSION['UID'] == $owner) {
		$isOwnerOfThisPage = true;
	} else {
		$isOwnerOfThisPage = false;
	} 
} else {
	$isOwnerOfThisPage = false;
}

?>

<head>
	<title>
		<?php echo $documentTitle ?> - Die Edith
	</title>

	<?php include '../framework/head.php'?>

	<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

	<script src="/framework/timeago.js"></script>

	<link rel="stylesheet" type="text/css" href="../artikel/artikel.css">
	<script src="../artikel/artikel.js"></script>
</head>

<body>
	<?php include '../framework/nav-overlay.php'?>

	<div class="wrapper">
		<?php if($isOwnerOfThisPage == true) : ?>
		<div class="editor-information">
			<button class="article-delete-btn editor-information__btn" onclick="deleteArticle('<?php echo $AID ?>')"><span class="btn__text"><i class="material-icons">delete_forever</i></span></button>
			<a href="../editor/<?php echo htmlspecialchars(encodeURIComponent($articleName), ENT_QUOTES, 'UTF-8'); ?>" class="editor-information__btn edit-btn"><span class="btn__text"><i class="material-icons">edit</i></span></a>
		</div>
		<?php endif;?>
		<h1 data-aid="<?php echo $AID ?>" class="main-title">
			<?php echo htmlspecialchars($articleName, ENT_QUOTES, 'UTF-8'); ?>
		</h1>

		<div class="article__info">
			<img class="article__info__picture" src="/user/<?php echo $owner ?>/pb-small.jpg" alt="profile picture">
			<div class="article__info__textbox">
				<div class="article__info__textbox__name">
					<?php echo $fullname; ?>
				</div>
				<div class="article__info__textbox__time" data-timeago="<?php echo $publishedon ?>"><?php echo date('d.m.Y', strtotime($publishedon))?></div>
			</div>
		</div>
		<!-- <script>
			var nodes = document.querySelectorAll('.article__info__textbox__time');
			timeago.render(nodes, 'de');
		</script> -->

		<p class="clear"></p>


		<article class="article"><?php echo $articleData ?></article>
</body>

</html>
