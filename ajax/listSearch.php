<?php
    $searchTitle = "";
    $searchBody = "";
    if(isset($_POST['searchTitle'])){
        $searchTitle = $_POST['searchTitle'];
    }
    if(isset($_POST['searchBody'])){
        $searchBody = $_POST['searchBody'];
    }

    include(dirname(__FILE__).'/../assets/_inc/const.php');
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        // SQLエラーの表示設定
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 報告書取得
        $searchReportSql = "SELECT * FROM reports WHERE title like :title AND body like :body ORDER BY 'created_at'";
        $searchReportStmt = $dbh->prepare($searchReportSql);
        $searchReportStmt -> execute(array(
                                    ':title'=>'%'.$searchTitle.'%',
                                    ':body'=>'%'.$searchBody.'%'
                                    ));
        $searchReportResult = $searchReportStmt->fetchAll();

        echo json_encode($searchReportResult);
    } catch (PDOException $e) {
        echo 'DB接続エラー！: ' . $e->getMessage();
    }
    // PDO開放
    $dbh = null;