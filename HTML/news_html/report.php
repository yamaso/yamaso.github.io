<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Plugins News</title>
	<!-- Bootstrap core CSS -->
    <link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
	<!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<style>
		div {
			float: left;
			background: #ffffff;
			width: 1000px;
			padding: 10px;
			text-align: left;
			border: 2px solid #87CEBB;
			margin: 10px 2px 5px 2px;
		}
	</style>
</head>
<body>
	<p><a class="btn btn-default" href="http://webdesign.center.wakayama-u.ac.jp/~s236307/myweb/HTML/index.html" role="button">トップページへ戻る &raquo;</a></p>
	<h1>ICON新着記事</h1>
        <div class="container">
			<div class="col-12 text-center">
				<h3>DAW用のプラグインなどの紹介をするWebサイト、ICONから、新着記事をスクレイピングしています。フリーで優秀なプラグインなどを見つけましょう！</h3>
			</div>
			<?php
				require_once("phpQuery-onefile.php");
				require_once("pathChanger.php");
				echo '<div>';
				$root_url = 'https://icon.jp/';
				$url = $root_url; 
				$doc = phpQuery::newDocumentFileHTML($url);
				echo '取得サイト:'.$doc['title']->text().'<br>';
				echo $doc['.post-box-wrap'];
				echo '</div>';
			?>
		</div>
</body>

</html>