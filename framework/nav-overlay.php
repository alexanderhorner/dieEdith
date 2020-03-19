<div class="prompt-bg"></div>

<div class="prompt prompt--delete-post">
  <h2 class="prompt__headline">Beitrag löschen</h2>
  <div class="prompt__description">Bist du dir sicher, dass du diesen Beitrag löschen willst?</div>
  <div class="prompt__btn-container">
    <button onclick="closePrompt('all'); window.promptFunction = function() {return 'function unset'}" tabindex="0" class="prompt__btn-container__btn prompt__btn-container__btn--abort">Abbrechen</button>
    <button onclick="promptFunction()" tabindex="0" class="prompt__btn-container__btn prompt__btn-container__btn--confirm--danger">Löschen</button>
  </div>
</div>

<div class="prompt prompt--new-article">
  <h2 class="prompt__headline">Neuen Artikel erstellen</h2>
  <div class="prompt__description">Wähle einen Titel für deinen Artikel.</div>
  <input tabindex="-1" class="prompt__text-field prompt--new-article__text-field" placeholder="Titel..." maxlength="100" type="text" value="">
  <div class="prompt__btn-container">
    <button onclick="closePrompt('all'); window.promptFunction = function() {return 'function unset'}" tabindex="0" class="prompt__btn-container__btn prompt__btn-container__btn--abort">Abbrechen</button>
    <button onclick="promptFunction()" tabindex="0" class="prompt__btn-container__btn prompt__btn-container__btn--confirm">Erstellen</button>
  </div>
</div>

<header class="header">
  <a href="/"><img class="header__logo header__logo--big--light" src="/framework/icons/logo--big--light.svg" alt="Die Edith Logo"></a>
  <a href="/"><img class="header__logo header__logo--big--dark" src="/framework/icons/logo--big--dark.svg" alt="Die Edith Logo"></a>
  <a href="/"><img class="header__logo header__logo--small header__logo--small--light" src="/framework/icons/logo--small--light.svg" alt="Die Edith Logo. E"></a>
  <a href="/"><img class="header__logo header__logo--small header__logo--small--dark" src="/framework/icons/logo--small--dark.svg" alt="Die Edith Logo. E"></a>
  <ul class="header__nav-items">

    <?php
    if (isset($_SESSION['UID'])) : ?>
    <li>
      <button onclick="switchHeaderProfileMenu()" class="header__nav-items__button header__nav-items__profile-btn" type="button">
        <img class="header__nav-items__button__picture" src="/user/<?php echo $_SESSION['UID'] ?>/pb-small.jpg" alt="Profilbild">
      </button>
      <div class="header__profile-menu">
        <ul>
          <li><a href="/profil/<?php echo $_SESSION['username'] ?>#beitraege">Mein Profil</a></li>
          <li><a href="/profil/<?php echo $_SESSION['username'] ?>#artikel">Meine Artikel</a></li>
          <li><a href="/profil/<?php echo $_SESSION['username'] ?>#entwuerfe">Entwürfe</a></li>
          <li><a href="/logout">Abmelden</a></li>
        </ul>
      </div>
    </li>
    <?php else : ?>
    <li>
      <button onclick="$('html').addClass('prompt--login--shown');" class="header__nav-items__button" type="button">
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

    <!-- <li class="side-menu__list__li">
      <a href="/profil/">
        <i class="material-icons">notes</i>
        <span class="side-menu__list__li__text">Artikel</span>
      </a>
    </li> -->

    <?php if (isset($_SESSION['UID'])) : ?>
    <li class="side-menu__list__li">
      <a href="/profil/<?php echo $_SESSION['username'] ?>">
        <i class="material-icons">person</i>
        <span class="side-menu__list__li__text">Mein Profil</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if (!isset($_SESSION['UID'])) : ?>
    <li onclick="$('html').addClass('prompt--login--shown');" class="side-menu__list__li">
      <i class="material-icons">input</i>
      <span class="side-menu__list__li__text">Anmelden</span>
    </li>
    <?php endif; ?>

    <?php if (isset($_SESSION['UID'])) : ?>
    <li class="side-menu__list__li">
      <a href="/logout/">
        <i class="material-icons">input</i>
        <span class="side-menu__list__li__text">Abmelden</span>
      </a>
    </li>
    <?php endif; ?>
  </ul>

  <hr>

  <div class="side-menu__list__li side-menu__theme-selection">

    <div class="side-menu__theme-selection__option side-menu__theme-selection__option--auto">
      <div class="side-menu__theme-selection__option__center">
        <div class="side-menu__theme-selection__option__center__text">Auto</div>
        <i class="material-icons">settings_brightness</i>
      </div>
    </div>

    <div class="vr"></div>

    <div class="side-menu__theme-selection__option side-menu__theme-selection__option--light">
      <div class="side-menu__theme-selection__option__center">
        <div class="side-menu__theme-selection__option__center__text">Hell</div>
        <i class="material-icons">brightness_high</i>
      </div>
    </div>

    <div class="vr"></div>

    <div class="side-menu__theme-selection__option side-menu__theme-selection__option--dark">
      <div class="side-menu__theme-selection__option__center">
        <div class="side-menu__theme-selection__option__center__text">Dunkel</div>
        <i class="material-icons">brightness_2</i>
      </div>
    </div>

  </div>

  <hr>


</nav>


<!-- Login -->
<div class="prompt--login">
  <div class="prompt--login__close"><i class="material-icons">close</i></div>
  <div class="prompt--login__pic-container">
    <img class="prompt--login__pic-container__picture" src="/framework/blue-mountains.jpg" alt="Blue Mountains">
    <div class="prompt--login__pic-container__shadow"></div>
  </div>

  <div class="prompt--login__login">
    <img class="prompt--login__login__logo prompt--login__login__logo--light" src="/framework/icons/logo--small--light.svg" alt="die Edith kleines Logo. E">
    <img class="prompt--login__login__logo prompt--login__login__logo--dark" src="/framework/icons/logo--small--dark.svg" alt="die Edith kleines Logo. E">
    <h2>Anmelden</h2>
    <p class="prompt--login__login__error"></p>
    <form class="prompt--login__login__form">
      <input class="prompt--login__login__form__textfield prompt--login__login__form__textfield--username" type="text" name="username" autocomplete="username" spellcheck="false" autocapitalize="none" placeholder="Benutzername">
      <input class="prompt--login__login__form__textfield prompt--login__login__form__textfield--password" type="password" name="password" autocomplete="current-password" spellcheck="false" autocapitalize="none" placeholder="Passwort">
      <input class="prompt--login__login__form__submit" type="submit" value="Absenden">
    </form>
  </div>
</div>

<!-- Feauture detect prefers-color-scheme  -->
<div class="feature-detect--prefers-color-scheme--1"></div>
<div class="feature-detect--prefers-color-scheme--2"></div>

