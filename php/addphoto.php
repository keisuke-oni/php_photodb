<?php
	header('Content-type: text/html; charset=utf-8');

	require("./db_setting.php");

	$contents = $_POST['contents'];
	$yYear = $_POST['yYear'];
	$mMonth = $_POST['mMonth'];
	$dDay = $_POST['dDay'];
	$section = $_POST['sec2'];
	$class = $_POST['cls2'];
	$description = $_POST['description'];

	$filesLength = $_POST['filesLength'];

	for ($i = 0; $i < $filesLength ; $i++) { 
		$fileOriginal = $_FILES['files']['name'][$i];

		try {
			//connect
			$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			//挿入するidを求める→idの最大値+1
			$stmt = $db->query('select max(id) from tblpicture');
			$getIdMax = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$getId = intval($getIdMax[0]['max(id)']);
			$id = $getId + 1;
			$fileNameId = sprintf("%010d", $id);

			//写真の保存

			try {
				//拡張子の取得
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				if (!$ext = array_search(
					$finfo->file($_FILES['files']['tmp_name'][$i]),
					array(
						'GIF' => 'image/gif',
						'JPG' => 'image/jpeg',
						'PNG' => 'image/png',
						'BMP' => 'image/bmp',
					),
					true
				)) {
					throw new RuntimeException("({$fileOriginal})ファイル形式が不正です");
				}
				//保存する
				$fileName = $fileNameId . "." . $ext;
				if (!move_uploaded_file(
					$_FILES['files']['tmp_name'][$i],
					$path = sprintf("../photos/{$fileName}")
				)) {
					throw new RuntimeException("({$fileOriginal})ファイル保存時にエラーが発生しました");
				}
				//ファイルのパーミッションを確実に0644に設定する
				chmod($path, 0644);


			} catch (RuntimeException $e) {
				echo $e->getMessage();
			}

			$insertStmt = $db->prepare("insert into tblpicture (id, fileName, fileOriginal, contents, yYear, mMonth, dDay, section, class, description) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$insertStmt->bindValue(1, $id, PDO::PARAM_INT);
			$insertStmt->bindValue(2, $fileName, PDO::PARAM_STR);
			$insertStmt->bindValue(3, $fileOriginal, PDO::PARAM_STR);
			$insertStmt->bindValue(4, $contents, PDO::PARAM_STR);
			$insertStmt->bindValue(5, $yYear, PDO::PARAM_INT);
			$insertStmt->bindValue(6, $mMonth, PDO::PARAM_INT);
			$insertStmt->bindValue(7, $dDay, PDO::PARAM_INT);
			$insertStmt->bindValue(8, $section, PDO::PARAM_INT);
			$insertStmt->bindValue(9, $class, PDO::PARAM_INT);
			$insertStmt->bindValue(10, $description, PDO::PARAM_STR);
			$insertStmt->execute();

			print "<p>{$fileOriginal} -> <a href=\"./detail.php?id={$id}\" target=\"_blank\">{$fileName}</a></p>\n";

			//disconnect
			$db = null;

		} catch (PDOException $e) {
			print "<p>{$fileOriginal}アップロード中にエラーが発生しました。もう一度やり直してください。</p>\n";
//			echo $e->getMessage();
		}
	}

	print "<p>写真の登録が完了しました</p>\n";

?>