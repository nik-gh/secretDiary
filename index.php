<?php
  session_start();

  if (array_key_exists('logout', $_GET)) {
    unset($_SESSION['id']);
    setcookie('id', '', time() - 3600);
    $_COOKIE['id'] = '';
  } else if ((array_key_exists('id', $_SESSION) && $_SESSION['id']) ||
   (array_key_exists('id', $_COOKIE) && $_COOKIE['id'])) {
    header("Location: dashboard.php");
  }

  $error = '';
  if (array_key_exists('submit', $_POST)) {

    if (!$_POST['email']) {
      $error .= 'Email required<br>';
    }

    if (!$_POST['pass']) {
      $error .= 'Password required<br>';
    }

    if ($error != '') {
      $error = '<p>There were some errors:<br>'.$error.'</p>';
    } else {
      include('connection.php');

      $cleanEmail = mysqli_real_escape_string($db, $_POST['email']);
      $cleanPass = mysqli_real_escape_string($db, $_POST['pass']);
      $hashedPass = password_hash($cleanPass, PASSWORD_BCRYPT);

      if($_POST['signup'] == '1') {

        $query = "SELECT id FROM user WHERE email='".$cleanEmail."' LIMIT 1";
        $result = mysqli_query($db, $query);

        if (mysqli_num_rows($result) > 0) {
          $error = 'Email is already taken.';
        } else {
          $query = "INSERT INTO user (email, password) VALUES ('".$cleanEmail."','".$hashedPass."')";

          if (!mysqli_query($db, $query)) {
            $error = "<p>Could not sigh up - Try again.</p>";
          } else {
            $_SESSION['id'] = mysqli_insert_id($db);
            if ($_POST['stayLogged'] == '1') {
              setcookie('id', mysqli_insert_id($db), time() + 60 * 60 * 24 * 7);
            }
            header("Location: dashboard.php");
          }
        }
      } else {
        $query = "SELECT id, email, password FROM user WHERE email='".$cleanEmail."' LIMIT 1";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row && array_key_exists('id', $row)) {
          if (password_verify($cleanPass, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            if ($_POST['stayLogged'] == '1') {
              setcookie('id', $row['id'], time() + 60 * 60 * 24 * 7);
            }
            header("Location: dashboard.php");
          } else {
            $error = 'Bad username or password.';
          }
        } else {
          $error = 'Bad username or password.';
        }
      }
    }
  }
  
  include('./partials/header.php');
?>

    <div class="container" id="homeContainer">
      <h1>Secret Diary</h1>
      <p><strong>Save your memory</strong></p>
      <div id="error"><?php echo $error; ?></div>

      <form method="post" id="signupForm">
        <p>Don't wait, join now</p>
        <fieldset class="form-group">
          <input class="form-control" type="email" name="email" placeholder="Enter email">
        </fieldset>
        <fieldset class="form-group">
          <input  class="form-control" type="password" name="pass" placeholder="Enter password">
        </fieldset>
        <div class="checkbox">
          <label for="stayLogged">
            <input type="checkbox" name="stayLogged" value=1> Stay Logged In
          </label>
        </div>
        <fieldset class="form-group">
          <input type="hidden" name="signup" value="1">
          <input class="btn btn-success" type="submit" name="submit" value="Sign Up">
        </fieldset>
        <p><a href="#" class="toggleForm">Log In</a></p>
      </form>

      <form method="post" id="loginForm">
        <p>Access your memory with email and password</p>
        <fieldset class="form-group">
          <input class="form-control" type="email" name="email" placeholder="Enter email">
        </fieldset>
        <fieldset class="form-group">
          <input class="form-control" type="password" name="pass" placeholder="Enter password">
        </fieldset>
        <div class="checkbox">
          <label for="stayLogged">
            <input type="checkbox" name="stayLogged" value=1> Stay Logged In
          </label>
        </div>
        <fieldset class="form-group">
          <input type="hidden" name="signup" value="0">
          <input class="btn btn-success" type="submit" name="submit" value="Log In">
        </fieldset>
        <p><a href="#" class="toggleForm">Sign Up</a></p>
      </form>
    </div>
<?php include('./partials/footer.php') ?>