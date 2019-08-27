<!DOCTYPE html>
<html class="preload" lang="de">

<head>
  <title>Die Edith</title>

  <!-- include? VVV -->
  <?php include '../../framework/head.html'?>

  <link rel="stylesheet" type="text/css" href="/artikel/artikel.css">
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

    <h1>Solardorf Herrneied</h1>

    <div class="article__info">
      <img class="article__info__picture" src="/users/lillischön/pb-small.jpg" alt="profile picture">
      <div class="article__info__textbox">
        <!-- TODO: Ugly -->
        <div class="article__info__textbox__name">Lilli Schön</div>
        <div class="article__info__textbox__time">12. August</div>
      </div>
    </div>

    <p class="clear"></p>
    <article class="article">
      <p>In einem kleinen Dorf, nahe Parsberg wird etwas für die Energiewende getan: der Elektroautobesitzer Martin Selch, der den Solarstammtisch Herrnried ins Leben gerufen hat, möchte die Leute wachrütteln. Für seinen Verdienst, ihr
        Umweltbewusstsein zu schärfen, hat er auch schon Preise erhalten.</p>
      <p>Martin Selch ist sich sicher: Energietechnisch ist vieles im Umbruch und es verändert sich auch einiges zum Positiven, beispielsweise erzeugt Herrn-ried 150 Prozent mehr Strom, als dort benötigt wird. Dennoch muss deutlich mehr für die
        Energiewende getan werden, um von umweltschädigenden Energien wegzukommen. Sei es die 10-H Regelung (Windrad muss so weit von einem Ort weg sein wie die 10-fache Höhe des Windrads) zu beenden oder mehr Photovoltaik-Anlagen auf Freiflächen
        zu bauen.</p>
      <p>Leider hat die Stadt Parsberg im Oktober 2018 dies vorerst untersagt. Es gibt vielerlei Möglichkeiten, unsere Umwelt vor Kohleabbau und Atomkraftwerken zu schützen, doch sie müssen auch ergriffen werden. Herr Selch ist sich dessen bewusst
        und fährt nun schon seit vier Jahren ein Auto mit Elektromotor. Er möchte möglichst viele Autofahrer ermutigen, diesen Schritt ebenfalls zu gehen. Deshalb unterstützt er die Ladeinfrastruktur, indem man bei ihm kostenlos Sonnenstrom laden
        kann.</p>
      <p>Sein Engagement gilt auch dem Solarstammtisch Herrnried, der sich unter seiner Leitung jeden Monat im örtlichen Gasthaus zum regen Austausch trifft. Hierzu kommen Leute, die sich für die Energiewende und E-Autos interessieren. Auch gab es
        bereits zwei Tage der E-Mobilität, wo man auf dem Parkplatz des Gasthofes Neugebauer die verschiedensten Elektroautos besichtigen konnte. Vom Nissan Leaf, über den Tesla Roadster, bis hin zum kleinen Twizy war alles vertreten. Man konnte
        auch Elektrofahrräder, -motorräder und Segways ausprobieren.</p>
      <figure role="group">
        <img src="pic1.png" alt="Schild auf dem steht: Kostenlose Solarstromtankstelle für Elektrofahrzuege">
        <figcaption>Dieses Schild befindet sich an der Garage von Martin Selch.</figcaption>
      </figure>
      <p><i>Was genau ist der Solarstammtisch?</i> Es ist eine Versammlung, zu der man kommen kann, wenn man sich für die Themen Energiewende, erneuerbare Energien, Solar oder Elektroautos interessiert und sich informieren möchte. Durch
        Veranstaltungen und Vorträge ist somit ein reger Informationsaustausch möglich.</p>
      <p><i>Was erhoffen Sie sich für die Zukunft im Punkt der Energiewende?</i> Ich wünsche mir, dass die Energiewende irgendwann komplett umgesetzt wird und der Schadstoffausstoß immens verringert wird. Denn meine allergrößte Hoffnung ist es, das
        Klima zu retten oder wenigstens die Klimaerwärmung abzuschwächen. Hierfür kann nämlich jeder einen Beitrag leisten.</p>
    </article>
  </div>
</body>

</html>
