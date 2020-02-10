<?php include '../framework/document-start.php';

if (!isset($_SESSION['UID'])) {
  header("Location: /");
  die();
}
?>

<head>
  <meta charset="utf-8">
  <title>Die Edith &gt; Einstellungen</title>

  <?php include '../framework/head.php'?>

  <link rel="stylesheet" type="text/css" href="login.css">
  <script src="login.js"></script>
</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

</body>

</html>
