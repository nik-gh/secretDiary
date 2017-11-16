<?php
session_start();

if (array_key_exists('content', $_POST)) {
  include('./connection.php');

  $cleanContent = mysqli_real_escape_string($db, $_POST['content']);
  $cleanId = mysqli_real_escape_string($db, $_SESSION['id']);
  $query = "UPDATE user SET diary_text = '"
  .$cleanContent."' WHERE id = '"
  .$cleanId."' LIMIT 1";

  mysqli_query($db, $query);
}

?>