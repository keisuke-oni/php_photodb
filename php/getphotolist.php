<?php
	header('Content-type: text/html; charset=utf-8');

	require("./db_setting.php");

	//1ページあたりの表示件数
	$ppr = 150; //5000件

	try {
		//connect
		$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//全分類を配列に格納
		$stmt = $db->query('select * from tblclass2');
		$class2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$cls = array();
		$cls[0] = "未登録";
		foreach ($class2 as $cls2) {
			$cls[$cls2['id']] = $cls2['className'];
		}

		//全部門を配列に格納
		$stmt2 = $db->query('select * from tblsection2');
		$section2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		$sec = array();
		$sec[0] = "未登録";
		foreach ($section2 as $sec2) {
			$sec[$sec2['id']] = $sec2['sectionName'];
		}

		if(!isset($_GET['cls1']) || !isset($_GET['cls2']) || !isset($_GET['sec1']) || !isset($_GET['sec2'])){
			print "データが不正です。やり直してください。\n";
			exit;
		}else{
			$cls1 = $_GET['cls1'];
			$cls2 = $_GET['cls2'];
			$sec1 = $_GET['sec1'];
			$sec2 = $_GET['sec2'];

			$sortF = $_GET['sort'];
			$orderF = $_GET['order'];

			$crtPage = $_GET['p'];

			if($sortF == 0){
				//登録順
				$sort = "id";
			}elseif($sortF == 1){
				//西暦順
				$sort = "yYear";
			}

			if($orderF == 0){
				//昇順
				$order = "ASC";
			}elseif($orderF == 1){
				//降順
				$order = "DESC";
			}

			$sortOrder = " ORDER BY " . $sort . " ". $order;

			if($cls1 == 0 && $cls2 == 0 && $sec1 == 0 && $sec2 == 0){
				//全件抽出
				$sql = 'select * from tblpicture' . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif(($cls1 == 0 && $cls2 == 0 && $sec1 == 0 && $sec2 != 0) || ($cls1 == 0 && $cls2 == 0 && $sec1 != 0 && $sec2 != 0)){
				//sec2
				$sql = 'select * from tblpicture where section = ' . $sec2 . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif($cls1 == 0 && $cls2 == 0 && $sec1 != 0 && $sec2 == 0){
				//ORsec1
				$orsec1 = makeOrSection2($sec1);
				$sql = 'select * from tblpicture where ' . $orsec1 . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif(($cls1 == 0 && $cls2 != 0 && $sec1 == 0 && $sec2 == 0) || ($cls1 != 0 && $cls2 != 0 && $sec1 == 0 && $sec2 == 0)){
				//cls2
				$sql = 'select * from tblpicture where class = ' . $cls2 . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif(($cls1 == 0 && $cls2 != 0 && $sec1 == 0 && $sec2 != 0) || ($cls1 == 0 && $cls2 != 0 && $sec1 != 0 && $sec2 != 0) || ($cls1 != 0 && $cls2 != 0 && $sec1 == 0 && $sec2 != 0) || ($cls1 != 0 && $cls2 != 0 && $sec1 != 0 && $sec2 != 0)){
				//cls2 AND sec2
				$sql = 'select * from tblpicture where class = ' . $cls2 . ' AND section = ' . $sec2 . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif(($cls1 == 0 && $cls2 != 0 && $sec1 != 0 && $sec2 == 0) || ($cls1 != 0 && $cls2 != 0 && $sec1 != 0 && $sec2 == 0)){
				//cls2 AND ORsec1
				$orsec1 = makeOrSection2($sec1);
				$sql = 'select * from tblpicture where class = ' . $cls2 . ' AND ( ' . $orsec1 . ')' . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif($cls1 != 0 && $cls2 == 0 && $sec1 == 0 && $sec2 == 0){
				//ORcls1
				$orcls1 = makeOrClass2($cls1);
				$sql = 'select * from tblpicture where ' . $orcls1 . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif(($cls1 != 0 && $cls2 == 0 && $sec1 == 0 && $sec2 != 0) || ($cls1 != 0 && $cls2 == 0 && $sec1 != 0 && $sec2 != 0)){
				//ORcls1 AND sec2
				$orcls1 = makeOrClass2($cls1);
				$sql = 'select * from tblpicture where ('. $orcls1 . ') AND section = ' . $sec2 . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}elseif($cls1 != 0 && $cls2 == 0 && $sec1 != 0 && $sec2 == 0){
				//ORcls1 AND ORsec1
				$orcls1 = makeOrClass2($cls1);
				$orsec1 = makeOrSection2($sec1);
				$sql = 'select * from tblpicture where ('. $orcls1 . ') AND (' . $orsec1 . ')' . $sortOrder;
				$stmt = $db->query($sql);
//				$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);


			}else{
				print "データが不正です。やり直してください。\n";
				exit;
			}
		}
		//レコード件数の表示
		$rowCount = $stmt->rowCount();

		print "<p class=\"lead\">" . $rowCount . " 件見つかりました。</p>\n";
		print "<p class=\"lead\">" . $crtPage . " ページ目を表示しています。</p>\n";

		if($rowCount % $ppr == 0){
			$pageNumber = floor($rowCount / $ppr);
		}else{
			$pageNumber = floor($rowCount / $ppr) + 1;
		}

		$limit = " LIMIT " . ($crtPage - 1) * $ppr . ", " . $ppr;
		$query = $sql . $limit;
		$stmt = $db->query($query);
		$picture = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$rowCount2 = $stmt->rowCount();
		print "<p class=\"lead\">" . $rowCount2 . " 件表示中</p>\n";

		//disconnect
		$db = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}

	$prev = (int)$crtPage - 1;
	$next = (int)$crtPage + 1;

	//ページネーション
	print "<ul class=\"pagination\">\n";
	if($crtPage == 1){
		print "<li class=\"disabled\"><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$prev}\">«</a></li>\n";
	}else{
	print "<li><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$prev}\">«</a></li>\n";
	}
	for ($i=1; $i <= $pageNumber ; $i++) {
		if($i == $crtPage){
			print "<li class=\"active\"><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$i}\">{$i}</a></li>\n";
		}else{
			print "<li><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$i}\">{$i}</a></li>\n";
		}
	}

	if($crtPage == $pageNumber){
		print "<li class=\"disabled\"><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$next}\">»</a></li>\n";
	}else{
	print "<li><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$next}\">»</a></li>\n";
	}
	print "</ul>\n";
	//ページネーション終わり	

	//データ書き出し
	print "<div class=\"table-responsive\">\n";
	print "<table class=\"table table-hover\">\n";
	print "<thead>\n";
	print "<tr>\n";
	print "<th></th>\n";
	print "<th></th>\n";
//	print "<th>サムネイル</th>\n";
	print "<th>ファイル名</th>\n";
	print "<th>元ファイル名</th>\n";
	print "<th>内容</th>\n";
	print "<th>年</th>\n";
	print "<th>月</th>\n";
	print "<th>日</th>\n";
	print "<th>部門</th>\n";
	print "<th>分類</th>\n";
	print "<th>説明</th>\n";
	print "</tr>\n";
	print "</thead>\n";
	print "<tbody>\n";
	foreach ($picture as $pic) {
		print "<tr>\n";
		print "<td><a href=\"./detail.php?id={$pic["id"]}\" target=\"_blank\"><span class=\"glyphicon glyphicon-info-sign\" aria-hidden=\"true\"></span></a></td>";
		print "<td><a href=\"./php/downphoto.php?id={$pic["id"]}\" target=\"_blank\"><span class=\"glyphicon glyphicon-download-alt\" aria-hidden=\"true\"></span></a></td>\n";
//		print "<td><img data-layzr=\"./php/getthumb.php?id={$pic["id"]}&width=100\" /></td>\n";
		print "<td><a class=\"gallery\" href=\"./photos/{$pic["fileName"]}\" title=\"{$pic["fileName"]}\">{$pic["fileName"]}<br /><img data-original=\"./php/getthumb.php?id={$pic["id"]}&width=100\" class=\"lazy\" /></a></td>\n";
		print "<td>{$pic["fileOriginal"]}</td>\n";
		print "<td>{$pic["contents"]}</td>\n";
		print "<td>{$pic["yYear"]}</td>\n";
		print "<td>{$pic["mMonth"]}</td>\n";
		print "<td>{$pic["dDay"]}</td>\n";
		print "<td>{$sec[$pic["section"]]}</td>\n";
		print "<td>{$cls[$pic["class"]]}</td>\n";
		print "<td>{$pic["description"]}</td>\n";
		print "</tr>\n";
	}
	print "</tbody>\n";
	print "</table>\n";
	print "</div><!-- /.table-responsive -->\n";

		//ページネーション
	print "<ul class=\"pagination\">\n";
	if($crtPage == 1){
		print "<li class=\"disabled\"><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$prev}\">«</a></li>\n";
	}else{
	print "<li><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$prev}\">«</a></li>\n";
	}
	for ($i=1; $i <= $pageNumber ; $i++) {
		if($i == $crtPage){
			print "<li class=\"active\"><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$i}\">{$i}</a></li>\n";
		}else{
			print "<li><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$i}\">{$i}</a></li>\n";
		}
	}

	if($crtPage == $pageNumber){
		print "<li class=\"disabled\"><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$next}\">»</a></li>\n";
	}else{
	print "<li><a href=\"./showresult.php?sec1={$sec1}&sec2={$sec2}&cls1={$cls1}&cls2={$cls2}&sort={$sortF}&order={$orderF}&p={$next}\">»</a></li>\n";
	}
	print "</ul>\n";
	//ページネーション終わり	

function makeOrClass2 ($cls1){

	try {
			//connect
			$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $db->query('select id from tblclass2 where parentClass = '. $cls1);
			$class2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$count = 0;
			$result = "";
			foreach ($class2 as $cls2 ) {
				if($count != 0){
				$result .= " OR ";
				}
				$result .= "class = {$cls2['id']}";
				$count++;
			}

			//disconnect
			$db = null;

		} catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}

	return $result;
}

function makeOrSection2 ($sec1){

	try {
			//connect
			$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $db->query('select id from tblsection2 where parentSection = '. $sec1);
			$section2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$count = 0;
			$result = "";
			foreach ($section2 as $sec2 ) {
				if($count != 0){
				$result .= " OR ";
				}
				$result .= "section = {$sec2['id']}";
				$count++;
			}

			//disconnect
			$db = null;

		} catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}

	return $result;
}

?>