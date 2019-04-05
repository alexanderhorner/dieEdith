<?php
session_start();
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $navinformation = "Hallo ".$firstname." ".$lastname.'! <a class="red" href="../logout/">Nicht du?</a>';
    include '../mysqlcredentials.php';
    if ($pdo === false) {
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    } else {
        $categories = array("");
        $sql = "SHOW COLUMNS FROM allusers";
        foreach ($pdo->query($sql) as $row) {
            $field = $row['Field'];
            if (strpos($field, 'id_') !== false) {
                $field_new = substr($field, 3);
                array_push($categories, $field_new);
            }
        }
        unset(
        $categories[0]
      );
        // GET VOTES
        $statement = $pdo->prepare("SELECT * FROM allusers WHERE id = ?");
        $statement->execute(array($userid));
        $row = $statement->fetch();

        // save id in id_ variables
        foreach ($categories as $val) {
            $var_name = "id_".$val;
            $$var_name = $row["id_$val"];
        }
        $statement = $pdo->prepare("SELECT pronoun, firstname, lastname from allteacher WHERE id = ?");

        // get names from ids and save them in name_ var
        foreach ($categories as $val) {
            $id_var = "id_".$val;
            $name_var = "name_".$val;
            if ($$id_var != "") {
                $statement->execute(array($$id_var));
                $row = $statement->fetch();
                $$name_var = $row["pronoun"]." ".$row["lastname"]." ".substr($row["firstname"], 0, 1).".";
            }
        }
        unset(
          $categories[8],
          $categories[9]
        );

        $sql = "SELECT * FROM allteacher ORDER BY lastname";
        $printtable = "";
    }
} else {
    $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $encoded_link = rawurlencode($actual_link);
    header("Location: ../login/?backto=$encoded_link");
    $navinformation = 'Diese Seite ist nur für angemeldete Leute. Wie bist du her gekommen? Meld dich an: <a class="red" href="../login/">Anmelden</a>';
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <title>Schülerzeitung: Die Edith - Abstimmungen</title>
  <?php $version = '10';
  $subdirectory = '../';
  include '../config/basic-framework/head.php';?>
  <link rel="stylesheet" type="text/css" href="abstimmungen.css?v=<?php echo $version ?>">
  <script src="search.js?v=<?php echo $version ?>"></script>
  <script src="select.js?v=<?php echo $version ?>"></script>
  <script src="transmit-vote.js?v=<?php echo $version ?>"></script>
</head>

<body>
  <div id="title-section" class="big">
    <span id="title" class="big"><a class="text" href="../">Die Edith</a></span>
  </div>
  <div id="wrapper">
    <div id="category-subheading"><a class="text" href="../">&lt; Zurück</a> Abstimmen</div>
    <div class="topic-box">
      <?php echo $navinformation?>
    </div>

    <p style="clear: both; margin: 0;"></p>

    <?php
foreach ($categories as $val) {
      $id_var = "id_".$val;
      $name_var = "name_".$val;
      $akkusativ = substr($val, 0, -1)."n";
      $you_voted_for = "";
      if ($$name_var != "") {
          $you_voted_for = "<span class=\"category-box__vote\">Du hast für ".$$name_var." gestimmt.</span>";
      } else {
          $you_voted_for = "<span class=\"category-box__vote\"></span>";
      }
      echo <<<KAT
<div class="category-box" id="$val">
  <div class="category-box__centering">
    <span>Stimme für den $akkusativ Lehrer</span><br>
    $you_voted_for
  </div>
</div>
KAT;
}
?>
<div class="category-box" id="attraktivstermann">
  <div class="category-box__centering">
    <span>Stimme für den attraktivsten Lehrer</span><br>
    <?php if ($name_attraktivstermann != "") {
      echo "<span class=\"category-box__vote\">Du hast für ".$name_attraktivstermann." gestimmt.</span>";
    } else {
      echo "<span class=\"category-box__vote\"></span>";
    }?>
  </div>
</div><div class="category-box" id="attraktivstefrau">
  <div class="category-box__centering">
    <span>Stimme für die attraktivste Lehrerin</span><br>
    <?php if ($name_attraktivstefrau != "") {
      echo "<span class=\"category-box__vote\">Du hast für ".$name_attraktivstefrau." gestimmt.</span>";
    } else {
      echo "<span class=\"category-box__vote\"></span>";
    }?>
  </div>
</div>

    <!-- VOTE SECTION -->
      <div style="display: none; opacity: 0" id="vote-section">
        <input name="teachername" type="text" autocomplete="off" placeholder="Suche nach Lehrern">
        <table>
          <tbody>
            <?php
          if (isset($printtable)) {
              foreach ($pdo->query($sql) as $row) {
                $id = $row["id"];
                $fullname = $row["pronoun"]." ".$row["lastname"]." ".substr($row["firstname"], 0, 1).".";
                  echo <<< TAB

                  <tr>
                    <td hidden>$id</td>
                    <td class="fullname">$fullname</td>
                  </tr>

TAB;
              }
          }
          ?>

          </tbody>
        </table>
      </div>

  </div>
  <div style="display: none; opacity: 0" id="submit">
    Stimme abgeben
  </div>
</body>

</html>
