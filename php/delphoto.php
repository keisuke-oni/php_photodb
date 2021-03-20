<?php
	header('Content-type: text/html; charset=utf-8');

	require("./db_setting.php");

	if(!isset($_POST["id"])){
		print "データが不正です。やり直してください。\n";
		exit;
	}
	$id = $_POST["id"];

	try {
		//connect
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmtPhoto = $db->prepare("delete from tblpicture where id = ?");
		$stmtPhoto->execute([$id]);
		print "<p>この写真を削除しました。</p>";

		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}

?>