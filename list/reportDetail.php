<?php
    session_start();
    if(!isset($_SESSION['id']) || !isset($_SESSION['name'])){
        $_SESSION["login_message"] = "報告書詳細の閲覧にはログインが必要です。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/management/loginForm');
        exit();
    }

    $id = $_GET["detail"];
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
        $selectDetailSql = "SELECT * FROM reports WHERE id = :id";
        $selectDetailStmt = $dbh->prepare($selectDetailSql);
        $selectDetailStmt -> execute(array(
                            ':id'=>$id
                            ));
        $selectDetailResult = $selectDetailStmt->fetch();

        // 添付情報取得
        $selectAttachmentSql = "SELECT * FROM attachments WHERE parent_id = :id";
        $selectAttachmentStmt = $dbh->prepare($selectAttachmentSql);
        $selectAttachmentStmt -> execute(array(
            ':id'=>$id
            ));
        $selectAttachmentResult = $selectAttachmentStmt->fetchAll();
    } catch (PDOException $e) {
        echo 'DB接続エラー！: ' . $e->getMessage();
    }
    // PDO開放
    $dbh = null;

    // 以下、共通パーツ定数
    define("TITLE" ,"Report Detail");
    define("NOT_SHOW_AUTH" ,"Detail");
    $path = "..";
?>
<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

    <div class="container">
        <div class="row">
            <h1 class="display-5 text-secondary font-weight-lighter">
                Report Detail
            </h1>
        </div>

        <h2 class="row justify-content-center">報告書</h2>
        <div class="container">
            <div class='row'>
                <div class="col-9"></div>
                <label class="col-1 align-items-end">登録日時:</label>
                <p class="text-justify"><?php echo date('Y/m/d H:i', strtotime($selectDetailResult['created_at'])) ?></p>
            </div>

            <div class='row'>
                <label class="col-2 align-items-end">報告書タイトル:</label>
                <p class="text-justify font-weight-bold h3"><?php echo $selectDetailResult['title'] ?></p>
            </div>

            <hr class="my-1">
            <div class='row'>
                <label class="col-2 mt-4">報告書内容:</label>
                <p class="text-justify mt-4 text-break"><?php echo nl2br($selectDetailResult['body']) ?></p>
            </div>

            <hr class="my-5">
            <?php if($selectAttachmentResult) : ?>
                <?php foreach($selectAttachmentResult as $attachment) :?>
                    <form action="download" method="POST">
                    <div class="row">
                        <div class="d-flex align-items-center col-3">ファイル名：<?php echo $attachment["file_name"] ?></div>
                        <a class="col-2 align-items-center d-flex align-items-center" href="<?php echo $attachment["file_path"] ?>" target="_blank">添付ファイルを表示</a>
                        <div><a class="btn btn-outline-secondary" role="button" href="<?php echo $attachment['file_path'] ?>" download="<?php echo $attachment["file_name"] ?>"><?php echo $attachment["file_name"] ?>をダウンロード</a></div>
                    </div>
                    <br>
                    </form>
                <?php endforeach; ?>
            <?php endif; ?>
            <br>

            <div class='row'>
                <div class='col-9'></div>
                <label class="col-1">報告者:</label>
                <p class="text-justify font-weight-bold text-center h5"><?php echo $selectDetailResult['user_name'] ?></p>
            </div>
        </div>
        <div class="links">
            <a href="#" onClick="window.close();" role="button" class="links btn btn-outline-info btn-lg">Close</a>
        </div>
    </div>
</body>
</html>