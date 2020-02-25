<?php include 'framework/document-start.php';
require_once __DIR__ . '/framework/isTeamMember.php';
?>


<head>
  <title>Die Edith</title>

  <!-- include? VVV -->
  <?php include 'framework/head.php'?>

  <script src="masonry.js"></script>
  <script src="https://unpkg.com/imagesloaded@4.1.4/imagesloaded.pkgd.min.js"></script>
  <script src="/framework/timeago.js"></script>


  <link rel="stylesheet" type="text/css" href="home.css">
  <script src="home.js"></script>
</head>

<body>
  <?php include 'framework/nav-overlay.php'?>

  <div class="wrapper">
    <?php if (isTeamMember()) : ?>
    <div class="quickstart">
      <h1 class="titles">Schnellstart</h1>
      <div class="quickstart__options__wrapper">
        <div class="quickstart__options">
          <div class="quickstart__options__option" onclick="linkto('/profil/<?php echo $_SESSION['username'] ?>#neuerArtikel')">Neuer Artikel</div>
          <div class="quickstart__options__option" onclick="linkto('/profil/<?php echo $_SESSION['username'] ?>#entwuerfe')">Deine Entwürfe</div>
          <div class="quickstart__options__option" onclick="linkto('/profil/<?php echo $_SESSION['username'] ?>#artikel')">Alle Artikel</div>
          <div class="quickstart__options__option" onclick="linkto('/profil/<?php echo $_SESSION['username'] ?>#beitraege')">Profil</div>
        </div>
      </div> 
      <h1 class="titles">Neuste Beiträge</h1>
    </div>
    <?php endif; ?>
    <div class="grid">
    </div>

    <div class="loading">
      <div class="loading__center">


        <!-- Loading Icons -->
        <div class="loadingio-spinner-spinner-vqetnn421rh">
          <div class="ldio-z6riyxaklfj">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
        </div>
        <style type="text/css">
          @keyframes ldio-z6riyxaklfj {
            0% {
              opacity: 1
            }

            100% {
              opacity: 0
            }
          }

          .ldio-z6riyxaklfj div {
            left: 48.5px;
            top: 24px;
            position: absolute;
            animation: ldio-z6riyxaklfj linear 1s infinite;
            background: #808080;
            width: 3px;
            height: 12px;
            border-radius: 0px / 0px;
            transform-origin: 1.5px 26px;
          }

          .ldio-z6riyxaklfj div:nth-child(1) {
            transform: rotate(0deg);
            animation-delay: -0.9166666666666666s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(2) {
            transform: rotate(30deg);
            animation-delay: -0.8333333333333334s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(3) {
            transform: rotate(60deg);
            animation-delay: -0.75s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(4) {
            transform: rotate(90deg);
            animation-delay: -0.6666666666666666s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(5) {
            transform: rotate(120deg);
            animation-delay: -0.5833333333333334s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(6) {
            transform: rotate(150deg);
            animation-delay: -0.5s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(7) {
            transform: rotate(180deg);
            animation-delay: -0.4166666666666667s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(8) {
            transform: rotate(210deg);
            animation-delay: -0.3333333333333333s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(9) {
            transform: rotate(240deg);
            animation-delay: -0.25s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(10) {
            transform: rotate(270deg);
            animation-delay: -0.16666666666666666s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(11) {
            transform: rotate(300deg);
            animation-delay: -0.08333333333333333s;
            background: #808080;
          }

          .ldio-z6riyxaklfj div:nth-child(12) {
            transform: rotate(330deg);
            animation-delay: 0s;
            background: #808080;
          }

          .loadingio-spinner-spinner-vqetnn421rh {
            width: 74px;
            height: 74px;
            display: inline-block;
            overflow: hidden;
            background: none;
          }

          .ldio-z6riyxaklfj {
            width: 100%;
            height: 100%;
            position: relative;
            transform: translateZ(0) scale(0.74);
            backface-visibility: hidden;
            transform-origin: 0 0;
            /* see note above */
          }

          .ldio-z6riyxaklfj div {
            box-sizing: content-box;
          }

          /* generated by https://loading.io/ */
        </style>

      </div>
    </div>

  </div>
</body>

</html>