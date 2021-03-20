<?php
	header('Content-type: text/html; charset=utf-8');

	require("./db_setting.php");

	try {
		//connect
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if(!isset($_GET['cls1'])){
			$stmt = $db->query('select * from tblclass2');
		}else{
			$getParentClass = $_GET['cls1'];
			if($getParentClass == 0){
				$stmt = $db->query('select * from tblclass2');
			}else{
				$stmt = $db->prepare("select * from tblclass2 where parentClass = ?");
				$stmt->execute([$getParentClass]);
			}
		}
		$class2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
			print "<option value=\"0\">--指定なし(選択してください)--</option>\n";
		foreach ($class2 as $cls2) {
			print "<option value=\"{$cls2['id']}\">{$cls2['className']}</option>\n";
		}
		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}
?>