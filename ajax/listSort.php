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
    
    include(__DIR__.'/../assets/_inc/const.php');
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