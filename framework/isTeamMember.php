<?php function isTeamMember()
{
  if (isset($_SESSION['UID'])) {
    if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Schülerzeitung') {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
?>