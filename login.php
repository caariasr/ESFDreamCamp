<?php
$action = (!empty($_POST['login']) &&
($_POST['login'] === 'Log in')) ? 'login'
: 'show_form';
switch($action)
{
case 'login':
  require('security/session.php');
  require('security/user.php');
  $user = new User();
  $username = $_POST['username'];
  $password = $_POST['password'];
  if ($user->authenticate($username, $password)) {
    header('location: background.php');
    exit;
  } else {
    $errorMessage = "Username/password did not match.";
    break;
  }
case 'show_form':
default:
  $errorMessage = NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/main.css" />
<title>ESF Dream Camp Protected Page: Please enter password</title>>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,400italic' rel='stylesheet' type='text/css'>
<body/>
<div class="overflow">
                <div id="banner">
                        <div id="banner-head"></div>
                        <h1>
                                <span id="pottstown">ESF Dream</span> <span id="health">Evaluation Reporting System</span>
                        </h1>
                        <nav>
                                <div id="nav-left">
                                        <a href="background.php">Background Data</a>
                                        <a href="outcome.php">Outcome Data</a>
                                </div>
                                <div id="nav-right">
                                        <a href="#support">Support</a>
<a href="logout.php">Log Out</a>
                                </div>
                        </nav>
</div>
<div id="background"></div>
<div id="outcome"></div>
<div id="support"></div>
</body>
</head>
<body>
<div id="contentarea">
<div id="innercontentarea">
<div id="login-box">
<div class="inner">
<form id="login" action="login.php" method="post" accept-charset="utf-8">
<ul>
<?php if(isset($errorMessage)): ?>
<li><?php echo $errorMessage; ?></li>
<?php endif ?>
<li>
<label>Username </label>
<input class="textbox" tabindex="1" type="text" name="username" autocomplete="off"/>
</li>
<li>
<label>Password </label>
<input class="textbox" tabindex="2" type="password" name="password"/>
</li>
<li>
<input id="login-submit" name="login" tabindex="3" type="submit" value="Log in" />
</li>
<li class="clear"></li>
</ul>
</form>
</div>
</div>
</div>
</div>
</body>
</html>

