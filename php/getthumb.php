<?php
	header('Content-type: image/jpeg');

	require("./db_setting.php");

	if(!isset($_GET["id"]) || !isset($_GET["width"])){
		print "データが不正です。やり直してください。\n";
		exit;
	}
	$id = $_GET["id"];
	$width = $_GET["width"];

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
		$originalFilePath = "../images/file_error.jpg";
	}else{
		$originalFilePath = $filePath;
	}

	//元画像の縦横サイズを取得
	list($image_w, $image_h) = getimagesize($originalFilePath);

	//元画像の比率を計算し、高さを設定
	$proportion = $image_w / $image_h;
	$height = $width / $proportion;

	// サイズを指定して、背景用画像を生成
	$canvas = imagecreatetruecolor($width, $height);

	// ファイル名から、画像インスタンスを生成
	$image = imagecreatefromstring(file_get_contents($originalFilePath));

	// 背景画像に、画像をコピーする
	imagecopyresampled($canvas,  // 背景画像
		$image,   // コピー元画像
		0,        // 背景画像の x 座標
		0,       // 背景画像の y 座標
		0,        // コピー元の x 座標
		0,        // コピー元の y 座標
		$width,   // 背景画像の幅
		$height,  // 背景画像の高さ
		$image_w, // コピー元画像ファイルの幅
		$image_h  // コピー元画像ファイルの高さ
	);

	// 画像を出力する
	imagejpeg($canvas,           // 背景画像
		NULL,    // 出力するファイル名(NULLは直接表示)
		50                // 画像精度（この例だと100%で作成）
	);

	// メモリを開放する
	imagedestroy($image); //元画像
	imagedestroy($canvas); //サムネイル画像

	exit;

?>