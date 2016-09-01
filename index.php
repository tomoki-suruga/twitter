<?php
	session_start();
	require('dbconnect.php');

	if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
		$_SESSION['time'] = time();

	$sql = sprintf('SELECT * FROM members WHERE id=%d',
		mysqli_real_escape_string($db, $_SESSION['id'])
	);
	$record = mysqli_query($db, $sql) or die(mysqli_error($db));
	$member = mysqli_fetch_assoc($record);
	} else {

			header('Location:login.php');
			exit();
			}

			if (!empty($_POST)) {
				if ($_POST['message'] != '') {
					$sql = sprintf('INSERT INTO posts SET member_id=%d, message="%s",reply_post_id=%d,created=NOW()',
						mysqli_real_escape_string($db, $member['id']),
						mysqli_real_escape_string($db, $_POST['message']),
						mysqli_real_escape_string($db, $_POST['reply_post_id'])
					);
				mysqli_query($db, $sql) or die(mysqli_error($db));
						header('Location: index.php');
				exit();
				}
			}
			
			
			
	$page = $_REQUEST['page'];
	if($page == ''){
		$page = 1;
		}
	$page = max($page,1);
	
	$sql = 'SELECT COUNT(*) AS cnt FROM posts';
	$recordSet = mysqli_query($db,$sql);
	$table = mysqli_fetch_assoc($recordSet);
	$maxPage = ceil($table['cnt'] / 5);
	$page = min($page,$maxPage);
	
	$start = ($page - 1) * 5 ;
	$start = max(0,$start);
	
	$sql = sprintf('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT %d,5',
	$start
	);
	$posts = mysqli_query($db,$sql) or die(mysqli_error($db));
	
	
	if(isset($_REQUEST['res'])){
		$sql = sprintf('SELECT m.name,m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=%d ORDER BY p.created DESC',
			mysqli_real_escape_string($db,$_REQUEST['res'])
		);
		$record = mysqli_query($db,$sql) or die(mysqli_error($db));
		$table = mysqli_fetch_assoc($record);
		$message = '@' . $table['name'] . '  ' . $table['message'];
		}
		
	function makeLink($value) {
		return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>' , $value);
	}


	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
	$(function(){
		$(".form p").click(function(){
			if( $(".form-a").css("display") == "none"){
				$(".form-a").slideDown(500);
				$(".togi").show();
				$(".kaku").hide();
			}else{
				$(".form-a").slideUp(500);
				$(".togi").hide();
				$(".kaku").show();
			}

		});

	var res = location.search;

	if( ~res.indexOf('res')){$(".form-a").slideDown(500);
				$(".togi").show();
				$(".kaku").hide();}



	});
</script>
<title>ひとこと掲示板</title>
</head>

<body>
<div id="wrap" class="rap">
<div id="head">
<img  class="logo-top" src="images/top-siro-2.png">
<img src="images/top-img.jpg">
<h1>Tomoki's  twitter</h1>
</div>
<div id="content" class="content-index">
<div id="lead">
<div class="logout">
<a href="logout.php"> ログアウト</a>
</div>
<div class="content-index1">
<div class="content-index2">
<?php while($post = mysqli_fetch_assoc($posts)): ?>
    <div class="msg">
    
        <img src="member_picture/<?php echo htmlspecialchars($post['picture'],ENT_QUOTES,'UTF-8');?>" alt="<?php echo htmlspecialchars($post['name'],ENT_QUOTES,'UTF-8');?>" >
        <div class="msg-2">
        <p class="prof"><span class="name"><a href="prof.php?id=<?php echo htmlspecialchars($post['member_id'],ENT_QUOTES,'UTF-8');?>">(<?php echo htmlspecialchars($post['name'],ENT_QUOTES,'UTF-8');?>)</a></span>
        <span class="time"><?php echo htmlspecialchars($post['created'],ENT_QUOTES,'UTF-8');?></span>
        <p><?php echo makeLink(htmlspecialchars($post['message'],ENT_QUOTES,'UTF-8'));?>
        
        [<a href="index.php?res=<?php echo htmlspecialchars($post['id'],ENT_QUOTES,'UTF-8');?>">Re</a>]</p>
        
        <?php if($post['reply_post_id'] > 0):?>
        <a href="view.php?id=<?php echo htmlspecialchars($post['reply_post_id'],ENT_QUOTES,'UTF-8');?>">返信元のメッセージ</a>
        <?php endif;?>
        
		<?php
        if ($_SESSION['id'] == $post['member_id']):
        ?>
            [<a href="delete.php?id=<?php echo htmlspecialchars($post['id']); ?>" style="color: #F33;">削除</a>]
        <?php
        endif;
        ?>
        </p>
        </div>
    </div>
<?php endwhile ;?>
</div>
<div class="news">
<p>最近のNEWS</p>
<ul>
<li><span>2016-08-15</span>　　newsはダミーです。リンクはってません</li>
<li><span>2016-08-10</span>　　サイトをリニューアルしました</li>
<li><span>2016-08-03</span>　　最近また暑くなりましたね</li>
<li><span>2016-07-31</span>　　ツイッター風サイトを作りました。</li>
</ul>
</div>

</div>
<ul class="paging">
<?php if($page > 1){?>
<li><a href="index.php?page=<?php print($page-1);?>">前のページへ</a></li>
<?php }else{?>
<li></li>
<?php }?>
<?php if($page < $maxPage){?>
<li><a href="index.php?page=<?php print($page+1);?>">次のページへ</a></li>
<?php }else{?>
<li></li>
<?php }?>
</ul>


</div>
</div>
<div class="form">
<p><span class="kaku">messageを書く</span><span class="togi">閉じる</span></p>
<form action="" method="post" class="form-a">
<div class="content-index">

	<dl>
    	<dt><?php echo htmlspecialchars($member['name']); ?> さん、メッセージをどうぞ　　　　　　</dt>
    	<dd><textarea name="message" cols="70" rows="10"><?php echo htmlspecialchars($message,ENT_QUOTES,'UTF-8');?>
       
        </textarea><input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST['res'],ENT_QUOTES,'UTF-8');?>">
        </dd>
    </dl>
    <div class="form-div"><input type="submit" value="投稿する"></div>
    </div>
</form>
</div>
<div id="foot">
<p>tomoki twitter</p>
</div>

</div>
</body>
</html>
