<?php
	require("./php/db_setting.php");

	try {
		//connect
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//tblclass1の全件抽出
		$stmtCls1 = $db->query('select * from tblclass1');
		$class1 = $stmtCls1->fetchAll(PDO::FETCH_ASSOC);

		//tblclass2の全件抽出
		$stmtCls2 = $db->query('select * from tblclass2');
		$class2 = $stmtCls2->fetchAll(PDO::FETCH_ASSOC);

		//tblsection1の全件抽出
		$stmtSec1 = $db->query('select * from tblsection1');
		$section1 = $stmtSec1->fetchAll(PDO::FETCH_ASSOC);

		//tblsection2の全件抽出
		$stmtSec2 = $db->query('select * from tblsection2');
		$section2 = $stmtSec2->fetchAll(PDO::FETCH_ASSOC);


		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>写真を探す | 写真データベース</title>
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="css/custom.css" rel="stylesheet">
		<!-- Colorbox-->
		<link href="css/colorbox.css" rel="stylesheet">
	</head>
	<body>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class ="navbar-brand" href="#">Photo DB</a>
				</div><!-- /.navbar-header -->
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="index.html">Home</a></li>
						<li class="active"><a href="search.php">写真を探す</a></li>
						<li><a href="add.php">写真を登録する</a></li>
					</ul>

				</div><!-- /#navbar -->
			</div><!-- /.container-fluid -->
		</nav>

		<!-- Begin page content -->
		<div class="container">
			<div class="page-header">
				<h1>写真を探す</h1>
			</div>

			<div id="select-form">
				<form id="searchForm" target="_blank" action="./showresult.php" method="GET" class="form-horizontal">

					<div id="section" class="form-group">
						<label for="inputSection1" class="col-sm-2 control-label">部門１</label>
						<div class="col-sm-4">
							<select id="sec1" name="sec1" class="form-control">
								<option value="0">--指定なし(選択してください)--</option>
								<?php
									foreach ($section1 as $sec1) {
										print "<option value=\"{$sec1['id']}\">{$sec1['sectionName']}</option>\n";
									}
								?>
							</select>
						</div>
						<label for="inputSection2" class="col-sm-2 control-label">部門２</label>
						<div class="col-sm-4">
							<select id="sec2" name="sec2" class="form-control">
								<option value="0">--指定なし(選択してください)--</option>
								<?php
									foreach ($section2 as $sec2) {
										print "<option value=\"{$sec2['id']}\">{$sec2['sectionName']}</option>\n";
									}
								?>
							</select>
						</div>
					</div><!-- /#section -->

					<div id="class" class="form-group">
						<label for="inputClass1" class="col-sm-2 control-label">分類１</label>
						<div class="col-sm-4">
							<select id="cls1" name="cls1" class="form-control">
								<option value="0">--指定なし(選択してください)--</option>
								<?php
									foreach ($class1 as $cls1) {
										print "<option value=\"{$cls1['id']}\">{$cls1['className']}</option>\n";
									}
								?>
							</select>
						</div>
						<label for="inputClass2" class="col-sm-2 control-label">分類２</label>
						<div class="col-sm-4">
							<select id="cls2" name="cls2" class="form-control">
								<option value="0">--指定なし(選択してください)--</option>
								<?php
									foreach ($class2 as $cls2) {
										print "<option value=\"{$cls2['id']}\">{$cls2['className']}</option>\n";
									}
								?>
							</select>
						</div>
					</div><!-- /#class -->

					<div id="sort-order" class="form-group">
						<label for="inputSort" class="col-sm-2 control-label">並べ替え</label>
						<div class="col-sm-4">
							<select id="sort" name="sort" class="form-control">
								<option value="0">登録順</option>
								<option value="1">西暦順</option>
							</select>
						</div>
						<label for="inputOrder" class="col-sm-2 control-label">順序</label>
						<div class="col-sm-4">
							<select id="order" name="order" class="form-control">
								<option value="0">昇順</option>
								<option value="1">降順</option>
							</select>
						</div>
					</div><!-- /#sort-order -->

					<div id="submit" class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button id="searchButton" type="submit" class="btn btn-default">検索</button>
						</div>
					</div><!-- /#submit -->
					<input type="hidden" value="1" name="p" />
				</form>
			</div><!-- /#select-form -->

			<div id="loading">
			</div><!-- /#loading -->

			<div id="resultShow">
				<p class="lead">部門・分類・表示順序を選択して、検索ボタンをクリックしてください。</p>
			</div><!-- /#resultShow -->

		</div>

		<footer class="footer">
			<div class="container">
				<p class="text-muted">Copyright (C) 2015 Keisuke Inoue. All Right Reserved.</p>
			</div>
		</footer>
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
				//１のセレクトボックスの値によって２の内容を変更
				$("#cls1").change(function() {
					$.get("./php/db_gcls2.php?cls1="+$(this).val(), function(data) {
						$("#cls2").html(data);
					});
				});
				$("#sec1").change(function() {
					$.get("./php/db_gsec2.php?sec1="+$(this).val(), function(data) {
						$("#sec2").html(data);
					});
				});
			});
		</script>
	</body>
</html>