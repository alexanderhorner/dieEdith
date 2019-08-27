<!DOCTYPE html>
<html lang="de" class="preload">

<head>
  <meta charset="utf-8">
  <title>Die Edith &gt; Login</title>

  <!-- include? VVV -->
  <?php include '../framework/head.html'?>

  <link rel="stylesheet" type="text/css" href="login.css">
  <script src="login.js"></script>
</head>

<body>
  <header class="header">
    <span class="header__title">Die Edith</span>
    <ul class="header__nav-items">
      <li>
        <button class="hamburger hamburger--squeeze" type="button">
          <span class="hamburger-box">
            <span class="hamburger-inner"></span>
          </span>
        </button>
      </li>
    </ul>
  </header>

  <nav class="side-menu">
    <ul class="side-menu__list">
      <li onclick="linkto('/')">Home</li>
      <hr>
      <li>Darkmode</li>
      <hr>
      <li onclick="linkto('/artikel/')">Artikel</li>
      <hr>
      <li>Profil</li>
      <hr>
      <li onclick="linkto('/login/')">Login</li>
      <hr>
      <li>Registrieren</li>
      <hr>
    </ul>
  </nav>

  <div class="wrapper">
    <div class="login">
      <h2>Login</h2>
      <form class="login-form">
        <input class="form__textfield" type="text" name="username" autofocus autocomplete="username" spellcheck="false" autocapitalize="none" placeholder="Benutzername">
        <input class="form__textfield" type="password" name="password" autocomplete="current-password" spellcheck="false" autocapitalize="none" placeholder="Passwort">
        <input class="form__submit" type="submit" value="Anmelden">
      </form>
    </div>
  </div>
</body>

</html>