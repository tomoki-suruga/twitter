<?php
	session_start();
	require('dbconnect.php');
	if(empty($_REQUEST['id'])){
		header('Location: index.php');
		exit();
		}
	$sql = sprintf('SELECT * FROM members WHERE id=%d',
		mysqli_real_escape_string($db, $_REQUEST['id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$member = mysqli_fetch_assoc($record);

	$sql = sprintf('SELECT m.name, m.picture, m.background, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.member_id=%d ORDER BY p.created DESC',
		mysqli_real_escape_string($db,$_REQUEST['id'])
	);
	
	$posts = mysqli_query($db,$sql) or die(mysqli_error($db));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ひとこと掲示板</title>
</head>

<body>
<div id="wrap" class="rap">
<div class="prof">
	<div class="top-prof">
		<img src="member_picture/<?php echo htmlspecialchars($member['background'],ENT_QUOTES,'UTF-8');?>" height="250px" alt="<?php echo htmlspecialchars($member['name'],ENT_QUOTES,'UTF-8');?>" >
	</div>
	
</div>

<div id="content" class="prof-con">
	<div class="text-prof">
			<img src="member_picture/<?php echo htmlspecialchars($member['picture'],ENT_QUOTES,'UTF-8');?>" width="48" height="48" alt="<?php echo htmlspecialchars($member['name'],ENT_QUOTES,'UTF-8');?>" >
			<p class="prof-name"><a href="prof.php?id=<?php echo htmlspecialchars($member['id'],ENT_QUOTES,'UTF-8');?>">(<?php echo htmlspecialchars($member['name'],ENT_QUOTES,'UTF-8');?>)</a></p>
			<p class="prof-text"><?php echo htmlspecialchars($member['text'],ENT_QUOTES,'UTF-8');?></p>
	</div>
	<div class="logout">
		<a href="logout.php"> ログアウト</a>
	</div>
	<div class="twittet">
	<p>&laquo;<a href="index.php">一覧に戻る</a></p>
	<?php while($post = mysqli_fetch_assoc($posts)):?> 
	    <div class="msg">
	    
	        <img src="member_picture/<?php echo htmlspecialchars($post['picture'],ENT_QUOTES,'UTF-8');?>" alt="<?php echo htmlspecialchars($post['name'],ENT_QUOTES,'UTF-8');?>" >
	        <div class="msg-2">
	        <p class="prof">
	        <span class="name"><a href="prof.php?id=<?php echo htmlspecialchars($post['member_id'],ENT_QUOTES,'UTF-8');?>">(<?php echo htmlspecialchars($post['name'],ENT_QUOTES,'UTF-8');?>)</a></span>
	        <span class="time"><?php echo htmlspecialchars($post['created'],ENT_QUOTES,'UTF-8');?></span>
	        </p>
	        <?php echo htmlspecialchars($post['message'],ENT_QUOTES,'UTF-8');?>
	        [<a href="index.php?res=<?php echo htmlspecialchars($post['id'],ENT_QUOTES,'UTF-8');?>">Re</a>]
	        
	        </div>
	    </div>

	<?php endwhile;?>
	</div>

</div>
<div id="foot">
<p>tomoki twitter</p>
</div>

</div>
</body>
</html>
