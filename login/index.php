<?php include '../framework/document-start.php';

if (isset($_SESSION['userUUID'])) {
  header("Location: /");
  die();
}
?>

<head>
  <meta charset="utf-8">
  <title>Die Edith - Login</title>

  <?php include '../framework/head.php'?>

  <script src="login.js"></script>

</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

</body>

</html>
