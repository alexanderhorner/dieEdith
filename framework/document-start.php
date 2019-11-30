<?php
session_start();
if (isset($_COOKIE['color_scheme'])) {

  // Check wich Theme setting is chosen
  if ($_COOKIE['color_scheme'] == "auto") {

    // check what the last calculated auto value
    if (isset($_COOKIE['last_color_scheme'])) {
      if ($_COOKIE['last_color_scheme'] == "light") {
        $class = "light";
      } else {
        $class = "dark";
      }
    } else {
      $class = "light";
    }

  } else if ($_COOKIE['color_scheme'] == "light") {
    $class = "light";
  } else {
    $class = "dark";
  }

} else {
  setcookie('color_scheme', 'auto', 2147483647, '/');
  $class = "light";
}

?>
<!DOCTYPE html>
<html class="<?php echo $class ?>" lang="de">
