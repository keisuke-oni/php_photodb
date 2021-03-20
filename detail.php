<?php
	require("./php/db_setting.php");
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>写真の詳細 | 写真データベース</title>
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="css/custom.css" rel="stylesheet">
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
					<ul class="nav navbar-nav navbar-right">
						<li class="active"><a href="#">写真の詳細</a></li>
					</ul>
				</div><!-- /#navbar -->
			</div><!-- /.container-fluid -->
		</nav>

		<!-- Begin page content -->
		<div class="container">
			<div class="page-header">
				<h1>写真の詳細</h1>
			</div>
<?php
	if(!isset($_GET['id'])){
		print "データが不正です。やり直してください。\n";
		exit;
	}
	$id = $_GET['id'];

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
		//全分類を配列に格納
		$cls = array();
		$cls[0] = "未登録";
		foreach ($class2 as $cls2) {
			$cls[$cls2['id']] = $cls2['className'];
		}

		//tblsection1の全件抽出
		$stmtSec1 = $db->query('select * from tblsection1');
		$section1 = $stmtSec1->fetchAll(PDO::FETCH_ASSOC);

		//tblsection2の全件抽出
		$stmtSec2 = $db->query('select * from tblsection2');
		$section2 = $stmtSec2->fetchAll(PDO::FETCH_ASSOC);
		//全部門を配列に格納
		$sec = array();
		$sec[0] = "未登録";
		foreach ($section2 as $sec2) {
			$sec[$sec2['id']] = $sec2['sectionName'];
		}

		//GETで取得したidから写真の情報を取得
		$stmtPhoto = $db->prepare("select * from tblpicture where id = ?");
		$stmtPhoto->execute([$id]);
		$photo = $stmtPhoto->fetchAll(PDO::FETCH_ASSOC);
		//情報を変数に格納
		foreach ($photo as $pht) {
			$fileName = $pht['fileName'];
			$fileOriginal = $pht['fileOriginal'];
			$contents = $pht['contents'];
			$yYear = $pht['yYear'];
			$mMonth = $pht['mMonth'];
			$dDay = $pht['dDay'];
			$section = $pht['section'];
			$class = $pht['class'];
			$description = $pht['description'];
		}

		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}

?>
			<div id="photoShow">
				<h2><?php echo $fileName; ?></h2>
				<img src="./photos/<?php echo $fileName; ?>" class="img-responsive img-responsive-overwrite" />
				<br />
				<p class="text-center"><a href="./php/downphoto.php?id=<?php echo $id; ?>"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> この写真をダウンロード</button></a></p>
			</div>
			<div id="photoForm">
				<h2>写真の情報</h2>
				<form id="changeForm" method="POST" class="form-horizontal">

					<div id="change" class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button id="changeButton" type="button" class="btn btn-default">変更</button>
						</div>
					</div><!-- /#change -->

					<div id="id" class="form-group">
						<label for="inputId" class="col-sm-2 control-label">id</label>
						<div class="col-sm-10">
							<input name="id" type="text" value="<?php echo $id; ?>" readonly="readonly" class="form-control" />
						</div>
					</div><!-- /#id -->

					<div id="fileName" class="form-group">
						<label for="inputFileName" class="col-sm-2 control-label">ファイル名</label>
						<div class="col-sm-10">
							<input name="fileName" type="text" value="<?php echo $fileName; ?>" readonly="readonly" class="form-control" />
						</div>
					</div><!-- /#fileName -->

					<div id="fileOriginal" class="form-group">
						<label for="inputFileOriginal" class="col-sm-2 control-label">元ファイル名</label>
						<div class="col-sm-10">
							<input name="fileOriginal" type="text" value="<?php echo $fileOriginal; ?>" readonly="readonly" class="form-control" />
						</div>
					</div><!-- /#fileOriginal -->

					<div id="contents" class="form-group">
						<label for="inputContents" class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10">
							<input name="contents" type="text" value="<?php echo $contents; ?>" class="form-control" disabled />
						</div>
					</div><!-- /#contents -->

					<div id="yYear" class="form-group">
						<label for="inputyYear" class="col-sm-2 control-label">年</label>
						<div class="col-sm-10">
							<select name="yYear" class="form-control" disabled>
								<option value="<?php echo $yYear; ?>"><?php echo $yYear; ?></option>
								<option value="0">不明</option>
								<?php
									$today = getdate();
									for ($i = 1930; $i <= $today['year']; $i++) { 
										print "<option value=\"{$i}\">{$i}</option>\n";
									}
								?>
							</select>
						</div>
					</div><!-- /#yYear -->

					<div id="mMonth" class="form-group">
						<label for="inputmMonth" class="col-sm-2 control-label">月</label>
						<div class="col-sm-10">
							<select name="mMonth" class="form-control" disabled>
								<option value="<?php echo $mMonth; ?>"><?php echo $mMonth; ?></option>
								<option value="0">不明</option>
								<?php
									for ($i = 1; $i <= 12; $i++) { 
										print "<option value=\"{$i}\">{$i}</option>\n";
									}
								?>
							</select>
						</div>
					</div><!-- /#mMonth -->

					<div id="dDay" class="form-group">
						<label for="inputdDay" class="col-sm-2 control-label">日</label>
						<div class="col-sm-10">
							<select name="dDay" class="form-control" disabled>
								<option value="<?php echo $dDay; ?>"><?php echo $dDay; ?></option>
								<option value="0">不明</option>
								<?php
									for ($i = 1; $i <= 31; $i++) { 
										print "<option value=\"{$i}\">{$i}</option>\n";
									}
								?>
							</select>
						</div>
					</div><!-- /#dDay -->

					<div id="section" class="form-group">
						<label for="inputSection1" class="col-sm-2 control-label">部門１</label>
						<div class="col-sm-10">
							<select id="sec1" name="sec1" class="form-control" disabled>
								<option value="0">--指定なし(選択してください)--</option>
								<?php
									foreach ($section1 as $sec1) {
										print "<option value=\"{$sec1['id']}\">{$sec1['sectionName']}</option>\n";
									}
								?>
							</select>
						</div>
						<label for="inputSection2" class="col-sm-2 control-label">部門２</label>
						<div class="col-sm-10">
							<select id="sec2" name="sec2" class="form-control" disabled>
								<option value="<?php echo $section; ?>"><?php echo $sec[$section]; ?></option>
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
						<div class="col-sm-10">
							<select id="cls1" name="cls1" class="form-control" disabled>
								<option value="0">--指定なし(選択してください)--</option>
								<?php
									foreach ($class1 as $cls1) {
										print "<option value=\"{$cls1['id']}\">{$cls1['className']}</option>\n";
									}
								?>
							</select>
						</div>
						<label for="inputClass2" class="col-sm-2 control-label">分類２</label>
						<div class="col-sm-10">
							<select id="cls2" name="cls2" class="form-control" disabled>
								<option value="<?php echo $class; ?>"><?php echo $cls[$class]; ?></option>
								<option value="0">--指定なし(選択してください)--</option>
								<?php
									foreach ($class2 as $cls2) {
										print "<option value=\"{$cls2['id']}\">{$cls2['className']}</option>\n";
									}
								?>
							</select>
						</div>
					</div><!-- /#class -->

					<div id="description" class="form-group">
						<label for="inputdDescription" class="col-sm-2 control-label">説明</label>
						<div class="col-sm-10">
							<textarea name="description" class="form-control" rows="3" disabled><?php echo $description; ?></textarea>
						</div>
					</div><!-- /#description -->

					<div id="submit" class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<a data-toggle="modal" href="#modalWindow"><button id="saveCButton" type="button" class="btn btn-default" disabled>保存</button></a>
							<span id="message"></span>
						</div>
					</div><!-- /#submit -->

				</form>
			</div><!-- /#photoForm -->

			<div id="photoDel">
				<h2>写真の削除</h2>
				<p class="text-center"><a data-toggle="modal" href="#modalWindow"><button id="delCButton" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> この写真を削除</button></a></p>
			</div><!-- /#photoDel -->
		</div>

		<div class="modal dade" id="modalWindow">
			<div class="modal-dialog">
				<div class="modal-content">

					<div id="mh" class="modal-header">

					</div><!-- /#mh -->


					<div id="mb" class="modal-body">

					</div><!-- /#mb -->

					<div id="mf" class="modal-footer">
						<button id="close" data-dismiss="modal" class="btn btn-default">キャンセル</button>
					</div><!-- /#mf -->

				</div>
			</div>
		</div><!-- /#modalWindow-->

		<footer class="footer">
			<div class="container">
				<p class="text-muted">Copyright (C) 2015 Keisuke Inoue. All Right Reserved.</p>
			</div>
		</footer>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery-1.11.3.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
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

				//変更ボタンをクリックするとdisabeld属性を除去
				$("#changeButton").click(function(){
					$('input').removeAttr("disabled");
					$('select').removeAttr("disabled");
					$('textarea').removeAttr("disabled");
					$("#saveCButton").removeAttr("disabled");
				});

				//saveCButtonのイベント
				$("#saveCButton").click(function(){
					$("#mh").html("<h3>変更の確認</h3>");
					$("#mb").html("<p>本当にこの写真の情報の変更を保存しますか？</p>");
					$("#mf").html("<button id=\"close\" data-dismiss=\"modal\" class=\"btn btn-default\">キャンセル</button><button class=\"btn btn-success\" onclick=\"save();\">保存</button>");
				});

				//delCButtonのイベント
				$("#delCButton").click(function(){
					$("#mh").html("<h3>削除の確認</h3>");
					$("#mb").html("<p>本当にこの写真を削除しますか？</p>");
					$("#mf").html("<button id=\"close\" data-dismiss=\"modal\" class=\"btn btn-default\">キャンセル</button><button class=\"btn btn-danger\" onclick=\"del();\">削除</button>");
				});

			});
		

			function save(){
				showLoadingImage();
				var form = $('#changeForm');
				var fd = new FormData(form[0]);
				$.ajax({
					url: "./php/changephoto.php",
					type: form.attr('method'),
					data: fd,
					processData: false,
					contentType: false,
					success: function(resposnse){
						hideLoadingImage();
						$("#mb").html(resposnse);
						$("#mf").html("<button id=\"close\" data-dismiss=\"modal\" class=\"btn btn-default\">とじる</button>");
					},
					error: function(xhr){
						alert(xhr.resposnseText);
					},
				});
			}

			function del(){
				showLoadingImage();
				var form = $('#changeForm');
				var fd = new FormData(form[0]);
				$.ajax({
					url: "./php/delphoto.php",
					type: form.attr('method'),
					data: fd,
					processData: false,
					contentType: false,
					success: function(resposnse){
						hideLoadingImage();
						$("#mb").html(resposnse);
						$("#mf").html("<button id=\"close\" data-dismiss=\"modal\" class=\"btn btn-default\" onclick=\"window.close();\">とじる</button>");
					},
					error: function(xhr){
						alert(xhr.resposnseText);
					},
				});
			}

			// クルクル画像表示
			function showLoadingImage() {
				$("#mb").html('<img src="./images/gif-load.gif" class="img-responsive img-responsive-overwrite" />');
			}
			// クルクル画像消去
			function hideLoadingImage() {
				$("#mb").empty();
			}
		</script>
	</body>
</html>