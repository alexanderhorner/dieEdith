<?php
session_start();
$status = "failure";
$error = "unknown error";
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $teacherid = $_POST["teacherid"];
    $category = substr($_POST["category"], 0, 20);
    if ($teacherid == "" or $category == "") {
        $error = "at least one parameter is empty";
        goto end;
    }
    include '../mysqlcredentials.php';
    if ($pdo === false) {
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    } else {
        if ($category != '') {
            $column = "id_".$category;
        }
        $statement = $pdo->prepare("UPDATE allusers SET $column = ? WHERE id = ?");
        $statement->execute(array($teacherid, $userid));
        $status = "success";
        $error = "none";
    }
} else {
    $status = "fail";
    $error = "not logged in";
};
end:
$arr = array('status' => $status, 'error' => $error, 'category' => $category, 'teacherid' => $teacherid);
echo json_encode($arr, JSON_PRETTY_PRINT);
