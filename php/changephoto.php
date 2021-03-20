<?php
	header('Content-type: text/html; charset=utf-8');

	require("./db_setting.php");

	if(!isset($_POST["id"])){
		print "データが不正です。やり直してください。\n";
		exit;
	}
	$id = $_POST["id"];

	$contents = $_POST['contents'];
	$yYear = $_POST['yYear'];
	$mMonth = $_POST['mMonth'];
	$dDay = $_POST['dDay'];
	$section = $_POST['sec2'];
	$class = $_POST['cls2'];
	$description = $_POST['description'];

	try {
		//connect
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmtPhoto = $db->prepare("update tblpicture set contents = ?, yYear = ?, mMonth = ?, dDay = ?, section = ?, class = ?, description = ? where id = ?");
		$stmtPhoto->bindValue(1, $contents, PDO::PARAM_STR);
		$stmtPhoto->bindValue(2, $yYear, PDO::PARAM_INT);
		$stmtPhoto->bindValue(3, $mMonth, PDO::PARAM_INT);
		$stmtPhoto->bindValue(4, $dDay, PDO::PARAM_INT);
		$stmtPhoto->bindValue(5, $section, PDO::PARAM_INT);
		$stmtPhoto->bindValue(6, $class, PDO::PARAM_INT);
		$stmtPhoto->bindValue(7, $description, PDO::PARAM_STR);
		$stmtPhoto->bindValue(8, $id, PDO::PARAM_INT);
		$stmtPhoto->execute();

		print "<p>この写真の情報の変更を保存しました。</p>";

		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}

?>