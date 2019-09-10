<?php
session_start();
if (isset($_SESSION['userid'])) {
  header("Location: /");
  die();
}
?>

<?php include '../framework/document-start.php'?>

<head>
  <meta charset="utf-8">
  <title>Die Edith &gt; Login</title>

  <?php include '../framework/head.php'?>

  <link rel="stylesheet" type="text/css" href="login.css">
  <script src="login.js"></script>
</head>

<body>
  <?php include '../framework/nav-overlay.php'?>

  <div class="wrapper">
    <div class="container">
      <div class="container__pic-container">
        <img class="container__pic-container__picture" src="background.jpg" alt="Blue Mountains">
        <div class="container__pic-container__shadow"></div>
      </div>

      <div class="container__login">
        <img class="container__login__logo container__login__logo--light" src="/framework/icons/logo--light.svg" alt="die Edith kleines Logo. E">
        <img class="container__login__logo container__login__logo--dark" src="/framework/icons/logo--dark.svg" alt="die Edith kleines Logo. E">
        <h2>Anmelden</h2>
        <p class="container__login__error"></p>
        <form class="container__login__form">
          <input class="form__textfield form__textfield--username" type="text" name="username" autocomplete="username" spellcheck="false" autocapitalize="none" placeholder="Benutzername">
          <input class="form__textfield form__textfield--password" type="password" name="password" autocomplete="current-password" spellcheck="false" autocapitalize="none" placeholder="Passwort">
          <input class="form__submit" type="submit" value="Absenden">
        </form>
      </div>

    </div>
  </div>
</body>

</html>
