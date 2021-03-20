<?php
//データベースへの接続はこのphpファイルをrequireする
//	define('DB_HOSTNAME', 'localhost');
	define('DB_HOSTNAME', '127.0.0.1');
	define('DB_DATABASE', 'pm');
	define('DB_USERNAME', 'pm');
	define('DB_PASSWORD', 'pm1234');
	define('PDO_DSN', 'mysql:dbhost=' . DB_HOSTNAME . ';dbname='. DB_DATABASE);

?>