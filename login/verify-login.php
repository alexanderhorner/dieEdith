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
    $statement = $pdo->prepare("SELECT id, password, firstname, lastname, prefers_color_scheme_dark FROM user WHERE username = ?");

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
        $prefers_color_scheme_dark = $row['prefers_color_scheme_dark'];
        // check if password is valid
        if (password_verify($post_password, $password_hash)) {
            // password valid
            $password_validity = "valid";
            $errormessage = 'Login erflogreich! Du wirst weitergeleitet...';
            // set Session variables
            session_start();
            $_SESSION['userid'] = $userid;
            $_SESSION['username'] = $post_username;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            if ($prefers_color_scheme_dark == 1) {
              setcookie('darkmode', 'true', 2147483647, '/');
            } else {
              setcookie('darkmode', 'false', 2147483647, '/');
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
        $errormessage = 'Der eingegebene Benutzer existiert nicht! Hast du dich verschrieben?';
    }
}

// Put responses into array
$response = array('username' => $username_validity, 'password' => $password_validity, 'errormessage' => $errormessage);
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
?>
