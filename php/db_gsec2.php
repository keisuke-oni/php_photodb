<?php
	header('Content-type: text/html; charset=utf-8');

	require("./db_setting.php");

	try {
		//connect
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if(!isset($_GET['sec1'])){
			$stmt = $db->query('select * from tblsection2');
		}else{
			$getParentSection = $_GET['sec1'];
			if($getParentSection == 0){
				$stmt = $db->query('select * from tblsection2');
			}else{
				$stmt = $db->prepare("select * from tblsection2 where parentSection = ?");
				$stmt->execute([$getParentSection]);
			}
		}
		$section2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
			print "<option value=\"0\">--指定なし(選択してください)--</option>\n";
		foreach ($section2 as $sec2) {
			print "<option value=\"{$sec2['id']}\">{$sec2['sectionName']}</option>\n";
		}
		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}
?>