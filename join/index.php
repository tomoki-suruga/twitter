<?php
	
	session_start();
	require('../dbconnect.php');
	
	if(!empty($_POST)){
		
		if($_POST['name'] == ""){
			$error['name'] = 'blank';
			}
			
			if($_POST['email'] == ""){
			$error['email'] = 'blank';
			}
			
			if($_POST['password'] == ""){
			$error['password'] = 'blank';
			}
			
			if($_POST['password'] < 4){
			$error['password'] = 'length';
			}
			
			$fileName = $_FILES['image']['name'];
			if (!empty($fileName)) {
				$ext = substr($fileName, -3);
				if ($ext != 'jpg' && $ext != 'gif') {
					$error['image'] = 'type';
				}
			}
			
	if(empty($error)){
		//重複カウント
		
		$sql = sprintf('SELECT COUNT(*) AS cnt FROM members WHERE email="%s"',
			mysqli_real_escape_string($db,$_POST['email'])
		);
		$record = mysqli_query($db,$sql) or die(mysqli_error($db));
		$table = mysqli_fetch_assoc($record);
		if($table['cnt'] > 0){
			$error['email'] = 'duplicate';
			}
		}
	
	
	if (empty($error)) {
	// 画像をアップロードする　
	$image = date('YmdHis') . $_FILES['image']['name'];
	move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);

	$image2 = date('YmdHis') . $_FILES['back-image']['name'];
	move_uploaded_file($_FILES['back-image']['tmp_name'], '../member_picture/' . $image2);

	$_SESSION['join'] = $_POST;
	$_SESSION['join']['image'] = $image;
	$_SESSION['join']['back-image'] = $image2;
		header('Location: check.php');
		exit();
	}
}
	
	if($_REQUEST['action'] == 'rewrite'){
		$_POST = $_SESSION['join'];
		$error['rewrite'] = true;
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

<div id="content" >
<div id="lead-2" class="login-page">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
    	<dt class="login-f">ニックネーム<span class="required">必須</span></dt>
    	<dd class="login-f"><input type="text" name="name" size="20" maxlangth="255" placeholder="ニックネーム"
        value="<?php echo htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8');?>" />
        
        <?php if ($error['name'] == 'blank'): ?>
        <p class="error">* ニックネームをつけてください</p>
        <?php endif; ?>
        </dd>
    
    	<dt class="login-f">メールアドレス<span class="required">必須</span></dt>
    	<dd class="login-f"><input type="text" name="email" size="20" maxlangth="255" placeholder="email"
        value="<?php echo htmlspecialchars($_POST['email'],ENT_QUOTES,'UTF-8');?>"/>
        
        <?php if($error['email'] == 'blank'):?>
        <p class="error">メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if($error['email'] == 'duplicate'):?>
        <p class="error">*指定されたメールアドレスは既に登録されています</p>
        <?php endif; ?>
        </dd>
        
        <dt class="login-f">パスワード<span class="required">必須</span></dt>
    	<dd class="login-f"><input type="password" name="password" size="10" maxlangth="20" placeholder="password"
        value="<?php echo htmlspecialchars($_POST['password'],ENT_QUOTES,'UTF-8');?>"/>
        
        <?php if($error['password'] == 'blank'):?>
        <p class="error">パスワードを入力してください</p>
        <?php endif; ?>
        
        <?php if($error['password'] == 'length'):?>
        <p class="error">パスワードを4文字以上で入力してください</p>
        <?php endif; ?>
        </dd>
        
        <dt class="login-f">写真など</dt>
    	<dd class="login-i"><input type="file" name="image" size="20" value="test" />
			<?php if ($error['image'] == 'type'): ?>
			<p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください</p>
			<?php endif; ?>
			<?php if (!empty($error)): ?>
			<p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
			<?php endif; ?>
		</dd>
		<dt class="login-f">バックグラウンドイメージ</dt>
    	<dd class="login-i"><input type="file" name="back-image" size="20" value="test" />
			<?php if ($error['back-image'] == 'type'): ?>
			<p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください</p>
			<?php endif; ?>
			<?php if (!empty($error)): ?>
			<p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
			<?php endif; ?>
		</dd>
    <div class="btn"><input type="submit" value="入力内容を確認する"></div>
</form>
</div>
</div>

<div id="foot">
<p>tomoki twitter</p>
</div>

</div>
</body>
</html>
