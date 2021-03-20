<?php
	require("./php/db_setting.php");
	$cls1 = $_GET['cls1'];
	$cls2 = $_GET['cls2'];
	$sec1 = $_GET['sec1'];
	$sec2 = $_GET['sec2'];

	$sort = $_GET['sort'];
	$order = $_GET['order'];
	$crtPage = $_GET['p'];
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>検索結果一覧 | 写真データベース</title>
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="css/custom.css" rel="stylesheet">
		<!-- Colorbox-->
		<link href="css/colorbox.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div id="loading">
			</div><!-- /#loading -->

			<div id="resultShow">
			</div><!-- /#resultShow -->
		</div>

		<p id="page-top"><a href="#wrap">PAGE TOP</a></p>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery-1.11.3.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<!-- Colorbox -->
		<script src="js/jquery.colorbox-min.js"></script>
		<!-- Layzr -->
		<script src="js/jquery.lazyload.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {

				function ajax(){
					showLoadingImage();
					$("#resultShow").empty();
					$.ajax({
						url: './php/getphotolist.php',
						type: 'GET',
						data: <?php
							print "{\n
								sec1 : '{$sec1}',\n
								sec2 : '{$sec2}',\n
								cls1 : '{$cls1}',\n
								cls2 : '{$cls2}',\n
								sort : '{$sort}',\n
								order : '{$order}',\n
								p : '{$crtPage}'\n
							},\n";
							?>
						success: function(resposnse){
							$("#resultShow").html(resposnse);
							hideLoadingImage();
							$(function() {
								$(".gallery").colorbox({
									rel:'slideshow',
									slideshow:false,
									slideshowSpeed:3000,
									maxWidth:"90%",
									maxHeight:"90%",
									opacity: 0.7,
									loop: false
								});
							});
							$('img.lazy').lazyload();
						},
						error: function(xhr){
							alert(xhr.resposnseText);
						},
					});
				}

				// クルクル画像表示
				function showLoadingImage() {
					$("#loading").append('<img src="./images/gif-load.gif" class="img-responsive img-responsive-overwrite" />');
				}
				// クルクル画像消去
				function hideLoadingImage() {
					$("#loading").empty();
				}
				//Page Top
				var topBtn = $('#page-top');
				topBtn.hide();
				//スクロールが100に達したらボタン表示
				$(window).scroll(function () {
					if ($(this).scrollTop() > 100) {
						topBtn.fadeIn();
					} else {
						topBtn.fadeOut();
					}
				});
				//スクロールしてトップ
				topBtn.click(function () {
					$('body,html').animate({
						scrollTop: 0
					}, 500);
					return false;
				});
			
				ajax();
			});
		</script>
	</body>
</html>