<?php

// GUID generator
//
// header('Content-Type: text/plain');
//
// include 'mysqlcredentials.php';
// function getGUID(){
//     if (function_exists('com_create_guid')){
//         return com_create_guid();
//     }else{
//         mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
//         $charid = strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
//         $hyphen = chr(45);// "-"
//         $uuid = substr($charid, 0, 8).$hyphen
//             .substr($charid, 8, 4).$hyphen
//             .substr($charid,12, 4).$hyphen
//             .substr($charid,16, 4).$hyphen
//             .substr($charid,20,12);// "}"
//         return $uuid;
//     }
// }
//
// // prepare statement
// $statement1 = $pdo->prepare("SELECT id FROM posts");
//
// // execute statement
// $statement1->execute();
//
// while($row = $statement1->fetch()) {
//   $statement2 = $pdo->prepare("UPDATE `posts` SET `GUID`=? WHERE id=?");
//
//   $GUID = getGUID();
//   // execute statement
//   $statement2->execute(array($GUID, $row['id']));
//
//   echo $GUID;
//   echo "     ";
//   echo $row['id'];
//   echo "\n";
// }

 ?>
