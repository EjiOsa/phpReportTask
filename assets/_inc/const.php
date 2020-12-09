<?php
    // PDO用の定数と変数宣言
    date_default_timezone_set('Asia/Tokyo');
    $date = date('Y-m-d H:i:s');
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

    // local用
    const DB_DSN = 'mysql:dbname=report_project;host=localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = 'root';

    // sakura用
    // const DB_DSN = 'mysql:dbname=mt-compass_guest;host=mysql634.db.sakura.ne.jp';
    // const DB_USER = 'mt-compass';
    // const DB_PASSWORD = 'wWgWSCnW2-Ue';