<!-- Status? -->

<?php
session_start();

require_once __DIR__ . '/framework/randomID.php';

if (!isset($_SESSION['UID'])) {
    echo 'not logged in';
    die();
} else {
    if ($_SESSION['UID'] != 'UoaWWOeSsGk') {
        echo 'not alexander';
        die();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = strtolower($_POST['lastname']).'.'.strtolower($_POST['firstname']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $newUID = "U".random_str(10);


    // Request
    // Connenct to database
    include 'framework/mysqlcredentials.php';

    // Check connection
    if ($pdo === false) {
        echo 'pdo false';
        die();
    } else {

        // Update database
        // prepare statement
        $statement = $pdo->prepare("INSERT INTO `user` (`UID`, `username`, `password`, `firstname`, `lastname`, `role`) VALUES (?, ?, ?, ?, ?, ?)");

        // execute statement
        $newPID = 'P'.random_str();
        $statement->execute(array($newUID, $username, $password, $_POST['firstname'], $_POST['lastname'], $_POST['role']));

        // check response from statement
        if($statement->errorCode() != 0) {
            $stmntError = $statement->errorInfo();
            echo 'mysql error 1';
            die();
        } else {

            // Check how many rows were affected
            if ($statement->rowCount() <= 0) {
                echo 'mysql error 2';
                die();
            } else {
                mkdir('user/'.$newUID);
                mkdir('user/'.$newUID.'/'.$username);
                echo 'success';
                die();
            }
        }


    }

}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User</title>
</head>
<body>
    <form action="newuser.php" method="post">
        <label for="firstname">First name:</label>
        <input type="text" autocomplete="given-name" name="firstname"> <br>

        <label for="lastname">Last name:</label>
        <input type="text" autocomplete="family-name" name="lastname"> <br>

        <label for="Password">Password:</label>
        <input type="password" autocomplete="new-password" name="password"> <br>

        <label for="role">Rolle:</label>
        <select name="role">
            <option autofocus value="Schüler">Schüler</option>
            <option value="Lehrer">Lehrer</option>
            <option value="Schülerzeitung">Schülerzeitung</option>
            <option value="Admin">Admin</option>
        </select> <br>

        <input type="submit">
    </form>
</body>
</html>