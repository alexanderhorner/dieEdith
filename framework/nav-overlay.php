<header class="header">
  <span class="header__title">Die Edith</span>
  <img class="header__logo header__logo--light" src="/framework/icons/logo--no-margin--light.svg" alt="Die Edith Logo. E">
  <img class="header__logo header__logo--dark" src="/framework/icons/logo--no-margin--dark.svg" alt="Die Edith Logo. E">
  <ul class="header__nav-items">

    <?php
    if (isset($_SESSION['userid'])) : ?>
    <li>
      <button class="header__nav-items__button" type="button">
        <img class="header__nav-items__button__picture" src="/user/<?php echo $_SESSION['userid'] ?>/pb-small.jpg" alt="Profilbild">
      </button>
    </li>
    <?php else : ?>
      <li>
        <button onclick="linkto('/login/')" class="header__nav-items__button" type="button">
          <i class="material-icons">input</i>
        </button>
      </li>
    <?php endif; ?>

    <li>
      <button class="hamburger hamburger--slider-r" type="button">
        <span class="hamburger-box">
          <span class="hamburger-inner"></span>
        </span>
      </button>
    </li>
  </ul>
</header>

<nav class="side-menu">
  <ul class="side-menu__list">

    <li class="side-menu__list__li" onclick="linkto('/')">
      <i class="material-icons">home</i>
      <span class="side-menu__list__li__text">Home</span>
    </li>

    <li class="side-menu__list__li" onclick="linkto('/artikel/')">
      <i class="material-icons">notes</i>
      <span class="side-menu__list__li__text">Artikel</span>
    </li>

    <li class="side-menu__list__li">
      <i class="material-icons">person</i>
      <span class="side-menu__list__li__text">Profil</span>
    </li>

    <?php if (!isset($_SESSION['userid'])) : ?>
    <li class="side-menu__list__li" onclick="linkto('/login/')">
      <i class="material-icons">input</i>
      <span class="side-menu__list__li__text">Anmelden</span>
    </li>
    <?php endif; ?>

    <?php if (isset($_SESSION['userid'])) : ?>
    <li class="side-menu__list__li" onclick="linkto('/logout/')">
      <i class="material-icons">input</i>
      <span class="side-menu__list__li__text">Abmelden</span>
    </li>
    <?php endif; ?>

    <li class="side-menu__list__li">
      <i class="material-icons">assignment</i>
      <span class="side-menu__list__li__text">Registrieren</span>
    </li>

    <li class="side-menu__list__li">
      <i class="material-icons">tune</i>
      <span class="side-menu__list__li__text">Einstellungen</span>
    </li>

    <li class="side-menu__list__li">
      <i class="material-icons">settings_brightness</i>
      <span class="side-menu__list__li__text">Dunkel</span>
      <input class="switch" type="checkbox">
    </li>

    <li class="side-menu__list__li">
      <i class="material-icons">build</i>
      <span class="side-menu__list__li__text">Debug</span>
      <input class="switch" type="checkbox">
    </li>

  </ul>
</nav>
