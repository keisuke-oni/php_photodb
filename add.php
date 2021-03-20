<?php
	require("./php/db_setting.php");
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>写真を登録する | 写真データベース</title>
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="css/custom.css" rel="stylesheet">
		<style type="text/css">
			input[type="file"] {
				display: none;
			}

			#drag-area {
				padding: 10px;
				border: 2px dashed #ccc;
			}
		</style>
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
						<li><a href="search.php">写真を探す</a></li>
						<li class="active"><a href="add.php">写真を登録する</a></li>
					</ul>

				</div><!-- /#navbar -->
			</div><!-- /.container-fluid -->
		</nav>

		<!-- Begin page content -->
		<div class="container">
			<div class="page-header">
				<h1>写真を登録する</h1>
			</div>
<?php
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
			<p class="lead">写真を登録するには、下の入力フォームに写真の情報を入力して「次へ」をクリックしてください。</p>
			<div id="photoForm">
				<h2>写真の情報</h2>
				<form id="addForm" action="./php/addphoto.php" method="POST" class="form-horizontal" enctype="multipart/form-data">

					<div id="contents" class="form-group">
						<label for="inputContents" class="col-sm-2 control-label">内容</label>
						<div class="col-sm-10">
							<input name="contents" type="text" value="" class="form-control" />
						</div>
					</div><!-- /#contents -->

					<div id="yYear" class="form-group">
						<label for="inputyYear" class="col-sm-2 control-label">年</label>
						<div class="col-sm-10">
							<select name="yYear" class="form-control">
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
							<select name="mMonth" class="form-control">
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
							<select name="dDay" class="form-control">
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
						<div class="col-sm-10">
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
						<div class="col-sm-10">
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
						<div class="col-sm-10">
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

					<div id="description" class="form-group">
						<label for="inputdDescription" class="col-sm-2 control-label">説明</label>
						<div class="col-sm-10">
							<textarea name="description" class="form-control" rows="3"></textarea>
						</div>
					</div><!-- /#description -->

					<div id="nextButton" class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<a data-toggle="modal" href="#fileSelect" onclick="$('#result').empty();";><button id="nextButton" type="button" class="btn btn-default">次へ</button></a>
						</div>
					</div><!-- /#submit -->

					<div class="modal dade" id="fileSelect">
						<div class="modal-dialog">
							<div class="modal-content">

								<div id="mh" class="modal-header">
									<h3>写真ファイルを選択する</h3>
								</div><!-- /#mh -->


								<div id="mb" class="modal-body">
									<div id="drag-area" class="text-center">
										<p>一度にアップロードできる写真は20枚までです。(PNG,JPG,GIF,BMPに対応)</p>
										<p><span class="glyphicon glyphicon-cloud-upload" aria-hidden="true" style="font-size: 100px;"></span></p>
										<p class="lead">アップロードするファイルをドロップ</p>
										<p>または</p>
										<div>
											<input id="fileInput" type="file" multiple="multiple"　accept="image/*" />
											<button id="selectBtn" class="btn btn-default" onclick="return false;">ファイルを選択</button>
										</div>
									</div>
									<div id="result">

									</div>
								</div><!-- /#mb -->

								<div id="mf" class="modal-footer">
									<button id="close" data-dismiss="modal" class="btn btn-default">キャンセル</button>
								</div><!-- /#mf -->

							</div>
						</div>
					</div><!-- /#fileSelect -->

				</form>
			</div><!-- /#photoForm -->

		</div>

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

			/*================================================
				ファイルをドロップした時の処理
			=================================================*/
			$('#drag-area').bind('drop', function(e){
				// デフォルトの挙動を停止
				e.preventDefault();
		 
				// ファイル情報を取得
				var files = e.originalEvent.dataTransfer.files;
		 
				uploadFiles(files);
		 
			}).bind('dragenter', function(){
				// デフォルトの挙動を停止
				return false;
			}).bind('dragover', function(){
				// デフォルトの挙動を停止
				return false;
			});
		 
			/*================================================
				ダミーボタンを押した時の処理
			=================================================*/
			$('#selectBtn').click(function() {
				// ダミーボタンとinput[type="file"]を連動
				$('input[type="file"]').click();

			});
		 
			$('input[type="file"]').change(function(){
				// ファイル情報を取得
				var files = this.files;
		 
				uploadFiles(files);
			});

			 
			/*================================================
				アップロード処理
			=================================================*/
			function uploadFiles(files) {
				$("#result").html("<p>写真をアップロード中です。</p>");
				var form = $('#addForm');
				// FormDataオブジェクトを用意
				var fd = new FormData(form[0]);
			 
				// ファイルの個数を取得
				var filesLength = files.length;

				console.log(filesLength);
			 
				if(filesLength > 20){
					alert("写真ファイルが20枚を超えています。")

				}else{

 					// ファイル情報を追加
					for (var i = 0; i < filesLength; i++) {
						fd.append("files[]", files[i]);
					}

					fd.append("filesLength", filesLength);

					// Ajaxでアップロード処理をするファイルへ内容渡す
					$.ajax({
						url: form.attr('action'),
						type: form.attr('method'),
						data: fd,
						processData: false,
						contentType: false,
						success: function(data) {
							console.log('写真がアップロードされました。');
							$("#result").html(data);

						},
						error: function(xhr){
							alert(xhr.resposnseText);
						},
					});
				}
			}

			});
		</script>
	</body>
</html>