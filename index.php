<?php
    session_start();

    $db = new PDO(
      'mysql:host=localhost;dbname=akun',
      'root'
    );

    if ( isset($_COOKIE["id"]) && isset($_COOKIE["username"]) ) {
      $data = $db->prepare("SELECT username FROM login WHERE id=?");
      $data->execute([$_COOKIE["id"]]);
      $row = $data->fetch();

      if ( $_COOKIE["username"] === hash("md5", $row["username"]) ) {
        $_SESSION["login"] = true;
      }
    }

    if ( isset($_SESSION["login"]) ) {
      header("Location: home.php");
      exit;
    }

    if ( isset($_POST['submit']) ) {
      $data = $db->prepare("SELECT * FROM login WHERE username=? AND password=?");
      $data->execute([$_POST['username'], $_POST['password']]);
      $count = $data->rowCount();
      $row = $data->fetch();
      if ( $count === 1 ) {
        $_SESSION["login"] = true;

        if ( isset($_POST["remember"]) ) {
          setcookie('id', $row['id'], time()+700);
          setcookie('username', hash("md5", $row['username']), time()+700);
        }

        header("Location: home.php");
        exit;
      }
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
<form method="POST">
<div class="login-box"  style="color:black">
  <h1>Login</h1>
  <div class="textbox" >
    <i class="fas fa-user"></i>
    <input type="text" name="username" placeholder="Username" style="color:black">
  </div>

  <div class="textbox">
    <i class="fas fa-lock"></i>
    <input type="password" name="password" placeholder="Password" style="color:black">
  </div>
  <div class="field-group">
		<div><input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["member_login"])) { ?> checked <?php } ?> />
		<label for="remember-me">Remember me</label>
	</div>
  <h5>username ,password : robi</h5>
  <input type="submit" class="btn" name="submit" value="Submit" style="color:black">
</div>
</form>

  <style type="text/css">
  body{
  margin: 0;
  padding: 0;
  font-family: sans-serif;
  background: url(bg2.jpg) no-repeat;
  background-size: cover;
  }
  body{
    margin:50px 0px; padding:0px;
    text-align:center;
    align:center
  
  }
  }
  </style>
</html>
