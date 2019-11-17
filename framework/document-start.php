<?php
session_start();
if (isset($_COOKIE['prefers_color_scheme'])) {
  if ($_COOKIE['prefers_color_scheme'] == "dark") {
    $prefers_color_scheme = "dark";
  } else {
    $prefers_color_scheme = "light";
  }
} else {
  setcookie('prefers_color_scheme', 'light', 2147483647, '/');
  $prefers_color_scheme = "light";
}

?>
<!DOCTYPE html>
<html class="<?php echo $prefers_color_scheme ?>" lang="de">
