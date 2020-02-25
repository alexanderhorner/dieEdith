<?php
session_start();
// Set up all variables
$errormessage = $post_username = $post_password = "";
$error = 0;
$post_username = $_POST["username"];
$post_password = $_POST["password"];
$username_validity = "error";
$password_validity = "error";


if (!isset($_SESSION['UID'])) {
  // Connenct to database
  include '../framework/mysqlcredentials.php';

  // Check connection
  if ($pdo === false) {
      die("ERROR: Could not connect. " . $mysqli->connect_error);
  } else {

      // prepare statement
      $statement = $pdo->prepare("SELECT `UID`, `role`, `password`, `firstname`, `lastname`, `color_scheme` FROM user WHERE username = ?");

      // execute statement and put response into array
      $statement->execute(array($post_username));

      // fetch
      $row = $statement->fetch();

      //check if there was a user was found
      if ($statement->rowCount() > 0) {
          // username valid
          $username_validity = "valid";
          // put data in variable
          $UID = $row['UID'];
          $role = $row['role'];
          $password_hash = $row['password'];
          $firstname = $row['firstname'];
          $lastname = $row['lastname'];
          $color_scheme = $row['color_scheme'];

          // check if password is valid
          if (password_verify($post_password, $password_hash)) {
              // password valid
              $password_validity = "valid";
              $errormessage = 'Richtig!';

              // set Session variables
              $_SESSION['UID'] = $UID;
              $_SESSION['role'] = $role;
              $_SESSION['username'] = $post_username;
              $_SESSION['firstname'] = $firstname;
              $_SESSION['lastname'] = $lastname;
              $PHPSESSID = $_COOKIE['PHPSESSID'];
              setcookie('PHPSESSID', $PHPSESSID, time()+604800, '/', '', FALSE, TRUE);

              if ($color_scheme == 'auto') {
                setcookie('color_scheme', 'auto', 2147483647, '/');
              } else if ($color_scheme == 'light'){
                setcookie('color_scheme', 'light', 2147483647, '/');
              } else {
                setcookie('color_scheme', 'dark', 2147483647, '/');
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
} else {
  $error = 1;
  $errormessage = "Du bist schon angemeldet!";
}
// Put responses into array
$response = array('error' => $error, 'username' => $username_validity, 'password' => $password_validity, 'errormessage' => $errormessage);
// Encode response array into json
echo json_encode($response, JSON_PRETTY_PRINT);
?>
