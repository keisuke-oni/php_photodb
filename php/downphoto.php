<?php
	require("./db_setting.php");

	if(!isset($_GET["id"])){
		print "データが不正です。やり直してください。\n";
		exit;
	}
	$id = $_GET["id"];

	try {
		//connect
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//GETで取得したidから写真の情報を取得
		$stmtPhoto = $db->prepare("select * from tblpicture where id = ?");
		$stmtPhoto->execute([$id]);
		$photo = $stmtPhoto->fetchAll(PDO::FETCH_ASSOC);
		//情報を変数に格納
		foreach ($photo as $pht) {
			$fileName = $pht['fileName'];
		}

		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}

	$filePath = "../photos/" . $fileName;
	//ファイルの存在チェック
	if(!file_exists($filePath)){
		print "写真ファイルが存在しません。idを確認して下さい。\n";
		exit;
	}

	$fileLength = filesize($filePath);
	header("Content-Disposition: attachment; filename=$fileName");
	header("Content-Length:$fileLength");
	header("Content-Type: application/force-download");
	readfile ($filePath);


?>