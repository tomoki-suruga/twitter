<?php
	session_start();
	require('../dbconnect.php');

	if(!isset($_SESSION['join'])){
		header('Location: index.php');
		exit();
		}

		if(!empty($_POST)){

			$sql = sprintf('INSERT INTO members SET name="%s", email="%s",password="%s", picture="%s", background="%s", created="%s"',
			mysqli_real_escape_string($db, $_SESSION['join']['name']),
			mysqli_real_escape_string($db, $_SESSION['join']['email']),
			mysqli_real_escape_string($db, $_SESSION['join']['password']),
			mysqli_real_escape_string($db, $_SESSION['join']['image']),
			mysqli_real_escape_string($db, $_SESSION['join']['back-image']),
			date('Y-m-d H:i:s')
			);
			mysqli_query($db, $sql) or die(mysqli_error($db));
			unset($_SESSION['join']);

			header('Location: thanks.php');
			exit();
			}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../style.css" />
<title>会員登録</title>
</head>

<body>
<div id="wrap">
<div id="head">
<img  class="logo-top" src="../images/top-siro-2.png">
<img src="../images/top-img.jpg">
<h1>会員登録</h1>
</div>

<div id="content">
<div id="lead-2" class="login-page">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" >
<input type="hidden" name="action" value="submit" />
	<dl>
    	<dt class="login-f">ニックネーム</dt>
    	<dd class="login-f"><?php echo htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES,'UTF-8');?> </dd>

    	<dt class="login-f">メールアドレス</dt>
    	<dd class="login-f"><?php echo htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES,'UTF-8');?></dd>

        <dt class="login-f" class="login-f">パスワード</dt>
    	<dd class="login-f">【表示されません】</dd>

        <dt class="login-f">写真など</dt>
    	<dd class="login-i"><img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES,'UTF-8');?>" width="100" height="100" alt="" /></dd>
    	<dt class="login-f">写真など</dt>
    	<dd class="login-i"><img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['back-image'],ENT_QUOTES,'UTF-8');?>" width="100" height="100" alt="" /></dd>
    </dl>
    <div class="btn">
    <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
    ｜
    <input type="submit" value="登録する"></div>
</form>
</div>
</div>
<div id="foot">
<p>tomoki twitter</p>
</div>

</div>
</body>
</html>
