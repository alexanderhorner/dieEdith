<?php
/* Attempt to connect to MySQL database */
$errormessage = $password_error = $password_error = $post_username = "";

$post_username = $_POST["username"];
$post_password = $_POST["password"];
include '../mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
} else {
    $statement = $pdo->prepare("SELECT id, password, firstname, lastname FROM allusers WHERE username = ?");
    $statement->execute(array($post_username));
    $row = $statement->fetch();
    if ($statement->rowCount() > 0) {
        $username_validity = "valid";
        $userid = $row['id'];
        $password_hash = $row['password'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        if (password_verify($post_password, $password_hash)) {
            $password_validity = "valid";
            $errormessage = 'Login erflogreich! Du wirst weitergeleitet.';
            session_start();
            $_SESSION['userid'] = $userid;
            $_SESSION['username'] = $post_username;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
        } else {
            $password_validity = "invalid";
            $errormessage = 'Falsches Passwort!';
        }
    } else {
        $username_validity = "invalid";
        $password_validity = "unknown";
        $errormessage = 'Der eingegebene Benutzer wurde nicht gefunden!';
    }
}
$arr = array('username' => $username_validity, 'password' => $password_validity, 'errormessage' => $errormessage);
echo json_encode($arr, JSON_PRETTY_PRINT);
?>
