<?php
    $searchTitle = "";
    $searchBody = "";
    if(isset($_POST['searchTitle'])){
        $searchTitle = $_POST['searchTitle'];
    }
    if(isset($_POST['searchBody'])){
        $searchBody = $_POST['searchBody'];
    }
    $order = $_POST['order'];
    
    // PDO用の定数と変数宣言
    define('DB_DSN', 'mysql:dbname=report_project;host=localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        // SQLエラーの表示設定
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 報告書取得
        $sortReportSql = "SELECT * FROM reports WHERE title like :title AND body like :body $order";
        $sortReportStmt = $dbh->prepare($sortReportSql);
        $sortReportStmt -> execute(array(
                                    ':title'=>'%'.$searchTitle.'%',
                                    ':body'=>'%'.$searchBody.'%'
                                    ));
        $sortReportResult = $sortReportStmt->fetchAll();

        echo json_encode($sortReportResult);
    } catch (PDOException $e) {
        echo 'DB接続エラー！: ' . $e->getMessage();
    }
    // PDO開放
    $dbh = null;