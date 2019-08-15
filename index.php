<!DOCTYPE html>
<html class="preload" lang="de">

<head>
  <title>Die Edith</title>

  <!-- include? VVV -->
  <?php include 'framework/head.html'?>

  <script src="masonry.js"></script>

  <link rel="stylesheet" type="text/css" href="home.css">
  <script src="home.js"></script>
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
      <li>Log In</li>
      <hr>
      <li>Registrieren</li>
      <hr>
    </ul>
  </nav>

  <section class="wrapper">
    <div class="grid">
      <!-- Card -->
      <div class="card card--text">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Lea Stirn</div>
            <div class="card__info__textbox__time">vor 3 Wochen</div>
          </div>
        </div>
        <span class="card__text">Am <i>24. September</i> kommt die zweite Schülerzeitung raus. Sie wird 1,50 Euro kosten und kann bei dem jeweiligen Klassenleiter gekauft werden.</span>
      </div>
      <!-- Card -->
      <div class="card card--article">
        <img class="card__picture" src="artikel/Solardorf-Herrnried/pic1.png" alt="">
        <h3>Solardorf Herrnried</h3>
        <span class="card__text">In einem kleinen Dorf, nahe Parsberg wird etwas für die Energiewende getan: der Elektroautobesitzer Martin Selch, der den Solarstammtisch Herrnried ins Leben gerufen hat, möchte die Leute wachrütteln. Für seinen
          Verdienst, ihr Umweltbewusstsein<a href="artikel/Solardorf-Herrnried">... Weiter lesen</a></span>
      </div>
      <!-- Card -->
      <div class="card card--picture">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Dominik Kudaschow</div>
            <div class="card__info__textbox__time">vor 2 Tagen</div>
          </div>
        </div>
        <div class="card__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris</div>
        <img class="card__picture" src="color.jpg" alt="Color placeholder">
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Bethy-Analise Gayloard</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
        <div class="card__text">I just fucking shit my pants</div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>
      <!-- Card -->
      <div class="card">
        <div class="card__info">
          <img class="card__info__picture" src="profile-placeholder.png" alt="profile picture">
          <div class="card__info__textbox">
            <div class="card__info__textbox__name">Alexander Horner</div>
            <div class="card__info__textbox__time">vor 5 Minuten</div>
          </div>
        </div>
      </div>

      <footer id="footer" class="card">
        <ul>
          <li>Impressum</li>
          <li>Datenschutzerklärung</li>
          <li>Sponsored by <a href="https://www.browserstack.com">Browserstack</a></li>
        </ul>
      </footer>
    </div>
  </section>
</body>

</html>