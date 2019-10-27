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

<div class="message-box">
  <!-- <div class="message--2 message--error message">
    <div class="message__ribbon"><i class="material-icons">error_outline</i></div>
    <div class="message__close"><i class="material-icons">close</i></div>
    <div class="message_float_fix"></div><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut en</span>
  </div>
  <div class="message--2 message--warning message">
    <div class="message__ribbon"><i class="material-icons">error_outline</i></div>
    <div class="message__close"><i class="material-icons">close</i></div>
    <div class="message_float_fix"></div><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut en</span>
  </div> -->
  <noscript>
    <div class="message--1 message--error message">
      <div class="errormessage__red-border"><i class="material-icons">error_outline</i></div>
      <div class="errormessage__close"><i class="material-icons">close</i></div>
      <div class="errormessage_float_fix"></div><span>Bitte aktiviere JavaScript!</span>
    </div>
  </noscript>
</div>

<nav class="side-menu">
  <ul class="side-menu__list">

    <a href="/">
      <li class="side-menu__list__li">
        <i class="material-icons">home</i>
        <span class="side-menu__list__li__text">Home</span>
      </li>
    </a>

    <a href="/Artikel/">
      <li class="side-menu__list__li">
        <i class="material-icons">notes</i>
        <span class="side-menu__list__li__text">Artikel</span>
      </li>
    </a>

    <a href="/Profil/">
      <li class="side-menu__list__li">
        <i class="material-icons">person</i>
        <span class="side-menu__list__li__text">Profil</span>
      </li>
    </a>

    <?php if (!isset($_SESSION['userid'])) : ?>
    <a href="/login/">
      <li class="side-menu__list__li">
        <i class="material-icons">input</i>
        <span class="side-menu__list__li__text">Anmelden</span>
      </li>
    </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['userid'])) : ?>
    <a href="/logout/">
      <li class="side-menu__list__li">
        <i class="material-icons">input</i>
        <span class="side-menu__list__li__text">Abmelden</span>
      </li>
    </a>
    <?php endif; ?>

    <a href="/Einstellungen">
      <li class="side-menu__list__li">
        <i class="material-icons">tune</i>
        <span class="side-menu__list__li__text">Einstellungen</span>
      </li>
    </a>

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