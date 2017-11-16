<?php
  session_start();

  $diaryContent = '';

  if(array_key_exists('id', $_COOKIE)) {
    $_SESSION['id'] = $_COOKIE['id'];
  }

  if(array_key_exists('id', $_SESSION)) {
    echo "<p class='head'><a href='index.php?logout=1'>Log out</a></p>";
    include('./connection.php');
    $cleanId = mysqli_real_escape_string($db, $_SESSION['id']);
    $query = "SELECT diary_text FROM user WHERE id = '".$cleanId."' LIMIT 1";
    $row = mysqli_fetch_assoc(mysqli_query($db, $query));
    $diaryContent = $row['diary_text'];
  } else {
    header("Location: index.php");
  }

  include('./partials/header.php');
?>

<div class="container-fluid">
  <textarea class="form-control" id="diary"><?php echo $diaryContent; ?></textarea>
</div>

<?php include('./partials/footer.php'); ?>