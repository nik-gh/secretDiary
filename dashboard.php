<?php
  session_start();

  $diaryContent = '';

  if(array_key_exists('id', $_COOKIE)) {
    $_SESSION['id'] = $_COOKIE['id'];
  }

  if(array_key_exists('id', $_SESSION)) {
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

<nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="#">Secret Diary</a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>

<div class="container-fluid">
  <textarea class="form-control" id="diary">
    <?php echo $diaryContent; ?>
  </textarea>
</div>

<?php include('./partials/footer.php'); ?>