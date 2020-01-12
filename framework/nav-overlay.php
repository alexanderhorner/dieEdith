<header class="header">
  <a href="/"><img class="header__logo header__logo--big--light" src="/framework/icons/logo--big--light.svg" alt="Die Edith Logo"></a>
  <a href="/"><img class="header__logo header__logo--big--dark" src="/framework/icons/logo--big--dark.svg" alt="Die Edith Logo"></a>
  <a href="/"><img class="header__logo header__logo--small header__logo--small--light" src="/framework/icons/logo--small--light.svg" alt="Die Edith Logo. E"></a>
  <a href="/"><img class="header__logo header__logo--small header__logo--small--dark" src="/framework/icons/logo--small--dark.svg" alt="Die Edith Logo. E"></a>
  <ul class="header__nav-items">

    <?php
    if (isset($_SESSION['userUUID'])) : ?>
    <li>
      <button onclick="switchHeaderProfileMenu()" class="header__nav-items__button header__nav-items__profile-btn" type="button">
        <img class="header__nav-items__button__picture" src="/user/<?php echo $_SESSION['userUUID'] ?>/pb-small.jpg" alt="Profilbild">
      </button>
      <div class="header__profile-menu">
        <ul>
          <li><a href="/profil/<?php echo $_SESSION['username'] ?>#beitraege">Dein Profil</a></li>
          <li><a href="/profil/<?php echo $_SESSION['username'] ?>#artikel">Deine Artikel</a></li>
          <li><a href="/profil/<?php echo $_SESSION['username'] ?>#entwuerfe">Entwürfe</a></li>
        </ul>
      </div>
    </li>
    <?php else : ?>
    <li>
      <button onclick="showLogin()" class="header__nav-items__button" type="button">
        <i class="material-icons">input</i>
      </button>
    </li>
    <?php endif; ?>

    <li>
      <button class="header__nav-items__button header__nav-items__side-menu-btn" type="button">
        <div class="hamburger hamburger--slider-r" type="button">
          <span class="hamburger-box">
            <span class="hamburger-inner"></span>
          </span>
        </div>
      </button>
    </li>
  </ul>
</header>

<div class="message-box">
  <noscript>
    <div class="message--1 message--error message">
      <div class="message__ribbon"><i class="material-icons">error_outline</i></div>
      <div class="message__close"><i class="material-icons">close</i></div>
      <div class="message__float-fix"></div><span>Bitte aktiviere JavaScript!</span>
    </div>
  </noscript>
  <div style="display: none" class="message--2 message--cookies message--error message">
    <div class="message__ribbon"><i class="material-icons">error_outline</i></div>
    <div class="message__close"><i class="material-icons">close</i></div>
    <div class="message__float-fix"></div><span>Bitte aktiviere Cookies!</span>
  </div>
</div>

<nav class="side-menu">
  <ul class="side-menu__list">

    <li class="side-menu__list__li">
      <a href="/">
        <i class="material-icons">home</i>
        <span class="side-menu__list__li__text">Home</span>
      </a>
    </li>

    <li class="side-menu__list__li">
      <a href="/artikel/">
        <i class="material-icons">notes</i>
        <span class="side-menu__list__li__text">Artikel</span>
      </a>
    </li>

    <?php if (isset($_SESSION['userUUID'])) : ?>
    <li class="side-menu__list__li">
      <a href="/profil/<?php echo $_SESSION['username'] ?>">
        <i class="material-icons">person</i>
        <span class="side-menu__list__li__text">Mein Profil</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if (!isset($_SESSION['userUUID'])) : ?>
    <li onclick="showLogin()" class="side-menu__list__li">
      <i class="material-icons">input</i>
      <span class="side-menu__list__li__text">Anmelden</span>
    </li>
    <?php endif; ?>

    <?php if (isset($_SESSION['userUUID'])) : ?>
    <li class="side-menu__list__li">
      <a href="/logout/">
        <i class="material-icons">input</i>
        <span class="side-menu__list__li__text">Abmelden</span>
      </a>
    </li>
    <?php endif; ?>


    <li class="side-menu__list__li">
      <a href="/Einstellungen">
        <i class="material-icons">tune</i>
        <span class="side-menu__list__li__text">Einstellungen</span>
      </a>
    </li>
  </ul>

  <hr>

  <div class="side-menu__list__li side-menu__theme-selection">

    <div class="side-menu__theme-selection__option side-menu__theme-selection__option--auto">
      <div class="side-menu__theme-selection__option__center">
        <span>Auto</span>
        <i class="material-icons">settings_brightness</i>
      </div>
    </div>

    <div class="vr"></div>

    <div class="side-menu__theme-selection__option side-menu__theme-selection__option--light">
      <div class="side-menu__theme-selection__option__center">
        <span>Hell</span>
        <i class="material-icons">brightness_high</i>
      </div>
    </div>

    <div class="vr"></div>

    <div class="side-menu__theme-selection__option side-menu__theme-selection__option--dark">
      <div class="side-menu__theme-selection__option__center">
        <span>Dunkel</span>
        <i class="material-icons">brightness_2</i>
      </div>
    </div>

  </div>

  <hr>


</nav>


<!-- Login -->
<div class="login__wrapper">
  <div class="login__container">
    <div class="login__container__close"><i class="material-icons">close</i></div>
    <div class="container__pic-container">
      <img class="container__pic-container__picture" src="/framework/blue-mountains.jpg" alt="Blue Mountains">
      <div class="container__pic-container__shadow"></div>
    </div>

    <div class="container__login">
      <img class="container__login__logo container__login__logo--light" src="/framework/icons/logo--small--light.svg" alt="die Edith kleines Logo. E">
      <img class="container__login__logo container__login__logo--dark" src="/framework/icons/logo--small--dark.svg" alt="die Edith kleines Logo. E">
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

<!-- Feauture detect prefers-color-scheme  -->
<div class="feature-detect--prefers-color-scheme--1"></div>
<div class="feature-detect--prefers-color-scheme--2"></div>
