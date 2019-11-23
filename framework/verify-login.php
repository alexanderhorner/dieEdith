<?php
// Set up all variables
$errormessage = $password_error = $password_error = $post_username = "";
$post_username = $_POST["username"];
$post_password = $_POST["password"];

// Connenct to database
include '../framework/mysqlcredentials.php';

// Check connection
if ($pdo === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
} else {

    // prepare statement
    $statement = $pdo->prepare("SELECT id, password, firstname, lastname, prefers_color_scheme FROM user WHERE username = ?");

    // execute statement and put response into array
    $statement->execute(array($post_username));

    // fetch
    $row = $statement->fetch();

    //check if there was a user was found
    if ($statement->rowCount() > 0) {
        // username valid
        $username_validity = "valid";
        // put data in variable
        $userid = $row['id'];
        $password_hash = $row['password'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $prefers_color_scheme = $row['prefers_color_scheme'];
        // check if password is valid
        if (password_verify($post_password, $password_hash)) {
            // password valid
            $password_validity = "valid";
            $errormessage = 'Richtig!';
            // set Session variables
            session_start();
            $_SESSION['userid'] = $userid;
            $_SESSION['username'] = $post_username;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $PHPSESSID = $_COOKIE['PHPSESSID'];
            setcookie('PHPSESSID', $PHPSESSID, time()+604800, '/', '', FALSE, TRUE);

            if ($prefers_color_scheme == 'dark') {
              setcookie('prefers_color_scheme', 'dark', 2147483647, '/');
            } else {
              setcookie('prefers_color_scheme', 'light', 2147483647, '/');
            }
        } else {
            // password invalid
            $password_validity = "invalid";
            $errormessage = 'Falsches Passwort!';
        }
    } else {
        // if no user was found
        $username_validity = "invalid";
        $password_validity = "unknown";
        $errormessage = 'Der eingegebene Benutzer wurde nicht gefunden!';
    }
}

// Put responses into array
$response = array('username' => $username_validity, 'password' => $password_validity, 'errormessage' => $errormessage);
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
?>
