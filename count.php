<?php
session_start();
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    if ($userid != "826") {
        die();
    }
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $navinformation = "Hallo ".$firstname." ".$lastname.'! <a class="red" href="../logout/">Nicht du?</a>';
    include 'mysqlcredentials.php';
    if ($pdo === false) {
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    } else {
        $category = array('witzigster', 'motiviertester', 'technikbegabtester', 'chilligster', 'stylischster', 'pünktlichster', 'strengster', 'attraktivstermann', 'attraktivstefrau');
        foreach ($category as $cat) {
            $cat1 = "id_".$cat;
            $sql = "SELECT $cat1, count(*) as c FROM allusers GROUP BY $cat1 ORDER BY c DESC";
            echo <<<TABLE
        <table border="1">
          <tr>
            <th>Kategorie: $cat</th>
            <th>Stimmen</th>
          </tr>

TABLE;
            $n = 0;
            foreach ($pdo->query($sql) as $row) {
                $n = $n+1;
                echo "<tr>\n";
                $teacherid = $row[$cat1];
                $c = $row['c'];
                if ($n == 1){
                  $c = "-";
                }
                $statement = $pdo->prepare("SELECT pronoun, firstname, lastname FROM allteacher WHERE id = ?");
                $statement->execute(array($teacherid));
                $row = $statement->fetch();
                $teachername = $row["pronoun"]." ".$row["lastname"]." ".$row["firstname"]."\n";

                echo "\t<td>".$teachername."</td>\n";
                echo "\t<td>".$c."</td>\n";
                echo "</tr>\n";
            }
            echo '</table>';
        }
    }
}
